<?php
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

        // Récupérer les données du formulaire
        $name = $_POST["name"];
        $type = $_POST["type"];
        $image = file_get_contents($_FILES["image"]["tmp_name"]);
        $streetNumber = $_POST["street-number"];
        $streetName = $_POST["street-name"];
        $city = $_POST["city"];
        $zipCode = $_POST["zip-code"];
        $email = $_POST["email"];

        // Vérifier que les mots de passe correspondent
        $password = $_POST["password"];
        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,32}$/", $password)) {
            echo "Le mot de passe ne satisfait pas les critères requis.";
            die();
        }
        
        $passwordC = $_POST["password-c"];
        if ($password !== $passwordC) {
            echo "Les mots de passe ne correspondent pas.";
            die();
        }

        $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

        // Vérifier si l'email n'a pas déjà été utilisé
        $query = "SELECT * FROM user WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            echo "Cet email a déjà été utilisé.";
            die();
        }

        // Insérer les données dans la base de données
        $query = "INSERT INTO user (`name`, `type`, `image`, `street-number`, `street-name`, `city`, `zip-code`, `email`, `password`) VALUES (:name, :type, :image, :streetNumber, :streetName, :city, :zipCode, :email, :password)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":type", $type);
        $stmt->bindParam(":image", $image, PDO::PARAM_LOB);
        $stmt->bindParam(":streetNumber", $streetNumber);
        $stmt->bindParam(":streetName", $streetName);
        $stmt->bindParam(":city", $city);
        $stmt->bindParam(":zipCode", $zipCode);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);
        $stmt->execute();

        echo "Inscription réussie.";
    } catch (PDOException $e) {
        echo "Erreur de base de données : " . $e->getMessage();
    }
}
?>
