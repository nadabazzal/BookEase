<?php
session_start();

// 1) CONNECT TO DATABASE
$conn = mysqli_connect('localhost', 'root', '', 'hotel_management_system');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 2) GET DISTINCT CITIES FOR SELECT
$cities = [];
$sqlCities = "SELECT DISTINCT city FROM hotels WHERE status = 'approved' ORDER BY city ASC";
$resultCities = mysqli_query($conn, $sqlCities);

if ($resultCities && mysqli_num_rows($resultCities) > 0) {
    while ($row = mysqli_fetch_assoc($resultCities)) {
        $cities[] = $row['city'];
    }
}
// HOTEL FEATURES
$hotelFeatures = [];
$sqlHF = "SELECT feature_id, feature_name FROM hotelsfeatures ORDER BY feature_name ASC";
$resultHF = mysqli_query($conn, $sqlHF);
while ($row = mysqli_fetch_assoc($resultHF)) {
    $hotelFeatures[] = $row;
}

// ROOM FEATURES
$roomFeatures = [];
$sqlRF = "SELECT featurer_id, featurer_name FROM roomsfeatures ORDER BY featurer_name ASC";
$resultRF = mysqli_query($conn, $sqlRF);
while ($row = mysqli_fetch_assoc($resultRF)) {
    $roomFeatures[] = $row;
}

$hotels = [];
// 3) HANDLE SELECTED CITY AND LOAD HOTELS
$selected_city      = isset($_GET['city']) ? $_GET['city'] : '';


