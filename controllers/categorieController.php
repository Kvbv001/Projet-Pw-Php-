<?php
ini_set('display_errors', 1);

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../classes/models/Connexion.php');
require_once(__DIR__ . '/../classes/models/CategorieModel.php');
require_once(__DIR__ . "/../classes/dao/CategorieDAO.php");
/*$categorieD=new CategorieDAO(new Connexion());
$controller=new CategorieController($categorieD);
*/

$categorieController = new CategorieController(new CategorieDAO(new Connexion()));

// Vérifier si la requête AJAX est effectuée
if (isset($_GET['getAllCategories']) && $_GET['getAllCategories'] == true) {
    // Appeler la méthode qui récupère toutes les catégories
    $categories = $categorieController->getAllCategories();
    echo json_encode(['success' => true, 'liste_categories' => $categories]);
} else {
    // Traiter d'autres actions du contrôleur ici
    // ...
}

if (isset($_POST['btn_enregister'])) {
    $categorieController->addCategorie();
}

// RECUPERATION D'UNE CATEGORIE EN FONCTION DE SON ID
if (isset($_GET['idModif'])) {

    $id = $_GET['idModif'];
    $categoriedao = new CategorieDAO(new Connexion());
    $categorie = $categoriedao->getById($id)->toArray();
    if ($categorie) {
        echo json_encode([
            'categorie' => $categorie,
        ]);
        
    } else {
        echo json_encode([
            'categorie' => null
        ]);
    }
}

if (isset($_POST['bt_modifier'])) {
    $categorieController->editCategorie($_POST['id_modif']);

}


// Supprimer une categorie
if (isset($_POST['Supprimer'])) {
    $id = $_POST['Supprimer'];
    $categoriedao = new CategorieDAO(new Connexion());

    if ($categoriedao->deleteById($id)) {
        $message = "Catégorie  supprimée avec succès.";
        echo json_encode([
            'success' => 'true',
            'message' => $message
        ]);
    }else {
        $message = "Erreur impossible de supprimer cette catégorie.";
        echo json_encode([
            'success' => 'false',
            'message' => $message
        ]);
    }



}



class CategorieController
{
    private $categorieDao;

    public function __construct(CategorieDAO $categorieDao)
    {
        $this->categorieDao = $categorieDao;
    }

    public function index()
    {
        $categories = $this->categorieDao->getAll();
        include("../views/categorieView.php");
    }

    public function addCategorie()
    {

        // Récupérer les données du formulaire
        $nom = strtolower($_POST['nom']);
        $code = strtolower($_POST['code']);


        // Valider les données du formulaire (ajoutez des validations si nécessaire)

        // Créer un nouvel objet ContactModel avec les données du formulaire
        $categorie = new CategorieModel(0, $nom, $code);
        $codes = $this->categorieDao->getAllCode();
        $noms = $this->categorieDao->getAllNom();
        if(in_array($nom , $noms)){
            $message = "Cette catégorie existe déjà .";
            echo json_encode([
                'success' => 'false',
                'message' => $message
            ]);
        }
        else if(in_array($code , $codes)){
            $message = "Ce code existe déjà .";
            echo json_encode([
                'success' => 'false',
                'message' => $message
            ]);
        }
       // Appeler la méthode du modèle (ContactDAO) pour ajouter le contact
       else if ($this->categorieDao->create($categorie)) {
            $message = "Catégorie ajoutée avec succès.";
            echo json_encode([
                'success' => 'true',
                'message' => $message
            ]);
        } else {
            // Gérer les erreurs d'ajout de contact
            // echo "Erreur lors de l'ajout du contact.";
            $message = "Echec.";

            echo json_encode([
                'success' => 'false',
                'message' => $message
            ]);
        }
    }

    public function editCategorie($id) {
        // Récupérer le contact à modifier en utilisant son ID
        $categorie = $this->categorieDao->getById($id);
        $nom = strtolower($_POST['nom_modif']);
        $code = strtolower($_POST['code_modif']);
        $codes = $this->categorieDao->getAllCode();
        $noms = $this->categorieDao->getAllNom();
        
        if(in_array($nom , $noms) && ( $nom != strtolower($categorie->getNom()))){
            $message = "Cette catégorie existe deja .";
            echo json_encode([
                'success' => 'false',
                'message' => $message
            ]);
        }
        else if(in_array($code , $codes) && ($code != strtolower($categorie->getCode()))){
            $message = "Ce code existe deja .";
            echo json_encode([
                'success' => 'false',
                'message' => $message
            ]);
        } else{
        $categorie->setNom($nom);
        $categorie->setCode($code);
         if ($this->categorieDao->update($categorie)) {
            $message = "Catégorie Modifiée avec succès.";
            echo json_encode([
                'success' => 'true',
                'message' => $message
            ]);
        } else {
            $message = "Echec.";
            echo json_encode([
                'success' => 'false',
                'message' => $message
            ]);
        }
      }
    }

    public function getAllCategories()
    {
        $categories = $this->categorieDao->getAll();

        $formattedCategories = [];
        foreach ($categories as $categorie) {
            $formattedCategories[] = [
                'nom' => $categorie->getNom(),
                'code' => $categorie->getCode(),
                'idCategorie' => $categorie->getId(),

                // Ajoutez d'autres propriétés au besoin
            ];
        }

        return $formattedCategories;
    }
}
