<?php
class ContactDAO {
    private $connexion;

    public function __construct(Connexion $connexion) {
        $this->connexion = $connexion;
    }

    // MÃ©thode pour insÃ©rer un nouveau contact dans la base de donnÃ©es
    public function create(ContactModel $contact) {
        try {
            $stmt = $this->connexion->pdo->prepare("INSERT INTO contact (nom, prenom, email, telephone) VALUES (?, ?, ?, ?)");
            $stmt->execute([$contact->getNom(), $contact->getPrenom(), $contact->getEmail(), $contact->getTelephone()]);
            return true;
        } catch (PDOException $e) {
            // GÃ©rer les erreurs d'insertion ici
            return false;
        }
    }


    public function countContact(){
        try {
            $stmt = $this->connexion->pdo->query("SELECT COUNT(*) FROM contact");
            $result = $stmt->fetchColumn();
    
            // Vérifier si la requête a réussi
            if ($result !== false) {
                return $result;
            } else {
                // Gérer les erreurs ici
                return -1; // Ou une valeur qui indique une erreur
            }
        } catch (PDOException $e) {
            // Gérer les erreurs d'exécution de la requête ici
            return -1; // Ou une valeur qui indique une erreur
        }
    }
    

    public function getLastId() {
        try {
            // Utilisez la fonction lastInsertId de PDO pour récupérer l'ID du dernier élément inséré
            return $this->connexion->pdo->lastInsertId();
        } catch (PDOException $e) {
            // Gérer les erreurs d'insertion ici
            return null;
        }
    }
    

    // MÃ©thode pour rÃ©cupÃ©rer un contact par son ID
    public function getById($id) {
        try {
            $stmt = $this->connexion->pdo->prepare("SELECT * FROM contact WHERE idContact = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                return new ContactModel($row['idContact'],$row['nom'], $row['prenom'], $row['email'], $row['telephone']);
            } else {
                return null; // Aucun contact trouvÃ© avec cet ID
            }
        } catch (PDOException $e) {
            // GÃ©rer les erreurs de rÃ©cupÃ©ration ici
            return null;
        }
    }

    // MÃ©thode pour rÃ©cupÃ©rer la liste de tous les contacts
    public function getAll() {
        try {
            $stmt = $this->connexion->pdo->query("SELECT * FROM contact");
            $contacts = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $contacts[] = new ContactModel($row['idContact'],$row['nom'], $row['prenom'], $row['email'], $row['telephone']);
            }

            return $contacts;
        } catch (PDOException $e) {
            // GÃ©rer les erreurs de rÃ©cupÃ©ration ici
            return [];
        }
    }

    public function getAllEmail() {
        try {
            $stmt = $this->connexion->pdo->query("SELECT email FROM contact");
            $emails = [];
    
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Ajouter l'email au tableau $emails
                $emails[] = $row['email'];
            }
    
            return $emails;
        } catch (PDOException $e) {
            // Gérer les erreurs de récupération ici
            return [];
        }
    }

    public function getAllTelephone() {
        try {
            $stmt = $this->connexion->pdo->query("SELECT telephone FROM contact");
            $telephone = [];
    
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Ajouter l'email au tableau $emails
                $telephone[] = strtolower($row['telephone']);            }
    
            return $telephone;
        } catch (PDOException $e) {
            // Gérer les erreurs de récupération ici
            return [];
        }
    }
    

    // MÃ©thode pour mettre Ã  jour un contact
    public function update(ContactModel $contact) {
        try {
            $stmt = $this->connexion->pdo->prepare("UPDATE contact SET nom = ?, prenom = ?, email = ?, telephone = ? WHERE idContact = ?");
            $stmt->execute([$contact->getNom(), $contact->getPrenom(), $contact->getEmail(), $contact->getTelephone(), $contact->getId()]);
            return true;
        } catch (PDOException $e) {
            // GÃ©rer les erreurs de mise Ã  jour ici
            return false;
        }
    }

    // MÃ©thode pour supprimer un contact par son ID
    public function deleteById($id) {
        try {
            $stmt = $this->connexion->pdo->prepare("DELETE FROM contact WHERE idContact = ?");
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            // GÃ©rer les erreurs de suppression ici
            return false;
        }
    }
}
?>
