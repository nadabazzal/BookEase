<?php
session_start();

/* ==========================
   CONNECT TO DATABASE
========================== */
$conn = mysqli_connect('localhost', 'root', '', 'hotel_management_system');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

/* ==========================
   GET DISTINCT CITIES
========================== */
$cities = array();
$sqlCities = "SELECT DISTINCT city FROM hotels WHERE status='approved' ORDER BY city ASC";
$resultCities = mysqli_query($conn, $sqlCities);

if ($resultCities) {
    while ($row = mysqli_fetch_assoc($resultCities)) {
        $cities[] = $row['city'];
    }
}

/* ==========================
   HOTEL FEATURES
========================== */
$hotelFeatures = array();
$sqlHF = "SELECT feature_id, feature_name FROM hotelsfeatures ORDER BY feature_name ASC";
$resultHF = mysqli_query($conn, $sqlHF);

if ($resultHF) {
    while ($row = mysqli_fetch_assoc($resultHF)) {
        $hotelFeatures[] = $row;
    }
}

/* ==========================
   ROOM FEATURES
========================== */
$roomFeatures = array();
$sqlRF = "SELECT featurer_id, featurer_name FROM roomsfeatures ORDER BY featurer_name ASC";
$resultRF = mysqli_query($conn, $sqlRF);

if ($resultRF) {
    while ($row = mysqli_fetch_assoc($resultRF)) {
        $roomFeatures[] = $row;
    }
}

/* ==========================
   FILTER VARIABLES
========================== */
$hotels            = array();
$selected_city     = isset($_POST['city']) ? $_POST['city'] : '';
$selectedHotelFeat = isset($_POST['hotel_features']) ? $_POST['hotel_features'] : array();
$selectedRoomFeat  = isset($_POST['room_features']) ? $_POST['room_features'] : array();
$sort              = isset($_POST['sort']) ? $_POST['sort'] : 'recommended';

