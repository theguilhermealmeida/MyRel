DROP SCHEMA IF EXISTS lbaw2212 CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw2212;
SET search_path TO lbaw2212;

CREATE TYPE relationship_type AS ENUM ('Close Friends', 'Friends', 'Family');
CREATE TYPE relationship_state AS ENUM ('pending', 'accepted');
CREATE TYPE gender AS ENUM ('Male', 'Female', 'Other');
CREATE TYPE reactions AS ENUM ('Like', 'Dislike', 'Sad', 'Angry', 'Amazed');

CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  gender gender NULL,
  description VARCHAR(800),
  photo VARCHAR(255),
  password VARCHAR NOT NULL,
  ban BOOLEAN NOT NULL DEFAULT FALSE,
  remember_token VARCHAR
);

CREATE TABLE posts(
  id SERIAL PRIMARY KEY,
  user_id INTEGER REFERENCES users ON DELETE CASCADE NOT NULL,
  date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL,
  text VARCHAR(800),
  photo VARCHAR(800),
  visibility relationship_type,
  family BOOLEAN NOT NULL DEFAULT FALSE
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
   0,
  'admin',
  'admin@example.com',
  'Male',
  'Sou lindo',
  'https://robohash.org/solutacumqueet.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')

INSERT INTO users VALUES (
  DEFAULT,
  'John Doe',
  'johndoe@example.com',
  'Male',
  'Gosto de gatos',
  'https://robohash.org/solutacumqueet.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')

INSERT INTO users VALUES (
  DEFAULT,
  'Tiago',
  'tiago@example.com',
  'Male',
  'FC PORTO <3',
  'https://robohash.org/sintbeataefugiat.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')

INSERT INTO users VALUES (
  DEFAULT,
  'Rockie Ablitt',
  'rablitt2@rambler.ru',
  'Male',
  'empower global e-commerce',
  'https://robohash.org/architectodoloremnon.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')

INSERT INTO users VALUES (
  DEFAULT,
  'Wesley Pinckstone',
  'wpinckstone3@ox.ac.uk',
  'Female',
  'repurpose enterprise functionalities',
  'https://robohash.org/nisipraesentiumqui.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Marsh Monsey',
  'mmonsey4@friendfeed.com',
  'Male',
  'synergize distributed niches',
  'https://robohash.org/sedassumendadebitis.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Brewster Houchin',
  'bhouchin5@uol.com.br',
  'Female',
  'maximize visionary schemas',
  'https://robohash.org/etquiid.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Augie Roostan',
  'aroostan6@columbia.edu',
  'Female',
  'redefine extensible supply-chains',
  'https://robohash.org/quiaetinventore.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Sonny Fawkes',
  'sfawkes7@gnu.org',
  'Male',
  'strategize value-added partnerships',
  'https://robohash.org/doloremexvoluptatem.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Josefina Simpkiss',
  'jsimpkiss8@networkadvertising.org',
  'Male',
  'enable impactful experiences',
  'https://robohash.org/pariaturofficiaquia.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Hilary Dulwitch',
  'hdulwitch9@slideshare.net',
  'Male',
  'utilize front-end systems',
  'https://robohash.org/aliasvoluptateseveniet.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Shanan Venturoli',
  'sventurolia@guardian.co.uk',
  'Female',
  'mesh impactful web services',
  'https://robohash.org/perferendisremesse.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')

