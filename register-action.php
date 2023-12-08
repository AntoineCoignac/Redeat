<?php
session_start();

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
    
        // Vérifier la taille du fichier image
        $imageTest = $_FILES["image"];
        if ($imageTest['size'] < 5 * 1024 * 1024 || empty($imageTest)) { // 5 Mo en octets
            // Récupérer les données du formulaire
            $name = $_POST["name"];
            $type = $_POST["type"];
            $image = file_get_contents($_FILES["image"]["tmp_name"]);
            $streetNumber = $_POST["street-number"];
            $streetName = $_POST["street-name"];
            $city = $_POST["city"];
            $zipCode = $_POST["zip-code"];
            $email = $_POST["email"];

            function isDomainValid($email) {
                list($username, $domain) = explode('@', $email);
            
                if (checkdnsrr($domain, "MX")) {
                    return true; // Le domaine existe et a un enregistrement MX (serveur de messagerie) valide.
                } else {
                    return false; // Le domaine n'a pas d'enregistrement MX valide.
                }
            }

            if (isDomainValid($email)) {

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

                // Utilisez l'en-tête Refresh pour effectuer la redirection
                header("Refresh: 5; URL=login.php");
                echo "Inscription réussie ! Vous allez être redirigé dans 5 secondes.";
            } else {
                echo "Le domaine de l'adresse e-mail n'est pas valide. ";
            }
        }else{
            echo "L'image est trop volumineuse (plus de 5 Mo).";
        }

    } catch (PDOException $e) {
        echo "Erreur de base de données : " . $e->getMessage();
    }
}
?>
