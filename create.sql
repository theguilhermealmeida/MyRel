DROP SCHEMA IF EXISTS lbaw2212 CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw2212;
SET search_path TO lbaw2212;


CREATE TYPE relationship_type AS ENUM ('Close Friends', 'Friends', 'Family');
CREATE TYPE relationship_state AS ENUM ('pending', 'accepted');
CREATE TYPE gender AS ENUM ('Male', 'Female', 'Other');
CREATE TYPE reactions AS ENUM ('Like', 'Dislike', 'Sad', 'Angry', 'Amazed');


CREATE TABLE IF NOT EXISTS Users(
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    gender gender NOT NULL,
    password VARCHAR(255) NOT NULL,  -- stored in sha1
    ban BOOLEAN NOT NULL DEFAULT FALSE,
    description VARCHAR(800),
    photo VARCHAR(255)
);


CREATE TABLE IF NOT EXISTS Post(
    id SERIAL PRIMARY KEY,
    author INTEGER REFERENCES Users(id) ON DELETE CASCADE NOT NULL,
    date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL,
    text VARCHAR(800),
    photo VARCHAR(800),
    visibility relationship_type NOT NULL
);


CREATE TABLE IF NOT EXISTS CommentPost(
    id SERIAL PRIMARY KEY,
    author INTEGER REFERENCES Users(id) ON DELETE CASCADE NOT NULL,
    post INTEGER REFERENCES post(id) ON DELETE CASCADE NOT NULL,
    text VARCHAR(800) NOT NULL,
    date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL
);


CREATE TABLE IF NOT EXISTS CommentPostNotification(
    id SERIAL PRIMARY KEY,
    comment INTEGER REFERENCES CommentPost(id) ON DELETE CASCADE NOT NULL,
    userId INTEGER REFERENCES Users(id) ON DELETE CASCADE NOT NULL,
    text VARCHAR(800) NOT NULL,
    read BOOLEAN NOT NULL DEFAULT FALSE,
    date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL,
    UNIQUE (comment, userId)
);

CREATE TABLE IF NOT EXISTS CommentReply(
    id SERIAL PRIMARY KEY,
    author INTEGER REFERENCES Users(id) ON DELETE CASCADE NOT NULL,
    comment INTEGER REFERENCES commentpost(id) ON DELETE CASCADE NOT NULL,
    text VARCHAR(800) NOT NULL,
    date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL
);

CREATE TABLE IF NOT EXISTS CommentPostReaction(
    id SERIAL PRIMARY KEY,
    userId INTEGER REFERENCES Users(id) ON DELETE CASCADE NOT NULL,
    comment INTEGER REFERENCES commentpost(id) ON DELETE CASCADE NOT NULL,
    date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL,
    type reactions NOT NULL,
    UNIQUE (comment,userId)
);

CREATE TABLE IF NOT EXISTS CommentPostReactionNotification(
    id SERIAL PRIMARY KEY,
    reaction INTEGER REFERENCES commentpostreaction(id) ON DELETE CASCADE NOT NULL,
    text VARCHAR(800),
    read BOOLEAN NOT NULL DEFAULT FALSE,
    date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL
);

CREATE TABLE IF NOT EXISTS CommentReplyReaction(
    id SERIAL PRIMARY KEY,
    userId INTEGER REFERENCES Users(id) ON DELETE CASCADE NOT NULL,
    comment INTEGER REFERENCES commentreply(id) ON DELETE CASCADE NOT NULL,
    date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL,
    type reactions NOT NULL,
    UNIQUE (comment,userId)
);

CREATE TABLE IF NOT EXISTS PostReaction(
    id SERIAL PRIMARY KEY,
    post INTEGER REFERENCES post(id) ON DELETE CASCADE NOT NULL,
    userId INTEGER REFERENCES Users(id) ON DELETE CASCADE NOT NULL,
    date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL,
    type reactions NOT NULL,
    UNIQUE (post,userId)
);

CREATE TABLE IF NOT EXISTS PostReactionNotification(
    reaction INTEGER REFERENCES postreaction(id) ON DELETE CASCADE NOT NULL,
    text VARCHAR(800),
    read BOOLEAN NOT NULL DEFAULT FALSE,
    date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL
);

CREATE TABLE IF NOT EXISTS Relationship(
    id SERIAL PRIMARY KEY,
    user1 INTEGER REFERENCES Users(id) ON DELETE CASCADE NOT NULL,
    user2 INTEGER REFERENCES Users(id) ON DELETE CASCADE NOT NULL,
    type relationship_type NOT NULL,
    state relationship_state NOT NULL DEFAULT 'pending',
    date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL,
    UNIQUE (user1,user2,type)
);

CREATE TABLE IF NOT EXISTS RelationshipNotification(
    id SERIAL PRIMARY KEY,
    relationship INTEGER REFERENCES relationship(id) ON DELETE CASCADE NOT NULL,
    text VARCHAR(800),
    read BOOLEAN NOT NULL DEFAULT FALSE,
    date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL
);



-- -- TRIGGER01 - When a new comment is created, a notification is created for the author of the post, with the name of who commented
CREATE FUNCTION create_comment_post_notification() RETURNS trigger AS $$
DECLARE 
    authorName VARCHAR(255);
    text VARCHAR(800);
BEGIN
    SELECT name INTO authorName FROM Users WHERE id = NEW.author;
    text := authorName || ' commented on your post';
    INSERT INTO CommentPostNotification (comment, userId, text) VALUES (NEW.id, NEW.post, text);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;
