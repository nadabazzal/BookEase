<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>BookEase – Reservations Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome (for hamburger icon) -->
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


    .badge-logo {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      border: 2px solid var(--accent);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
    }

    .header-title {
      flex: 1;
      text-align: center;
      font-size: 25px;
      font-weight: 500;
       font-family:TAN Mon Cheri;
    }
    .hotelna

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
      font-family: 'TAN Mon Cheri';
      font-style: italic;    }

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

 
  </style>
</head>
<body id="top">

  
 <?php include 'navbar.html'; ?>
  <!-- ============ DASHBOARD ============ -->
  <main class="dashboard">

    <header class="top-bar">
      
      <div class="header-title">Reservations Dashboard – Admin View</div>
    </header>

    <!-- Filters -->
    <div class="filters-row">
      <label>Reservations for:</label>
      <select>
        <option>today</option>
        <option>tomorrow</option>
        <option>this week</option>
      </select>
    </div>

    <!-- Hotel Card -->
    <section class="hotel-card">
      <div class="hotel-card-header">
        <div class="hotel-name">LE GREY HOTEL</div>
        <div class="hotel-location"><i class="fa-solid fa-location-dot"></i>
          Beirut <br>
          <div class="hotel-nb"><i class="fa-solid fa-phone"></i> +961 7378383</div>
  </div>
       
      </div>

      <nav class="hotel-nav">
        <a href="#top" class="active">Today's Reservations</a>
        <a href="#">Pending</a>
        <a href="#">Confirmed</a>
        <a href="#">Cancelled</a>
      </nav>

      <ul class="guest-list">
        <li><a href="#nancy">NANCY AWADA</a></li>
        <li><a href="#ghadeer">GHADER MAZLOUM</a></li>
        <li><a href="#nadeen">NADEEN FARES</a></li>
      </ul>
    </section>

    <!-- ============= RESERVATION CARDS ============= -->

    <article id="nancy" class="reservation-card">
      <div class="reservation-header"><u>RESERVATION DETAILS</u></div>
 <div class="reservation-section-title">Reservation Info</div>
      <ul class="reservation-list">
        <li>Reservation ID: R-48291</li>
        <li>Date: March 14, 2025</li>
        <li>Time: 7:30 PM</li>
        <li>Guests: 4</li>
        <li>Status: Pending</li>
      </ul>

      <div class="reservation-section">
        <div class="reservation-section-title">Guest</div>
        <ul class="reservation-list">
          <li>Name: Nancy Awada</li>
          <li>Email: nancy@example.com</li>
          <li>Phone: +1 555-123-0001</li>
        </ul>
      </div>

      <div class="reservation-section">
        <div class="reservation-section-title">Service</div>
        <ul class="reservation-list">
          <li>Indoor Table</li>
          <li>Description</li>
        </ul>
      </div>

      <div class="reservation-actions">
        <button class="btn">Confirm</button>
        <button class="btn">Cancel</button>
        <button class="btn">Pending</button>
      </div>
    </article>

    <article id="ghadeer" class="reservation-card">
      <div class="reservation-header">RESERVATION DETAILS</div>
      <ul class="reservation-list">
        <li>Reservation ID: R-48292</li>
        <li>Date: March 14, 2025</li>
        <li>Time: 8:00 PM</li>
      </ul>

      <div class="reservation-actions">
        <button class="btn">Confirm</button>
        <button class="btn">Cancel</button>
      </div>
    </article>

    <article id="nadeen" class="reservation-card">
      <div class="reservation-header">RESERVATION DETAILS</div>
      <ul class="reservation-list">
        <li>Reservation ID: R-48293</li>
        <li>Date: March 14, 2025</li>
        <li>Time: 9:15 PM</li>
      </ul>

      <div class="reservation-actions">
        <button class="btn">Confirm</button>
        <button class="btn">Cancel</button>
      </div>
    </article>

  </main>
 <?php include 'footer.html'; ?>
  

</body>
</html>
