<?php 
session_start();

$conn = mysqli_connect("localhost", "root", "", "hotel_management_system");
if (!$conn) {
    die("connection failed: " . mysqli_connect_error());
}

$room_id = 0;
if (isset($_POST['room_id'])) {
    $room_id = (int)$_POST['room_id'];
}
if ($room_id <= 0) {
    die("No room selected.");
}

$sql = "
    SELECT r.room_id, r.price, r.capacity, r.room_type,
           h.hotel_name, h.hotel_id
    FROM rooms r
    JOIN hotels h ON r.hotel_id = h.hotel_id
    WHERE r.room_id = $room_id
";

$res = mysqli_query($conn, $sql);
if (!$res || mysqli_num_rows($res) == 0) {
    die("Room not found.");
}
$room = mysqli_fetch_assoc($res);

$hotel_id = (int)$room['hotel_id'];   

$services = array();
$services_res = mysqli_query($conn, "SELECT service_id, service_name, description FROM services ORDER BY service_id ASC");
if ($services_res) {
    while ($row = mysqli_fetch_assoc($services_res)) {
        $services[] = $row;
    }
}

$housekeepers = array();
$hk_list_res = mysqli_query($conn, "SELECT user_id, first_name, last_name FROM users WHERE role='housekeeper' ORDER BY first_name ASC");
if ($hk_list_res) {
    while ($hkrow = mysqli_fetch_assoc($hk_list_res)) {
        $housekeepers[] = $hkrow;
    }
}

$need_housekeeper = 0;
$hk_services = array();
$housekeeper_id = 0;
$checkin = "";
$checkout = "";
$guests = 1;
$first_name = "";
$last_name = "";
$email = "";
$payment_method = "";
$need_housekeeper = 0;
$hk_services = array();
$housekeeper_id = 0;

