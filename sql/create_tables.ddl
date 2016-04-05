drop table jk_writins;
drop table jk_votes;
drop table jk_team;
drop table jk_projects;
drop table jk_users;



CREATE TABLE jk_projects(
  name varchar(15) PRIMARY KEY,
  isopen boolean
);

CREATE TABLE jk_users(
  linux_user varchar(8) PRIMARY KEY,
  name varchar(20),
  password_hash char(128)
);

CREATE TABLE jk_team(
  team_id INT,
  project varchar(15),
  user varchar(8),
  PRIMARY KEY (team_id, project, user),
  FOREIGN KEY (user) REFERENCES jk_users(linux_user),
  FOREIGN KEY (project) REFERENCES jk_projects(name)
);

CREATE TABLE jk_votes(
  user varchar(8),
  project varchar(15),
  first INT,
  second INT,
  third INT,
  PRIMARY KEY (user, project),
  FOREIGN KEY (user) REFERENCES jk_users(linux_user),
  FOREIGN KEY (project) REFERENCES jk_projects(name),
  FOREIGN KEY (first) REFERENCES jk_team(team_id)
	ON DELETE CASCADE
	ON UPDATE CASCADE,
  FOREIGN KEY (second) REFERENCES jk_team(team_id)
  	ON DELETE CASCADE
	ON UPDATE CASCADE,
  FOREIGN KEY (third) REFERENCES jk_team(team_id)
  	ON DELETE CASCADE
	ON UPDATE CASCADE
);

CREATE TABLE jk_writins(
  user varchar(8),
  project varchar(15),
  team_id INT,
  award varchar(256),
  PRIMARY KEY (user, project, team_id, award),
  FOREIGN KEY (user) REFERENCES jk_users(linux_user),
  FOREIGN KEY (team_id) REFERENCES jk_team(team_id),
  FOREIGN KEY (project) REFERENCES jk_projects(name)
);