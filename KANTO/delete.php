<?php
require_once 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Validate ID
    if (filter_var($id, FILTER_VALIDATE_INT)) {
        $stmt = $pdo->prepare("DELETE FROM places WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}

header("Location: index.php");
exit;
?>
