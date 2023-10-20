<?php
session_start(); // Démarre la session

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html"); // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

// Récupérez les informations de l'utilisateur à partir de la session
$user_id = $_SESSION["user_id"];
$user_email = $_SESSION["user_email"];

// Connexion à la base de données
$dbHost = "localhost";
$dbName = "redeat";
$dbUser = "root";
$dbPass = "root";

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les informations actuelles de l'utilisateur depuis la base de données
    $query = "SELECT `name`, `type`, `street-number`, `street-name`, `city`, `zip-code`, `monday-start`, `monday-end`, `monday-stock`, `monday-open`, `tuesday-start`, `tuesday-end`, `tuesday-stock`, `tuesday-open`, `wednesday-start`, `wednesday-end`, `wednesday-stock`, `wednesday-open`, `thursday-start`, `thursday-end`, `thursday-stock`, `thursday-open`, `friday-start`, `friday-end`, `friday-stock`, `friday-open`, `saturday-start`, `saturday-end`, `saturday-stock`, `saturday-open`, `sunday-start`, `sunday-end`, `sunday-stock`, `sunday-open` FROM user WHERE id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $userInfo = $stmt->fetch();

        // Utilisez les valeurs de $userInfo pour remplir les champs
        $name = $userInfo["name"];
        $type = $userInfo["type"];
        $streetNumber = $userInfo["street-number"];
        $streetName = $userInfo["street-name"];
        $city = $userInfo["city"];
        $zipCode = $userInfo["zip-code"];

        // Lundi
        $mondayStart = $userInfo["monday-start"];
        $mondayEnd = $userInfo["monday-end"];
        $mondayStock = $userInfo["monday-stock"];
        $isMondayOpen = ($userInfo["monday-open"] == 1) ? "Ouvert" : "Fermé";

        // Mardi
        $tuesdayStart = $userInfo["tuesday-start"];
        $tuesdayEnd = $userInfo["tuesday-end"];
        $tuesdayStock = $userInfo["tuesday-stock"];
        $isTuesdayOpen = ($userInfo["tuesday-open"] == 1) ? "Ouvert" : "Fermé";

        // Mercredi
        $wednesdayStart = $userInfo["wednesday-start"];
        $wednesdayEnd = $userInfo["wednesday-end"];
        $wednesdayStock = $userInfo["wednesday-stock"];
        $isWednesdayOpen = ($userInfo["wednesday-open"] == 1) ? "Ouvert" : "Fermé";

        // Jeudi
        $thursdayStart = $userInfo["thursday-start"];
        $thursdayEnd = $userInfo["thursday-end"];
        $thursdayStock = $userInfo["thursday-stock"];
        $isThursdayOpen = ($userInfo["thursday-open"] == 1) ? "Ouvert" : "Fermé";

        // Vendredi
        $fridayStart = $userInfo["friday-start"];
        $fridayEnd = $userInfo["friday-end"];
        $fridayStock = $userInfo["friday-stock"];
        $isFridayOpen = ($userInfo["friday-open"] == 1) ? "Ouvert" : "Fermé";

        // Samedi
        $saturdayStart = $userInfo["saturday-start"];
        $saturdayEnd = $userInfo["saturday-end"];
        $saturdayStock = $userInfo["saturday-stock"];
        $isSaturdayOpen = ($userInfo["saturday-open"] == 1) ? "Ouvert" : "Fermé";

        // Dimanche
        $sundayStart = $userInfo["sunday-start"];
        $sundayEnd = $userInfo["sunday-end"];
        $sundayStock = $userInfo["sunday-stock"];
        $isSundayOpen = ($userInfo["sunday-open"] == 1) ? "Ouvert" : "Fermé";

    }
} catch (PDOException $e) {
    echo "Erreur de base de données : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="style.css" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
    />
  </head>
  <body>
    <div class="ctn logo-nav">
      <a href="./" class="logo">
        <svg
          width="121"
          height="26"
          viewBox="0 0 121 26"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <g clip-path="url(#clip0_23_150)">
            <path
              d="M0.91008 24.8389V0.585724H5.60943V24.8389H0.91008ZM15.67 24.8389L8.25695 14.3457H13.5188L21.1636 24.8389H15.67ZM4.21948 16.7875V12.9928H10.375C11.2354 12.9928 11.9745 12.8167 12.5922 12.4648C13.2321 12.1129 13.7285 11.6179 14.0815 10.9799C14.4344 10.342 14.611 9.61603 14.611 8.8021C14.611 7.96616 14.4344 7.22922 14.0815 6.59126C13.7285 5.95331 13.2321 5.45835 12.5922 5.10638C11.9745 4.75441 11.2354 4.57843 10.375 4.57843H4.21948V0.585724H9.87856C11.82 0.585724 13.4968 0.871701 14.9089 1.44366C16.343 2.01562 17.446 2.88454 18.2182 4.05046C18.9905 5.21637 19.3766 6.67925 19.3766 8.43913V8.96708C19.3766 10.749 18.9794 12.2119 18.1852 13.3558C17.413 14.4996 16.3208 15.3576 14.9089 15.9296C13.4968 16.5015 11.82 16.7875 9.87856 16.7875H4.21948ZM31.0906 25.4659C29.5461 25.4659 28.1892 25.2019 27.02 24.6739C25.8727 24.146 24.9129 23.442 24.1408 22.562C23.3906 21.66 22.8171 20.6593 22.42 19.5592C22.0449 18.4593 21.8573 17.3374 21.8573 16.1935V15.5665C21.8573 14.3786 22.0449 13.2348 22.42 12.1348C22.8171 11.0129 23.3906 10.023 24.1408 9.16508C24.9129 8.28514 25.8617 7.59219 26.9869 7.08623C28.1122 6.55827 29.4138 6.29429 30.892 6.29429C32.8335 6.29429 34.4551 6.72325 35.7568 7.58119C37.0806 8.41712 38.0734 9.52804 38.7352 10.9139C39.3972 12.2779 39.7282 13.7518 39.7282 15.3356V16.9855H23.8099V14.1807H36.849L35.4259 15.5665C35.4259 14.4227 35.2605 13.4437 34.9296 12.6299C34.5985 11.8159 34.091 11.189 33.4071 10.749C32.7453 10.309 31.9068 10.089 30.892 10.089C29.8771 10.089 29.0166 10.32 28.3107 10.782C27.6047 11.2439 27.0641 11.9149 26.6891 12.7948C26.336 13.6527 26.1596 14.6866 26.1596 15.8966C26.1596 17.0185 26.336 18.0194 26.6891 18.8993C27.042 19.7573 27.5827 20.4392 28.3107 20.9452C29.0388 21.4291 29.9654 21.6711 31.0906 21.6711C32.2158 21.6711 33.1314 21.4512 33.8374 21.0112C34.5433 20.5492 34.9956 19.9882 35.1943 19.3283H39.4303C39.1655 20.5602 38.658 21.6381 37.908 22.562C37.1578 23.486 36.198 24.2009 35.0288 24.7069C33.8814 25.2129 32.5687 25.4659 31.0906 25.4659ZM50.0242 25.4328C48.7666 25.4328 47.6083 25.2019 46.5493 24.7399C45.4903 24.278 44.5747 23.629 43.8025 22.793C43.0303 21.9571 42.4346 20.9892 42.0154 19.8893C41.5963 18.7673 41.3866 17.5574 41.3866 16.2595V15.5665C41.3866 14.2907 41.5853 13.0918 41.9824 11.9699C42.4015 10.8479 42.9752 9.86902 43.7033 9.03308C44.4533 8.19714 45.3469 7.54819 46.3839 7.08623C47.4429 6.60227 48.6122 6.36029 49.8918 6.36029C51.3038 6.36029 52.5393 6.66826 53.5984 7.28421C54.6794 7.87817 55.5398 8.7801 56.1797 9.99001C56.8195 11.1999 57.1725 12.7178 57.2387 14.5437L55.8818 12.9598V3.16366C55.8818 1.83103 56.9653 0.75071 58.3018 0.75071H60.4818V24.8389H56.8416V17.2165H57.6358C57.5696 19.0424 57.1945 20.5712 56.5106 21.8031C55.8267 23.013 54.9221 23.926 53.797 24.5419C52.6937 25.1359 51.4361 25.4328 50.0242 25.4328ZM51.0501 21.5721C51.9547 21.5721 52.782 21.3741 53.5322 20.9781C54.2823 20.5602 54.878 19.9663 55.3193 19.1963C55.7826 18.4044 56.0142 17.4805 56.0142 16.4246V15.1046C56.0142 14.0487 55.7826 13.1688 55.3193 12.4648C54.8559 11.7389 54.2491 11.189 53.4991 10.8149C52.749 10.419 51.9326 10.221 51.0501 10.221C50.0573 10.221 49.1748 10.463 48.4025 10.9469C47.6525 11.4089 47.0568 12.0688 46.6155 12.9268C46.1963 13.7847 45.9868 14.7857 45.9868 15.9296C45.9868 17.0734 46.2074 18.0744 46.6486 18.9323C47.0899 19.7683 47.6856 20.4173 48.4357 20.8792C49.2079 21.3412 50.0794 21.5721 51.0501 21.5721ZM72.767 25.4659C71.2226 25.4659 69.8658 25.2019 68.6964 24.6739C67.5492 24.146 66.5894 23.442 65.8172 22.562C65.0671 21.66 64.4935 20.6593 64.0964 19.5592C63.7213 18.4593 63.5338 17.3374 63.5338 16.1935V15.5665C63.5338 14.3786 63.7213 13.2348 64.0964 12.1348C64.4935 11.0129 65.0671 10.023 65.8172 9.16508C66.5894 8.28514 67.5382 7.59219 68.6634 7.08623C69.7886 6.55827 71.0903 6.29429 72.5684 6.29429C74.51 6.29429 76.1315 6.72325 77.4332 7.58119C78.7571 8.41712 79.7499 9.52804 80.4118 10.9139C81.0736 12.2779 81.4046 13.7518 81.4046 15.3356V16.9855H65.4863V14.1807H78.5254L77.1023 15.5665C77.1023 14.4227 76.9369 13.4437 76.6059 12.6299C76.275 11.8159 75.7675 11.189 75.0836 10.749C74.4218 10.309 73.5834 10.089 72.5684 10.089C71.5536 10.089 70.6932 10.32 69.9871 10.782C69.2811 11.2439 68.7406 11.9149 68.3655 12.7948C68.0125 13.6527 67.836 14.6866 67.836 15.8966C67.836 17.0185 68.0125 18.0194 68.3655 18.8993C68.7184 19.7573 69.2591 20.4392 69.9871 20.9452C70.7152 21.4291 71.6418 21.6711 72.767 21.6711C73.8923 21.6711 74.8078 21.4512 75.5138 21.0112C76.2198 20.5492 76.6721 19.9882 76.8707 19.3283H81.1067C80.8419 20.5602 80.3344 21.6381 79.5844 22.562C78.8343 23.486 77.8745 24.2009 76.7052 24.7069C75.558 25.2129 74.2452 25.4659 72.767 25.4659ZM95.1425 24.8389V19.5263H94.3813V13.6198C94.3813 12.5858 94.1276 11.8159 93.6202 11.3099C93.1128 10.8039 92.3295 10.551 91.2705 10.551C90.7189 10.551 90.057 10.562 89.2848 10.584C88.5126 10.6059 87.7295 10.6389 86.9352 10.683C86.163 10.7049 85.468 10.7379 84.8503 10.782V6.88825C85.3576 6.84425 85.9313 6.80025 86.5711 6.75625C87.2109 6.71225 87.8618 6.69026 88.5237 6.69026C89.2076 6.66826 89.8475 6.65726 90.4431 6.65726C92.2964 6.65726 93.8297 6.89925 95.0432 7.3832C96.2786 7.86717 97.2053 8.62611 97.8232 9.66003C98.4629 10.694 98.7828 12.0469 98.7828 13.7187V24.8389H95.1425ZM89.351 25.3008C88.0493 25.3008 86.9021 25.0699 85.9093 24.6079C84.9385 24.146 84.1773 23.486 83.6258 22.628C83.0963 21.7701 82.8315 20.7361 82.8315 19.5263C82.8315 18.2064 83.1514 17.1285 83.7913 16.2926C84.4532 15.4566 85.3688 14.8296 86.5381 14.4117C87.7295 13.9938 89.1194 13.7847 90.7079 13.7847H94.8778V16.5235H90.6417C89.5827 16.5235 88.7663 16.7875 88.1928 17.3154C87.6411 17.8214 87.3654 18.4814 87.3654 19.2952C87.3654 20.1092 87.6411 20.7692 88.1928 21.2752C88.7663 21.7812 89.5827 22.0341 90.6417 22.0341C91.2814 22.0341 91.8662 21.924 92.3957 21.7041C92.9472 21.4621 93.3995 21.0661 93.7526 20.5162C94.1276 19.9442 94.3372 19.1744 94.3813 18.2064L95.5065 19.4932C95.3961 20.7472 95.0872 21.8031 94.5799 22.6611C94.0944 23.519 93.4105 24.1789 92.5281 24.6409C91.6677 25.0809 90.6087 25.3008 89.351 25.3008ZM110.528 25.0699C108.873 25.0699 107.505 24.8608 106.424 24.4429C105.365 24.0029 104.571 23.277 104.041 22.2651C103.512 21.2311 103.247 19.8453 103.247 18.1074L103.28 1.87263H105.158C106.496 1.87263 107.58 2.95588 107.578 4.29038L107.549 18.4044C107.549 19.2843 107.781 19.9663 108.244 20.4502C108.729 20.9122 109.413 21.1432 110.296 21.1432H113.109V25.0699H110.528ZM100.401 10.287V6.92125H113.109V10.287H100.401Z"
              fill="black"
            />
            <path
              d="M115.37 25.0801V19.8006H118.113C119.449 19.8006 120.533 20.8809 120.533 22.2136V25.0801H115.37Z"
              fill="#10643C"
            />
          </g>
          <defs>
            <clipPath id="clip0_23_150">
              <rect
                width="121"
                height="25.8185"
                fill="white"
                transform="translate(0 0.0907593)"
              />
            </clipPath>
          </defs>
        </svg>
      </a>
    </div>
    <main>
      <div class="little-ctn">
        <div class="log-form">
          <h2>Je modifie mon commerce</h2>
          <form action="" enctype="multipart/form-data">
            <div class="separator">
              <span>Informations sur le commerce</span>
            </div>
            <div class="field">
              <label for="name">Nom du commerce</label>
              <input name="name" value="<?php echo $name ?>" type="text" placeholder="Nom du commerce" />
            </div>
            <div class="field">
              <label for="type">Type du commerce</label>
              <select name="type">
                <option value="Supermarché" <?php echo ($type == "Supermarché") ? "selected" : "" ?>>Supermarché</option>
                <option value="Epicerie" <?php echo ($type == "Epicerie") ? "selected" : "" ?>>Epicerie</option>
                <option value="Restaurant" <?php echo ($type == "Restaurant") ? "selected" : "" ?>>Restaurant</option>
                <option value="Boulangerie" <?php echo ($type == "Boulangerie") ? "selected" : "" ?>>Boulangerie</option>
                <option value="Producteur" <?php echo ($type == "Producteur") ? "selected" : "" ?>>Producteur</option>
              </select>
            </div>
            <div class="field">
              <label for="image">Image du commerce</label>
              <p>Image actuelle</p>
              <div class="preview">
                <img src="./image.php?user_id=<?php echo $_SESSION["user_id"] ?>" alt="">
              </div>
              <p>Nouvelle image</p>
              <input type="file" name="image" />
            </div>
            <div class="separator">
              <span>Localisation</span>
            </div>
            <div class="field">
              <label for="street-number">Numéro de voie</label>
              <input
                type="number"
                name="street-number"
                value="<?php echo $streetNumber ?>"
                placeholder="Numéro de voie"
              />
            </div>
            <div class="field">
              <label for="street-name">Nom de la voie</label>
              <input
                type="text"
                name="street-name"
                value="<?php echo $streetName ?>"
                placeholder="Nom de la voie"
              />
            </div>
            <div class="field">
              <label for="city">Ville</label>
              <input type="text" name="city" value="<?php echo $city ?>" placeholder="Ville" />
            </div>
            <div class="field">
              <label for="zip-code">Code postal</label>
              <input
                type="text"
                name="zip-code"
                value="<?php echo $zipCode ?>"
                pattern="(?:0[1-9]|[1-8]\d|9[0-8])\d{3}"
                placeholder="Code postal"
              />
            </div>
            <div class="separator">
              <span>Horaires de retrait & stocks</span>
            </div>
            <div class="field">
              <label for="monday">Lundi</label>
              <div class="col-2">
                <select name="monday-start">
                <?php
                  $selectedTime = $mondayStart;

                  for ($hour = 0; $hour < 24; $hour++) {
                      for ($minute = 0; $minute < 60; $minute += 30) {
                          $time = sprintf("%02d:%02d", $hour, $minute);
                          $selected = ($time === $selectedTime) ? 'selected' : '';

                          echo "<option value=\"$time\" $selected>$time</option>";
                      }
                  }
                ?>
                </select>
                <select name="monday-end">
                <?php
                  $selectedTime = $mondayEnd;

                  for ($hour = 0; $hour < 24; $hour++) {
                      for ($minute = 0; $minute < 60; $minute += 30) {
                          $time = sprintf("%02d:%02d", $hour, $minute);
                          $selected = ($time === $selectedTime) ? 'selected' : '';

                          echo "<option value=\"$time\" $selected>$time</option>";
                      }
                  }
                ?>
                </select>
              </div>
              <input
                type="number"
                value="<?php echo $mondayStock ?>"
                name="monday-stock"
                placeholder="Nombre de panier"
              />
              <select name="monday-open">
                <option value="Ouvert" <?php echo ($isMondayOpen == "Ouvert") ? "selected" : "" ?>>Ouvert</option>
                <option value="Fermé" <?php echo ($isMondayOpen == "Fermé") ? "selected" : "" ?>>Fermé</option>
              </select>
            </div>
            <div class="field">
                <label for="tuesday">Mardi</label>
                <div class="col-2">
                  <select name="tuesday-start">
                  <?php
                    $selectedTime = $tuesdayStart;

                    for ($hour = 0; $hour < 24; $hour++) {
                        for ($minute = 0; $minute < 60; $minute += 30) {
                            $time = sprintf("%02d:%02d", $hour, $minute);
                            $selected = ($time === $selectedTime) ? 'selected' : '';

                            echo "<option value=\"$time\" $selected>$time</option>";
                        }
                    }
                  ?>
                  </select>
                  <select name="tuesday-end">
                  <?php
                    $selectedTime = $tuesdayEnd;

                    for ($hour = 0; $hour < 24; $hour++) {
                        for ($minute = 0; $minute < 60; $minute += 30) {
                            $time = sprintf("%02d:%02d", $hour, $minute);
                            $selected = ($time === $selectedTime) ? 'selected' : '';

                            echo "<option value=\"$time\" $selected>$time</option>";
                        }
                    }
                  ?>
                  </select>
                </div>
                <input
                  type="number"
                  value="<?php echo $tuesdayStock ?>"
                  name="tuesday-stock"
                  placeholder="Nombre de panier"
                />
                <select name="tuesday-open">
                  <option value="Ouvert" <?php echo ($isTuesdayOpen == "Ouvert") ? "selected" : "" ?>>Ouvert</option>
                  <option value="Fermé" <?php echo ($isTuesdayOpen == "Fermé") ? "selected" : "" ?>>Fermé</option>
                </select>
              </div>
              <div class="field">
                <label for="wednesday">Mercredi</label>
                <div class="col-2">
                  <select name="wednesday-start">
                    <?php
                      $selectedTime = $wednesdayStart;

                      for ($hour = 0; $hour < 24; $hour++) {
                          for ($minute = 0; $minute < 60; $minute += 30) {
                              $time = sprintf("%02d:%02d", $hour, $minute);
                              $selected = ($time === $selectedTime) ? 'selected' : '';

                              echo "<option value=\"$time\" $selected>$time</option>";
                          }
                      }
                    ?>
                  </select>
                  <select name="wednesday-end">
                    <?php
                      $selectedTime = $wednesdayEnd;

                      for ($hour = 0; $hour < 24; $hour++) {
                          for ($minute = 0; $minute < 60; $minute += 30) {
                              $time = sprintf("%02d:%02d", $hour, $minute);
                              $selected = ($time === $selectedTime) ? 'selected' : '';

                              echo "<option value=\"$time\" $selected>$time</option>";
                          }
                      }
                    ?>
                  </select>
                </div>
                <input
                  type="number"
                  value="<?php echo $wednesdayStock ?>"
                  name="wednesday-stock"
                  placeholder="Nombre de panier"
                />
                <select name="wednesday-open">
                  <option value="Ouvert" <?php echo ($isWednesdayOpen == "Ouvert") ? "selected" : "" ?>>Ouvert</option>
                  <option value="Fermé" <?php echo ($isWednesdayOpen == "Fermé") ? "selected" : "" ?>>Fermé</option>
                </select>
              </div>
              <div class="field">
                <label for="thursday">Jeudi</label>
                <div class="col-2">
                  <select name="thursday-start">
                    <?php
                      $selectedTime = $thursdayStart;

                      for ($hour = 0; $hour < 24; $hour++) {
                          for ($minute = 0; $minute < 60; $minute += 30) {
                              $time = sprintf("%02d:%02d", $hour, $minute);
                              $selected = ($time === $selectedTime) ? 'selected' : '';

                              echo "<option value=\"$time\" $selected>$time</option>";
                          }
                      }
                    ?>
                  </select>
                  <select name="thursday-end">
                    <?php
                      $selectedTime = $thursdayEnd;

                      for ($hour = 0; $hour < 24; $hour++) {
                          for ($minute = 0; $minute < 60; $minute += 30) {
                              $time = sprintf("%02d:%02d", $hour, $minute);
                              $selected = ($time === $selectedTime) ? 'selected' : '';

                              echo "<option value=\"$time\" $selected>$time</option>";
                          }
                      }
                    ?>
                  </select>
                </div>
                <input
                  type="number"
                  value="<?php echo $thursdayStock ?>"
                  name="thursday-stock"
                  placeholder="Nombre de panier"
                />
                <select name="thursday-open">
                  <option value="Ouvert" <?php echo ($isThursdayOpen == "Ouvert") ? "selected" : "" ?>>Ouvert</option>
                  <option value="Fermé" <?php echo ($isThursdayOpen == "Fermé") ? "selected" : "" ?>>Fermé</option>
                </select>
              </div>
              <div class="field">
                <label for="friday">Vendredi</label>
                <div class="col-2">
                  <select name="friday-start">
                    <?php
                      $selectedTime = $fridayStart;

                      for ($hour = 0; $hour < 24; $hour++) {
                          for ($minute = 0; $minute < 60; $minute += 30) {
                              $time = sprintf("%02d:%02d", $hour, $minute);
                              $selected = ($time === $selectedTime) ? 'selected' : '';

                              echo "<option value=\"$time\" $selected>$time</option>";
                          }
                      }
                    ?>
                  </select>
                  <select name="friday-end">
                    <?php
                      $selectedTime = $fridayEnd;

                      for ($hour = 0; $hour < 24; $hour++) {
                          for ($minute = 0; $minute < 60; $minute += 30) {
                              $time = sprintf("%02d:%02d", $hour, $minute);
                              $selected = ($time === $selectedTime) ? 'selected' : '';

                              echo "<option value=\"$time\" $selected>$time</option>";
                          }
                      }
                    ?>
                  </select>
                </div>
                <input
                  type="number"
                  value="<?php echo $fridayStock ?>"
                  name="friday-stock"
                  placeholder="Nombre de panier"
                />
                <select name="friday-open">
                  <option value="Ouvert" <?php echo ($isFridayOpen == "Ouvert") ? "selected" : "" ?>>Ouvert</option>
                  <option value="Fermé" <?php echo ($isFridayOpen == "Fermé") ? "selected" : "" ?>>Fermé</option>
                </select>
              </div>
              <div class="field">
                <label for="saturday">Samedi</label>
                <div class="col-2">
                  <select name="saturday-start">
                    <?php
                      $selectedTime = $saturdayStart;

                      for ($hour = 0; $hour < 24; $hour++) {
                          for ($minute = 0; $minute < 60; $minute += 30) {
                              $time = sprintf("%02d:%02d", $hour, $minute);
                              $selected = ($time === $selectedTime) ? 'selected' : '';

                              echo "<option value=\"$time\" $selected>$time</option>";
                          }
                      }
                    ?>
                  </select>
                  <select name="saturday-end">
                    <?php
                      $selectedTime = $saturdayEnd;

                      for ($hour = 0; $hour < 24; $hour++) {
                          for ($minute = 0; $minute < 60; $minute += 30) {
                              $time = sprintf("%02d:%02d", $hour, $minute);
                              $selected = ($time === $selectedTime) ? 'selected' : '';

                              echo "<option value=\"$time\" $selected>$time</option>";
                          }
                      }
                    ?>
                  </select>
                </div>
                <input
                  type="number"
                  value="<?php echo $saturdayStock ?>"
                  name="saturday-stock"
                  placeholder="Nombre de panier"
                />
                <select name="saturday-open">
                  <option value="Ouvert" <?php echo ($isSaturdayOpen == "Ouvert") ? "selected" : "" ?>>Ouvert</option>
                  <option value="Fermé" <?php echo ($isSaturdayOpen == "Fermé") ? "selected" : "" ?>>Fermé</option>
                </select>
              </div>
              <div class="field">
                <label for="sunday">Dimanche</label>
                <div class="col-2">
                  <select name="sunday-start">
                  <?php
                      $selectedTime = $sundayStart;

                      for ($hour = 0; $hour < 24; $hour++) {
                          for ($minute = 0; $minute < 60; $minute += 30) {
                              $time = sprintf("%02d:%02d", $hour, $minute);
                              $selected = ($time === $selectedTime) ? 'selected' : '';

                              echo "<option value=\"$time\" $selected>$time</option>";
                          }
                      }
                    ?>
                  </select>
                  <select name="sunday-end">
                  <?php
                      $selectedTime = $saturdayEnd;

                      for ($hour = 0; $hour < 24; $hour++) {
                          for ($minute = 0; $minute < 60; $minute += 30) {
                              $time = sprintf("%02d:%02d", $hour, $minute);
                              $selected = ($time === $selectedTime) ? 'selected' : '';

                              echo "<option value=\"$time\" $selected>$time</option>";
                          }
                      }
                    ?>
                  </select>
                </div>
                <input
                  type="number"
                  value="<?php echo $sundayStock ?>"
                  name="sunday-stock"
                  placeholder="Nombre de panier"
                />
                <select name="sunday-open">
                  <option value="Ouvert" <?php echo ($isSundayOpen == "Ouvert") ? "selected" : "" ?>>Ouvert</option>
                  <option value="Fermé" <?php echo ($isSundayOpen == "Fermé") ? "selected" : "" ?>>Fermé</option>
                </select>
              </div>
            <button class="btn fw" type="submit">
              <span>Sauvegarder</span>
              <span class="material-symbols-outlined"> save </span>
            </button>
            <a href="./edit-password.html">Modifier le mot de passe</a>
          </form>
        </div>
      </div>
    </main>
  </body>
</html>
