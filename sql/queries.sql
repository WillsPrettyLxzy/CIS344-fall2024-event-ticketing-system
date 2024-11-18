INSERT INTO events (event_name, event_date, event_location, available_seats) VALUES
('Avengers: Endgame', '2024-12-01', 'Cinema Hall A', 50),
('Inception', '2024-12-05', 'Cinema Hall B', 40),
('Interstellar', '2024-12-10', 'Cinema Hall C', 60);

INSERT INTO tickets (event_id, user_id, seat_number, payment_status) 
VALUES (event_id, user_id, seat_number, 'pending');
