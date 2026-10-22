<?php 
require 'auth.php';
require '../includes/config.php';

// Fetch distinct cities for dropdown
$cities_res = $conn->query("SELECT DISTINCT city FROM properties WHERE city != '' ORDER BY city ASC");
$cities = [];
if ($cities_res) {
    while ($c = $cities_res->fetch_assoc()) {
        $cities[] = $c['city'];
    }
}

// Filter inputs
$q_search = trim($_GET['q'] ?? '');
$f_type = trim($_GET['type'] ?? '');
$f_listing = trim($_GET['listing_type'] ?? '');
$f_city = trim($_GET['city'] ?? '');

$where = [];
$params = [];
$types = "";

if ($q_search !== '') {
    if (is_numeric($q_search)) {
        $where[] = "(id = ? OR title LIKE ? OR city LIKE ? OR address LIKE ?)";
        $params[] = (int)$q_search;
        $like_term = "%" . $q_search . "%";
        $params[] = $like_term;
        $params[] = $like_term;
        $params[] = $like_term;
        $types .= "isss";
    } else {
        $where[] = "(title LIKE ? OR city LIKE ? OR address LIKE ?)";
        $like_term = "%" . $q_search . "%";
        $params[] = $like_term;
        $params[] = $like_term;
        $params[] = $like_term;
        $types .= "sss";
    }
}

if ($f_type !== '') {
    $where[] = "type = ?";
    $params[] = $f_type;
    $types .= "s";
}

if ($f_listing !== '') {
    $where[] = "listing_type = ?";
    $params[] = $f_listing;
    $types .= "s";
}

if ($f_city !== '') {
    $where[] = "city = ?";
    $params[] = $f_city;
    $types .= "s";
}

$sql = "SELECT * FROM properties";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$r = $stmt->get_result();

// Count total properties in DB
$total_count_res = $conn->query("SELECT COUNT(*) as total FROM properties");
$total_in_db = $total_count_res->fetch_assoc()['total'];
$matching_count = $r->num_rows;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Properties - DreamNest Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-filter-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid #e0e0e0;
        }
        .admin-filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 15px;
            align-items: end;
        }
        .admin-filter-grid label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #0F3D3E;
            margin-bottom: 6px;
        }
        .admin-filter-grid input,
        .admin-filter-grid select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            background-color: #fff;
        }
        .admin-filter-actions {
            display: flex;
            gap: 10px;
        }
        .filter-stats-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            font-size: 14px;
            color: #555;
        }
        .badge-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-available { background-color: #e6f4ea; color: #137333; }
        .badge-sold { background-color: #fce8e6; color: #c5221f; }
        .badge-rented { background-color: #feefc3; color: #b06000; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="pagehero">
        <div class="container">
            <h1>Manage Properties</h1>
            <p>Search, filter, update and delete database properties easily.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <a class="btn gold" href="add-property.php">+ Add New Property</a>
            </div>

            <!-- Admin Property Filter Form -->
            <div class="admin-filter-card">
                <form method="get" action="properties.php">
                    <div class="admin-filter-grid">
                        <div style="grid-column: span 2;">
                            <label>Search Keyword / Property ID</label>
                            <input type="text" name="q" placeholder="Search Title, ID (#80), Address..." value="<?= htmlspecialchars($q_search) ?>">
                        </div>
                        <div>
                            <label>Property Type</label>
                            <select name="type">
                                <option value="">All Types</option>
                                <?php foreach (['House', 'Apartment', 'Villa', 'Commercial', 'Plot'] as $t): ?>
                                    <option value="<?= $t ?>" <?= ($f_type === $t) ? 'selected' : '' ?>><?= $t ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label>Listing Mode</label>
                            <select name="listing_type">
                                <option value="">All Modes</option>
                                <option value="Buy" <?= ($f_listing === 'Buy') ? 'selected' : '' ?>>Buy</option>
                                <option value="Rent" <?= ($f_listing === 'Rent') ? 'selected' : '' ?>>Rent</option>
                            </select>
                        </div>
                        <div>
                            <label>City</label>
                            <select name="city">
                                <option value="">All Cities</option>
                                <?php foreach ($cities as $c): ?>
                                    <option value="<?= htmlspecialchars($c) ?>" <?= ($f_city === $c) ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="admin-filter-actions">
                            <button type="submit" class="btn gold" style="flex:1;">Filter</button>
                            <?php if ($q_search !== '' || $f_type !== '' || $f_listing !== '' || $f_city !== ''): ?>
                                <a href="properties.php" class="btn outline" style="padding: 10px 14px;">Reset</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Filter Results Stats Bar -->
            <div class="filter-stats-bar">
                <div>
                    Showing <strong><?= $matching_count ?></strong> property listing<?= ($matching_count !== 1) ? 's' : '' ?>
                    <?php if ($matching_count !== $total_in_db): ?>
                        (filtered from <strong><?= $total_in_db ?></strong> total properties)
                    <?php endif; ?>
                </div>
            </div>

            <!-- Table of Properties -->
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Mode</th>
                            <th>City</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th style="text-align: center; width: 160px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($r->num_rows > 0): ?>
                            <?php while ($p = $r->fetch_assoc()): ?>
                                <?php 
                                    $statusClass = 'badge-available';
                                    if ($p['status'] === 'Sold') $statusClass = 'badge-sold';
                                    if ($p['status'] === 'Rented') $statusClass = 'badge-rented';
                                ?>
                                <tr>
                                    <td><strong>#<?= $p['id'] ?></strong></td>
                                    <td>
                                        <a href="../property-details.php?id=<?= $p['id'] ?>" target="_blank" style="color:#0F3D3E; font-weight:600; text-decoration:none;">
                                            <?= htmlspecialchars($p['title']) ?>
                                        </a>
                                        <div style="font-size:12px; color:#777; margin-top:2px;"><?= htmlspecialchars($p['address']) ?></div>
                                    </td>
                                    <td><?= htmlspecialchars($p['type']) ?></td>
                                    <td>
                                        <span style="font-size:12px; padding:2px 6px; border-radius:3px; background:<?= ($p['listing_type']==='Buy')?'#e8f4f8':'#fff4e5' ?>; color:<?= ($p['listing_type']==='Buy')?'#0f3d3e':'#b06000' ?>;">
                                            <?= htmlspecialchars($p['listing_type']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($p['city']) ?></td>
                                    <td>
                                        <strong>
                                            <?= ($p['listing_type'] === 'Rent') ? '₹' . number_format($p['price']) . '/mo' : '₹' . number_format($p['price']) ?>
                                        </strong>
                                    </td>
                                    <td><span class="badge-status <?= $statusClass ?>"><?= htmlspecialchars($p['status']) ?></span></td>
                                    <td style="text-align: center;">
                                        <a class="btn outline" href="edit-property.php?id=<?= $p['id'] ?>" style="padding: 6px 12px; font-size:12px;">Edit</a>
                                        <a class="btn gold" href="delete-property.php?id=<?=$p['id']?>" onclick="return confirm('Are you sure you want to delete property #<?= $p['id'] ?> - <?= htmlspecialchars(addslashes($p['title'])) ?>?')" style="padding: 6px 12px; font-size:12px; background-color:#d9534f; border-color:#d9534f; color:#fff;">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 40px; color: #777;">
                                    <h3>No properties found matching your filter criteria.</h3>
                                    <p style="margin-top: 8px;"><a href="properties.php" class="btn gold" style="display:inline-block; margin-top:10px;">Clear Filters</a></p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="copyright">© 2026 DreamNest Admin Panel</div>
        </div>
    </footer>
</body>
</html>