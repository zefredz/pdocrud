DROP TABLE IF EXISTS `posts`;

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