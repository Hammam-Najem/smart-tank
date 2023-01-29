CREATE TABLE `technri2_smart_tank_db`.`ota` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ota_date` VARCHAR(20) NOT NULL,
  `ota_format` VARCHAR(20) NOT NULL,
  `size` VARCHAR(20) NOT NULL,
  `name` VARCHAR(20) NOT NULL,
  `created_by` VARCHAR(20) NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);