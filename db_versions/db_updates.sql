CREATE TABLE contracts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lease_type VARCHAR(50) NOT NULL,
    tenant_id INT NOT NULL,
    apartment_id INT NOT NULL,
    from_date DATETIME NOT NULL,
    to_date DATETIME NOT NULL,
    status ENUM('active', 'pending', 'terminated') NOT NULL,
    has_family ENUM('yes', 'no') NOT NULL,
    total_rent DECIMAL(14, 2) NOT NULL,    
    witness_first_name VARCHAR(100) NOT NULL,
    witness_last_name VARCHAR(100) NOT NULL,
    witness_phone VARCHAR(15) NOT NULL,
    FOREIGN KEY (tenant_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (apartment_id) REFERENCES apartments(id) ON DELETE CASCADE
);
ALTER TABLE `contracts` CHANGE `lease_type` `lease_type` ENUM('residence', 'business') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
