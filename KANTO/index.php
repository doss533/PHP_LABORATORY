<?php
require_once 'db.php';

// Handle Search and Filter
$search = $_GET['search'] ?? '';
$filter_category = $_GET['category'] ?? 'All';

$query = "SELECT * FROM places WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (name LIKE :search OR category LIKE :search OR location LIKE :search OR description LIKE :search)";
    $params[':search'] = "%$search%";
}

if ($filter_category !== 'All') {
    $query .= " AND category = :category";
    $params[':category'] = $filter_category;
}

$query .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$places = $stmt->fetchAll();

$categories = ['All', 'Food', 'Café', 'Beach', 'Study', 'Sports', 'Nightlife', 'Date Spot', 'Hidden Gem'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KANTO - Find Your Next Tambayan</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-content">
            <a href="index.php" class="logo">KANTO</a>
            <div class="nav-links">
                <a href="add.php" class="btn btn-primary">+ Add Place</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <div class="hero-content">
            <h1>FIND YOUR NEXT TAMBAYAN</h1>
            <p>Discover cafés, food spots, beaches, study places, and hidden gems around the Philippines.</p>
            
            <form action="index.php" method="GET" class="search-form">
                <div class="search-bar">
                    <input type="text" name="search" placeholder="Search places, food, cafés, beaches..." value="<?= htmlspecialchars($search) ?>">
                    <?php if ($filter_category !== 'All'): ?>
                        <input type="hidden" name="category" value="<?= htmlspecialchars($filter_category) ?>">
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary">Search</button>
                    <?php if (!empty($search) || $filter_category !== 'All'): ?>
                        <a href="index.php" class="btn btn-secondary">Reset</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </header>

    <main class="container">
        
        <!-- Category Filter Buttons -->
        <section class="categories-section">
            <div class="location-selector">
                <span>📍 Choose your location</span>
            </div>
            <div class="category-buttons">
                <?php foreach ($categories as $cat): ?>
                    <?php 
                        $icon = '';
                        switch($cat) {
                            case 'Food': $icon = '🍔 '; break;
                            case 'Café': $icon = '☕ '; break;
                            case 'Beach': $icon = '🌊 '; break;
                            case 'Study': $icon = '📚 '; break;
                            case 'Sports': $icon = '🏀 '; break;
                            case 'Nightlife': $icon = '🌙 '; break;
                            case 'Date Spot': $icon = '❤️ '; break;
                            case 'Hidden Gem': $icon = '✨ '; break;
                        }
                        $active = ($filter_category === $cat) ? 'active' : '';
                        $cat_url = "index.php?category=" . urlencode($cat);
                        if (!empty($search)) {
                            $cat_url .= "&search=" . urlencode($search);
                        }
                    ?>
                    <a href="<?= $cat_url ?>" class="cat-btn <?= $active ?>"><?= $icon . htmlspecialchars($cat) ?></a>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Places List -->
        <section class="trending-section">
            <h2><?= $filter_category === 'All' ? (!empty($search) ? 'SEARCH RESULTS' : 'TRENDING TAMBAYAN') : strtoupper($filter_category) . ' SPOTS' ?></h2>
            <div class="places-grid">
                <?php if (count($places) > 0): ?>
                    <?php foreach ($places as $place): ?>
                        <div class="place-card">
                            <div class="card-image-container">
                                <img src="<?= htmlspecialchars($place['image']) ?>" alt="<?= htmlspecialchars($place['name']) ?>" class="card-image">
                                <div class="card-badge"><?= htmlspecialchars($place['category']) ?></div>
                                <!-- Visual heart button (no backend persistence) -->
                                <button class="fav-btn" onclick="this.classList.toggle('active');">♥</button>
                            </div>
                            <div class="card-content">
                                <div class="card-header">
                                    <h3><?= htmlspecialchars($place['name']) ?></h3>
                                    <div class="rating">
                                        <span class="stars">★★★★★</span>
                                        <span class="rating-val"><?= htmlspecialchars($place['rating']) ?></span>
                                    </div>
                                </div>
                                <div class="location">📍 <?= htmlspecialchars($place['location']) ?></div>
                                <p class="desc"><?= htmlspecialchars($place['description']) ?></p>
                                
                                <div class="card-actions">
                                    <a href="#" class="btn btn-view">View Place</a>
                                    <div class="admin-actions">
                                        <a href="edit.php?id=<?= $place['id'] ?>" class="btn btn-edit">Edit</a>
                                        <a href="delete.php?id=<?= $place['id'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this place?');">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-results">No places found. Try a different search or category.</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Explore by Category Section -->
        <section class="explore-categories">
            <h2>EXPLORE BY CATEGORY</h2>
            <div class="explore-grid">
                <?php
                $explore_cats = [
                    'FOOD' => 'Food', 
                    'CAFÉS' => 'Café', 
                    'BEACHES' => 'Beach', 
                    'STUDY SPOTS' => 'Study', 
                    'SPORTS' => 'Sports', 
                    'NIGHT SPOTS' => 'Nightlife', 
                    'DATE SPOTS' => 'Date Spot', 
                    'HIDDEN GEMS' => 'Hidden Gem'
                ];
                foreach ($explore_cats as $display_name => $db_category) {
                    $url = "index.php?category=" . urlencode($db_category);
                    echo "<a href='$url' class='explore-card' style='text-decoration: none; color: inherit;'><h3>$display_name</h3></a>";
                }
                ?>
            </div>
        </section>

        <!-- Explore Near You / Map -->
        <section class="map-section">
            <h2>EXPLORE PLACES NEAR YOU</h2>
            <div id="map-container" style="width: 100%; height: 400px; border-radius: 16px; overflow: hidden; border: 1px solid var(--border-color); position: relative; background: #0f172a;">
                <?php
                    $map_query = !empty($search) ? urlencode($search . ", Philippines") : "Nasugbu,Batangas,Philippines";
                ?>
                <iframe id="google-map" width="100%" height="100%" frameborder="0" style="border:0;" src="https://maps.google.com/maps?q=<?= $map_query ?>&t=&z=14&ie=UTF8&iwloc=&output=embed" allowfullscreen loading="lazy"></iframe>
                <div id="map-overlay-loading" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.7); display: flex; align-items: center; justify-content: center; pointer-events: none; transition: opacity 0.3s; color: var(--accent-yellow); font-weight: 600;">
                    <?= !empty($search) ? "Searching map for: " . htmlspecialchars($search) . " 📍" : "Locating you... 📍" ?>
                </div>
            </div>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const iframe = document.getElementById('google-map');
                    const loader = document.getElementById('map-overlay-loading');
                    const hasSearch = <?= !empty($search) ? 'true' : 'false' ?>;
                    
                    if (hasSearch) {
                        // If user searched for something, prioritize the search query on the map
                        iframe.onload = function() {
                            loader.style.opacity = '0';
                        };
                        // Fallback in case onload doesn't fire
                        setTimeout(() => { loader.style.opacity = '0'; }, 1500);
                    } else if (navigator.geolocation) {
                        // Otherwise, locate the user automatically
                        navigator.geolocation.getCurrentPosition(function(position) {
                            const lat = position.coords.latitude;
                            const lon = position.coords.longitude;
                            iframe.src = `https://maps.google.com/maps?q=${lat},${lon}&t=&z=15&ie=UTF8&iwloc=&output=embed`;
                            
                            iframe.onload = function() {
                                loader.style.opacity = '0';
                            };
                        }, function(error) {
                            console.log("Geolocation error: ", error.message);
                            loader.innerHTML = "Location access denied. Showing default view.";
                            setTimeout(() => { loader.style.opacity = '0'; }, 2000);
                        }, { timeout: 10000 });
                    } else {
                        loader.style.opacity = '0';
                    }
                });
            </script>
        </section>

        <!-- Popular Near You -->
        <section class="popular-near-you">
            <h2>POPULAR NEAR YOU</h2>
            <div class="list-view">
                <div class="list-item">
                    <div class="list-info">
                        <h4>Nasugbu Brews</h4>
                        <span class="list-cat">Café</span>
                        <span class="list-rating">⭐ 4.6</span>
                    </div>
                    <div class="list-meta">
                        <span class="list-loc">Nasugbu, Batangas</span>
                        <span class="list-dist">1.2 km away</span>
                    </div>
                </div>
                <div class="list-item">
                    <div class="list-info">
                        <h4>Lomi House</h4>
                        <span class="list-cat">Food</span>
                        <span class="list-rating">⭐ 4.8</span>
                    </div>
                    <div class="list-meta">
                        <span class="list-loc">Lipa, Batangas</span>
                        <span class="list-dist">3.5 km away</span>
                    </div>
                </div>
                <div class="list-item">
                    <div class="list-info">
                        <h4>Hidden Cove</h4>
                        <span class="list-cat">Beach</span>
                        <span class="list-rating">⭐ 4.9</span>
                    </div>
                    <div class="list-meta">
                        <span class="list-loc">Laiya, San Juan</span>
                        <span class="list-dist">12 km away</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Reviews Section -->
        <section class="reviews-section">
            <h2>WHAT PEOPLE SAY</h2>
            <div class="reviews-grid">
                <div class="review-card">
                    <div class="review-stars">⭐⭐⭐⭐⭐</div>
                    <p class="review-text">"Perfect place to chill with friends after class. The ambiance is amazing and the food is very affordable!"</p>
                    <p class="reviewer">— RACK MAROS</p>
                </div>
                <div class="review-card">
                    <div class="review-stars">⭐⭐⭐⭐</div>
                    <p class="review-text">"Great coffee and relaxing atmosphere. Definitely my new favorite study spot in the area."</p>
                    <p class="reviewer">— KERVYN WAY</p>
                </div>
                <div class="review-card">
                    <div class="review-stars">⭐⭐⭐⭐⭐</div>
                    <p class="review-text">"Best hidden gem I've found! The sunset view here is unmatched. Highly recommended for dates."</p>
                    <p class="reviewer">— MIKE MAROS</p>
                </div>
            </div>
        </section>

    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> KANTO - Find Your Next Tambayan.</p>
    </footer>
</body>
</html>
