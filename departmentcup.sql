DROP TABLE IF EXISTS `fbaccount`;
CREATE TABLE `fbaccount` (
  `id` char(64) NOT NULL,
  `name` char(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `fbaccountteam`;
CREATE TABLE `fbaccountteam` (
  `FBaccountid` char(64) NOT NULL,
  `teamid` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `team`;
CREATE TABLE `team` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `depart` char(32) NOT NULL,
  `publishtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `teamleader`;
CREATE TABLE `teamleader` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `name` char(32) NOT NULL,
  `teamid` mediumint(9) NOT NULL,
  `number` char(32) NOT NULL,
  `departlevel` char(32) NOT NULL,
  `iscontacter` BOOLEAN NOT NULL,
  `FBaccount` char(32) DEFAULT NULL,
  `email` char(64) DEFAULT NULL,
  `phone` char(16) DEFAULT NULL,
  `participate1` BOOLEAN NOT NULL,
  `participate2` BOOLEAN NOT NULL,
  `field1` int(5) DEFAULT NULL,
  `field2` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `contacter`;
CREATE TABLE `contacter` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `teamid` mediumint(9) NOT NULL,
  `name` char(32) NOT NULL,
  `departlevel` char(32) NOT NULL,
  `FBaccount` char(32) DEFAULT NULL,
  `email` char(64) DEFAULT NULL,
  `phone` char(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `teammember`;
CREATE TABLE `teammember` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `teamid` mediumint(9) NOT NULL,
  `name` char(32) NOT NULL,
  `number` char(32) NOT NULL,
  `departlevel` char(32) NOT NULL,
  `participate1` BOOLEAN NOT NULL,
  `participate2` BOOLEAN NOT NULL,
  `field1` int(5) DEFAULT NULL,
  `field2` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `teamleaderpicture`;
CREATE TABLE `teamleaderpicture` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `teamid` mediumint(9) NOT NULL,
  `filename` varchar(64) NOT NULL,
  `fileextend` char(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `teammemberpicture`;
CREATE TABLE `teammemberpicture` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `teammemberid` mediumint(9) NOT NULL,
  `filename` varchar(64) NOT NULL,
  `fileextend` char(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;