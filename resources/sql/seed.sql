DROP SCHEMA IF EXISTS lbaw CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw;
SET search_path TO lbaw;

DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS cards CASCADE;
DROP TABLE IF EXISTS posts CASCADE;
DROP TABLE IF EXISTS items CASCADE;

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

CREATE TABLE cards (
  id SERIAL PRIMARY KEY,
  name VARCHAR NOT NULL,
  user_id INTEGER REFERENCES users NOT NULL
);

CREATE TABLE posts(
  id SERIAL PRIMARY KEY,
  user_id INTEGER REFERENCES users ON DELETE CASCADE NOT NULL,
  date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL,
  text VARCHAR(800),
  photo VARCHAR(800),
  visibility relationship_type NOT NULL
);

CREATE TABLE items (
  id SERIAL PRIMARY KEY,
  card_id INTEGER NOT NULL REFERENCES cards ON DELETE CASCADE,
  description VARCHAR NOT NULL,
  done BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE comments(
  id SERIAL PRIMARY KEY,
  user_id INTEGER REFERENCES users ON DELETE CASCADE NOT NULL,
  post_id INTEGER REFERENCES posts ON DELETE CASCADE NOT NULL,
  text VARCHAR(800) NOT NULL,
  date TIMESTAMP DEFAULT (NOW() at time zone 'utc') NOT NULL
);



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

INSERT INTO cards VALUES (DEFAULT, 'Things to do', 1);
INSERT INTO items VALUES (DEFAULT, 1, 'Buy milk');
INSERT INTO items VALUES (DEFAULT, 1, 'Walk the dog', true);

INSERT INTO cards VALUES (DEFAULT, 'Things not to do', 1);
INSERT INTO items VALUES (DEFAULT, 2, 'Break a leg');
INSERT INTO items VALUES (DEFAULT, 2, 'Crash the car');

INSERT INTO posts(user_id, text, photo, visibility) VALUES(1,'10 Python Mini Automation Projects','https://pbs.twimg.com/profile_images/429285908953579520/InZKng9-_x96.jpeg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(1,'The 3 best ADVANCED techniques I learned while building in public last month were:,,1) How to use Chrome Inspect Console properly, especially the Network - Fetch/XHR - Responses feature.,,Understanding the requests from a site is life changing for web scraping.,,(1/3)','https://pbs.twimg.com/profile_images/1461710621397897223/XZciUUbZ_x96.jpg','Friends');
INSERT INTO posts(user_id, text, photo, visibility) VALUES(2,'Halloween is coming','https://pbs.twimg.com/profile_images/1269253433703510016/B6XjoBkv_x96.jpg','Friends');

INSERT INTO comments(user_id, post_id, text) VALUES(2, 1, 'Thank you for the information');
INSERT INTO comments(user_id, post_id, text) VALUES(1, 3, 'I will try it');
