<?php
if($_Joueur_['rang'] == 1 OR $_PGrades_['PermsPanel']['vote']['actions']['addVote'] == true) {
	$lectureServs = new Lire('modele/config/configServeur.yml');
	$lectureServs = $lectureServs->GetTableau();

	$lectureServs = $lectureServs['Json'];

	//Nouveau système de votes :
	$message = htmlspecialchars($_POST['message']);
	if(htmlspecialchars($_POST['display']) == 2)
		$message = "";
	$action = htmlspecialchars($_POST['action']);
	if($action == 1)
	{
		$action = 'cmd:';
		$cmd = htmlspecialchars($_POST['cmd']);
		$action.= $cmd;
	}
	elseif($action == 2)
	{
		$action = 'give:';
		$id = htmlspecialchars($_POST['id']);
		$action.= 'id:'.$id.':';
		$quantite = htmlspecialchars($_POST['quantite']);
		$action.= 'quantite:'.$quantite;
	}
	else
	{
		$action = 'jeton:';
		$quantite = htmlspecialchars($_POST['quantite']);
		$action.= $quantite;
	}
	$serveur = htmlspecialchars($_POST['serveur']);
	$lien = htmlspecialchars($_POST['lien']);
	$titre = htmlspecialchars($_POST['titre']);
	$temps = htmlspecialchars($_POST['temps']);
	$methode = htmlspecialchars($_POST['methode']);

	$req = $bddConnection->prepare('INSERT INTO cmw_votes_config(message, methode, action, serveur, lien, temps, titre) VALUES (:message, :methode, :action, :serveur, :lien, :temps, :titre) ');
	$req->execute(array(
		'message' => $message,
		'methode' => $methode,
		'action' => $action,
		'serveur' => $serveur,
		'lien' => $lien,
		'temps' => $temps,
		'titre' => $titre
	));
}
?>