<?php 

if (!isset($_SESSION['educateur_id'])) {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location:index.php?page=login"); // Remplacez 'page_connexion.php' par le chemin de votre page de connexion
    exit();
}
else{
    require_once(__DIR__ . "/../classes/dao/EducateurDAO.php");
    require_once(__DIR__ . '/../classes/models/EducateurModel.php');

    ?>

<?php 
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../classes/models/Connexion.php');
require_once(__DIR__ . '/../classes/models/CategorieModel.php');
require_once(__DIR__ . '/../controllers/CategorieController.php');

require_once(__DIR__ . "/../classes/dao/CategorieDAO.php");
/*$categorieD=new CategorieDAO(new Connexion());
$controller=new CategorieController($categorieD);
*/

$categorieController = new CategorieController(new CategorieDAO(new Connexion()));
$categories = $categorieController->getAllCategories();
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
                <h5>Licenciés</h5>
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
                    <h6 class="offcanvas-title" id="offcanvasRightLabel">Nouveau licencié</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div id="errorDiv"></div>
                    <form class="form-control" id="form_ajouter" action="controllers/licencieController.php" method="POST">
                        <div class="mb-3 col-12">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" placeholder="">
                            <div id="nomHelp" class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prenom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" placeholder="">
                            <div id="codeHelp" class="form-text"></div>
                        </div>
                        <div class="mb-3 col-12">
                            <label for="nomContact" class="form-label">Nom Du Contact</label>
                            <input type="text" class="form-control" id="nomContact" name="nomContact" placeholder="">
                            <div id="nomHelp" class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="prenomContact" class="form-label">Prenom Du Contact</label>
                            <input type="text" class="form-control" id="prenomContact" name="prenomContact" placeholder="">
                            <div id="codeHelp" class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Du Contact</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="">
                            <div id="codeHelp" class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="telephone" class="form-label">Telephone Du Contact</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="">
                            <div id="codeHelp" class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="categories">Catégories</label>
                           <select name="categories" id="categories">
                            <option value="">--Veuillez choisir la catégories--</option>
                            <?php foreach($categories as $categorie){ ?>
                            <option value="<?php echo $categorie['idCategorie'] ?>"><?php echo $categorie['nom'] ?></option>
                            <?php }; ; ?>

                           </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>
            <!-- End Modal ajouter catégorie -->

            <!-- Modal modifier catégorie -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas_modifier" aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">
                    <h6 class="offcanvas-title" id="offcanvasRightLabel">Modification Licencié</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div id="errorDiv_modif"></div>
                    <form class="form-control" id="form_modifier" action="controllers/licencieController.php" method="POST">
                    <div class="mb-3 col-12">
                            <label for="nom_modif" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom_modif" name="nom_modif" placeholder="">
                            <div id="nomHelp" class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="prenom_modif" class="form-label">Prenom</label>
                            <input type="text" class="form-control" id="prenom_modif" name="prenom_modif" placeholder="">
                            <div id="codeHelp" class="form-text"></div>
                        </div>
                        <div class="mb-3 col-12">
                            <label for="nomContact_modif" class="form-label">Nom Du Contact</label>
                            <input type="text" class="form-control" id="nomContact_modif" name="nomContact_modif" placeholder="">
                            <div id="nomHelp" class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="prenomContact_modif" class="form-label">Prenom Du Contact</label>
                            <input type="text" class="form-control" id="prenomContact_modif" name="prenomContact_modif" placeholder="">
                            <div id="codeHelp" class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="email_modif" class="form-label">Email Du Contact</label>
                            <input type="email" class="form-control" id="email_modif" name="email_modif" placeholder="">
                            <div id="codeHelp" class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="telephone_modif" class="form-label">Telephone Du Contact</label>
                            <input type="tel" class="form-control" id="telephone_modif" name="telephone_modif" placeholder="">
                            <div id="codeHelp" class="form-text"></div>
                        </div>
                        <div class="mb-3">
                            <label for="categories_modif">Catégories</label>
                           <select name="categories_modif" id="categories_modif">
                            <option value="">--Veuillez choisir la catégories--</option>
                            <?php foreach($categories as $categorie){ ?>
                            <option value="<?php echo $categorie['idCategorie'] ?>"><?php echo $categorie['nom'] ?></option>
                            <?php }; ; ?>

                           </select>
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
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Détail Licencié</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-2">Nom : <span class="detailNom"></span></p>
                            <p class="mb-2">Prénom : <span class="detailPrenom"></span></p>
                            <p class="mb-2">Nom Du Contact : <span class="detailNomContact"></span></p>
                            <p class="mb-2">Prénom Du Contact : <span class="detailPrenomContact"></span></p>
                            <p class="mb-2">Catégorie : <span class="detailCategrie"></span></p>
                            <p class="mb-2">Téléphone Du Contact : <span class="detailTelephone"></span></p>
                            <p class="mb-2">Email Du Contact : <span class="detailEmail"></span></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin Modal Detail -->


            <h3 class="main_title">Les Licenciés</h3>

            <div class="d-flex align-items-center justify-content-end mb-3">

                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_ajouter" aria-controls="offcanvas_ajouter">Nouveau Licencié</button>
            </div>

            <div id="successDiv"></div>



            <table id="licencie_datatable" class="table table-striped dt-responsive nowrap w-100"></table>


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
            fetchAllLicencies();
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

                        fetchAllLicencies();
                        var message = donnee['message'];
                        var successMessage = '<div class="alert alert-success alert-dismissible fade show" role="alert"><ul>';

                        successMessage += '<p>' + message + '</p>';

                        successMessage += '</ul></div>';
                        $('#successDiv').html(successMessage);
                        $('#offcanvas_ajouter').offcanvas('hide');

                    } else {
                        // Erreur
                        var message = donnee['message'];
                        var errorMessage = '<div class="alert alert-danger mt-2" role="alert"><ul>';

                        // Itérer sur l'objet d'erreurs
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
                data: form.serialize() + "&btn_modifier=" + true,
                url: url,
                success: function(result) {
                    var donnee = JSON.parse(result);
                    if (donnee['success'] === 'true') {

                        fetchAllLicencies();
                        var message = donnee['message'];
                        var successMessage = '<div class="alert alert-success alert-dismissible fade show" role="alert"><ul>';

                        successMessage += '<p>' + message + '</p>';

                        successMessage += '</ul></div>';
                        $('#successDiv').html(successMessage);
                        $('#offcanvas_modifier').offcanvas('hide');

                    } else {
                        // Erreur
                        var message = donnee['message'];
                        var errorMessage = '<div class="alert alert-danger mt-2" role="alert"><ul>';                        
                                errorMessage += '<li>' + message + '</li>';
                        

                        errorMessage += '</ul></div>';
                        $('#errorDiv_modif').html(errorMessage);
                    }
                }
            })
        });


        // Variable globale pour stocker l'instance DataTable
        var licencieDataTable;

        // Fonction pour récupérer la liste des catégories
        function fetchAllLicencies() {
            $.ajax({
                url: 'controllers/licencieController.php',
                data: 'getAllLicencies=' + true,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        // Détruire la DataTable existante si elle existe
                        if ($.fn.DataTable.isDataTable('#licencie_datatable')) {
                            licencieDataTable.destroy();
                        }
                        console.log(data.liste_licencies)
                        // Initialiser la DataTable avec les données reçues
                        licencieDataTable = $('#licencie_datatable').DataTable({
                            data: data.liste_licencies,

                            columns: [{
                                    data: 'nom',
                                    title: 'Nom'
                                },
                                {
                                    data: 'prenom',
                                    title: 'Prenom'
                                },
                                {
                                    data: 'categorie.nom',
                                    title: 'Catégorie'
                                },
                                {
                                    data: 'contact.nom',
                                    title: 'Contact Nom'
                                },
                                {
                                    data: 'contact.prenom',
                                    title: 'Contact Prenom'
                                },
                                {
                                    data: 'contact.email',
                                    title: 'Contact Email'
                                },
                                {
                                    title: 'Actions',
                                    data: null,
                                    render: function(data, type, row) {
                                        // Ajouter des icônes d'action pour chaque ligne
                                        return '<button class="me-4 bg-transparent text-info"  onclick="modifier(' + row.numeroLicence + ')"><i class="fa-solid fa-pen-to-square"></i></button>' +
                                            '<button class="me-4 bg-transparent text-warning"  onclick="voir(' + row.numeroLicence + ')"><i class="fa-solid fa-eye "></i></button>' +
                                            '<button class="bg-transparent text-danger"  onclick="supprimer(' + row.numeroLicence + ')"><i class="fa-solid fa-trash "></i></button>';
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
                url: "controllers/licencieController.php",
                success: function(result) {

                    var donnees = JSON.parse(result);
                    console.log(result);
                    if (donnees['licencie'] !== null) {
                        let licencie = donnees['licencie']
                        let contact = donnees['contact']
                        let categorie = donnees['categorie']

                        $('.detailNom').text(licencie['nom'])
                        $('.detailPrenom').text(licencie['prenom'])
                        $('.detailPrenomContact').text(contact['prenom'])
                        $('.detailNomContact').text(contact['nom'])
                        $('.detailEmail').text(contact['email'])
                        $('.detailTelephone').text(contact['telephone'])
                        $('.detailCategrie').text(categorie['nom'])

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
                url: "controllers/licencieController.php",
                success: function(result) {
                    var donnees = JSON.parse(result);
                    if (donnees['licencie'] !== null && donnees['contact'] !== null ) {
                        let licencie = donnees['licencie'];
                        let contact = donnees['contact'];

                        $("#id_modif").val(licencie['id']);
                        $("#nom_modif").val(licencie['nom']);
                        $("#prenom_modif").val(licencie['prenom']);
                        $("#nomContact_modif").val(contact['nom']);
                        $("#prenomContact_modif").val(contact['prenom']);
                        $("#telephone_modif").val(contact['telephone']);
                        $("#email_modif").val(contact['email']);
                        $("#categories_modif").val(licencie['idCategorie']);

                        // afficher le modal
                        $('#offcanvas_modifier').offcanvas('show');

                    }
                }
            })
        }


        // supprimer un licencié

        function supprimer(id) {
            $.ajax({
                type: "GET",
                data: "idModif=" + id, //Envois de l'id selectionné
                url: "controllers/licencieController.php",
                success: function(result) {
                    var donnees = JSON.parse(result);
                    if (donnees['licencie'] !== null) {

                        let licencie = donnees['licencie']

                        Swal.fire({
                            title: "Êtes-vous sûr ?",
                            text: "Vous allez supprimer le licencié <<" + licencie['nom'] + ' ' + licencie['prenom'] + ">>",
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
                                    url: "controllers/licencieController.php",
                                    data: "Supprimer=" + id,
                                    success: function(response) {
                                        var donnee = JSON.parse(response);
                                        if (donnee['success'] === 'true') {
                                            fetchAllLicencies()

                                            Swal.fire({
                                                title: "Supprimé !",
                                                text: donnee['message'],
                                                icon: "success"
                                            });
                                        } else {
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
<?php } ?>
