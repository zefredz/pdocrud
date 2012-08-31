DROP TABLE IF EXISTS `tags`;

CREATE TABLE IF NOT EXISTS `tags` (
  `tag_id` int(11) NOT NULL auto_increment,
  `tag_name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`tag_id`)
);

INSERT INTO tags (tag_name) VALUES ('velit');
INSERT INTO tags (tag_name) VALUES ('Proin');
INSERT INTO tags (tag_name) VALUES ('eu,');
INSERT INTO tags (tag_name) VALUES ('enim');
INSERT INTO tags (tag_name) VALUES ('hymenaeos.');