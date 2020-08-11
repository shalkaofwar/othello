<?php
	include($_SERVER['DOCUMENT_ROOT']."/mysql_connection.php");
	session_start();
	if(isset($_POST["newgame"]))
	{
		$token = openssl_random_pseudo_bytes(4);
 
		//Convert the binary data into hexadecimal representation.
		$token = bin2hex($token);
		$userId = 1;
		InsertIntoTable($token);
		$_SESSION["tokenNumber"]=$token;
		$_SESSION["userId"]=$userId;

	}
	elseif (isset($_POST["submit1"]))
	{
		# code...
		$userId = 2;
		$enteredToken = $_POST["token"];
		LookIntoTable($enteredToken);
		$_SESSION["tokenNumber"]=$enteredToken;
		$_SESSION["userId"]=$userId;
    $token = $enteredToken;
	}
?>
<!DOCTYPE html>
<html>

	
<head>

    <style type="text/css"> 	
     body
     {
       background-color: #A02A11;
     } 
    .lightgreen
    {
        background-color: #228B22;
        width: 50px;
        height:50px;
    }
    .green
    {
        background-color :#006400;
        width: 50px;
        height:50px;

    }
    .green:hover{
        cursor: pointer;
    }
    .lightgreen:hover{
        cursor: pointer;
    }
    #container
    {
        padding: 10px;
        background-color: #A02A11;
    }
    #othellobox
    {
        width: 800px;
        vertical-align: top;
        display: inline-block;
        float: right;
        background-color: #A02A11;
    }
    #scoreboard
    {
        width : 500px;
        height: 1000px;
        float: left;
        background-color: #A02A11;
    }
   
