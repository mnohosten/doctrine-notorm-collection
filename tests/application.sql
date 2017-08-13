-- Adminer 4.2.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `application`;
CREATE TABLE `application` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `maintainer_id` int(11) DEFAULT NULL,
  `title` varchar(50) NOT NULL,
  `web` varchar(100) DEFAULT NULL,
  `slogan` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `application_author` (`author_id`),
  KEY `application_maintainer` (`maintainer_id`),
  KEY `application_title` (`title`),
  CONSTRAINT `application_author` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`),
  CONSTRAINT `application_maintainer` FOREIGN KEY (`maintainer_id`) REFERENCES `author` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `application` (`id`, `author_id`, `maintainer_id`, `title`, `web`, `slogan`) VALUES
(5,	13,	13,	'tempore illum placeat a in',	'http://hegmann.org/sit-non-rerum-quia-autem-voluptatem-cumque-impedit-expedita.html',	'Ratione explicabo ullam laudantium aperiam sapiente vel.'),
(6,	13,	13,	'libero nesciunt et ex excepturi',	'http://maggio.com/et-est-corrupti-quis-nulla-laudantium-molestiae-inventore',	'Mollitia occaecati sit dolor culpa ut et.'),
(7,	13,	13,	'in itaque delectus aut maiores',	'http://www.lueilwitz.com/aspernatur-est-est-et-eveniet-sed-aut-veritatis-accusantium',	'Quia tempore sit qui et sed optio non.'),
(8,	13,	13,	'magni modi omnis omnis recusandae',	'http://www.smitham.com/quia-accusamus-explicabo-et',	'Iste sequi odit architecto et ipsa distinctio est.'),
(9,	13,	13,	'laudantium voluptatem enim cumque quia',	'http://kunze.info/atque-quia-magnam-rerum',	'Amet incidunt hic laboriosam perferendis.'),
(10,	13,	13,	'incidunt quia quam provident aut',	'http://www.koepp.info/autem-molestias-cupiditate-ipsam-voluptas-rerum-ut',	'Ut molestias praesentium et.'),
(11,	13,	13,	'facere alias in asperiores quo',	'http://www.waelchi.info/',	'Qui et aperiam nam nostrum.'),
(12,	13,	13,	'ipsa vitae illo aspernatur repellat',	'http://www.ondricka.info/quia-corporis-rerum-quibusdam-possimus-sit',	'Totam cumque consequatur velit est cupiditate.'),
(13,	13,	13,	'est eos possimus qui aspernatur',	'http://mills.biz/enim-sequi-doloremque-rerum',	'Aut ipsum sed aut et aut dolorem quae nisi.'),
(14,	13,	13,	'cupiditate et in voluptatibus quo',	'http://huel.info/impedit-dolorum-ut-nam-quas-nesciunt',	'Id qui dolorem excepturi nihil exercitationem.'),
(15,	13,	13,	'laborum id non explicabo quia',	'http://www.koss.com/reprehenderit-ducimus-praesentium-qui-maxime-et-consectetur-libero-excepturi',	'Accusantium dolorem sequi voluptates qui.'),
(16,	13,	13,	'aut mollitia qui dignissimos architecto',	'http://www.doyle.com/',	'Deserunt est reiciendis sed accusamus incidunt possimus.'),
(17,	13,	13,	'ex exercitationem veniam reprehenderit doloribus',	'https://www.weimann.com/amet-sequi-deleniti-autem',	'Amet et aperiam quos repudiandae reiciendis placeat rerum quisquam.');

DROP TABLE IF EXISTS `application_tag`;
CREATE TABLE `application_tag` (
  `application_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`application_id`,`tag_id`),
  KEY `application_tag_tag` (`tag_id`),
  CONSTRAINT `application_tag_tag` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `application_tag` (`application_id`, `tag_id`) VALUES
(1,	21),
(1,	22),
(2,	23),
(3,	21),
(4,	21),
(4,	22);

DROP TABLE IF EXISTS `author`;
CREATE TABLE `author` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `web` varchar(100) NOT NULL,
  `born` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `author` (`id`, `name`, `web`, `born`) VALUES
(11,	'Jakub Vrana',	'http://www.vrana.cz/',	NULL),
(12,	'David Grudl',	'http://davidgrudl.com/',	NULL),
(13,	'David Grudl',	'http://davidgrudl.com/',	NULL);

DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `tag` (`id`, `name`) VALUES
(21,	'PHP'),
(22,	'MySQL'),
(23,	'JavaScript');

-- 2017-08-13 17:22:15
