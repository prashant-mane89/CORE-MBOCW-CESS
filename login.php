<?php 
$password = md5('superadmin');
//echo '<pre>'; print_r($password); exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MBOCWCESS Portal Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
    }

    body {
      background: #f8f9fa;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      display: flex;
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      overflow: hidden;
      max-width: 900px;
      width: 100%;
    }

    .form-section {
      flex: 1;
      padding: 50px;
    }

    .form-section h1 {
      font-size: 32px;
      font-weight: 700;
      margin-bottom: 10px;
      color: #343a40;
    }

    .form-section p {
      font-size: 14px;
      color: #666;
      margin-bottom: 30px;
    }

    .form-section input {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }

    .form-section button {
      width: 100%;
      padding: 12px;
      border: none;
      background: linear-gradient(to right, #007bff, #339af0);
      color: white;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .image-section {
      flex: 1;
      background: #e9ecef;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .image-section img {
      max-width: 100%;
      height: auto;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        max-width: 90%;
      }

      .image-section {
        padding: 10px;
      }

      .form-section {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="form-section">
      <h1>MBOCWCESS Portal</h1>
      <p>MBOCW CESS</p>
      <form action="auth.php" method="post">
        <input type="email" name="email" placeholder="Email Address" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit">Log in</button>
      </form>
    </div>
    <div class="image-section">
        <img src="http://localhost/CORE-MBOCW-CESS/assets/img/mbocwcess-login.png" alt="POS Illustration" />
    </div>
  </div>
</body>
</html>
