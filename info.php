<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "hotel_management_system");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

/* ------------------------------------------------------
   1) READ hotel_id FROM POST (with GET fallback)
------------------------------------------------------ */
$hotel_id = 0;

if (isset($_POST['hotel_id'])) {
    $hotel_id = (int) $_POST['hotel_id'];
} elseif (isset($_GET['hotel_id'])) {
    // fallback in case someone opens the page by URL
    $hotel_id = (int) $_GET['hotel_id'];
} else {
    die("No hotel selected.");
}

if ($hotel_id <= 0) {
    die("Invalid hotel.");
}

/* ------------------------------------------------------
   2) GET HOTEL INFO
------------------------------------------------------ */
$sqlHotel = "
    SELECT hotel_id, hotel_name, description, rating,
           country, city, base_price,image
    FROM hotels
    WHERE hotel_id = $hotel_id
      AND status = 'approved'
";
$resultHotel = mysqli_query($conn, $sqlHotel);

if (!$resultHotel || mysqli_num_rows($resultHotel) == 0) {
    die("Hotel not found.");
}

$hotel = mysqli_fetch_assoc($resultHotel);

/* ------------------------------------------------------
   3) GET ROOM FEATURES PER ROOM
------------------------------------------------------ */
$roomFeatures = [];  // room_id => features array
$sqlRoomFeat = "
    SELECT rfm.room_id, rf.featurer_name
    FROM rooms_feature_map rfm
    JOIN roomsfeatures rf ON rf.featurer_id = rfm.featurer_id
    JOIN rooms r ON r.room_id = rfm.room_id
    WHERE r.hotel_id = $hotel_id
";
$resultRoomFeat = mysqli_query($conn, $sqlRoomFeat);

while ($row = mysqli_fetch_assoc($resultRoomFeat)) {
    $rid = (int)$row['room_id'];
    if (!isset($roomFeatures[$rid])) {
        $roomFeatures[$rid] = [];
    }
    $roomFeatures[$rid][] = $row['featurer_name'];
}

/* ------------------------------------------------------
   4) GET ROOMS + ONE IMAGE PER ROOM
------------------------------------------------------ */
$sqlRooms = "
    SELECT 
        r.room_id,
        r.room_type,
        r.price,
        r.capacity,
        MIN(ri.image) AS image
    FROM rooms r
    LEFT JOIN room_images ri ON ri.room_id = r.room_id
    WHERE r.hotel_id = $hotel_id
    GROUP BY r.room_id, r.room_type, r.price, r.capacity
    ORDER BY r.price ASC
";
$resultRooms = mysqli_query($conn, $sqlRooms);

$rooms = [];
while ($row = mysqli_fetch_assoc($resultRooms)) {
    $rooms[] = $row;
}

$firstRoom = $rooms[0] ?? null;

/* ------------------------------------------------------
   5) GET HOTEL AMENITIES
------------------------------------------------------ */
$sqlFeatures = "
    SELECT hf.feature_name
    FROM hotelsfeatures hf
    JOIN hotels_features_map m ON m.feature_id = hf.feature_id
    WHERE m.hotel_id = $hotel_id
";
$resultFeat = mysqli_query($conn, $sqlFeatures);

$features = [];
while ($row = mysqli_fetch_assoc($resultFeat)) {
    $features[] = $row['feature_name'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo htmlspecialchars($hotel['hotel_name']); ?></title>

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


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

   
   .hero-section {
    position: relative;
    width: 100%;
    height: 420px;          /* control hero height */
    margin-top: 74px;       /* navbar height */
    overflow: hidden;       /* hides cropped parts */
}
.hotel-image {
    width: 100%;
    height: 100%;
    object-fit: cover;      /* 🔥 THIS DOES THE CROPPING */
    object-position: center;
    display: block;
}


    .hero-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    to bottom,
    rgba(12, 36, 50, 0.4),
    rgba(12, 36, 50, 0.85)
  );
  display: flex;
  align-items: center;       /* center vertically  */
  justify-content: center;   /* center horizontally */
        /* remove top padding */
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
    padding: 12px 26px;
      background: var(--bg-dark);

    color: white;
    border-radius: 25px;         /* Fully rounded */
    border: none;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: 0.25s;
    display: inline-block;
    text-decoration: none;
}
.btn-primary:hover {
    background: #0d8af0;         /* Lighter blue */
    transform: translateY(-2px); /* Floating effect */
}

.btn-primary:active {
    transform: scale(0.96);      /* Tap animation */
}
.btn-secondary {
    padding: 10px 22px;
    background: transparent;
    color: #ffffff;
    border-radius: 25px;
    border: 1px solid #ffffff;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: 0.25s;
    display: inline-block;
    text-decoration: none;
    margin-left: 10px;
}

.btn-secondary:hover {
    background: #0d8af0;
    border-color: #0d8af0;
    transform: translateY(-2px);
}

