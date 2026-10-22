<?php
$price = ($p['listing_type']==='Rent') ? '₹'.number_format($p['price']).'/month' : '₹'.number_format($p['price']/100000,2).' Lakh';
?>
<article class="card"><div class="property-top"><img class="property-img" src="<?=htmlspecialchars($p['image'])?>" alt="<?=htmlspecialchars($p['title'])?>"><span class="badge">VERIFIED</span>
<form method="post" action="favorite.php" style="position:absolute;right:14px;top:14px"><input type="hidden" name="property_id" value="<?=$p['id']?>"><button class="fav" type="submit">♡</button></form></div>
<div class="card-body"><h3><?=htmlspecialchars($p['title'])?></h3><p class="muted">📍 <?=htmlspecialchars($p['city'])?></p><div class="price"><?=$price?></div><div class="meta"><span>🛏 <?=$p['bedrooms']?> Beds</span><span>🛁 <?=$p['bathrooms']?> Baths</span><span>📐 <?=$p['area']?> sq.ft.</span></div><div style="margin-top:17px"><a class="btn outline" href="property-details.php?id=<?=$p['id']?>">View Details</a></div></div></article>