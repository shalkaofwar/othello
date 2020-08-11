<?php

	if(isset($_GET['time']))
	{
		$token = $_GET['token'];
		$db_host="localhost";
		$db_username="root";
		$db_pass="strong_password";
		$db_name="userToken";
		$link = mysqli_connect($db_host,$db_username,$db_pass,$db_name);
		if($link === false)
		{
    		die("ERROR: Could not connect. " . mysqli_connect_error());
		}
		$sql = "select time from tokenTable where token = '{$token}'";
		$res = mysqli_query($link, $sql);
		$row = mysqli_fetch_assoc($res);
		$res1 = $row["time"];
		$t = time() - $res1;
		$row["time"] = $t;
		echo json_encode($row);
	}
	elseif(isset($_GET['grid']) && isset($_GET['turn'])&&isset($_GET['token']) )
	{
		$updTurn = $_GET['turn'];
		$token = $_GET['token'];
		$grid = $_GET['grid'];
		$db_host="localhost";
		$db_username="root";
		$db_pass="strong_password";
		$db_name="userToken";
		$link = mysqli_connect($db_host,$db_username,$db_pass,$db_name);
		if($link === false)
		{
    		die("ERROR: Could not connect. " . mysqli_connect_error());
		}
		$t = time();
		$sql = "update tokenTable set gridInfo = '{$grid}',turn = '{$updTurn}',time = '{$t}' where token = '{$token}'";
		mysqli_query($link, $sql);

	}
	else
	{
		if(isset($_GET['token']))
		{
			$token = $_GET['token'];
			$db_host="localhost";
			$db_username="root";
			$db_pass="strong_password";
			$db_name="userToken";
			$link = mysqli_connect($db_host,$db_username,$db_pass,$db_name);
			if($link === false)
			{
				die("ERROR: Could not connect. " . mysqli_connect_error());
			}
			$sql = "select gridInfo,turn,gameAlive,userCount from tokenTable where token = '{$token}'";
			$res = mysqli_query($link, $sql);
			$row = mysqli_fetch_assoc($res);
			echo json_encode($row);
		}
	}
	if(isset($_GET['gameAlive']))
	{
		$game = $_GET['gameAlive'];
		$token = $_GET['token'];
		$db_host="localhost";
		$db_username="root";
		$db_pass="strong_password";
		$db_name="userToken";
		$link = mysqli_connect($db_host,$db_username,$db_pass,$db_name);
		if($link === false)
		{
    		die("ERROR: Could not connect. " . mysqli_connect_error());
		}
		$sql = "update tokenTable set gameAlive = '{$game}' where token = '{$token}'";
		mysqli_query($link, $sql);
	}
	
?>