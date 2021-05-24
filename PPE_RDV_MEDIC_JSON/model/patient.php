<?php

class Patient {
	private $pdo;

	//CONNEXION BDD
	public function __construct() {
		$config = parse_ini_file("config.ini");
		
		try {
			$this->pdo = new \PDO("mysql:host=".$config["host"].";dbname=".$config["database"].";charset=utf8", $config["user"], $config["password"]);
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}
	
	//Méthode qui affiche un patient par son id.
	public function afficherPatient($id) {
		$sql = "SELECT * FROM patient WHERE idPatient = :id";
		
		$req = $this->pdo->prepare($sql);
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();
		
		return $req->fetch(PDO::FETCH_ASSOC);
	}

	//Méthode qui permet de retourner l'id d'un patient par son token dans l'authentification actuelle (afin de vérifier si c'est la bonne personne qui effectue une action).
	public function getInfosByToken($token) {

		$sql = "SELECT * FROM authentification WHERE token = :token";

		$requete = $this->pdo->prepare($sql);
		$requete->bindParam(':token', $token, PDO::PARAM_STR);
		$requete->execute();

		return $requete->fetch(PDO::FETCH_ASSOC);
	}

	//Méthode qui permet d'ajouter un patient à la BDD.
	public function ajouterPatient($nom, $prenom, $rue, $cp, $ville, $tel, $mdp, $login) {
		$sql = "INSERT INTO patient (nomPatient, prenomPatient, ruePatient, cpPatient, villePatient, telPatient, loginPatient, mdpPatient) VALUES (:nom, :prenom, :rue, :cp, :ville, :tel, :logPat, :mdp)";
		
		$req = $this->pdo->prepare($sql);
		
		$req->bindParam(':nom', $nom, PDO::PARAM_STR);
		$req->bindParam(':prenom', $prenom, PDO::PARAM_STR);
		$req->bindParam(':rue', $rue, PDO::PARAM_STR);
		$req->bindParam(':cp', $cp, PDO::PARAM_STR);
        $req->bindParam(':ville', $ville, PDO::PARAM_STR);
		$req->bindParam(':tel', $tel, PDO::PARAM_STR);
		$req->bindParam(':mdp', password_hash($mdp, PASSWORD_DEFAULT), PDO::PARAM_STR);
		$req->bindParam(':logPat', $login, PDO::PARAM_STR);
		
		return $req->execute();
	}
	
	//Méthode qui vérifie si un patient est déjà inscrit.
    public function estDejaInscrit($login){

        $sql = "SELECT COUNT(*) AS nombre FROM patient WHERE loginPatient = :logPat";
		
		$req = $this->pdo->prepare($sql);
		$req->bindParam(':logPat', $login, PDO::PARAM_STR);
		$req->execute();
		
		$ligne = $req->fetch(PDO::FETCH_ASSOC);

		if($ligne["nombre"] == 0)
		{
			return false;
		}
		else
		{
			return true;
		}
    }

	//Méthode de connexion d'un patient.
    public function connexionPatient($login, $mdp, $token, $IP){

        $sql = "SELECT idPatient, mdpPatient FROM patient WHERE loginPatient = :logPat";
		
		$req = $this->pdo->prepare($sql);
		$req->bindParam(':logPat', $login, PDO::PARAM_STR);
		$req->execute();
		
		$ligne = $req->fetch(PDO::FETCH_ASSOC);

		if($ligne != false) {

			//Vérification du hash de mot de passe dans la base.		
			if(password_verify($mdp, $ligne["mdpPatient"])) {

				//Suppression de la session précédente pour l'utilisateur qui a le même ID et le même IP.
				$sql = "DELETE FROM authentification WHERE idPatient = :id AND ipAppareil = :ip";

				$req = $this->pdo->prepare($sql);
				$req->bindParam(':id', $ligne["idPatient"], PDO::PARAM_STR);
				$req->bindParam(':ip', $IP, PDO::PARAM_STR);

				$req->execute();

				//Insertion de l'authentification dans la base.
				$sql = "INSERT INTO authentification (token, idPatient, ipAppareil) VALUES (:tok, :id, :ip)";
				
				$req = $this->pdo->prepare($sql);
				$req->bindParam(':tok', $token, PDO::PARAM_STR);
				$req->bindParam(':id', $ligne["idPatient"], PDO::PARAM_INT);
				$req->bindParam(':ip', $IP, PDO::PARAM_STR);
				
				$req->execute();
				
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	//Méthode qui supprime l'authentification d'un client lorsqu'il se déconnecte.
	public function deconnexion($token) {

		$sql = "DELETE FROM authentification WHERE token = :token";

		$res = $this->pdo->prepare($sql);
		$res->bindParam(':token', $token, PDO::PARAM_STR);

		return $res->execute();
	}
	
}


