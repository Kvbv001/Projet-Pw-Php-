<?php 

class LicenceDAO{
    private $connexion;

    public function __construct(Connexion $connexion) {
        $this->connexion = $connexion;
    }


    public function create(LicencieModel $licence) {
        try {
            $stmt = $this->connexion->pdo->prepare("INSERT INTO licence (nom, prenom, idCategorie, idContact) VALUES (?, ?, ?, ?)");
            $stmt->execute([$licence->getNom(), $licence->getPrenom(), $licence->getIdCategorie(), $licence->getIdContact()]);
            return true;
        } catch (PDOException $e) {
            // Gérer les erreurs d'insertion ici
            return false;
        }
    }

    public function countLicencie(){
        try {
            $stmt = $this->connexion->pdo->query("SELECT COUNT(*) FROM licence");
            $result = $stmt->fetchColumn();
                if ($result !== false) {
                return $result;
            } else {
                return -1; // Ou une valeur qui indique une erreur
            }
        } catch (PDOException $e) {
            return -1; // Ou une valeur qui indique une erreur
        }
    }
    

    public function getById($id) {
        try {
            $stmt = $this->connexion->pdo->prepare("SELECT * FROM licence WHERE numeroLicence = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                return new LicencieModel($row['numeroLicence'], $row['nom'], $row['prenom'], $row['idCategorie'], $row['idContact']);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            // Gérer les erreurs de récupération ici
            return null;
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
    public function getAll() {
        try {
            $stmt = $this->connexion->pdo->query("SELECT * FROM licence");
            $licences = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $licences[] = new LicencieModel($row['numeroLicence'], $row['nom'], $row['prenom'], $row['idCategorie'], $row['idContact']);
            }

            return $licences;
        } catch (PDOException $e) {
            // Gérer les erreurs de récupération ici
            return [];
        }
    }

    public function update(LicencieModel $licence) {
        try {
            $stmt = $this->connexion->pdo->prepare("UPDATE licence SET nom = ?, prenom = ?, idCategorie = ?, idContact = ? WHERE numeroLicence = ?");
            $stmt->execute([$licence->getNom(), $licence->getPrenom(), $licence->getIdCategorie(), $licence->getIdContact(), $licence->getId()]);
            return true;
        } catch (PDOException $e) {
            // Gérer les erreurs de mise à jour ici
            return false;
        }
    }

    public function deleteById($id) {
        try {
            $stmt = $this->connexion->pdo->prepare("DELETE FROM licence WHERE numeroLicence = ?");
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            // Gérer les erreurs de suppression ici
            return false;
        }
    }
    

}

?>