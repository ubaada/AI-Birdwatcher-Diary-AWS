CREATE TABLE birds (
	id int PRIMARY KEY AUTO_INCREMENT,
	scientific_name varchar(100) NOT NULL,
	common_name varchar(100) NOT NULL,
	sighting_time DATETIME NOT NULL,
	location varchar(100) NOT NULL
);

INSERT INTO birds VALUES (1,'Haemorhous cassinii','Cassin\'s finch', '2018-04-26 14:00:00', 'Georgia');
INSERT INTO birds VALUES (2,'Aramus guarauna','Limpkin', '2018-05-21 09:30:00','Paris,France');
INSERT INTO birds VALUES (3,'Rupornis magnirostris','Roadside hawk', '2020-04-26 21:15:00', 'Washington, USA');