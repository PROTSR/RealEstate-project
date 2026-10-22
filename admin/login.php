<?php
session_start();
require '../includes/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id,name,email,password,role FROM users WHERE email=? AND role='admin' LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();

    if ($admin && hash('sha256', $password) === $admin['password']) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        header("Location: index.php");
        exit;
    }
    $error = "Invalid admin email or password.";
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Login | DreamNest</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>
<section class="pagehero">
  <div class="container">
    <h1>DreamNest Admin</h1>
    <p>Secure administrator login.</p>
  </div>
</section>
<section class="section">
  <div class="container" style="max-width:560px">
    <form class="form-card" method="post">
      <h2 style="color:var(--navy);margin-bottom:20px">Administrator Login</h2>
      <?php if ($error): ?><div class="notice"><?=htmlspecialchars($error)?></div><br><?php endif; ?>
      <label>Email</label>
      <input type="email" name="email" value="admin@dreamnest.com" required>
      <br><br>
      <label>Password</label>
      <input type="password" name="password" required>
      <br><br>
      <button class="btn gold" type="submit">Login to Admin Panel</button>
      <p class="muted" style="margin-top:15px">
        Demo: admin@dreamnest.com / admin123
      </p>
    </form>
  </div>
</section>
</body>
</html>