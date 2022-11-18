DROP SCHEMA IF EXISTS lbaw CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw;
SET search_path TO lbaw;

DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS posts CASCADE;
DROP TABLE IF EXISTS postreactions CASCADE;
DROP TABLE IF EXISTS postreactionnotifications CASCADE;
DROP TABLE IF EXISTS comments CASCADE;
DROP TABLE IF EXISTS commentnotifications CASCADE;
DROP TABLE IF EXISTS commentreactions CASCADE;
DROP TABLE IF EXISTS commentreactionnotifications CASCADE;
DROP TABLE IF EXISTS replies CASCADE;
DROP TABLE IF EXISTS replynotifications CASCADE;
DROP TABLE IF EXISTS replyreactions CASCADE;
DROP TABLE IF EXISTS replyreactionnotifications CASCADE;
DROP TABLE IF EXISTS relationships CASCADE;
DROP TABLE IF EXISTS relationshipnotifications CASCADE;

CREATE TYPE relationship_type AS ENUM ('Close Friends', 'Friends', 'Family');
CREATE TYPE relationship_state AS ENUM ('pending', 'accepted');
CREATE TYPE gender AS ENUM ('Male', 'Female', 'Other');
CREATE TYPE reactions AS ENUM ('Like', 'Dislike', 'Sad', 'Angry', 'Amazed');

CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  name VARCHAR NOT NULL,
  email VARCHAR UNIQUE NOT NULL,
  password VARCHAR NOT NULL,
  remember_token VARCHAR
);

CREATE TABLE posts(
  id SERIAL PRIMARY KEY,
  user_id INTEGER REFERENCES users ON DELETE CASCADE NOT NULL,
  date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL,
  text VARCHAR(800),
  photo VARCHAR(800),
  visibility relationship_type NOT NULL
);

CREATE TABLE postreactions(
    id SERIAL PRIMARY KEY,
    post_id INTEGER REFERENCES posts ON DELETE CASCADE NOT NULL,
    user_id INTEGER REFERENCES users ON DELETE CASCADE NOT NULL,
    date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL,
    type reactions NOT NULL,
    UNIQUE (post_id,user_id)
);

CREATE TABLE postreactionnotifications(
    postreaction_id INTEGER REFERENCES postreactions ON DELETE CASCADE NOT NULL,
    text VARCHAR(800),
    read BOOLEAN NOT NULL DEFAULT FALSE,
    date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL
);

CREATE TABLE comments(
  id SERIAL PRIMARY KEY,
  user_id INTEGER REFERENCES users ON DELETE CASCADE NOT NULL,
  post_id INTEGER REFERENCES posts ON DELETE CASCADE NOT NULL,
  text VARCHAR(800) NOT NULL,
  date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL
);

CREATE TABLE commentnotifications(
    id SERIAL PRIMARY KEY,
    comment_id INTEGER REFERENCES comments ON DELETE CASCADE NOT NULL,
    text VARCHAR(800) NOT NULL,
    read BOOLEAN NOT NULL DEFAULT FALSE,
    date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL
);

CREATE TABLE commentreactions(
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users ON DELETE CASCADE NOT NULL,
    comment_id INTEGER REFERENCES comments ON DELETE CASCADE NOT NULL,
    date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL,
    type reactions NOT NULL,
    UNIQUE (comment_id,user_id)
);

CREATE TABLE commentreactionnotifications(
    id SERIAL PRIMARY KEY,
    commentreaction_id INTEGER REFERENCES commentreactions ON DELETE CASCADE NOT NULL,
    text VARCHAR(800),
    read BOOLEAN NOT NULL DEFAULT FALSE,
    date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL
);

CREATE TABLE replies(
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users ON DELETE CASCADE NOT NULL,
    comment_id INTEGER REFERENCES comments ON DELETE CASCADE NOT NULL,
    text VARCHAR(800) NOT NULL,
    date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL
);

CREATE TABLE replynotifications(
    id SERIAL PRIMARY KEY,
    reply_id INTEGER REFERENCES replies ON DELETE CASCADE NOT NULL,
    text VARCHAR(800) NOT NULL,
    read BOOLEAN NOT NULL DEFAULT FALSE,
    date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL
);

