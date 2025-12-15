<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "hotel_management_system");
if (!$conn) {
    die("connection failed: " . mysqli_connect_error());
}

/* ✅ 1) لازم يكون مسجّل دخول */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

/* ✅ 2) تأكد إنه Admin */
$sql = "SELECT role FROM users WHERE user_id = $user_id LIMIT 1";
$res = mysqli_query($conn, $sql);

if (!$res || mysqli_num_rows($res) === 0) {
    // user not found (غريب بس احتياط)
    header("Location: login.php");
    exit();
}

$row = mysqli_fetch_assoc($res);
$role = $row['role'];

/* ✅ إذا مش admin امنعه */
if ($role !== 'admin') {
    header("Location: index.php");  // أو صفحة ثانية بدك ياها
    exit();
}

/* إذا وصل لهون => هو Admin */
$admin_id = $user_id;


/* ===================== MESSAGES ===================== */
$statusError   = "";
$hotelError    = "";
$roomError     = "";
$manageError   = "";

$hotelMessage  = "";
$roomMessage   = "";
$manageMessage = "";



/* ===================== STATE  ===================== */
if (!isset($_SESSION['date_filter'])) {
    $_SESSION['date_filter'] = 'today';
}
if (!isset($_SESSION['status_filter'])) {
    $_SESSION['status_filter'] = 'main'; // main / pending / confirmed / cancelled
}

$filter       = $_SESSION['date_filter'];
$statusFilter = $_SESSION['status_filter'];

$hotelToEdit = null;

