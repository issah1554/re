ALTER TABLE payments
ADD COLUMN payment_date DATETIME NULL;

ALTER TABLE `payments` CHANGE `payment_method` `payment_method` ENUM('credit_card','bank_transfer','cash') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'cash';
ALTER TABLE `payments` CHANGE `status` `status` ENUM('pending','verified') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'pending';
ALTER TABLE `payments` CHANGE `payment_method` `payment_method` ENUM('credit_card','bank_transfer','cash','mobile_money') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'cash';
