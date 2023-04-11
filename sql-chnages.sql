CREATE TABLE purchase_returns LIKE purchases;

ALTER TABLE `purchase_returns`
ADD `purchase_return_note` text COLLATE 'utf8mb4_unicode_ci' NULL AFTER `invoice_status`;

ALTER TABLE `purchase_returns`
ADD `purchase_id` int(11) NULL AFTER `purchase_return_note`;

ALTER TABLE `purchase_transfers`
DROP FOREIGN KEY `purchase_transfers_ibfk_1`;

ALTER TABLE `purchase_invoices`
DROP FOREIGN KEY `purchase_invoices_ibfk_1`;