CREATE TABLE replyreactions(
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users ON DELETE CASCADE NOT NULL,
    reply_id INTEGER REFERENCES replies ON DELETE CASCADE NOT NULL,
    date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL,
    type reactions NOT NULL,
    UNIQUE (user_id,reply_id)
);

CREATE TABLE replyreactionnotifications(
    id SERIAL PRIMARY KEY,
    replyreaction_id INTEGER REFERENCES replyreactions ON DELETE CASCADE NOT NULL,
    text VARCHAR(800),
    read BOOLEAN NOT NULL DEFAULT FALSE,
    date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL
);

CREATE TABLE relationships(
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users ON DELETE CASCADE NOT NULL,
    related_id INTEGER REFERENCES users ON DELETE CASCADE NOT NULL,
    type relationship_type NOT NULL,
    state relationship_state NOT NULL DEFAULT 'pending',
    date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL,
    UNIQUE (user_id,related_id,type)
);

CREATE TABLE relationshipnotifications(
    id SERIAL PRIMARY KEY,
    relationship_id INTEGER REFERENCES relationships ON DELETE CASCADE NOT NULL,
    receiver_id INTEGER REFERENCES users ON DELETE CASCADE NOT NULL,
    text VARCHAR(800),
    read BOOLEAN NOT NULL DEFAULT FALSE,
    date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL
);

CREATE FUNCTION create_post_reaction_notification() RETURNS trigger AS $$
    DECLARE newName VARCHAR(255);
    DECLARE newText VARCHAR(800);
BEGIN
    --Get the name of the user that sent the request and declare variable
    SELECT name INTO newName FROM users WHERE id = NEW.user_id;
    -- create the notification text
    newText = newName || ' reacted to your post';
    -- insert the notification
    INSERT INTO postreactionnotifications (postreaction_id, text) VALUES (NEW.id, newText);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;
CREATE TRIGGER create_post_reaction_notification
AFTER INSERT ON postreactions
FOR EACH ROW
EXECUTE PROCEDURE create_post_reaction_notification();



CREATE FUNCTION create_comment_notification() RETURNS trigger AS $$
DECLARE 
    authorName VARCHAR(255);
    text VARCHAR(800);
BEGIN
    SELECT name INTO authorName FROM users WHERE id = NEW.user_id;
    text := authorName || ' commented on your post';
    INSERT INTO commentnotifications (comment_id, text) VALUES (NEW.id,text);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;
CREATE TRIGGER create_comment_notification
AFTER INSERT ON comments
FOR EACH ROW
EXECUTE PROCEDURE create_comment_notification();



CREATE FUNCTION create_comment_reaction_notification() RETURNS trigger AS $$
    DECLARE newName VARCHAR(255);
    DECLARE newText VARCHAR(800);
BEGIN
    --Get the name of the user that sent the request and declare variable
    SELECT name INTO newName FROM users WHERE id = NEW.user_id;
    -- create the notification text
    newText = newName || ' reacted to your comment';
    -- insert the notification
    INSERT INTO commentreactionnotifications (commentreaction_id, text) VALUES (NEW.id, newText);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER create_comment_reaction_notification
AFTER INSERT ON commentreactions
FOR EACH ROW
EXECUTE PROCEDURE create_comment_reaction_notification();



CREATE FUNCTION create_reply_notification() RETURNS trigger AS $$
    DECLARE authorName VARCHAR(255);
    DECLARE newText VARCHAR(800);
BEGIN
    SELECT name INTO authorName FROM users WHERE id = NEW.user_id;
    -- create the notification text
    newText = authorName || ' replied to your comment';
    -- insert the notification
    INSERT INTO replynotifications (reply_id, text) VALUES (NEW.id, newText);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;
CREATE TRIGGER create_reply_notification
AFTER INSERT ON replies
FOR EACH ROW
EXECUTE PROCEDURE create_reply_notification();



CREATE FUNCTION create_reply_reaction_notification() RETURNS trigger AS $$
    DECLARE newName VARCHAR(255);
    DECLARE newText VARCHAR(800);
