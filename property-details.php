<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Property Details | DreamNest</title><link rel="stylesheet" href="css/style.css"></head><body><?php include 'includes/header.php'; ?><?php
require 'includes/config.php';
$id=(int)($_GET['id']??0);
$stmt=$conn->prepare("SELECT * FROM properties WHERE id=?");
$stmt->bind_param("i",$id);$stmt->execute();$p=$stmt->get_result()->fetch_assoc();
if(!$p){header("Location: properties.php");exit;}
$price=($p['listing_type']==='Rent')?'₹'.number_format($p['price']).'/month':'₹'.number_format($p['price']/100000,2).' Lakh';
?>
<section class="pagehero"><div class="container"><div class="crumb">Home / Properties / Details</div><h1><?=htmlspecialchars($p['title'])?></h1><p><?=htmlspecialchars($p['city'])?> • <?=htmlspecialchars($p['type'])?></p></div></section>
<section class="section"><div class="container"><div class="detail"><div><img class="gallery-main" src="<?=htmlspecialchars($p['image'])?>" alt=""><div class="notice" style="margin-top:15px">Property ID: #<?=$p['id']?> • Status: <?=htmlspecialchars($p['status'])?></div></div>
<div class="detail-panel"><span class="badge" style="position:static;display:inline-block">VERIFIED</span><h2 style="color:var(--navy);font-size:32px;margin-top:14px"><?=htmlspecialchars($p['title'])?></h2><p class="muted">📍 <?=htmlspecialchars($p['address'])?>, <?=htmlspecialchars($p['city'])?></p><div class="price-big"><?=$price?></div>
<div class="feature-list"><div>🛏 <b><?=$p['bedrooms']?></b> Bedrooms</div><div>🛁 <b><?=$p['bathrooms']?></b> Bathrooms</div><div>📐 <b><?=$p['area']?></b> sq.ft.</div><div>🚗 <b><?=$p['parking']?></b> Parking</div><div>🏠 <?=htmlspecialchars($p['type'])?></div><div>📌 <?=htmlspecialchars($p['listing_type'])?></div></div>
<a class="btn gold" href="contact.php?property_id=<?=$p['id']?>" style="width:100%;text-align:center">Request Property Visit</a><form method="post" action="favorite.php"><input type="hidden" name="property_id" value="<?=$p['id']?>"><button class="btn outline" style="width:100%;margin-top:9px">♡ Save Property</button></form></div></div></div></section>
<section class="section"><div class="container"><div class="card"><div class="card-body"><h2 style="color:var(--navy)">Property Description</h2><p class="muted" style="margin-top:10px"><?=nl2br(htmlspecialchars($p['description']))?></p></div></div></div></section><?php include 'includes/footer.php'; ?></body></html>