.btn-secondary:active {
    transform: scale(0.96);
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

<!-- HERO -->
<section class="hero-section">
       <?php
$imagePath = (!empty($hotel['image'])) 
    ? htmlspecialchars($hotel['image']) 
    : 'images/hotel.png';
?>
<img src="<?php echo $imagePath; ?>" alt="Hotel" class="hotel-image">

    <div class="hero-overlay">
        <h1 class="hero-title"><?php echo htmlspecialchars($hotel['hotel_name']); ?></h1>
    </div>
</section>

<!-- TABS -->
<div class="tabs">
    <a href="#details">Details</a>
    <a href="#rooms">Rooms</a>
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
                📍 <?php echo htmlspecialchars($hotel['city'] . ", " . $hotel['country']); ?>
            </div>

            <div class="detail-item rating-pill">
                <?php echo htmlspecialchars($hotel['rating']); ?>
            </div>

            <?php if ($firstRoom): ?>
                <form action="booking.php" method="get" style="display:inline;">
                    <input type="hidden" name="room_id" value="<?php echo (int)$firstRoom['room_id']; ?>">
                    <button type="submit" class="btn-primary">Book Now</button>
                </form>
            <?php endif; ?>

            <form method="POST" action="favorites.php" style="display:inline;">
                <input type="hidden" name="hotel_id" value="<?php echo (int)$hotel_id; ?>">
                <input type="hidden" name="return_to" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                <button type="submit" class="btn-secondary">
                    <i class="fa-regular fa-heart"></i> Add to Favorites
                </button>
            </form>
        </div>
    </div>
</section>

<hr class="section-divider">

<!-- ROOMS -->
<section id="rooms">
    <div class="section-header">ROOMS AND PRICES</div>

    <?php if ($firstRoom): ?>
    <div class="rooms-row">
        <div class="room-photo-card">
            <img src="<?php echo htmlspecialchars($firstRoom['image'] ?: 'images/room.jpg'); ?>" alt="Room Image">
        </div>

        <div class="room-details-card">
            <h3 class="room-title"><?php echo htmlspecialchars($firstRoom['room_type']); ?></h3>
            <p class="room-meta"><?php echo (int)$firstRoom['capacity']; ?> guests</p>

            <div class="room-list">
                <?php
                $featList = $roomFeatures[(int)$firstRoom['room_id']] ?? [];
                if (!empty($featList)):
                    foreach ($featList as $f):
                ?>
                    <span>✓ <?php echo htmlspecialchars($f); ?></span>
                <?php
                    endforeach;
                else:
                ?>
                    <span>No features listed</span>
                <?php endif; ?>
            </div>

            <div class="room-bottom">
                <span><i class="fa-solid fa-user-group"></i> <?php echo (int)$firstRoom['capacity']; ?></span>
                <span class="room-price"><?php echo number_format((float)$firstRoom['price'], 2); ?>$ / night</span>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (count($rooms) > 1): ?>
    <div id="extra-rooms" class="hidden">
        <?php for ($i = 1; $i < count($rooms); $i++):
            $room = $rooms[$i];
            $featList = $roomFeatures[(int)$room['room_id']] ?? [];
        ?>
        <div class="rooms-row">
            <div class="room-photo-card">
                <img src="<?php echo htmlspecialchars($room['image'] ?: 'images/room.jpg'); ?>" alt="Room Image">
            </div>

            <div class="room-details-card">
                <h3 class="room-title"><?php echo htmlspecialchars($room['room_type']); ?></h3>
                <p class="room-meta"><?php echo (int)$room['capacity']; ?> guests</p>

                <div class="room-list">
                    <?php if (!empty($featList)): ?>
                        <?php foreach ($featList as $f): ?>
                            <span>✓ <?php echo htmlspecialchars($f); ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span>No features listed</span>
                    <?php endif; ?>
                </div>

                <div class="room-bottom">
                    <span><i class="fa-solid fa-user-group"></i> <?php echo (int)$room['capacity']; ?></span>
                    <span class="room-price"><?php echo number_format((float)$room['price'], 2); ?>$ / night</span>
                </div>
            </div>
        </div>
        <?php endfor; ?>
    </div>

    <button id="show-more-rooms" class="rooms-more">Show more rooms</button>
    <?php endif; ?>
</section>

<hr class="section-divider">

<!-- AMENITIES -->
<section id="amenities">
    <div class="section-header">AMENITIES</div>

    <div class="amenities-card">
        <?php foreach ($features as $f): ?>
        <div class="amenity">
            <i class="fa-solid fa-check"></i>
            <span><?php echo htmlspecialchars($f); ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<hr class="section-divider">

<!-- ABOUT -->
<section id="about">
    <div class="section-header">ABOUT</div>

    <div class="about-box">
        <?php echo nl2br(htmlspecialchars($hotel['description'])); ?>
    </div>
</section>

</main>

<?php include 'footer.html'; ?>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("show-more-rooms");
    const extra = document.getElementById("extra-rooms");

    if (btn && extra) {
        btn.addEventListener("click", () => {
            extra.classList.toggle("hidden");
            btn.textContent = extra.classList.contains("hidden")
                ? "Show more rooms"
                : "Show less rooms";
        });
    }
});
</script>

</body>
</html>
