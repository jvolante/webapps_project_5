CREATE VIEW project_votes AS
SELECT project, team_id, firsts, seconds, thirds, (firsts * 3 + seconds * 2 + thirds) points
FROM team,
     (SELECT COUNT(first) FROM votes WHERE first = team_id AND votes.project = project) firsts,
     (SELECT COUNT(second) FROM votes WHERE second = team_id AND votes.project = project) seconds,
     (SELECT COUNT(third) FROM votes WHERE first = team_id AND votes.project = project) thirds;
