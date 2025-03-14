<?php
// Fonction d'inscription
    // Si l'identifiant, l'email le mot de passe un et le mot de passe deux sont poster
    //      Si les deux mot de passe sont identiques
    //          Si le pseudo n'existe pas dans la bdd
    //              Si l'email est valide
    //                  Si l'email n'existe pas dans la bdd
    //                      creation du profil
    //                      creation de la protection des info du profil
    //                      envoie du message de bienvenue
    //                      Retourne Activation du profil
    //                  Sinon
    //                      Retourne email existe deja
    //              Sinon
    //                  Retourne email non valide
    //          Sinon
    //              Retourne le pseudo existe
    //      Sinon
    //          Retourne les 2 mots de passe sont !=
    // Sinon
    //      Retourne remplir tout les champs
    public static function inscrire($identifiant, $email, $passeUn, $passeDe) {
        if(!empty($identifiant) AND !empty($email) AND !empty($passeUn) AND !empty($passeDe)) {
            if($passeUn === $passeDe) {
                $verifIdentifiant = Bdd::connectBdd()->prepare(SELECT.ALL.MEMBRE.PSEUDO);
                $verifIdentifiant -> bindParam(':identifiant', $identifiant, PDO::PARAM_STR, 50);
                $verifIdentifiant -> execute();
                if($verifIdentifiant -> rowCount() != 1) {
                    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $verifMail = Bdd::connectBdd()->prepare(SELECT.ALL.MEMBRE.EMAIL);
                        $verifMail -> bindParam(':email', $email);
                        $verifMail -> execute();
                        if($verifMail -> rowCount() != 1) {
                            Inscription::profil($identifiant, $email, $passeUn);
                            Inscription::protect($identifiant);
                            Inscription::message($identifiant);
                            $resultat = Inscription::activer($identifiant);
                        }
                        else {
                            $resultat = '<span class="error-info">L\'adresse email'.$email.' existe d&eacute;j&agrave;,<br />veuillez en saisir une autre et recommencer l\'inscription.</span>';
                        }
                    }
                    else {
                        $resultat = '<span class="error-info">L\'adresse email saisie n\'est pas valide, <br />veuillez recommencer l\'inscription.</span>';
                    }
                }
                else {
                    $resultat = '<span class="error-info">L\'identifiant saisi existe d&eacute;j&agrave;,<br />veuillez en choisir un autre et recommencer l\'inscription.</span>';
                }
            }
            else {
                $resultat = '<span class="error-info">Le champ &quot;Votre mot de passe&quot; et le champ &quot;Confirmez votre mot de passe&quot; doivent &ecirc;tre identiques, <br />veuillez recommencer l\'inscription.</span>';
            }
        }
        else {
            $resultat = '<span class="error-info">Vous devez remplir tout les champs, <br />veuillez recommencer l\'inscription.</span>';
        }
        return $resultat;
    }
?>