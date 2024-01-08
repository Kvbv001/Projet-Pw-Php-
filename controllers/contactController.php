<?php
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../classes/models/Connexion.php');
require_once(__DIR__ . '/../classes/models/ContactModel.php');
require_once(__DIR__ . "/../classes/dao/ContactDAO.php");

$contactController = new ContactController(new ContactDAO(new Connexion()));
$contactDao = new ContactDAO(new Connexion());

if (isset($_POST['btn_enregister'])) {
    $contactController->addContact();
}

if (isset($_POST['btn_modifier'])) {
    $contactController->editContact($_POST['id_modif']);
}


if (isset($_GET['idModif'])) {

    $id = $_GET['idModif'];
    $contact = $contactDao->getById($id)->toArray();
    if ($contact !== null) {

        echo json_encode([
            'contact' => $contact
        ]);
    } else {
        // Gérer le cas où $licencie est null
        echo json_encode([
            'contact' => null
        ]);
    }
}


if (isset($_POST['Supprimer'])) {
    $id = $_POST['Supprimer'];

    if ($contactDao->deleteById($id)) {
        $message = "contact  supprimée avec succès.";
        echo json_encode([
            'success' => 'true',
            'message' => $message
        ]);
    } else {
        $message = "Erreur impossible de supprimer ce contact car il est déjà associé à un liciencé .";
        echo json_encode([
            'success' => 'false',
            'message' => $message
        ]);
    }
}



if (isset($_GET['getAllContacts']) && $_GET['getAllContacts'] == true) {
    // Appeler la méthode qui récupère toutes les catégories
    $contacts = $contactController->getAllContacts();
    echo json_encode(['success' => true, 'liste_contacts' => $contacts]);
} else {
    // Traiter d'autres actions du contrôleur ici
    // ...
}

class ContactController
{
    private $contactDAO;

    public function __construct(ContactDAO $contactDAO)
    {
        $this->contactDAO = $contactDAO;
    }

    public function index()
    {
        $contacts = $this->contactDAO->getAll();

        // Inclure la vue pour afficher la liste des contacts
        include('../views/contactView.php');
    }

    public function addContact()
    {
        // Récupérer les données du formulaire
        $nom = strtolower($_POST['nom']);
        $prenom = strtolower($_POST['prenom']);
        $email = strtolower($_POST['email']);
        $telephone = strtolower($_POST['telephone']);

        // Valider les données du formulaire (ajoutez des validations si nécessaire)

        // Créer un nouvel objet ContactModel avec les données du formulaire
        $nouveauContact = new ContactModel(0, $nom, $prenom, $email, $telephone);

        $emails = $this->contactDAO->getAllEmail();
        $telephones = $this->contactDAO->getAllTelephone();

        if (in_array($email, $emails)) {
            $message = "Cette adresse email existe deja .";
            echo json_encode([
                'success' => 'false',
                'message' => $message
            ]);
        } else if (in_array($telephone, $telephones)) {
            $message = "Ce Numero de telephone existe déjà existe deja .";
            echo json_encode([
                'success' => 'false',
                'message' => $message
            ]);
        } else if ($this->contactDAO->create($nouveauContact)) {
            $message = "Contact ajoutée avec succès.";
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

    public function deleteContact($contactId)
    {
        $contact = $this->contactDAO->getById($contactId);

        if (!$contact) {
            // Le contact n'a pas été trouvé, vous pouvez rediriger ou afficher un message d'erreur
            echo "Le contact n'a pas été trouvé.";
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Supprimer le contact en appelant la méthode du modèle (ContactDAO)
            if ($this->contactDAO->deleteById($contactId)) {
                // Rediriger vers la page d'accueil après la suppression
                header('Location:HomeController.php');
                exit();
            } else {
                // Gérer les erreurs de suppression du contact
                echo "Erreur lors de la suppression du contact.";
            }
        }

        // Inclure la vue pour afficher la confirmation de suppression du contact
        include('../views/delete_contact.php');
    }

    public function editContact($contactId)
    {
        // Récupérer le contact à modifier en utilisant son ID
        $contact = $this->contactDAO->getById($contactId);
        var_dump($contact);
        $nom = $_POST['nom_modif'];
        $prenom = $_POST['prenom_modif'];
        $email = strtolower($_POST['email_modif']);
        $telephone = strtolower($_POST['telephone_modif']);
        $emails = $this->contactDAO->getAllEmail();
        $telephones = $this->contactDAO->getAllTelephone();

        if (in_array($email, $emails) && ($email != strtolower($contact->getEmail()))) {
            $message = "Cette adresse email existe deja .";
            echo json_encode([
                'success' => 'false',
                'message' => $message
            ]);
        } else if (in_array($telephone, $telephones) && ($telephone != strtolower($contact->getTelephone()))) {
            $message = "Ce Numero de telephone existe déjà existe deja .";
            echo json_encode([
                'success' => 'false',
                'message' => $message
            ]);
        } else {
            $contact->setNom($nom);
            $contact->setPrenom($prenom);
            $contact->setEmail($email);
            $contact->setTelephone($telephone);
            if ($this->contactDAO->update($contact)) {
                // Rediriger vers la page de détails du contact après la modification
                $message = "Contact Modifiée avec succès.";
                echo json_encode([
                    'success' => 'true',
                    'message' => $message
                ]);
            } else {
                // Gérer les erreurs de mise à jour du contact
                $message = "Echec.";
                echo json_encode([
                    'success' => 'false',
                    'message' => $message
                ]);
            }
        }
    }

    // Inclure la vue pour afficher le formulaire de modification du contact


    public function getAllContacts()
    {
        $contacts = $this->contactDAO->getAll();

        $formattedContact = [];
        foreach ($contacts as $contact) {
            $formattedContact[] = [
                'id' => $contact->getId(),
                'nom' => $contact->getNom(),
                'prenom' => $contact->getPrenom(),
                'email' => $contact->getEmail(),
                'telephone' => $contact->getTelephone()

                // Ajoutez d'autres propriétés au besoin
            ];
        }

        return $formattedContact;
    }
}
