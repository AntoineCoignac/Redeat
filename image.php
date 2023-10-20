<?php
// Vérifiez si le paramètre "user_id" est présent dans l'URL
if (isset($_GET['user_id'])) {
    // Récupérez l'ID de l'utilisateur à partir de l'URL
    $user_id = $_GET['user_id'];

    // Connexion à la base de données (remplacez ces informations par les vôtres)
    $dbHost = "localhost";
    $dbName = "redeat";
    $dbUser = "root";
    $dbPass = "root";

    try {
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Exécutez une requête pour récupérer l'image de l'utilisateur en fonction de son ID
        $query = "SELECT image FROM user WHERE id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        // Vérifiez si l'utilisateur avec cet ID existe
        if ($stmt->rowCount() == 1) {
            // Récupérez les données de l'utilisateur
            $userData = $stmt->fetch();

            // Spécifiez le type de contenu comme une image
            header('Content-Type: image/jpeg'); // Changez le type de contenu en fonction du type d'image

            // Affichez l'image de l'utilisateur
            echo $userData['image'];
        } else {
            echo "Utilisateur non trouvé.";
        }
    } catch (PDOException $e) {
        echo "Erreur de base de données : " . $e->getMessage();
    }
} else {
    echo "Paramètre 'user_id' manquant dans l'URL.";
}
?>
