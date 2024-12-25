<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tinder-Style Pause Game</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    .bangers-regular {
      font-family: "Bangers", serif;
      font-weight: 400;
      font-style: normal;
        }

    body {
       font-family: "Bangers", serif;
      margin: 0;
      padding: 0;
      background-color: #f8f9fa;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      overflow: hidden;
    }

    #score-container {
      position: fixed;
      top: 10px;
      left: 10px;
      background-color: #000;
      color: #fff;
      padding: 10px;
      border-radius: 5px;
      font-size: 18px;
      z-index: 1000;
    }

    #counters-container {
      position: fixed;
      top: 10px;
      right: 10px;
      background-color: #000;
      color: #fff;
      padding: 10px;
      border-radius: 5px;
      font-size: 18px;
      z-index: 1000;
    }

    .card-stack {
      position: relative;
      width: 90%;
      max-width: 400px;
      height: 80%;
    }

    .card {
      position: absolute;
      width: 100%;
      height: 100%;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
      transition: transform 0.5s ease, opacity 0.5s ease;
    }

    .card video {
      width: 100%;
      height: 60%;
      border-radius: 10px 10px 0 0;
    }

    .card-content {
      padding: 10px;
      text-align: center;
    }

    .card:not(:last-child) {
      transform: scale(0.9);
      opacity: 0.8;
      z-index: -1;
    }

    /* Win and Missed Classes */
    .card.missed {
      pointer-events: none;
      background-color: rgba(255, 0, 0, 0.9); /* Red overlay */
      transform: scale(0.95);
    }

    .card.win {
      pointer-events: none;
      background-color: rgba(0, 255, 0, 0.9); /* Green overlay */
      transform: scale(0.95);
    }

    /* Swipe Animations */
    .card.swipe-right {
      transform: translateX(100%) rotate(15deg);
      opacity: 0;
    }

    .card.swipe-left {
      transform: translateX(-100%) rotate(-15deg);
      opacity: 0;
    }
    
   .card:nth-last-child(2) {
  transform: rotate(6deg);
}
#gameOver {
    text-align: center;
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #000;
    color: #fff;
    padding: 20px;
    border-radius: 10px;
    font-size: 24px;
    z-index: 1000;
    }
    
        /* Reset Button */
        .reset-button {
        position: fixed;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #000;
        color: #fff;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 18px;
        cursor: pointer;
}
  </style>
</head>
<body>
  <!-- Score and Counters -->
  <div id="score-container">Score: 0</div>
  <div id="counters-container">
    <div><span style="color:green;">Swiped Right:</span> <span id="swipedRightCount">0</span> | <span style="color:red;">Swiped Left:</span> <span id="swipedLeftCount">0</span> | <span><a href="game.html" style="color:white;">Restart</a></span></div>
  </div>

  <!-- Card Container -->
   <div id="gameOver">
    <h1 style="color:red;">Game Over</h1>
    <p>Final Score: <span id="finalScore"></span></p>
    </div>
  <div id="cardContainer" class="card-stack" style="margin-top:100px;"></div>
  <input type="hidden" id="user_id" value="1">
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <script>
    // Simulated video data (replace with your dynamic source if needed)
    const videoData =[
    {
      "id": 1,
      "videoSrc": "https://thinksyntax.com/game/videos/video.mp4",
      "timestamps": "00:04,00:08,0:12"
    },
    {
      "id": 2,
      "videoSrc": "https://thinksyntax.com/game/videos/video1.mp4",
      "timestamps": "0:10"
    }
  ]
  

    // Game State
    let totalScore = 0;
    let swipedRightCount = 0;
    let swipedLeftCount = 0;

    // DOM Elements
    const scoreContainer = document.getElementById("score-container");
    const swipedRightCountEl = document.getElementById("swipedRightCount");
    const swipedLeftCountEl = document.getElementById("swipedLeftCount");
    const cardContainer = document.getElementById("cardContainer");

    // Create stacked video cards
    function createCards(videos) {
      videos.reverse().forEach((video, index) => {
        const card = document.createElement("div");
        card.className = "card";

        card.innerHTML = `
          <video class="gameVideo" data-ts="${video.timestamps}" data-gameid="${video.id}" controls>
            <source src="${video.videoSrc}" type="video/mp4">
            Your browser does not support the video tag.
          </video>
          <div class="card-content">
            <h2 class="attempts bangers-regular">Attempts left: 3</h2>
          </div>
        `;
        cardContainer.appendChild(card);
      });

      initializeCards();
    }

    // Initialize swipe and game logic
    function initializeCards() {
    const cards = Array.from(document.querySelectorAll(".card"));

    cards.forEach((card, index) => {
      const video = card.querySelector(".gameVideo");
      const attemptsDisplay = card.querySelector(".attempts");
      const timestamps = parseTimestamps(video.dataset.ts);
      const gameId = card.getAttribute("data-gameid"); // Get the data-gameid attribute
      let attemptsLeft = 3;

      video.addEventListener("pause", () => {
        const currentTime = Math.floor(video.currentTime);

        if (timestamps.includes(currentTime)) {
          totalScore += 10;
          updateScore();
          swipedRightCount++;
          updateSwipeCounts();

          // Send data to the server
          sendScoreData(swipedRightCount, swipedLeftCount, gameId);

          disableCardTemporarily(card, "win", cards, () => swipeCard(card, "right", cards));
        } else {
          attemptsLeft--;
          if (attemptsLeft > 0) {
            attemptsDisplay.textContent = `Attempts left: ${attemptsLeft}`;
            disableCardTemporarily(card, "missed", cards, () => {});
          } else {
            swipedLeftCount++;
            updateSwipeCounts();

            // Send data to the server
            sendScoreData(swipedRightCount, swipedLeftCount, gameId);

            disableCardTemporarily(card, "missed", cards, () => swipeCard(card, "left", cards));
          }
        }
      });
    });
  }

