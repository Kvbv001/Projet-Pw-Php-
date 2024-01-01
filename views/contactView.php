<?php 

if (!isset($_SESSION['educateur_id'])) {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: index.php?page=login"); // Remplacez 'page_connexion.php' par le chemin de votre page de connexion
    exit();
}
else{?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catégorie | Club Sportif</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/v/bs5/dt-1.13.6/datatables.min.css" rel="stylesheet">


    <link rel="stylesheet" href="./assets/css/style.css">

</head>

<body>
    <!-- sidebar -->
    <?php include_once './views/includes/menu.php' ?>


    <div class="main_content">
        <div class="header_wrapper">
            <div class="header_title">
                <span>Club Sportif</span>
                <h5>Contacts</h5>
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
            <h3 class="main_title">Les Contacts</h3>
            <table id="categorie_datatable" class="table table-striped dt-responsive nowrap w-100"></table>
        </div>
    </div>

    <!-- script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- pour les messages d'alert -->




    <script>
        $(document).ready(function() {
            // recuperer la liste des categorie au chargement de la page
            fetchAllCategorie();
        });

        // traitement pour enregistrer dans la bd à la soumission du formulaire
      
        // Variable globale pour stocker l'instance DataTable
        var contactDataTable;

        // Fonction pour récupérer la liste des contacts
        function fetchAllCategorie() {
            $.ajax({
                url: 'controllers/contactController.php',
                data: 'getAllContacts=' + true,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        // Détruire la DataTable existante si elle existe
                        if ($.fn.DataTable.isDataTable('#categorie_datatable')) {
                            contactDataTable.destroy();
                        }
                        console.log(data.liste_contacts)
                        // Initialiser la DataTable avec les données reçues
                        contactDataTable = $('#categorie_datatable').DataTable({
                            data: data.liste_contacts,

                            columns: [{
                                    data: 'nom',
                                    title: 'Nom'
                                },
                                {
                                    data: 'prenom',
                                    title: 'Prenom'
                                },
                                {
                                    data: 'email',
                                    title: 'Email'
                                },
                                {
                                    data: 'telephone',
                                    title: 'Telephone'
                                },
                               
                            ]
                        });
                    } else {
                        // Afficher un message d'erreur si la récupération a échoué
                        alert(data.message);
                    }
                },
                error: function(xhr, status, error) {
                    // Gérer les erreurs lors de la requête AJAX
                    alert('Erreur AJAX : ' + error);
                }
            });
        }


       
    </script>
</body>

</html>
<?php }?>