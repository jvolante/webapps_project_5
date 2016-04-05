<?php
if(isset($_GET["projectname"])){
	include '/../sqlserverparams.php';

	$projectName = $_GET["projectname"];

	$conn = new mysqli($serverAddress, $serverUser, $serverPassword);

	if($conn->connect_error){
	die("Database Connection Failed");
	}

	$result = $conn->query("SELECT linux_user, name FROM $dbname.jk_users;");

	if (! $result){ 
		// probably a syntax error in your SQL, 
		// but could be some other error
		throw new Db_Query_Exception("DB Error: " . mysql_error()); 
	}
	$nameMap = array();
	while($row = mysqli_fetch_assoc($result)){
		$nameMap[$row['linux_user']] = $row['name'];
	}
	
	$result = $conn->query("SELECT distinct team_id, user FROM $dbname.jk_team WHERE project = '$projectName' ORDER BY team_id;");

	$teams = array();
	while($row = mysqli_fetch_assoc($result)){
		if(!isset($teams[$row["team_id"]])){
			$teams[$row["team_id"]] = array();
		}
		$luser =  $row["user"];
		$name = $nameMap[$luser];
		array_push($teams[$row["team_id"]], $name);
	}
	
	$result = $conn->query("SELECT * FROM $dbname.jk_project_votes;");
	
	$teamScoreMap = array();
	while($row = mysqli_fetch_assoc($result)){
		if(array_key_exists($row['team_id'], $teams)){
			$scores = new stdClass();
			$scores->firstVotes = $row['firsts'];
			$scores->secondVotes = $row['seconds'];
			$scores->thirdVotes = $row['thirds'];
			$scores->names = $teams[$row['team_id']];
			$teamScoreMap[$row['team_id']] = $scores;
		}
	}

	echo json_encode($teamScoreMap);
}
?>
