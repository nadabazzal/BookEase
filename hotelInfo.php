<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Le Gray Beirut</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    crossorigin="anonymous"
  />

  <style>
    :root {
      --bg-main: #214a5c;
      --bg-dark: #153649;
      --bg-card: #1e4e62;
      --accent: #3cb371;
      --text-main: #ffffff;
      --border-line: #3c6275;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: "Montserrat", sans-serif;
      background: var(--bg-main);
      color: var(--text-main);
    }

    a {
      color: inherit;
      text-decoration: none;
    }


    /* ---------- HERO ---------- */
    .hero-section {
      margin-top: 74px;
      width: 100%;
      height: 430px;
      position: relative;
    }

    .hero-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .hero-overlay {
      position: absolute;
      inset: 0;
      background: rgba(12, 36, 50, 0.7);
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
    }

    .hero-title {
      font-size: 50px;
      font-weight: 500;
      letter-spacing: 2px;
      font-family: "Tan Mon Cheri";
    }

    /* ---------- TABS ---------- */
    .tabs {
      width: 100%;
      display: flex;
      justify-content: center;
      background: #1c4454;
      padding: 12px 10px;
      gap: 60px;
      font-size: 20px;
    }

    .tabs a {
      color: #fff;
      opacity: 0.9;
    }

<<<<<<< HEAD
    /* ---------- SECTIONS GENERAL ---------- */
=======
    /* ---------- CONTENT ---------- */
