<?php
session_start();


// 1. Connect to DB
$conn = new mysqli('localhost', 'root', '', 'hotel_management_system');


// 2. Handle date filter (today / tomorrow / week)
$filter = isset($_GET['date_filter']) ? $_GET['date_filter'] : 'today';

$dateCondition = "";
$filterLabel = "today";

switch ($filter) {
    case 'tomorrow':
        $dateCondition = "WHERE b.check_in = CURDATE() + INTERVAL 1 DAY";
        $filterLabel = "tomorrow";
        break;
    case 'week':
        $dateCondition = "WHERE b.check_in BETWEEN CURDATE() AND CURDATE() + INTERVAL 6 DAY";
        $filterLabel = "this week";
        break;
    case 'today':
    default:
        $dateCondition = "WHERE b.check_in = CURDATE()";
        $filterLabel = "today";
        $filter = 'today';
        break;
}

// 3. Get reservations (join booking + rooms + hotels + users)
$sql = "
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
        u.first_name,
        u.last_name,
        u.email,
        r.room_nb,
        r.room_type,
        h.hotel_name,
        h.city,
        h.country
    FROM booking b
    JOIN users u ON b.user_id = u.user_id
    JOIN rooms r ON b.room_id = r.room_id
    JOIN hotels h ON r.hotel_id = h.hotel_id
    $dateCondition
    ORDER BY b.check_in, b.created_at DESC
";

$result = $conn->query($sql);
$bookings = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}

// 4. Basic header info (hotel name / city) — use first booking if exists
$hotelName = "Reservations";
$hotelCity = "";
if (!empty($bookings)) {
    $hotelName = $bookings[0]['hotel_name'];
    $hotelCity = $bookings[0]['city'];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>BookEase – Reservations Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome (for icons) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    :root {
      --bg-main: #0f3c4b;
      --bg-card: #11495c;
      --bg-card-dark: #0b2d39;
      --accent: #19b5c6;
      --text-main: #f6f8f9;
      --text-muted: #c3d1d7;
      --border-light: rgba(255, 255, 255, 0.2);
      --btn-bg: #0b2d39;
      --btn-bg-hover: #17647d;
      --radius-xl: 18px;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: "Montserrat", sans-serif;
    }

    body {
      background: var(--bg-main);
      color: var(--text-main);
    }

    a { text-decoration: none; color: inherit; }

    .social-icons i {
      font-size: 22px;
      color: #CBA135;
    }

    /* Move dashboard down below navbar */
    .dashboard {
      max-width: 1000px;
      margin: auto;
      padding: 110px 16px 40px; 
    }

    /* ------------ HEADER ------------ */
    .top-bar {
      display: flex;
      align-items: center;
      gap: 16px;
      margin-bottom: 20px;
    }

    .header-title {
      flex: 1;
      text-align: center;
      font-size: 25px;
      font-weight: 500;
      font-family: "TAN Mon Cheri", serif;
    }

    /* ------------ FILTERS ------------ */
    .filters-row {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 16px;
      font-size: 19px;
    }

    .filters-row label {
      color: var(--text-muted);
    }

    .filters-row select {
      padding: 6px 20px;
      border-radius: 20px;
      background: var(--bg-card-dark);
      border: none;
      color: white;
      cursor: pointer;
    }

    /* ------------ HOTEL CARD ------------ */
    .hotel-card {
      background: var(--bg-card);
      padding: 20px;
      border-radius: var(--radius-xl);
      margin-bottom: 26px;
    }

    .social-icons i:hover {
      background-color: #CBA135;
      color: #2A4E61;
    }

    .hotel-card i {
      color: #CBA135;
      font-size: 15px;
      margin-right: 5px;
    }

    .hotel-card-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 12px;
    }

    .hotel-location {
      color: var(--text-muted);
      font-size: 14px;
      text-align: right;
    }

    .hotel-name {
      font-size: 20px;
      font-weight: 600;
      font-family: 'TAN Mon Cheri', serif;
      font-style: italic;
    }

    .hotel-nav {
      display: flex;
      gap: 16px;
      font-size: 13px;
      margin-bottom: 12px;
    }

    .hotel-nav a:hover,
    .hotel-nav a.active {
      color: var(--accent);
      text-decoration: underline;
    }

    .guest-list {
      list-style: none;
    }

    .guest-list li a {
      text-decoration: underline;
      text-underline-offset: 2px;
      color: white;
    }

    .guest-list li + li {
      margin-top: 6px;
    }

    /* ------------ RESERVATION CARD ------------ */
    .reservation-card {
      display: none;
      background: var(--bg-card);
      padding: 20px;
      border-radius: var(--radius-xl);
      box-shadow: 0 14px 40px rgba(0,0,0,0.3);
      margin-top: 10px;
    }

    .reservation-card:target {
      display: block;
    }

    .reservation-header {
      text-align: center;
      font-size: 16px;
      font-weight: 600;
      margin-bottom: 16px;
      font-family: 'Times New Roman', Times, serif;
    }

    .reservation-list {
      list-style: disc;
      margin-left: 18px;
      margin-bottom: 12px;
    }

    .reservation-section {
      margin-top: 12px;
      border-top: 1px solid var(--border-light);
      padding-top: 12px;
    }

    .reservation-section-title {
      font-weight: 600;
      margin-bottom: 4px;
    }

    .reservation-actions {
      display: flex;
      gap: 10px;
      margin-top: 16px;
    }

    .btn {
      padding: 6px 16px;
      border: 1px solid var(--border-light);
      border-radius: 20px;
      background: var(--btn-bg);
      color: white;
      cursor: pointer;
      text-transform: uppercase;
      font-size: 11px;
    }

    .btn:hover {
      background: var(--btn-bg-hover);
    }

    .no-reservations {
      margin-top: 10px;
      padding: 16px 20px;
      border-radius: var(--radius-xl);
      background: var(--bg-card-dark);
      color: var(--text-muted);
      font-size: 14px;
    }

    @media (max-width: 600px) {
      .hotel-card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
      }

      .hotel-location {
        text-align: left;
      }
    }
  </style>
