CREATE TABLE projects(
  name varchar(15) PRIMARY KEY,
  isopen boolean
);

CREATE TABLE users(
  linux_user varchar(8) PRIMARY KEY,
  name varchar(20),
  password_hash char(128)
);

CREATE TABLE team(
  team_id INT,
  project varchar(15),
  user varchar(8),
  PRIMARY KEY (team_id, project),
  FOREIGN KEY user REFERENCES users(linux_user),
  FOREIGN KEY project REFERENCES projects(name)
);

CREATE TABLE votes(
  user varchar(8),
  project varchar(15),
  first INT,
  second INT,
  third INT,
  PRIMARY KEY (user, project),
  FOREIGN KEY user REFERENCES users(linux_user),
  FOREIGN KEY first REFERENCES team(team_id),
  FOREIGN KEY second REFERENCES team(team_id),
  FOREIGN KEY third REFERENCES team(team_id)
)

CREATE TABLE writins(
  user varchar(8),
  project varchar(15),
  team_id INT,
  award varchar(256),
  PRIMARY KEY (user, project, team_id, award),
  FOREIGN KEY user REFERENCES users(linux_user),
  FOREIGN KEY team_id REFERENCES team(team_id),
  FOREIGN KEY project REFERENCES projects(name)
)
