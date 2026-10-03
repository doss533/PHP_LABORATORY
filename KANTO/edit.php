<?php
require_once 'db.php';

$id = $_GET['id'] ?? null;
if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    header("Location: index.php");
    exit;
}

// Fetch existing place
$stmt = $pdo->prepare("SELECT * FROM places WHERE id = :id");
$stmt->execute([':id' => $id]);
$place = $stmt->fetch();

if (!$place) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $rating = trim($_POST['rating'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $contact = trim($_POST['contact'] ?? '');

    if (!empty($name) && !empty($category) && !empty($location)) {
        $stmt = $pdo->prepare("UPDATE places SET name=:name, category=:category, location=:location, description=:description, rating=:rating, image=:image, address=:address, contact=:contact WHERE id=:id");
        $stmt->execute([
            ':name' => $name,
            ':category' => $category,
            ':location' => $location,
            ':description' => $description,
            ':rating' => $rating ?: 0.0,
            ':image' => $image ?: $place['image'],
            ':address' => $address,
            ':contact' => $contact,
            ':id' => $id
        ]);
        header("Location: index.php");
        exit;
    } else {
        $error = "Name, Category, and Location are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Place - KANTO</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="top-nav">
        <a href="index.php" class="logo">KANTO</a>
        <a href="index.php" class="btn btn-secondary">Back to Home</a>
    </header>

    <div class="form-container">
        <h2>Edit Tambayan</h2>
        <?php if (isset($error)): ?>
            <div class="alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="edit.php?id=<?= $place['id'] ?>" class="kanto-form">
            <div class="form-group">
                <label for="name">Place Name *</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($place['name']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="category">Category *</label>
                <select id="category" name="category" required>
                    <?php
                    $categories = ['Food', 'Café', 'Beach', 'Study', 'Sports', 'Nightlife', 'Date Spot', 'Hidden Gem'];
                    foreach ($categories as $cat) {
                        $selected = ($place['category'] == $cat) ? 'selected' : '';
                        echo "<option value=\"$cat\" $selected>$cat</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="location">Location *</label>
                <input type="text" id="location" name="location" value="<?= htmlspecialchars($place['location']) ?>" required>
            </div>

            <div class="form-group">
                <label for="address">Full Address</label>
                <input type="text" id="address" name="address" value="<?= htmlspecialchars($place['address']) ?>">
            </div>

            <div class="form-group">
                <label for="contact">Contact Number</label>
                <input type="text" id="contact" name="contact" value="<?= htmlspecialchars($place['contact']) ?>">
            </div>

            <div class="form-group">
                <label for="rating">Rating (1.0 to 5.0)</label>
                <input type="number" id="rating" name="rating" min="1" max="5" step="0.1" value="<?= htmlspecialchars($place['rating']) ?>">
            </div>

            <div class="form-group">
                <label for="image">Image URL</label>
                <input type="url" id="image" name="image" value="<?= htmlspecialchars($place['image']) ?>">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"><?= htmlspecialchars($place['description']) ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</body>
</html>
