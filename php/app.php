<?php
// Start session at the beginning
session_start();

// Database connection
$host = 'localhost';
$dbname = 'ticket_sales';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Routes and logic for form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // User registration
    if (isset($_POST['register'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->execute(['username' => $username, 'email' => $email, 'password' => $password]);
            echo "Registration successful!";
        } catch (Exception $e) {
            die("Error during registration: " . $e->getMessage());
        }
    }

    // User login
    if (isset($_POST['login'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                echo "Login successful!";
            } else {
                echo "Invalid credentials!";
            }
        } catch (Exception $e) {
            die("Error during login: " . $e->getMessage());
        }
    }

    // Ticket reservation
    if (isset($_POST['reserve_ticket'])) {
        if (!isset($_SESSION['user_id'])) {
            die("You must be logged in to reserve a ticket.");
        }

        $user_id = $_SESSION['user_id'];
        $event_id = $_POST['event_id'];

        try {
            // Start transaction
            $pdo->beginTransaction();

            // Check seat availability
            $stmt = $pdo->prepare("SELECT available_seats FROM events WHERE event_id = :event_id");
            $stmt->execute(['event_id' => $event_id]);
            $event = $stmt->fetch();

            if (!$event || $event['available_seats'] <= 0) {
                throw new Exception("No seats available for this event.");
            }

            // Reserve the seat
            $stmt = $pdo->prepare(
                "INSERT INTO tickets (event_id, user_id, seat_number, payment_status) 
                 VALUES (:event_id, :user_id, :seat_number, 'pending')"
            );
            $seat_number = $event['available_seats']; // Last available seat
            $stmt->execute([
                'event_id' => $event_id,
                'user_id' => $user_id,
                'seat_number' => $seat_number,
            ]);

            // Update event availability
            $stmt = $pdo->prepare("UPDATE events SET available_seats = available_seats - 1 WHERE event_id = :event_id");
            $stmt->execute(['event_id' => $event_id]);

            // Commit transaction
            $pdo->commit();
            echo "Ticket reserved successfully! Your seat number is $seat_number.";
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Reservation failed: " . $e->getMessage());
        }
    }
}
?>
