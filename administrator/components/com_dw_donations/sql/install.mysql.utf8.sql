CREATE TABLE IF NOT EXISTS `#__dw_donations` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`state` TINYINT(3)  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`created` DATETIME NOT NULL ,
`modified` DATETIME NOT NULL ,
`donor_id` TEXT NOT NULL ,
`beneficiary_id` TEXT NOT NULL ,
`fname` VARCHAR(255)  NOT NULL ,
`lname` VARCHAR(255)  NOT NULL ,
`email` VARCHAR(255)  NOT NULL ,
`amount` VARCHAR(10)  NOT NULL ,
`country` VARCHAR(255)  NOT NULL ,
`anonymous` VARCHAR(255)  NOT NULL ,
`payment_method` VARCHAR(255)  NOT NULL ,
`order_code` BIGINT(20)  NOT NULL ,
`transaction_id` VARCHAR(255)  NOT NULL ,
`parameters` TEXT NOT NULL ,
`language` CHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

