<?php
class CategorieDAO {
    private $connexion;

    public function __construct(Connexion $connexion) {
        $this->connexion = $connexion;
    }

    // MÃ©thode pour insÃ©rer un nouveau categorie dans la base de donnÃ©es
    public function create(CategorieModel $categorie) {
        try {
            $stmt = $this->connexion->pdo->prepare("INSERT INTO categorie (nom, code) VALUES (?, ?)");
            $stmt->execute([$categorie->getNom(), $categorie->getCode()]);
            return true;
        } catch (PDOException $e) {
            // GÃ©rer les erreurs d'insertion ici
            return false;
        }
    }

    public function countCategorie(){
        try {
            $stmt = $this->connexion->pdo->query("SELECT COUNT(*) FROM categorie");
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
    
    // MÃ©thode pour rÃ©cupÃ©rer un categorie par son ID
    public function getById($id) {
        try {
            $stmt = $this->connexion->pdo->prepare("SELECT * FROM categorie WHERE idCategorie = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($row) {

                return new CategorieModel($row['idCategorie'],$row['nom'], $row['code']);
                
            } else {
                return null; // Aucun categorie trouvÃ© avec cet ID
            }
        } catch (PDOException $e) {
            // GÃ©rer les erreurs de rÃ©cupÃ©ration ici
            return null;
        }
    }

    // MÃ©thode pour rÃ©cupÃ©rer la liste de tous les categorie
    public function getAll() {
        try {
            $stmt = $this->connexion->pdo->query("SELECT * FROM categorie");
            $categories = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $categories[] = new CategorieModel($row['idCategorie'],$row['nom'], $row['code']);
            }

            return $categories;
        } catch (PDOException $e) {
            // GÃ©rer les erreurs de rÃ©cupÃ©ration ici
            return [];
        }
    }

    // MÃ©thode pour mettre Ã  jour un categorie
    public function update(CategorieModel $categorie) {
        try {
            $stmt = $this->connexion->pdo->prepare("UPDATE categorie SET nom = ?, code = ? WHERE idCategorie = ?");
            $stmt->execute([$categorie->getNom(), $categorie->getCode(),$categorie->getId()]);
            return true;
        } catch (PDOException $e) {
            // GÃ©rer les erreurs de mise Ã  jour ici
            return false;
        }
    }

    // MÃ©thode pour supprimer un categorie par son ID
    public function deleteById($id) {
        try {
            $stmt = $this->connexion->pdo->prepare("DELETE FROM categorie WHERE idCategorie = ?");
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            // GÃ©rer les erreurs de suppression ici
            return false;
        }
    }


    public function getAllNom() {
        try {
            $stmt = $this->connexion->pdo->query("SELECT nom FROM categorie");
            $noms = [];
    
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Ajouter l'email au tableau $emails
                $noms[] = strtolower($row['nom']);
            }
    
            return $noms;
        } catch (PDOException $e) {
            // Gérer les erreurs de récupération ici
            return [];
        }
    }

    public function getAllCode() {
        try {
            $stmt = $this->connexion->pdo->query("SELECT code FROM categorie");
            $codes = [];
    
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Ajouter l'email au tableau $emails
                $codes[] = strtolower($row['code']);            }
    
            return $codes;
        } catch (PDOException $e) {
            // Gérer les erreurs de récupération ici
            return [];
        }
    }
}
?>
