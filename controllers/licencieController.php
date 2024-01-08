<?php 

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../classes/models/Connexion.php');
require_once(__DIR__ . '/../classes/models/LicencieModel.php');
require_once(__DIR__ . "/../classes/dao/LicenceDAO.php");
require_once(__DIR__ . '/../classes/models/ContactModel.php');
require_once(__DIR__ . '/../classes/models/CategorieModel.php');
require_once(__DIR__ . "/../classes/dao/ContactDAO.php");
require_once(__DIR__ . "/../classes/dao/CategorieDAO.php");


$licenciesController = new LicencieController(new LicenceDAO(new Connexion()));
$categoriedao = new CategorieDAO(new Connexion());
$contactdao = new ContactDAO(new Connexion());

if (isset($_GET['getAllLicencies']) && $_GET['getAllLicencies'] == true) {
    // Appeler la méthode qui récupère toutes les catégories
    $licencies = $licenciesController->getAllLicencies();
    echo json_encode(['success' => true, 'liste_licencies' => $licencies]);
} else {
    // Traiter d'autres actions du contrôleur ici
    // ...
}

if (isset($_POST['btn_enregister'])) {
    $licenciesController->addLicencie();
}
if (isset($_GET['idModif'])) {

    $id = $_GET['idModif'];
    $licenciedao = new LicenceDAO(new Connexion());
    $licencie = $licenciedao->getById($id);
    if ($licencie !== null) {
        $contactId = $licencie->getIdContact();
        $contact = $contactId !== null ? $contactdao->getById($contactId) : null;
        $categorieId = $licencie->getIdCategorie();
        $categorie = $categorieId !== null ? $categoriedao->getById($categorieId) : null;
        
        if ($contact !== null) {
            echo json_encode([
                'licencie' => $licencie->toArray(),
                'contact' => $contact->toArray(),
                'categorie' => $categorie->toArray()
            ]);
        } else {
            echo json_encode([
                'licencie' => $licencie->toArray(),
                'contact' => null
            ]);
        }
    } else {
        // Gérer le cas où $licencie est null
        echo json_encode([
            'licencie' => null,
            'contact' => null
        ]);
    }
}

 
if (isset($_POST['btn_modifier'])) {
    $licenciesController->editLicencie($_POST['id_modif']);
}

if (isset($_POST['Supprimer'])) {
    $id = $_POST['Supprimer'];
    $licenciedao = new LicenceDAO(new Connexion());
    $licencie = $licenciedao->getById($id);
    $contactid = $licencie->getIdContact();

    if ($licenciedao->deleteById($id)) {
       if($contactdao->deleteById($contactid)){
        $message = "Licencié  supprimé avec succès.";
        echo json_encode([
            'success' => 'true',
            'message' => $message
        ]);
       }else{
        $message = "Erreur impossible de supprimer le Contact";
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

class LicencieController{

    private $licencieDao;

    public function __construct(LicenceDAO $licenceDAO){
        $this->licencieDao = $licenceDAO;
    }

    public function index()
    {
        $licencies = $this->licencieDao->getAll();
        include("../views/licencieView.php");
    }

    public function getAllLicencies()
    {
        $licencies = $this->licencieDao->getAll();

        $categoriedao = new CategorieDAO(new Connexion());
        $contactdao = new ContactDAO(new Connexion());

        $formattedLicencies = [];

        foreach ($licencies as $licencie) {
            $contactId = $licencie->getIdContact();
            $categorieId = $licencie->getIdCategorie();
            

            $contact = $contactdao->getById($contactId);
            $categorie = $categoriedao->getById($categorieId);

            $formattedLicencies[] = [
                'nom' => $licencie->getNom(),
                'prenom' => $licencie->getPrenom(),
                'numeroLicence' => $licencie->getId(),
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
                ]
                
                // Ajoutez d'autres propriétés au besoin
            ];
        }

        return $formattedLicencies;
    }

    public function addLicencie() {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $nomC = $_POST['nomContact'];
        $prenomC = $_POST['prenomContact'];
        $email = strtolower($_POST['email']);
        $telephone = strtolower($_POST['telephone']);
        $id = $_POST['categories'];

        
        // Récupérer la liste des e-mails existants
        $contactsDao = new ContactDAO(new Connexion());
        $emails = $contactsDao->getAllEmail();
        $telephones =$contactsDao->getAllTelephone();

    
        if (in_array($email, $emails)) {
            $message = "Ce mail existe déjà";
    
            echo json_encode([
                'success' => false,
                'message' => $message
            ]);
        }else if (in_array($telephone, $telephones)) {
            $message = "Ce Numero de telephone existe déjà existe deja .";
            echo json_encode([
                'success' => 'false',
                'message' => $message
            ]);
        } else {
            // Créer une nouvelle instance de contact
            $contact = new ContactModel(0, $nomC, $prenomC, $email, $telephone);
            // Ajouter le contact
            $contactsDao->create($contact);
            // Récupérer l'ID du dernier contact ajouté
            $lastInsertedId = $contactsDao->getLastId();        
            // Créer une instance de LicencieModel avec l'ID du contact
            $licencie = new LicencieModel(0, $nom, $prenom, $id, $lastInsertedId);
            if ($this->licencieDao->create($licencie)) {
                $message = "Licencié ajouté avec succès.";
                echo json_encode([
                    'success' => 'true',
                    'message' => $message
                ]);
            } else {
                $contactsDao->deleteById($lastInsertedId);
                $message = "Echec.";
                echo json_encode([
                    'success' => 'false',
                    'message' => $message
                ]);
            }
        }
    }


    public function editLicencie($id){
        $licencie = $this->licencieDao->getById($id);
    
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
        } else {
            $licencie->setNom($_POST['nom_modif']);
            $licencie->setPrenom($_POST['prenom_modif']);
            $licencie->setIdCategorie($_POST['categories_modif']);
    
            $contact->setEmail($email);
            $contact->setTelephone($_POST['telephone_modif']);
            $contact->setNom($_POST['nomContact_modif']);
            $contact->setPrenom($_POST['prenomContact_modif']);
    
            if ($contactsDao->update($contact)) {
                if ($this->licencieDao->update($licencie)) {
                    $message = "Licencié modifié avec succès.";
                    echo json_encode([
                        'success' => 'true',
                        'message' => $message
                    ]);
                } else {
                    $message = "Licencié non enregistré.";
                    echo json_encode([
                        'success' => 'false',
                        'message' => $message
                    ]);
                }
            } else {
                $message = "Echec.";
                echo json_encode([
                    'success' => 'false',
                    'message' => $message
                ]);
            }
        }
    }
    
    
}

?>
