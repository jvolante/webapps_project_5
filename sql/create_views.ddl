drop view jk_first_votes;
drop view jk_second_votes;
drop view jk_third_votes;
drop view jk_project_votes;

CREATE VIEW jk_first_votes AS
select first, count(first) firsts from jk_votes group by first;

CREATE VIEW jk_second_votes AS
select second, count(second) seconds from jk_votes group by second;

CREATE VIEW jk_third_votes AS
select third, count(third) thirds from jk_votes group by third;

CREATE VIEW jk_project_votes AS
select team_id, COALESCE(firsts, 0) firsts, 
				COALESCE(seconds, 0) seconds, 
				COALESCE(thirds, 0) thirds 
from jk_team
left join jk_first_votes 
on jk_team.team_id = jk_first_votes.first 
left join jk_second_votes
on jk_team.team_id = jk_second_votes.second
left JOIN jk_third_votes
on jk_team.team_id = jk_third_votes.third