INSERT INTO users VALUES (
  DEFAULT,
  'Norbert Koubek',
  'nkoubekb@vk.com',
  'Male',
  'optimize 24/7 users',
  'https://robohash.org/corporisidiure.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Alla Stoppe',
  'astoppec@blog.com',
  'Female',
  'integrate visionary solutions',
  'https://robohash.org/optiovelitvoluptatum.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Silvan Bock',
  'sbockd@creativecommons.org',
  'Male',
  'disintermediate e-business interfaces',
  'https://robohash.org/temporaplaceatnihil.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Tabbatha Willarton',
  'twillartone@pen.io',
  'Female',
  'cultivate open-source paradigms',
  'https://robohash.org/voluptasnequenisi.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Alaster Bollam',
  'abollamf@gizmodo.com',
  'Female',
  'engage sticky vortals',
  'https://robohash.org/dolorvoluptatibusesse.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Hamil Pringell',
  'hpringellg@smh.com.au',
  'Male',
  'harness bleeding-edge markets',
  'https://robohash.org/iustoesseconsequuntur.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Garrek Brendeke',
  'gbrendekeh@guardian.co.uk',
  'Male',
  'evolve seamless architectures',
  'https://robohash.org/idsuntipsa.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Kizzie Gailor',
  'kgailori@oakley.com',
  'Male',
  'unleash cross-media infomediaries',
  'https://robohash.org/estmaximeoptio.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Ruy ODowne',
  'rodownej@constantcontact.com',
  'Male',
  'reinvent impactful functionalities',
  'https://robohash.org/minimamolestiaelaudantium.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Celina Godon',
  'cgodonk@sciencedirect.com',
  'Female',
  'redefine dot-com web-readiness',
  'https://robohash.org/maioreslaudantiumdignissimos.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Rona Quittonden',
  'rquittondenl@etsy.com',
  'Female',
  'generate extensible relationships',
  'https://robohash.org/totamvitaeaut.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')

