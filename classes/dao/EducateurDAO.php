<?php

class EducateurDAO{
    private $connexion;

    public function __construct(Connexion $connexion) {
        $this->connexion = $connexion;
    }

   public function create(EducateurModel $educateur ){
    try {
        $stmt = $this->connexion->pdo->prepare("INSERT INTO educateur (nom, prenom, email, password,admin,numeroLicence) VALUES (?, ?, ?, ?,?,?)");
        $stmt->execute([$educateur->getNom(), $educateur->getPrenom(), $educateur->getEmail(), $educateur->getPassword(),$educateur->getAdmin(),$educateur->getNumeroLicence()]);
        return true;
    } catch (PDOException $e) {
        // GÃ©rer les erreurs d'insertion ici
        return false;
    }
   }

   public function countEducateur(){
    try {
        $stmt = $this->connexion->pdo->query("SELECT COUNT(*) FROM educateur");
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


   public function getById($id) {
    try {
        $stmt = $this->connexion->pdo->prepare("SELECT * FROM educateur WHERE numero = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new EducateurModel($row['numero'],$row['nom'], $row['prenom'], $row['email'], $row['password'], $row['admin'], $row['numeroLicence']);
        } else {
            return null; // Aucun contact trouvÃ© avec cet ID
        }
    } catch (PDOException $e) {
        // GÃ©rer les erreurs de rÃ©cupÃ©ration ici
        return null;
    }
}


public function getByEmail($email) {
    try {
        $stmt = $this->connexion->pdo->prepare("SELECT * FROM educateur WHERE email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new EducateurModel($row['numero'],$row['nom'], $row['prenom'], $row['email'], $row['password'], $row['admin'], $row['numeroLicence']);
        } else {
            return null; // Aucun contact trouvÃ© avec cet ID
        }
    } catch (PDOException $e) {
        // GÃ©rer les erreurs de rÃ©cupÃ©ration ici
        return null;
    }
}

public function getAll() {
    try {
        $stmt = $this->connexion->pdo->query("SELECT * FROM educateur");
        $educateur = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $educateur[] = new EducateurModel($row['numero'],$row['nom'], $row['prenom'], $row['email'], $row['password'], $row['admin'] ,$row['numeroLicence']);
        }

        return $educateur;
    } catch (PDOException $e) {
        // GÃ©rer les erreurs de rÃ©cupÃ©ration ici
        return [];
    }
}

public function getAllEmail() {
    try {
        $stmt = $this->connexion->pdo->query("SELECT email FROM educateur");
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

public function update(EducateurModel $educateur) {
    try {
        $stmt = $this->connexion->pdo->prepare("UPDATE educateur SET nom = ?, prenom = ?, email = ?, password = ?, admin = ? , numeroLicence = ? WHERE numero = ?");
        $stmt->execute([$educateur->getNom(), $educateur->getPrenom(), $educateur->getEmail(), $educateur->getPassword(),$educateur->getAdmin(), $educateur->getNumeroLicence(),$educateur->getId()]);
        return true;
    } catch (PDOException $e) {
        // GÃ©rer les erreurs de mise Ã  jour ici
        return false;
    }
}

public function deleteById($id) {
    try {
        $stmt = $this->connexion->pdo->prepare("DELETE FROM educateur WHERE numero = ?");
        $stmt->execute([$id]);
        return true;
    } catch (PDOException $e) {
        // GÃ©rer les erreurs de suppression ici
        return false;
    }
}

}

?>