<?php
session_start();

/* ===============================
   DATABASE CONNECTION
================================ */
$conn = mysqli_connect("localhost", "root", "", "hotel_management_system");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

/* ===============================
   1) READ hotel_id
================================ */
$hotel_id = 0;

if (isset($_POST['hotel_id'])) {
    $hotel_id = (int)$_POST['hotel_id'];
} elseif (isset($_GET['hotel_id'])) {
    $hotel_id = (int)$_GET['hotel_id'];
} else {
    die("No hotel selected.");
}

if ($hotel_id <= 0) {
    die("Invalid hotel.");
}

/* ===============================
   2) GET HOTEL INFO
================================ */
$sqlHotel = "
    SELECT hotel_id, hotel_name, description, rating,
           country, city, base_price, image
    FROM hotels
    WHERE hotel_id = $hotel_id
      AND status = 'approved'
";
$resultHotel = mysqli_query($conn, $sqlHotel);

if (!$resultHotel || mysqli_num_rows($resultHotel) == 0) {
    die("Hotel not found.");
}

$hotel = mysqli_fetch_assoc($resultHotel);

/* ===============================
   3) ROOM FEATURES PER ROOM
================================ */
$roomFeatures = array();

$sqlRoomFeat = "
    SELECT rfm.room_id, rf.featurer_name
    FROM rooms_feature_map rfm
    JOIN roomsfeatures rf ON rf.featurer_id = rfm.featurer_id
    JOIN rooms r ON r.room_id = rfm.room_id
    WHERE r.hotel_id = $hotel_id
";
$resultRoomFeat = mysqli_query($conn, $sqlRoomFeat);

if ($resultRoomFeat) {
    while ($row = mysqli_fetch_assoc($resultRoomFeat)) {
        $rid = (int)$row['room_id'];
        if (!isset($roomFeatures[$rid])) {
            $roomFeatures[$rid] = array();
        }
        $roomFeatures[$rid][] = $row['featurer_name'];
    }
}

/* ===============================
   4) ROOMS + ONE IMAGE PER ROOM
================================ */
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

$rooms = array();
if ($resultRooms) {
    while ($row = mysqli_fetch_assoc($resultRooms)) {
        $rooms[] = $row;
    }
}

$firstRoom = (count($rooms) > 0) ? $rooms[0] : null;

/* ===============================
   5) HOTEL AMENITIES
================================ */
$sqlFeatures = "
    SELECT hf.feature_name
    FROM hotelsfeatures hf
    JOIN hotels_features_map m ON m.feature_id = hf.feature_id
    WHERE m.hotel_id = $hotel_id
";
$resultFeat = mysqli_query($conn, $sqlFeatures);

$features = array();
if ($resultFeat) {
    while ($row = mysqli_fetch_assoc($resultFeat)) {
        $features[] = $row['feature_name'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo htmlspecialchars($hotel['hotel_name']); ?></title>

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
/* === CSS SAME AS YOUR VERSION (UNCHANGED) === */
:root {
  --bg-main: #214a5c;
  --bg-dark: #153649;
  --bg-card: #1e4e62;
  --accent: #3cb371;
  --text-main: #ffffff;
  --border-line: #3c6275;
}
* { box-sizing: border-box; margin:0; padding:0; }
body { font-family:Montserrat,sans-serif; background:var(--bg-main); color:#fff; }
.hero-section{position:relative;height:420px;margin-top:74px;overflow:hidden;}
.hotel-image{width:100%;height:100%;object-fit:cover;}
.hero-overlay{position:absolute;inset:0;background:linear-gradient(to bottom,rgba(12,36,50,.4),rgba(12,36,50,.85));display:flex;align-items:center;justify-content:center;}
.hero-title{font-size:50px;letter-spacing:2px;}
.tabs{display:flex;justify-content:center;gap:60px;background:#1c4454;padding:12px;font-size:12px;}
main{width:90%;max-width:1100px;margin:35px auto;}
.section-header{padding:10px 26px;border:1px solid var(--border-line);border-radius:25px;display:inline-block;margin-bottom:18px;}
.details-box{background:var(--bg-card);padding:18px;border-radius:18px;}
.rooms-row{background:var(--bg-card);border-radius:18px;padding:14px;display:grid;grid-template-columns:1.1fr 1.1fr;gap:18px;}
.room-photo-card img{width:100%;height:100%;object-fit:cover;}
.room-details-card{background:#205b72;border-radius:14px;padding:18px;}
.room-list{display:grid;grid-template-columns:1fr 1fr;gap:6px;}
.room-price{background:var(--bg-main);padding:6px 14px;border-radius:18px;}
.hidden{display:none;}
.rooms-more{margin-top:10px;padding:7px 16px;border-radius:18px;background:var(--bg-dark);color:#fff;border:none;}
.amenities-card{background:var(--bg-card);border-radius:18px;padding:18px;display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;}
.about-box{background:var(--bg-card);border-radius:18px;padding:20px;}
</style>
</head>

<body>

<?php include 'navbar.html'; ?>

<!-- HERO -->
<section class="hero-section">
<?php
$imagePath = (!empty($hotel['image'])) ? htmlspecialchars($hotel['image']) : 'images/hotel.png';
?>
<img src="<?php echo $imagePath; ?>" class="hotel-image">
<div class="hero-overlay">
  <h1 class="hero-title"><?php echo htmlspecialchars($hotel['hotel_name']); ?></h1>
</div>
</section>

<div class="tabs">
  <a href="#details">Details</a>
  <a href="#rooms">Rooms</a>
  <a href="#amenities">Amenities</a>
  <a href="#about">About</a>
</div>

<main>

<section id="details">
  <div class="section-header">DETAILS</div>
  <div class="details-box">
    📍 <?php echo htmlspecialchars($hotel['city'].", ".$hotel['country']); ?>
    &nbsp; | ⭐ <?php echo htmlspecialchars($hotel['rating']); ?>
  </div>
</section>

<section id="rooms">
  <div class="section-header">ROOMS</div>

<?php if ($firstRoom): ?>
<div class="rooms-row">
  <div class="room-photo-card">
    <img src="<?php echo htmlspecialchars($firstRoom['image'] ?: 'images/room.jpg'); ?>">
  </div>
  <div class="room-details-card">
    <h3><?php echo htmlspecialchars($firstRoom['room_type']); ?></h3>
    <div class="room-list">
<?php
$rid = (int)$firstRoom['room_id'];
if (isset($roomFeatures[$rid])) {
    foreach ($roomFeatures[$rid] as $f) {
        echo "<span>✓ ".htmlspecialchars($f)."</span>";
    }
}
?>
    </div>
    <div class="room-price">
      <?php echo number_format($firstRoom['price'],2); ?> $ / night
    </div>
  </div>
</div>
<?php endif; ?>

</section>

<section id="amenities">
  <div class="section-header">AMENITIES</div>
  <div class="amenities-card">
<?php foreach ($features as $f): ?>
    <div>✓ <?php echo htmlspecialchars($f); ?></div>
<?php endforeach; ?>
  </div>
</section>

<section id="about">
  <div class="section-header">ABOUT</div>
  <div class="about-box">
    <?php echo nl2br(htmlspecialchars($hotel['description'])); ?>
  </div>
</section>

</main>

<?php include 'footer.html'; ?>

</body>
</html>
