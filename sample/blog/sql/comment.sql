DROP TABLE IF EXISTS `comments`;

CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(11) NOT NULL auto_increment,
  `comment_post` int(11) NOT NULL default 0,
  `comment_author` int(11) NOT NULL default 0,
  `comment_title` varchar(255) NOT NULL default '',
  `comment_time` datetime default '0000-00-00 00:00:00',
  `comment_content` text NOT NULL,
  PRIMARY KEY  (`comment_id`)
);

INSERT INTO comments (comment_post,comment_author,comment_title,comment_content,comment_time) VALUES ('10','4','sem molestie sodales.','Lorem ipsum dolor sit amet, consectetuer adipiscing elit.','2008-01-04 02:04:13');
INSERT INTO comments (comment_post,comment_author,comment_title,comment_content,comment_time) VALUES ('2','4','In nec orci.','Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed','2008-01-11 12:24:00');
INSERT INTO comments (comment_post,comment_author,comment_title,comment_content,comment_time) VALUES ('6','3','Nullam scelerisque neque','Lorem ipsum dolor sit amet, consectetuer','2008-11-21 05:24:52');
INSERT INTO comments (comment_post,comment_author,comment_title,comment_content,comment_time) VALUES ('3','3','in consequat enim','Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur','2007-11-27 12:26:52');
INSERT INTO comments (comment_post,comment_author,comment_title,comment_content,comment_time) VALUES ('2','2','auctor ullamcorper, nisl','Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur','2008-03-23 21:05:39');
INSERT INTO comments (comment_post,comment_author,comment_title,comment_content,comment_time) VALUES ('4','3','Sed malesuada augue','Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur','2008-07-26 15:45:57');
INSERT INTO comments (comment_post,comment_author,comment_title,comment_content,comment_time) VALUES ('7','4','nec urna suscipit','Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed','2007-08-08 05:17:45');
INSERT INTO comments (comment_post,comment_author,comment_title,comment_content,comment_time) VALUES ('2','4','primis in faucibus','Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed','2007-10-11 10:24:55');
INSERT INTO comments (comment_post,comment_author,comment_title,comment_content,comment_time) VALUES ('7','4','Nullam nisl. Maecenas','Lorem ipsum dolor sit amet, consectetuer adipiscing elit.','2008-02-03 08:02:15');
INSERT INTO comments (comment_post,comment_author,comment_title,comment_content,comment_time) VALUES ('4','4','odio. Phasellus at','Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur','2007-05-24 10:58:30');