<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookEase - Hotels</title>

    <!-- Playfair Display -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- CSS File -->
    <link rel="stylesheet" href="search.css">
</head>
<body>
        <?php include 'navbar.html'; ?>

    <header class="header"><br><br><br>
        <h1>SEARCH BY CITY AND FILTER TO DISCOVER THE BEST<br>HOTEL DEALS</h1>

        <div class="search-bar">
            <input type="text" placeholder="search city">
            <button class="dropdown-btn">⌄</button>
        </div>
    </header>

    <main class="container">

        <!-- LEFT FILTERS -->
        <aside class="filters">

            <h2>Your Budget</h2>
            <div class="budget">
                <span>70$-</span>
                <input type="range">
                <span>200$+</span>
            </div>

            <h3>Hotel Features</h3>
            <div class="tags">
                <span>Restaurant</span>
                <span>Swimming Pool</span>
                <span>Gym</span>
                <span>Non-Smoking Room</span>
                <span>Parking</span>
                <span>Free-wifi</span>
                <span>Room Service</span>
                <span>Pets Allowed</span>
            </div>

            <h3>Rooms Features</h3>
            <div class="tags">
                <span>Private Bathroom</span>
                <span>Balcony</span>
                <span>Kitchen</span>
                <span>View</span>
                <span>Electrical Tools</span>
            </div>
        </aside>

        <!-- RIGHT HOTEL LIST -->
        <section class="hotel-list">

            <div class="sort">
                <p>Sort By</p>
                <button>↑↓</button>
            </div>

            <!-- HOTEL CARD -->
            <div class="hotel-card">
                <img src="PUT IMAGE LINK HERE" alt="hotel">
                <div class="hotel-info">
                    <h2>LE GRAY BEIRUT</h2>
                    <p>FROM $500/NIGHT</p>
                    <div class="stars">★★★★★</div>
                    <button class="info-btn">Show more info</button>
                </div>
            </div>

            <div class="hotel-card">
                <img src="PUT IMAGE LINK HERE" alt="hotel">
                <div class="hotel-info">
                    <h2>LE GRAY BEIRUT</h2>
                    <p>FROM $500/NIGHT</p>
                    <div class="stars">★★★★★</div>
                    <button class="info-btn">Show more info</button>
                </div>
            </div>

            <div class="hotel-card">
                <img src="PUT IMAGE LINK HERE" alt="hotel">
                <div class="hotel-info">
                    <h2>LE GRAY BEIRUT</h2>
                    <p>FROM $500/NIGHT</p>
                    <div class="stars">★★★★★</div>
                    <button class="info-btn">Show more info</button>
                </div>
            </div>

        </section>

    </main>
    <?php include 'footer.html'; ?>

</body>
</html>
