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


if (isset($_POST['bt_login'])) {

$login = new LoginController(new Connexion);
$login->login();


  
}



class LoginController
{
    private $connexion;

    public function __construct(Connexion $connexion)
    {
        $this->connexion = $connexion;
    }


 


    public function login()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
    
        if (empty($email) || empty($password)) {
            $message = "Email ou mot de passe ne doit pas être vide.";
            echo json_encode([
                'success' => 'false',
                'message' => $message
            ]);
        } else {
            $educateurDao = new EducateurDAO(new Connexion());
            $educateur = $educateurDao->getByEmail($email);
    
            if ($educateur !== null) {
                // verifiier si les deux mots de passe sont identiques
                if (password_verify($password, $educateur->getPassword())  ) {
                     // L'utilisateur est authentifié avec succès
                     if($educateur->getAdmin()=== 'oui'){    
                         session_start();
                         $_SESSION['educateur_id'] = $educateur->getId(); // Remplacez 'id' par la colonne correspondante dans votre table
                        
                        $message = "Authentification reussi";
    
                         echo json_encode([
                            'success' => 'true',
                            'message' => $message
                        ]);
                     }else{
                        $message = "Ce compte n'est pas autorisé";
                        echo json_encode([
                            'success' => 'false',
                            'message' => $message
                        ]);
                     }
                
                    }else{
                    $message = "Email ou mot de passe incorrect.";
                    echo json_encode([
                        'success' => 'false',
                        'message' => $message
                    ]);
                }
            }else{
                $message = "Email ou mot de passe incorrect.";
                echo json_encode([
                    'success' => 'false',
                    'message' => $message
                ]);
            }
        }
    }
}
