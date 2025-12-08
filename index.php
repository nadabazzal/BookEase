<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">



<!-- In <head> of your main page -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />






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
        <a href="#" class="btn-primary">Book Now</a><br>
    </div>
</section>



<!-- ========= RECOMMENDED HOTELS ========= -->
<section class="recommended">
    <h2 class="section-title">RECOMMENDED HOTELS</h2>
    <p class="section-subtitle">
       Choose from our carefully curated selection of luxury accommodations, each designed to provide the ultimate comfort and elegance. </p>

    <div class="hotel-cards">

        <!-- Hotel Card 1 -->
        <div class="hotel-card">
            <img src="images/HOTEL-IMAGE-1.jpg" alt="Hotel Image">
            <h3>Beirut Central Hotel</h3>
            <p class="rating">★★★★★</p>
            <a href="#" class="btn-secondary">View Details</a>
        </div>

        <!-- Hotel Card 2 -->
        <div class="hotel-card">
            <img src="images/HOTEL-IMAGE-2.jpg" alt="Hotel Image">
            <h3>Tripoli Old Town Hotel</h3>
            <p class="rating">★★★★☆</p>

            <a href="#" class="btn-secondary">View Details</a>
        </div>

        <!-- Hotel Card 3 -->
        <div class="hotel-card">
            <img src="images/HOTEL-IMAGE-3.jpg" alt="Hotel Image">
            <h3>Byblos Harbor Hotel</h3>
            <p class="rating">★★★★★</p>
            <a href="#" class="btn-secondary">View Details</a>
        </div>

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
            <span class="how-icon">🔍</span>
            <span class="how-step-number">01</span>
          </div>
          <h3 class="how-card-title">Search for your city</h3>
          <p class="how-card-text">Find hotels instantly by entering your destination.</p>
        </div>

        <div class="how-card">
          <div class="how-card-header">
            <span class="how-icon">🏨</span>
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
          <div class="how-card-header">
            <span class="how-icon">👥</span>
            <span class="how-step-number">03</span>
          </div>
          <h3 class="how-card-title">Check availability</h3>
          <p class="how-card-text">See room options and available dates instantly.</p>
        </div>

        <div class="how-card">
          <div class="how-card-header">
            <span class="how-icon">👤</span>
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


