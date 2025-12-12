<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// 1) اتصال بالـ DB
$conn = new mysqli('localhost', 'root', '', 'hotel_management_system');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2) تحديد room_id (من GET أول مرة، أو من POST بعد ما ينعمل submit)
$room_id = 0;
if (isset($_GET['room_id'])) {
    $room_id = (int) $_GET['room_id'];
} elseif (isset($_POST['room_id'])) {
    $room_id = (int) $_POST['room_id'];
}

if ($room_id <= 0) {
    die("No room selected.");
}

// 3) جلب معلومات الغرفة + الفندق
$sql = "
    SELECT r.room_id, r.price, r.capacity, r.room_type, h.hotel_name
    FROM rooms r
    JOIN hotels h ON r.hotel_id = h.hotel_id
    WHERE r.room_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();
$stmt->close();

if (!$room) {
    $conn->close();
    die("Room not found.");
}

// متغيّرات لرسالة نجاح/خطأ
$success_msg = "";
$error_msg   = "";

// 4) إذا الفورم انبعت (POST) → نعمل INSERT بجدول booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // user_id من السيشن (عدّليه حسب نظامك)
    $user_id = $_SESSION['user_id'] ?? 1; // مؤقتاً 1 إذا ما عندك login جاهز

    $checkin        = $_POST['checkin'] ?? null;
    $checkout       = $_POST['checkout'] ?? null;
    $guests         = isset($_POST['guests']) ? (int)$_POST['guests'] : 0;
    $first_name     = trim($_POST['first_name'] ?? '');
    $last_name      = trim($_POST['last_name'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $payment_method = $_POST['payment_method'] ?? null;

    // تحقق بسيط
    if (
        empty($checkin) || empty($checkout) ||
        empty($guests)  || empty($first_name) ||
        empty($last_name) || empty($email) ||
        empty($payment_method)
    ) {
        $error_msg = "Please fill in all required fields.";
    } else {

        $checkin_ts  = strtotime($checkin);
        $checkout_ts = strtotime($checkout);

        if ($checkin_ts === false || $checkout_ts === false || $checkout_ts <= $checkin_ts) {
            $error_msg = "Invalid dates: check-out must be after check-in.";
        } else {
            // حساب عدد الليالي والسعر
            $price_per_night = (float)$room['price'];
            $nights = (int) round(($checkout_ts - $checkin_ts) / 86400);
            if ($nights < 1) $nights = 1;

            $total_amount = $price_per_night * $nights;

            // قيم ابتدائية
            $status         = 'pending';
            $payment_status = 'pending';
            if ($payment_method === 'credit_card') {
                $payment_status = 'paid';
            }

            $created_at = date('Y-m-d H:i:s');

            $sql_insert = "INSERT INTO booking
                (user_id, room_id, check_in, check_out, created_at, status, guests_no, total_amount, payment_method, payment_status,
                 guest_first_name, guest_last_name, guest_email)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt_ins = $conn->prepare($sql_insert);
            if (!$stmt_ins) {
                $error_msg = "Prepare failed: " . $conn->error;
            } else {

                // ✅ FIXED: 13 types for 13 variables (كان عندك حرف زيادة قبل)
                $stmt_ins->bind_param(
                    "iissssidsssss",
                    $user_id,
                    $room_id,
                    $checkin,
                    $checkout,
                    $created_at,
                    $status,
                    $guests,
                    $total_amount,
                    $payment_method,
                    $payment_status,
                    $first_name,
                    $last_name,
                    $email
                );

                if ($stmt_ins->execute()) {
                    $success_msg = "Booking saved successfully! ✅";
                } else {
                    $error_msg = "Error saving booking: " . $stmt_ins->error;
                }

                $stmt_ins->close();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Booking Details</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

  <style>
  /* RESET & BASE ----------------------------------------------------------- */
*,
*::before,
*::after {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: "Open Sans", system-ui, -apple-system, BlinkMacSystemFont,
    "Segoe UI", sans-serif;
  color: #ffffff;
  min-height: 100vh;
  background-color: #1e4250;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  padding-top: 80px;
}

/* TOP BACK LINK --------------------------------------------------------- */
.top-bar {
  padding: 10px 20px 0;
}

.back-link {
  color: #ffffff;
  text-decoration: none;
  font-size: 0.9rem;
  opacity: 0.9;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.back-link .arrow {
  font-size: 1rem;
}

/* LAYOUT ----------------------------------------------------------------- */
.booking-layout {
  display: flex;
  justify-content: center;
  align-items: flex-start;
  gap: 34px;
  padding: 10px 20px 30px;
  flex-wrap: wrap;
}

/* SHARED CARD STYLES ----------------------------------------------------- */
.card {
  background: rgba(3, 54, 70, 0.9); /* dark teal with transparency */
  border-radius: 26px;
  box-shadow: 0 18px 40px rgba(0, 0, 0, 0.45);
}

.card-left {
  max-width: 420px;
  width: 100%;
  padding: 32px 32px 26px;
}

.card-right {
  max-width: 360px;
  width: 100%;
  padding: 26px 30px 22px;
}

/* TITLES / TEXT ---------------------------------------------------------- */
.card-title {
  font-family: "Playfair Display", "Times New Roman", serif;
  font-size: 2.5rem;
  text-align: center;
  letter-spacing: 0.02em;
  margin-bottom: 4px;
}

.card-subtitle {
  text-align: center;
  font-size: 0.95rem;
  opacity: 0.9;
  margin-bottom: 24px;
}

/* FORM ------------------------------------------------------------------- */
.booking-form {
  width: 100%;
}

.form-group {
  margin-bottom: 18px;
}

.label-strong {
  font-size: 0.95rem;
  font-weight: 600;
  margin-bottom: 6px;
  display: block;
}

/* Generic pill input wrapper */
.input-wrapper {
  background: #ffffff;
  border-radius: 30px;
  padding: 0;
  overflow: hidden;
}

.input-wrapper input {
  width: 100%;
  border: none;
  outline: none;
  padding: 12px 20px;
  font-size: 0.98rem;
  color: #222;
  background: transparent;
}

/* Date fields with icon */
.with-icon {
  position: relative;
}

.field-icon {
  position: absolute;
  left: 16px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 1.1rem;
  color: #666;
}

.with-icon input {
  padding-left: 48px;
  cursor: pointer;
}

/* Guests select with custom arrow, same pill style */
.select-wrapper {
  position: relative;
  background: #ffffff;
  border-radius: 30px;
  overflow: hidden;
}

.select-wrapper select {
  width: 100%;
  border: none;
  outline: none;
  padding: 12px 46px 12px 20px;
  font-size: 0.98rem;
  color: #222;
  background: transparent;
  appearance: none;
}

/* Down arrow on right side */
.select-wrapper::after {
  content: "▾";
  position: absolute;
  right: 16px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 1rem;
  color: #666;
  pointer-events: none;
}

/* BUTTON ----------------------------------------------------------------- */
.btn-primary {
  display: block;
  width: 80%;
  max-width: 260px;
  margin: 20px auto 0;
  padding: 12px 0;
  border-radius: 30px;
  border: none;
  background: #1d6c86;
  color: #ffffff;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
}

.btn-primary:hover {
  background: #2386a4;
}

/* ROOM SUMMARY CARD ------------------------------------------------------ */
.card-right .card-title {
  margin-bottom: 10px;
}

.room-type {
  text-align: center;
  font-weight: 600;
  margin-bottom: 4px;
}

.room-desc {
  text-align: center;
  font-size: 0.95rem;
  opacity: 0.9;
  margin-bottom: 22px;
  line-height: 1.4;
}

.room-info {
  font-size: 0.95rem;
}

.room-info .row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 8px;
}

.room-info .label {
  opacity: 0.9;
}

.room-info .value {
  font-weight: 600;
}

/* PAYMENT SECTION -------------------------------------------------------- */
.payment-section {
  margin-top: 24px;
  padding-top: 18px;
  border-top: 1px solid rgba(255, 255, 255, 0.25);
  display: none; /* مخفي بالبداية */
}

.payment-title {
  font-size: 1.2rem;
  font-weight: 600;
  margin-bottom: 14px;
}

/* لإخفاء/إظهار حقول الكريدت كارد */
#card-fields {
  display: none;
}

/* رسائل */
.alert {
  padding: 10px 14px;
  border-radius: 16px;
  margin-bottom: 12px;
  font-size: 0.9rem;
}

.alert-success {
  background: #1c7c57;
}

.alert-error {
  background: #8b1f2b;
}
  </style>
</head>

<body>

  <!-- NAVBAR AT THE VERY TOP -->
  <?php include 'navbar.html'; ?>

  <br><br><br><br>

  <!-- Back link bar -->
  <div class="top-bar">
    <a href="hotelInfo.php" class="back-link">
      <span class="arrow">&larr;</span>
      Back to rooms
    </a>
  </div>

  <main class="booking-layout">

    <!-- LEFT: BOOKING DETAILS -->
    <section class="card card-left">
      <h1 class="card-title">Booking Details</h1>
      <p class="card-subtitle">Select your dates and enter your information</p>

      <?php if (!empty($success_msg)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success_msg); ?></div>
      <?php endif; ?>

      <?php if (!empty($error_msg)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error_msg); ?></div>
      <?php endif; ?>

      <!-- Form sends data to SAME PAGE -->
      <form class="booking-form" action="" method="post">

        <!-- pass room_id -->
        <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($room['room_id']); ?>">

        <!-- Check-in -->
        <div class="form-group">
          <label class="label-strong">Check-in Date</label>
          <div class="input-wrapper with-icon">
            <span class="field-icon">&#128197;</span>
            <input type="date" name="checkin" required>
          </div>
        </div>

        <!-- Check-out -->
        <div class="form-group">
          <label class="label-strong">Check-out Date</label>
          <div class="input-wrapper with-icon">
            <span class="field-icon">&#128197;</span>
            <input type="date" name="checkout" required>
          </div>
        </div>

        <!-- Guests -->
        <div class="form-group">
          <label class="label-strong">Number of Guests</label>
          <div class="select-wrapper">
            <select name="guests" required>
              <option value="1">1 Guest</option>
              <option value="2">2 Guests</option>
              <option value="3">3 Guests</option>
              <option value="4">4 Guests</option>
            </select>
          </div>
        </div>

        <!-- First name -->
        <div class="form-group">
          <label class="label-strong">First Name</label>
          <div class="input-wrapper">
            <input type="text" name="first_name" required>
          </div>
        </div>

        <!-- Last name -->
        <div class="form-group">
          <label class="label-strong">Last Name</label>
          <div class="input-wrapper">
            <input type="text" name="last_name" required>
          </div>
        </div>

        <!-- Email -->
        <div class="form-group">
          <label class="label-strong">Email</label>
          <div class="input-wrapper">
            <input type="email" name="email" required>
          </div>
        </div>

        <!-- First button: show payment section -->
        <button type="button" class="btn-primary" id="show-payment-btn">
          Confirm Booking
        </button>

        <!-- PAYMENT SECTION -->
        <div class="payment-section" id="payment-section">
          <p class="payment-title">Payment Method</p>

          <!-- Payment Method select -->
          <div class="form-group">
            <label class="label-strong">Choose Payment Method</label>
            <div class="select-wrapper">
              <select name="payment_method" id="payment-method" required>
                <option value="at_hotel">Pay at Hotel</option>
                <option value="credit_card">Credit Card</option>
              </select>
            </div>
          </div>

          <!-- Credit Card fields - فقط إذا اختار Credit Card -->
          <div id="card-fields" style="display:none;">
            <div class="form-group">
              <label class="label-strong">Card Number</label>
              <div class="input-wrapper">
                <input type="text" name="card_number" placeholder="xxxx xxxx xxxx xxxx">
              </div>
            </div>

            <div class="form-group">
              <label class="label-strong">Card Password / CVV</label>
              <div class="input-wrapper">
                <input type="password" name="card_password" placeholder="***">
              </div>
            </div>
          </div>

          <!-- Final submit -->
          <button type="submit" class="btn-primary">
            Submit Booking
          </button>
        </div>

      </form>
    </section>

    <!-- RIGHT: ROOM SUMMARY -->
    <section class="card card-right">
      <h1 class="card-title">Room Summary</h1>

      <p class="room-type">
        <?php echo htmlspecialchars($room['hotel_name']); ?> –
        <?php echo htmlspecialchars($room['room_type']); ?>
      </p>
      <p class="room-desc">
        Enjoy a comfortable stay in this room type. <br>
        Perfect for up to <?php echo (int)$room['capacity']; ?> guest(s).
      </p>

      <div class="room-info">
        <div class="row">
          <span class="label">Room Type:</span>
          <span class="value"><?php echo htmlspecialchars($room['room_type']); ?></span>
        </div>

        <div class="row">
          <span class="label">Max Guests:</span>
          <span class="value"><?php echo (int)$room['capacity']; ?></span>
        </div>

        <div class="row">
          <span class="label">Price per Night:</span>
          <span class="value">$<?php echo number_format($room['price'], 2); ?></span>
        </div>
      </div>
    </section>

  </main>

  <!-- FOOTER -->
  <?php include 'footer.html'; ?>

  <!-- JS لإظهار الدفع + حقول الكريدت كارد -->
  <script>
    // Show payment section
    const showPaymentBtn = document.getElementById('show-payment-btn');
    const paymentSection = document.getElementById('payment-section');

    showPaymentBtn.addEventListener('click', function () {
      paymentSection.style.display = 'block';
      paymentSection.scrollIntoView({ behavior: 'smooth' });
    });

    // Show credit card fields only if "Credit Card" selected
    const paymentSelect = document.getElementById('payment-method');
    const cardFields = document.getElementById('card-fields');

    paymentSelect.addEventListener('change', function () {
      if (this.value === 'credit_card') {
        cardFields.style.display = 'block';
      } else {
        cardFields.style.display = 'none';
      }
    });
  </script>

</body>
</html>
