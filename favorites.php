<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotels</title>
    <link rel="stylesheet" href="favorites.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'navbar.html'; ?>

<br><br><br><br>

<section class="filter-header">
    <div class="sort-container">
        <button class="sort-btn">
            Sort by : Price
            <img src="YOUR-DOWN-ARROW-LINK" class="arrow-icon">
        </button>
    </div>
</section>

<section class="hotel-list">

    <!-- HOTEL CARD -->
    <div class="hotel-card">
        <img src="YOUR-HOTEL-IMAGE" class="hotel-img">

        <div class="hotel-info">
            <h3 class="hotel-name">LE GRAY BEIRUT</h3>
            <p class="price">FROM $500/NIGHT</p>

            <div class="stars">
                <img src="YOUR-STAR-ICON">
                <img src="YOUR-STAR-ICON">
                <img src="YOUR-STAR-ICON">
                <img src="YOUR-STAR-ICON">
                <img src="YOUR-STAR-ICON">
            </div>

            <button class="more-btn">Show more info</button>
        </div>

        <div class="icons-right">
            <div class="hotel-features">
                <img src="YOUR-BED-ICON">
                <img src="YOUR-BED-ICON">
            </div>

            <img src="YOUR-HEART-ICON" class="fav-icon">
        </div>
    </div>

    <!-- DUPLICATE BELOW -->
    <div class="hotel-card">
        <img src="YOUR-HOTEL-IMAGE" class="hotel-img">

        <div class="hotel-info">
            <h3 class="hotel-name">LE GRAY BEIRUT</h3>
            <p class="price">FROM $500/NIGHT</p>

            <div class="stars">
                <img src="YOUR-STAR-ICON">
                <img src="YOUR-STAR-ICON">
                <img src="YOUR-STAR-ICON">
                <img src="YOUR-STAR-ICON">
                <img src="YOUR-STAR-ICON">
            </div>

            <button class="more-btn">Show more info</button>
        </div>

        <div class="icons-right">
            <div class="hotel-features">
                <img src="YOUR-BED-ICON">
                <img src="YOUR-BED-ICON">
            </div>

            <img src="YOUR-HEART-ICON" class="fav-icon">
        </div>
    </div>

</section>

<?php include 'footer.html'; ?>

</body>
</html>
