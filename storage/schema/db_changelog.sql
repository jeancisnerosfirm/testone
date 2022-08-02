-- activity_logs

ALTER TABLE `activity_logs` CHANGE `type` `type` VARCHAR(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

-- addons

ALTER TABLE `addons` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, CHANGE `unique_identifier` `unique_identifier` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

ALTER TABLE `addons` ADD UNIQUE(`unique_identifier`);
ALTER TABLE `addons` DROP INDEX `unique_identifier`, ADD UNIQUE `addons_unique_identifier_unique` (`unique_identifier`);

-- admins

ALTER TABLE `admins` CHANGE `status` `status` VARCHAR(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active';

-- app_store_credentials

ALTER TABLE `app_store_credentials` CHANGE `has_app_credentials` `has_app_credentials` VARCHAR(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Yes', CHANGE `link` `link` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `company` `company` VARCHAR(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

-- app_transaction_infos
ALTER TABLE `app_transactions_infos` CHANGE `status` `status` VARCHAR(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending';

-- backups
ALTER TABLE `backups` CHANGE `name` `name` VARCHAR(31) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

-- banks
ALTER TABLE `banks` CHANGE `is_default` `is_default` VARCHAR(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No';

-- currencies
ALTER TABLE `currencies` DROP `allow_address_creation`;

ALTER TABLE `currencies` CHANGE `name` `name` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, CHANGE `symbol` `symbol` CHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, CHANGE `code` `code` VARCHAR(21) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, CHANGE `default` `default` VARCHAR(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0', CHANGE `exchange_from` `exchange_from` VARCHAR(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local', CHANGE `status` `status` VARCHAR(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active';

ALTER TABLE `currencies` ADD `allowed_wallet_creation` VARCHAR(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No' AFTER `exchange_from`, ADD `address` VARCHAR(91) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `allowed_wallet_creation`;

ALTER TABLE `currencies` ADD UNIQUE(`code`);
ALTER TABLE `currencies` DROP INDEX `code`, ADD UNIQUE `currencies_code_unique` (`code`);

-- currency_exchanges
ALTER TABLE `currency_exchanges` CHANGE `type` `type` VARCHAR(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, CHANGE `status` `status` VARCHAR(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;


-- deposits
ALTER TABLE `deposits` CHANGE `status` `status` VARCHAR(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

-- disputes
ALTER TABLE `disputes` CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `status` `status` VARCHAR(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Open';

-- dispute_discussions
ALTER TABLE `dispute_discussions` CHANGE `type` `type` VARCHAR(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;


-- document_verifications
ALTER TABLE `document_verifications` CHANGE `verification_type` `verification_type` VARCHAR(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `identity_type` `identity_type` VARCHAR(17) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `status` `status` VARCHAR(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending';

-- email_templates
ALTER TABLE `email_templates` CHANGE `type` `type` VARCHAR(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

-- fees_limits
ALTER TABLE `fees_limits` CHANGE `has_transaction` `has_transaction` VARCHAR(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

-- languages
ALTER TABLE `languages` CHANGE `default` `default` VARCHAR(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0', CHANGE `deletable` `deletable` VARCHAR(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Yes', CHANGE `status` `status` VARCHAR(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active';

-- merchants
ALTER TABLE `merchants` CHANGE `type` `type` VARCHAR(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, CHANGE `status` `status` VARCHAR(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Moderation';


-- merchant_groups
ALTER TABLE `merchant_groups` CHANGE `name` `name` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, CHANGE `is_default` `is_default` VARCHAR(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No';

-- merchant_payments
ALTER TABLE `merchant_payments` CHANGE `status` `status` VARCHAR(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Success';

-- pages
ALTER TABLE `pages` CHANGE `status` `status` VARCHAR(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active';

-- payment_methods
ALTER TABLE `payment_methods` CHANGE `status` `status` VARCHAR(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active';

DELETE FROM `payment_methods` WHERE `payment_methods`.`name` = '2Checkout';
DELETE FROM `payment_methods` WHERE `payment_methods`.`name` = 'BlockIo';

INSERT INTO `payment_methods` (`name`, `status`) VALUES ('Crypto', 'Active');

-- Payout Settings

ALTER TABLE `payout_settings` ADD `currency_id` INT(11) NULL DEFAULT NULL AFTER `email`, ADD `crypto_address` VARCHAR(191) NULL DEFAULT NULL AFTER `currency_id`;

-- preferences

ALTER TABLE `preferences` ADD UNIQUE(`field`);
ALTER TABLE `preferences` DROP INDEX `field`, ADD UNIQUE `preferences_field_unique` (`field`);

INSERT INTO `preferences` (`category`, `field`, `value`) VALUES ('preference', 'decimal_format_amount_crypto', '8');

-- request_payments
ALTER TABLE `request_payments` CHANGE `status` `status` VARCHAR(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

-- roles
ALTER TABLE `roles` CHANGE `name` `name` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, CHANGE `display_name` `display_name` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL, CHANGE `user_type` `user_type` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, CHANGE `customer_type` `customer_type` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, CHANGE `is_default` `is_default` VARCHAR(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No';

-- role_user
ALTER TABLE `role_user` CHANGE `user_type` `user_type` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

-- settings
INSERT INTO `settings` (`name`, `value`, `type`) VALUES
('allowed_wallets', NULL, 'general'),
('exchange_enabled_api', 'Disabled', 'currency_exchange_rate'),
('currency_converter_api_key', NULL, 'currency_exchange_rate'),
('exchange_rate_api_key', NULL, 'currency_exchange_rate');

-- tickets
ALTER TABLE `tickets` CHANGE `priority` `priority` VARCHAR(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Low';


-- ticket_replies
ALTER TABLE `ticket_replies` CHANGE `user_type` `user_type` VARCHAR(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;



-- transactions
ALTER TABLE `transactions` CHANGE `user_type` `user_type` VARCHAR(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'registered', CHANGE `status` `status` VARCHAR(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

-- transfers
ALTER TABLE `transfers` CHANGE `status` `status` VARCHAR(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;


-- users
ALTER TABLE `users` CHANGE `type` `type` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user';

-- user_details
ALTER TABLE `user_details` CHANGE `two_step_verification_type` `two_step_verification_type` VARCHAR(21) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'disabled';

-- wallets
ALTER TABLE `wallets` CHANGE `is_default` `is_default` VARCHAR(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No';

-- withdrawals
ALTER TABLE `withdrawals` CHANGE `status` `status` VARCHAR(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

-- Withdrawal Details

ALTER TABLE `withdrawal_details` ADD `crypto_address` VARCHAR(191) NULL DEFAULT NULL AFTER `email`;

-- Metas
INSERT INTO `metas` (`url`, `title`, `description`, `keywords`) VALUES ('deposit/payumoney_confirm', 'Deposit With PayUMoney', 'Deposit With PayUMoney', '');

-- transaction_types
ALTER TABLE `transaction_types` CHANGE `name` `name` VARCHAR(30) NOT NULL;
DELETE FROM `transaction_types` WHERE `transaction_types`.`name` = 'Crypto_Sent';
DELETE FROM `transaction_types` WHERE `transaction_types`.`name` = 'Crypto_Received';

-- permissions

DELETE FROM `permissions` WHERE `permissions`.`group` = 'Enable WooCommerce';
DELETE FROM `permissions` WHERE `permissions`.`group` = 'BlockIo Settings';
DELETE FROM `permissions` WHERE `permissions`.`group` = 'Crypto Transactions';

-- permission_role

-- woocommerce
DELETE FROM `permission_role` WHERE `permission_role`.`role_id` = 1 AND `permission_role`.`permission_id` = 165;
DELETE FROM `permission_role` WHERE `permission_role`.`role_id` = 1 AND `permission_role`.`permission_id` = 166;
DELETE FROM `permission_role` WHERE `permission_role`.`role_id` = 1 AND `permission_role`.`permission_id` = 167;
DELETE FROM `permission_role` WHERE `permission_role`.`role_id` = 1 AND `permission_role`.`permission_id` = 168;

-- block.io settings
DELETE FROM `permission_role` WHERE `permission_role`.`role_id` = 1 AND `permission_role`.`permission_id` = 169;
DELETE FROM `permission_role` WHERE `permission_role`.`role_id` = 1 AND `permission_role`.`permission_id` = 170;
DELETE FROM `permission_role` WHERE `permission_role`.`role_id` = 1 AND `permission_role`.`permission_id` = 171;
DELETE FROM `permission_role` WHERE `permission_role`.`role_id` = 1 AND `permission_role`.`permission_id` = 172;

-- crypto_transactions
DELETE FROM `permission_role` WHERE `permission_role`.`role_id` = 1 AND `permission_role`.`permission_id` = 173;
DELETE FROM `permission_role` WHERE `permission_role`.`role_id` = 1 AND `permission_role`.`permission_id` = 174;
DELETE FROM `permission_role` WHERE `permission_role`.`role_id` = 1 AND `permission_role`.`permission_id` = 175;
DELETE FROM `permission_role` WHERE `permission_role`.`role_id` = 1 AND `permission_role`.`permission_id` = 176;


INSERT INTO `permissions` (`id`, `group`, `name`, `display_name`, `description`, `user_type`, `created_at`, `updated_at`) VALUES
(165, 'Admin Security', 'view_admin_security', 'View Admin Security', 'View Admin Security', 'Admin', '2022-03-16 23:29:09', '2022-03-16 23:29:09'),
(166, 'Admin Security', 'add_admin_security', NULL, NULL, 'Admin', '2022-03-16 23:29:09', '2022-03-16 23:29:09'),
(167, 'Admin Security', 'edit_admin_security', NULL, NULL, 'Admin', '2022-03-16 23:29:09', '2022-03-16 23:29:09'),
(168, 'Admin Security', 'delete_admin_security', NULL, NULL, 'Admin', '2022-03-16 23:29:09', '2022-03-16 23:29:09'),
(169, 'Notification Type', 'view_notification_type', 'View Notification Type', 'View Notification Type', 'Admin', '2022-03-16 23:29:09', '2022-03-16 23:29:09'),
(170, 'Notification Type', 'add_notification_type', NULL, NULL, 'Admin', '2022-03-16 23:29:09', '2022-03-16 23:29:09'),
(171, 'Notification Type', 'edit_notification_type', 'Edit Notification Type', 'Edit Notification Type', 'Admin', '2022-03-16 23:29:09', '2022-03-16 23:29:09'),
(172, 'Notification Type', 'delete_notification_type', NULL, NULL, 'Admin', '2022-03-16 23:29:09', '2022-03-16 23:29:09'),
(173, 'Notification Setting', 'view_notification_setting', 'View Notification Setting', 'View Notification Setting', 'Admin', '2022-03-16 23:29:09', '2022-03-16 23:29:09'),
(174, 'Notification Setting', 'add_notification_setting', NULL, NULL, 'Admin', '2022-03-16 23:29:09', '2022-03-16 23:29:09'),
(175, 'Notification Setting', 'edit_notification_setting', 'Edit Notification Setting', 'Edit Notification Setting', 'Admin', '2022-03-16 23:29:09', '2022-03-16 23:29:09'),
(176, 'Notification Setting', 'delete_notification_setting', NULL, NULL, 'Admin', '2022-03-16 23:29:09', '2022-03-16 23:29:09'),
(177, 'Conversion Rate Api', 'view_conversion_rate_api', 'View Conversion Rate Api', 'View Conversion Rate Api', 'Admin', '2022-03-16 23:29:09', '2022-03-16 23:29:09'),
(178, 'Conversion Rate Api', 'add_conversion_rate_api', NULL, NULL, 'Admin', '2022-03-16 23:29:09', '2022-03-16 23:29:09'),
(179, 'Conversion Rate Api', 'edit_conversion_rate_api', 'Edit Conversion Rate Api', 'Edit Conversion Rate Api', 'Admin', '2022-03-16 23:29:09', '2022-03-16 23:29:09'),
(180, 'Conversion Rate Api', 'delete_conversion_rate_api', NULL, NULL, 'Admin', '2022-03-16 23:29:09', '2022-03-16 23:29:09'),
(181, 'Addon Manager', 'view_addon_manager', 'View Addon Manager', 'View Addon Manager', 'Admin', '2022-03-16 23:29:09', '2022-03-16 23:29:09'),
(182, 'Addon Manager', 'add_addon_manager', NULL, NULL, 'Admin', '2022-03-16 23:29:09', '2022-03-16 23:29:09'),
(183, 'Addon Manager', 'edit_addon_manager', NULL, NULL, 'Admin', '2022-03-16 23:29:09', '2022-03-16 23:29:09'),
(184, 'Addon Manager', 'delete_addon_manager', NULL, NULL, 'Admin', '2022-03-16 23:29:09', '2022-03-16 23:29:09');

INSERT INTO `permission_role` (`role_id`, `permission_id`) VALUES
(1, 165),
(1, 166),
(1, 167),
(1, 168),
(1, 169),
(1, 170),
(1, 171),
(1, 172),
(1, 173),
(1, 174),
(1, 175),
(1, 176),
(1, 177),
(1, 178),
(1, 179),
(1, 180),
(1, 181),
(1, 182),
(1, 183),
(1, 184);