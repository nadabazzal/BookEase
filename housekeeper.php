<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>BookEase – Housekeeper Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: "Poppins", sans-serif;
      background: #123c4f;
      color: #f5f7f9;
    }

    a {
      color: inherit;
      text-decoration: none;
    }

    /* MAIN WRAPPER */
    .page-wrapper {
      max-width: 1100px;
      margin: 40px auto 60px;
      padding: 0 20px;
    }

    /* WELCOME SECTION */
    .welcome-block {
      margin-bottom: 20px;
    }

    .welcome-title {
      font-family: "Playfair Display", serif;
      font-size: 32px;
      margin-bottom: 4px;
    }

    .welcome-title span {
      font-style: italic;
      font-weight: 500;
    }

    .welcome-meta {
      font-size: 14px;
      color: #d3e0e8;
      line-height: 1.7;
    }

    /* CARD BASE DESIGN */
    .card {
      background: #0e3144;
      border-radius: 22px;
      padding: 24px 28px;
      box-shadow: 0 10px 28px rgba(0, 0, 0, 0.25);
      margin-bottom: 30px;
    }

    /* TASKS CARD */
    .tasks-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 16px;
    }

    .chip {
      background: #0c2837;
      padding: 6px 16px;
      border-radius: 100px;
      font-size: 13px;
      display: flex;
      gap: 6px;
      align-items: center;
    }

    .tasks-hotel {
      font-family: "Playfair Display", serif;
      font-size: 20px;
      letter-spacing: .5px;
      margin-bottom: 16px;
    }

    .tasks-tabs {
      display: flex;
      gap: 18px;
      margin-bottom: 16px;
      font-size: 14px;
    }

    .tasks-tabs a {
      padding-bottom: 2px;
      border-bottom: 1px solid transparent;
    }

    .tasks-tabs a.active {
      border-color: #f2e6c9;
    }

    /* GUEST LIST */
    .guest-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .guest-item {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 15px;
    }

    .guest-icon {
      background: #0c2837;
      width: 24px;
      height: 24px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
    }

    .guest-item a {
      text-decoration: underline;
    }

    /* DETAILS CARD */
    .details-title {
      text-align: center;
      font-family: "Playfair Display", serif;
      font-size: 22px;
      margin-bottom: 18px;
    }

    .details-grid {
      font-size: 15px;
      line-height: 1.8;
      margin-bottom: 20px;
    }

    .details-grid span.label {
      font-weight: 600;
    }

    .divider {
      width: 65%;
      margin: 10px auto 20px;
      border-bottom: 2px solid #ffffff80;
    }

    .services-title {
      text-align: center;
      font-size: 15px;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .services-text {
      font-size: 14px;
      line-height: 1.8;
      color: #e0edf5;
    }

    /* ACTION BUTTONS */
    .actions-row {
      margin-top: 28px;
      display: flex;
      justify-content: center;
      gap: 25px;
      flex-wrap: wrap;
    }

    .btn-pill {
      padding: 12px 28px;
      background: #fff;
      border: none;
      border-radius: 50px;
      font-size: 15px;
      font-weight: 500;
      color: #0e3144;
      cursor: pointer;
      transition: 0.2s ease;
    }

    .btn-pill:hover {
      background: #f0f0f0;
      transform: translateY(-2px);
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
      .page-wrapper {
        margin-top: 20px;
      }
      .card {
        padding: 20px 18px;
      }
    }

  </style>
</head>

<body>
    <?php include 'navbar.html'; ?>
    <br><br><br><br>
  <main class="page-wrapper">

    <!-- WELCOME -->
    <section class="welcome-block">
      <h1 class="welcome-title">Welcome,esma</h1>
      <p class="welcome-meta">
        Shift: 8:00 AM – 4:00 PM<br>
        Today: December 7, 2025
      </p>
    </section>

    <!-- TASKS CARD -->
    <section class="card">
      <div class="tasks-header">
        <div class="chip">📅 Today</div>
        <div class="chip">📍 Beirut &nbsp;•&nbsp; 75788833</div>
      </div>

      <div class="tasks-hotel">LE GREY HOTEL</div>

      <div class="tasks-tabs">
        <a href="#" class="active">Today’s Requests</a>
        <a href="#">To clean</a>
        <a href="#">In Progress</a>
        <a href="#">Completed</a>
      </div>

      <div class="guest-list">
        <div class="guest-item"><div class="guest-icon">👤</div> <a href="#">NANCY AWADA</a></div>
        <div class="guest-item"><div class="guest-icon">👤</div> <a href="#">GHADIR MAZLOUM</a></div>
        <div class="guest-item"><div class="guest-icon">👤</div> <a href="#">NADEEN FARES</a></div>
      </div>
    </section>

    <!-- DETAILS CARD -->
    <section class="card">
      <h2 class="details-title">Details</h2>

      <div class="details-grid">
        <div><span class="label">Task ID:</span> HK-20341</div>
        <div><span class="label">Date:</span> March 14, 2025</div>
        <div><span class="label">Time:</span> 9:00 AM</div>
        <div><span class="label">Duration:</span> 3 hours</div>
        <div><span class="label">Status:</span> Scheduled</div>
      </div>

      <div class="divider"></div>

      <div class="services-title">Services Cleaning</div>

      <p class="services-text">
        <strong>Package:</strong> Deep Cleaning<br>
        <strong>Rooms:</strong> 2 bedrooms, 1 bathroom, kitchen, living room<br>
        <strong>Extras:</strong> Inside fridge, balcony sweep, linen change<br>
        <strong>Notes:</strong> Pet in apartment – friendly dog
      </p>

      <div class="actions-row">
        <button class="btn-pill">Start cleaning</button>
        <button class="btn-pill">Mark as Cleaned</button>
      </div>
    </section>

  </main>
  <?php include 'footer.html'; ?>
</body>
</html>
