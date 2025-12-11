<?php
session_start();
$conn=mysqli_connect("localhost","root","","hotel_management_system");
if( !$conn){
  die("Connection failed: ". mysqli_connect_error());
}
// 2) READ hotel_id & city FROM URL
if (!isset($_GET['hotel_id'])) {
    die("No hotel selected.");
}

$hotel_id = (int) $_GET['hotel_id'];          // from link
$selected_city = isset($_GET['city']) ? $_GET['city'] : '';

// 3) GET HOTEL INFO
$sqlHotel = "SELECT hotel_id, hotel_name, description, rating,
                    country, city, base_price
             FROM hotels
             WHERE hotel_id = $hotel_id
               AND status = 'approved'";

$resultHotel = mysqli_query($conn, $sqlHotel);

if (!$resultHotel || mysqli_num_rows($resultHotel) == 0) {
    die("Hotel not found.");
}

$hotel = mysqli_fetch_assoc($resultHotel);
// 4-bis) GET ROOM FEATURES FOR ALL ROOMS OF THIS HOTEL
$roomFeatures = []; // room_id => [feature1, feature2, ...]

$sqlRoomFeat = "
    SELECT rfm.room_id, rf.featurer_name
    FROM rooms_feature_map AS rfm
    JOIN roomsfeatures AS rf ON rf.featurer_id = rfm.featurer_id
    JOIN rooms AS r ON r.room_id = rfm.room_id
    WHERE r.hotel_id = $hotel_id
";

$resultRoomFeat = mysqli_query($conn, $sqlRoomFeat);

if ($resultRoomFeat && mysqli_num_rows($resultRoomFeat) > 0) {
    while ($row = mysqli_fetch_assoc($resultRoomFeat)) {
        $rid = $row['room_id'];
        if (!isset($roomFeatures[$rid])) {
            $roomFeatures[$rid] = [];
        }
        $roomFeatures[$rid][] = $row['featurer_name'];
    }
  }
// 5) GET ROOMS FOR THIS HOTEL
$sqlRooms = "SELECT room_id, room_type, price, capacity, status
             FROM rooms
             WHERE hotel_id = $hotel_id
             ORDER BY price ASC";

$resultRooms = mysqli_query($conn, $sqlRooms);
$rooms = [];
if ($resultRooms && mysqli_num_rows($resultRooms) > 0) {
    while ($row = mysqli_fetch_assoc($resultRooms)) {
        $rooms[] = $row;
    }
}

// 6) GET HOTEL FEATURES (AMENITIES)
$sqlFeatures = "SELECT hf.feature_name
                FROM hotelsfeatures hf
                JOIN hotels_features_map m
                    ON m.feature_id = hf.feature_id
                WHERE m.hotel_id = $hotel_id";

$resultFeat = mysqli_query($conn, $sqlFeatures);
$features = [];
if ($resultFeat && mysqli_num_rows($resultFeat) > 0) {
    while ($row = mysqli_fetch_assoc($resultFeat)) {
        $features[] = $row['feature_name'];
    }
  }


?>
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
      font-size: 12px;
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

   
.btn-primary {
    padding: 10px 20px;
    background:  #ffffff;
    color: #0b2d39;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;


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
    <br><br><br><br>

  <!-- HERO -->
  <section class="hero-section">
    <img src="images/hotel.png" alt="Hotel" class="hero-img" />
    <div class="hero-overlay">
      <h1 class="hero-title"><?php echo htmlspecialchars($hotel['hotel_name']); ?></h1>
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
            📍 <span><?php echo htmlspecialchars($hotel['city'] . ', ' . $hotel['country']); ?></span>
          </div>

          <div class="detail-item rating-pill"><?php echo htmlspecialchars($hotel['rating']); ?></div>
        <a href="booking.php?room_id=<?php echo $room['room_id']; ?>" class="btn-primary">Book Now</a><br>
      </div>
    </section>

    <hr class="section-divider" />
 <!-- ROOMS -->
    <section id="rooms">
      <div class="section-header">ROOMS AND PRICES</div>
      <?php
    // if we have at least one room, use the first as ROOM 1
    $firstRoom = !empty($rooms) ? $rooms[0] : null;
  ?>

      <!-- ROOM 1 -->
     <?php if ($firstRoom): ?>
      <div class="rooms-row">
        <div class="room-photo-card">
          <img src="images/room.jpg" alt="">
        </div>

        <div class="room-details-card">
          <div>
            <h3 class="room-title"><?php echo htmlspecialchars($rooms[0]['room_type']); ?></h3>
            <p class="room-meta"><?php echo htmlspecialchars($rooms[0]['capacity'] . ' guests'); ?></p>

            <div class="room-list">
              <?php
  $featList = isset($roomFeatures[$room['room_id']])
      ? $roomFeatures[$room['room_id']]
      : [];
?>
<div class="room-list">
  <?php if (!empty($featList)): ?>
    <?php foreach ($featList as $feat): ?>
      <span>✓ <?php echo htmlspecialchars($feat); ?></span>
    <?php endforeach; ?>
  <?php else: ?>
    <span>No features listed</span>
  <?php endif; ?>
</div>

            </div>
          </div>

          <div class="room-bottom">
            <span><i class="fa-solid fa-user-group"> <?php echo (int)$firstRoom['capacity']; ?></span>
            <span class="room-price"> <?php echo number_format($firstRoom['price'], 2); ?>$ US / night</span>
          </div>
        </div>
      </div>
<?php else: ?>
    <p>No rooms available for this hotel.</p>
  <?php endif; ?>
   <?php if (!empty($rooms) && count($rooms) > 1): ?>
      <!-- EXTRA ROOMS — HIDDEN -->
      <div id="extra-rooms" class="hidden">
        <?php
        // start from index 1 (second room) for "extra" rooms
        for ($i = 1; $i < count($rooms); $i++):
          $room = $rooms[$i];
          $featList = isset($roomFeatures[$room['room_id']])
              ? $roomFeatures[$room['room_id']]
              : [];
      ?>
   <div class="rooms-row">
          <div class="room-photo-card">
            <img src="images/room.jpg" alt="">
          </div>

          <div class="room-details-card">
            <div>
              <h3 class="room-title">
                <?php echo htmlspecialchars($room['room_type']); ?>
              </h3>
              <p class="room-meta">
                <?php echo htmlspecialchars($room['capacity'] . ' guests'); ?>
              </p>

              <div class="room-list">
                <?php if (!empty($featList)): ?>
                  <?php foreach ($featList as $feat): ?>
                    <span>✓ <?php echo htmlspecialchars($feat); ?></span>
                  <?php endforeach; ?>
                <?php else: ?>
                  <span>No features listed</span>
                <?php endif; ?>
              </div>
            </div>

            <div class="room-bottom">
              <span>
                <i class="fa-solid fa-user-group"></i>
                <?php echo (int)$room['capacity']; ?>
              </span>
              <span class="room-price">
                <?php echo number_format($room['price'], 2); ?>$ US / night
              </span>
            </div>
          </div>
        </div>
      <?php endfor; ?>
    </div>

       
      <!-- BUTTON -->
      <button id="show-more-rooms" class="rooms-more">Show more rooms</button>
  <?php endif; ?>
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