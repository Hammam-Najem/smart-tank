CREATE TABLE `technri2_smart_tank_db`.`tank` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `device_number` varchar(10) NOT NULL,
  `receiver_number` VARCHAR(10) NOT NULL,
  `tank_height` DOUBLE NOT NULL,
  `dead_height` DOUBLE NOT NULL,
  `imei` VARCHAR(15) NOT NULL,
  `iccid` VARCHAR(22) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `imei_idx` (`imei` ASC),
  FOREIGN KEY (imei) REFERENCES authorized_emie(imei) on delete cascade on update no action
);