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

            $daysOfWeek = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];

            foreach ($daysOfWeek as $day) {
                $startKey = $day . "-start";
                $endKey = $day . "-end";
                $stockKey = $day . "-stock";
                $openKey = $day . "-open";

                ${$day . "Start"} = $_POST[$startKey];
                ${$day . "End"} = $_POST[$endKey];
                ${$day . "Stock"} = $_POST[$stockKey];
                ${$day . "Open"} = ($_POST[$openKey] == "Ouvert") ? 1 : 0;
            }

            $sql = "UPDATE user SET `name` = :name, `type` = :type, ";

            if (!empty($image)) {
                $sql .= "`image` = :image, ";
            }

            $sql .= " `street-number` = :streetNumber, `street-name` = :streetName, `city` = :city, `zip-code` = :zipCode, `monday-start` = :monday_start, `monday-end` = :monday_end, `monday-stock` = :monday_stock, `monday-open` = :monday_open, `tuesday-start` = :tuesday_start, `tuesday-end` = :tuesday_end, `tuesday-stock` = :tuesday_stock, `tuesday-open` = :tuesday_open, `wednesday-start` = :wednesday_start, `wednesday-end` = :wednesday_end, `wednesday-stock` = :wednesday_stock, `wednesday-open` = :wednesday_open, `thursday-start` = :thursday_start, `thursday-end` = :thursday_end, `thursday-stock` = :thursday_stock, `thursday-open` = :thursday_open, `friday-start` = :friday_start, `friday-end` = :friday_end, `friday-stock` = :friday_stock, `friday-open` = :friday_open, `saturday-start` = :saturday_start, `saturday-end` = :saturday_end, `saturday-stock` = :saturday_stock, `saturday-open` = :saturday_open, `sunday-start` = :sunday_start, `sunday-end` = :sunday_end, `sunday-stock` = :sunday_stock, `sunday-open` = :sunday_open WHERE id = :user_id";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":type", $type);
            if (!empty($image)) {
                $stmt->bindParam(":image", $image, PDO::PARAM_LOB);
            }
            $stmt->bindParam(":streetNumber", $streetNumber);
            $stmt->bindParam(":streetName", $streetName);
            $stmt->bindParam(":city", $city);
            $stmt->bindParam(":zipCode", $zipCode);
            $stmt->bindParam(":monday_start", $mondayStart);
            $stmt->bindParam(":monday_end", $mondayEnd);
            $stmt->bindParam(":monday_stock", $mondayStock);
            $stmt->bindParam(":monday_open", $mondayOpen);
            $stmt->bindParam(":tuesday_start", $tuesdayStart);
            $stmt->bindParam(":tuesday_end", $tuesdayEnd);
            $stmt->bindParam(":tuesday_stock", $tuesdayStock);
            $stmt->bindParam(":tuesday_open", $tuesdayOpen);
            $stmt->bindParam(":wednesday_start", $wednesdayStart);
            $stmt->bindParam(":wednesday_end", $wednesdayEnd);
            $stmt->bindParam(":wednesday_stock", $wednesdayStock);
            $stmt->bindParam(":wednesday_open", $wednesdayOpen);
            $stmt->bindParam(":thursday_start", $thursdayStart);
            $stmt->bindParam(":thursday_end", $thursdayEnd);
            $stmt->bindParam(":thursday_stock", $thursdayStock);
            $stmt->bindParam(":thursday_open", $thursdayOpen);
            $stmt->bindParam(":friday_start", $fridayStart);
            $stmt->bindParam(":friday_end", $fridayEnd);
            $stmt->bindParam(":friday_stock", $fridayStock);
            $stmt->bindParam(":friday_open", $fridayOpen);
            $stmt->bindParam(":saturday_start", $saturdayStart);
            $stmt->bindParam(":saturday_end", $saturdayEnd);
            $stmt->bindParam(":saturday_stock", $saturdayStock);
            $stmt->bindParam(":saturday_open", $saturdayOpen);
            $stmt->bindParam(":sunday_start", $sundayStart);
            $stmt->bindParam(":sunday_end", $sundayEnd);
            $stmt->bindParam(":sunday_stock", $sundayStock);
            $stmt->bindParam(":sunday_open", $sundayOpen);
            $stmt->bindParam(":user_id", $_SESSION["user_id"]);
            $stmt->execute();

            header("Location: manage.php");

        }else{
            echo "L'image est trop volumineuse (plus de 5 Mo).";
        }
    } catch (PDOException $e) {
        echo "Erreur de base de données : " . $e->getMessage();
    }
}
?>
