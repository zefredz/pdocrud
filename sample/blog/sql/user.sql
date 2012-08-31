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