<?php
$conn = mysqli_connect("localhost", "root", "", "hotel_management_system");
if (!$conn) die("Connection failed: " . mysqli_connect_error());

$sqlRec = "
  SELECT hotel_id, hotel_name, rating, image
  FROM hotels
  WHERE status='approved'
  ORDER BY rating DESC, hotel_id DESC
  LIMIT 3
";
$resRec = mysqli_query($conn, $sqlRec);

$recommended = [];
while ($row = mysqli_fetch_assoc($resRec)) {
  $recommended[] = $row;
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
   
     <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">





    <!-- External CSS -->
    <link rel="stylesheet" href="index.css">
    
</head>
<body>
        <?php include 'navbar.html'; ?>


<!-- ========= HEADER SECTION (BANNER) ========= -->
<section class="hero-section">
        <img src="images/hotel5.jpg" class="hero-img">

    <div class="hero-text">
        <h1>BOOKEASE</h1><br>
        <p>Find the Perfect Stay Wherever You Go.</p><br>
        <a href="search.php" class="btn-primary">Book Now</a><br>
    </div>
</section>



<!-- ========= RECOMMENDED HOTELS ========= -->
<section class="recommended">
    <h2 class="section-title">RECOMMENDED HOTELS</h2>
    <p class="section-subtitle">
       Choose from our carefully curated selection of luxury accommodations, each designed to provide the ultimate comfort and elegance. </p>

  

    <div class="hotel-cards">
  <?php foreach ($recommended as $h): 
    $img = !empty($h['image']) ? $h['image'] : "images/default-hotel.jpg";
  ?>
    <div class="hotel-card">
      <img src="<?php echo htmlspecialchars($img); ?>" alt="Hotel Image">
      <h3><?php echo htmlspecialchars($h['hotel_name']); ?></h3>
      <p class="rating"><?php echo htmlspecialchars($h['rating']); ?></p>
      <a href="info.php?hotel_id=<?php echo (int)$h['hotel_id']; ?>" class="btn-secondary">
        View Details
      </a>
    </div>
  <?php endforeach; ?>
</div>




    
</section>



<!-- ========= HOW IT WORKS ========= -->
<section class="how-section">
  <div class="how-container">

    <h2 class="how-title">How It Works</h2>
    <div class="how-underline"></div>

    <div class="how-grid">
      <!-- عمود يسار -->
      <div class="how-column">
        <div class="how-card">
          <div class="how-card-header">
<i class="fas fa-search"></i>
            <span class="how-step-number">01</span>
          </div>
          <h3 class="how-card-title">Search for your city</h3>
          <p class="how-card-text">Find hotels instantly by entering your destination.</p>
        </div>

        <div class="how-card">
          <div class="how-card-header">
<i class="fas fa-hotel"></i>
            <span class="how-step-number">02</span>
          </div>
          <h3 class="how-card-title">Compare hotels</h3>
          <p class="how-card-text">View ratings, prices, and locations side-by-side.</p>
        </div>
      </div>

      <!-- الصورة بالنص -->
      <div class="how-center">
        <div class="how-image-wrap">
          <img src="images/hotel1.jpg" alt="Hotel" class="how-image">
        </div>
      </div>

      <!-- عمود يمين -->
      <div class="how-column">
        <div class="how-card">
          <div class="how-card-header"><i class="fas fa-users"></i>

            <span class="how-step-number">03</span>
          </div>
          <h3 class="how-card-title">Check availability</h3>
          <p class="how-card-text">See room options and available dates instantly.</p>
        </div>

        <div class="how-card">
          <div class="how-card-header">
<i class="fas fa-user"></i>
            <span class="how-step-number">04</span>
          </div>
          <h3 class="how-card-title">Book your hotel</h3>
          <p class="how-card-text">Reserve your stay securely in a few taps.</p>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- ========= end ========= -->













<!-- ========= BOTTOM IMAGE SECTION ========= -->
<section >
    <img src="images/BOTTOM-IMAGE-LINK.jpg" alt="Hotel Interior" class="bottom-image">
</section>

    <?php include 'footer.html'; ?>


</body>
</html>