/* ===================== POST ACTIONS ===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$action = isset($_POST['action']) ? $_POST['action'] : '';

    /* ---------- SET DATE FILTER (POST) ---------- */
    if ($action === 'set_date_filter') {
        $df = isset($_POST['date_filter']) ? $_POST['date_filter'] : 'today';

       
        $_SESSION['date_filter'] = $df;
    }

    /* ---------- SET STATUS FILTER (POST) ---------- */
    if ($action === 'set_status_filter') {
$sf = isset($_POST['status_filter']) ? $_POST['status_filter'] : 'main';
       
        $_SESSION['status_filter'] = $sf;
    }

    /* ---------- UPDATE BOOKING STATUS ---------- */
    if ($action === 'update_status' && isset($_POST['booking_id'], $_POST['new_status'])) {
        $booking_id = (int)$_POST['booking_id'];
        $new_status = $_POST['new_status'];


       if ($booking_id > 0) {

    $ns = mysqli_real_escape_string($conn, $new_status);

    $sql = "UPDATE booking SET status = '$ns' WHERE booking_id = $booking_id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $_SESSION['status_filter'] = $new_status;
    } else {
        $statusError = "Error updating booking: " . mysqli_error($conn);
    }

} else {
    $statusError = "Invalid booking or status.";
}

    }

    /* ---------- ADD HOTEL ---------- */
    if ($action === 'add_hotel') {
        $hotel_name   = isset($_POST['hotel_name']) ? trim($_POST['hotel_name']) : '';
$description  = isset($_POST['description']) ? trim($_POST['description']) : '';
$rating       = (isset($_POST['rating']) && $_POST['rating'] !== '') ? (float)$_POST['rating'] : 0;
$country      = isset($_POST['country']) ? trim($_POST['country']) : '';
$city         = isset($_POST['city']) ? trim($_POST['city']) : '';
$base_price   = (isset($_POST['base_price']) && $_POST['base_price'] !== '') ? (float)$_POST['base_price'] : 0;
$hotel_status = isset($_POST['hotel_status']) ? $_POST['hotel_status'] : 'pending';


        if (!in_array($hotel_status, ['pending','approved','rejected'], true)) {
            $hotel_status = 'pending';
        }

       // upload image
$imagePath = '';

if (isset($_FILES['hotel_image']) && $_FILES['hotel_image']['error'] == 0) {

    if (!is_dir('images')) {
        mkdir('images');
    }

    $imagePath = 'images/' . time() . '_' . $_FILES['hotel_image']['name'];

    if (!move_uploaded_file($_FILES['hotel_image']['tmp_name'], $imagePath)) {
        $hotelError = "Error uploading hotel image.";
        $imagePath = '';
    }
}


        if ($hotel_name === '' || $country === '' || $city === '') {
            $hotelError = "Please fill hotel name, country and city.";
        }

        if ($hotelError === "") {

    $hn = mysqli_real_escape_string($conn, $hotel_name);
    $ds = mysqli_real_escape_string($conn, $description);
    $ct = mysqli_real_escape_string($conn, $country);
    $cy = mysqli_real_escape_string($conn, $city);
    $hs = mysqli_real_escape_string($conn, $hotel_status);

    if ($imagePath != '') {
        $imgSql = "'" . mysqli_real_escape_string($conn, $imagePath) . "'";
    } else {
        $imgSql = "NULL";
    }

    $sql = "
        INSERT INTO hotels
        (hotel_name, description, rating, created_at, country, city, base_price, status, image)
        VALUES
        ('$hn', '$ds', $rating,  NOW(), '$ct', '$cy', $base_price, '$hs', $imgSql)
    ";

    if (mysqli_query($conn, $sql)) {
        $hotelMessage = "New hotel added successfully ✅";
    } else {
        $hotelError = "Error inserting hotel: " . mysqli_error($conn);
    }
}

    }

    /* ---------- ADD ROOM ---------- */
    if ($action === 'add_room') {
       $hotel_id  = isset($_POST['hotel_id']) ? (int)$_POST['hotel_id'] : 0;
$room_nb   = isset($_POST['room_nb']) ? (int)$_POST['room_nb'] : 0;
$room_type = isset($_POST['room_type']) ? trim($_POST['room_type']) : '';
$price     = (isset($_POST['price']) && $_POST['price'] !== '') ? (float)$_POST['price'] : 0;
$capacity  = isset($_POST['capacity']) ? (int)$_POST['capacity'] : 1;
$status    = isset($_POST['room_status']) ? $_POST['room_status'] : 'available';


        if (!in_array($status, ['available','booked'], true)) {
            $status = 'available';
        }

        if ($hotel_id <= 0 || $room_nb <= 0 || $room_type === '') {
            $roomError = "Please choose a hotel and fill room number & type.";
        } else {
           $rt = mysqli_real_escape_string($conn, $room_type);
$st = mysqli_real_escape_string($conn, $status);


           $sql = "
    INSERT INTO rooms (hotel_id, price, capacity, status, room_nb, room_type)
    VALUES ($hotel_id, $price, $capacity, '$st', $room_nb, '$rt')
";

$result = mysqli_query($conn, $sql);

if ($result) {
    $roomMessage = "New room added successfully ✅";
} else {
    $roomError = "Error inserting room: " . mysqli_error($conn);
}

        }
    }

    /* ---------- LOAD HOTEL TO EDIT ---------- */
    if ($action === 'load_hotel') {
$hid = isset($_POST['hotel_id']) ? (int)$_POST['hotel_id'] : 0;

        if ($hid <= 0) {
            $manageError = "Please choose a hotel to load.";
        } else {    $sql    = "SELECT * FROM hotels WHERE hotel_id = $hid";

              $res = mysqli_query($conn, $sql);
if ($res && mysqli_num_rows($res) === 1) {

        $hotelToEdit = mysqli_fetch_assoc($res);
            
            } else {
                $manageError = "Hotel not found.";
            }
        }
    }

    /* ---------- UPDATE HOTEL ---------- */
    if ($action === 'update_hotel') {
       $hid = isset($_POST['hotel_id']) ? (int)$_POST['hotel_id'] : 0;

$hotel_name   = isset($_POST['hotel_name']) ? trim($_POST['hotel_name']) : '';
$description  = isset($_POST['description']) ? trim($_POST['description']) : '';
$rating       = (isset($_POST['rating']) && $_POST['rating'] !== '') ? (float)$_POST['rating'] : 0;
$country      = isset($_POST['country']) ? trim($_POST['country']) : '';
$city         = isset($_POST['city']) ? trim($_POST['city']) : '';
$base_price   = (isset($_POST['base_price']) && $_POST['base_price'] !== '') ? (float)$_POST['base_price'] : 0;
$hotel_status = isset($_POST['hotel_status']) ? $_POST['hotel_status'] : 'pending';


        if (!in_array($hotel_status, ['pending','approved','rejected'], true)) {
            $hotel_status = 'pending';
        }

        if ($hid <= 0) {
            $manageError = "Invalid hotel.";
        } elseif ($hotel_name === '' || $country === '' || $city === '') {
            $manageError = "Please fill hotel name, country and city.";
        } else {
            $hn = mysqli_real_escape_string($conn, $hotel_name);
$ds = mysqli_real_escape_string($conn, $description);
$ct = mysqli_real_escape_string($conn, $country);
$cy = mysqli_real_escape_string($conn, $city);
$hs = mysqli_real_escape_string($conn, $hotel_status);


            $sql = "
                UPDATE hotels
                SET hotel_name='$hn',
                    description='$ds',
                    rating=$rating,
                    country='$ct',
                    city='$cy',
                    base_price=$base_price,
                    status='$hs'
                WHERE hotel_id=$hid
            ";

            $result = mysqli_query($conn, $sql);

if ($result) {
    $manageMessage = "Hotel updated successfully ✅";
} else {
    $manageError = "Error updating hotel: " . mysqli_error($conn);
}

        }
    }

    /* ---------- DELETE HOTEL (with children) ---------- */
    if ($action === 'delete_hotel') {
$hid = isset($_POST['hotel_id']) ? (int)$_POST['hotel_id'] : 0;

        if ($hid <= 0) {
            $manageError = "Invalid hotel to delete.";
        } else {


    

                // rooms ids for this hotel
                $roomIds = [];
                 $sql    = "SELECT room_id FROM rooms WHERE hotel_id = $hid";
    $res = mysqli_query($conn, $sql);
                if ($res) {
                    while ($row = mysqli_fetch_assoc($res)) {
                        $roomIds[] = (int)$row['room_id'];
                    }
                }

                if (!empty($roomIds)) {
                    $in = implode(',', $roomIds);

                    // delete bookings
                    $sql1    = "DELETE FROM booking WHERE room_id IN ($in)";
    $res1 = mysqli_query($conn, $sql1);

                    // optional tables if exist
                   $sql2 = "SHOW TABLES LIKE 'room_images'";
$t = mysqli_query($conn, $sql2);

if ($t && mysqli_num_rows($t) > 0) {
    $sql2 = "DELETE FROM room_images WHERE room_id IN ($in)";
    mysqli_query($conn, $sql2);
}
$sql3 = "SHOW TABLES LIKE 'rooms_feature_map'";
$t = mysqli_query($conn, $sql3);

if ($t && mysqli_num_rows($t) > 0) {
    $sql4 = "DELETE FROM rooms_feature_map WHERE room_id IN ($in)";
    mysqli_query($conn, $sql4);
}


                    // delete rooms
$sql5 = "DELETE FROM rooms WHERE hotel_id = $hid";
$t = mysqli_query($conn, $sql5);
                }

                // optional favorites
$sql6 = "SHOW TABLES LIKE 'favorites'";
$t = mysqli_query($conn, $sql6);

if ($t && mysqli_num_rows($t) > 0) {
    $sql7 = "DELETE FROM favorites WHERE hotel_id = $hid";
    mysqli_query($conn, $sql7);
}


                // delete hotel
$sql8 = "DELETE FROM hotels WHERE hotel_id = $hid";
$t = mysqli_query($conn, $sql8);

                if ($t) {
                    $manageMessage = "Hotel and all associated data deleted successfully ✅";
                } else {
                    $manageError = "Error deleting hotel: " . mysqli_error($conn);
                }


         
        }
    }
}

