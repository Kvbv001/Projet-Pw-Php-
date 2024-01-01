<?php

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../classes/models/Connexion.php');
require_once(__DIR__ . '/../classes/models/EducateurModel.php');
require_once(__DIR__ . '/../classes/models/LicencieModel.php');
require_once(__DIR__ . '/../classes/models/ContactModel.php');
require_once(__DIR__ . '/../classes/models/CategorieModel.php');
require_once(__DIR__ . "/../classes/dao/EducateurDAO.php");
require_once(__DIR__ . "/../classes/dao/LicenceDAO.php");
require_once(__DIR__ . "/../classes/dao/ContactDAO.php");
require_once(__DIR__ . "/../classes/dao/CategorieDAO.php");




$educateursController = new EducateurController(new EducateurDAO(new Connexion()));
$contactdao = new ContactDAO(new Connexion());
$licenciedao = new LicenceDAO(new Connexion());
$categoriedao = new CategorieDAO(new Connexion());



if (isset($_GET['getAllEducateurs']) && $_GET['getAllEducateurs'] == true) {
    // Appeler la méthode qui récupère toutes les catégories
    $educateurs = $educateursController->getAllEducateurs();
    echo json_encode(['success' => true, 'liste_educateurs' => $educateurs]);
} else {
    // Traiter d'autres actions du contrôleur ici
    // ...
}

if (isset($_POST['btn_enregister'])) {
    $educateursController->addEducateur();
}

if (isset($_GET['idModif'])) {

    $id = $_GET['idModif'];
    $educateurDao = new EducateurDAO(new Connexion());
    $educateur = $educateurDao->getById($id);
    if ($educateur !== null) {
        $licencie = $licenciedao->getById($educateur->getNumeroLicence());
        if($licencie != null){
            $contactId = $licencie->getIdContact();
            $contact = $contactId !== null ? $contactdao->getById($contactId) : null;
            $categorieId = $licencie->getIdCategorie();
            $categorie = $categorieId !== null ? $categoriedao->getById($categorieId) : null;
            if ($contact !== null) {
                echo json_encode([
                    'educateur' =>$educateur->toArray(),
                    'licencie' => $licencie->toArray(),
                    'contact' => $contact->toArray(),
                    'categorie' => $categorie->toArray()
                ]);
            } else {
                echo json_encode([
                    'educateur' => null,
                    'licencie' => null,
                    'contact' => null,
                    'categorie' => null
                ]);
            }
        }else{
            echo json_encode([
                'educateur' => null,
                'licencie' => null,
                'contact' => null,
                'categorie' => null
            ]);
        }
    } else {
        // Gérer le cas où $licencie est null
        echo json_encode([
            'educateur' => null,
            'licencie' => null,
            'contact' => null,
            'categorie' => null
        ]);
    }
 }

 if (isset($_POST['btn_modifier'])) {
   $educateursController->editEducateur($_POST['id_modif']);
}

if (isset($_POST['Supprimer'])) {
    $id = $_POST['Supprimer'];
    $educateurdao = new EducateurDAO(new Connexion());
    $educateur = $educateurdao->getById($id);
    $licencie = $licenciedao->getById($educateur->getNumeroLicence());
    $contactid = $licencie->getIdContact();
    if ($educateurdao->deleteById($id)) {
       if($licenciedao->deleteById($licencie->getId())){
        if($contactdao->deleteById($contactid)){
            $message = "Educateur supprimé avec succès.";
            echo json_encode([
            'success' => 'true',
            'message' => $message
        ]);
        }else{
            $message = "Erreur impossible de supprimer le contact";
            echo json_encode([
            'success' => 'false',
            'message' => $message
        ]);
        }
       }else{
            $message = "Erreur impossible de supprimer le Licencié";
            echo json_encode([
            'success' => 'false',
            'message' => $message
        ]);
       }
    }else {
            $message = "Erreur impossible de supprimer ce licencié.";
            echo json_encode([
            'success' => 'false',
            'message' => $message
        ]);
    }
}

class EducateurController{
    private $educateurDao ;

    public function __construct(EducateurDAO $educateurDao){
        $this->educateurDao=$educateurDao;
    }

    public function index()
    {
        $educateurs = $this->educateurDao->getAll();
        include("../views/educateurView.php");
    }


   
    public function getAllEducateurs()
    {
        $educateurs = $this->educateurDao->getAll();
        $categoriedao = new CategorieDAO(new Connexion());
        $contactdao = new ContactDAO(new Connexion());
        $licenciedao = new LicenceDAO(new Connexion());


        $formattedEducateur = [];
        foreach ($educateurs as $educateur) {
            $licencie =$licenciedao->getById($educateur->getNumeroLicence()) ;
            $contactId = $licencie->getIdContact();
            $categorieId = $licencie->getIdCategorie();

            $contact = $contactdao->getById($contactId);
            $categorie = $categoriedao->getById($categorieId);

            $formattedEducateur[] = [
                'nom' => $educateur->getNom(),
                'prenom' => $educateur->getPrenom(),
                'numero' => $educateur->getId(),
                'email' => $educateur->getEmail(),
                'admin' => $educateur->getAdmin(),
                'contact' => [
                    'nom' => $contact->getNom(),
                    'prenom' => $contact->getPrenom(),
                    'email' => $contact->getEmail(),
                    'telephone' => $contact->getTelephone(),
                    // Ajoutez d'autres propriétés au besoin
                ],
                'categorie' => [
                    'nom' => $categorie->getNom(),
                    // Ajoutez d'autres propriétés au besoin
                ],

                // Ajoutez d'autres propriétés au besoin
            ];
        }

        return $formattedEducateur;
    }

