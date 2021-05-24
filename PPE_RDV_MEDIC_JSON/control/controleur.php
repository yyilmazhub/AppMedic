<?php
class Controleur{

    //Erreur 404.
    public function erreur404(){
        (new Vue)->erreur404();
    }

    // -- FONCTIONNEL --
    //Ajoute un RDV grâce à l'id du médecin, la date et l'ID du patient.
    public function ajouterRDV()
    {

        $corpsRequete= file_get_contents('php://input');
        if ($json=json_decode($corpsRequete, true))
        {
            if(isset($json["recordid"]) && isset($json["date"]) && isset($json["token"]))
            {
                //On récupère les infos d'authentification par le token du patient.
                $patient = (new Patient)->getInfosByToken($json["token"]);

                $IP = $this->getIP();

                //On vérifie l'IP de l'appareil.
                if($patient["ipAppareil"] == $IP)
                {
                    $RDV = (new Rdv)->ajouterRdv($json["date"], $patient["idPatient"], $json["recordid"]);

                    if($RDV == true)
                    {
                        http_response_code(201);
                        $json = '{ "code":201, "message": "Rendez-vous ajouté.", "recordid": "'.$json["recordid"].'" }';
                        (new Vue)->afficherJSON($json);
                    }
                    else
                    {
                        http_response_code(500);
                        $json = '{ "code":500, "message": "Aucune insertion medecin" }';
                        (new Vue)->afficherJSON($json);
                    }
                }
                else
                {
                    http_response_code(500);
                    $json = '{ "code":500, "message": "Erreur d\'authentification" }';
                    (new Vue)->afficherJSON($json);
                }
            }
            else
            {
                http_response_code(400);
                $json = '{ "code":400, "message": "Données manquantes." }';
                (new Vue)->afficherJSON($json);
            }
        }
        else
        {  
            http_response_code(400);   
            $json = '{ "code":400, "message": "Le corps de la requête est invalide." }';
            (new Vue)->afficherJSON($json);
        }      
    }

    // -- FONCTIONNEL --
    //Affiche les rendez-vous d'un patient en fonction de son ID.
    public function afficherRdv()
    {
        
        $corpsRequete= file_get_contents('php://input');
        if($json=json_decode($corpsRequete, true))
        {
            if(isset($json['token']))
            {
                //On récupère les infos d'authentification par le token du patient.
                $patient = (new Patient)->getInfosByToken($json["token"]);

                $IP = $this->getIP();

                //On vérifie l'IP de l'appareil.
                if($patient["ipAppareil"] == $IP)
                {
                    $RDV = (new Rdv);

                    http_response_code(201);
                    (new Vue)->afficherObjetEnJSON($RDV->getRdv($patient["idPatient"]));
                }
                else
                {
                    http_response_code(500);
                    $json = '{ "code":500, "message": "Erreur d\'authentification" }';
                    (new Vue)->afficherJSON($json);
                }
            }
            else
            {
                http_response_code(400);
                $json = '{ "code":400, "message": "Données manquantes." }';
                (new Vue)->afficherJSON($json);
            }
        }
        else
        {
            http_response_code(400);   
            $json = '{ "code":400, "message": "Le corps de la requête est invalide." }';
            (new Vue)->afficherJSON($json);
        }

    }




    // -- FONCTIONNEL --
    //Modifie la date et l'heure d'un rendez-vous par son ID.
    public function modifierRdv() {
        
        $corpsRequete= file_get_contents('php://input');
        if($json=json_decode($corpsRequete, true))
        {
            if(isset($json["token"]) && isset($json["date"]) && isset($json["idRdv"]))
            {
                //On récupère les infos d'authentification par le token du patient.
                $patient = (new Patient)->getInfosByToken($json["token"]);

                $IP = $this->getIP();

                //On vérifie l'IP de l'appareil.
                if($patient["ipAppareil"] == $IP)
                {
                    $RDV = (new Rdv);

                    if($RDV->modifierRdv($json["date"], $json["idRdv"]))
                    {
                        $date = explode(' ', $json["date"]);

                        http_response_code(201);
                        $json = '{ "code":201, "message": "Rendez-vous modifié pour le '.$date[0].' à '.$date[1].'" }';
                        (new Vue)->afficherJSON($json);
                    }
                    else
                    {
                        http_response_code(500);
                        $json = '{ "code":500, "message": "Aucune insertion medecin" }';
                        (new Vue)->afficherJSON($json);
                    }
                }
                else
                {
                    http_response_code(500);
                    $json = '{ "code":500, "message": "Erreur d\'authentification" }';
                    (new Vue)->afficherJSON($json);
                }
            }
            else
            {
                http_response_code(400);
                $json = '{ "code":400, "message": "Données manquantes." }';
                (new Vue)->afficherJSON($json);
            }
        }
        else
        {
            http_response_code(400);   
            $json = '{ "code":400, "message": "Le corps de la requête est invalide." }';
            (new Vue)->afficherJSON($json);
        }

    }

    // -- FONCTIONNEL --
    //Annule un RDV par le biais de son ID.
    public function annulerRdv() {

        $corpsRequete= file_get_contents('php://input');
        if($json=json_decode($corpsRequete, true))
        {
            if(isset($json["token"]) && isset($json["idRdv"]))
            {
                //On récupère les infos d'authentification par le token du patient.
                $patient = (new Patient)->getInfosByToken($json["token"]);

                $IP = $this->getIP();

                //On vérifie l'IP de l'appareil.
                if($patient["ipAppareil"] == $IP)
                {
                    $RDV = (new Rdv);

                    if($RDV->annulerRdv($json["idRdv"]) == true)
                    {
                        http_response_code(201);
                        $json = '{ "code":201, "message": "Rendez-vous annulé avec succès." }';
                        (new Vue)->afficherJSON($json);
                    }
                    else
                    {
                        http_response_code(500);
                        $json = '{ "code":500, "message": "Une erreur est survenue, impossible d\'annuler" }';
                        (new Vue)->afficherJSON($json);
                    }
                }
                else
                {
                    http_response_code(500);
                    $json = '{ "code":500, "message": "Erreur d\'authentification" }';
                    (new Vue)->afficherJSON($json);
                }
            }
            else
            {
                http_response_code(400);
                $json = '{ "code":400, "message": "Données manquantes." }';
                (new Vue)->afficherJSON($json);
            }
        }
        else
        {
            http_response_code(400);
            $json = '{ "code":400, "message": "requete incorrecte." }';
            (new Vue)->afficherJSON($json);
        }
    
    }
    