$whereParts = array();
$filterLabel = "today";

if ($filter == 'tomorrow') {

    $whereParts[] = "b.check_in = CURDATE() + INTERVAL 1 DAY";
    $filterLabel = "tomorrow";

} elseif ($filter == 'week') {

    $whereParts[] = "b.check_in BETWEEN CURDATE() AND CURDATE() + INTERVAL 6 DAY";
    $filterLabel = "this week";

} else {
    // today (default)
    $whereParts[] = "b.check_in = CURDATE()";
    $filterLabel = "today";
    $_SESSION['date_filter'] = "today";
    $filter = "today";
}

if ($statusFilter == 'pending') {

    $whereParts[] = "b.status = 'pending'";

} elseif ($statusFilter == 'confirmed') {

    $whereParts[] = "b.status = 'confirmed'";

} elseif ($statusFilter == 'cancelled') {

    $whereParts[] = "b.status = 'cancelled'";

} else {
    // main (default)
    $_SESSION['status_filter'] = 'main';
    $statusFilter = 'main';
    $whereParts[] = "b.status <> 'pending'";
}


$whereSql = "WHERE " . implode(" AND ", $whereParts);

$sqlBookings = "
    SELECT
        b.booking_id,
        b.user_id,
        b.room_id,
        b.check_in,
        b.check_out,
        b.created_at,
        b.status,
        b.guests_no,
        b.total_amount,
        b.payment_method,
        b.payment_status,

        COALESCE(b.guest_first_name, u.first_name) AS guest_first_name,
        COALESCE(b.guest_last_name,  u.last_name)  AS guest_last_name,
        COALESCE(b.guest_email,      u.email)      AS guest_email,

        r.room_nb,
        r.room_type,
        h.hotel_name,
        h.city,
        h.country
    FROM booking b
    JOIN users  u ON b.user_id = u.user_id
    JOIN rooms  r ON b.room_id = r.room_id
    JOIN hotels h ON r.hotel_id = h.hotel_id
    $whereSql
    ORDER BY b.check_in, b.created_at DESC
