<?php

class EducateurModel {

    private $id;

    private $nom;

    private $prenom;

    private $email;

    private $password;

    private $admin;

    private $numeroLicence;

    public function __construct($id,$nom, $prenom, $email, $password , $admin , $numeroLicence) {

        $this->id = $id;

        $this->nom = $nom;

        $this->prenom = $prenom;

        $this->email = $email;

        $this->password = $password;

        $this->admin = $admin;

        $this->numeroLicence = $numeroLicence;

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

    public function getEmail() {

        return $this->email;
    }

    public function getPassword() {

        return $this->password;

    }

    public function getAdmin() {

        return $this->admin;

    }

    public function getNumeroLicence(){

        return $this->numeroLicence ;
    }
    public function setId($id) {

        $this->id=$id;

    }

    public function setNom($nom) {

        $this->nom=$nom;

    }
    public function setPrenom($prenom) {

        $this->prenom=$prenom;

    }
    public function setEmail($email) {

        $this->email=$email;

    }
    public function setPassword($password) {

        $this->password=$password;

    }

    public function setAdmin($admin) {

        $this->admin=$admin;

    }

    public function setNumeroLicence($numeroLicence){

        $this->numeroLicence = $numeroLicence;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'password' => $this->password,
            'admin' => $this->admin,
            'numeroLicence' => $this->numeroLicence
        ];
     }
    // Vous pouvez ajouter des mÃ©thodes supplÃ©mentaires ici pour manipuler les donnÃ©es du contact

}

?>

