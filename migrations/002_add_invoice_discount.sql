-- Add discount field to invoice
ALTER TABLE `invoice` ADD COLUMN `discount` DECIMAL(10,2) NOT NULL DEFAULT 0.00;
