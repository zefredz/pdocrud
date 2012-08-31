DROP TABLE IF EXISTS `posts_tags`;

CREATE TABLE IF NOT EXISTS `posts_tags` (
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
);

INSERT INTO posts_tags (post_id,tag_id) VALUES ('3','5');
INSERT INTO posts_tags (post_id,tag_id) VALUES ('6','2');
INSERT INTO posts_tags (post_id,tag_id) VALUES ('5','1');
INSERT INTO posts_tags (post_id,tag_id) VALUES ('8','5');
INSERT INTO posts_tags (post_id,tag_id) VALUES ('1','4');
INSERT INTO posts_tags (post_id,tag_id) VALUES ('9','1');
INSERT INTO posts_tags (post_id,tag_id) VALUES ('2','3');
INSERT INTO posts_tags (post_id,tag_id) VALUES ('9','1');