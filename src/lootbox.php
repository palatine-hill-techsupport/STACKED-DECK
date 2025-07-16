<?php
// Start the session so I can store user data like bad luck streaks
session_start();

// Include the database connection so I can access game data
include('db.php');

// Also include the header layout
include('header.php');

// Set up a default for the selected game so I can use it later
$selectedGame = null;

// Initialise the score color so I can style it later based on the roll
$scoreColor = '';

// Initialise the score value for use after a roll
$score = 0;

// If the user clicked the "open booster" button, start the loot roll logic
if (isset($_GET['open']) && $_GET['open'] === 'true') {

    // Fetch all the games from the database
    $result = $conn->query("SELECT * FROM games");
    $games = [];

    // Loop through each game and weight it based on its rarity (lower score = higher chance)
    while ($row = $result->fetch_assoc()) {
        $rarity = 100 - (int)str_replace('%', '', $row['user_score']);
        $rarity = max(1, $rarity); // Make sure every game appears at least once
        for ($i = 0; $i < $rarity; $i++) {
            $games[] = $row; // Add the game multiple times to simulate weight
        }
    }

    // I track how many bad rolls the user has had in a row using a session
    if (!isset($_SESSION['bad_luck'])) {
        $_SESSION['bad_luck'] = 0;
    }

    // Randomly select a game from the weighted list
    $selectedGame = $games[array_rand($games)];

    // If the user has had 5 bad rolls, I guarantee them the highest-scoring game and reset the counter
    if ($_SESSION['bad_luck'] >= 5) {
        $result = $conn->query("SELECT * FROM games ORDER BY user_score DESC LIMIT 1");
        $selectedGame = $result->fetch_assoc();
        $_SESSION['bad_luck'] = 0;
    } else {
        $_SESSION['bad_luck'] += 1; // Otherwise, I increment their bad luck
    }

    // Calculate the score color based on the user score so I can style it later
    $score = (int)str_replace('%', '', $selectedGame['user_score']);
    if ($score >= 90) {
        $scoreColor = 'gold';
    } elseif ($score >= 75) {
        $scoreColor = 'green';
    } elseif ($score >= 50) {
        $scoreColor = 'orange';
    } else {
        $scoreColor = 'red';
    }
}
?>

<!-- This wraps the whole page content for consistent layout -->
<div class="page-wrapper">

  <!-- This container holds the lootbox-specific layout -->
  <div class="lootbox-container">
    <h1>Backlog Booster</h1>

    <!-- Show the booster icon if no game has been rolled yet -->
    <?php if (!$selectedGame): ?>
      <img src="images/logo.png" alt="Logo" class="booster-icon">
    <?php endif; ?>

    <!--If no game has been rolled, show the countdown and the Open Booster button -->
    <?php if (!$selectedGame): ?>
      <div id="countdown" class="countdown-display"></div>
      <a href="lootbox.php?open=true" class="roll-again" id="roll-button">Open Booster</a>
    <?php endif; ?>

    <!-- If a game has been rolled, show the result as a game card. 
     If I'd had more time I would have loved to implement https://github.com/catdad/canvas-confetti or a pure CSS based version. 
    -->
    <?php if ($selectedGame): ?>
      <div class="game-card">
        <img src="images/<?php echo $selectedGame['image_filename']; ?>" alt="<?php echo $selectedGame['name']; ?>">
        <div class="game-info">
          <h2><?php echo $selectedGame['name']; ?></h2>
          <p class="description"><?php echo $selectedGame['description']; ?></p>
          <p class="price">$<?php echo number_format($selectedGame['price'], 2); ?></p>
          <p class="score">
            <strong>User Score:</strong>
            <span style="color: var(--<?php echo $scoreColor; ?>)">
              <?php echo $selectedGame['user_score']; ?>
            </span>
          </p>
        </div>
      </div>

      <!-- Show the Roll Again button once a game has been revealed -->
      <a href="lootbox.php" class="roll-again">Roll Again</a>

    <?php endif; ?>
  </div>
</div>


<script>
  // Grab references to the button and countdown display elements
  const rollButton = document.getElementById('roll-button');
  const countdownEl = document.getElementById('countdown');

  // Only run if both the button and countdown elements are found
  if (rollButton && countdownEl) {
    rollButton.addEventListener('click', function (e) {
      e.preventDefault(); // Prevents default link behaviour

      // Show the countdown element and start at "3"
      countdownEl.style.display = 'block';
      countdownEl.textContent = "3";

      const sequence = [3, 2, 1]; // Countdown sequence
      let index = 0;

      // Begin countdown loop
      const interval = setInterval(() => {
        index++;
        if (index >= sequence.length) {
          clearInterval(interval); // Stop countdown
          window.location.href = "lootbox.php?open=true"; // Trigger booster roll for them sweet game drops
        } else {
          countdownEl.textContent = sequence[index]; // Update countdown number
        }
      }, 1000); // One second between each step
    });
  }
</script>

<!--Include the site footer, includes ToS link-->
<?php include('footer.php'); ?>

<!-- Yes, this site is evil -->