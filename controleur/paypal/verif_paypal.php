<?php
$recupOpffresPaypal = $bddConnection->prepare('SELECT * FROM cmw_jetons_paypal_offres WHERE id = :id');
$recupOpffresPaypal->execute(array('id' => $_GET['offre']));
$donneesActions = $recupOpffresPaypal->fetch(PDO::FETCH_ASSOC);

$req = 'cmd=_notify-validate';
 
foreach ($_POST as $cle => $valeur)
{
    $valeur = urlencode(stripslashes($valeur));
    $req .= "&$cle=$valeur";
}

$cURL = curl_init();
curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($cURL, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($cURL, CURLOPT_URL, "https://www.paypal.com/cgi-bin/webscr");
curl_setopt($cURL, CURLOPT_ENCODING, 'gzip');
curl_setopt($cURL, CURLOPT_BINARYTRANSFER, true);
curl_setopt($cURL, CURLOPT_POST, true); // POST back
curl_setopt($cURL, CURLOPT_POSTFIELDS, $req); // the $IPN
curl_setopt($cURL, CURLOPT_HEADER, false);
curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
curl_setopt($cURL, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
curl_setopt($cURL, CURLOPT_FORBID_REUSE, true);
curl_setopt($cURL, CURLOPT_FRESH_CONNECT, true);
curl_setopt($cURL, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($cURL, CURLOPT_TIMEOUT, 60);
curl_setopt($cURL, CURLINFO_HEADER_OUT, true);
curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
    'Connection: close',
    'Expect: ',
));
$Response = curl_exec($cURL);
$Status = (int)curl_getinfo($cURL, CURLINFO_HTTP_CODE);
curl_close($cURL);
/*
// On renvoie les informations IPN à Paypal pour valider la transaction
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);// Mode de connexion par SSL.
 */
// On récupère les données POST dans des variables
$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];
$id = $_POST['custom'];
// var_dump($Response);
// var_dump($id);
if (empty($Response) || $Status != 200 || !$Status) {
    echo('Problème de connexion avec Paypal, les données IPN n\'ont pas pu être repostées');
    header('Location: crediter.php');
    exit();
}
if($payment_status = "Completed" AND $payment_currency == "EUR" AND $receiver_email == $_Serveur_['Payement']['paypalEmail'] AND (string)$payment_amount == (string)$donneesActions['prix'])
    {
        require_once('modele/joueur/maj.class.php');
        $joueurMaj = new Maj($id, $bddConnection);
        $playerData = $joueurMaj->getReponseConnection();
        $playerData = $playerData->fetch(PDO::FETCH_ASSOC);
        $playerData['tokens'] = $playerData['tokens'] + $donneesActions['jetons_donnes'];
        $joueurMaj->setReponseConnection($playerData);
        $joueurMaj->setNouvellesDonneesTokens($playerData);
    }
    else if (strcmp ($Response, "INVALID") == 0) // Si on trouve le mot INVALID (données reçues != données de la transaction)
    {
        echo('Un problème est survenue durant le paiement, veuillez ré-essayer.');
        header('Location: crediter.php?erreur');
        exit();
    }


// if (!$fp) // Si la connexion avec Paypal n'a pas pu être initialisée, on affiche une erreur
// {
//     setFlash('Problème de connexion avec Paypal, les données IPN n\'ont pas pu être repostées', 'error');
//     header('Location: crediter.php');
//     exit();
// }
// else
// {
//     fputs ($fp, $header . $req);// fputs=fwrite | On envoie la variable $req à Paypal via le connexion initialisée précédemment (nommée $fp)
//     while (!feof($fp))// Tant qu'on n'arrive pas à la fin de $fp
//     {
//         $res = fgets ($fp, 1024);
//         if (strcmp ($res, "VERIFIED") == 0)// Si on trouve le mot VERIFIED (donc si les données reçues correspondent aux données de la transaction)
//         {

//             if ($payment_status=="Completed" AND $receiver_email==$_Serveur_['Payement']['paypalEmail'] AND (string)$payment_amount==(string)$donneesActions['prix'] AND $payment_currency=="EUR")// Si tous les paramètres sont bons, on peut procéder au traitement de la commande
//             {


// $file = fopen('test.txt', 'r+');
// fputs($file, 'test'.$id );
// fclose($file);
// require_once('modele/joueur/maj.class.php');
// $joueurMaj = new Maj($id, $bddConnection);
// $playerData = $joueurMaj->getReponseConnection();
// $playerData = $playerData->fetch(PDO::FETCH_ASSOC);
// $playerData['tokens'] = $playerData['tokens'] + $donneesActions['jetons_donnes'];
// $joueurMaj->setReponseConnection($playerData);
// $joueurMaj->setNouvellesDonneesTokens($playerData);
//             }
//         }
//         else if (strcmp ($res, "INVALID") == 0) // Si on trouve le mot INVALID (données reçues != données de la transaction)
//         {
//             setFlash('Un problème est survenue durant le paiement, veuillez ré-essayer.', 'error');
//             header('Location: crediter.php?erreur');
//             exit();
//         }
//     }
// fclose ($fp);
// }
?>