    // -- FONCTIONNEL --
    //Méthode qui retourne l'IP du PC, même s'il passe par un proxy.
    public function getIp() {
    
        if(!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    
        return $ip;
    }
    
    // -- FONCTIONNEL --
    //Méthode qui connecte un patient et qui retourne ses informations en JSON.
    public function connexionPatient() {
    
        $corpsRequete= file_get_contents('php://input');
        if ($json=json_decode($corpsRequete, true)) {

            if(isset($json["login"]) && isset($json["mdp"]))
            {
                //On génère le token aléatoire et on récupère l'IP.
                $token = bin2hex(random_bytes(30));
                $IP = $this->getIp();
                
                $patient = (new Patient)->connexionPatient($json["login"], $json["mdp"], $token, $IP);

                if($patient == true)
                {
                    http_response_code(201);
                    $json = '{ "code":201, "token":"'.$token.'" }';
                    (new Vue)->afficherJSON($json);
                }
                else
                {
                    http_response_code(500);
                    $json = '{ "code":500, "message": "Erreur de connexion" }';
                    (new Vue)->afficherJSON($json);
                }  
            }
            else
            {
                http_response_code(400);
                $json = '{ "code":400, "message": "Données manquantes." }';
                (new Vue)->afficherJSON($json);
            }
            
        }
        else
        {
            http_response_code(400);
            $json = '{ "code":400, "message": "requete incorrecte." }';
            (new Vue)->afficherJSON($json);
        }
        
    }


    // -- FONCTIONNEL --
    //On déconnecte un patient par son token.
    public function deconnexionPatient() {

        $corpsRequete= file_get_contents('php://input');
        if($json=json_decode($corpsRequete, true))
        {
            if(isset($json['token']))
            {
                $patient = (new Patient);

                if($patient->deconnexion($json['token']) == true)
                {
                    http_response_code(201);
                    $json = '{ "code":201, "message": "A bientôt." }';
                    (new Vue)->afficherJSON($json);
                }
                else
                {
                    http_response_code(500);
                    $json = '{ "code":500, "message": "Erreur lors de la déconnexion" }';
                    (new Vue)->afficherJSON($json);
                }
            }
            else
            {
                http_response_code(400);
                $json = '{ "code":400, "message": "Données manquantes." }';
                (new Vue)->afficherJSON($json);
            }
        }
        else
        {
            http_response_code(400);
            $json = '{ "code":400, "message": "requete incorrecte." }';
            (new Vue)->afficherJSON($json);
        }
    }
    
    // -- FONCTIONNEL --
    //On inscrit un nouveau patient dans la base avec les infos nécessaires.
    public function inscriptionPatient() {

        $corpsRequete= file_get_contents('php://input');
        if($json=json_decode($corpsRequete, true))
        {
            if(isset($json["nom"]) && isset($json["prenom"]) && isset($json["rue"]) && isset($json["cp"]) && isset($json["ville"]) && isset($json["tel"]) && isset($json["mdp"]) && isset($json["login"]))
            {
                $patient = (new Patient);

                if($patient->estDejaInscrit($json["login"]) === false)
                {
                    //Si le patient est bien ajouté, on retourne son nom et son prénom.
                    if($patient->ajouterPatient($json["nom"], $json["prenom"], $json["rue"], $json["cp"], $json["ville"], $json["tel"], $json["mdp"], $json["login"]) === true)
                    {
                        http_response_code(201);
                        $json = '{ "code":201, "message": "Patient '.$json["nom"].' '.$json["prenom"].' ajouté." }';
                        (new Vue)->afficherJSON($json);
                    }
                    else
                    {
                        http_response_code(500);
                        $json = '{ "code":500, "message": "Aucune insertion patient" }';
                        (new Vue)->afficherJSON($json);
                    }
                }
                else
                {
                    http_response_code(500);
                    $json = '{ "code":500, "message": "Le patient existe déjà." }';
                    (new Vue)->afficherJSON($json);
                }
            }
            else
            {
                http_response_code(400);
                $json = '{ "code":400, "message": "Données manquantes." }';
                (new Vue)->afficherJSON($json);
            }
        }
        else
        {  
            http_response_code(400);   
            $json = '{ "code":400, "message": "Le corps de la requête est invalide." }';
            (new Vue)->afficherJSON($json);
        }
    }

    //Méthode de test.
    /*public function tests()
    {
        $RDV = (new Rdv);
        $dateHeure = "2021-01-11 15:12:30";
        $idRdv = 33;

        //$r = $RDV->modifierRdv($dateHeure, $idRdv);

        //var_dump($r);

        if($RDV->modifierRdv($dateHeure, $idRdv) == true)
        {
            http_response_code(201);
            $json = '{ "code":400, "message": "Let\'s go" }';
            (new Vue)->afficherJSON($json);
        }
        else
        {
            http_response_code(500);
            $json = '{ "code":400, "message": "Sad" }';
            (new Vue)->afficherJSON($json);
        }

        
    }*/
   

}