/* ==========================
   BUILD QUERY
========================== */
if ($selected_city != '') {

    $city_safe = mysqli_real_escape_string($conn, $selected_city);

    $sql = "
        SELECT 
            h.hotel_id,
            h.hotel_name,
            h.rating,
            h.city,
            h.country,
            h.base_price,
            h.image
        FROM hotels h
        WHERE h.city = '$city_safe'
          AND h.status = 'approved'
    ";

    /* ---- HOTEL FEATURES FILTER ---- */
    if (!empty($selectedHotelFeat)) {
        $hfIds = array_map('intval', $selectedHotelFeat);
        $inHF  = implode(',', $hfIds);

        $sql .= "
          AND h.hotel_id IN (
            SELECT hotel_id
            FROM hotels_features_map
            WHERE feature_id IN ($inHF)
            GROUP BY hotel_id
            HAVING COUNT(DISTINCT feature_id) = " . count($hfIds) . "
          )
        ";
    }

    /* ---- ROOM FEATURES FILTER ---- */
    if (!empty($selectedRoomFeat)) {
        $rfIds = array_map('intval', $selectedRoomFeat);
        $inRF  = implode(',', $rfIds);

        $sql .= "
          AND h.hotel_id IN (
            SELECT r.hotel_id
            FROM rooms r
            JOIN rooms_feature_map rfm ON rfm.room_id = r.room_id
            WHERE rfm.featurer_id IN ($inRF)
            GROUP BY r.hotel_id
            HAVING COUNT(DISTINCT rfm.featurer_id) = " . count($rfIds) . "
          )
        ";
    }

    /* ---- SORTING ---- */
    switch ($sort) {
        case 'price_asc':
            $sql .= " ORDER BY h.base_price ASC";
            break;
        case 'price_desc':
            $sql .= " ORDER BY h.base_price DESC";
            break;
        case 'rating':
            $sql .= " ORDER BY h.rating DESC";
            break;
        default:
            $sql .= " ORDER BY h.rating DESC, h.base_price ASC";
            $sort = 'recommended';
            break;
    }

    $resultHotels = mysqli_query($conn, $sql);
    if ($resultHotels) {
        while ($row = mysqli_fetch_assoc($resultHotels)) {
            $hotels[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>BookEase – Search Hotels</title>

<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:Arial;background:#022432;color:#fff}
.page-wrapper{max-width:1100px;margin:20px auto;background:#024158;border-radius:10px}
.inner-header{text-align:center;padding:25px;background:linear-gradient(#024158,#012c3b)}
.city-search{display:inline-flex;background:#005c8a;border-radius:25px;overflow:hidden}
.city-search select{padding:10px 18px;background:none;border:none;color:#fff}
.city-search option{color:#000}
.city-search button{background:#013a52;border:none;color:#fff;padding:10px 16px}
.content{display:flex;padding:20px;gap:20px}
.filters-column{width:280px}
.filter-box{background:#013549;border-radius:10px;padding:15px;margin-bottom:20px}
.filter-title{text-align:center;margin-bottom:10px}
.toggle-btn{width:100%;padding:8px;border-radius:20px;background:#024d68;border:none;color:#fff}
.pill-list{display:none;flex-direction:column;gap:8px;margin-top:10px}
.pill-btn{background:#b0d7e8;color:#033142;padding:8px;border-radius:20px;cursor:pointer}
.pill-btn input{display:none}
.results-column{flex:1}
.sort-row{display:flex;justify-content:flex-end;margin-bottom:10px}
.sort-select{background:#013549;color:#fff;border-radius:20px;padding:6px}
.hotel-card{display:flex;background:#013549;border-radius:10px;margin-bottom:15px;overflow:hidden}
.hotel-image{width:220px;height:160px;object-fit:cover}
.hotel-info{padding:14px;flex:1}
.more-info-btn{background:#b0d7e8;color:#033142;padding:6px 14px;border-radius:18px;text-decoration:none}
</style>
</head>

<body>

<?php include 'navbar.html'; ?>
<br><br><br>

<div class="page-wrapper">
<div class="inner-header">
<h1>SEARCH BY CITY AND FILTER</h1>

<form method="post" action="search.php">

<div class="city-search">
<select name="city" required>
<option value="">Select city</option>
<?php foreach ($cities as $city): ?>
<option value="<?php echo htmlspecialchars($city); ?>"
<?php if ($city == $selected_city) echo 'selected'; ?>>
<?php echo htmlspecialchars($city); ?>
</option>
<?php endforeach; ?>
</select>
<button type="submit">Search</button>
</div>
</div>

<div class="content">

<div class="filters-column">

<div class="filter-box">
<div class="filter-title">Hotel Features</div>
<button type="button" class="toggle-btn">Choose</button>
<div class="pill-list">
<?php foreach ($hotelFeatures as $hf): ?>
<label class="pill-btn">
<?php echo htmlspecialchars($hf['feature_name']); ?>
<input type="checkbox" name="hotel_features[]"
value="<?php echo (int)$hf['feature_id']; ?>"
<?php if (in_array((string)$hf['feature_id'], array_map('strval',$selectedHotelFeat))) echo 'checked'; ?>>
</label>
<?php endforeach; ?>
</div>
</div>

<div class="filter-box">
<div class="filter-title">Room Features</div>
<button type="button" class="toggle-btn">Choose</button>
<div class="pill-list">
<?php foreach ($roomFeatures as $rf): ?>
<label class="pill-btn">
<?php echo htmlspecialchars($rf['featurer_name']); ?>
<input type="checkbox" name="room_features[]"
value="<?php echo (int)$rf['featurer_id']; ?>"
<?php if (in_array((string)$rf['featurer_id'], array_map('strval',$selectedRoomFeat))) echo 'checked'; ?>>
</label>
<?php endforeach; ?>
</div>
</div>

</div>

<div class="results-column">

<div class="sort-row">
<select name="sort" class="sort-select" onchange="this.form.submit()">
<option value="recommended" <?php if($sort=='recommended')echo'selected';?>>Recommended</option>
<option value="price_asc" <?php if($sort=='price_asc')echo'selected';?>>Price ↑</option>
<option value="price_desc" <?php if($sort=='price_desc')echo'selected';?>>Price ↓</option>
<option value="rating" <?php if($sort=='rating')echo'selected';?>>Rating</option>
</select>
</div>

<?php if ($selected_city == ''): ?>
<p>Please select a city.</p>
<?php elseif (empty($hotels)): ?>
<p>No hotels found.</p>
<?php else: ?>
<?php foreach ($hotels as $hotel): ?>
<div class="hotel-card">
<img src="<?php echo htmlspecialchars($hotel['image'] ?: 'images/hotel.png'); ?>" class="hotel-image">
<div class="hotel-info">
<h3><?php echo htmlspecialchars($hotel['hotel_name']); ?></h3>
<p>From <?php echo number_format($hotel['base_price'],2); ?> $ / night</p>
<p>Rating: <?php echo htmlspecialchars($hotel['rating']); ?></p>
<a href="info.php?hotel_id=<?php echo (int)$hotel['hotel_id']; ?>" class="more-info-btn">
More info
</a>
</div>
</div>
<?php endforeach; ?>
<?php endif; ?>

</div>
</div>

</form>
</div>

<?php include 'footer.html'; ?>

<script>
document.querySelectorAll('.toggle-btn').forEach(btn=>{
  btn.onclick=()=>{ 
    let list=btn.nextElementSibling;
    list.style.display=list.style.display==='flex'?'none':'flex';
  };
});
</script>

</body>
</html>
