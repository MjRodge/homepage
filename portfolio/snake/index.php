<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Michael Rodgers: HTML5 Snake Game</title>
		<link rel="stylesheet" type="text/css" href="style.css" />
		<script type="text/javascript" src="jquery-1.7.2.min.js"></script>

		<script>
			//Variable to hold the entire game context
			var context;
			//Specifying the width and height of our game area
			var width = 480;
			var height = 360;

			//Specify the initial game configuration
		   	var snakeLength = 3;
			var level = 1;
			var squareSize = 10;

			//Specify the initial snake alignment and direction of movement
			//Snake is starts horizontal moving towards its right

		  	//Array to manage X coordinates for snake body
			var bodyX = new Array(150, 150-squareSize, 150-2*squareSize);

			//Array to manage Y coordinates for snake body
			var bodyY = new Array(200, 200, 200);

			//Array to manage horizontal velocity for snake body
			var vX = new Array(1, 1, 1);

			//Array to manage vertical velocity for snake body
			var vY = new Array(0, 0, 0);

			//Variables used to place strawberries on the canvas
			var strawberryX;
			var strawberryY;

			//Variable holding the score, will be used to update document each time strawberry is eaten, also added to high score table when game is over
			var score = 0;

			//Variables holding the sounds for the game
			var chomp = new Audio("sounds/chomp.wav");
			var pop = new Audio("sounds/pop.wav");
			var gameOverSound = new Audio("sounds/gameover.wav");

			//To hold the context of div used to display score and level
			var scoreDiv;
			var restartDiv;

			//To check if new Strawberry needs to be placed
			var eaten = true;

			//To check if the game is over and enable control to restart the game
			var gameOver = false;


			function drawCanvasBoundary()
			{
				context.fillStyle="#ffffff";
				context.fillRect(0,0,width,height);
				context.fill();

				context.strokeStyle="#000000";

				context.strokeRect(0,0,width,height)
			}


			function drawPoint(x,y)
			{
				context.fillStyle = "#df752d";
				context.fillRect(x,y,squareSize, squareSize);
				context.fill();
				context.strokeStyle="#000000";
				context.strokeRect(x,y,squareSize, squareSize);
			}

			function drawSnake()
			{
				for(var i=0; i < snakeLength; i++)
					drawPoint(bodyX[i],bodyY[i]);
			}

			function moveSnake()
			{
				for(var i=0; i < snakeLength; i++)
				{
					bodyX[i] += (vX[i]*squareSize);
					bodyY[i] += (vY[i]*squareSize);

					//Pop sounds horrible as a move noise, will try find something more suitable, else leave it out completely
					//pop.play();
				}
				for(var i=snakeLength-1; i>0; i--)
				{
					vX[i] = vX[i-1];
					vY[i] = vY[i-1];
				}
				eatStrawberry();
			}

			function placeStrawberry()
			{
				if(eaten)
				{
			   		strawberryX = Math.floor(width*Math.random()/squareSize)*squareSize;
			   		strawberryY = Math.floor(height*Math.random()/squareSize)*squareSize;
			  		if(checkFoodCollision(strawberryX,strawberryY))
			  		{
			     		placeStrawberry();
			     	}
			     	else
			     	{
			      		eaten = false;
			      	}
			 	}
				var strawberryImg = new Image();
				strawberryImg.src="images/game/strawberry.png";
				context.drawImage(strawberryImg, strawberryX, strawberryY);
			}

			function checkCollision()
			{
				if(bodyX[0] >= width || bodyX[0] < 0 || bodyY[0] < 0 || bodyY[0] >= height)
				{
			  		scoreDiv.innerHTML = "Score: " + score + "     Level: " + level + "    <br/><b class='popup'>Game Over</b>";
					restartDiv.innerHTML = "<p class='popup'>Press \"Enter\" to try again</p>";
					gameOverSound.play();
					//submitScore();
					gameOver = true;
					clearTimeout(intervalId);
				}
				else if(snakeLength > 4)
				{
					if(checkSelfCollision(bodyX[0],bodyY[0]))
			   		{
			     		scoreDiv.innerHTML = "Score: " + score + "     Level: " + level + "     <br/><b class='popup'>Game Over</b>";
			   			restartDiv.innerHTML = "Press \"Enter\" to restart";
			   			gameOverSound.play();
			   			//submitScore();
			   			gameOver = true;
			    		clearTimeout(intervalId);
			   		}
				}
			}


			function checkSelfCollision(x, y)
			{
				for (var i = 4; i < snakeLength; i++)
				{
			  		if(x == bodyX[i] && y == bodyY[i])
			  		{
			   			return true;
			   		}
					return false;
			  	}
			}

			function checkFoodCollision(x, y)
			{
				for (var i = 0; i < snakeLength; i++)
				{
			  		if(x == bodyX[i] && y == bodyY[i])
			  		{
			     		return true;
			  		}
					return false;
				}
			}

			function eatStrawberry()
			{
				if(bodyX[0] == strawberryX && bodyY[0] == strawberryY)
				{
			 		eaten = true;

			 		var newX = bodyX[snakeLength-1] - vX[snakeLength-1]*squareSize;
					var newY = bodyY[snakeLength-1] -vY[snakeLength-1]*squareSize;

					bodyX.push(newX);
					bodyY.push(newY);

					vX.push(vX[snakeLength-1]);
					vY.push(vY[snakeLength-1]);
					snakeLength++;

					score += 10;

					chomp.play();

					if((score%100) == 0)
					{
						level++;
					}

			     		scoreDiv.innerHTML = "Score: " + score + "     Level: " + level;
				}
			}


			function keydown(e)
			{
			  	if(e.keyCode == 65 && vX[0] != 1)
			  	{
			 		vX[0] = -1;
					vY[0] = 0;
			 	}
			 	else if (e.keyCode == 87 && vY[0] != 1)
			 	{
			  		vY[0] = -1;
					vX[0] = 0;
			  	}
				else if (e.keyCode == 68 && vX[0] != -1)
				{
					vX[0] = 1;
					vY[0] = 0;
				}
				else if (e.keyCode == 83 && vY[0] != -1)
				{
					vY[0] = 1;
					vX[0] = 0;
				}
				else if (e.keyCode == 13 && gameOver == true)
				{
					gameOver = false;
					restart();
				}
			}

			/*
			function submitScore()
			{
				var name = prompt("Enter your name and click ok to submit your score to the leader board","");
				if (name != null && name != "")
				{
					$.post("save.php", { name: name, score: score, level: level});
				}
			}
			*/

			function restart()
			{
				bodyX = new Array(150, 150 - squareSize, 150 - 2 * squareSize);
				bodyY = new Array(200, 200, 200);

				vX = new Array(1, 1, 1);
				vY = new Array(0, 0, 0);

				snakeLength = 3;

			 	score = 0;
				level  = 1;

				eaten = true;

				scoreDiv.innerHTML = "Score: " + score + "    Level: " + level;
				restartDiv.innerHTML = "";

				intervalId = setTimeout(gameProcess, 1000/6);
			}


			function gameProcess()
			{
				intervalId = setTimeout(gameProcess, 1000/(6*level));
			   	clear();
			 	drawCanvasBoundary();
			  	placeStrawberry();
			  	moveSnake();
			   	checkCollision();
			   	drawSnake();
			}

			function clear()
			{
				context.clearRect(0,0,width,height);
			}

			//Initialise the game variables and the game context and then sends the game to the main game loop
			function init()
			{
				context = document.getElementById("canvas1").getContext("2d");
				drawCanvasBoundary();
				drawSnake();
				intervalId = setTimeout(gameProcess, 1000/6);
				scoreDiv = document.getElementById("score");
				restartDiv = document.getElementById("restart");
				window.onkeydown = keydown;
			}
			//Insert the listener for onload event to call our init function
			window.addEventListener("load", init, true);

		</script>
	</head>

	<body>
		<div id="container">
			<header>
				<h1><a href="index.php">Snake HTML5 Game</a></h1>

				<nav>
					<ul>
						<li class="selected"><a href="index.php">Home</a></li>
						<li><a href="highscores.html">High Scores</a></li>
						<li><a href="about.html">About</a></li>
					</ul>
				</nav>
			</header>

			<section>
				<br />
				<br />
				<br />
				<div id="snake-score">
					Score: 0    Level: 1
				</div>
				<div id="snake-restart"></div>
				<canvas id="canvas1" width="480" height="360">
				Unfortunately your browser does not support the Canvas element</canvas>
				<p>Controls:</p>
				<p>Snake Movement: W = Up | S = Down | A = Left | D = Right</p>
				<p>Click on randomly appearing Stopwatch bonuses with the left mouse button to add 10 seconds to the time!</p>
			</section>

			<footer>

			</footer>
		</div>
	</body>
</html>
