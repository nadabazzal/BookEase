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

/* RESPONSIVE ------------------------------------------------------------- */
@media (max-width: 900px) {
  .booking-layout {
    align-items: stretch;
  }

  .card-left,
  .card-right {
    max-width: 460px;
  }

  .card-right {
    margin-top: 10px;
  }
}

@media (max-width: 520px) {
  .card-left,
  .card-right {
    border-radius: 22px;
    padding-left: 22px;
    padding-right: 22px;
  }

  .card-title {
    font-size: 2.1rem;
  }
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

      <!-- حطي action و method اللي بدك ياهن بعدين -->
      <form class="booking-form" action="#" method="post">

        <!-- Check-in -->
        <div class="form-group">
          <label class="label-strong">Check-in Date</label>
          <div class="input-wrapper with-icon">
            <span class="field-icon">&#128197;</span>
            <input type="text" name="checkin" placeholder="Select Date">
          </div>
        </div>

        <!-- Check-out -->
        <div class="form-group">
          <label class="label-strong">Check-out Date</label>
          <div class="input-wrapper with-icon">
            <span class="field-icon">&#128197;</span>
            <input type="text" name="checkout" placeholder="Select Date">
          </div>
        </div>

        <!-- Guests -->
        <div class="form-group">
          <label class="label-strong">Number of Guests</label>
          <div class="select-wrapper">
            <select name="guests">
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
            <input type="text" name="first_name">
          </div>
        </div>

        <!-- Last name -->
        <div class="form-group">
          <label class="label-strong">Last Name</label>
          <div class="input-wrapper">
            <input type="text" name="last_name">
          </div>
        </div>

        <!-- Email -->
        <div class="form-group">
          <label class="label-strong">Email</label>
          <div class="input-wrapper">
            <input type="email" name="email">
          </div>
        </div>

        <!-- زر أول: يظهر قسم الدفع بدل ما يبعث الفورم -->
        <button type="button" class="btn-primary" id="show-payment-btn">
          Confirm Booking
        </button>

        <!-- PAYMENT SECTION (يظهر بعد الضغط على Confirm Booking) -->
        <!-- PAYMENT SECTION (يظهر بعد الضغط على Confirm Booking) -->
<div class="payment-section" id="payment-section">
  <p class="payment-title">Payment Method</p>

  <!-- Payment Method select -->
  <div class="form-group">
    <label class="label-strong">Choose Payment Method</label>
    <div class="select-wrapper">
      <select name="payment_method" id="payment-method">
        <option value="hotel">Pay at Hotel</option>
        <option value="card">Credit Card</option>
      </select>
    </div>
  </div>

  <!-- Credit Card fields - تظهر فقط إذا اختار Credit Card -->
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

  <!-- الزر النهائي اللي فعلياً بيبعث الفورم -->
  <button type="submit" class="btn-primary">
    Submit Booking
  </button>
</div>

        

      </form>
    </section>

    <!-- RIGHT: ROOM SUMMARY -->
    <section class="card card-right">
      <h1 class="card-title">Room Summary</h1>

      <p class="room-type">Family Suite</p>
      <p class="room-desc">
        Spacious accommodation perfect for families,<br>
        featuring separate sleeping areas
      </p>

      <div class="room-info">
        <div class="row">
          <span class="label">Room Size:</span>
          <span class="value">600 sq ft</span>
        </div>

        <div class="row">
          <span class="label">Max Guests:</span>
          <span class="value">4</span>
        </div>

        <div class="row">
          <span class="label">Beds:</span>
          <span class="value">1 King Bed</span>
        </div>
      </div>
    </section>

  </main>

  <!-- FOOTER -->
  <?php include 'footer.html'; ?>

  <!-- JS بسيط لإظهار قسم الدفع + إظهار حقول الكريدت كارد -->
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
    if (this.value === 'card') {
      cardFields.style.display = 'block';
    } else {
      cardFields.style.display = 'none';
    }
  });
</script>


</body>
</html>
