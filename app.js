const canvas = document.getElementById("pixelCanvas");
const ctx = canvas.getContext("2d");

const rowsInput = 20;
const colsInput = 20;
let numRows = 20;
let numCols = 20;

let canvasWidth = canvas.width;
let canvasHeight = canvas.height;

let gridInfo = [];
let selectedForPurchase = [];
let totalSelected = 0;

let thisColor = "white";
let pixel_colors = [];

function makeGrid(numRows, numCols, color) {
    ctx.fillStyle = "white";
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    ctx.strokeStyle = color || "black";
    let width = canvasWidth / numCols;
    let height = canvasHeight / numRows;

    for (let i = 0; i < numRows; i++) {
        gridInfo[i] = [];
        for (let j = 0; j < numCols; j++) {
            // console.log(i * 20 + j + 1)
            thisColor = pixel_colors[i * 20 + j].color; // + 1 because pixel_id is from 1-400 b/c auto_increment in phpmyadmin starts from 1
            gridInfo[i][j] = { color: thisColor, charity: "None", selected: false, hexcode: pixel_colors[i * 20 + j].hexcode };
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

function findIndex(num, size) {
    num = num - (num % size);
    return (num === 0) ? num : num + 1;
}

function pixelCoords(posX, posY) {
    let x = canvasWidth / colsInput;
    let y = canvasHeight / rowsInput;
    return [Math.floor(posX / x), Math.floor(posY / y)]
}

function clearSquare(x, y) {
    ctx.fillStyle = "white";

    let squareWidth = canvasWidth / numCols;
    let squareHeight = canvasHeight / numRows;

    x = findIndex(x, squareWidth);
    y = findIndex(y, squareHeight);

    let onVerticalAxis = x === 0 || x === canvasWidth - squareWidth + 1;
    let onHorizonalAxis = y === 0 || y === canvasHeight - squareHeight + 2;

    squareWidth -= (onVerticalAxis) ? 1 : 2;
    squareHeight -= (onHorizonalAxis) ? 1 : 2;

    ctx.fillRect(x, y, squareWidth, squareHeight);
    let pxs = pixelCoords(x, y)

    let pixel = gridInfo[pxs[1]][pxs[0]]
    pixel.selected = false
}

function drawSquare(x, y, hexcode, color, init = false) {
    if (!init) console.log(color)
    ctx.fillStyle = hexcode || "white";

    let squareWidth = canvasWidth / numCols;
    let squareHeight = canvasHeight / numRows;

    // console.log(x, y);

    x = findIndex(x, squareWidth);
    y = findIndex(y, squareHeight);

    let onVerticalAxis = x === 0 || x === canvasWidth - squareWidth + 1;
    let onHorizonalAxis = y === 0 || y === canvasHeight - squareHeight + 2;

    squareWidth -= (onVerticalAxis) ? 1 : 2;
    squareHeight -= (onHorizonalAxis) ? 1 : 2;

    ctx.fillRect(x, y, squareWidth, squareHeight);
    let pxs = pixelCoords(x, y)

    let pixel = gridInfo[pxs[1]][pxs[0]]
    if (color != "white") pixel.selected = true
    else pixel.selected = false

    if (!init) {
        let chr = document.getElementById("charity-list").value;
        let chrname = $("#charity-list option:selected").text()

        pixel.hexcode = hexcode
        pixel.color = color
        pixel.charity = chr

        totalSelected++;
        selectedForPurchase.push({ id: (pxs[1] * numRows + pxs[0] + 1), color: color, charity: chr })
        $("#total-num").html(totalSelected)

        $("#purchase-contents").append("<div class='item'><span class='item-pixel' id='pixel-" + (pxs[1] * numRows + pxs[0]) + "' style='background-color:" + hexcode + "'></span><span class='item-text' id='item-" + (pxs[1] * numRows + pxs[0]) + "'>@ (" + pxs[1] + ", " + pxs[0] + ") supporting " + chrname +
            " </span><span class='cost'>$1</span></div><hr/>")
    }
}

function init() {

    $("#color-list").css("outline", "5px solid " + $("#color-list").val());

    $("#color-list").change(function () {
        $("#color-list").css("outline", "5px solid " + $("#color-list").val());
    });

    $.get("retrieve_pixels.php", function (data) {
        pixel_colors = JSON.parse(data);
        makeGrid(numRows, numCols, "black");

        for (let i = 0; i < numRows; i++) { //draw in the squares based on the DB query. Since drawSquare function also sets              
            // charity and selected, reset those fields
            for (let j = 0; j < numCols; j++) {
                drawSquare(j * (canvasHeight / numRows), i * (canvasWidth / numCols), gridInfo[i][j].hexcode, gridInfo[i][j].color, true);
                gridInfo[i][j].selected = gridInfo[i][j].color !== "white"
            }
        }
    });

    canvas.onclick = function (event) {
        event.preventDefault();

        let margin = this.getBoundingClientRect();
        let x = event.clientX - margin.left;
        let y = event.clientY - margin.top;

        let colorInput = document.getElementById("color-list");
        let pxs = pixelCoords(findIndex(x, canvasWidth / numCols), findIndex(y, canvasHeight / numRows))
        let pixel = gridInfo[pxs[1]][pxs[0]]
        if (!pixel.selected) {
            drawSquare(x, y, colorInput.value, $("#color-list option:selected").text());
        }
    }

    canvas.onmousemove = function (event) {
        event.preventDefault();

        let margin = this.getBoundingClientRect();
        let x = event.clientX - margin.left;
        let y = event.clientY - margin.top;

        let pxs = pixelCoords(findIndex(x, canvasWidth / numCols), findIndex(y, canvasHeight / numRows));
        let pixel = pixel_colors[(pxs[1] * numRows + pxs[0])];

        if (pixel.color != "white") {

            let date = new Date(pixel.purchase_date);

            // adapted from https://stackoverflow.com/questions/12409299/
            const monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"];
            const month = monthNames[date.getMonth()];
            const day = String(date.getDate()).padStart(2, '0');
            const year = date.getFullYear();
            const output = month + '\n' + day + ', ' + year;

            $("#pixel-info").html("Pixel " + (pxs[1] * numRows + pxs[0]) + " donated by <strong>" + pixel.username + "</strong> on " + output + ", supporting <strong>" + pixel.charity_name + "</strong>");
        }
        else {
            $("#pixel-info").html("This pixel has not yet been purchased! Select a color and a charity and click this pixel to add it to your cart!");
        }


    }

    canvas.onmouseleave = function (event) {
        $("#pixel-info").html("Hover over a pixel to view its info!");
    }
}

function collectAndPurchase() {
    console.log(selectedForPurchase)
    $.post("purchase.php", {
        ptp: JSON.stringify(selectedForPurchase),
        amt: totalSelected
    }, function (data) {
        console.log(data);
        if (confirm("Your purchase was successful! The page will now refresh.")) {
            location.reload()
        } else {
            location.reload()
        }
    })
}

document.onload = init();