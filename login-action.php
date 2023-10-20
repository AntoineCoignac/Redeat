<?php
session_start(); // Démarre la session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connexion à la base de données
    $dbHost = "localhost";
    $dbName = "redeat";
    $dbUser = "root";
    $dbPass = "root";

    try {
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupérer les données du formulaire
        $email = $_POST["email"];
        $password = $_POST["password"];

        $query = "SELECT id, password FROM user WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch();
            $hashedPassword = $user["password"];

            if (password_verify($password, $hashedPassword)) {
                // Mot de passe correct, enregistre l'utilisateur dans la session
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_email"] = $email;

                // Redirige vers la page de gestion
                header("Location: manage.php");
                exit();
            } else {
                echo "Mot de passe incorrect.";
            }
        } else {
            echo "Utilisateur non trouvé. Veuillez vous inscrire d'abord.";
        }
    } catch (PDOException $e) {
        echo "Erreur de base de données : " . $e->getMessage();
    }
}
?>