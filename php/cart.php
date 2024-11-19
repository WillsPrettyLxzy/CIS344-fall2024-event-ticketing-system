<?php
session_start();
require 'app.php'; // Include database connection and logic

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view your cart.");
}

$user_id = $_SESSION['user_id'];

// Handle ticket removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_ticket'])) {
    $ticket_id = $_POST['ticket_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM tickets WHERE ticket_id = :ticket_id AND user_id = :user_id");
        $stmt->execute(['ticket_id' => $ticket_id, 'user_id' => $user_id]);
        echo "Ticket removed from cart!";
        
        // Refresh the page to update the cart
        header("Location: cart.php");
        exit();
    } catch (Exception $e) {
        echo "Error removing ticket: " . $e->getMessage();
    }
}

// Handle checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    try {
        $stmt = $pdo->prepare("
            UPDATE tickets 
            SET payment_status = 'purchased'
            WHERE user_id = :user_id AND payment_status = 'pending'
        ");
        $stmt->execute(['user_id' => $user_id]);
        echo "Checkout successful! Your tickets are now confirmed.";
        
        // Refresh the page to reflect changes
        header("Location: cart.php");
        exit();
    } catch (Exception $e) {
        echo "Error during checkout: " . $e->getMessage();
    }
}

// Fetch user's reserved tickets
try {
    $stmt = $pdo->prepare("
        SELECT t.ticket_id, e.event_name, e.event_date, e.event_location, t.seat_number, t.payment_status
        FROM tickets t
        JOIN events e ON t.event_id = e.event_id
        WHERE t.user_id = :user_id
    ");
    $stmt->execute(['user_id' => $user_id]);
    $tickets = $stmt->fetchAll();
} catch (Exception $e) {
    die("Error fetching cart: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
</head>
<body>
    <h1>Your Cart</h1>
    <?php if (count($tickets) > 0): ?>
        <form method="POST" action="cart.php">
            <table border="1">
                <tr>
                    <th>Event Name</th>
                    <th>Event Date</th>
                    <th>Location</th>
                    <th>Seat Number</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?= htmlspecialchars($ticket['event_name']) ?></td>
                        <td><?= htmlspecialchars($ticket['event_date']) ?></td>
                        <td><?= htmlspecialchars($ticket['event_location']) ?></td>
                        <td><?= htmlspecialchars($ticket['seat_number']) ?></td>
                        <td><?= htmlspecialchars($ticket['payment_status']) ?></td>
                        <td>
                            <?php if ($ticket['payment_status'] === 'pending'): ?>
                                <form method="POST" action="cart.php">
                                    <input type="hidden" name="ticket_id" value="<?= $ticket['ticket_id'] ?>">
                                    <button type="submit" name="remove_ticket">Remove</button>
                                </form>
                            <?php else: ?>
                                <em>Purchased</em>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php if (array_search('pending', array_column($tickets, 'payment_status')) !== false): ?>
                <button type="submit" name="checkout">Checkout</button>
            <?php endif; ?>
        </form>
    <?php else: ?>
        <p>Your cart is empty!</p>
    <?php endif; ?>

    <a href="events.php">Back to Events</a>
</body>
</html>
