-- First, check if there's any pending data that needs to be migrated
INSERT INTO neocreds_transactions (user_id, amount, status, request_date, process_date)
SELECT 
    user_id,
    amount,
    status,
    created_at,
    processed_at
FROM neocreds_requests
WHERE NOT EXISTS (
    SELECT 1 
    FROM neocreds_transactions t 
    WHERE t.user_id = neocreds_requests.user_id 
    AND t.amount = neocreds_requests.amount 
    AND t.request_date = neocreds_requests.created_at
);

-- Drop the table after ensuring data is migrated
DROP TABLE IF EXISTS neocreds_requests; 