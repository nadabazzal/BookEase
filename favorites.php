<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotels</title>
    <link rel="stylesheet" href="favorites.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    />

    <!-- Small extra styles for the favorite button -->
    <style>
        .favorite-btn {
            margin-top: 80px;
            padding: 6px 10px;
            border-radius: 20px;
            border: 1px solid #cba135;
            background-color: transparent;
            color: #cba135;
            font-size: 12px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .favorite-btn i {
            font-size: 13px;
        }

        .favorite-btn:hover {
            background-color: #cba135;
            color: #1b2b34;
        }
    </style>
</head>
<body>

<?php include 'navbar.html'; ?>

<br><br><br><br><br>


<!-- FILTER HEADER REMOVED -->

<section class="hotel-list">

    <!-- HOTEL CARD 1 -->
    <div class="hotel-card">
        <img src="images/hotel.png" class="hotel-img">

        <div class="hotel-info">
            <h3 class="hotel-name">LE GRAY BEIRUT</h3>
            <p class="price">FROM $500/NIGHT</p>
 <div class="hotel-stars">★★★★★</div>

            <button class="more-btn" onclick="window.location.href='hotelInfo.php';">
                Show more info
            </button>
        </div>

        <div class="icons-right">
            <div class="room-icons">🛏️🛏️🛏️</div>

            <!-- Favorite / Unfavorite button -->
            <button class="favorite-btn">
                <i class="fa-solid fa-heart"></i>
                
            </button>
        </div>
    </div>

    <!-- HOTEL CARD 2 -->
    <div class="hotel-card">
        <img src="images/hotel.png" class="hotel-img">

        <div class="hotel-info">
            <h3 class="hotel-name">LE GRAY BEIRUT</h3>
            <p class="price">FROM $500/NIGHT</p>

            <div class="stars">
                ★★★★★
            </div>

            <button class="more-btn" onclick="window.location.href='hotelInfo.php';">
                Show more info
            </button>
        </div>

        <div class="icons-right">
            
                <div class="room-icons">🛏️🛏️🛏️</div>
            </div>

            <!-- Favorite / Unfavorite button -->
            <button class="favorite-btn">
                <i class="fa-solid fa-heart"></i>
                
            </button>
        </div>
    </div>

</section>

<?php include 'footer.html'; ?>

<script>
    // When clicking "Unfavorite", remove that hotel card from the list
    document.querySelectorAll('.favorite-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const card = this.closest('.hotel-card');
            if (card) {
                card.remove();
            }
        });
    });
</script>

</body>
</html>
