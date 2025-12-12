<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "hotel_management_system");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$email = "";
$errors = array();
$success_msg = "";


$email_pattern = "/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   
    $email = isset($_POST['email']) ? trim($_POST['email']) : "";
    $password = isset($_POST['password']) ? $_POST['password'] : "";
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : "";

    if ($email === "") {
        $errors[] = "Email is required.";
    } elseif (!preg_match($email_pattern, $email)) {
        $errors[] = "Please enter a valid email address.";
    }

    /* --- Password validation (simple rules) --- */
    // - At least 8 characters
    // - At least one letter
    // - At least one number
    if ($password === "") {
        $errors[] = "Password is required.";
    } else {
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters.";
        } elseif (!preg_match('/[A-Za-z]/', $password)) {
            $errors[] = "Password must contain at least one letter.";
        } elseif (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one number.";
        }
    }

    /* --- Confirm password --- */
    if ($confirm_password === "") {
        $errors[] = "Please confirm your password.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

   
    if (empty($errors)) {
        $email_safe = mysqli_real_escape_string($conn, $email);

        
        $sql_check = "SELECT user_id FROM users WHERE email = '$email_safe' LIMIT 1";
        $result_check = mysqli_query($conn, $sql_check);

        if ($result_check && mysqli_num_rows($result_check) > 0) {
            $errors[] = "This email is already registered.";
        }
    }

   
    if (empty($errors)) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        $sql_insert = "INSERT INTO users (email, password, role)
                       VALUES ('$email_safe', '$password_hashed', 'user')";

        if (mysqli_query($conn, $sql_insert)) {
            // ✅ log the user in immediately
    $new_user_id = mysqli_insert_id($conn);
    $_SESSION['user_id'] = (int)$new_user_id;
    $_SESSION['user_email'] = $email_safe;
    $_SESSION['user_role'] = 'user';

    // ✅ auto-add pending favorite after signup
    if (isset($_SESSION['pending_favorite_hotel'])) {

        $hotel_id = (int) $_SESSION['pending_favorite_hotel'];
        unset($_SESSION['pending_favorite_hotel']);

        $uid = (int) $_SESSION['user_id'];

        $check = "SELECT fav_id FROM favorites WHERE user_id=$uid AND hotel_id=$hotel_id";
        $res = mysqli_query($conn, $check);

        if ($res && mysqli_num_rows($res) === 0) {
            mysqli_query($conn, "INSERT INTO favorites (user_id, hotel_id) VALUES ($uid, $hotel_id)");
            $_SESSION['fav_success'] = "Hotel added to favorites successfully ✅";
        }
    }

    // ✅ always go to favorites page after signup
    header("Location: favorites.php");
    exit;

} else {
    $errors[] = "Error while saving your account.Please try again ";
}
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Sign Up</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

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
  color: #ffffff;
  background-color: #1e4250;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}


.signup-wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.signup-card {
  background: rgba(1, 48, 63, 0.9);
  width: 100%;
  max-width: 720px;
  border-radius: 18px;
  padding: 36px 46px 30px;
  box-shadow: 0 18px 40px rgba(0, 0, 0, 0.4);
}

.signup-card h1 {
  font-family: "Playfair Display";
  font-size: 2.6rem;
  margin-bottom: 28px;
}

.messages {
  margin-bottom: 18px;
  font-size: 0.95rem;
}
.error-msg {
  background: rgba(255, 80, 80, 0.15);
  border: 1px solid rgba(255, 80, 80, 0.7);
  color: #ffd4d4;
  padding: 10px 14px;
  border-radius: 8px;
  margin-bottom: 8px;
}
.success-msg {
  background: rgba(80, 200, 120, 0.15);
  border: 1px solid rgba(80, 200, 120, 0.7);
  color: #d7ffe5;
  padding: 10px 14px;
  border-radius: 8px;
}

.signup-form label {
  display: block;
  margin-bottom: 6px;
}

.signup-form input {
  width: 100%;
  padding: 12px 20px;
  border-radius: 999px;
  border: none;
  margin-bottom: 22px;
}

.save-btn {
  display: inline-block;
  margin-left: auto;
  padding: 10px 40px;
  border-radius: 999px;
  border: none;
  background-color: #f4f4f4;
  color: #003c4f;
  font-weight: 600;
  font-size: 0.98rem;
  cursor: pointer;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.save-btn:hover {
  background-color: #ffffff;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
}
  </style>
</head>
<body>
  <div class="signup-wrapper">
    <div class="signup-card">
      <h1>Sign Up</h1>

      <div class="messages">
        <?php
        if (!empty($errors)) {
            foreach ($errors as $err) {
                echo '<div class="error-msg">' . htmlspecialchars($err, ENT_QUOTES, 'UTF-8') . '</div>';
            }
        }
        if ($success_msg !== "") {
            echo '<div class="success-msg">' . htmlspecialchars($success_msg, ENT_QUOTES, 'UTF-8') . '</div>';
        }
        ?>
      </div>

      <form class="signup-form" action="signup.php" method="post">
        <label for="email">Email address</label>
        <input
          type="text"
          id="email"
          name="email"
          required
          value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"
        />

        <label for="password">Password</label>
        <input
          type="password"
          id="password"
          name="password"
          required
        />

        <label for="confirm_password">Confirm password</label>
        <input
          type="password"
          id="confirm_password"
          name="confirm_password"
          required
        />

        <button type="submit" class="save-btn">Save my password</button>
       

<p style="margin-top: 18px; margin-left:150px;font-size: 0.95rem; text-align: center;">
  Already have an account? 
  <a href="login.php" style="color: #9fd7ff; text-decoration: underline;">
    Sign in
  </a>
</p>
      </form>
    </div>
  </div>
</body>
</html>