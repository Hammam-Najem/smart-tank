CREATE TABLE `technri2_smart_tank_db`.`authorized_emie` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `imei` VARCHAR(15) NOT NULL,
  `note` VARCHAR(200) NOT NULL DEFAULT "",
  `tank_state_requests_number` INT DEFAULT 0 NOT NULL,
  `tank_reader_requests_number` INT DEFAULT 0 NOT NULL,
  `password` VARCHAR(200) NOT NULL DEFAULT "0000",
  PRIMARY KEY (`id`),
  UNIQUE (imei)
);