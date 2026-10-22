<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login | DreamNest</title><link rel="stylesheet" href="css/style.css"></head><body><?php include 'includes/header.php'; ?><?php
require 'includes/config.php'; $error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $email=trim($_POST['email']);$password=$_POST['password'];
 $stmt=$conn->prepare("SELECT * FROM users WHERE email=?");$stmt->bind_param("s",$email);$stmt->execute();$u=$stmt->get_result()->fetch_assoc();
 if($u && hash('sha256',$password)===$u['password']){
   if($u['role']==='admin'){$_SESSION['admin_id']=$u['id'];$_SESSION['admin_name']=$u['name'];header("Location: admin/index.php");exit;}
   $_SESSION['user_id']=$u['id'];$_SESSION['user_name']=$u['name'];header("Location: dashboard.php");exit;
 } else $error="Invalid email or password.";
}
?>
<section class="pagehero"><div class="container"><h1>Login</h1><p>Access your DreamNest account.</p></div></section><section class="section"><div class="container" style="max-width:560px"><form class="form-card" method="post"><h2 style="color:var(--navy);margin-bottom:20px">Account Login</h2><?php if($error):?><div class="notice"><?=$error?></div><?php endif;?><label>Email</label><input type="email" name="email" required><br><br><label>Password</label><input type="password" name="password" required><br><br><button class="btn gold">Login</button><p class="muted" style="margin-top:15px">Admin demo: admin@dreamnest.com / admin123 &nbsp; | &nbsp; <a href="admin/login.php">Open Admin Login</a></p></form></div></section><?php include 'includes/footer.php'; ?></body></html>