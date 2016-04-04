<?php
if(isset($_GET["projectname"])){
	include '/../sqlserverparams.php';

	// Create connection
	$conn = new mysqli($servername, $username, $password);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	$result = $conn->query("SELECT linux_user, name FROM pca.jk_users;");

	if (! $result){ 
		// probably a syntax error in your SQL, 
		// but could be some other error
		throw new Db_Query_Exception("DB Error: " . mysql_error()); 
	}
	$rows = array();
	while($row = mysqli_fetch_assoc($result)){
		$rows[$row['linux_user']] = $row['name'];
	}
	
	echo json_encode($rows);
}
?>