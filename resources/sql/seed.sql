DROP SCHEMA IF EXISTS lbaw2112 CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw2112;
SET search_path TO lbaw2112;

CREATE TYPE relationship_type AS ENUM ('Close Friends', 'Friends', 'Family');
CREATE TYPE relationship_state AS ENUM ('pending', 'accepted');
CREATE TYPE gender AS ENUM ('Male', 'Female', 'Other');
CREATE TYPE reactions AS ENUM ('Like', 'Dislike', 'Sad', 'Angry', 'Amazed');

CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  gender gender NOT NULL,
  description VARCHAR(800),
  photo VARCHAR(255),
  password VARCHAR NOT NULL,
  remember_token VARCHAR,
  ban BOOLEAN NOT NULL DEFAULT FALSE
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

CREATE INDEX post_author ON posts USING hash (user_id);
CREATE INDEX reactions_of_post ON postreactions USING hash (post_id);
CREATE INDEX user_relationships ON relationships USING btree (user_id); CLUSTER relationships USING user_relationships;



-- Add column to user to store computed ts_vectors.
ALTER TABLE users
ADD COLUMN tsvectors TSVECTOR;
-- Create a function to automatically update ts_vectors.
CREATE FUNCTION user_search_update() RETURNS TRIGGER AS $$
BEGIN
IF TG_OP = 'INSERT' THEN
       NEW.tsvectors = (
        setweight(to_tsvector('english', NEW.name), 'A') ||
        setweight(to_tsvector('english', NEW.description), 'B')
       );
END IF;
IF TG_OP = 'UPDATE' THEN
        IF (NEW.name <> OLD.name OR NEW.description <> OLD.description) THEN
          NEW.tsvectors = (
            setweight(to_tsvector('english', NEW.name), 'A') ||
            setweight(to_tsvector('english', NEW.description), 'B')
          );
        END IF;
END IF;
RETURN NEW;
END $$
LANGUAGE plpgsql;
-- Create a trigger before insert or update on work.
CREATE TRIGGER user_search_update
BEFORE INSERT OR UPDATE ON users
FOR EACH ROW
EXECUTE PROCEDURE user_search_update();
-- Finally, create a GIN index for ts_vectors.
CREATE INDEX search_idx_user ON users USING GIN (tsvectors);



-- Add column to post to store computed ts_vectors.
ALTER TABLE posts
ADD COLUMN tsvectors TSVECTOR;
-- Create a function to automatically update ts_vectors.
CREATE FUNCTION post_search_update() RETURNS TRIGGER AS $$
BEGIN
IF TG_OP = 'INSERT' THEN
       NEW.tsvectors = to_tsvector('english', NEW.text);
END IF;
IF TG_OP = 'UPDATE' THEN
        IF NEW.text <> OLD.text THEN
          NEW.tsvectors = to_tsvector('english', NEW.text);
        END IF;
END IF;
RETURN NEW;
END $$
LANGUAGE plpgsql;
-- Create a trigger before insert or update on post.
CREATE TRIGGER post_search_update
BEFORE INSERT OR UPDATE ON posts
FOR EACH ROW
EXECUTE PROCEDURE post_search_update();
-- Finally, create a GIN index for ts_vectors.
CREATE INDEX search_idx_post ON posts USING GIN (tsvectors);




-- Add column to comment_post to store computed ts_vectors.
ALTER TABLE comments
ADD COLUMN tsvectors TSVECTOR;
-- Create a function to automatically update ts_vectors.
CREATE FUNCTION comment_search_update() RETURNS TRIGGER AS $$
BEGIN
IF TG_OP = 'INSERT' THEN
       NEW.tsvectors = to_tsvector('english', NEW.text);
END IF;
IF TG_OP = 'UPDATE' THEN
        IF NEW.text <> OLD.text THEN
          NEW.tsvectors = to_tsvector('english', NEW.text);
        END IF;
END IF;
RETURN NEW;
END $$
LANGUAGE plpgsql;
-- Create a trigger before insert or update on comment_post.
CREATE TRIGGER comment_search_update
BEFORE INSERT OR UPDATE ON comments
FOR EACH ROW
EXECUTE PROCEDURE comment_search_update();
-- Finally, create a GIN index for ts_vectors.
CREATE INDEX search_idx_comment ON comments USING GIN (tsvectors);




-- Add column to comment_reply to store computed ts_vectors.
ALTER TABLE replies
ADD COLUMN tsvectors TSVECTOR;
-- Create a function to automatically update ts_vectors.
CREATE FUNCTION reply_search_update() RETURNS TRIGGER AS $$
BEGIN
IF TG_OP = 'INSERT' THEN
       NEW.tsvectors = to_tsvector('english', NEW.text);
END IF;
IF TG_OP = 'UPDATE' THEN
        IF NEW.text <> OLD.text THEN
          NEW.tsvectors = to_tsvector('english', NEW.text);
        END IF;
END IF;
RETURN NEW;
END $$
LANGUAGE plpgsql;
-- Create a trigger before insert or update on comment_reply.
CREATE TRIGGER reply_search_update
BEFORE INSERT OR UPDATE ON replies
FOR EACH ROW
EXECUTE PROCEDURE reply_search_update();
-- Finally, create a GIN index for ts_vectors.
CREATE INDEX search_idx_reply ON replies USING GIN (tsvectors);


INSERT INTO users VALUES (
  DEFAULT,
  'John Doe',
  'admin@example.com',
  'Male',
  'Gosto de gatos',
  'https://robohash.org/solutacumqueet.png?size=50x50&set=set1',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'
); -- Password is 1234. Generated using Hash::make('1234')

INSERT INTO users VALUES (
  DEFAULT,
  'Tiago',
  'tiago@example.com',
  'Male',
  'FC PORTO <3',
  'https://robohash.org/sintbeataefugiat.png?size=50x50&set=set1',
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