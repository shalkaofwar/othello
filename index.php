<?php
	$link = mysqli_connect("localhost", "root", "strong_password");
	if($link === false)
	{
    	die("ERROR: Could not connect. " . mysqli_connect_error());
	}
	$sql = "CREATE DATABASE userToken";
	if(mysqli_query($link, $sql))
	{
    	//echo "Database created successfully";
	}
	else
	{
    	//echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
	}
	$db_host="localhost";
	$db_username="root";
	$db_pass="strong_password"; // TODO: make login using db table using proper salting and SHA256
	$db_name="userToken";
	$link = mysqli_connect($db_host,$db_username,$db_pass,$db_name);
	if($link === false)
	{
    	die("ERROR: Could not connect. " . mysqli_connect_error());
	}
	$sql = "create table tokenTable(token varchar(10) primary key,gridInfo varchar(70),turn int,gameAlive int,userCount int,time int)";
	if(mysqli_query($link, $sql))
	{
		//echo "Table created successfully.";
		$del = "delete from tokenTable";
		mysqli_query($link, $del);
	} 
	else
	{
    	// echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
	}
	// Close connection
	mysqli_close($link);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Welcome To Othello</title>
	<style type="text/css">
		body {
   		 background-color: #191919;
		}
		h1
		{
    		text-align: center;
    		color: white;
    	}
		#wrapper
		{
			text-align: center;
			padding: 15px 32px;
			font-size: 16px;
		}
		button
		{
			width: 360px;
			height: 120px;
			background-color: #DADAE3; 
			margin: 30px;
			font-size: 18px;
		}
		form
		{
			text-align: center;
			padding: 12px;	
			margin: 18px;
			color: white;
		}
		input[name="token"]
		{
			margin: 15px;
			width: 200px;
			height: 30px;
		}
	</style>
</head>
<body>
	<h1>Welcome To Othello!!! </h1>
	<div id = "container">
		<form action="process.php" method="post">
		<div id = "wrapper">
				<button name="newgame">Create New Game</button>
				<button type = "button" onclick="document.getElementById('Footer').style.display='block'" name="joingame">Join Existing Game</button>
				
		</div>
		<div id = "Footer" style="display:none">
			Enter Token: <input type="text" name="token"  ><br>
			<input type="submit" value="Submit" name="submit1">
			</form>
			
		</div>	
	</div>s
</body>
</html>
