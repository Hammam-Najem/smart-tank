CREATE TABLE `br_tank_state` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `imei` VARCHAR(15) NOT NULL,
  `operator` VARCHAR(25) NOT NULL,
  `water_height_level` DOUBLE NOT NULL,
  `raw_height` DOUBLE NOT NULL,
  `connect_time` INT NOT NULL,
  `sleep_time` INT NOT NULL,
  `signal_strength` INT NOT NULL,
  `connect_failures` INT NOT NULL,
  `bat_volt` DOUBLE NOT NULL,
  `comment` VARCHAR(200) NULL,
  `ota_date` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fail_reason` VARCHAR(200) NOT NULL ,
  PRIMARY KEY (`id`)
);