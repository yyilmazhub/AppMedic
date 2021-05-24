<?php

class Rdv {
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
	
	
	//Affiche les rendez-vous d'un patient par son ID.
	public function getRdv($id){

		$sql = "SELECT * FROM rdv WHERE idPatient = :id";

		$req = $this->pdo->prepare($sql);
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();

		return $req->fetchAll(PDO::FETCH_ASSOC);
	}


	//Ajoute un nouveau rendez-vous.
	public function ajouterRdv($dateHeure, $idPatient, $idMedecin){

		$sql = "INSERT INTO rdv (dateHeureRdv, idPatient, idMedecin) VALUES (:dateHeure, :patient, :medecin)";

		$req = $this->pdo->prepare($sql);
		$req->bindParam(':dateHeure', $dateHeure, PDO::PARAM_STR);
		$req->bindParam(':patient', $idPatient, PDO::PARAM_INT);
		$req->bindParam(':medecin', $idMedecin, PDO::PARAM_STR);

		return $req->execute();
	}
	
	//Supprime un rendez-vous par son ID.
	public function annulerRdv($idRdv){
		
		$sql = "DELETE FROM rdv WHERE idRdv = :idRdv";

		$req = $this->pdo->prepare($sql);
		$req->bindParam(':idRdv', $idRdv, PDO::PARAM_INT);
		$req->execute();

		return $req->execute();
	}


	//Modifie un rendez-vous par son ID.
	public function modifierRdv($dateHeure, $idRdv){

		$sql= "UPDATE rdv SET dateHeureRdv = :dateHeure WHERE idRdv = :idRdv";

		$req = $this->pdo->prepare($sql);
		$req->bindParam(':dateHeure', $dateHeure, PDO::PARAM_STR);
		$req->bindParam(':idRdv', $idRdv, PDO::PARAM_INT);

		return $req->execute();
	}
}

