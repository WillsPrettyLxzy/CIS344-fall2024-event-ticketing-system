<?php include 'app.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/styles.css">
    <title>Event Ticketing</title>
</head>
<body>
    <header>
        <h1>Available Events</h1>
    </header>

    <main>
        <?php
        // Fetch available events
        $stmt = $pdo->query("SELECT * FROM events WHERE available_seats > 0");
        $events = $stmt->fetchAll();

        if ($events): ?>
            <table>
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Available Seats</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?= htmlspecialchars($event['event_name']); ?></td>
                            <td><?= htmlspecialchars($event['event_date']); ?></td>
                            <td><?= htmlspecialchars($event['event_location']); ?></td>
                            <td><?= $event['available_seats']; ?></td>
                            <td>
                                <form method="POST" action="app.php">
                                    <input type="hidden" name="event_id" value="<?= $event['event_id']; ?>">
                                    <button type="submit" name="reserve_ticket">Reserve Ticket</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No events available at the moment.</p>
        <?php endif; ?>
    </main>
</body>
</html>
