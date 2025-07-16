<?php
session_start(); // Start session for managing user interactions or state
include('db.php'); // Include database connection
include('header.php'); // Include the site header (logo, nav, etc.)
?>

<!DOCTYPE html>
<html lang="en">

<body>
  <div class="carousel-container">

    <div class="carousel">
      <?php
      // Get 10 random games from the database to feature in the carousel
      $featuredGamesResult = $conn->query("SELECT * FROM games ORDER BY RAND() LIMIT 10");
      // Loop through each row in the result set as an associative array
      $result = $conn->query("SELECT * FROM games");
      // Display each game in a product card layout
      while ($row = $result->fetch_assoc()):
      ?>
        <div class="carousel-slide">
          <!-- Output a single slide in the carousel with the game's image, price, and title -->
          <img src="images/<?php echo $row['image_filename']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
          <div class="carousel-price"><?php echo '$' . number_format($row['price'], 2); ?></div>
          <div class="carousel-title"><?php echo htmlspecialchars($row['name']); ?></div>
        </div>
      <?php endwhile; ?>
    </div>

    <div class="promo-card">
      <img src="images/clover.png" alt="Clover Icon" class="promo-icon">
      <h2>Try your luck!</h2>
      <p>Open a Backlog Booster™️and score a random game.</p>
      <a href="lootbox.php" class="promo-button">Open Loot Crate</a>
    </div>

  </div>

  <div class="products-container">
    <?php
    // Retrieve all games from the database and loop through each one if results are found
    $query = "SELECT * FROM games";
    $result = $conn->query("SELECT * FROM games");

    if ($result && $result->num_rows > 0):
        while ($game = $result->fetch_assoc()):
    ?>
      <div class="game-card">
        <!-- Display a single game's image, name, description, price, and user score in a styled card -->
        <img src="images/<?php echo htmlspecialchars($game['image_filename']); ?>" alt="<?php echo htmlspecialchars($game['name']); ?>">
        <h2><?php echo htmlspecialchars($game['name']); ?></h2>
        <p class="description"><?php echo htmlspecialchars($game['description']); ?></p>
        <p class="price">$<?php echo number_format($game['price'], 2); ?></p>
        <p class="score">User Score: <?php echo $game['user_score']; ?>%</p>
      </div>
    <?php
    // If no games are found, display a message; otherwise, close the database connection
        endwhile;
    else:
        echo "<p>No games found.</p>";
    endif;
    $conn->close();
    ?>

  </div>

  <!--Basic JS carousel-->
  <script>
    const carousel = document.querySelector('.carousel');
    let scrollAmount = 0;
    const scrollStep = 1;

    // Smoothly auto-scrolls the carousel left to right and loops it back to the start
    function autoScrollCarousel() {
      if (!carousel) return;

      scrollAmount += scrollStep;
      carousel.scrollLeft = scrollAmount;

      if (scrollAmount >= carousel.scrollWidth - carousel.clientWidth) {
        scrollAmount = 0; // loop back to start
      }

      requestAnimationFrame(autoScrollCarousel);
    }

    requestAnimationFrame(autoScrollCarousel);
  </script>

  <!--Include the site footer, includes ToS link-->
  <?php include('footer.php'); ?>

</body>
</html>

<!-- Yes, this site is evil -->