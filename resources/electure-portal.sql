SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `electure_portal` ;
CREATE SCHEMA IF NOT EXISTS `electure_portal` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ;
USE `electure_portal` ;

-- -----------------------------------------------------
-- Table `electure_portal`.`terms`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `electure_portal`.`terms` ;

CREATE  TABLE IF NOT EXISTS `electure_portal`.`terms` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(100) NOT NULL ,
  `ordering` BIGINT NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `idx_unique_name` (`name` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `electure_portal`.`categories`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `electure_portal`.`categories` ;

CREATE  TABLE IF NOT EXISTS `electure_portal`.`categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(100) NOT NULL ,
  `ordering` BIGINT NOT NULL DEFAULT 0 ,
  `hide` TINYINT(1) NOT NULL DEFAULT 0 ,
  `term_free` TINYINT(1) NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `idx_unique_name` (`name` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `electure_portal`.`providers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `electure_portal`.`providers` ;

CREATE  TABLE IF NOT EXISTS `electure_portal`.`providers` (
  `name` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`name`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `electure_portal`.`listings`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `electure_portal`.`listings` ;

CREATE  TABLE IF NOT EXISTS `electure_portal`.`listings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(200) NOT NULL ,
  `slug` VARCHAR(200) NULL ,
  `code` VARCHAR(200) NULL ,
  `last_update` DATETIME NULL ,
  `provider_name` VARCHAR(100) NULL ,
  `category_id` BIGINT UNSIGNED NULL ,
  `term_id` BIGINT UNSIGNED NULL ,
  `html` VARCHAR(255) NULL ,
  `inactive` TINYINT(1) NOT NULL DEFAULT 0 ,
  `dynamic_view` TINYINT(1) NOT NULL DEFAULT 0 ,
  `invert_sorting` TINYINT(1) NOT NULL DEFAULT 0 ,
  `ordering` BIGINT NOT NULL DEFAULT 0 ,
  `parent_id` BIGINT NULL ,
  `lft` BIGINT NULL ,
  `rght` BIGINT NULL ,
  `created` DATETIME NOT NULL ,
  `updated` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_lists_terms1_idx` (`term_id` ASC) ,
  INDEX `fk_lists_categories1_idx` (`category_id` ASC) ,
  INDEX `idx_ordering` (`ordering` ASC) ,
  INDEX `idx_parent_id` (`parent_id` ASC) ,
  INDEX `fk_listings_providers1_idx` (`provider_name` ASC) ,
  CONSTRAINT `fk_lists_terms1`
    FOREIGN KEY (`term_id` )
    REFERENCES `electure_portal`.`terms` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lists_categories1`
    FOREIGN KEY (`category_id` )
    REFERENCES `electure_portal`.`categories` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_listings_providers1`
    FOREIGN KEY (`provider_name` )
    REFERENCES `electure_portal`.`providers` (`name` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `electure_portal`.`videos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `electure_portal`.`videos` ;

CREATE  TABLE IF NOT EXISTS `electure_portal`.`videos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `listing_id` BIGINT UNSIGNED NOT NULL ,
  `video_id` VARCHAR(200) NOT NULL ,
  `title` VARCHAR(255) NULL ,
  `subtitle` VARCHAR(255) NULL ,
  `speaker` VARCHAR(255) NULL ,
  `location` VARCHAR(255) NULL ,
  `description` VARCHAR(255) NULL ,
  `thumbnail` BLOB NULL ,
  `thumbnail_mime_type` VARCHAR(45) NULL ,
  `video_date` DATETIME NOT NULL ,
  `views` BIGINT NOT NULL DEFAULT 0 ,
  `created` DATETIME NOT NULL ,
  `updated` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_videos_list_idx` (`listing_id` ASC) ,
  UNIQUE INDEX `idx_unique_video_list_provider` (`listing_id` ASC, `video_id` ASC) ,
  INDEX `idx_video_date` (`video_date` DESC) ,
  CONSTRAINT `fk_videos_lists`
    FOREIGN KEY (`listing_id` )
    REFERENCES `electure_portal`.`listings` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `electure_portal`.`types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `electure_portal`.`types` ;

CREATE  TABLE IF NOT EXISTS `electure_portal`.`types` (
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`name`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `electure_portal`.`videos_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `electure_portal`.`videos_types` ;

CREATE  TABLE IF NOT EXISTS `electure_portal`.`videos_types` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `video_id` BIGINT UNSIGNED NOT NULL ,
  `type_name` VARCHAR(45) NOT NULL ,
  `url` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_videos_types_videos1_idx` (`video_id` ASC) ,
  UNIQUE INDEX `idx_unique_video_type` (`video_id` ASC, `type_name` ASC) ,
  INDEX `fk_videos_types_types1_idx` (`type_name` ASC) ,
  CONSTRAINT `fk_videos_types_videos1`
    FOREIGN KEY (`video_id` )
    REFERENCES `electure_portal`.`videos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_videos_types_types1`
    FOREIGN KEY (`type_name` )
    REFERENCES `electure_portal`.`types` (`name` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `electure_portal`.`groups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `electure_portal`.`groups` ;

CREATE  TABLE IF NOT EXISTS `electure_portal`.`groups` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) ,
  UNIQUE INDEX `idx_unique` (`name` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `electure_portal`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `electure_portal`.`users` ;

CREATE  TABLE IF NOT EXISTS `electure_portal`.`users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `group_id` BIGINT UNSIGNED NOT NULL ,
  `username` VARCHAR(45) NOT NULL ,
  `password` VARCHAR(45) NOT NULL ,
  `firstname` VARCHAR(45) NOT NULL ,
  `lastname` VARCHAR(45) NOT NULL ,
  `active` TINYINT(1) NOT NULL ,
  `last_login` DATETIME NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `email_UNIQUE` (`username` ASC) ,
  INDEX `fk_users_groups1_idx` (`group_id` ASC) ,
  CONSTRAINT `fk_users_groups1`
    FOREIGN KEY (`group_id` )
    REFERENCES `electure_portal`.`groups` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `electure_portal`.`logs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `electure_portal`.`logs` ;

CREATE  TABLE IF NOT EXISTS `electure_portal`.`logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `message` TEXT NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `electure_portal`.`posts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `electure_portal`.`posts` ;

CREATE  TABLE IF NOT EXISTS `electure_portal`.`posts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` BIGINT UNSIGNED NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `slug` VARCHAR(255) NOT NULL ,
  `content` TEXT NOT NULL ,
  `publish` TINYINT(1) NOT NULL ,
  `created` DATETIME NOT NULL ,
  `updated` DATETIME NOT NULL ,
  `show_link` TINYINT(1) NOT NULL DEFAULT 0 ,
  `show_frontpage` TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_posts_users1_idx` (`user_id` ASC) ,
  CONSTRAINT `fk_posts_users1`
    FOREIGN KEY (`user_id` )
    REFERENCES `electure_portal`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `electure_portal`.`terms`
-- -----------------------------------------------------
START TRANSACTION;
USE `electure_portal`;
INSERT INTO `electure_portal`.`terms` (`id`, `name`, `ordering`) VALUES (1, 'SS 10', 0);
INSERT INTO `electure_portal`.`terms` (`id`, `name`, `ordering`) VALUES (2, 'WS 10/11', 0);
INSERT INTO `electure_portal`.`terms` (`id`, `name`, `ordering`) VALUES (3, 'SS 11', 0);
INSERT INTO `electure_portal`.`terms` (`id`, `name`, `ordering`) VALUES (4, 'WS 11/12', 0);
INSERT INTO `electure_portal`.`terms` (`id`, `name`, `ordering`) VALUES (5, 'SS 12', 0);
INSERT INTO `electure_portal`.`terms` (`id`, `name`, `ordering`) VALUES (6, 'WS 12/13', 0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `electure_portal`.`categories`
-- -----------------------------------------------------
START TRANSACTION;
USE `electure_portal`;
INSERT INTO `electure_portal`.`categories` (`id`, `name`, `ordering`, `hide`, `term_free`) VALUES (1, 'Vorlesungen', 0, 0, 0);
INSERT INTO `electure_portal`.`categories` (`id`, `name`, `ordering`, `hide`, `term_free`) VALUES (2, 'Einzelveranstaltungen', 1, 0, 0);
INSERT INTO `electure_portal`.`categories` (`id`, `name`, `ordering`, `hide`, `term_free`) VALUES (3, 'Veranstaltungsreihen', 2, 0, 1);

COMMIT;

-- -----------------------------------------------------
-- Data for table `electure_portal`.`providers`
-- -----------------------------------------------------
START TRANSACTION;
USE `electure_portal`;
INSERT INTO `electure_portal`.`providers` (`name`) VALUES ('vilea');
INSERT INTO `electure_portal`.`providers` (`name`) VALUES ('mediasite');

COMMIT;

-- -----------------------------------------------------
-- Data for table `electure_portal`.`types`
-- -----------------------------------------------------
START TRANSACTION;
USE `electure_portal`;
INSERT INTO `electure_portal`.`types` (`name`) VALUES ('flash');
INSERT INTO `electure_portal`.`types` (`name`) VALUES ('html5');
INSERT INTO `electure_portal`.`types` (`name`) VALUES ('quicktime');
INSERT INTO `electure_portal`.`types` (`name`) VALUES ('mobile');
INSERT INTO `electure_portal`.`types` (`name`) VALUES ('mp3');
INSERT INTO `electure_portal`.`types` (`name`) VALUES ('silverlight');

COMMIT;

-- -----------------------------------------------------
-- Data for table `electure_portal`.`groups`
-- -----------------------------------------------------
START TRANSACTION;
USE `electure_portal`;
INSERT INTO `electure_portal`.`groups` (`id`, `name`) VALUES (1, 'admin');
INSERT INTO `electure_portal`.`groups` (`id`, `name`) VALUES (2, 'assistant');
INSERT INTO `electure_portal`.`groups` (`id`, `name`) VALUES (3, 'user');

COMMIT;
