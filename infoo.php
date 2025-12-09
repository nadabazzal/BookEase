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
      font-size: 12px;
    }

    .tabs a {
      color: #fff;
      opacity: 0.9;
    }

    /* ---------- CONTENT ---------- */
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

    .rating-pill {
      background: var(--accent);
      padding: 9px 18px;
      border-radius: 14px;
    }

    .book-btn {
      background: #0f4155;
      padding: 10px 24px;
      border-radius: 30px;
      cursor: pointer;
      border: 1px solid #ffffff44;
      white-space: nowrap;
    }

    .book-btn:hover {
      background: #0f4155;
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

   
  </style>
</head>

<body>
 <?php include 'navbar.html'; ?>

   

  <!-- HERO -->
  <section class="hero-section">
    <img src="images/hotel.png" class="hero-img" alt="">
    <div class="hero-overlay">
      <h1 class="hero-title">Le nadeen Beirut</h1>
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

  </main>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const btn = document.getElementById("show-more-rooms");
      const extra = document.getElementById("extra-rooms");

      btn.addEventListener("click", () => {
        if (extra.classList.contains("hidden")) {
          extra.classList.remove("hidden");
          btn.textContent = "Show less rooms";
        } else {
          extra.classList.add("hidden");
          btn.textContent = "Show more rooms";
        }
      });
    });
  </script>


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
  <script>
    const toggle = document.getElementById("menu-toggle");
    const links = document.getElementById("nav-links");

    toggle.addEventListener("click", () => {
      links.classList.toggle("active");
    });
  </script>
</body>
</html>