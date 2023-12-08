<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php"); // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connexion à la base de données
    $dbHost = "localhost";
    $dbName = "redeat";
    $dbUser = "root";
    $dbPass = "root";

    try {
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $user_id = $_SESSION["user_id"];
        $oldPw = $_POST["old-password"];
        $newPw = $_POST["password"];

        // Vérifier si le mot de passe actuel est correct pour cet utilisateur
        $query = "SELECT password FROM user WHERE id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        $userData = $stmt->fetch();

        if ($userData && password_verify($oldPw, $userData['password'])) {
            // Le mot de passe actuel est correct, mettre à jour le mot de passe
            $newHashedPw = password_hash($newPw, PASSWORD_BCRYPT);
            $updateQuery = "UPDATE user SET password = :password WHERE id = :user_id";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->bindParam(":password", $newHashedPw);
            $updateStmt->bindParam(":user_id", $user_id);
            $updateStmt->execute();

            // Redirection vers la page de gestion avec un message de succès
            header("Location: manage.php?success=1");
            exit();
        } else {
            echo "Le mot de passe actuel est incorrect.";
        }
    } catch (PDOException $e) {
        echo "Erreur de base de données : " . $e->getMessage();
    }
}
?>