</head>
<body id="top">

  <?php include 'navbar.html'; ?>

  <!-- ============ DASHBOARD ============ -->
  <main class="dashboard">

    <header class="top-bar">
      <div class="header-title">Reservations Dashboard – Admin View</div>
    </header>

    <!-- Filters (form submits automatically when changed) -->
    <form method="get" class="filters-row">
      <label>Reservations for:</label>
      <select name="date_filter" onchange="this.form.submit()">
        <option value="today"   <?php if ($filter === 'today')   echo 'selected'; ?>>today</option>
        <option value="tomorrow"<?php if ($filter === 'tomorrow')echo 'selected'; ?>>tomorrow</option>
        <option value="week"    <?php if ($filter === 'week')    echo 'selected'; ?>>this week</option>
      </select>
    </form>

    <!-- Hotel Card -->
    <section class="hotel-card">
      <div class="hotel-card-header">
        <div class="hotel-name">
          <?php echo htmlspecialchars($hotelName); ?>
        </div>
        <div class="hotel-location">
          <i class="fa-solid fa-location-dot"></i>
          <?php echo htmlspecialchars($hotelCity ?: ''); ?><br>
          <!-- Phone isn't in DB – keep static or remove -->
          <div class="hotel-nb">
            <i class="fa-solid fa-phone"></i> +961 7378383
          </div>
        </div>
      </div>

      <nav class="hotel-nav">
        <a href="#top" class="active">Reservations – <?php echo htmlspecialchars($filterLabel); ?></a>
        <a href="#">Pending</a>
        <a href="#">Confirmed</a>
        <a href="#">Cancelled</a>
      </nav>

      <?php if (!empty($bookings)): ?>
        <ul class="guest-list">
          <?php foreach ($bookings as $b): 
              $fullName = strtoupper($b['first_name'] . ' ' . $b['last_name']);
              $anchor   = 'booking' . $b['booking_id'];
          ?>
            <li>
              <a href="#<?php echo $anchor; ?>">
                <?php echo htmlspecialchars($fullName); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <div class="no-reservations">
          No reservations found for <?php echo htmlspecialchars($filterLabel); ?>.
        </div>
      <?php endif; ?>
    </section>

    <!-- ============= RESERVATION CARDS ============= -->
    <?php if (!empty($bookings)): ?>
      <?php foreach ($bookings as $b): 
          $anchor = 'booking' . $b['booking_id'];

          // Format some values
          $checkIn   = date('F j, Y', strtotime($b['check_in']));
          $checkOut  = date('F j, Y', strtotime($b['check_out']));
          $createdAt = date('F j, Y • H:i', strtotime($b['created_at']));
          $status    = ucfirst($b['status']);
          $payStatus = ucfirst($b['payment_status']);
          $payMethod = str_replace('_', ' ', $b['payment_method']);
      ?>
        <article id="<?php echo $anchor; ?>" class="reservation-card">
          <div class="reservation-header"><u>RESERVATION DETAILS</u></div>

          <div class="reservation-section-title">Reservation Info</div>
          <ul class="reservation-list">
            <li>Reservation ID: <?php echo $b['booking_id']; ?></li>
            <li>Hotel: <?php echo htmlspecialchars($b['hotel_name']); ?> (<?php echo htmlspecialchars($b['city']); ?>)</li>
            <li>Room: #<?php echo htmlspecialchars($b['room_nb']); ?> – <?php echo htmlspecialchars($b['room_type']); ?></li>
            <li>Check-in: <?php echo $checkIn; ?></li>
            <li>Check-out: <?php echo $checkOut; ?></li>
            <li>Guests: <?php echo (int)$b['guests_no']; ?></li>
            <li>Total Amount: $<?php echo number_format((float)$b['total_amount'], 2); ?></li>
            <li>Status: <?php echo $status; ?> | Payment: <?php echo $payStatus; ?> (<?php echo htmlspecialchars($payMethod); ?>)</li>
            <li>Created at: <?php echo $createdAt; ?></li>
          </ul>

          <div class="reservation-section">
            <div class="reservation-section-title">Guest</div>
            <ul class="reservation-list">
              <li>Name: <?php echo htmlspecialchars($b['first_name'] . ' ' . $b['last_name']); ?></li>
              <li>Email: <?php echo htmlspecialchars($b['email']); ?></li>
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
            <!-- For now these buttons are just UI (no DB update yet) -->
            <button class="btn" type="button">Confirm</button>
            <button class="btn" type="button">Cancel</button>
            <button class="btn" type="button">Pending</button>
          </div>
        </article>
      <?php endforeach; ?>
    <?php endif; ?>

  </main>

  <?php include 'footer.html'; ?>

</body>
</html>
