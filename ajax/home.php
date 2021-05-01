<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();

require_once('./config.php');
	
// query for colors
$sqlColor = "SELECT * FROM `Colors`";

// make the query and get the result
$colorResult = mysqli_query($con, $sqlColor);

// query for Charities
$sqlCharity = "SELECT * FROM `Charities`";

// make the query and get the result
$charityResult = mysqli_query($con, $sqlCharity);

// query for pixel colors
$pixelColor = "SELECT * FROM `Pixel_Colors`";
$pixelColorResult = mysqli_query($con, $pixelColor);

// https://stackoverflow.com/questions/383631/json-encode-mysql-results
$jsrows = array();
while($r = mysqli_fetch_assoc($pixelColorResult)){
    $jsrows[] = $r;
}
$jsPixelColors = json_encode($jsrows); //jsPixelColors is now a JSON encoded object, to be set to a js var later 

// close connection
mysqli_close($con);

// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Pixels for Humanity</title>
		<link href="styles/css/login.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <script src="js/jquery-1.6.2.min.js"></script>
	</head>
	<body class="loggedin">
		<?php
        include("base.php")
        ?>
        <div id="grid-container">
		<div class="content" id="main-content">
            <h2>Select a color and a charity of your choice</h2>
            <div class="container">
                <?php
                    echo "<span id='colorsel'><label id='color-label'>Color:</label><select name='color-list' id='color-list'>";
                    while ($row = mysqli_fetch_array($colorResult)) {
                        echo "<option id='color-input' value='" . $row['hexcode'] . "'>" . $row['color_name'] . "</option>";
                    }
                    echo "</select></span>";
                    echo "<span id='charity'><label id='charity-label'>Charity:</label><select name='charity-list' id='charity-list'>";
                    while ($row = mysqli_fetch_array($charityResult)) {
                        echo "<option id='charity-input' value='" . $row['charity_name'] . "'>" . $row['charity_name'] . "</option>";
                    }
                    echo "</select></span>";
                    /*
                    while ($row = mysqli_fetch_array($pixelColorResult)) {
                        echo $row['pixel_id'];
                        echo $row['color'];
                    }*/
                ?>
            </div>

            <canvas id="pixelCanvas" width="1000" height="1000"></canvas>
            <!-- https://stackoverflow.com/questions/2928827/access-php-var-from-external-javascript-file -->
            <script type="text/javascript">
                var pixel_colors = "<?= $jsPixelColors ?>";
                const canvas = document.getElementById("pixelCanvas");
                const ctx = canvas.getContext("2d");
                
                const rowsInput = 20;
                const colsInput = 20;

                let canvasWidth = canvas.width;
                let canvasHeight = canvas.height;

                let gridInfo = [];
                let totalSelected = 0;

                let thisColor = "white";
                var pix_color_result = JSON.parse(pixel_colors)

                function makeGrid(numRows, numCols, color) {
                    ctx.fillStyle = "white";
                    ctx.fillRect(0, 0, canvas.width, canvas.height);

                    ctx.strokeStyle = color || "black";
                    let width = canvasWidth / numCols;
                    let height = canvasHeight / numRows;
                    
                    for(let i = 0; i < numRows; i++){
                        gridInfo[i] = [];
                        for(let j = 0; j < numCols; j++){
                            //thisColor = pix_color_result[i*20+j+1].color;
                            gridInfo[i][j] = {color:thisColor, charity:"None", selected:false};
                        }
                    }

                    for (let i = width; i < canvasWidth; i += width) {
                        drawLine(i, 0, i, canvasHeight);
                    }

                    for (let i = height; i < canvasHeight; i += height) {
                        drawLine(0, i, canvasWidth, i);
                    }

                    function drawLine(x1, y1, x2, y2) {
                        ctx.beginPath();
                        ctx.moveTo(x1, y1);
                        ctx.lineTo(x2, y2);
                        ctx.stroke();
                        ctx.restore();
                    }
                }

                function init(){
                    let numRows = 20;
                    let numCols = 20;

                    makeGrid(numRows, numCols, "black");
                    //drawSquare(0,0,gridInfo[0][0].color);
                    /*
                    for(let i = 0; i < numRows; i++){
                        gridInfo[i] = [];
                        for(let j = 0; j < numCols; j++){
                            drawSquare[i,j,gridInfo[i][j].color]
                            gridInfo[i][j].charity = "None"
                            gridInfo[i][j].selected = false
                        }
                    }*/

                    function findIndex(num, size) {
                        num = num - (num % size);
                        return (num === 0) ? num : num + 1;
                    }

                    function pixelCoords(posX, posY){
                        let x = canvasWidth / colsInput;
                        let y = canvasHeight / rowsInput;
                        return [Math.floor(posX/x), Math.floor(posY/y)]
                    }

                    canvas.onclick = function(event){
                        event.preventDefault();
                        
                        let margin = this.getBoundingClientRect();
                        let x = event.clientX - margin.left;
                        let y = event.clientY - margin.top;
                        
                        let colorInput = document.getElementById("color-list");
                        let pxs = pixelCoords(findIndex(x, canvasWidth / numCols), findIndex(y, canvasHeight / numRows))
                        let pixel = gridInfo[pxs[1]][pxs[0]]
                        if(!pixel.selected){
                            drawSquare(x, y, colorInput.value);
                        }
                    }

                    function drawSquare(x, y, color){
                        ctx.fillStyle = color || "white";       
                    
                        let squareWidth = canvasWidth / numCols;
                        let squareHeight = canvasHeight / numRows;

                        console.log(x, y);

                        x = findIndex(x, squareWidth);
                        y = findIndex(y, squareHeight); 

                        let onVerticalAxis = x === 0 || x === canvasWidth - squareWidth + 1;
                        let onHorizonalAxis = y === 0 || y === canvasHeight - squareHeight + 2;
                        
                        squareWidth -= (onVerticalAxis) ? 1 : 2;
                        squareHeight -= (onHorizonalAxis) ? 1 : 2;
                    
                        ctx.fillRect(x, y, squareWidth, squareHeight);
                        let pxs = pixelCoords(x, y)

                        let chr = document.getElementById("charity-list").value;
                        let pixel = gridInfo[pxs[1]][pxs[0]]

                        pixel.color = color
                        pixel.charity = chr
                        pixel.selected = true
                        totalSelected++;
                        $("#total-num").html(totalSelected)

                        $("#purchase-contents").append("<div class='item'><span class='item-pixel' style='background-color:"+color+"'></span><span class='item-text'>@ ("+pxs[1]+", "+pxs[0]+") supporting "+chr+" </span><span class='cost'>$1</span></div><hr/>")
                    }
                }

                window.onload = init();
            </script>

		</div>
        <div id="purchase-info">
            <div id="purchase-header">Purchase Info</div>
            <hr />
            <div id="purchase-contents">
            </div>
            <hr />
            <div id="purchase-bottom">
                <form action="https://www.sandbox.paypal.com/donate" method="post" target="_top">
                    <input type="hidden" name="hosted_button_id" value="HYSZKYCADBDKW" />
                    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
                    <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
                </form>
                <div id="purchase-total"><strong>Order Total</strong><br/>$<span id="total-num">0</span>.00</div>
            </div>
        </div>
    </div>
	</body>
</html>
