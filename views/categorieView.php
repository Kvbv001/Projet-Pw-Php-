<?php 

if (!isset($_SESSION['educateur_id'])) {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: index.php?page=login"); // Remplacez 'page_connexion.php' par le chemin de votre page de connexion
    exit();
}
else{
    require_once(__DIR__ . "/../classes/dao/EducateurDAO.php");
    require_once(__DIR__ . '/../classes/models/EducateurModel.php');


    ?>

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
                <h5>Catégories</h5>
            </div>

            <div class="user_info">
                <div class="search_box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="rechercher">
                </div>
                <?php 

                if (isset($_SESSION['educateur_id'])) {
                    // L'utilisateur est connecté
                    $educateurDao = new EducateurDAO(new Connexion());
                    $educateur = $educateurDao->getById($_SESSION['educateur_id']);
            
                    
                    echo '<div class="user_profile">';
                    echo '<img src="./assets/images/default-user.png" alt="User Image">';
                    echo '<div class="user_details">';
                    echo '<span>'. $educateur->getNom()." " .$educateur->getPrenom(). '</span>';
                    echo '</div>';
                    echo '</div>';
            
                    // Affichage du menu déroulant pour la déconnexion
                    echo '<div class="logout_menu">';
                    echo '<select onchange="window.location.href=this.value">';
                    echo '<option value="" disabled selected>Deconnexion</option>';
                    echo '<option value="deconnexion.php">Se déconnecter</option>';
                    echo '</select>';
                    echo '</div>';
                }
                ?>

            </div>
        </div>
        <div class="card_container">

            <!-- Modal ajouter catégorie -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas_ajouter" aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">
                    <h6 class="offcanvas-title" id="offcanvasRightLabel">Nouvelle catégorie</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div id="errorDiv"></div>
                    <form class="form-control" id="form_ajouter" action="controllers/categorieController.php" method="POST">
                        <div class="mb-3 col-12">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" placeholder="Catégorie 1">
                            <div id="nomHelp" class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="code" class="form-label">Code racourci</label>
                            <input type="text" class="form-control" id="code" name="code" placeholder="">
                            <div id="codeHelp" class="form-text"></div>
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>
            <!-- End Modal ajouter catégorie -->

            <!-- Modal modifier catégorie -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas_modifier" aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">
                    <h6 class="offcanvas-title" id="offcanvasRightLabel">Modification catégorie</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div id="errorDiv_modif"></div>
                    <form class="form-control" id="form_modifier" action="controllers/categorieController.php" method="POST">
                        <div class="mb-3 col-12">
                            <label for="nom_modif" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom_modif" name="nom_modif" placeholder="Catégorie 1">
                        </div>
                        <div class="mb-3">
                            <label for="code_modif" class="form-label">Code racourci</label>
                            <input type="text" class="form-control" id="code_modif" name="code_modif" placeholder="">
                        </div>

                        <input type="hidden" name="id_modif" id="id_modif">
                        <button type="submit" class="btn btn-primary">Modifier</button>
                    </form>
                </div>
            </div>
            <!-- End Modal modifier catégorie -->
             <!-- Modal Detail -->
             <div class="modal fade" id="ModalDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Détail Catégorie</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-2">Nom : <span class="detailNom"></span></p>
                            <p class="mb-2">Code : <span class="detailCode"></span></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin Modal Detail -->


            <h3 class="main_title">Les Catégories</h3>

            <div class="d-flex align-items-center justify-content-end mb-3">

                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_ajouter" aria-controls="offcanvas_ajouter">Nouvelle catégorie</button>
            </div>

            <div id="successDiv"></div>



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
        $('#form_ajouter').on('submit', function(e) {
            e.preventDefault();

            var form = $('#form_ajouter');
            var method = form.prop('method');
            var url = form.prop('action');

            $.ajax({
                type: method,
                data: form.serialize() + "&btn_enregister=" + true,
                url: url,
                success: function(result) {
                    var donnee = JSON.parse(result);
                    if (donnee['success'] === 'true') {

                        fetchAllCategorie();
                        var message = donnee['message'];
                        var successMessage = '<div class="alert alert-success alert-dismissible fade show" role="alert"><ul>';

                        successMessage += '<p>' + message + '</p>';

                        successMessage += '</ul></div>';
                        $('#successDiv').html(successMessage);
                        $('#offcanvas_ajouter').offcanvas('hide');

                    } else {
                        // Erreur
                        var message = donnee['message'];
                        var errorMessage = '<div class="alert alert-danger mt-2 " role="alert"><ul>';

                        errorMessage += '<li>' + message + '</li>';
                       
                        errorMessage += '</ul></div>';
                        $('#errorDiv').html(errorMessage);
                    }
                }
            })
        });



        $('#form_modifier').on('submit', function(e) {
            e.preventDefault();

            
            var form = $('#form_modifier');
            var method = form.prop('method');
            var url = form.prop('action');

            $.ajax({
                type: method,
                data: form.serialize() + "&bt_modifier=" + true,
                url: url,
                success: function(result) {
                    var donnee = JSON.parse(result);
                    if (donnee['success'] === 'true') {

                        fetchAllCategorie();
                        var message = donnee['message'];
                        var successMessage = '<div class="alert alert-success alert-dismissible fade show" role="alert"><ul>';

                        successMessage += '<p>' + message + '</p>';

                        successMessage += '</ul></div>';
                        $('#successDiv').html(successMessage);
                        $('#offcanvas_modifier').offcanvas('hide');

                    } 
                    else {
                        // Erreur
                        
                        var message = donnee['message'];
                        var errorMessage = '<div class="alert alert-danger mt-2" role="alert"><ul>';

                        // Itérer sur l'objet d'erreurs                        
                        errorMessage += '<p>' + message + '</p>';

                        errorMessage += '</ul></div>';
                        $('#errorDiv_modif').html(errorMessage);
                        
                    }
                }
            })
        });

        // Variable globale pour stocker l'instance DataTable
        var categorieDataTable;

        // Fonction pour récupérer la liste des catégories
        function fetchAllCategorie() {
            $.ajax({
                url: 'controllers/categorieController.php',
                data: 'getAllCategories=' + true,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        // Détruire la DataTable existante si elle existe
                        if ($.fn.DataTable.isDataTable('#categorie_datatable')) {
                            categorieDataTable.destroy();
                        }
                        console.log(data.liste_categories)
                        // Initialiser la DataTable avec les données reçues
                        categorieDataTable = $('#categorie_datatable').DataTable({
                            data: data.liste_categories,

                            columns: [{
                                    data: 'nom',
                                    title: 'Nom'
                                },
                                {
                                    data: 'code',
                                    title: 'Code'
                                },
                                {
                                    title: 'Actions',
                                    data: null,
                                    render: function(data, type, row) {
                                        // Ajouter des icônes d'action pour chaque ligne
                                        return '<button class="me-4 bg-transparent text-info"  onclick="modifier(' + row.idCategorie + ')"><i class="fa-solid fa-pen-to-square"></i></button>' +
                                            '<button class="me-4 bg-transparent text-warning" data_id=" " onclick="voir(' + row.idCategorie + ')"><i class="fa-solid fa-eye "></i></button>' +
                                            '<button class="bg-transparent text-danger" data_id=" " onclick="supprimer(' + row.idCategorie + ')"><i class="fa-solid fa-trash "></i></button>';
                                    }
                                }
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


        function voir(id) {
            $.ajax({
                type: "GET",
                data: "idModif=" + id, //Envois de l'id selectionné
                url: "controllers/categorieController.php",
                success: function(result) {
                    var donnees = JSON.parse(result);
                    if (donnees['categorie'] !== null) {
                        let categorie = donnees['categorie']

                        $('.detailNom').text(categorie['nom'])
                        $('.detailCode').text(categorie['code'])

                        // afficher le modal
                        $('#ModalDetail').modal('show');

                    }
                }
            })
        }



        // fonction pour modifier
        function modifier(id) {

            $.ajax({
                type: "GET",
                data: "idModif=" + id, //Envois de l'id selectionné
                url: "controllers/categorieController.php",
                success: function(result) {
                    var donnees = JSON.parse(result);
                    if (donnees['categorie'] !== null) {
                        let categorie = donnees['categorie']
                        $("#id_modif").val(categorie['id']);
                        $("#nom_modif").val(categorie['nom']);
                        $("#code_modif").val(categorie['code']);

                        // afficher le modal
                        $('#offcanvas_modifier').offcanvas('show');

                    }
                }
            })
        }

        // supprimer une categorie
        function supprimer(id) {
            $.ajax({
                type: "GET",
                data: "idModif=" + id, //Envois de l'id selectionné
                url: "controllers/categorieController.php",
                success: function(result) {
                    var donnees = JSON.parse(result);
                    if (donnees['categorie'] !== null) {

                        let categorie = donnees['categorie']

                        Swal.fire({
                            title: "Êtes-vous sûr ?",
                            text: "Vous allez supprimer <<" + categorie['nom'] + ">>",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            cancelButtonText: "Annuler",
                            confirmButtonText: "Oui, Supprimé !"
                        }).then((result) => {
                            if (result.isConfirmed) {

                                $.ajax({
                                    type: "POST",
                                    url: "controllers/categorieController.php",
                                    data: "Supprimer=" + id,
                                    success: function(response) {
                                        var donnee = JSON.parse(response);
                                        if (donnee['success'] === 'true') {
                                            fetchAllCategorie()

                                            Swal.fire({
                                                title: "Supprimé !",
                                                text: donnee['message'],
                                                icon: "success"
                                            });
                                        }else{
                                            Swal.fire({
                                                title: "Supprimé !",
                                                text: donnee['message'],
                                                icon: "danger"
                                            });
                                        }

                                    }
                                });

                            }
                        });



                    }
                }
            })
        }
    </script>
</body>

</html>
<?php }?>