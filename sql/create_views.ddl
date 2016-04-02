CREATE VIEW jk_first_votes AS
SELECT COUNT(first)
FROM jk_votes WHERE jk_votes.first = jk_team.team_id AND jk_votes.project = jk_team.project;

CREATE VIEW jk_second_votes AS
SELECT COUNT(second)
FROM jk_votes WHERE jk_votes.second = jk_team.team_id AND jk_votes.project = jk_team.project;

CREATE VIEW jk_third_votes AS
SELECT COUNT(third)
FROM jk_votes WHERE jk_votes.third = jk_team.team_id AND jk_votes.project = jk_team.project;

CREATE VIEW jk_project_votes AS
SELECT project, team_id, firsts, seconds, thirds, (firsts * 3 + seconds * 2 + thirds) points
FROM team, jk_first_votes, jk_second_votes, jk_third_votes;