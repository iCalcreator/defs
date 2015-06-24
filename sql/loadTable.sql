--
-- drop all and insert data into table `defs`
--
DELETE FROM `defs`;
--
INSERT INTO `defs` (`nodeid`, `key1`, `key2`, `key3`, `value`) VALUES
('common',  'basepath',   NULL, NULL, '/opt/system/base/'),
('common',  'ttl',        NULL, NULL, 5000),
('storage', 'imagepath1', NULL, NULL, '/opt/images/1/'),
('storage', 'imagepath2', NULL, NULL, '/opt/images/2/'),
('storage', 'imagepath3', NULL, NULL, '/opt/images/3/'),
('storage', 'imagepath4', NULL, NULL, '/opt/images/4/'),
('storage', 'imagepath5', NULL, NULL, '/opt/images/5/'),
('storage', 'imagepath6', NULL, NULL, '/opt/images/6/'),
('storage', 'imagepath7', NULL, NULL, '/opt/images/7/'),
('storage', 'imagepath8', NULL, NULL, '/opt/images/8/'),
('storage', 'imagepath9', NULL, NULL, '/opt/images/9/'),
('storage', 'docpath1',   NULL, NULL, '/opt/docs/1/'),
('storage', 'docpath2',   NULL, NULL, '/opt/docs/2/'),
('storage', 'docpath3',   NULL, NULL, '/opt/docs/3/'),
('storage', 'docpath4',   NULL, NULL, '/opt/docs/4/'),
('storage', 'docpath5',   NULL, NULL, '/opt/docs/5/'),
('storage', 'docpath6',   NULL, NULL, '/opt/docs/6/'),
('storage', 'docpath7',   NULL, NULL, '/opt/docs/7/'),
('storage', 'docpath8',   NULL, NULL, '/opt/docs/8/'),
('storage', 'docpath9',   NULL, NULL, '/opt/docs/9/'),
('storage', 'videopath1', NULL, NULL, '/opt/videos/1/'),
('storage', 'videopath2', NULL, NULL, '/opt/videos/2/'),
('storage', 'videopath3', NULL, NULL, '/opt/videos/3/'),
('storage', 'videopath4', NULL, NULL, '/opt/videos/4/'),
('storage', 'videopath5', NULL, NULL, '/opt/videos/5/'),
('storage', 'videopath6', NULL, NULL, '/opt/videos/6/'),
('storage', 'videopath7', NULL, NULL, '/opt/videos/7/'),
('storage', 'videopath8', NULL, NULL, '/opt/videos/8/'),
('storage', 'videopath9', NULL, NULL, '/opt/videos/9/'),
('storage', 'audiopath1', NULL, NULL, '/opt/audios/1/'),
('storage', 'audiopath2', NULL, NULL, '/opt/audios/2/'),
('storage', 'audiopath3', NULL, NULL, '/opt/audios/3/'),
('storage', 'audiopath4', NULL, NULL, '/opt/audios/4/'),
('storage', 'audiopath5', NULL, NULL, '/opt/audios/5/'),
('storage', 'audiopath6', NULL, NULL, '/opt/audios/6/'),
('storage', 'audiopath7', NULL, NULL, '/opt/audios/7/'),
('storage', 'audiopath8', NULL, NULL, '/opt/audios/8/'),
('storage', 'audiopath9', NULL, NULL, '/opt/audios/9/'),
('other',   'modules',    1,    NULL, 'a-module'),
('other',   'modules',    2,    NULL, 'b-module'),
('other',   'ttl',        NULL, NULL, 2000),
('zModule', 'ttl',        NULL, NULL, 1000),
('zModule', 'header',     NULL, NULL, 'zModule'),
('zModule', 'modulePath', NULL, NULL, 'modules/zModule/'),
('advDbA',  'ttl',        NULL, NULL, 1000),
('advDbA',  'header',     NULL, NULL, 'advDbA'),
('advDbA',  'modulePath', NULL, NULL, 'modules/advDbA/'),
('advDbA',  'title',      'en', NULL, 'title advDbA'),
('advDbA',  'title',      'sv', NULL, 'titel advDbA'),
('advDbA',  'name',       'en', NULL, 'name advDbA'),
('advDbA',  'name',       'sv', NULL, 'namn advDbA'),
('advDbA',  'colours',    'en', 1,    'red advDbA'),
('advDbA',  'colours',    'en', 2,    'blue advDbA'),
('advDbA',  'colours',    'en', 3,    'black advDbA'),
('advDbA',  'colours',    'en', 4,    'white advDbA'),
('advDbA',  'colours',    'sv', 1,    'röd advDbA'),
('advDbA',  'colours',    'sv', 2,    'blå advDbA'),
('advDbA',  'colours',    'sv', 3,    'svart advDbA'),
('advDbA',  'colours',    'sv', 4,    'vit advDbA'),
('advDbB',  'header',     NULL, NULL, 'advDbB'),
('advDbB',  'modulePath', NULL, NULL, 'modules/advDbB/'),
('advDbB',  'ttl',        NULL, NULL, 1000),
('advDbB',  'title',      'en', NULL, 'title advDbB'),
('advDbB',  'title',      'sv', NULL, 'titel advDbB'),
('advDbB',  'name',       'en', NULL, 'name advDbB'),
('advDbB',  'name',       'sv', NULL, 'namn advDbB'),
('advDbB',  'colours',    'en', 1,    'red advDbB'),
('advDbB',  'colours',    'en', 2,    'blue advDbB'),
('advDbB',  'colours',    'en', 3,    'black advDbB'),
('advDbB',  'colours',    'en', 4,    'white advDbB'),
('advDbB',  'colours',    'sv', 1,    'röd advDbB'),
('advDbB',  'colours',    'sv', 2,    'blå advDbB'),
('advDbB',  'colours',    'sv', 3,    'svart advDbB'),
('advDbB',  'colours',    'sv', 4,    'vit advDbB'),
('advDbC',  'header',     NULL, NULL, 'advDbC'),
('advDbC',  'modulePath', NULL, NULL, 'modules/advDbC/'),
('advDbC',  'ttl',        NULL, NULL, 1000),
('advDbC',  'title',      'en', NULL, 'title advDbC'),
('advDbC',  'title',      'sv', NULL, 'titel advDbC'),
('advDbC',  'name',       'en', NULL, 'name advDbC'),
('advDbC',  'name',       'sv', NULL, 'namn advDbC'),
('advDbC',  'colours',    'en', 1,    'red advDbC'),
('advDbC',  'colours',    'en', 2,    'blue advDbC'),
('advDbC',  'colours',    'en', 3,    'black advDbC'),
('advDbC',  'colours',    'en', 4,    'white advDbC'),
('advDbC',  'colours',    'sv', 1,    'röd advDbC'),
('advDbC',  'colours',    'sv', 2,    'blå advDbC'),
('advDbC',  'colours',    'sv', 3,    'svart advDbC'),
('advDbC',  'colours',    'sv', 4,    'vit advDbC'),
('advDbD',  'advDbD',     NULL, NULL, 'advDbD'),
('advDbD',  'header',     NULL, NULL, 'advDbD'),
('advDbD',  'modulePath', NULL, NULL, 'modules/advDbD/'),
('advDbD',  'ttl',        NULL, NULL, 1000),
('advDbD',  'title',      'en', NULL, 'title advDbD'),
('advDbD',  'title',      'sv', NULL, 'titel advDbD'),
('advDbD',  'name',       'en', NULL, 'name advDbD'),
('advDbD',  'name',       'sv', NULL, 'namn advDbD'),
('advDbD',  'colours',    'en', 1,    'red advDbD'),
('advDbD',  'colours',    'en', 2,    'blue advDbD'),
('advDbD',  'colours',    'en', 3,    'black advDbD'),
('advDbD',  'colours',    'en', 4,    'white advDbD'),
('advDbD',  'colours',    'sv', 1,    'röd advDbD'),
('advDbD',  'colours',    'sv', 2,    'blå advDbD'),
('advDbD',  'colours',    'sv', 3,    'svart advDbD'),
('advDbD',  'colours',    'sv', 4,    'vit advDbD');
--
-- drop all and insert (the same) data into table `modules`
-- to test with another table, other table fields
-- and mapping table and columns to defs
--
DELETE FROM `modules`;
--
INSERT INTO `modules` (`name`, `primary`, `second`, `ix`, `content`)
SELECT `nodeid`, `key1`, `key2`, `key3`, `value` FROM `defs`;
--
--
-- NOW for larger node test
--
--
-- drop all  data in table `config`
--
DELETE FROM `config`;
--
ALTER TABLE `config` AUTO_INCREMENT = 1
--
-- opt 1, run 20 times
--
INSERT INTO `config` (`key1`, `key2`, `key3`, `cfgval`)
SELECT `key1`, `key2`, `key3`, `value` FROM `defs`;
-- -
-- - but a better approach, opt 2,  visit
-- - http://kedar.nitty-witty.com/blog/generate-random-test-data-for-mysql-using-routines
-- - download the sql script for generating random data
-- -  and invoke in the database
-- - execute
-- - call populate('defs','config',2000,'N');
-- -  AND (also)
-- - execute opt 1 ONE time !!
--
-- then, (regardless of option)
--
-- create a number of 'nodeid' chunks
--
UPDATE `config` SET `section`='section0' WHERE (`id`       <= 200);
UPDATE `config` SET `section`='section1' WHERE (`id` BETWEEN  201 AND  400);
UPDATE `config` SET `section`='section2' WHERE (`id` BETWEEN  401 AND  600);
UPDATE `config` SET `section`='section3' WHERE (`id` BETWEEN  601 AND  800);
UPDATE `config` SET `section`='section4' WHERE (`id` BETWEEN  801 AND 1000);
UPDATE `config` SET `section`='section5' WHERE (`id` BETWEEN 1001 AND 1200);
UPDATE `config` SET `section`='section6' WHERE (`id` BETWEEN 1201 AND 1400);
UPDATE `config` SET `section`='section7' WHERE (`id` BETWEEN 1401 AND 1600);
UPDATE `config` SET `section`='section8' WHERE (`id` BETWEEN 1601 AND 1800);
UPDATE `config` SET `section`='section9' WHERE (`id`                > 1800);
