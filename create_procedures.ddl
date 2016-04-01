CREATE PROCEDURE getVotesForTeamProject(team_id INT, project_name varchar(20))
BEGIN
  SELECT firsts, seconds, thirds, points
  FROM project_votes
  WHERE team_id = project_votes.team_id AND project_name = project_votes.project;
END
