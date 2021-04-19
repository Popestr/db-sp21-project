const canvas = document.getElementById("pixelCanvas");
const ctx = canvas.getContext("2d");

const rowsInput = 20;
const colsInput = 20;

let canvasWidth = canvas.width;
let canvasHeight = canvas.height;

function makeGrid(numRows, numCols, color) {
    ctx.fillStyle = "white";
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    ctx.strokeStyle = color || "black";
    let width = canvasWidth / numCols;
    let height = canvasHeight / numRows;

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

    canvas.onclick = function(event){
        event.preventDefault();
        
        let margin = this.getBoundingClientRect();
        let x = event.clientX - margin.left;
        let y = event.clientY - margin.top;
        
        let colorInput = document.getElementById("color-list");
        drawSquare(x, y, colorInput.value);
    }

    function drawSquare(x, y, color){
        ctx.fillStyle = color || "white";       
    
        let squareWidth = canvasWidth / numCols;
        let squareHeight = canvasHeight / numRows; 

        x = findIndex(x, squareWidth);
        y = findIndex(y, squareHeight); 

        let onVerticalAxis = x === 0 || x === canvasWidth - squareWidth + 1;
        let onHorizonalAxis = y === 0 || y === canvasHeight - squareHeight + 2;
        
        squareWidth -= (onVerticalAxis) ? 1 : 2;
        squareHeight -= (onHorizonalAxis) ? 1 : 2;
    
        ctx.fillRect(x, y, squareWidth, squareHeight);

        function findIndex(num, size) {
            num = num - (num % size);
            return (num === 0) ? num : num + 1;
        }
    }
}

window.onload = init();