if (!empty($_POST['city'])) {
    $selected_city = $_POST['city'];
    $city_safe = mysqli_real_escape_string($conn, $selected_city);

    $sqlHotels = "
        SELECT hotel_id, hotel_name, description, rating, city, country, base_price
        FROM hotels
        WHERE city = '$city_safe'
          AND status = 'approved'
       
    ";
//run

    $resultHotels = mysqli_query($conn, $sqlHotels);
    if ($resultHotels && mysqli_num_rows($resultHotels) > 0) {
        while ($row = mysqli_fetch_assoc($resultHotels)) {
            $hotels[] = $row;
        }
    }
    $selectedHotelFeat  = isset($_GET['hotel_features']) ? $_GET['hotel_features'] : [];
if (!empty($selectedHotelFeat)) {
        $hfIds = array_map('intval', $selectedHotelFeat);
        $inHF = implode(',', $hfIds);

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
$selectedRoomFeat   = isset($_GET['room_features']) ? $_GET['room_features'] : [];

    if (!empty($selectedRoomFeat)) {
        $rfIds = array_map('intval', $selectedRoomFeat);
        $inRF = implode(',', $rfIds);

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
$sort= isset($_GET['sort']) ? $_GET['sort'] : 'recommended';

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
        case 'recommended':
        default:
            // Recommended: best rating, then cheaper price
            $sql .= " ORDER BY h.rating DESC, h.base_price ASC";
            $sort = 'recommended';
            break;
    }


}
?>
<!DOCTYPE html> 
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BookEase – Hotel Deals</title>
<style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: Arial, Helvetica, sans-serif;
      background: #022432;
      color: #ffffff;
    }

    a {
      color: inherit;
      text-decoration: none;
    }

    .page-wrapper {
      max-width: 1100px;
      margin: 20px auto;
      background: #024158;
      border-radius: 10px;
      overflow: hidden;
    }

    .inner-header {
      padding: 25px 30px 15px;
      text-align: center;
      background: linear-gradient(to bottom, #024158, #012c3b);
    }

    .inner-header h1 {
      font-size: 18px;
      letter-spacing: 1px;
      margin-bottom: 20px;
    }

    .city-search {
      display: inline-flex;
      align-items: center;
      background: #005c8a;
      border-radius: 25px;
      overflow: hidden;
    }

    .city-search select {
      border: none;
      outline: none;
      padding: 10px 18px;
      background: transparent;
      color: #fff;
      font-size: 14px;
      min-width: 200px;
    }

    .city-search option {
      color: #000;
    }

    .city-search button {
      border: none;
      background: #013a52;
      color: #fff;
      padding: 10px 16px;
      font-size: 14px;
      cursor: pointer;
    }

    .content {
      display: flex;
      padding: 20px 30px 30px;
      gap: 20px;
    }

    .filters-column {
      width: 280px;
      flex-shrink: 0;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }
    
.results-column {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 18px;
}

    .filter-box {
      background: #013549;
      border-radius: 10px;
      padding: 15px 18px;
    }

    .filter-title {
      text-align: center;
      font-size: 16px;
      margin-bottom: 12px;
      border-bottom: 1px solid #0f607e;
      padding-bottom: 6px;
    }

    .toggle-btn {
      width: 100%;
      padding: 8px 10px;
      border-radius: 20px;
      border: 1px solid #0f607e;
      background: #024d68;
      color: #ffffff;
      font-size: 13px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.2s;
      margin-top: 5px;
    }

    .toggle-btn:hover {
      background: #036488;
      transform: translateY(-1px);
    }

    .pill-list {
      display: none;
      flex-direction: column;
      gap: 8px;
      margin-top: 10px;
    }

    .pill-btn {
      width: 100%;
      padding: 8px 10px;
      border-radius: 20px;
      border: none;
      background: #b0d7e8;
      color: #033142;
      font-size: 13px;
      font-weight: bold;
      text-align: center;
      cursor: pointer;
      transition: 0.2s;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
      display: block;
      user-select: none;
    }

    .pill-btn:hover {
      background: #91c3d8;
      transform: translateY(-1px);
    }

    .pill-btn input {
      display: none; /* hide real checkbox */
    }

    .pill-btn.active {
      background: #4db6d3;
      color: #ffffff;
      transform: translateY(-1px);
    }

    .sort-row {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      margin-bottom: 5px;
      font-size: 13px;
      gap: 8px;
    }

    .sort-select {
      background: #013549;
      border-radius: 18px;
      padding: 6px 10px;
      border: 1px solid #0f607e;
      font-size: 12px;
      color: #fff;
      outline: none;
      cursor: pointer;
     
    }

    .hotel-card {
      display: flex;
      background: #013549;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.35);
    }

    .hotel-image {
      width: 220px;
      height: 160px;
      object-fit: cover;
    }

    .hotel-info {
      padding: 14px 16px;
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .hotel-title {
      font-size: 16px;
      margin-bottom: 4px;
    }

    .hotel-price {
      font-size: 12px;
      margin-bottom: 6px;
      opacity: 0.85;
    }

    .hotel-stars {
      font-size: 14px;
      margin-bottom: 10px;
    }

    .hotel-bottom-row {
      display: flex;
      justify-content: flex-start;
      align-items: center;
      gap: 10px;
    }

    .more-info-btn {
      padding: 6px 14px;
      border-radius: 18px;
      border: none;
      background: #b0d7e8;
      color: #033142;
      font-size: 12px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.2s;
      text-decoration: none;
    }

    .more-info-btn:hover {
      background: #91c3d8;
      transform: translateY(-1px);
    }
  </style>
</head>

<body>
  <!-- شريط علوي -->
  <?php include 'navbar.html'; ?>
  <br><br><br><br>

  <!-- البوكس الرئيسي -->
  <div class="page-wrapper">
    <!-- العنوان والبحث عن مدينة -->
    <div class="inner-header">
      <h1>SEARCH BY CITY AND FILTER TO DISCOVER THE BEST HOTEL DEALS</h1>
<form method="post" action="search.php">
          <div class="city-search">
          <select name="city" required>
            <option value="">Select a city</option>
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
    <!-- المحتوى: فلاتر + فنادق -->
   <div class="content">
  <!-- ========== LEFT COLUMN: FILTERS ========== -->
  <div class="filters-column">
    <!-- Hotel Features -->
    <div class="filter-box">
      <div class="filter-title">Hotel Features</div>
      <button type="button" class="toggle-btn" data-label="Choose hotel features">
        Choose hotel features
      </button>

      <div class="pill-list">
        <?php foreach ($hotelFeatures as $hf): ?>
          <label class="pill-btn feature-button">
            <?php echo htmlspecialchars($hf['feature_name']); ?>
            <!-- IMPORTANT: [] to send array -->
            <input
              type="checkbox"
              name="hotel_features[]"
              value="<?php echo (int)$hf['feature_id']; ?>"
              <?php if (in_array($hf['feature_id'], $selectedHotelFeat)) echo 'checked'; ?>
            >
          </label>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Room Features -->
    <div class="filter-box">
      <div class="filter-title">Room Features</div>
      <button type="button" class="toggle-btn" data-label="Choose room features">
        Choose room features
      </button>

      <div class="pill-list">
        <?php foreach ($roomFeatures as $rf): ?>
          <label class="pill-btn feature-button">
            <?php echo htmlspecialchars($rf['featurer_name']); ?>
            <!-- ALSO [] here -->
            <input
              type="checkbox"
              name="room_features[]"
              value="<?php echo (int)$rf['featurer_id']; ?>"
              <?php if (in_array($rf['featurer_id'], $selectedRoomFeat)) echo 'checked'; ?>
            >
          </label>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- ========== RIGHT COLUMN: RESULTS ========== -->
  <div class="results-column">
    <div class="sort-row">
      <span>Sort By:</span>
      <select class="sort-select" name="sort" onchange="this.form.submit()">
        <option value="recommended" <?php if ($sort=='recommended') echo 'selected'; ?>>
          Recommended
        </option>
        <option value="price_asc" <?php if ($sort=='price_asc') echo 'selected'; ?>>
          Price: Low to High
        </option>
        <option value="price_desc" <?php if ($sort=='price_desc') echo 'selected'; ?>>
          Price: High to Low
        </option>
        <option value="rating" <?php if ($sort=='rating') echo 'selected'; ?>>
          Rating
        </option>
      </select>
    </div>

    <?php if ($selected_city === ''): ?>
      <p>Please select a city to see available hotels.</p>
    <?php else: ?>
      <h3>Hotels in "<?php echo htmlspecialchars($selected_city); ?>"</h3><br>

      <?php if (empty($hotels)): ?>
        <p>No hotels found with these filters.</p>
      <?php else: ?>
        <?php foreach ($hotels as $hotel): ?>
          <div class="hotel-card">
            <img
              src="images/hotel.png"
              alt="Hotel"
              class="hotel-image"
            />
            <div class="hotel-info">
              <div>
                <div class="hotel-title">
                  <?php echo htmlspecialchars($hotel['hotel_name']); ?>
                </div>
                <div class="hotel-price">
                  FROM <?php echo number_format($hotel['base_price'], 2); ?>$ /NIGHT
                </div>
                <div class="hotel-stars">
                  Rating: <?php echo htmlspecialchars($hotel['rating']); ?>
                </div>
              </div>

              <div class="hotel-bottom-row">
                <a
                  href="info.php?hotel_id=<?php echo (int)$hotel['hotel_id']; ?>"
                  class="more-info-btn"
                >
                  Show more info
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div> <!-- end .content -->
 </form>
  </div>
  <?php include 'footer.html'; ?>

  <!-- JavaScript بسيط لفتح/إغلاق الليستات -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      document.querySelectorAll(".toggle-btn").forEach(function (btn) {
        var defaultLabel = btn.dataset.label || btn.textContent.trim();
        btn.textContent = defaultLabel;

        btn.addEventListener("click", function () {
          var list = btn.nextElementSibling;
          if (!list) return;

          if (list.style.display === "none" || list.style.display === "") {
            list.style.display = "flex";
            btn.textContent = "Hide " + defaultLabel.toLowerCase();
          } else {
            list.style.display = "none";
            btn.textContent = defaultLabel;
          }
        });
      });
   
    document.querySelectorAll(".feature-button").forEach(function (btn) {
    const checkbox = btn.querySelector("input");
    if (checkbox.checked) btn.classList.add("active");

    btn.addEventListener("click", function () {
      checkbox.checked = !checkbox.checked;
      btn.classList.toggle("active");
    });
  });
});
  </script>
</body>
</html>