CREATE TRIGGER create_comment_post_notification
AFTER INSERT ON CommentPost
FOR EACH ROW
EXECUTE PROCEDURE create_comment_post_notification();



-- -- -- TRIGGER02 - When a new comment is created, a notification is created for the author of the comment
-- CREATE FUNCTION create_comment_reply_notification() RETURNS trigger AS $$
--     DECLARE 
--         authorName VARCHAR(255);
-- BEGIN
--     SELECT name INTO authorName FROM Users WHERE id = NEW.author;
--     -- create the notification text
--     NEW.text = authorName || ' replied to your comment';
--     -- insert the notification
--     INSERT INTO CommentPostReactionNotification (reaction, text) VALUES (NEW.id, NEW.text);
--     RETURN NEW;
-- END;
-- $$ LANGUAGE plpgsql;

-- CREATE TRIGGER create_comment_reply_notification
-- AFTER INSERT ON CommentReply
-- FOR EACH ROW
-- EXECUTE PROCEDURE create_comment_reply_notification();



-- TRIGGER03 - When a new relationship is created, a notification is created for the user that received the request with text "sent a relationship request: " type_of_relationship
CREATE FUNCTION create_relationship_notification() RETURNS trigger AS $$
    DECLARE newName VARCHAR(255);
    DECLARE newText VARCHAR(800);
BEGIN
    --Get the name of the user that sent the request and declare variable
    SELECT name INTO newName FROM Users WHERE id = NEW.user1;
    -- create the notification text
    newText = newName || ' sent a relationship request: ' || NEW.type;
    -- insert the notification
    INSERT INTO RelationshipNotification (relationship, text) VALUES (NEW.id, newText);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER create_relationship_notification
AFTER INSERT ON Relationship
FOR EACH ROW
EXECUTE PROCEDURE create_relationship_notification();



-- TRIGGER04 - When a Relationship is accepted, a notification is created for the user that sent the request with text "accepted your relationship request: " type_of_relationship and UPDATE the correspondent RelationshipNotification read is set to true
CREATE FUNCTION create_relationship_accepted_notification() RETURNS trigger AS $$
    DECLARE newName VARCHAR(255);
    DECLARE newText VARCHAR(800);
BEGIN
    UPDATE RelationshipNotification SET read = TRUE WHERE relationship = NEW.id;
    --Get the name of the user that sent the request and declare variable
    SELECT name INTO newName FROM Users WHERE id = NEW.user1;
    -- create the notification text
    newText = newName || ' accepted your relationship request: ' || NEW.type;
    -- insert the notification
    INSERT INTO RelationshipNotification (relationship, text) VALUES (NEW.id, newText);
    -- update the notification
    UPDATE RelationshipNotification SET read = TRUE WHERE relationship = NEW.id;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER create_relationship_accepted_notification
AFTER UPDATE ON Relationship
FOR EACH ROW
WHEN (NEW.state = 'accepted')
EXECUTE PROCEDURE create_relationship_accepted_notification();



-- TRIGGER05 - When a new post is reacted to, a notification is created for the author of the post
CREATE FUNCTION create_post_reaction_notification() RETURNS trigger AS $$
    DECLARE newName VARCHAR(255);
    DECLARE newText VARCHAR(800);
BEGIN
    --Get the name of the user that sent the request and declare variable
    SELECT name INTO newName FROM Users WHERE id = NEW.userId;
    -- create the notification text
    newText = newName || ' reacted to your post';
    -- insert the notification
    INSERT INTO PostReactionNotification (reaction, text) VALUES (NEW.id, newText);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER create_post_reaction_notification
AFTER INSERT ON PostReaction
FOR EACH ROW
EXECUTE PROCEDURE create_post_reaction_notification();


-- TRIGGER06 - When a new comment is reacted to, a notification is created for the author of the comment
CREATE FUNCTION create_comment_post_reaction_notification() RETURNS trigger AS $$
    DECLARE newName VARCHAR(255);
    DECLARE newText VARCHAR(800);
BEGIN
    --Get the name of the user that sent the request and declare variable
    SELECT name INTO newName FROM Users WHERE id = NEW.userId;
    -- create the notification text
    newText = newName || ' reacted to your comment';
    -- insert the notification
    INSERT INTO CommentPostReactionNotification (reaction, text) VALUES (NEW.id, newText);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER create_comment_post_reaction_notification
AFTER INSERT ON CommentPostReaction
FOR EACH ROW
EXECUTE PROCEDURE create_comment_post_reaction_notification();



-- TRIGGER07 - When a comment reply is reacted to, a notification is created for the author of the comment
-- TRIGGER07
CREATE FUNCTION create_comment_reply_reaction_notification() RETURNS trigger AS $$
    DECLARE newName VARCHAR(255);
    DECLARE newText VARCHAR(800);
BEGIN
    --Get the name of the user that sent the request and declare variable
    SELECT name INTO newName FROM Users WHERE id = NEW.userId;
    -- create the notification text with the type of reaction
    newText = newName || ' reacted to your comment';
    -- insert the notification
    INSERT INTO CommentReplyReactionNotification (reaction, text) VALUES (NEW.id, newText);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER create_comment_reply_reaction_notification
AFTER INSERT ON CommentReplyReaction
FOR EACH ROW
EXECUTE PROCEDURE create_comment_reply_reaction_notification();

