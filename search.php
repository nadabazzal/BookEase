<!DOCTYPE html> 
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BookEase – Hotel Deals</title>

  <style>
    /* ========= RESET بسيط ========= */
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

    /* ========= الشريط العلوي (Navbar) ========= */
    .top-bar {
      background: #0078b6;
      padding: 10px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .top-bar .logo {
      font-weight: bold;
      font-size: 20px;
    }

    .top-bar .nav-links {
      display: flex;
      gap: 20px;
      font-size: 14px;
    }

    /* ========= الحاوية الرئيسية ========= */
    .page-wrapper {
      max-width: 1100px;
      margin: 20px auto;
      background: #024158;
      border-radius: 10px;
      overflow: hidden;
    }

    /* الهيدر الداخلي (عنوان + بحث مدينة) */
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

    .city-search input {
      border: none;
      outline: none;
      padding: 10px 18px;
      background: transparent;
      color: #fff;
      width: 180px;
      font-size: 14px;
    }

    .city-search input::placeholder {
      color: #cfe9f4;
    }

    .city-search button {
      border: none;
      background: #013a52;
      color: #fff;
      padding: 10px 16px;
      font-size: 14px;
      cursor: pointer;
    }

    /* ========= محتوى الصفحة (فلاتر + فنادق) ========= */
    .content {
      display: flex;
      padding: 20px 30px 30px;
      gap: 20px;
    }

    /* ========= العمود الأيسر – الفلاتر ========= */
    .filters-column {
      width: 280px;
      flex-shrink: 0;
      display: flex;
      flex-direction: column;
      gap: 20px;
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

    /* زرار choose features */
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

    /* --- صندوق الميزانية --- */
    .budget-slider-wrapper {
      margin-top: 10px;
    }

    .budget-values {
      display: flex;
      justify-content: space-between;
      font-size: 13px;
      margin-top: 6px;
    }

    input[type="range"] {
      width: 100%;
      accent-color: #ffcc33;
    }

    /* --- القوائم العمودية للأزرار --- */
    .pill-list {
      display: none; /* مخفيين بالبداية – بيظهرو مع الزر */
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
    }

    .pill-btn:hover {
      background: #91c3d8;
      transform: translateY(-1px);
    }

    /* ========= العمود الأيمن – نتائج الفنادق ========= */
    .results-column {
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 18px;
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

    /* كرت الفندق */
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
      font-size: 16px;
      color: #ffcc33;
      margin-bottom: 10px;
    }

    .hotel-bottom-row {
      display: flex;
      justify-content: space-between;
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
    }

    .more-info-btn:hover {
      background: #91c3d8;
      transform: translateY(-1px);
    }

    .room-icons {
      font-size: 18px;
      opacity: 0.9;
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

      <div class="city-search">
        <input type="text" placeholder="Search city" />
        <button>▼</button>
      </div>
    </div>

    <!-- المحتوى: فلاتر + فنادق -->
    <div class="content">
      <!-- العمود الأيسر: الفلاتر -->
      <div class="filters-column">
        <!-- Hotel Features -->
        <div class="filter-box">
          <div class="filter-title">Hotel Features</div>
          <!-- الزر اللي بيفتح/بسكر الفيتشرز -->
          <button class="toggle-btn" data-label="Choose hotel features">
            Choose hotel features
          </button>

          <div class="pill-list">
            <button class="pill-btn">Restaurant</button>
            <button class="pill-btn">Swimming Pool</button>
            <button class="pill-btn">Gym</button>
            <button class="pill-btn">Non-Smoking Room</button>
            <button class="pill-btn">Parking</button>
            <button class="pill-btn">Free WiFi</button>
            <button class="pill-btn">Room Service</button>
            <button class="pill-btn">Pets Allowed</button>
          </div>
        </div>

        <!-- Rooms Features -->
        <div class="filter-box">
          <div class="filter-title">Rooms Features</div>
          <!-- الزر اللي بيفتح/بسكر room features -->
          <button class="toggle-btn" data-label="Choose room features">
            Choose room features
          </button>

          <div class="pill-list">
            <button class="pill-btn">Private Bathroom</button>
            <button class="pill-btn">Balcony</button>
            <button class="pill-btn">Kitchen</button>
            <button class="pill-btn">View</button>
            <button class="pill-btn">Electrical Tools</button>
          </div>
        </div>
      </div>

      <!-- العمود الأيمن: كروت الفنادق -->
      <div class="results-column">
        <div class="sort-row">
          <span>Sort By:</span>
          <select class="sort-select">
            <option>Recommended</option>
            <option>Price: Low to High</option>
            <option>Price: High to Low</option>
            <option>Rating</option>
          </select>
        </div>

        <!-- كرت 1 -->
        <div class="hotel-card">
          <img
            src="https://via.placeholder.com/400x250"
            alt="Hotel"
            class="hotel-image"
          />
          <div class="hotel-info">
            <div>
              <div class="hotel-title">LE GRAY BEIRUT</div>
              <div class="hotel-price">FROM $500/NIGHT</div>
              <div class="hotel-stars">★★★★★</div>
            </div>

            <div class="hotel-bottom-row">
              <button class="more-info-btn">Show more info</button>
              <div class="room-icons">🛏️🛏️🛏️</div>
            </div>
          </div>
        </div>

        <!-- كرت 2 -->
        <div class="hotel-card">
          <img
            src="https://via.placeholder.com/400x250"
            alt="Hotel"
            class="hotel-image"
          />
          <div class="hotel-info">
            <div>
              <div class="hotel-title">LE GRAY BEIRUT</div>
              <div class="hotel-price">FROM $500/NIGHT</div>
              <div class="hotel-stars">★★★★★</div>
            </div>

            <div class="hotel-bottom-row">
              <button class="more-info-btn">Show more info</button>
              <div class="room-icons">🛏️🛏️🛏️</div>
            </div>
          </div>
        </div>

        <!-- كرت 3 -->
        <div class="hotel-card">
          <img
            src="https://via.placeholder.com/400x250"
            alt="Hotel"
            class="hotel-image"
          />
          <div class="hotel-info">
            <div>
              <div class="hotel-title">LE GRAY BEIRUT</div>
              <div class="hotel-price">FROM $500/NIGHT</div>
              <div class="hotel-stars">★★★★★</div>
            </div>

            <div class="hotel-bottom-row">
              <button class="more-info-btn">Show more info</button>
              <div class="room-icons">🛏️🛏️🛏️</div>
            </div>
          </div>
        </div>
      </div>
      <!-- نهاية results-column -->
    </div>
    <!-- نهاية content -->
  </div>
  <!-- نهاية page-wrapper -->
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
    });
  </script>
</body>
</html>
