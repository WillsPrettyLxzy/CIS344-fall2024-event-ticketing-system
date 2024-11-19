USE ticket_sales;

DELETE FROM tickets WHERE event_id IN (SELECT event_id FROM events);

