<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill Check Game</title>
    <style>
        img {
            width: 300px;
            height: 300px;
            margin: 20px;
            object-fit: contain;
        }
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            margin-top: 20px;
        }
        .progress-bar {
            position: relative;
            width: 300px;
            height: 30px;
            margin: 20px auto;
            background-color: #ddd;
            border-radius: 15px;
            overflow: hidden;
        }
        .target-zone {
            position: absolute;
            height: 100%;
            background-color: rgba(0, 255, 0, 0.5);
            border: 2px solid green;
            border-radius: 15px;
            display: none; /* Tidak terlihat sebelum game dimulai */
        }
        .cursor {
            position: absolute;
            width: 10px;
            height: 100%;
            background-color: red;
            left: 0;
            border-radius: 5px;
        }
        .difficulty-buttons, .control-button {
            margin-top: 20px;
        }
        button {
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
            margin: 5px;
        }
        #score-display {
            margin-top: 20px;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Let Him Cook!!!</h1>
        <p>What do you want to cook today?</p>
        <div class="difficulty-buttons">
            <button data-difficulty="1">Experimental Batch</button>
            <button data-difficulty="2">Street Meth</button>
            <button data-difficulty="3">Mexican Cartel Meth</button>
            <button data-difficulty="4">Crystal Meth</button>
            <button data-difficulty="5">Blue Meth</button>
        </div>
        <img id='category-image' src="../assets/image/cookingMeth.jpeg" alt="cooking">
        <div class="progress-bar">
            <div class="target-zone"></div>
            <div class="cursor"></div>
        </div>
        <div class="control-button">
            <button id="control-button">Cook Now!!!</button>
        </div>
        <p id="score-display">Score: 0</p>
    </div>
    <script>
    let cursor = document.querySelector('.cursor');
    let targetZone = document.querySelector('.target-zone');
    let controlButton = document.getElementById('control-button');
    let scoreDisplay = document.getElementById('score-display');
    let difficultyButtons = document.querySelectorAll('[data-difficulty]');

    let cursorPosition = 0;
    let direction = 1;
    let interval = null; 
    let gameRunning = false;

    // Default difficulty settings
    let cursorSpeed = 2;
    let targetWidth = 50;

    let categoryImages = {
    1: '../assets/image/experimentMeth.jpeg',
    2: '../assets/image/streetMeth.jpeg',
    3: '../assets/image/mexicanMeth.jpeg',
    4: '../assets/image/crystalMeth.jpeg',
    5: '../assets/image/blueMeth.jpeg'
    };


    function updateCategoryImage(difficulty) {
        let imageElement = document.getElementById('category-image');
        imageElement.src = categoryImages[difficulty];
    }

    function resetGame() {
        
        if (interval) {
            clearInterval(interval);
            interval = null;
        }

        cursorPosition = 0;
        direction = 1; 
        cursor.style.left = cursorPosition + 'px';
        scoreDisplay.textContent = 'Score: 0';
        targetZone.style.display = 'none';
        gameRunning = false;
        controlButton.textContent = 'Start';
    }

    function setDifficulty(difficulty) {
        // Set difficulty based on selected level
        switch (difficulty) {
            case 1: // Easy
                cursorSpeed = 1;
                targetWidth = 80;
                break;
            case 2: // Normal
                cursorSpeed = 2;
                targetWidth = 50;
                break;
            case 3: // Hard
                cursorSpeed = 4;
                targetWidth = 40;
                break;
            case 4: // Expert
                cursorSpeed = 6;
                targetWidth = 30;
                break;
            case 5: // Impossible
                cursorSpeed = 8;
                targetWidth = 20;
                break;
        }
        // Update target zone width
        targetZone.style.width = targetWidth + 'px';
    }

    function startGame() {
        
        let randomLeft = Math.floor(Math.random() * (300 - targetWidth));
        targetZone.style.left = randomLeft + 'px';
        targetZone.style.display = 'block';

        
        gameRunning = true;
        controlButton.textContent = 'Stop';
        interval = setInterval(moveCursor, 20); 
    }

    function stopGame() {
        clearInterval(interval); 
        interval = null; 
        gameRunning = false;
        controlButton.textContent = 'Start';
        let score = calculateScore();
        scoreDisplay.textContent = `Score: ${score}`;
    }

    function moveCursor() {
        if (gameRunning) {
            cursorPosition += direction * cursorSpeed;
            if (cursorPosition >= 290 || cursorPosition <= 0) {
                direction *= -1;
            }
            cursor.style.left = cursorPosition + 'px';
        }
    }

    function calculateScore() {
        let targetLeft = targetZone.offsetLeft;
        let cursorCenter = cursorPosition + 5;
        let distance = Math.abs(targetLeft + targetWidth / 2 - cursorCenter);
        let maxDistance = targetWidth / 2 + 145;
        return Math.max(1, Math.round(10 - (distance / maxDistance) * 10)); 
    }

    // Event listeners for difficulty buttons
    difficultyButtons.forEach(button => {
        button.addEventListener('click', () => {
            let difficulty = parseInt(button.getAttribute('data-difficulty'));
            setDifficulty(difficulty);
            resetGame(); 
        });
    });
    difficultyButtons.forEach(button => {
    button.addEventListener('click', () => {
        let difficulty = parseInt(button.getAttribute('data-difficulty'));
        setDifficulty(difficulty);
        updateCategoryImage(difficulty);
        resetGame();
    });
});
    // Control button logic
    controlButton.addEventListener('click', () => {
        if (gameRunning) {
            stopGame();
        } else {
            resetGame();
            startGame();
        }
    });

    // Set default difficulty (Normal) on page load
    setDifficulty(2);
</script>

</body>
</html>
