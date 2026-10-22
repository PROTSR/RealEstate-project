<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Create Account | DreamNest</title><link rel="stylesheet" href="css/style.css"></head><body><?php include 'includes/header.php'; ?><?php
require 'includes/config.php'; $error='';$success='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $name=trim($_POST['name']);$email=trim($_POST['email']);$phone=trim($_POST['phone']);$password=$_POST['password'];
 if(strlen($password)<6)$error="Password must be at least 6 characters.";
 else{
  $hash=hash('sha256',$password);$stmt=$conn->prepare("INSERT INTO users(name,email,phone,password) VALUES(?,?,?,?)");$stmt->bind_param("ssss",$name,$email,$phone,$hash);
  if($stmt->execute())$success="Account created. You can now login.";else $error="Email already registered.";
 }
}
?>
<section class="pagehero"><div class="container"><h1>Create Account</h1><p>Register to save properties and send enquiries.</p></div></section><section class="section"><div class="container" style="max-width:720px"><form class="form-card" method="post"><h2 style="color:var(--navy);margin-bottom:20px">Registration</h2><?php if($error):?><div class="notice"><?=$error?></div><?php endif;?><?php if($success):?><div class="message"><?=$success?></div><?php endif;?><div class="formgrid"><div><label>Name</label><input name="name" required></div><div><label>Phone</label><input name="phone"></div><div class="full"><label>Email</label><input type="email" name="email" required></div><div class="full"><label>Password</label><input type="password" name="password" minlength="6" required></div><div class="full"><button class="btn gold">Create Account</button></div></div></form></div></section><?php include 'includes/footer.php'; ?></body></html>