>>>>>>> 18e3c9d1fbe54710805e334d256d691277a8fe64
    main {
      width: 90%;
      max-width: 1100px;
      margin: 35px auto 60px;
    }

    .section-header {
      padding: 10px 26px;
      background: var(--bg-main);
      border-radius: 25px;
      border: 1px solid var(--border-line);
      font-size: 18px;
      display: inline-block;
      margin-bottom: 18px;
    }

    /* ---------- DETAILS BOX ---------- */
    .details-box {
      background: var(--bg-card);
      padding: 18px 22px;
      border-radius: 18px;
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 20px;
    }

    .detail-item {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
    }

   
    .book-btn {
      background: #fdfdfdff;
color:0f4155
      padding: 10px 24px;
      border-radius: 30px;
      cursor: pointer;
      border: 1px solid #ffffff44;
      white-space: nowrap;
    }

    /* ---------- ROOMS ---------- */
    .rooms-row {
      background: var(--bg-card);
      border-radius: 18px;
      padding: 14px;
      display: grid;
      grid-template-columns: 1.1fr 1.1fr;
      gap: 18px;
      margin-bottom: 12px;
    }

    .room-photo-card img {
      width: 100%;
      height: 100%;
      border-radius: 14px;
      object-fit: cover;
    }

    .room-details-card {
      background: #205b72;
      border-radius: 14px;
      padding: 18px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .room-title {
      font-size: 16px;
      margin-bottom: 6px;
      font-weight: 600;
    }

    .room-meta {
      font-size: 10px;
      opacity: 0.9;
      margin-bottom: 10px;
    }

    .room-list {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 3px 18px;
      margin-bottom: 10px;
      font-size: 12px;
    }

    .room-bottom {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .room-price {
      background: var(--bg-main);
      padding: 6px 14px;
      border-radius: 18px;
      font-size: 13px;
    }

    .rooms-more {
      background: var(--bg-dark);
      padding: 10px 20px;
      border-radius: 20px;
      border: none;
      cursor: pointer;
      color: #fff;
      display: inline-block;
      margin-top: 10px;
    }

    /* Hidden initially */
    .hidden {
      display: none;
    }
    /* ---------- AMENITIES ---------- */
    .amenities-card {
      background: var(--bg-card);
      border-radius: 18px;
      padding: 18px 24px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap: 14px 30px;
      font-size: 13px;
    }

    .amenity {
      display: flex;
      align-items: center;
      gap: 9px;
    }

    .amenity i {
      width: 22px;
      text-align: center;
    }

    /* ---------- ABOUT ---------- */
    .about-box {
      background: var(--bg-card);
      border-radius: 18px;
      padding: 20px 24px;
      font-size: 13px;
      line-height: 1.8;
      max-width: 700px;
    }

    /* ---------- FOOTER ---------- */
    .footer {
      background-color: #2a4e61;
      color: #d7d7d7;
      padding: 55px 9%;
      display: flex;
      justify-content: space-between;
      gap: 60px;
      flex-wrap: wrap;
    }

    .footer-item {
      flex: 1;
      min-width: 260px;
    }

    .footer h2 {
      font-size: 24px;
      margin-bottom: 22px;
      color: #ffffff;
    }

    .footer-item p {
      font-size: 14px;
      line-height: 1.7;
      margin: 10px 0;
      color: #e0e0e0;
      max-width: 430px;
    }

    .footer-item i {
      color: #cba135;
      font-size: 18px;
      margin-right: 10px;
    }

    .social-icons i {
      font-size: 18px;
      color: #cba135;
      margin-right: 20px;
      border: 2px solid #cba135;
      padding: 9px;
      border-radius: 50%;
      width: 18px;
      height: 18px;
      text-align: center;
      line-height: 18px;
      transition: 0.3s;
      cursor: pointer;
    }

    .social-icons i:hover {
      background-color: #cba135;
      color: #2a4e61;
    }

    .highlight {
      color: #ffc54d;
      font-weight: 600;
    }

    /* ---------- RESPONSIVE ---------- */
    @media (max-width: 900px) {
      .rooms-row {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 768px) {
      .navbar {
        padding: 10px 18px;
      }

      .nav-links {
        position: absolute;
        top: 54px;
        right: 0;
        background-color: var(--bg-dark);
        width: 100%;
        flex-direction: column;
        align-items: center;
        display: none;
        padding: 14px 0 20px;
      }

      .nav-links.active {
        display: flex;
      }

      .menu-toggle {
        display: block;
      }

      .hero-section {
        height: 320px;
      }

      .hero-overlay {
        padding-top: 70px;
      }

      .hero-title {
        font-size: 30px;
      }

      .tabs {
        gap: 22px;
        font-size: 11px;
        flex-wrap: wrap;
      }

      main {
        width: 92%;
      }

      .details-box {
        flex-direction: column;
        align-items: flex-start;
      }

      .book-btn {
        align-self: flex-end;
      }
    }

    @media (max-width: 480px) {
      .section-header {
        font-size: 16px;
        padding: 8px 18px;
      }

      .about-box {
        font-size: 12px;
      }
    }
  </style>
</head>

<body>
  <!-- NAVBAR -->
  <header class="navbar">
    <div class="logo">
      <img src="logo.png" alt="Bookease Logo" />
      
    </div>

    <nav>
      <ul class="nav-links" id="nav-links">
        <li><a href="#">Home</a></li>
        <li><a href="#">Search</a></li>
        <li><a href="#">Favorites</a></li>
        <li><a href="#">Log in</a></li>
      </ul>
    </nav>

    <div class="menu-toggle" id="menu-toggle">
      <i class="fas fa-bars"></i>
    </div>
  </header>

  <!-- HERO -->
  <section class="hero-section">
    <img src="images/hotel.png" class="hero-img" alt="">
    <div class="hero-overlay">
      <h1 class="hero-title">Le Gray Beirut</h1>
    </div>
  </section>

  <!-- TABS -->
  <div class="tabs">
    <a href="#details">Details</a>
    <a href="#rooms">Rooms and prices</a>
    <a href="#amenities">Amenities</a>
    <a href="#about">About</a>
  </div>

  <main>

    <!-- DETAILS -->
    <section id="details">
      <div class="section-header">DETAILS</div>

      <div class="details-box">
        <div class="details-left">
          <div class="detail-item">
            <i class="fas fa-map-marker-alt"></i>
 <span>Downtown Beirut, Lebanon</span>
          </div>

  <div class="detail-item rating-pill"><i class="fas fa-star"></i>
9.3</div>

          <div class="detail-item">
            <i class="fas fa-phone"></i>
 <span>+961 81/363443</span>
          </div>

          <div class="detail-item">
            <i class="fas fa-envelope"></i>
 <span>legray@gmail.com</span>
          </div>
        </div>

        <button class="book-btn">Book Now</button>
      </div>
    </section>

    <hr style="border-top:1px solid #3c6275;margin:25px 0;">

    <!-- ROOMS -->
    <section id="rooms">
      <div class="section-header">ROOMS AND PRICES</div>

      <!-- ROOM 1 -->
      <div class="rooms-row">
        <div class="room-photo-card">
          <img src="images/room.jpg" alt="">
        </div>

        <div class="room-details-card">
          <div>
            <h3 class="room-title">Corner One Bedroom Suite</h3>
            <p class="room-meta">Corner Suite • 1 King Bed • 90 sqm</p>

            <div class="room-list">
              <span>✓ Sea View</span><span>✓ Free WiFi</span>
              <span>✓ King Bed</span><span>✓ Breakfast</span>
              <span>✓ Jacuzzi</span><span>✓ Smart TV</span>
            </div>
          </div>

          <div class="room-bottom">
            <span><i class="fa-solid fa-user-group"></i> 3</span>
            <span class="room-price">500$ US / night</span>
          </div>
        </div>
      </div>

      <!-- EXTRA ROOMS — HIDDEN -->
      <div id="extra-rooms" class="hidden">

        <!-- ROOM 2 -->
        <div class="rooms-row">
          <div class="room-photo-card">
            <img src="images/room.jpg" alt="">
          </div>

          <div class="room-details-card">
            <div>
              <h3 class="room-title">Deluxe City View Room</h3>
              <p class="room-meta">1 King Bed • City View • 45 sqm</p>

              <div class="room-list">
                <span>✓ City View</span><span>✓ Free WiFi</span>
                <span>✓ King Bed</span><span>✓ Breakfast</span>
                <span>✓ Work Desk</span><span>✓ Smart TV</span>
              </div>
            </div>

            <div class="room-bottom">
              <span><i class="fa-solid fa-user-group"></i> 2</span>
              <span class="room-price">320$ US / night</span>
            </div>
          </div>
        </div>

        <!-- ROOM 3 -->
        <div class="rooms-row">
          <div class="room-photo-card">
            <img src="images/room.jpg" alt="">
          </div>

          <div class="room-details-card">
            <div>
              <h3 class="room-title">Family Suite</h3>
              <p class="room-meta">2 Bedrooms • Living Room • 110 sqm</p>

              <div class="room-list">
                <span>✓ Sea & City View</span><span>✓ Free WiFi</span>
                <span>✓ 2 King Beds</span><span>✓ Breakfast</span>
                <span>✓ Kitchenette</span><span>✓ Smart TV</span>
              </div>
            </div>

            <div class="room-bottom">
              <span><i class="fa-solid fa-user-group"></i> 5</span>
              <span class="room-price">650$ US / night</span>
            </div>
          </div>
        </div>

      </div>

      <!-- BUTTON -->
      <button id="show-more-rooms" class="rooms-more">Show more rooms</button>

    </section>
     <hr class="section-divider" />
<!-- HOTEL AMENITIES -->
    <section id="amenities">
      <div class="section-header">Hotel Amenities</div>

      <div class="amenities-card">
        <div class="amenity">
          <i class="fa-solid fa-person-swimming"></i>
          <span>Swimming pool</span>
        </div>
        <div class="amenity">
          <i class="fa-solid fa-wifi"></i>
          <span>Wifi</span>
        </div>
        <div class="amenity">
          <i class="fa-solid fa-dumbbell"></i>
          <span>Gym</span>
        </div>
        <div class="amenity">
          <i class="fa-solid fa-square-parking"></i>
          <span>Free parking</span>
        </div>
        <div class="amenity">
          <i class="fa-solid fa-utensils"></i>
          <span>Restaurant</span>
        </div>
        <div class="amenity">
          <i class="fa-regular fa-clock"></i>
          <span>24/7 Reception</span>
        </div>
      </div>
    </section>

    <hr class="section-divider" />

    <!-- ABOUT -->
    <section id="about">
      <div class="section-header">About the Hotel</div>

      <div class="about-box">
        Blue Wave Hotel combines elegance and comfort. Located on the Beirut coast,
        we offer spacious rooms, excellent dining, and top-tier hospitality.
      </div>
    </section>
  </main>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="footer-item">
      <h2>Contact Us</h2>
      <p>
        <i class="fa-solid fa-location-dot"></i>
        123 Signature Boulevard<br />
        Preah Sihanouk, Cambodia
      </p>
      <p>
        <i class="fa-solid fa-phone"></i>
        +1 (555) 123-4567
      </p>
      <p>
        <i class="fa-solid fa-envelope"></i>
        info@thesignature.com
      </p>
    </div>

    <div class="footer-item">
      <h2>Stay Connected</h2>
      <p>
        Follow us on social media for updates<br />
        and exclusive offers
      </p>
      <div class="social-icons" style="margin: 25px 0">
        <i class="fab fa-facebook-f"></i>
        <i class="fab fa-twitter"></i>
        <i class="fab fa-instagram"></i>
      </div>
      <p>
        Opening Hours<br />
        <span class="highlight">24/7 Reception</span>
      </p>
    </div>
  </footer>

  <script>
    const toggle = document.getElementById("menu-toggle");
    const links = document.getElementById("nav-links");

    toggle.addEventListener("click", () => {
      links.classList.toggle("active");
    });
  </script>
</body>
</html>
