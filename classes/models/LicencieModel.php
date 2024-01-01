<?php

class LicencieModel {
    private $id;
    private $nom;
    private $prenom;
    private $idCategorie; // Clé étrangère pour la table Categorie
    private $idContact;   // Clé étrangère pour la table Contact

    public function __construct($id, $nom, $prenom, $idCategorie, $idContact) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->idCategorie = $idCategorie;
        $this->idContact = $idContact;
    }

    public function getId() {
        return $this->id;
    }   

    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getIdCategorie() {
        return $this->idCategorie;
    }

    public function getIdContact() {
        return $this->idContact;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    public function setIdCategorie($idCategorie) {
        $this->idCategorie = $idCategorie;
    }

    public function setIdContact($idContact) {
        $this->idContact = $idContact;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'idCategorie'=> $this->idCategorie,
            'idContact' => $this->idContact
        ];
     }
}
