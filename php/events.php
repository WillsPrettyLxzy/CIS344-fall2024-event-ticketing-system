<?php include 'app.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="event_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- For icons -->
    <title>Event Ticketing</title>
</head>
<body>
    <header>
        <div class="header-container">
            <h1>Available Events</h1>
            <a href="cart.php" class="view-cart-button">
                <i class="fas fa-shopping-cart"></i> View Cart
            </a>
        </div>
    </header>

    <main>
        <?php
        // Fetch available events
        $stmt = $pdo->query("SELECT * FROM events WHERE available_seats > 0");
        $events = $stmt->fetchAll();

        if ($events): ?>
            <div class="event-list">
                <?php foreach ($events as $event): ?>
                    <div class="event-card">
                        <div class="event-info">
                            <h2><?= htmlspecialchars($event['event_name']); ?></h2>
                            <p><strong>Date:</strong> <?= htmlspecialchars($event['event_date']); ?></p>
                            <p><strong>Location:</strong> <?= htmlspecialchars($event['event_location']); ?></p>
                            <p><strong>Available Seats:</strong> <?= $event['available_seats']; ?></p>
                        </div>
                        <form method="POST" action="app.php" class="reserve-form">
                            <input type="hidden" name="event_id" value="<?= $event['event_id']; ?>">
                            <button type="submit" name="reserve_ticket">Reserve Ticket</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No events available at the moment.</p>
        <?php endif; ?>
    </main>
</body>
</html>
