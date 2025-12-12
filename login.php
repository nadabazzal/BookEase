<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "hotel_management_system");
if (!$conn) {
    die("connection failed: " . mysqli_connect_error());
}


$error = "";
if (isset($_SESSION['login_error'])) {
    $error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   
    $email    = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($email === '' || $password === '') {
        $_SESSION['login_error'] = "Please fill in all fields.";
        header("Location: login.php");
        exit;
    }

  
    $email_safe = mysqli_real_escape_string($conn, $email);

    $sql    = "SELECT * FROM users WHERE email='$email_safe' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            // ✅ put user data in session (NOT the password)
            // change 'id' to your column name if needed
            $_SESSION['user_id']    = $user['user_id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role']  = $user['role'];

            // redirect based on role
            if ($user['role'] == "admin") {
                header("Location: admin.php");
            } if ($user['role'] == "housekeeper") {
                header("Location: housekeeper.php");
            } // normal user → auto-add favorite if exists
if (isset($_SESSION['pending_favorite_hotel'])) {

    $hotel_id = (int) $_SESSION['pending_favorite_hotel'];
    unset($_SESSION['pending_favorite_hotel']);

    $uid = (int) $_SESSION['user_id'];

    $check = "SELECT fav_id FROM favorites WHERE user_id=$uid AND hotel_id=$hotel_id";
    $res = mysqli_query($conn, $check);

    if ($res && mysqli_num_rows($res) === 0) {
        mysqli_query(
            $conn,
            "INSERT INTO favorites (user_id, hotel_id) VALUES ($uid, $hotel_id)"
        );
         $_SESSION['fav_success'] = "Hotel added to favorites successfully ✅";
    }
}


        } else {
            $_SESSION['login_error'] = "Wrong email or password.";
            header("Location: login.php");
            exit;
        }
    } else {
        $_SESSION['login_error'] = "Wrong email or password.";
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login to Your Account</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: "Open Sans", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI",
        sans-serif;
      min-height: 100vh;
      margin: 0;
      color: #ffffff;
      background-color: #1e4250;
    }

    .login-wrapper {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .login-card {
      background: rgba(1, 48, 63, 0.9);
      width: 100%;
      max-width: 720px;
      border-radius: 18px;
      padding: 36px 46px 30px;
      box-shadow: 0 18px 40px rgba(0, 0, 0, 0.4);
    }

    .login-card h1 {
      font-family: "Playfair Display", "Times New Roman", serif;
      font-size: 2.6rem;
      font-weight: 500;
      margin-bottom: 28px;
      letter-spacing: 0.03em;
    }

    .login-form label {
      display: block;
      font-size: 0.98rem;
      margin-bottom: 6px;
    }

    .login-form input {
      width: 100%;
      padding: 12px 20px;
      border-radius: 999px;
      border: none;
      outline: none;
      font-size: 0.98rem;
      margin-bottom: 22px;
      background-color: #f7f7f7;
      color: #222;
    }

    .login-form input:focus {
      box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.7);
    }

    .login-btn {
      display: inline-block;
      margin-left: auto;
      padding: 10px 42px;
      border-radius: 999px;
      border: none;
      background-color: #f4f4f4;
      color: #003c4f;
      font-weight: 600;
      font-size: 0.98rem;
      cursor: pointer;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .links {
      clear: both;
      margin-top: 40px;
      font-size: 0.98rem;
    }

    .link {
      display: block;
      color: #e1f2ff;
      text-decoration: underline;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="login-wrapper">
    <div class="login-card">
      <h1>Login to Your Account</h1>

      <?php if (!empty($error)) : ?>
        <p style="color:#ffb3b3; background:rgba(197,185,185,0.08); padding:10px; border-radius:8px; margin-bottom:15px;">
          <?php echo $error; ?>
        </p>
      <?php endif; ?>

      <form class="login-form" action="login.php" method="post" autocomplete="off">
        <label for="email">Email address</label>
        <input
          type="email"
          id="email"
          name="email"
          value=""
          autocomplete="off"
        />

        <label for="password">Password</label>
        <input
          type="password"
          id="password"
          name="password"
          value=""
          autocomplete="off"
        />

        <button type="submit" class="login-btn">Login</button>
      </form>

      <div class="links">
        <a href="signup.php" class="link">Don't have an account ? Sign Up</a>
      </div>
    </div>
  </div>

  <!-- Extra safety: clear any autofilled values on load -->
  <script>
    window.addEventListener('load', function () {
      document.getElementById('email').value = '';
      document.getElementById('password').value = '';
    });
  </script>
</body>
</html>