<?php
session_start();

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = (int) $_SESSION['user_id'];

// Connect to DB (procedural)
$conn = mysqli_connect('localhost', 'root', '', 'hotel_management_system');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// If this page also handles adding favorites (optional)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hotel_id'])) { //press the button
    $hotel_id = (int) $_POST['hotel_id'];

    // Check duplicate
    $check_sql = "SELECT id FROM favorites WHERE user_id = $user_id AND hotel_id = $hotel_id";
    $check_res = mysqli_query($conn, $check_sql);

    if ($check_res && mysqli_num_rows($check_res) == 0) {
        $insert_sql = "INSERT INTO favorites (user_id, hotel_id) VALUES ($user_id, $hotel_id)";
        if (mysqli_query($conn, $insert_sql)) {
            $_SESSION['fav_success'] = "Hotel added to favorites successfully ✅";
        } else {
            $_SESSION['fav_error'] = "Error adding favorite: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['fav_success'] = "This hotel is already in your favorites ⭐";
    }
}

// Get favorites with hotel info (including image)
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
        .flash-message { text-align:center; margin-top:80px; font-weight:700; }
        .flash-success { color:#0a7e2f; }
        .flash-error { color:#b00020; }
    </style>
</head>
<body>

<?php include 'navbar.html'; ?>

<!-- Flash messages -->
<?php if (isset($_SESSION['fav_success'])): ?>
    <div class="flash-message flash-success">
        <?php echo $_SESSION['fav_success']; unset($_SESSION['fav_success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['fav_error'])): ?>
    <div class="flash-message flash-error">
        <?php echo $_SESSION['fav_error']; unset($_SESSION['fav_error']); ?>
    </div>
<?php endif; ?>

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

            <button class="more-btn"
                onclick="window.location.href='hotelInfo.php?hotel_id=<?php echo $row['hotel_id']; ?>';">
                Show more info
            </button>
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
