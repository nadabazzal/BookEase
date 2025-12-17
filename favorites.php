<?php
session_start();


if (!isset($_SESSION['user_id'])) {

    if (isset($_POST['hotel_id'])) {
        $_SESSION['pending_favorite_hotel'] = (int) $_POST['hotel_id'];
    }

    header("Location: login.php");
    exit();
}



$user_id = (int) $_SESSION['user_id'];


$conn = mysqli_connect('localhost', 'root', '', 'hotel_management_system');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hotel_id'])) {

    $hotel_id = (int) $_POST['hotel_id'];

    $check_sql = "SELECT fav_id FROM favorites WHERE user_id = $user_id AND hotel_id = $hotel_id";
    $check_res = mysqli_query($conn, $check_sql);

    if ($check_res && mysqli_num_rows($check_res) == 0) {

        $insert_sql = "INSERT INTO favorites (user_id, hotel_id) VALUES ($user_id, $hotel_id)";
        mysqli_query($conn, $insert_sql);

        echo "<script>alert('Hotel added to favorites successfully ✅');</script>";

    } else {
        echo "<script>alert('This hotel is already in your favorites ⭐');</script>";
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_hotel_id'])) {

    $remove_hotel_id = (int) $_POST['remove_hotel_id'];

    $del_sql = "DELETE FROM favorites 
                WHERE user_id = $user_id AND hotel_id = $remove_hotel_id LIMIT 1";

    mysqli_query($conn, $del_sql);

    echo "<script>
            alert('Hotel removed from favorites ❌');
            window.location.href = 'favorites.php';
          </script>";
    exit();
}



$sql = "
    SELECT h.hotel_id,
           h.hotel_name,
           h.city,
           h.country,
           h.base_price,
           h.image
    FROM favorites f
    JOIN hotels h ON f.hotel_id = h.hotel_id
    WHERE f.user_id = $user_id
";
$favorites = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Favorite Hotels</title>
    <link rel="stylesheet" href="favorites.css">

    <style>
 
.favorite-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background-color: transparent;
    border: none;
    border-radius: 50%;

    cursor: pointer;
    padding: 0;
    margin-left: 10px;

    transition: background-color 0.25s ease;
}


.favorite-btn i {
    font-size: 22px;
    color: #999;                
    transition: color 0.25s ease, transform 0.2s ease;
}


.favorite-btn:hover {
    background-color: #295066;
}

.favorite-btn:hover i {
    color: #ffffff;
    transform: scale(1.15);
}


.favorite-btn:active i {
    transform: scale(0.95);
}


.favorite-btn.active i {
    color: #e74c3c;  
}

h2{
    font-family: 'Playfair Display', serif;
    color: white;
}


    </style>
</head>
<body>

<?php include 'navbar.html'; ?>


<br><br><br><br>
<h2 style="text-align:center; margin-top:20px;">My Favorite Hotels</h2>

<section class="hotel-list">
<?php
if ($favorites && mysqli_num_rows($favorites) > 0):
    while ($row = mysqli_fetch_assoc($favorites)):
?>
    <div class="hotel-card">
        <img src="<?php echo htmlspecialchars($row['image']); ?>" class="hotel-img" alt="Hotel">

        <div class="hotel-info">
            <h3 class="hotel-name"><?php echo htmlspecialchars($row['hotel_name']); ?></h3>
            <p class="hotel-location">
                <?php echo htmlspecialchars($row['city'] . ', ' . $row['country']); ?>
            </p>

            <?php if (!empty($row['base_price'])): ?>
                <p class="price">From $<?php echo htmlspecialchars($row['base_price']); ?>/night</p>
            <?php endif; ?>

         

            <form action="info.php" method="POST" style="display:inline-block; margin-left:10px;">
    <input type="hidden" name="hotel_id" value="<?php echo $row['hotel_id']; ?>">
    <button type="submit" class="more-btn">
        Show more info
    </button>
</form>
           

           <form method="POST" style="display:inline-block; margin-left:10px;">
    <input type="hidden" name="remove_hotel_id" value="<?php echo (int)$row['hotel_id']; ?>">
    <button class="favorite-btn" type="submit">
        <i class="fa-solid fa-bookmark"></i>
    </button>
</form>


                
            </form>
        </div>
    </div>
<?php
    endwhile;
else:
    echo "<p style='text-align:center; margin-top:20px;'>You have no favorite hotels yet.</p>";
endif;

mysqli_close($conn);
?>
</section>

<?php include 'footer.html'; ?>

</body>
</html>
