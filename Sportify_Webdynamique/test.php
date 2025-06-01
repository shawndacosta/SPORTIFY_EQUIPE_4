<?php
$mdp = "admin1234";
$hash = '$2y$10$aT1rbBFRGE9jP5lWYQuHg.ht0D27CULoc/8eVOuZTbrBZMBvPcAIa'; // colle ici EXACTEMENT le hash dans la BDD

if (password_verify($mdp, $hash)) {
    echo "✅ Le mot de passe correspond.";
} else {
    echo "❌ Mauvais mot de passe.";
}