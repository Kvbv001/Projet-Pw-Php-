<?php 

if (!isset($_SESSION['educateur_id'])) {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: index.php?page=login"); // Remplacez 'page_connexion.php' par le chemin de votre page de connexion
    exit();
}
else{?>

<?php 
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../classes/models/Connexion.php');
require_once(__DIR__ . '/../classes/models/CategorieModel.php');
require_once(__DIR__ . '/../classes/models/ContactModel.php');
require_once(__DIR__ . '/../classes/models/EducateurModel.php');
require_once(__DIR__ . '/../classes/models/LicencieModel.php');
require_once(__DIR__ . "/../classes/dao/CategorieDAO.php");
require_once(__DIR__ . "/../classes/dao/LicenceDAO.php");
require_once(__DIR__ . "/../classes/dao/EducateurDAO.php");
require_once(__DIR__ . "/../classes/dao/ContactDAO.php");

$contactdao = new ContactDAO(new Connexion());
$contactNumber = $contactdao->countContact(); 
$categoriedao = new CategorieDAO(new Connexion());
$categorieNumber = $categoriedao->countCategorie(); 
$licencedao = new LicenceDAO(new Connexion());
$licenceNumber = $licencedao->countLicencie(); 
$educateurdao = new EducateurDAO(new Connexion());
$educateurNumber= $educateurdao->countEducateur(); 

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>

<body>
    <!-- sidebar -->
    <?php include_once './views/includes/menu.php' ?>


    <div class="main_content">
        <div class="header_wrapper">
            <div class="header_title">
                <span>Club Sportif</span>
                <h2>Tableau de Bord</h2>
            </div>

            <div class="user_info">
                <div class="search_box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="rechercher">
                </div>
                <img src="./assets/images/default-user.png" alt="">
            </div>
        </div>
        <div class="card_container">
            <h3 class="main_title">Tableau de bord du jour</h3>
            <div class="card_wrapper">
                <div class="card_one light-red">
                    <div class="card_header">
                        <div class="amount">
                            <span class="title">
                                Nombre de catégorie
                            </span>
                            <span class="amount_value">
                                <?php
                                if($categorieNumber > 0){
                                    echo $categorieNumber;
                                }
                                else{echo 0;}
                                ?>
                            </span>
                        </div>
                        <i class="fa-solid fa-table-columns icon"></i>

                    </div>
                    <span class="card_detail">**** **** **** ****</span>
                </div>
                <div class="card_one light-purple">
                    <div class="card_header">
                        <div class="amount">
                            <span class="title">
                                Nombre de Licencié
                            </span>
                            <span class="amount_value">
                            <?php
                                if($licenceNumber > 0){
                                    echo $licenceNumber;
                                }
                                else{echo 0;}
                                ?>
                            </span>
                        </div>
                    <i class="fa-solid fa-id-card icon dark-purple"></i>
                    </div>
                    <span class="card_detail">**** **** **** ****</span>
                </div>
                <div class="card_one light-green">
                    <div class="card_header">
                        <div class="amount">
                            <span class="title">
                                Nombre de Contact
                            </span>
                            <span class="amount_value">
                            <?php
                                if($contactNumber > 0){
                                    echo $contactNumber;
                                }
                                else{echo 0;}
                                ?>
                            </span>
                        </div>
                        <i class="fa-solid fa-phone icon dark-green"></i>

                    </div>
                    <span class="card_detail">**** **** **** ****</span>
                </div>
                <div class="card_one light-blue">
                    <div class="card_header">
                        <div class="amount">
                            <span class="title">
                                Nombre d'Educateur
                            </span>
                            <span class="amount_value">
                            <?php
                                if($educateurNumber > 0){
                                    echo $educateurNumber;
                                }
                                else{echo 0;}
                                ?>
                            </span>
                        </div>
                        <i class="fa-solid fa-person-chalkboard icon dark-blue"></i>


                    </div>
                    <span class="card_detail">**** **** **** ****</span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<?php }?>