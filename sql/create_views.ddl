drop view jk_first_votes;
drop view jk_second_votes;
drop view jk_third_votes;

CREATE VIEW jk_first_votes AS
SELECT COUNT(first) as count
FROM jk_votes WHERE jk_votes.first = (select team_id from jk_team) AND jk_votes.project = (select project from jk_team);

CREATE VIEW jk_second_votes AS
SELECT COUNT(second) as count
FROM jk_votes WHERE jk_votes.second = (select team_id from jk_team) AND jk_votes.project = (select project from jk_team);

CREATE VIEW jk_third_votes AS
SELECT COUNT(third) as count
FROM jk_votes WHERE jk_votes.third = (select team_id from jk_team) AND jk_votes.project = (select project from jk_team);

CREATE VIEW jk_project_votes AS
SELECT project, team_id, jk_first_votes.count as firsts, jk_second_votes.count as seconds, jk_third_votes.count as thirds, (jk_first_votes.count * 3 + jk_second_votes.count * 2 + jk_third_votes.count) points
FROM jk_team, jk_first_votes, jk_second_votes, jk_third_votes;