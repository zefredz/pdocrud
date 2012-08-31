DROP TABLE IF EXISTS `posts`;
DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL auto_increment,
  `user_uid` varchar(255) NOT NULL default '',
  `user_password` varchar(255) NOT NULL default '',
  `user_firstname` varchar(255) NOT NULL default '',
  `user_lastname` varchar(255) NOT NULL default '',
  `user_email` varchar(255) default NULL,
  `user_registration` datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (`user_id`)
);

INSERT INTO users (user_uid,user_password,user_firstname,user_lastname,user_email,user_registration) VALUES ('Anika','scelerisque','Stacy','Mcfadden','dapibus.quam.quis@nonante.com','2008-08-24 19:06:44');
INSERT INTO users (user_uid,user_password,user_firstname,user_lastname,user_email,user_registration) VALUES ('Talon','elementum','Pamela','Drake','a.sollicitudin.orci@fringilla.com','2007-01-21 02:18:13');
INSERT INTO users (user_uid,user_password,user_firstname,user_lastname,user_email,user_registration) VALUES ('Nicholas','lobortis','Dara','Hill','auctor@Nuncmauris.com','2006-12-16 07:23:53');
INSERT INTO users (user_uid,user_password,user_firstname,user_lastname,user_email,user_registration) VALUES ('Ciaran','neque','Baxter','Douglas','mollis@id.edu','2007-04-27 00:18:36');
INSERT INTO users (user_uid,user_password,user_firstname,user_lastname,user_email,user_registration) VALUES ('Giacomo','erat','Karyn','Armstrong','sollicitudin.commodo.ipsum@vulputaterisus.ca','2007-03-12 06:22:25');

CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` int(11) NOT NULL auto_increment,
  `post_author` int(11) NOT NULL default 0,
  `post_chapo` varchar(255) NOT NULL default '',
  `post_title` varchar(255) NOT NULL default '',
  `post_content` text NOT NULL,
  `post_time` datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (`post_id`)
);


INSERT INTO posts (post_author,post_chapo,post_title,post_content,post_time) VALUES ('5','Aenean gravida nunc sed pede.','libero mauris, aliquam','Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer','2008-10-21 16:29:42');
INSERT INTO posts (post_author,post_chapo,post_title,post_content,post_time) VALUES ('4','in faucibus orci luctus et','hendrerit id, ante.','Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer','2007-10-06 03:03:47');
INSERT INTO posts (post_author,post_chapo,post_title,post_content,post_time) VALUES ('2','placerat eget, venenatis a, magna.','tellus non magna.','Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor.','2008-01-04 15:01:23');
INSERT INTO posts (post_author,post_chapo,post_title,post_content,post_time) VALUES ('5','ipsum ac mi eleifend egestas.','elit. Curabitur sed','Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam adipiscing lacus.','2008-07-22 15:04:25');
INSERT INTO posts (post_author,post_chapo,post_title,post_content,post_time) VALUES ('1','est. Nunc ullamcorper, velit in','ac libero nec','Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam adipiscing lacus.','2007-12-01 15:05:51');
INSERT INTO posts (post_author,post_chapo,post_title,post_content,post_time) VALUES ('2','placerat, augue. Sed molestie. Sed','dapibus rutrum, justo.','Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam adipiscing lacus. Ut','2008-03-23 02:05:10');
INSERT INTO posts (post_author,post_chapo,post_title,post_content,post_time) VALUES ('2','ultrices, mauris ipsum porta elit,','nonummy ipsum non','Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam adipiscing lacus. Ut nec urna et arcu','2007-08-20 11:52:57');
INSERT INTO posts (post_author,post_chapo,post_title,post_content,post_time) VALUES ('1','ut quam vel sapien imperdiet','dui. Fusce diam','Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor.','2008-10-12 10:48:02');
INSERT INTO posts (post_author,post_chapo,post_title,post_content,post_time) VALUES ('3','risus. Nunc ac sem ut','enim. Etiam imperdiet','Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed tortor. Integer aliquam','2008-02-23 10:34:19');
INSERT INTO posts (post_author,post_chapo,post_title,post_content,post_time) VALUES ('4','convallis erat, eget tincidunt dui','nec, leo. Morbi','Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur sed','2007-02-25 01:50:00');

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