// Function to send data via AJAX to the /score route
function sendScoreData(swipedRightCount, swipedLeftCount, gameId) {
    // Retrieve the user_id from the hidden input
    const userId = $('#user_id').val();

    // Check if user_id exists before proceeding
    if (!userId) {
        console.error("user_id is not set.");
        return;
    }

    // Prepare the data to send
    const data = {
        user_id: userId,
        swipedRightCount: swipedRightCount,
        swipedLeftCount: swipedLeftCount,
        gameId: gameId,
    };
    //Add headers
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // Send the data via AJAX
    $.ajax({
        url: "{{ route('frontend.scores.store') }}",
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function(updatedData) {
            // Update the swiped counts in the DOM with the response data
            $('#swipedRightCount').text(updatedData.swipedRightCount || swipedRightCount);
            $('#swipedLeftCount').text(updatedData.swipedLeftCount || swipedLeftCount);
            $('#score-container').text(`Score: ${updatedData.totalScore || totalScore}`);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('There was a problem with the AJAX request:', textStatus, errorThrown);
        }
    });
}

// Parse timestamps into seconds
function parseTimestamps(dataTs) {
    return dataTs.split(",").map((ts) => {
        const [minutes, seconds] = ts.split(":").map(Number);
        return minutes * 60 + seconds;
    });
}

// Update score display
function updateScore() {
    $('#score-container').text(`Score: ${totalScore}`);
}

// Update swipe counts
function updateSwipeCounts() {
    $('#swipedRightCount').text(swipedRightCount);
    $('#swipedLeftCount').text(swipedLeftCount);
}

// Disable the card temporarily
function disableCardTemporarily(card, className, cards, callback) {
    $(card).addClass(className);
    const interactiveElements = $(card).find("video, button, input");
    interactiveElements.prop('disabled', true);

    setTimeout(() => {
        $(card).removeClass(className);
        interactiveElements.prop('disabled', false);
        callback();
    }, 3000); // 3 seconds
}

// Swipe card with animation
function swipeCard(card, direction, cards) {
    $(card).addClass(direction === "right" ? "swipe-right" : "swipe-left");
    setTimeout(() => {
        $(card).remove();
        cards.pop();
        checkGameOver(cards);
    }, 500);
}

// Check if all cards are swiped
function checkGameOver(cards) {
    if ($(".card").length === 0) {
        $("#gameOver").show();
        $("#finalScore").text(totalScore);
    }
}

    // Start the game
    createCards(videoData);

  
    
  </script>
  
</body>
</html>
