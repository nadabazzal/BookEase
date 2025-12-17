<?php
session_start();
date_default_timezone_set("Asia/Beirut");

$conn = mysqli_connect('localhost', 'root', '', 'hotel_management_system');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = (int) $_SESSION['user_id'];


$sql = "SELECT role FROM users WHERE user_id = $user_id LIMIT 1";
$res = mysqli_query($conn, $sql);

if (!$res || mysqli_num_rows($res) === 0) {
  
    header("Location: login.php");
    exit();
}

$row = mysqli_fetch_assoc($res);
$role = $row['role'];


if ($role !== 'housekeeper') {
    header("Location: index.php");  
    exit();
}
$housekeeper_id = $user_id;


$housekeeper_name = '';
$name_sql = "SELECT first_name FROM users WHERE user_id = $housekeeper_id";
$name_res = mysqli_query($conn, $name_sql);
if ($name_res && mysqli_num_rows($name_res) === 1) {
    $user_row = mysqli_fetch_assoc($name_res);
    $housekeeper_name = $user_row['first_name'];
}


$allowed_status = ['all', 'pending', 'in_progress', 'done'];
$status_filter = "all";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['status'])) {
    if (in_array($_POST['status'], $allowed_status, true)) {
        $status_filter = $_POST['status'];
    }
}




$selected = null;
$selected_hs_id = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hs_id'])) {
    $selected_hs_id = (int) $_POST['hs_id'];

    
    if (isset($_POST['update_status'])) {
        $new_status = $_POST['update_status'];

        if ($new_status === 'in_progress' || $new_status === 'done') {
            $upd_sql = "
                UPDATE housekeeping_requests
                SET status = '$new_status'
                WHERE hs_id = $selected_hs_id
                  AND assign_to = $housekeeper_id
                LIMIT 1
            ";
            mysqli_query($conn, $upd_sql);
        }
    }

    
    $detail_sql = "
        SELECT 
            hr.*,
            s.service_name,
            s.description AS service_desc,
            r.room_nb
        FROM housekeeping_requests hr
        JOIN services s ON hr.service_id = s.service_id
        JOIN rooms r    ON hr.room_id   = r.room_id
        WHERE hr.hs_id = $selected_hs_id
          AND hr.assign_to = $housekeeper_id
        LIMIT 1
    ";

    $detail_res = mysqli_query($conn, $detail_sql);

    if ($detail_res && mysqli_num_rows($detail_res) === 1) {
        $selected = mysqli_fetch_assoc($detail_res);
    }
}


$status_sql = ($status_filter === 'all')? "" : " AND hr.status = '$status_filter'";

$list_sql = "
    SELECT 
        hr.hs_id,
        hr.created_at,
        hr.status,
        s.service_name,
        r.room_nb
    FROM housekeeping_requests hr
    JOIN services s ON hr.service_id = s.service_id
    JOIN rooms r ON hr.room_id = r.room_id
    WHERE hr.assign_to = $housekeeper_id
    $status_sql
    ORDER BY hr.created_at DESC
";