";

$bookings = array();
$res = mysqli_query($conn, $sqlBookings);

if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $bookings[] = $row;
    }
}


/* ===================== HEADER HOTEL INFO ===================== */
$hotelName = "Reservations";
$hotelCity = "";
if (!empty($bookings)) {
    $hotelName = $bookings[0]['hotel_name'];
    $hotelCity = $bookings[0]['city'];
}

/* ===================== HOTELS LIST for forms ===================== */
$hotelsList = array();

$sql = "SELECT hotel_id, hotel_name, city, country FROM hotels ORDER BY hotel_name";
$resHotels = mysqli_query($conn, $sql);

if ($resHotels) {
    while ($row = mysqli_fetch_assoc($resHotels)) {
        $hotelsList[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>BookEase – Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

  <style>
    :root{
      --bg-main:#0f3c4b; --bg-card:#11495c; --bg-card-dark:#0b2d39;
      --accent:#19b5c6; --text-main:#f6f8f9; --text-muted:#c3d1d7;
      --border-light:rgba(255,255,255,0.2);
      --btn-bg:#0b2d39; --btn-bg-hover:#17647d; --radius-xl:18px;
    }
    *{box-sizing:border-box;margin:0;padding:0;font-family:"Montserrat",sans-serif;}
    body{background:var(--bg-main);color:var(--text-main);}
    a{text-decoration:none;color:inherit;}
    .dashboard{max-width:1100px;margin:auto;padding:110px 16px 40px;}

    .top-bar{display:flex;align-items:center;gap:16px;margin-bottom:20px;}
    .header-title{flex:1;text-align:center;font-size:25px;font-weight:500;}

    .filters-row{display:flex;align-items:center;gap:10px;margin-bottom:16px;font-size:18px;flex-wrap:wrap;}
    .filters-row label{color:var(--text-muted);}
    .filters-row select{
      padding:6px 20px;border-radius:20px;background:var(--bg-card-dark);
      border:none;color:white;cursor:pointer;
    }

    .hotel-card{background:var(--bg-card);padding:20px;border-radius:var(--radius-xl);margin-bottom:26px;}
    .hotel-card i{color:#CBA135;font-size:15px;margin-right:5px;}
    .hotel-card-header{display:flex;justify-content:space-between;margin-bottom:12px;}
    .hotel-location{color:var(--text-muted);font-size:14px;text-align:right;}
    .hotel-name{font-size:20px;font-weight:600;font-style:italic;}

    .hotel-nav{display:flex;gap:16px;font-size:13px;margin-bottom:12px;flex-wrap:wrap;align-items:center;}
    .hotel-nav form{display:inline;}
    .hotel-nav button{
      padding-bottom:2px;background:none;border:none;color:inherit;cursor:pointer;
      font-size:13px;
    }
    .hotel-nav button.active{color:var(--accent);text-decoration:underline;}

    .guest-list{list-style:none;}
    .guest-list li+li{margin-top:6px;}
    .guest-list li{color:white;font-weight:500;}

    .reservation-card{background:var(--bg-card);padding:20px;border-radius:var(--radius-xl);
      box-shadow:0 14px 40px rgba(0,0,0,0.3);margin-top:10px;}
    .reservation-header{text-align:center;font-size:16px;font-weight:600;margin-bottom:16px;}
    .reservation-list{list-style:disc;margin-left:18px;margin-bottom:12px;}
    .reservation-section{margin-top:12px;border-top:1px solid var(--border-light);padding-top:12px;}
    .reservation-section-title{font-weight:600;margin-bottom:4px;}
    .reservation-actions{display:flex;gap:10px;margin-top:16px;flex-wrap:wrap;}
    .btn{
      padding:6px 16px;border:1px solid var(--border-light);border-radius:20px;
      background:var(--btn-bg);color:white;cursor:pointer;text-transform:uppercase;font-size:11px;
    }
    .btn:hover{background:var(--btn-bg-hover);}

    .no-reservations{
      margin-top:10px;padding:16px 20px;border-radius:var(--radius-xl);
      background:var(--bg-card-dark);color:var(--text-muted);font-size:14px;
    }

    .alert{padding:10px 14px;border-radius:16px;margin-bottom:10px;font-size:13px;}
    .alert-success{background:#1c7c57;}
    .alert-error{background:#8b1f2b;}

    .admin-section-card{background:var(--bg-card-dark);padding:22px;border-radius:var(--radius-xl);margin-top:40px;}
    .admin-section-title{font-size:20px;font-weight:600;margin-bottom:12px;}
    .admin-row{display:flex;gap:12px;flex-wrap:wrap;}
    .admin-group{flex:1 1 220px;margin-bottom:12px;}
    .admin-group label{display:block;font-size:14px;margin-bottom:4px;color:var(--text-muted);}
    .admin-group input, .admin-group textarea, .admin-group select{
      width:100%;padding:8px 10px;border-radius:10px;border:none;outline:none;
      font-size:14px;background:#0c3041;color:#fff;
    }
    .admin-group textarea{min-height:70px;resize:vertical;}
    .admin-check{margin-bottom:12px;font-size:14px;}
    .admin-check input{margin-right:6px;}

    .btn-primary-admin{
      margin-top:10px;padding:8px 20px;border-radius:20px;border:none;background:var(--accent);
      color:#fff;font-weight:600;cursor:pointer;font-size:14px;
    }
    .btn-primary-admin:hover{background:#22c0d2;}

    @media (max-width:600px){
      .hotel-card-header{flex-direction:column;align-items:flex-start;gap:8px;}
      .hotel-location{text-align:left;}
    }
  </style>
</head>
<body>

<?php include 'navbar.html'; ?>

<main class="dashboard">

  <header class="top-bar">
    <div class="header-title">Reservations Dashboard – Admin View</div>
  </header>

  <?php if ($statusError): ?><div class="alert alert-error"><?php echo htmlspecialchars($statusError); ?></div><?php endif; ?>
  <?php if ($hotelError):  ?><div class="alert alert-error"><?php echo htmlspecialchars($hotelError); ?></div><?php endif; ?>
  <?php if ($roomError):   ?><div class="alert alert-error"><?php echo htmlspecialchars($roomError); ?></div><?php endif; ?>
  <?php if ($manageError): ?><div class="alert alert-error"><?php echo htmlspecialchars($manageError); ?></div><?php endif; ?>

  <?php if ($hotelMessage):  ?><div class="alert alert-success"><?php echo htmlspecialchars($hotelMessage); ?></div><?php endif; ?>
  <?php if ($roomMessage):   ?><div class="alert alert-success"><?php echo htmlspecialchars($roomMessage); ?></div><?php endif; ?>
  <?php if ($manageMessage): ?><div class="alert alert-success"><?php echo htmlspecialchars($manageMessage); ?></div><?php endif; ?>

  <!-- Date filter (POST ONLY) -->
  <form method="post" class="filters-row">
    <input type="hidden" name="action" value="set_date_filter">
    <label>Reservations for:</label>
    <select name="date_filter" onchange="this.form.submit()">
      <option value="today"    <?php if ($filter === 'today') echo 'selected'; ?>>today</option>
      <option value="tomorrow" <?php if ($filter === 'tomorrow') echo 'selected'; ?>>tomorrow</option>
      <option value="week"     <?php if ($filter === 'week') echo 'selected'; ?>>this week</option>
    </select>
  </form>

  <!-- Reservations card  -->
  <section class="hotel-card">

    <nav class="hotel-nav">
      <form method="post">
        <input type="hidden" name="action" value="set_status_filter">
        <input type="hidden" name="status_filter" value="main">
        <button type="submit" class="<?php echo ($statusFilter==='main')?'active':''; ?>">
          Reservations – <?php echo htmlspecialchars($filterLabel); ?>
        </button>
      </form>

      <form method="post">
        <input type="hidden" name="action" value="set_status_filter">
        <input type="hidden" name="status_filter" value="pending">
        <button type="submit" class="<?php echo ($statusFilter==='pending')?'active':''; ?>">Pending</button>
      </form>

      <form method="post">
        <input type="hidden" name="action" value="set_status_filter">
        <input type="hidden" name="status_filter" value="confirmed">
        <button type="submit" class="<?php echo ($statusFilter==='confirmed')?'active':''; ?>">Confirmed</button>
      </form>

      <form method="post">
        <input type="hidden" name="action" value="set_status_filter">
        <input type="hidden" name="status_filter" value="cancelled">
        <button type="submit" class="<?php echo ($statusFilter==='cancelled')?'active':''; ?>">Cancelled</button>
      </form>
    </nav>

    <?php if (!empty($bookings)): ?>
      <ul class="guest-list">
        <?php foreach ($bookings as $b): ?>
<li><?php echo strtoupper(htmlspecialchars(trim($b['guest_first_name'].' '.$b['guest_last_name']))); ?></li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <div class="no-reservations">
        No reservations found for <?php echo htmlspecialchars($filterLabel); ?> in this section.
      </div>
    <?php endif; ?>
  </section>

  <!-- Booking cards -->
  <?php if (!empty($bookings)): ?>
    <?php foreach ($bookings as $b): ?>
      <?php
        $checkIn   = date('F j, Y', strtotime($b['check_in']));
        $checkOut  = date('F j, Y', strtotime($b['check_out']));
        $createdAt = date('F j, Y • H:i', strtotime($b['created_at']));
        $status    = ucfirst($b['status']);
        $payStatus = ucfirst($b['payment_status']);
        $payMethod = str_replace('_',' ', $b['payment_method']);
      ?>
      <article class="reservation-card">
        <div class="reservation-header"><u>RESERVATION DETAILS</u></div>

        <div class="reservation-section-title">Reservation Info</div>
        <ul class="reservation-list">
          <li>Hotel: <?php echo htmlspecialchars($b['hotel_name']); ?> (<?php echo htmlspecialchars($b['city']); ?>)</li>
          <li>Room number: <?php echo htmlspecialchars($b['room_nb']); ?> – <?php echo htmlspecialchars($b['room_type']); ?></li>
          <li>Check-in: <?php echo $checkIn; ?></li>
          <li>Check-out: <?php echo $checkOut; ?></li>
          <li>Guests: <?php echo (int)$b['guests_no']; ?></li>
          <li>Total Amount: $<?php echo number_format((float)$b['total_amount'], 2); ?></li>
          <li>Status: <?php echo htmlspecialchars($status); ?> | Payment: <?php echo htmlspecialchars($payStatus); ?> (<?php echo htmlspecialchars($payMethod); ?>)</li>
          <li>Created at: <?php echo $createdAt; ?></li>
        </ul>

        <div class="reservation-section">
          <div class="reservation-section-title">Guest</div>
          <ul class="reservation-list">
            <li>Name: <?php echo htmlspecialchars(trim($b['guest_first_name'].' '.$b['guest_last_name'])); ?></li>
            <li>Email: <?php echo htmlspecialchars($b['guest_email']); ?></li>
          </ul>
        </div>

        <div class="reservation-section">
          <div class="reservation-section-title">Stay Details</div>
          <ul class="reservation-list">
            <li>Country: <?php echo htmlspecialchars($b['country']); ?></li>
            <li>City: <?php echo htmlspecialchars($b['city']); ?></li>
          </ul>
        </div>

        <div class="reservation-actions">
          <form method="post">
            <input type="hidden" name="action" value="update_status">
            <input type="hidden" name="booking_id" value="<?php echo (int)$b['booking_id']; ?>">
            <input type="hidden" name="new_status" value="confirmed">
            <button class="btn" type="submit">Confirm</button>
          </form>

          <form method="post">
            <input type="hidden" name="action" value="update_status">
            <input type="hidden" name="booking_id" value="<?php echo (int)$b['booking_id']; ?>">
            <input type="hidden" name="new_status" value="cancelled">
            <button class="btn" type="submit">Cancel</button>
          </form>

          <form method="post">
            <input type="hidden" name="action" value="update_status">
            <input type="hidden" name="booking_id" value="<?php echo (int)$b['booking_id']; ?>">
            <input type="hidden" name="new_status" value="pending">
            <button class="btn" type="submit">Pending</button>
          </form>
        </div>
      </article>
    <?php endforeach; ?>
  <?php endif; ?>

  <!-- ================== ADD NEW HOTEL ================== -->
  <section class="admin-section-card">
    <div class="admin-section-title">Add a New Hotel</div>

    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value="add_hotel">

      <div class="admin-row">
        <div class="admin-group">
          <label>Hotel Name *</label>
          <input type="text" name="hotel_name" required>
        </div>
        <div class="admin-group">
          <label>City *</label>
          <input type="text" name="city" required>
        </div>
        <div class="admin-group">
          <label>Country *</label>
          <input type="text" name="country" required>
        </div>
      </div>

      <div class="admin-row">
        <div class="admin-group">
          <label>Base Price</label>
          <input type="number" name="base_price" step="0.01" min="0">
        </div>
        <div class="admin-group">
          <label>Rating (0-5)</label>
          <input type="number" name="rating" step="0.1" min="0" max="5">
        </div>
        <div class="admin-group">
          <label>Status</label>
          <select name="hotel_status">
            <option value="pending">pending</option>
            <option value="approved" selected>approved</option>
            <option value="rejected">rejected</option>
          </select>
        </div>
      </div>

      <div class="admin-group">
        <label>Description</label>
        <textarea name="description" placeholder="Short description"></textarea>
      </div>


      <div class="admin-group">
        <label>Hotel Main Image</label>
        <input type="file" name="hotel_image" accept="image/*">
      </div>

      <button type="submit" class="btn-primary-admin">Add Hotel</button>
    </form>
  </section>

  <!-- ================== ADD NEW ROOM ================== -->
  <section class="admin-section-card">
    <div class="admin-section-title">Add a New Room</div>

    <form method="post">
      <input type="hidden" name="action" value="add_room">

      <div class="admin-row">
        <div class="admin-group">
          <label>Hotel *</label>
          <select name="hotel_id" required>
            <option value="">-- Select Hotel --</option>
            <?php foreach ($hotelsList as $h): ?>
              <option value="<?php echo (int)$h['hotel_id']; ?>">
                <?php echo htmlspecialchars($h['hotel_name'] . ' (' . $h['city'] . ')'); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="admin-group">
          <label>Room Number *</label>
          <input type="number" name="room_nb" min="1" required>
        </div>

        <div class="admin-group">
          <label>Room Type *</label>
          <input type="text" name="room_type" required>
        </div>
      </div>

      <div class="admin-row">
        <div class="admin-group">
          <label>Price</label>
          <input type="number" name="price" step="0.01" min="0">
        </div>
        <div class="admin-group">
          <label>Capacity</label>
          <input type="number" name="capacity" min="1" value="1">
        </div>
        <div class="admin-group">
          <label>Status</label>
          <select name="room_status">
            <option value="available" selected>available</option>
            <option value="booked">booked</option>
          </select>
        </div>
      </div>

      <button type="submit" class="btn-primary-admin">Add Room</button>
    </form>
  </section>

  <!-- ================== MANAGE HOTELS ================== -->
  <section class="admin-section-card">
    <div class="admin-section-title">Manage Hotels (Edit / Delete)</div>

    <!-- Load hotel -->
    <form method="post" style="margin-bottom:16px;">
      <input type="hidden" name="action" value="load_hotel">
      <div class="admin-row">
        <div class="admin-group">
          <label>Choose a Hotel</label>
          <select name="hotel_id">
            <option value="">-- Select Hotel --</option>
            <?php foreach ($hotelsList as $h): ?>
              <option value="<?php echo (int)$h['hotel_id']; ?>">
                <?php echo htmlspecialchars($h['hotel_name'] . ' (' . $h['city'] . ', ' . $h['country'] . ')'); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <button type="submit" class="btn-primary-admin">Load Hotel</button>
    </form>

    <?php if ($hotelToEdit): ?>
      <form method="post">
        <input type="hidden" name="hotel_id" value="<?php echo (int)$hotelToEdit['hotel_id']; ?>">

        <div class="admin-row">
          <div class="admin-group">
            <label>Hotel Name *</label>
            <input type="text" name="hotel_name" required value="<?php echo htmlspecialchars($hotelToEdit['hotel_name']); ?>">
          </div>
          <div class="admin-group">
            <label>City *</label>
            <input type="text" name="city" required value="<?php echo htmlspecialchars($hotelToEdit['city']); ?>">
          </div>
          <div class="admin-group">
            <label>Country *</label>
            <input type="text" name="country" required value="<?php echo htmlspecialchars($hotelToEdit['country']); ?>">
          </div>
        </div>

        <div class="admin-row">
          <div class="admin-group">
            <label>Base Price</label>
            <input type="number" name="base_price" step="0.01" min="0" value="<?php echo htmlspecialchars($hotelToEdit['base_price']); ?>">
          </div>
          <div class="admin-group">
            <label>Rating</label>
            <input type="number" name="rating" step="0.1" min="0" max="5" value="<?php echo htmlspecialchars($hotelToEdit['rating']); ?>">
          </div>
          <div class="admin-group">
            <label>Status</label>
            <select name="hotel_status">
              <option value="pending"  <?php if ($hotelToEdit['status']==='pending') echo 'selected'; ?>>pending</option>
              <option value="approved" <?php if ($hotelToEdit['status']==='approved') echo 'selected'; ?>>approved</option>
              <option value="rejected" <?php if ($hotelToEdit['status']==='rejected') echo 'selected'; ?>>rejected</option>
            </select>
          </div>
        </div>

        <div class="admin-group">
          <label>Description</label>
          <textarea name="description"><?php echo htmlspecialchars($hotelToEdit['description']); ?></textarea>
        </div>

       

        <div class="admin-row">
          <div class="admin-group">
            <button type="submit" name="action" value="update_hotel" class="btn-primary-admin">Update Hotel</button>
          </div>
          <div class="admin-group">
            <button type="submit" name="action" value="delete_hotel" class="btn-primary-admin"
              onclick="return confirm('Are you sure you want to delete this hotel?');"
              style="background:#b3343a;">Delete Hotel</button>
          </div>
        </div>
      </form>
    <?php endif; ?>
  </section>

</main>

<?php include 'footer.html'; ?>
</body>
</html>
