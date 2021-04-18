var bw = 1000;
var bh = 1000;
var p = 10;

var canvas = document.getElementById("pixelCanvas");
var context = canvas.getContext("2d");

function drawBoard(){
    for (var x = 0; x <= bw; x += 50) {
        context.moveTo(0.5 + x + p, p);
        context.lineTo(0.5 + x + p, bh + p);
    }

    for (var x = 0; x <= bh; x += 50) {
        context.moveTo(p, 0.5 + x + p);
        context.lineTo(bw + p, 0.5 + x + p);
    }
    context.strokeStyle = "black";
    context.stroke();
}
drawBoard();