<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Booking Details</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

  <!-- Stylesheet -->
  <link rel="stylesheet" href="booking.css" />
</head>

<body>

  <!-- NAVBAR AT THE VERY TOP -->
  <?php include 'navbar.html'; ?>
  

  <!-- Back link bar -->
  <div class="top-bar">
    <a href="#" class="back-link">
      <span class="arrow">&larr;</span>
      Back to rooms
    </a>
  </div>

  <main class="booking-layout">

    <!-- LEFT: BOOKING DETAILS -->
    <section class="card card-left">
      <h1 class="card-title">Booking Details</h1>
      <p class="card-subtitle">Select your dates and enter your information</p>

      <form class="booking-form" action="#" method="post">

        <!-- Check-in -->
        <div class="form-group">
          <label class="label-strong">Check-in Date</label>
          <div class="input-wrapper with-icon">
            <span class="field-icon">&#128197;</span>
            <input type="text" placeholder="Select Date">
          </div>
        </div>

        <!-- Check-out -->
        <div class="form-group">
          <label class="label-strong">Check-out Date</label>
          <div class="input-wrapper with-icon">
            <span class="field-icon">&#128197;</span>
            <input type="text" placeholder="Select Date">
          </div>
        </div>

        <!-- Guests -->
        <div class="form-group">
          <label class="label-strong">Number of Guests</label>
          <div class="select-wrapper">
            <select>
              <option>1 Guest</option>
              <option>2 Guests</option>
              <option>3 Guests</option>
              <option>4 Guests</option>
            </select>
          </div>
        </div>

        <!-- First name -->
        <div class="form-group">
          <label class="label-strong">First Name</label>
          <div class="input-wrapper">
            <input type="text">
          </div>
        </div>

        <!-- Last name -->
        <div class="form-group">
          <label class="label-strong">Last Name</label>
          <div class="input-wrapper">
            <input type="text">
          </div>
        </div>

        <!-- Email -->
        <div class="form-group">
          <label class="label-strong">Email</label>
          <div class="input-wrapper">
            <input type="email">
          </div>
        </div>

        <button type="submit" class="btn-primary">Confirm Booking</button>
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

</body>
</html>
