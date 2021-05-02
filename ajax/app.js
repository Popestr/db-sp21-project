const canvas = document.getElementById("pixelCanvas");
const ctx = canvas.getContext("2d");

const rowsInput = 20;
const colsInput = 20;

let canvasWidth = canvas.width;
let canvasHeight = canvas.height;

let gridInfo = [];
let totalSelected = 0;

let thisColor = "white";


function makeGrid(numRows, numCols, color) {
    ctx.fillStyle = "white";
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    ctx.strokeStyle = color || "black";
    let width = canvasWidth / numCols;
    let height = canvasHeight / numRows;
    
    for(let i = 0; i < numRows; i++){
        gridInfo[i] = [];
        for(let j = 0; j < numCols; j++){
            console.log(i*20+j+1)
            thisColor = pixel_colors[i*20+j].color; // + 1 because pixel_id is from 1-400 b/c auto_increment in phpmyadmin starts from 1
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

    
    for(let i = 0; i < numRows; i++){ //draw in the squares based on the DB query. Since drawSquare function also sets
        gridInfo[i] = [];               // charity and selected, reset those fields
        for(let j = 0; j < numCols; j++){
            drawSquare[i,j,gridInfo[i][j].color] 
            gridInfo[i][j].charity = "None"
            gridInfo[i][j].selected = false
        }
    }

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