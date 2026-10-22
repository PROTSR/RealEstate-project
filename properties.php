<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Properties | DreamNest</title><link rel="stylesheet" href="css/style.css"></head><body><?php include 'includes/header.php'; ?><?php
require 'includes/config.php';

$search = trim($_GET['search'] ?? '');
$type = trim($_GET['type'] ?? '');
$listing = trim($_GET['listing_type'] ?? '');
$min_beds = (int)($_GET['min_beds'] ?? 0);

$sql = "SELECT * FROM properties WHERE status='Available'";
$params = [];
$types = "";

if ($search !== '') {
    $sql .= " AND (title LIKE ? OR city LIKE ? OR address LIKE ? OR type LIKE ?)";
    $term = "%".$search."%";
    $params[] = $term; $params[] = $term; $params[] = $term; $params[] = $term;
    $types .= "ssss";
}
if ($type !== '') {
    $sql .= " AND type=?";
    $params[] = $type;
    $types .= "s";
}
if ($listing !== '') {
    $sql .= " AND listing_type=?";
    $params[] = $listing;
    $types .= "s";
}
if ($min_beds > 0) {
    $sql .= " AND bedrooms>=?";
    $params[] = $min_beds;
    $types .= "i";
}

$sql .= " ORDER BY created_at DESC, id DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Search query error: " . htmlspecialchars($conn->error));
}
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<section class="pagehero">
<div class="container">
  <div class="crumb">Home / Properties</div>
  <h1>Explore Properties</h1>
  <p>Search properties by city, locality, title, type, listing and bedrooms.</p>
</div>
</section>

<section class="section">
<div class="container">
<form class="filterbar" method="get" action="properties.php">
  <input name="search" value="<?=htmlspecialchars($search)?>" placeholder="Search city, locality or property">
  <select name="type">
    <option value="">All Types</option>
    <?php foreach(['House','Apartment','Villa','Commercial','Plot'] as $t): ?>
      <option value="<?=htmlspecialchars($t)?>" <?=$type===$t?'selected':''?>><?=$t?></option>
    <?php endforeach; ?>
  </select>
  <select name="listing_type">
    <option value="">Buy / Rent</option>
    <option value="Buy" <?=$listing==='Buy'?'selected':''?>>Buy</option>
    <option value="Rent" <?=$listing==='Rent'?'selected':''?>>Rent</option>
  </select>
  <select name="min_beds">
    <option value="">Bedrooms</option>
    <?php foreach([1,2,3,4,5] as $b): ?>
      <option value="<?=$b?>" <?=$min_beds===$b?'selected':''?>><?=$b?>+ Beds</option>
    <?php endforeach; ?>
  </select>
  <button class="btn gold" type="submit">Search</button>
  <a class="btn outline" href="properties.php">Reset</a>
</form>

<?php if ($search !== '' || $type !== '' || $listing !== '' || $min_beds > 0): ?>
  <div class="notice" style="margin:18px 0">
    Showing results for:
    <?=htmlspecialchars($search ?: 'all properties')?>
    <?php if($type): ?> • <?=htmlspecialchars($type)?><?php endif; ?>
    <?php if($listing): ?> • <?=htmlspecialchars($listing)?><?php endif; ?>
    <?php if($min_beds): ?> • <?=$min_beds?>+ bedrooms<?php endif; ?>
  </div>
<?php endif; ?>

<div class="grid3">
<?php if ($result->num_rows > 0): ?>
  <?php while($p=$result->fetch_assoc()): include 'includes/property-card.php'; endwhile; ?>
<?php else: ?>
  <div class="notice" style="grid-column:1/-1">
    No properties found. Try another city, property type or filter.
  </div>
<?php endif; ?>
</div>
</div>
</section><?php include 'includes/footer.php'; ?></body></html>