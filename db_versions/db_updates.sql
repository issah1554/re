ALTER TABLE contracts
ADD COLUMN contract_file text DEFAULT NULL,
ADD COLUMN created_at datetime DEFAULT NULL,
ADD COLUMN updated_at datetime DEFAULT NULL,