</style>
</head>
<body>
    <h1 style="padding:5px;" align="center">Othello Game</h1>
    <div id = "container" >
    <div id="scoreboard" >
        <h2>Your Score : <span id="yourScore"></span></h2>
        <h2>Opponent's Score : <span id="oppScore"></span></h2>
        <h2>Timer : <span id="timer"></span></h2>
        <h2>Token Number : <span id="token"><?php echo $token ?></span> </h2>
        <h2>UserId : <span id="userId"><?php echo $userId ?></span>
        <h2>Game Status : <span id ="status">Ongoing...</span></h2> 
        <h2>turn : <span id ="turn">1</span></h2> 
    </div>
    <div id="othellobox"> </div>
    </div>
    <script type="text/javascript">

    var data1,turn;
    var table = "<table>";
    for (var i = 1; i < 9; i++)
    {
        var tr = "<tr>";
        for (var j = 1; j < 9; j++)
        {
            var td;
            if((i+j)%2==0)
               td = "<td class='green' id ='block" + ((i-1)* 8 + j) +"'  onclick='Fun(" + ((i-1)* 8 + j) + ")'>";
            else
               td = "<td class='lightgreen' id ='block" + ((i-1)* 8 + j) +"'  onclick='Fun(" + ((i-1)* 8 + j) + ")'>";
            tr += td;
            tr += "</td>"
        }
        table += tr + "</tr>";
    }
    document.getElementById('othellobox').innerHTML = table;
	  var token = document.getElementById("token").innerHTML;
    table += "</table>"
    document.getElementById("block28").style.backgroundColor = "black";
    document.getElementById("block37").style.backgroundColor = "black";
    document.getElementById("block29").style.backgroundColor = "white";
    document.getElementById("block36").style.backgroundColor = "white";
    document.getElementById("oppScore").innerHTML = 2;
    document.getElementById("yourScore").innerHTML = 2;
    var id = document.getElementById("userId").innerHTML;
    var gameAlive = 1;
    var userCount = 1;
    var flag = false;
    var mv = 0;
    var flag1 = 0; 
    var myvar;
    var xx;
    var data;
    var other;
    var cnt = 0;
    var won = 0;
    if(id == 1)
      other = 2;
    else
      other = 1;

    function check(gridval)
    {
      var a=0,b=0,c=0;
      for(var i=0;i<gridval.length;i++)
      {
        if(gridval.charAt(i)==0)
          a = 1;
        else if(gridval.charAt(i)==1)
          b = 1;
        else
          c = 1;  
      }
      if(a+b+c!=3)
      {
        var urScore = document.getElementById("yourScore").innerHTML;
        var oppScore = document.getElementById("oppScore").innerHTML;
        if(urScore>oppScore)
        {
          document.getElementById("status").innerHTML = "You Win....";
          won = 1;
        }
        else if (oppScore>urScore)
        {
          document.getElementById("status").innerHTML = "You Lose....";
        }
        else
        {
          document.getElementById("status").innerHTML = "Draw....";
        }
        sendGameOverSignal(2);
        gameAlive = 2;
        clearInterval(myvar);
      }
      
    }  

    function changeTime() {
      var init = 60;
      
      myvar = setInterval(function()
      {
        var xhttp = new XMLHttpRequest();
        var obj;
        xhttp.open("GET","queryDatabase.php?time=0&token="+token,true);
        xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            obj = JSON.parse(this.responseText);
            obj = obj.time;
            document.getElementById("timer").innerHTML = (init-obj)+"s";
            if(init-obj<0)
            {
                document.getElementById("timer").innerHTML = 0+"s";
                sendGameOverSignal(0);
                clearInterval(xx);
                gameAlive = 0;
                clearInterval(myvar);
            }
        }
      };
        xhttp.send();
    }, 1000);
    }  
    function requestEverySecond() {
    	// body...	
    	xx = setInterval(function()
    	{
    		var xhttp = new XMLHttpRequest();
    		var obj;
    		xhttp.open("GET","queryDatabase.php?token="+token,true);
    		xhttp.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
      			obj = JSON.parse(this.responseText);
      			data1  = obj.gridInfo;
      			turn = obj.turn;
            gameAlive = obj.gameAlive;
            userCount = obj.userCount;
            if(turn == id && flag1 == 0 && userCount == 2)
            {
              changeTime();
              document.getElementById("turn").innerHTML = turn;
              flag1 = 1;
            }
            if(gameAlive == 0)
            {
              document.getElementById("status").innerHTML = "You Win....";
              won = 1;
              clearInterval(xx);
            }
            if(gameAlive == 2)
            {
              var urScore = document.getElementById("yourScore").innerHTML;
              var oppScore = document.getElementById("oppScore").innerHTML;
              if(urScore>oppScore)
              {
                document.getElementById("status").innerHTML = "You Win....";
                won = 1;
              }
              else if (oppScore>urScore)
              {
                document.getElementById("status").innerHTML = "You Lose....";
              }
              else
              {
                document.getElementById("status").innerHTML = "Draw....";
              }
              clearInterval(xx);
            }
            updateGrid(data1);
            updScore(data1);
            check(data1);
    		}
  		};
  		xhttp.send();
    	},1000);
    }
    String.prototype.replaceAt=function(index, replacement) {
    return this.substr(0, index) + replacement+ this.substr(index + replacement.length);
	}

	requestEverySecond();

  function updateGrid(data) {
     // body...
     for(var i=0;i<data.length;i++)
        {
          if(data.charAt(i)=='1')
          {
            //alert(i+1);
            document.getElementById("block"+(i+1)).style.backgroundColor = "black";
          }
          else if(data.charAt(i)=='2')
          {
            document.getElementById("block"+(i+1)).style.backgroundColor = "white";
          }
        }
   } 
   function sendGameOverSignal(signal)
   {
          var xhttp = new XMLHttpRequest();
          if(signal == 0)
              xhttp.open("GET","queryDatabase.php?gameAlive=0&token="+token,true);
          else
              xhttp.open("GET","queryDatabase.php?gameAlive=2&token="+token,true);
          xhttp.send();
          if(signal == 0)
              document.getElementById("status").innerHTML = "You Lose.....";
          else
          {
              var urScore = document.getElementById("yourScore").innerHTML;
              var oppScore = document.getElementById("oppScore").innerHTML;
              if(urScore>oppScore)
              {
                document.getElementById("status").innerHTML = "You Win....";
              }
              else if (oppScore>urScore)
              {
                document.getElementById("status").innerHTML = "You Lose....";
              }
              else
              {
                document.getElementById("status").innerHTML = "Draw....";
              }
          }
   }
   function updScore(data)
   {
        var own=0,sec=0;
        for(var i =0;i<data.length;i++)
        {
          if(data.charAt(i)==id)
             own = own+1;
          else if(data.charAt(i)==other)
             sec = sec+1;  
        }
        document.getElementById("oppScore").innerHTML = sec;
        document.getElementById("yourScore").innerHTML = own;
    }

    var data;
    function checkAbove(row,col)
    {
        var i,j;
        i = row-1,j=col;
        while(i>=0 && data.charAt(i*8+j)==other)
        {
          flag = true;
          i--;
        }
        if( flag == true && i>=0 && data.charAt(i*8+j) == id)
        {
          i = row-1,j=col;
          while(i>=0 && data.charAt(i*8+j)==other)
          {
            mv = 1;
            data = data.replaceAt(i*8+j,id);
            i--;
          }
        }
    }
    function checkBelow(row,col)
    {
        var i,j=col;
        i=row+1;
        while(i<8 && data.charAt(i*8+j)==other)
        {
          flag = true;
          i++;
        }
        if( flag == true && i<8 && data.charAt(i*8+j) == id)
        {
          i=row+1;
          while(i<8 && data.charAt(i*8+j)==other)
          {mv = 1;
            data = data.replaceAt(i*8+j,id);
            i++;
          }
        }
    }

    function checkLeft(row,col)
    {
        var i,j;
        i=row,j=col-1;
        while(j>=0 && data.charAt(i*8+j)==other)
        {
          flag = true;
          j--;
        }
        if(flag == true && j>=0 && data.charAt(i*8+j) == id)
        {
          i=row,j=col-1;
          while(j>=0 && data.charAt(i*8+j)==other)
          {mv = 1;
            data = data.replaceAt(i*8+j,id);
            j--;
          }
        }
    }

    function checkRight(row,col)
    {
        // body...
        var i,j;
        i=row,j=col+1;
        while(j<8 && data.charAt(i*8+j)==other)
        {
          flag = true;
          j++;
        }
        if(flag == true && j<8 && data.charAt(i*8+j) == id)
        {
          i=row,j=col+1;
          while(j<8 && data.charAt(i*8+j)==other)
          {mv = 1;
            data = data.replaceAt(i*8+j,id);
            j++;
          }
        }
    }
    function checkTopLeft(row,col)
    {
        var i,j;
        i=row-1,j=col-1;
        while(i>=0&&j>=0&&data.charAt(i*8+j)==other)
        {
          flag = true;
          i--;
          j--;
        }
        if(flag == true && i>=0&&j>=0 && data.charAt(i*8+j) == id)
        {
          i=row-1,j=col-1;
          while(i>=0&&j>=0 && data.charAt(i*8+j)==other)
          {mv = 1;
            data = data.replaceAt(i*8+j,id);
            j--;
            i--;
          }
        }
    }
    function checkTopRight(row,col)
    {
        var i,j;
        i=row-1,j=col+1;
        while(i>=0&&j<8&&data.charAt(i*8+j)==other)
        {
          flag = true;
          i--;
          j++;
        }
        if(flag == true && i>=0&&j<8 && data.charAt(i*8+j) == id)
        {
          i=row-1,j=col+1;
          while(i>=0&&j<8&& data.charAt(i*8+j)==other)
          {mv = 1;
            data = data.replaceAt(i*8+j,id);
            j++;
            i--;
          }
        }
    }

    function checkBottomLeft(row,col)
    {
        var i,j;
        i=row+1,j=col-1;
        while(i<8&&j>=0&&data.charAt(i*8+j)==other)
        {
          flag = true;
          i++;
          j--;
        }
        if(flag == true && i<8&&j>=0 && data.charAt(i*8+j) == id)
        {
          i=row+1,j=col-1;
          while(i<8&&j>=0 && data.charAt(i*8+j)==other)
          {mv = 1;
            data = data.replaceAt(i*8+j,id);
            j--;
            i++;
          }
        }
    }
    function checkBottomRight(row,col)
    {
        var i,j;
        i=row+1;
        j=col+1;
        while(i<8&&j<8&&data.charAt(i*8+j)==other)
        {
          flag = true;
          i++;
          j++;
        }
        if(flag == true && i<8&&j<8 && data.charAt(i*8+j) == id)
        {
          i=row+1,j=col+1;
          while(i<8&&j<8 && data.charAt(i*8+j)==other)
          { mv = 1;
            data = data.replaceAt(i*8+j,id);
            j++;
            i++;
          }
        }
    }
    function Fun(num)
    {
    	//turn = 1;
      //requestEverySecond();
    	var row = Math.floor((num-1)/8);
    	var col = (num-1)%8;
    	//check valid move;
    	data = data1;
    	if(document.getElementById("status").innerHTML == "You Lose.....")
        gameAlive = 0;
    	if(userCount==2 && turn == id&& data.charAt(row*8+col)==0 && gameAlive==1)
    	{
    		//check above
    		flag = false;
        mv = 0;
    		checkAbove(row,col);
   			//check below
   			flag = false;
   			checkBelow(row,col);

   			// //check left
   			flag = false;
   			checkLeft(row,col);

   			// //check right
   			flag = false;
   			checkRight(row,col);

   			// //check topLeft
   			flag = false;
   			checkTopLeft(row,col);

   			// //check topRight
   			flag = false;
   		  checkTopRight(row,col);

   			// //check bottom left
   			flag = false;
   			checkBottomLeft(row,col);

   			// //check bottom right
   			flag = false;
   			checkBottomRight(row,col);

        if(mv == 1)
   			{
          clearInterval(myvar);
          cnt = 0;
          data = data.replaceAt(num-1,id);	
     			turn = other;
          document.getElementById("turn").innerHTML = turn;
     			var xhttp = new XMLHttpRequest();
     			xhttp.open("GET","queryDatabase.php?grid="+data+"&turn="+turn+"&token="+token,true);
     			xhttp.send();
          updateGrid(data);
          flag1 = 0;
        }
        else
        {
          var valid=false;
          var pre = data;
          for(var i=0;i<data.length;i++)
          {
            if(data.charAt(i)=='0')
            {
              var row1 = Math.floor((i)/8);
              var col1 = (i)%8;
              flag = false;
              mv = 0;
              checkAbove(row1,col1);
              //check below
              flag = false;
              checkBelow(row1,col1);
              // //check left
              flag = false;
              checkLeft(row1,col1);
              // //check right
              flag = false;
              checkRight(row1,col1);

              // //check topLeft
              flag = false;
              checkTopLeft(row1,col1);

              // //check topRight
              flag = false;
              checkTopRight(row1,col1);

              // //check bottom left
              flag = false;
              checkBottomLeft(row1,col1);

              // //check bottom right
              flag = false;
              checkBottomRight(row1,col1);
              if(mv == 1)
              {
                valid = true;
                break;
              }
            }
          }
          data = pre;
          if(valid == false)
          {
            clearInterval(myvar);
            alert("No valid move exists !! Passing to Other player");
            turn = other;
            cnt = cnt+1;
            if(cnt == 2)
            {
              var urScore = document.getElementById("yourScore").innerHTML;
              var oppScore = document.getElementById("oppScore").innerHTML;
              if(urScore>oppScore)
              {
                document.getElementById("status").innerHTML = "You Win....";
              }
              else if (oppScore>urScore)
              {
                document.getElementById("status").innerHTML = "You Lose....";
              }
              else
              {
                document.getElementById("status").innerHTML = "Draw....";
              }
              sendGameOverSignal(2);
              gameAlive = 2;
              clearInterval(myvar);
            }
            else
            {
              document.getElementById("turn").innerHTML = turn;
              var xhttp = new XMLHttpRequest();
              xhttp.open("GET","queryDatabase.php?grid="+data+"&turn="+turn+"&token="+token,true);
              xhttp.send();
              flag = false;
            }
          }
          else
          {
            alert("Valid Position exist!! Choose wisely ...");
          }
        }

    	}
    	else
    	{
        if(won)
          alert("you have won the game..Congrats..");
        else if(!gameAlive)
          alert("You have Lost the game....");
        else if(userCount==1)
          alert("Wait for Second Player...");
        else
    		  alert("Invalid moves");
    	}
    }
    </script>
     
   

</body>
</html>
