ALTER TABLE apartments
ADD COLUMN manager_assigned_by INT NULL,
ADD COLUMN manager_assigned_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;

ALTER  TABLE apartments
ADD CONSTRAINT fk_manager_assigned_by FOREIGN KEY (manager_assigned_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `payments` 
CHANGE `status` `status` ENUM('pending','completed','failed','veried')  NULL DEFAULT 'pending';

ALTER TABLE `payments`
ADD COLUMN `payment_method` ENUM('credit_card', 'bank_transfer', 'cash') NULL DEFAULT 'credit_card',
ADD COLUMN `payment_details` TEXT NULL,
ADD COLUMN `from_date` TIMESTAMP NULL ,
ADD COLUMN `to_date` TIMESTAMP NULL;