$success_msg = "";
$error_msg   = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_booking'])) {

    $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;

    $checkin        = isset($_POST['checkin']) ? $_POST['checkin'] : '';
    $checkout       = isset($_POST['checkout']) ? $_POST['checkout'] : '';
    $guests         = isset($_POST['guests']) ? (int)$_POST['guests'] : 0;
    $first_name     = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
    $last_name      = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
    $email          = isset($_POST['email']) ? trim($_POST['email']) : '';
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';

    $need_housekeeper = isset($_POST['need_housekeeper']) ? (int)$_POST['need_housekeeper'] : 0; // 1 yes, 0 no
    $hk_services = isset($_POST['hk_services']) && is_array($_POST['hk_services']) ? $_POST['hk_services'] : array();
    $housekeeper_id = isset($_POST['housekeeper_id']) ? (int)$_POST['housekeeper_id'] : 0;

    if ($checkin == '' || $checkout == '' || $guests <= 0 || $first_name == '' || $last_name == '' || $email == '' || $payment_method == '') {
        $error_msg = "Please fill in all required fields.";
    } else {

        $checkin_ts  = strtotime($checkin);
        $checkout_ts = strtotime($checkout);

        if ($checkin_ts === false || $checkout_ts === false || $checkout_ts <= $checkin_ts) {
            $error_msg = "Invalid dates: check-out must be after check-in.";
        } else {

            if ($need_housekeeper === 1) {

                if ($housekeeper_id <= 0) {
                    $error_msg = "Please choose a housekeeper.";
                } elseif (count($hk_services) == 0) {
                    $error_msg = "Please select at least one housekeeping service.";
                } else {
                    /* Validate that chosen user is really a housekeeper */
                    $chk_hk = mysqli_query($conn, "SELECT user_id FROM users WHERE user_id = $housekeeper_id AND role='housekeeper' LIMIT 1");
                    if (!$chk_hk || mysqli_num_rows($chk_hk) == 0) {
                        $error_msg = "Invalid housekeeper selected.";
                    }
                }
            }

            if ($error_msg == "") {

                $price_per_night = (float)$room['price'];
                $nights = (int)(($checkout_ts - $checkin_ts) / 86400);
                if ($nights < 1) $nights = 1;

                $total_amount = $price_per_night * $nights;

                $status         = "pending";
                $payment_status = "pending";
                if ($payment_method == "credit_card") {
                    $payment_status = "paid";
                }

                $created_at = date("Y-m-d H:i:s");

                $fn = mysqli_real_escape_string($conn, $first_name);
                $ln = mysqli_real_escape_string($conn, $last_name);
                $em = mysqli_real_escape_string($conn, $email);
                $pm = mysqli_real_escape_string($conn, $payment_method);
                $st = mysqli_real_escape_string($conn, $status);
                $ps = mysqli_real_escape_string($conn, $payment_status);
                $ci = mysqli_real_escape_string($conn, $checkin);
                $co = mysqli_real_escape_string($conn, $checkout);
                $ca = mysqli_real_escape_string($conn, $created_at);

                $sql_insert = "
                    INSERT INTO booking
                    (user_id, room_id, check_in, check_out, created_at, status, guests_no, total_amount, payment_method, payment_status,
                     guest_first_name, guest_last_name, guest_email)
                    VALUES
                    ($user_id, $room_id, '$ci', '$co', '$ca', '$st', $guests, $total_amount, '$pm', '$ps', '$fn', '$ln', '$em')
                ";

                $ins = mysqli_query($conn, $sql_insert);
                if ($ins) {

                    $success_msg = "Booking saved successfully! ✅";

                    if ($need_housekeeper === 1) {

                        $assign_to = $housekeeper_id; 

                        $hs_status = "pending";
                        $notes = mysqli_real_escape_string($conn, "Requested during booking.");
                        $hs_created_at = date("Y-m-d H:i:s");
                        $hs_ca = mysqli_real_escape_string($conn, $hs_created_at);
                        $hs_st = mysqli_real_escape_string($conn, $hs_status);

                        $added_count = 0;

                        foreach ($hk_services as $sid) {
                            $service_id = (int)$sid;
                            if ($service_id > 0) {

                                $sql_hs = "
                                    INSERT INTO housekeeping_requests
                                    (room_id, service_id, status, assign_to, notes, created_at)
                                    VALUES
                                    ($room_id, $service_id, '$hs_st', $assign_to, '$notes', '$hs_ca')
                                ";
                                $ins_hs = mysqli_query($conn, $sql_hs);
                                if ($ins_hs) {
                                    $added_count++;
                                }
                            }
                        }

                        if ($added_count > 0) {
                            $success_msg .= " Housekeeping requests added ✅";
                        } else {
                            $success_msg .= " (Booking saved, but no housekeeping request was added.)";
                        }
                    }

                } else {
                    $error_msg = "Error saving booking: " . mysqli_error($conn);
                }
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

  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

  <style>
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
  background: rgba(3, 54, 70, 0.9);
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
.booking-form { width: 100%; }

.form-group { margin-bottom: 18px; }

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
.with-icon { position: relative; }

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

.btn-primary:hover { background: #2386a4; }

.card-right .card-title { margin-bottom: 10px; }

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

.room-info { font-size: 0.95rem; }

.room-info .row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 8px;
}

.room-info .label { opacity: 0.9; }
.room-info .value { font-weight: 600; }

.payment-section {
  margin-top: 24px;
  padding-top: 18px;
  border-top: 1px solid rgba(255, 255, 255, 0.25);
  display: none;
}

.payment-title {
  font-size: 1.2rem;
  font-weight: 600;
  margin-bottom: 14px;
}

#card-fields { display: none; }

.hk-services {
  display: none;
  margin-top: 10px;
  padding: 14px 14px;
  border-radius: 18px;
  background: rgba(255, 255, 255, 0.10);
  border: 1px solid rgba(255, 255, 255, 0.18);
}

.hk-item {
  display: flex;
  gap: 10px;
  align-items: flex-start;
  padding: 8px 6px;
  border-radius: 12px;
}

.hk-item input { margin-top: 3px; }

.hk-item .hk-text {
  font-size: 0.92rem;
  line-height: 1.25;
}

.hk-item .hk-name { font-weight: 600; }

.hk-item .hk-desc {
  opacity: 0.9;
  font-size: 0.85rem;
  margin-top: 2px;
}

.alert {
  padding: 10px 14px;
  border-radius: 16px;
  margin-bottom: 12px;
  font-size: 0.9rem;
}
.alert-success { background: #1c7c57; }
.alert-error   { background: #8b1f2b; }
  </style>
</head>

<body>

  <?php include 'navbar.html'; ?>

  <br><br><br><br>

  <form action="info.php" method="post" style="display:inline;">
    <input type="hidden" name="hotel_id" value="<?php echo (int)$hotel_id; ?>">
    <button type="submit" class="back-link" style="background:none;border:none;cursor:pointer;">
      <span class="arrow">&larr;</span>
      Back to rooms
    </button>
  </form>

  <main class="booking-layout">

    <section class="card card-left">
      <h1 class="card-title">Booking Details</h1>
      <p class="card-subtitle">Select your dates and enter your information</p>

      <?php if ($success_msg != ""): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success_msg); ?></div>
      <?php endif; ?>

      <?php if ($error_msg != ""): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error_msg); ?></div>
      <?php endif; ?>

      <form class="booking-form" action="" method="post">

        <input type="hidden" name="room_id" value="<?php echo (int)$room['room_id']; ?>">

        <div class="form-group">
          <label class="label-strong">Check-in Date</label>
          <div class="input-wrapper with-icon">
            <span class="field-icon">&#128197;</span>
            <input type="date" name="checkin" required value="<?php echo htmlspecialchars($checkin); ?>">
          </div>
        </div>

        <div class="form-group">
          <label class="label-strong">Check-out Date</label>
          <div class="input-wrapper with-icon">
            <span class="field-icon">&#128197;</span>
            <input type="date" name="checkout" required value="<?php echo htmlspecialchars($checkout); ?>">
          </div>
        </div>

        <div class="form-group">
          <label class="label-strong">Number of Guests</label>
          <div class="select-wrapper">
            <select name="guests" required>
              <option value="1" <?php echo ($guests==1?'selected':''); ?>>1 Guest</option>
              <option value="2" <?php echo ($guests==2?'selected':''); ?>>2 Guests</option>
              <option value="3" <?php echo ($guests==3?'selected':''); ?>>3 Guests</option>
              <option value="4" <?php echo ($guests==4?'selected':''); ?>>4 Guests</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="label-strong">First Name</label>
          <div class="input-wrapper">
            <input type="text" name="first_name" required value="<?php echo htmlspecialchars($first_name); ?>">
          </div>
        </div>

        <div class="form-group">
          <label class="label-strong">Last Name</label>
          <div class="input-wrapper">
            <input type="text" name="last_name" required value="<?php echo htmlspecialchars($last_name); ?>">
          </div>
        </div>

        <div class="form-group">
          <label class="label-strong">Email</label>
          <div class="input-wrapper">
            <input type="email" name="email" required value="<?php echo htmlspecialchars($email); ?>">
          </div>
        </div>

        <button type="button" class="btn-primary" id="show-payment-btn">
          Confirm Booking
        </button>

        <div class="payment-section" id="payment-section">
          <p class="payment-title">Payment Method</p>

          <div class="form-group">
            <label class="label-strong">Choose Payment Method</label>
            <div class="select-wrapper">
              <select name="payment_method" id="payment-method" required>
                <option value="at_hotel" <?php echo ($payment_method=='at_hotel'?'selected':''); ?>>Pay at Hotel</option>
                <option value="credit_card" <?php echo ($payment_method=='credit_card'?'selected':''); ?>>Credit Card</option>
              </select>
            </div>
          </div>

          <!-- HOUSEKEEPER (YES/NO) -->
          <div class="form-group">
            <label class="label-strong">Do you want a housekeeper?</label>
            <div class="select-wrapper">
              <select name="need_housekeeper" id="need-housekeeper">
                <option value="0" <?php echo ($need_housekeeper===0?'selected':''); ?>>No</option>
                <option value="1" <?php echo ($need_housekeeper===1?'selected':''); ?>>Yes</option>
              </select>
            </div>
          </div>

          <!-- NEW: CHOOSE HOUSEKEEPER (HIDDEN UNTIL YES) -->
          <div class="form-group" id="hk-choose-box" style="display:none;">
            <label class="label-strong">Choose Housekeeper</label>
            <div class="select-wrapper">
              <select name="housekeeper_id" id="housekeeper-id">
                <option value="">-- Select --</option>
                <?php foreach ($housekeepers as $hk): ?>
                  <option value="<?php echo (int)$hk['user_id']; ?>" <?php echo ($housekeeper_id==(int)$hk['user_id']?'selected':''); ?>>
                    <?php echo htmlspecialchars($hk['first_name']." ".$hk['last_name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <!-- SERVICES CHECKBOXES (HIDDEN UNTIL YES) -->
          <div class="hk-services" id="hk-services-box">
            <label class="label-strong" style="margin-bottom:10px;">Select Housekeeping Services</label>

            <?php if (count($services) == 0): ?>
              <div style="opacity:0.9;font-size:0.9rem;">No services found in database.</div>
            <?php else: ?>
              <?php foreach ($services as $s): ?>
                <?php $checked = in_array((string)$s['service_id'], array_map('strval',$hk_services), true); ?>
                <div class="hk-item">
                  <input type="checkbox" name="hk_services[]" value="<?php echo (int)$s['service_id']; ?>" <?php echo ($checked?'checked':''); ?>>
                  <div class="hk-text">
                    <div class="hk-name"><?php echo htmlspecialchars($s['service_name']); ?></div>
                    <div class="hk-desc"><?php echo htmlspecialchars($s['description']); ?></div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>

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

          <button type="submit" class="btn-primary" name="submit_booking" value="1">
            Submit Booking
          </button>
        </div>

      </form>
    </section>

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
          <span class="value">$<?php echo number_format((float)$room['price'], 2); ?></span>
        </div>
      </div>
    </section>

  </main>

  <?php include 'footer.html'; ?>

  <script>
    const showPaymentBtn = document.getElementById('show-payment-btn');
    const paymentSection = document.getElementById('payment-section');

    showPaymentBtn.addEventListener('click', function () {
      paymentSection.style.display = 'block';
      paymentSection.scrollIntoView({ behavior: 'smooth' });
    });

    const paymentSelect = document.getElementById('payment-method');
    const cardFields = document.getElementById('card-fields');

    paymentSelect.addEventListener('change', function () {
      if (this.value === 'credit_card') {
        cardFields.style.display = 'block';
      } else {
        cardFields.style.display = 'none';
      }
    });

    const hkSelect = document.getElementById('need-housekeeper');
    const hkBox = document.getElementById('hk-services-box');
    const hkChooseBox = document.getElementById('hk-choose-box');
    const hkSelectId = document.getElementById('housekeeper-id');

    function toggleHK() {
      if (hkSelect.value === '1') {
        hkBox.style.display = 'block';
        hkChooseBox.style.display = 'block';
      } else {
        hkBox.style.display = 'none';
        hkChooseBox.style.display = 'none';

        const checks = hkBox.querySelectorAll('input[type="checkbox"]');
        checks.forEach(ch => ch.checked = false);

        if (hkSelectId) hkSelectId.value = "";
      }
    }

    hkSelect.addEventListener('change', toggleHK);
    toggleHK();
  </script>

</body>
</html>
