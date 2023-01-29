CREATE TABLE `technri2_smart_tank_db`.`tank_state` (
  `id` INT NOT NULL AUTO_INCREMENT,
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
  `tank_id` INT NOT NULL,
  INDEX `tank_id_idx` (`tank_id` ASC),
  PRIMARY KEY (`id`),
  FOREIGN KEY (tank_id) REFERENCES tank(id) on delete cascade on update no action
);