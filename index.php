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
        <img src="hotel5.jpg" class="hero-img">

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
            <img src="HOTEL-IMAGE-1" alt="Hotel Image">
            <h3>LE GRANDE SUITE</h3>
            <p class="rating">★★★★★</p>
            <a href="#" class="btn-secondary">View Details</a>
        </div>

        <!-- Hotel Card 2 -->
        <div class="hotel-card">
            <img src="HOTEL-IMAGE-2" alt="Hotel Image">
            <h3>ROYAL ELITE SUITE</h3>
            <p class="rating">★★★★★</p>
            <a href="#" class="btn-secondary">View Details</a>
        </div>

        <!-- Hotel Card 3 -->
        <div class="hotel-card">
            <img src="HOTEL-IMAGE-3" alt="Hotel Image">
            <h3>MONARCH HOTEL</h3>
            <p class="rating">★★★★★</p>
            <a href="#" class="btn-secondary">View Details</a>
        </div>

    </div>
</section>



<!-- ========= HOW IT WORKS ========= -->
<section class="how-section">
    <h2 class="section-title">How It Works</h2>

    <div class="how-grid">

        <div class="how-step">
              <i class="fa-solid fa-magnifying-glass"></i>
            <h4>01 Search your city</h4>
            <p>Discover the best hotels in seconds.</p>
        </div>

        <div class="how-step">
              <i class="fa-solid fa-building"></i>
            <h4>02 Compare hotels</h4>
            <p>Compare ratings, reviews, and locations.</p>
        </div>

        <div class="how-step">
              <i class="fa-solid fa-user-group"></i>
            <h4>03 Check availability</h4>
            <p>See available rooms instantly.</p>
        </div>

        <div class="how-step">
              <i class="fa-solid fa-suitcase-rolling"></i>
            <h4>04 Book your hotel</h4>
            <p>Secure your stay quickly and easily.</p>
        </div>

    </div>
</section>
<!-- ========= end ========= -->













<!-- ========= BOTTOM IMAGE SECTION ========= -->
<section class="bottom-image">
    <img src="BOTTOM-IMAGE-LINK" alt="Hotel Interior">
</section>

    <?php include 'footer.html'; ?>


</body>
</html>


