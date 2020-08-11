<?php
	function LookIntoTable($token1)
	{
		$db_host="localhost";
		$db_username="root";
		$db_pass="strong_password";
		$db_name="userToken";
		$link = mysqli_connect($db_host,$db_username,$db_pass,$db_name);
		if($link === false)
		{
    		die("ERROR: Could not connect. " . mysqli_connect_error());
		}
		$sql = "select userCount from tokenTable where token = '{$token1}'";
		$res = mysqli_query($link, $sql);
		if(mysqli_num_rows($res) == 0)
		{
			echo "Invalid Token";
		}
		else
		{
			$t = time();
			$sql1 = "update tokenTable set userCount = userCount + 1,time = '{$t}' where token = '{$token1}'";
			$res1 = mysqli_query($link, $sql1);
			$sql2 = "select userCount from tokenTable where token = '{$token1}'";
			$res1 = mysqli_query($link, $sql2);
			$row = mysqli_fetch_assoc($res1);
			$res1 = $row["userCount"];
			if($res1 > 2)
			{
				$sql1 = "update tokenTable set userCount = userCount-1 where token = '{$token1}'";
				$res1 = mysqli_query($link, $sql1);
				header("Location:return.html");	
			}
		}
	}
	function InsertIntoTable($token)
	{
		$db_host="localhost";
		$db_username="root";
		$db_pass="strong_password";
		$db_name="userToken";
		$array = array(
 				range(1, 8), 
 				range(1, 8),
 				range(1, 8), 
 				range(1, 8),
 				range(1, 8), 
 				range(1, 8),
 				range(1, 8), 
 				range(1, 8)
				);
		$matrix = array();
		foreach (range(1,8) as $row)
		{
 			foreach (range(1,8) as $col)
 			{
  				$matrix[$row][$col] = 0;
 			}
		}
		$matrix[4][4] = 1;
		$matrix[4][5] = 2;
		$matrix[5][4] = 2;
		$matrix[5][5] = 1;
		foreach ($matrix as $sub)
		{
 		 	$tmpArr[] = implode('', $sub);
		}
		$result = implode('', $tmpArr);
		#echo $result;
		$link = mysqli_connect($db_host,$db_username,$db_pass,$db_name);
		
		if($link === false)
		{
    		die("ERROR: Could not connect. " . mysqli_connect_error());
		}
		$sql = "Insert into tokenTable values ('$token','$result',1,1,1,0)";
		if(mysqli_query($link, $sql))
		{
    		
		} 
		else
		{
	    	echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
		}
	}
?>