INSERT INTO users VALUES (
  DEFAULT,
  'Cathy Ludron',
  'cludronm@elegantthemes.com',
  'Male',
  'synergize world-class eyeballs',
  'https://robohash.org/distinctioconsequaturtempora.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Andrej_Scoggans',
  'ascoggansn@discuz.net',
  'Male',
  'deploy robust infomediaries',
  'https://robohash.org/abeaquevoluptas.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Trixy Broughton',
  'tbroughtono@merriam-webster.com',
  'Female',
  'leverage cutting-edge platforms',
  'https://robohash.org/quiadbeatae.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Shalna Calbert',
  'scalbertp@pen.io',
  'Female',
  'target leading-edge bandwidth',
  'https://robohash.org/autfugaiusto.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Orin Dearle-Palser',
  'odearlepalserq@rediff.com',
  'Female',
  'seize open-source e-tailers',
  'https://robohash.org/dignissimosoptioofficia.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W'
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Brittaney Emblin',
  'bemblinr@bizjournals.com',
  'Male',
  'strategize visionary e-markets',
  'https://robohash.org/natusplaceatmolestiae.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W',
  TRUE
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Adina Phebey',
  'aphebeys@umn.edu',
  'Female',
  'e-enable intuitive relationships',
  'https://robohash.org/etquiet.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W',
  TRUE
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO users VALUES (
  DEFAULT,
  'Darci Aslum',
  'daslumt@dion.ne.jp',
  'Male',
  'transition 24/365 schemas',
  'https://robohash.org/aperiamnumquammaiores.png?size=50x50&set=set1',
  '$2y$10$fh7ZzO4ki1gbq4gc4.t6se3fZEOYhrtt0tiaE4GwDQtVjSgWBp77W',
  TRUE
); -- Password is 123456. Generated using Hash::make('123456')


INSERT INTO posts(user_id, text, photo, visibility) VALUES(1,'10 Python Mini Automation Projects','https://pbs.twimg.com/profile_images/429285908953579520/InZKng9-_x96.jpeg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(1,'The 3 best ADVANCED techniques I learned while building in public last month were:,,1) How to use Chrome Inspect Console properly, especially the Network - Fetch/XHR - Responses feature.,,Understanding the requests from a site is life changing for web scraping.,,(1/3)','https://pbs.twimg.com/profile_images/1461710621397897223/XZciUUbZ_x96.jpg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(18,'Halloween is coming','https://pbs.twimg.com/profile_images/1269253433703510016/B6XjoBkv_x96.jpg',NULL);
INSERT INTO posts(user_id, text, photo, visibility) VALUES(6,'October traffic has been in the red so far.,,Heres how I plan to make it green: Create helpful and relevant posts in communities (Reddit, Facebook, IH),, Run an engineering as marketing campaign. Experiment with content marketing. As usual, will share everything here ','https://pbs.twimg.com/profile_images/1535551343036964865/13nIczOn_x96.jpg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(5,'Puppy thinks hes a bunny.. ','https://pbs.twimg.com/profile_images/1130022182971760640/FlbICzEn_x96.jpg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(5,'The Psychology of Manipulation You Should Know Before Its Too Late...','https://pbs.twimg.com/profile_images/1431998771349626882/614uNv3Y_x96.jpg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(24,' Hacked filter: sepia() to colorize white walls by masking them with a canvas from an image from auto detected segment that adds it as a image-mask to the image ,,So that you user can soon colorize any walls with a slider','https://pbs.twimg.com/profile_images/1562107516066095106/IUccJ78Y_x96.jpg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(5,'-you*','https://pbs.twimg.com/profile_images/1562107516066095106/IUccJ78Y_x96.jpg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(16,'20 must-watch movies that will change your life & mindset:,,1. Schindler’s List','https://pbs.twimg.com/profile_images/1568369300909473793/JUHo6Ali_x96.jpg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(7,'Let us not forget the Indian version of Michael Jacksons THRILLER!','https://pbs.twimg.com/profile_images/1243734573764071424/wTvOVSyJ_x96.jpg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(4,'Heading for a $5k+ month ,,Someone once told me I`d never make it into the 40% tax bracket and yet here we are. A big thanks to all my clients, sponsors and supporters','https://abs-0.twimg.com/emoji/v2/svg/26a1.svg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(25,'Congratulations to Ulf Kristersson ,@moderaterna, on being elected PM of Sweden. Im sure - cooperation will continue active development, and ’s support in the fight against Russian aggression will increase. I am grateful to Magdalena Andersson for solidarity & supporting .','https://pbs.twimg.com/profile_images/1215070700026855425/7edvU72D_x96.jpg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(21,'Steps to become a data analyst (my preference) :,,Stats (yes),↓,,SQL,↓,,Storytelling with data,(Tableau/ PowerBI),↓,,Build dashboards,↓,,Python,↓,,Visualization libraries,(Pandas, Matplotlib, NumPy, Seaborn),Portfolio projects,(From data cleaning → Data analysis)','https://pbs.twimg.com/profile_images/1581653962822152193/YxHE5AVT_x96.jpg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(14,'En route to SF for TechCrunch Disrupt. ,,Seems like we have a fun crew here already. Who else should I meet?,,And who wants to join our tech crew?','https://abs-0.twimg.com/emoji/v2/svg/1f499.svg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(7,'A historic failure','https://pbs.twimg.com/profile_images/1085150433960706048/_T5T_iZY_x96.jpg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(5,'Trabalhe mais feliz com os portáteis HP com a plataforma Intel® Evo™. Com a funcionalidade HP de redução do ruído pode trabalhar de onde quiser, quando quiser.','https://pbs.twimg.com/profile_images/1542882499851845635/wwafo2gn_x96.jpg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(24,'Uncanny ','https://pbs.twimg.com/profile_images/1210913904597110784/8tFdcHPs_x96.jpg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(5,'Mountain Climber Fights Off Bear (2022)','https://pbs.twimg.com/profile_images/1495825338806554631/9g1Bm6qt_x96.jpg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(17,'Playful bear seems to dance in the forest','https://pbs.twimg.com/profile_images/557958031606939649/jExOWkdO_x96.jpeg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(9,'Lose your belly fat','https://pbs.twimg.com/profile_images/1581205234407247872/eeoMBwIb_x96.jpg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(19,'Submitting Tailscan to the Chrome Web Store later today! Yesterday, I finished one of the last core features:,,Tailwind CSS config support!,,You can save your own config so that all your custom classes and variants are available','https://pbs.twimg.com/profile_images/1574976813272813570/qASrP2yS_x96.jpg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(3,'mhuaw','https://pbs.twimg.com/profile_images/1581003527735894017/Kwb-vg5A_x96.jpg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(24,'If you want to build an African startup, its not about copying the Silicon Valley model.,,Its about finding the *African* way to do things. Different problems, different contexts, different solutions.','https://pbs.twimg.com/profile_images/1480420844941848580/eiJBfe6h_x96.jpg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(23,'Do you work in English? Check our specialized search engine for English-speaking professionals looking for opportunities in Portugal.','https://pbs.twimg.com/profile_images/481545308094996480/pP7T4KkS_x96.png','Friends');

INSERT INTO comments(user_id, post_id, text) VALUES(2, 1, 'Thank you for the information');
INSERT INTO comments(user_id, post_id, text) VALUES(1, 3, 'I will try it');
INSERT INTO comments(user_id, post_id, text) VALUES(3, 5, 'Thats really nice!!!');
INSERT INTO comments(user_id, post_id, text) VALUES(4, 1, 'I will try it it soon, thank you');
INSERT INTO comments(user_id, post_id, text) VALUES(4, 4, 'I dont know if it will work');
INSERT INTO comments(user_id, post_id, text) VALUES(5, 1, 'I think that building a website is not that hard');
INSERT INTO comments(user_id, post_id, text) VALUES(5, 2, 'Well deserved. You write good content. Glad to have you here');
INSERT INTO comments(user_id, post_id, text) VALUES(6, 4, 'Cheers to more good reads.');
INSERT INTO comments(user_id, post_id, text) VALUES(6, 1, 'I use Linkedin mainly to offer and find new job opportunities.');
INSERT INTO comments(user_id, post_id, text) VALUES(19, 1, 'I wish you the best for 2022!!!');
INSERT INTO comments(user_id, post_id, text) VALUES(13, 7, 'I am not a master. but I like Master of Puppets and I have been a D&D Dungeon Master. does it count?');
INSERT INTO comments(user_id, post_id, text) VALUES(14, 7, 'Wishing you more success and great year');

INSERT INTO replies(user_id, comment_id, text) VALUES(1, 1, 'You are welcome :)');
INSERT INTO replies(user_id, comment_id, text) VALUES(2, 2, 'Cheers to more good reads.');
INSERT INTO replies(user_id, comment_id, text) VALUES(9, 5, 'I am glad you liked it');
INSERT INTO replies(user_id, comment_id, text) VALUES(10, 1, 'Looking forward to learn more from you in 2022.');
INSERT INTO replies(user_id, comment_id, text) VALUES(16, 6, 'Good luck on your journey Captain!');
INSERT INTO replies(user_id, comment_id, text) VALUES(11, 6, 'Will always follow you');
INSERT INTO replies(user_id, comment_id, text) VALUES(12, 8, 'You are definitely a great person, already following you.');
INSERT INTO replies(user_id, comment_id, text) VALUES(18, 9, 'Have just downloaded Docker to complete @freeCodeCamp Relational Database Cert using Docker and VSCode... wish me luck!');


INSERT INTO postreactions(post_id, user_id, type) VALUES(1, 2, 'Amazed');
INSERT INTO postreactions(post_id, user_id, type) VALUES(3, 1, 'Dislike');
INSERT INTO postreactions(post_id, user_id, type) VALUES(1, 3, 'Sad');
INSERT INTO postreactions(post_id, user_id, type) VALUES(1, 4, 'Like');
INSERT INTO postreactions(post_id, user_id, type) VALUES(1, 5, 'Dislike');
INSERT INTO postreactions(post_id, user_id, type) VALUES(1, 6, 'Amazed');
INSERT INTO postreactions(post_id, user_id, type) VALUES(1, 7, 'Amazed');
INSERT INTO postreactions(post_id, user_id, type) VALUES(1, 8, 'Dislike');
INSERT INTO postreactions(post_id, user_id, type) VALUES(1, 9, 'Like');
INSERT INTO postreactions(post_id, user_id, type) VALUES(1, 10, 'Sad');
INSERT INTO postreactions(post_id, user_id, type) VALUES(1, 11, 'Like');
INSERT INTO postreactions(post_id, user_id, type) VALUES(2, 12, 'Amazed');
INSERT INTO postreactions(post_id, user_id, type) VALUES(2, 13, 'Dislike');
INSERT INTO postreactions(post_id, user_id, type) VALUES(2, 14, 'Like');
INSERT INTO postreactions(post_id, user_id, type) VALUES(2, 15, 'Like');
INSERT INTO postreactions(post_id, user_id, type) VALUES(2, 16, 'Amazed');
INSERT INTO postreactions(post_id, user_id, type) VALUES(2, 17, 'Like');
INSERT INTO postreactions(post_id, user_id, type) VALUES(2, 18, 'Like');
INSERT INTO postreactions(post_id, user_id, type) VALUES(4, 19, 'Like');
INSERT INTO postreactions(post_id, user_id, type) VALUES(5, 13, 'Like');
INSERT INTO postreactions(post_id, user_id, type) VALUES(6, 12, 'Like');
INSERT INTO postreactions(post_id, user_id, type) VALUES(8, 19, 'Like');
INSERT INTO postreactions(post_id, user_id, type) VALUES(9, 11, 'Like');
INSERT INTO postreactions(post_id, user_id, type) VALUES(3, 19, 'Like');

INSERT INTO commentreactions(comment_id, user_id, type) VALUES(1, 1, 'Like');
INSERT INTO commentreactions(comment_id, user_id, type) VALUES(2, 2, 'Angry');

INSERT INTO replyreactions(reply_id, user_id, type) VALUES(1, 2, 'Sad');
INSERT INTO replyreactions(reply_id, user_id, type) VALUES(2, 1, 'Like');

INSERT INTO relationships(user_id, related_id, type) VALUES(1, 2, 'Close Friends');
INSERT INTO relationships(user_id, related_id, type) VALUES(1, 3, 'Close Friends');
INSERT INTO relationships(user_id, related_id, type) VALUES(1, 4, 'Close Friends');
INSERT INTO relationships(user_id, related_id, type) VALUES(1, 5, 'Close Friends');
INSERT INTO relationships(user_id, related_id, type) VALUES(1, 6, 'Friends');
INSERT INTO relationships(user_id, related_id, type) VALUES(1, 7, 'Friends');
INSERT INTO relationships(user_id, related_id, type) VALUES(2, 7, 'Friends');
INSERT INTO relationships(user_id, related_id, type) VALUES(2, 8, 'Friends');
INSERT INTO relationships(user_id, related_id, type) VALUES(2, 9, 'Family');
INSERT INTO relationships(user_id, related_id, type) VALUES(2, 10, 'Family');
INSERT INTO relationships(user_id, related_id, type) VALUES(2, 11, 'Family');
INSERT INTO relationships(user_id, related_id, type) VALUES(1, 12, 'Family');
INSERT INTO relationships(user_id, related_id, type) VALUES(1, 13, 'Family');
INSERT INTO relationships(user_id, related_id, type) VALUES(1, 14, 'Friends');
INSERT INTO relationships(user_id, related_id, type) VALUES(1, 15, 'Friends');
INSERT INTO relationships(user_id, related_id, type) VALUES(1, 16, 'Family');
INSERT INTO relationships(user_id, related_id, type) VALUES(1, 17, 'Family');
INSERT INTO relationships(user_id, related_id, type) VALUES(1, 18, 'Friends');
INSERT INTO relationships(user_id, related_id, type) VALUES(1, 19, 'Close Friends');