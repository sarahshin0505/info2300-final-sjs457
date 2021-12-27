BEGIN TRANSACTION;

CREATE TABLE images(
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    file_name TEXT NOT NULL,
	file_ext TEXT NOT NULL,
    citation TEXT NOT NULL,
	description TEXT
);

CREATE TABLE tags(
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    image_id INTEGER NOT NULL,
    tag TEXT NOT NULL
);

CREATE TABLE location_tags(
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	location TEXT NOT NULL
);

CREATE TABLE all_tags(
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    image_id INTEGER NOT NULL,
    location_id INTEGER,
    tag_id INTEGER NOT NULL
);


INSERT INTO images (id, file_name, file_ext, citation, description) VALUES (1, 'bingsoo', 'JPG', 'Sarah Shin', 'Bingsoo');
INSERT INTO images (id, file_name, file_ext, citation, description) VALUES (2,'bread_and_cheese', 'jpg', 'Sarah Shin', 'Bread and burrata cheese');
INSERT INTO images (id,  file_name, file_ext, citation, description) VALUES (3, 'pancake', 'jpg', 'Sarah Shin', 'Souffle Pancakes');
INSERT INTO images (id,  file_name, file_ext, citation, description) VALUES (4, '4', 'png', 'Sarah Shin', 'Pasta');
INSERT INTO images (id,  file_name, file_ext, citation, description) VALUES (5, '5', 'jpg', 'Sarah Shin', 'Spicy rice cakes');
INSERT INTO images (id,  file_name, file_ext, citation, description) VALUES (6, '6', 'jpg', 'Sarah Shin', 'Sushi');
INSERT INTO images (id,  file_name, file_ext, citation, description) VALUES (7, '7', 'jpg', 'Sarah Shin', 'Taiyaki');
INSERT INTO images (id,  file_name, file_ext, citation, description) VALUES (8, '8', 'JPG', 'Sarah Shin', 'Meat');
INSERT INTO images (id,  file_name, file_ext, citation, description) VALUES (9, '9', 'png', 'Sarah Shin', 'Squid');
INSERT INTO images (id,  file_name, file_ext, citation, description) VALUES (10, '10', 'JPG', 'Sarah Shin', 'Avocado Toast');

INSERT INTO tags (id, image_id, tag) VALUES (1,1, 'Grace Street');
INSERT INTO tags (id,image_id,  tag) VALUES (2,2, 'RH Rooftop');
INSERT INTO tags (id, image_id,  tag) VALUES (3,3, 'Flippers');
INSERT INTO tags (id, image_id, tag) VALUES (4, 4, 'Marea');
INSERT INTO tags (id, image_id, tag) VALUES (5, 5, 'Yeopki Teokbokki');
INSERT INTO tags (id, image_id, tag) VALUES (6, 6, 'Sushi bar');
INSERT INTO tags (id, image_id, tag) VALUES (7, 7, 'Dessert');
INSERT INTO tags(id, image_id, tag) VALUES (8,1,'Yum');
INSERT INTO tags(id, image_id, tag) VALUES (9,5,'Spicy');
INSERT INTO tags(id, image_id, tag) VALUES (10,1,'Dessert');
INSERT INTO tags(id, image_id, tag) VALUES (11,8,'Sliced meats');
INSERT INTO tags(id, image_id, tag) VALUES (12,9,'Squid');
INSERT INTO tags(id, image_id, tag) VALUES (13,10,'Avocado Toast');


INSERT INTO location_tags (id, location) VALUES (1, 'Queens');
INSERT INTO location_tags (id, location) VALUES (2, 'Manhattan');
INSERT INTO location_tags (id, location) VALUES (3, 'Brooklyn');
INSERT INTO location_tags (id, location) VALUES (4, 'The Bronx');
INSERT INTO location_tags (id, location) VALUES (5, 'Staten Island');

INSERT INTO all_tags (id, image_id, location_id, tag_id) VALUES (1,1,2,1);
INSERT INTO all_tags (id, image_id, location_id, tag_id) VALUES (2,2,2,2);
INSERT INTO all_tags (id, image_id, location_id, tag_id) VALUES (3,3,2,3);
INSERT INTO all_tags (id, image_id, location_id, tag_id) VALUES (4,4,2,4);
INSERT INTO all_tags (id, image_id, location_id, tag_id) VALUES (5,5,1,5);
INSERT INTO all_tags (id, image_id, location_id, tag_id) VALUES (6,6,2,6);
INSERT INTO all_tags (id, image_id, location_id, tag_id) VALUES (7,7,2,7);
INSERT INTO all_tags (id, image_id, location_id, tag_id) VALUES (8,1,NULL,8);
INSERT INTO all_tags (id, image_id, location_id, tag_id) VALUES (9,2,NULL,8);
INSERT INTO all_tags (id, image_id, location_id, tag_id) VALUES (10,5,NULL,9);
INSERT INTO all_tags (id, image_id, location_id, tag_id) VALUES (11,1,NULL,10);
INSERT INTO all_tags (id, image_id, location_id, tag_id) VALUES (12,8,4,11);
INSERT INTO all_tags (id, image_id, location_id, tag_id) VALUES (13,9,2,12);
INSERT INTO all_tags (id, image_id, location_id, tag_id) VALUES (14,10,3,13);
INSERT INTO all_tags (id, image_id, location_id, tag_id) VALUES (15,10,NULL,8);


COMMIT;
