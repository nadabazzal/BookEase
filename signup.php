<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Sign Up</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Fonts similar to the design -->
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

/* Center card */
.signup-wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

/* Card styles */
.signup-card {
  background: rgba(1, 48, 63, 0.9); /* dark teal, semi-transparent */
  width: 100%;
  max-width: 720px;
  border-radius: 18px;
  padding: 36px 46px 30px;
  box-shadow: 0 18px 40px rgba(0, 0, 0, 0.4);
}

/* Heading */
.signup-card h1 {
  font-family: "Playfair Display", "Times New Roman", serif;
  font-size: 2.6rem;
  font-weight: 500;
  margin-bottom: 28px;
  letter-spacing: 0.03em;
}

/* Form */
.signup-form label {
  display: block;
  font-size: 0.98rem;
  margin-bottom: 6px;
}

.signup-form input {
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

.signup-form input:focus {
  box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.7);
}

/* Button */
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
  transition: transform 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.save-btn:hover {
  background-color: #ffffff;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
  transform: translateY(-1px);
}





  </style>
</head>
<body>
  <div class="signup-wrapper">
    <div class="signup-card">
      <h1>Sign Up</h1>

      <form class="signup-form" action="#" method="post">
        <label for="email">Email address</label>
        <input type="email" id="email" name="email" required />

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />

        <label for="confirm-password">Confirm password</label>
        <input type="password" id="confirm-password" name="confirm-password" required />

        <button type="submit" class="save-btn">Save my password</button>
      </form>
    </div>
  </div>
</body>
</html>