$list_res = mysqli_query($conn, $list_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>BookEase – Housekeeper Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  
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

   
    .page-wrapper {
      max-width: 1100px;
      margin: 40px auto 60px;
      padding: 0 20px;
    }

    
    .welcome-block {
      margin-bottom: 20px;
    }

    .welcome-title {
      font-family: "Playfair Display", serif;
      font-size: 32px;
      margin-bottom: 4px;
    }

    .welcome-meta {
      font-size: 14px;
      color: #d3e0e8;
      line-height: 1.7;
    }

   
    .card {
      background: #0e3144;
      border-radius: 22px;
      padding: 24px 28px;
      box-shadow: 0 10px 28px rgba(0, 0, 0, 0.25);
      margin-bottom: 30px;
    }

   
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
  gap: 12px; 
}


    .tasks-tabs button { 
      display: inline-flex;
      
      align-items: center;
      justify-content: center;
      gap: 18px;
      margin-bottom: 16px;
      font-size: 14px;
      border-color: #f2e6c9;
      border-radius: 15px;
      padding: 8px 16px;
      background: none;
      cursor: pointer;
      color: #f2e6c9;
    }

  

    
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

    .guest-item button {
      background: none;
      border: none;
      padding: 0;
      margin-left: 6px;
      font-size: 15px;
      text-decoration: underline;
      color: inherit;
      cursor: pointer;
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
    .white-text {
    white-space: pre-line;
}

  </style>
</head>

<body>
<?php include 'navbar.html'; ?>
<br><br><br><br>

<main class="page-wrapper">

 
  <section class="welcome-block">
    <h1 class="welcome-title">Welcome, <?php echo htmlspecialchars($housekeeper_name); 
    ?></h1>
    <p class="welcome-meta">
      Shift: 8:00 AM – 4:00 PM<br>
      Today: <?php  
           echo date("l, d F Y");
?>

    </p>
  </section>

 
  <section class="card">
    <div class="tasks-header">
      <div class="chip">📍 Beirut • Housekeeping</div>
    </div>

    <div class="tasks-hotel">Assigned Requests</div>

    <div class="tasks-tabs">

    <form method="POST">
    <button name="status" value="all">All</button>
</form>
      <form method="POST">
    <button name="status" value="pending">Pending</button>
</form>

<form method="POST">
    <button name="status" value="in_progress">In Progress</button>
</form>

<form method="POST">
    <button name="status" value="done">Done</button>
</form>

    </div>

    <div class="guest-list">
      <?php
      if ($list_res && mysqli_num_rows($list_res) > 0):
          while ($row = mysqli_fetch_assoc($list_res)):
      ?>
        <form method="POST">
          <div class="guest-item">
            <div class="guest-icon">🛏</div>
           
            <input type="hidden" name="hs_id" value="<?php echo (int)$row['hs_id']; ?>">
            <button type="submit">
              Room <?php echo htmlspecialchars($row['room_nb']); ?>
              – <?php echo htmlspecialchars($row['service_name']); ?>
              (<?php echo htmlspecialchars($row['status']); ?>)
            </button>
          </div>
        </form>
      <?php
          endwhile;
      else:
          echo "<p>No housekeeping requests assigned.</p>";
      endif;
      ?>
    </div>
  </section>

  
  <?php if ($selected): ?>
  <section class="card" id="details-card">

    <h2 class="details-title">
      Room <?php echo htmlspecialchars($selected['room_nb']); ?>
      – <?php echo htmlspecialchars($selected['service_name']); ?>
    </h2>

    <div class="details-grid">
      <div><span class="label">Request ID:</span> HK-<?php echo $selected['hs_id']; ?></div>
      <div><span class="label">Status:</span> <?php echo htmlspecialchars($selected['status']); ?></div>
      <div><span class="label">Created at:</span> <?php echo htmlspecialchars($selected['created_at']); ?></div>
    </div>

    <div class="divider"></div>

    <div class="services-title">Service & Notes</div>

    <p class="services-text">
      <strong>Service description:</strong>
      <?php echo nl2br(htmlspecialchars($selected['service_desc'])); ?><br><br>

      <strong>Notes:</strong>
   
      <?php echo nl2br(htmlspecialchars($selected['notes'])); ?>
    </p>

    <div class="actions-row">
     
      <form method="POST">
        <input type="hidden" name="hs_id" value="<?php echo (int)$selected['hs_id']; ?>">
        <button class="btn-pill" type="submit" name="update_status" value="in_progress">
          Start cleaning
        </button>
      </form>

      
      <form method="POST">
        <input type="hidden" name="hs_id" value="<?php echo (int)$selected['hs_id']; ?>">
        <button class="btn-pill" type="submit" name="update_status" value="done">
          Mark as Cleaned
        </button>
      </form>
    </div>
  </section>
  <?php endif; ?>

</main>

<?php
mysqli_close($conn);
include 'footer.html';
?>
</body>
</html>
