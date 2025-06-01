<?php
include 'includes/db.php';

$nom = 'Admin';
$prenom = 'Test';
$email = 'admin@site.com';
$password = password_hash('admin1234', PASSWORD_DEFAULT);
$role = 'admin';

// Supprimer si déjà existant
$conn->query("DELETE FROM users WHERE email = '$email'");

$stmt = $conn->prepare("INSERT INTO users (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $nom, $prenom, $email, $password, $role);

if ($stmt->execute()) {
    echo "✅ Admin ajouté avec succès !";
} else {
    echo "❌ Erreur : " . $stmt->error;
}
