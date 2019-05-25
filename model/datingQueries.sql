CREATE TABLE member
(
  member_id INT PRIMARY KEY AUTO_INCREMENT,
  fname VARCHAR(40) NOT NULL,
  lname VARCHAR(40) NOT NULL,
  age INT NOT NULL,
  gender VARCHAR(6),
  phone VARCHAR(15) NOT NULL,
  email VARCHAR(255) NOT NULL,
  state CHAR(20) NOT NULL,
  seeking VARCHAR(6),
  bio TEXT,
  premium TINYINT(1) NOT NULL,
  image VARCHAR(80) NOT NULL
);

CREATE TABLE interest
(
  interest_id INT PRIMARY KEY AUTO_INCREMENT,
  interest VARCHAR(20),
  type VARCHAR(7)
);

CREATE TABLE member_interest
(
  member_id INT,
  interest_id INT,
  PRIMARY KEY (member_id, interest_id),
  FOREIGN KEY (member_id) REFERENCES member(member_id),
  FOREIGN KEY (interest_id) REFERENCES interest(interest_id)
);

INSERT into interest(interest, type)
VALUES
('tv', 'indoor'),
('movies', 'indoor'),
('cooking', 'indoor'),
('board games', 'indoor'),
('puzzles', 'indoor'),
('reading', 'indoor'),
('playing cards', 'indoor'),
('video games', 'indoor'),
('hiking', 'outdoor'),
('biking', 'outdoor'),
('swimming', 'outdoor'),
('collecting', 'outdoor'),
('walking', 'outdoor'),
('climbing', 'outdoor');