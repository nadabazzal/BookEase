<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Le Gray Beirut</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome (icons in footer & amenities) -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
  />

  <style>
    :root {
      --bg-main: #214a5c;
      --bg-dark: #153649;
      --bg-card: #1e4e62;
      --accent: #3cb371;
      --text-main: #ffffff;
      --subtle: #cfd9df;
      --border-line: #3c6275;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      margin: 0;
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
      margin-top: 74px; /* navbar height */
      position: relative;
      width: 100%;
      height: 430px;
      overflow: hidden;
    }

    .hero-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .hero-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    to bottom,
    rgba(12, 36, 50, 0.6),
    rgba(12, 36, 50, 0.9)
  );
  display: flex;
  align-items: center;       /* center vertically  */
  justify-content: center;   /* center horizontally */
  padding: 0;                /* remove top padding */
  text-align: center;
}

.hero-title {
  font-size: 50px;
  font-weight: 500;
  letter-spacing: 2px;
  font-family:TAN Mon Cheri;
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
      opacity: 0.9;
    }

    .tabs a:hover {
      opacity: 1;
    }

    /* ---------- SECTIONS GENERAL ---------- */
    main {
      width: 90%;
      max-width: 1100px;
      margin: 35px auto 60px;
    }

    section {
      margin-bottom: 32px;
    }

    .section-header {
      display: inline-block;
      padding: 10px 26px;
      background: var(--bg-main);
      border-radius: 25px;
      border: 1px solid var(--border-line);
      font-size: 18px;
      font-weight: 500;
      margin-bottom: 18px;
    }

    .section-divider {
      border: none;
      border-top: 1px solid var(--border-line);
      margin: 26px 0;
    }

    /* ---------- DETAILS BOX ---------- */
    .details-box {
      background: var(--bg-card);
      padding: 18px 22px;
      border-radius: 18px;
      display: flex;
      flex-wrap: wrap;
      gap: 18px 28px;
      align-items: center;
      justify-content: space-between;
      font-size: 13px;
    }

    .details-left {
      display: flex;
      flex-wrap: wrap;
      gap: 18px 30px;
      align-items: center;
    }

    .detail-item {
      display: flex;
      align-items: center;
      gap: 8px;
      white-space: nowrap;
    }

    .rating-pill {
      background: var(--accent);
      padding: 9px 18px;
      border-radius: 14px;
      font-weight: 600;
    }

    .book-btn {
      background: #0f4155;
      padding: 10px 24px;
      border-radius: 30px;
      font-size: 13px;
      cursor: pointer;
      border: 1px solid #ffffff44;
      white-space: nowrap;
      color: #ffffff;
    }

    .book-btn:hover {
      background: #0f4155;
    }

    /* ---------- ROOMS & PRICES ---------- */
    .rooms-row {
      background: var(--bg-card);
      border-radius: 18px;
      padding: 14px;
      display: grid;
      grid-template-columns: 1.1fr 1.1fr;
      gap: 18px;
      align-items: stretch;
    }

    .room-photo-card {
      background: #1b4355;
      border-radius: 14px;
      overflow: hidden;
    }

    .room-photo-card img {
      display: block;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .room-details-card {
      background: #205b72;
      border-radius: 14px;
      padding: 18px 20px 16px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      font-size: 12px;
      line-height: 1.6;
    }

    .room-title {
      font-size: 16px;
      font-weight: 600;
      margin-bottom: 6px;
    }

    .room-meta {
      font-size: 10px;
      margin-bottom: 10px;
      opacity: 0.9;
    }

    .room-list {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 3px 18px;
      margin-bottom: 10px;
    }

    .room-bottom {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 6px;
    }

    .room-guests {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 12px;
    }

    .room-price {
      background: var(--bg-main);
      padding: 6px 14px;
      border-radius: 18px;
      font-size: 12px;
      white-space: nowrap;
    }

    .rooms-more {
      margin-top: 10px;
      font-size: 12px;
      padding: 7px 16px;
      border-radius: 18px;
      background: var(--bg-dark);
      display: inline-block;
      cursor: pointer;
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

  
  </style>
</head>

<body>
          <?php include 'navbar.html'; ?>


  <!-- HERO -->
  <section class="hero-section">
    <img src="images/hotel.png" alt="Hotel" class="hero-img" />
    <div class="hero-overlay">
      <h1 class="hero-title">Le Gray Beirut</h1>
    </div>
  </section>

  <!-- TABS -->
  <div class="tabs">
    <a href="#details">Details</a>
    <a href="#rooms">Rooms and prices</a>
    <a href="#amenities">Hotel Amenities</a>
    <a href="#about">About the hotel</a>
  </div>

  <main>
    <!-- DETAILS -->
    <section id="details">
      <div class="section-header">DETAILS</div>

      <div class="details-box">
        <div class="details-left">
          <div class="detail-item">
            📍 <span>Downtown Beirut, Lebanon</span>
          </div>

          <div class="detail-item rating-pill">9.3</div>

          <div class="detail-item">
            📞 <span>+961 81/363443</span>
          </div>

          <div class="detail-item">
            📧 <span>legray@gmail.com</span>
          </div>
        </div>

        <button href="booking.php" class="book-btn">Book Now</button>
      </div>
    </section>

    <hr class="section-divider" />

    <!-- ROOMS AND PRICES -->
    <section id="rooms">
      <div class="section-header">ROOMS AND PRICES</div>

      <div class="rooms-row">
        <div class="room-photo-card">
          <img src="room1.jpg" alt="Corner One Bedroom Suite" />
        </div>

        <div class="room-details-card">
          <div>
            <h3 class="room-title">Corner One Bedroom Suite</h3>
            <p class="room-meta">
              Corner Suite, 1 King Bed, Separate Living Room, Dining Table, 90 sqm
            </p>

            <div class="room-list">
              <span>✓ Sea View</span>
              <span>✓ Free WiFi</span>
              <span>✓ King Bed</span>
              <span>✓ Breakfast</span>
              <span>✓ Jacuzzi</span>
              <span>✓ Smart TV</span>
            </div>
          </div>

          <div class="room-bottom">
            <div class="room-guests">
              <i class="fa-solid fa-user-group"></i>
              <span>3</span>
            </div>
            <div class="room-price">500$ US/night</div>
          </div>
        </div>
      </div>

      <div class="rooms-more">Show more rooms</div>
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
    <?php include 'footer.html'; ?>

</body>
</html>
