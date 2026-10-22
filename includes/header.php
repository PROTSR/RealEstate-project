<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<div class="topbar"><div class="container"><span>📍 Ahmedabad • Gujarat, India</span><span>Mon–Sat: 9:00 AM – 7:00 PM &nbsp; | &nbsp; +91 98765 43210</span></div></div>
<header class="navbar"><div class="container nav">
<a class="logo" href="index.php">Dream<span>Nest</span></a>
<button class="mobile">☰</button>
<nav class="links">
<a href="index.php">Home</a>
<a href="properties.php">Properties</a>
<a href="buy.php">Buy</a>
<a href="rent.php">Rent</a>
<a href="sell.php">Sell</a>
<a href="agents.php">Agents</a>
<a href="about.php">About</a>
<a href="contact.php">Contact</a>
</nav>
<div class="nav-actions">
<?php if(isset($_SESSION['user_id'])): ?>
<a class="btn outline" href="dashboard.php">Dashboard</a>
<a class="btn gold" href="logout.php">Logout</a>
<?php else: ?>
<a class="btn outline" href="login.php">Login</a>
<a class="btn gold" href="register.php">Create Account</a>
<?php endif; ?>
</div></div></header>