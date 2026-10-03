<?php
require_once 'db.php';

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
        $stmt = $pdo->prepare("INSERT INTO places (name, category, location, description, rating, image, address, contact) VALUES (:name, :category, :location, :description, :rating, :image, :address, :contact)");
        $stmt->execute([
            ':name' => $name,
            ':category' => $category,
            ':location' => $location,
            ':description' => $description,
            ':rating' => $rating ?: 0.0,
            ':image' => $image ?: 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            ':address' => $address,
            ':contact' => $contact
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
    <title>Add Place - KANTO</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="top-nav">
        <a href="index.php" class="logo">KANTO</a>
        <a href="index.php" class="btn btn-secondary">Back to Home</a>
    </header>

    <div class="form-container">
        <h2>Add a New Tambayan</h2>
        <?php if (isset($error)): ?>
            <div class="alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="add.php" class="kanto-form">
            <div class="form-group">
                <label for="name">Place Name *</label>
                <input type="text" id="name" name="name" required placeholder="e.g., Kape sa Kanto">
            </div>
            
            <div class="form-group">
                <label for="category">Category *</label>
                <select id="category" name="category" required>
                    <option value="">Select Category</option>
                    <option value="Food">Food</option>
                    <option value="Café">Café</option>
                    <option value="Beach">Beach</option>
                    <option value="Study">Study</option>
                    <option value="Sports">Sports</option>
                    <option value="Nightlife">Nightlife</option>
                    <option value="Date Spot">Date Spot</option>
                    <option value="Hidden Gem">Hidden Gem</option>
                </select>
            </div>

            <div class="form-group">
                <label for="location">Location *</label>
                <input type="text" id="location" name="location" required placeholder="e.g., Nasugbu, Batangas">
            </div>

            <div class="form-group">
                <label for="address">Full Address</label>
                <input type="text" id="address" name="address" placeholder="e.g., 123 Sunset Blvd">
            </div>

            <div class="form-group">
                <label for="contact">Contact Number</label>
                <input type="text" id="contact" name="contact" placeholder="e.g., 0917-123-4567">
            </div>

            <div class="form-group">
                <label for="rating">Rating (1.0 to 5.0)</label>
                <input type="number" id="rating" name="rating" min="1" max="5" step="0.1" placeholder="4.5">
            </div>

            <div class="form-group">
                <label for="image">Image URL</label>
                <input type="url" id="image" name="image" placeholder="https://example.com/image.jpg">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" placeholder="What makes this place special?"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">+ Add Place</button>
        </form>
    </div>
</body>
</html>
