USE ticket_sales;

INSERT INTO events (event_name, event_date, event_location, total_seats, available_seats) VALUES
('The Matrix Resurrections', '2024-12-15', 'Cinema Hall A', 100, 100),
('The Batman', '2024-12-18', 'Cinema Hall B', 120, 120),
('Dune Part Two', '2024-12-20', 'Cinema Hall C', 150, 150),
('Black Panther: Wakanda Forever', '2024-12-22', 'Cinema Hall D', 80, 80),
('Avatar: The Way of Water', '2024-12-25', 'Cinema Hall E', 200, 200),
('Spider-Man: No Way Home', '2024-12-28', 'Cinema Hall F', 90, 90);


-- DELETE FROM tickets WHERE event_id IN (SELECT event_id FROM events);

