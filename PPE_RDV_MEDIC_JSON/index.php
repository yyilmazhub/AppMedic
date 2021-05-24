<?php
session_start();

// Test de connexion à la base
$config = parse_ini_file("config.ini");
try {
	$pdo = new \PDO("mysql:host=".$config["host"].";dbname=".$config["database"].";charset=utf8", $config["user"], $config["password"]);
} catch(Exception $e) {
	echo "<h1>Erreur de connexion à la base de données :</h1>";
	echo $e->getMessage();
	exit;
}

// Chargement des fichiers MVC
require("control/controleur.php");
require("view/vue.php");
require("model/patient.php");
require("model/rdv.php");

// Routes
if(isset($_GET["action"])) {
	switch($_GET["action"]) {
		case "rdv":
			switch($_SERVER["REQUEST_METHOD"]) {
				case "PUT":
					(new Controleur)->afficherRdv();
					break;
				case "POST":
					(new Controleur)->ajouterRdv();
					break;
				case "PATCH":
					(new Controleur)->modifierRdv();
					break;
				case "DELETE":
					(new Controleur)->annulerRdv();
					break;
			}
			break;

        case "patient":
            switch($_SERVER["REQUEST_METHOD"]) {
                case "PUT":
                    (new Controleur)->connexionPatient();
                    break;
                case "POST":
                    (new Controleur)->inscriptionPatient();
					break;
				case "DELETE":
					(new Controleur)->deconnexionPatient();
					break;
				//Redirige vers une méthode de test.
				/*case "PATCH":
					(new Controleur)->tests();
					break;*/
			}
			break;
		// Route par défaut : erreur 404
		default:
			(new Controleur)->erreur404();
			break;
	}
}
else {
	// Pas d'action précisée = afficher l'accueil
	$json = '{ "code":200, "message": "Bienvenue dans l\'API !" }';
	(new Vue)->afficherJSON($json);
}