-- If you're still having issues, you can run this SQL directly in your database
-- to recreate the votes table with a simpler structure

DROP TABLE IF EXISTS votes;

CREATE TABLE votes (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  post_id INT(11) UNSIGNED NOT NULL,
  user_id INT(11) UNSIGNED NOT NULL,
  vote_value TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY post_user_idx (post_id, user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
