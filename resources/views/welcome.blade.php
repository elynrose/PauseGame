@extends('layouts.welcome')
@section('content')
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
  
  @endsection
  
  @section('scripts')
    @parent
    <script>
      // Simulated video data (replace with your dynamic source if needed)
      const videoData =[
      {
        "id": 1,
        "videoSrc": "http://thinksyntax.com/game/videos/video.mp4",
        "timestamps": "00:04,00:08,0:12"
      },
      {
        "id": 2,
        "videoSrc": "http://thinksyntax.com/game/videos/video1.mp4",
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
      const gameId = video.getAttribute("data-gameid"); // Get the data-gameid attribute
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
  @endsection


@section('scripts')
  @parent
@endsection

