-- --------------------------------------------------------
--
-- Table structure for (test) table `defs`
--
-- only `nodeid`, `key1`, `key2`, `key3` and `value` used in tests
--
CREATE TABLE IF NOT EXISTS `defs` (
  `id`        bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nodeid`    varchar(64)         NOT NULL     COMMENT 'node identification',
  `key1`      varchar(128)        NOT NULL     COMMENT 'value key (1)',
  `key2`      varchar(128)    DEFAULT NULL     COMMENT 'value key (2)',
  `key3`      varchar(128)    DEFAULT NULL     COMMENT 'value key (3)',
  `value`     varchar(2048)       NOT NULL     COMMENT 'the value',
  `nokeys`    integer         DEFAULT 1        COMMENT 'number of keys used',
  `valuetype` varchar(10)     DEFAULT 'string' COMMENT 'value type',
  `comment`   varchar(2048)       NOT NULL     COMMENT 'value description'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
--
-- Table structure for (test) table `modules`
--
-- only `name`, `primary`, `second`, `ix` and `content` used in tests
--
CREATE TABLE IF NOT EXISTS `modules` (
  `id`      bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`    varchar(64)         NOT NULL,
  `counter` integer             NOT NULL,
  `primary` varchar(128)        NOT NULL,
  `second`  varchar(128)    DEFAULT NULL,
  `ix`      varchar(128)    DEFAULT NULL,
  `other`   varchar(2048)   DEFAULT NULL,
  `content` varchar(2048)       NOT NULL,
  `comment` varchar(2048)   DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
-- --------------------------------------------------------
--
-- Table structure for (test) table `config`
--
-- only `section`, `key1`, `key2`, `key3`, `key4` and `cfgval` used in tests
--
CREATE TABLE IF NOT EXISTS `config` (
  `id`      bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `section` varchar(64)         NOT NULL,
  `counter` integer             NOT NULL,
  `key1`    varchar(128)        NOT NULL,
  `key2`    varchar(128)    DEFAULT NULL,
  `key3`    varchar(128)    DEFAULT NULL,
  `key4`    varchar(128)    DEFAULT NULL,
  `other`   varchar(2048)   DEFAULT NULL,
  `cfgval`  varchar(2048)       NOT NULL,
  `comment` varchar(2048)   DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
