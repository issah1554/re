START TRANSACTION;

-- Ensure that all changes are applied atomically
-- If any error occurs, the transaction can be rolled back

-- 2. Add the columns (without NOT NULL initially)
ALTER TABLE categories
ADD COLUMN created_by INT,
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- 5. Add the foreign key for categories
ALTER TABLE categories
ADD CONSTRAINT fk_categories_created_by 
FOREIGN KEY (created_by) REFERENCES users(id);

-- Add columns to the users table
ALTER TABLE users
ADD COLUMN phone VARCHAR(20) NOT NULL,
add COLUMN avatar VARCHAR(255),
ADD COLUMN gender ENUM('male', 'female'),
ADD COLUMN last_login DATETIME,
ADD COLUMN created_by INT,
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- 3. Add the foreign key constraint to the users table
ALTER TABLE users
ADD CONSTRAINT fk_users_created_by
FOREIGN KEY (created_by) REFERENCES users(id);

-- Apartment table
CREATE TABLE apartments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    number VARCHAR(20) NOT NULL UNIQUE,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT NOT NULL,
    owner_id INT NOT NULL,
    manager_id INT,
    tenant_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (owner_id) REFERENCES users(id),
    FOREIGN KEY (manager_id) REFERENCES users(id),
    FOREIGN KEY (tenant_id) REFERENCES users(id)
);

-- Create the assets table
CREATE TABLE assets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    apartment_id INT NOT NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    granted_at DATETIME,
    FOREIGN KEY (apartment_id) REFERENCES apartments(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Create the asset_items table
CREATE TABLE asset_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    asset_id INT NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    quantity INT DEFAULT 1,
    description TEXT,
    FOREIGN KEY (asset_id) REFERENCES assets(id)
);

ALTER TABLE payments
add COLUMN apartment_id INT NOT NULL,
add COLUMN created_by INT NOT NULL,
add COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
add COLUMN verified_by INT,
add COLUMN verified_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
add COLUMN status ENUM('pending', 'completed', 'failed') DEFAULT 'pending';

-- Add foreign keys to the payments table
ALTER TABLE payments
ADD CONSTRAINT fk_payments_apartment_id
FOREIGN KEY (apartment_id) REFERENCES apartments(id);

ALTER TABLE payments
ADD CONSTRAINT fk_payments_created_by
FOREIGN KEY (created_by) REFERENCES users(id);

ALTER TABLE payments
ADD CONSTRAINT fk_payments_verified_by
FOREIGN KEY (verified_by) REFERENCES users(id); 

COMMIT;