    public function addEducateur(){

        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $id = $_POST['categories'];
        $admin = $_POST['admin'];
        $password = $_POST['password'];
        $passwordC = $_POST['passwordC'];
       
        $contactsDao = new ContactDAO(new Connexion());
        $licenceDao = new LicenceDAO(new Connexion());
        $emails = $contactsDao->getAllEmail();
    
        if (in_array($email, $emails)) {
            $message = "Ce mail existe déjà";
    
            echo json_encode([
                'success' => 'false',
                'message' => $message
            ]);
        }else if($password != $passwordC){
            $message = "Les deux mots de passes sont différents";
    
            echo json_encode([
                'success' => 'false',
                'message' => $message
            ]);
        }else{
            $contact = new ContactModel(0, $nom, $prenom, $email, $telephone);
            // Ajouter le contact
            $contactsDao->create($contact);
            // Récupérer l'ID du dernier contact ajouté
            $lastInsertedId = $contactsDao->getLastId();
            // Créer une instance de LicencieModel avec l'ID du contact
            $licencie = new LicencieModel(0, $nom, $prenom, $id, $lastInsertedId);
            if ($licenceDao->create($licencie)) {
                $lastLicenceId = $licenceDao->getLastId();
                $passwordHash = password_hash($password, PASSWORD_BCRYPT);
                $educateur = new EducateurModel(0,$nom ,$prenom,$email,$passwordHash,$admin,$lastLicenceId);
                if($this->educateurDao->create($educateur)){
                    $message = "Educateur ajouté avec succès";
                echo json_encode([
                    'success' => 'true',
                    'message' => $message
                ]);
                }else{
                    $contactsDao->deleteById($lastInsertedId);
                    $licenceDao->deleteById($lastLicenceId);
                    $message = "Echec Educateur.";
                    echo json_encode([
                        'success' => 'false',
                        'message' => $message
                    ]);
                }
            } else {
                $contactsDao->deleteById($lastInsertedId);
                $message = "Echec licencié.";
                echo json_encode([
                    'success' => 'false',
                    'message' => $message
                ]);
            }  

        }

    }

    public function editEducateur($id){
        $educateur = $this->educateurDao->getById($id);
        $licenceDao = new LicenceDAO(new Connexion());
        $licencie = $licenceDao->getById($educateur->getNumeroLicence()); 
        $contactsDao = new ContactDAO(new Connexion());
        $contact = $contactsDao->getById($licencie->getIdContact());
        $emails = $contactsDao->getAllEmail();
        $email = $_POST['email_modif'];

        if (in_array($email, $emails) && $email != $contact->getEmail()) {
            $message = "Ce mail existe déjà";
    
            echo json_encode([
                'success' => 'false',
                'message' => $message
            ]);
        }else{

            $educateur->setAdmin($_POST['admin_modif']);
            $educateur->setEmail($email);
            $educateur->setNom($_POST['nom_modif']);
            $educateur->setPrenom($_POST['prenom_modif']);
            $educateur->setPassword($_POST['password_modif']);

            $licencie->setNom($_POST['nom_modif']);
            $licencie->setPrenom($_POST['prenom_modif']);
            $licencie->setIdCategorie($_POST['categories_modif']);
    
            $contact->setEmail($email);
            $contact->setTelephone($_POST['telephone_modif']);
            $contact->setNom($_POST['nom_modif']);
            $contact->setPrenom($_POST['prenom_modif']);

            if ($contactsDao->update($contact)) {
                if ($licenceDao->update($licencie)) {
                   if($this->educateurDao->update($educateur)){
                    $message = "Educateur enregistré avec success.";
                    echo json_encode([
                        'success' => 'true',
                        'message' => $message
                    ]);
                   }else{
                    $message = "Echec Educateur non enregistré.";
                    echo json_encode([
                        'success' => 'false',
                        'message' => $message
                    ]);
                   }
                } else {
                    $message = "Echec licencié non enregistré.";
                    echo json_encode([
                        'success' => 'false',
                        'message' => $message
                    ]);
                }
            } else {
                $message = "Echec Contact.";
                echo json_encode([
                    'success' => 'false',
                    'message' => $message
                ]);
            }
        }

    }



}



?>