BEGIN
    --Get the name of the user that sent the request and declare variable
    SELECT name INTO newName FROM users WHERE id = NEW.user_id;
    -- create the notification text with the type of reaction
    newText = newName || ' reacted to your reply';
    -- insert the notification
    INSERT INTO replyreactionnotifications (replyreaction_id, text) VALUES (NEW.id, newText);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;
CREATE TRIGGER create_reply_reaction_notification
AFTER INSERT ON replyreactions
FOR EACH ROW
EXECUTE PROCEDURE create_reply_reaction_notification();



CREATE FUNCTION create_relationship_notification() RETURNS trigger AS $$
    DECLARE newName VARCHAR(255);
    DECLARE newText VARCHAR(800);
BEGIN
    --Get the name of the user that sent the request and declare variable
    SELECT name INTO newName FROM users WHERE id = NEW.user_id;
    -- create the notification text
    newText = newName || ' sent you a relationship request: ' || NEW.type;
    -- insert the notification
    INSERT INTO relationshipnotifications (relationship_id, receiver_id, text) VALUES (NEW.id,New.related_id ,newText);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER create_relationship_notification
AFTER INSERT ON relationships
FOR EACH ROW
EXECUTE PROCEDURE create_relationship_notification();



CREATE FUNCTION create_relationship_accepted_notification() RETURNS trigger AS $$
    DECLARE newName VARCHAR(255);
    DECLARE newText VARCHAR(800);
BEGIN
    --Get the name of the user that sent the request and declare variable
    SELECT name INTO newName FROM users WHERE id = NEW.related_id;
    -- create the notification text
    newText = newName || ' accepted your relationship request: ' || NEW.type;
    -- insert the notification
    INSERT INTO relationshipnotifications (relationship_id,receiver_id,text) VALUES (NEW.id,New.user_id,newText);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER create_relationship_accepted_notification
AFTER UPDATE ON relationships
FOR EACH ROW
WHEN (NEW.state = 'accepted')
EXECUTE PROCEDURE create_relationship_accepted_notification();


INSERT INTO users VALUES (
  DEFAULT,
  'John Doe',
  'admin@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'
); -- Password is 1234. Generated using Hash::make('1234')

INSERT INTO users VALUES (
  DEFAULT,
  'Tiago',
  'tiago@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'
); -- Password is 1234. Generated using Hash::make('1234')

INSERT INTO posts(user_id, text, photo, visibility) VALUES(1,'10 Python Mini Automation Projects','https://pbs.twimg.com/profile_images/429285908953579520/InZKng9-_x96.jpeg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(1,'The 3 best ADVANCED techniques I learned while building in public last month were:,,1) How to use Chrome Inspect Console properly, especially the Network - Fetch/XHR - Responses feature.,,Understanding the requests from a site is life changing for web scraping.,,(1/3)','https://pbs.twimg.com/profile_images/1461710621397897223/XZciUUbZ_x96.jpg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(2,'Halloween is coming','https://pbs.twimg.com/profile_images/1269253433703510016/B6XjoBkv_x96.jpg','Friends');

INSERT INTO comments(user_id, post_id, text) VALUES(2, 1, 'Thank you for the information');
INSERT INTO comments(user_id, post_id, text) VALUES(1, 3, 'I will try it');

INSERT INTO replies(user_id, comment_id, text) VALUES(1, 1, 'You are welcome :)');
INSERT INTO replies(user_id, comment_id, text) VALUES(2, 2, 'Cheers to more good reads.');

INSERT INTO postreactions(post_id, user_id, type) VALUES(1, 2, 'Amazed');
INSERT INTO postreactions(post_id, user_id, type) VALUES(3, 1, 'Dislike');

INSERT INTO commentreactions(comment_id, user_id, type) VALUES(1, 1, 'Like');
INSERT INTO commentreactions(comment_id, user_id, type) VALUES(2, 2, 'Angry');

INSERT INTO replyreactions(reply_id, user_id, type) VALUES(1, 2, 'Sad');
INSERT INTO replyreactions(reply_id, user_id, type) VALUES(2, 1, 'Like');

INSERT INTO relationships(user_id, related_id, type) VALUES(1, 2, 'Close Friends');