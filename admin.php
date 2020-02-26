<?php
error_reporting(0);
date_default_timezone_set('Europe/Paris');
ini_set('display_errors', 1);
	// On appelle les classes du controleur qui instancies les objets principaux (BDD, config, JSONAPI...).
	require_once('controleur/config.php');
	require_once('controleur/connection_base.php');	

	// On démarre les sessions sur la page pour récupérer les variables globales(les données du joueur...).
	session_start();

	if(isset($_COOKIE['id'], $_COOKIE['pass']))
	{
		require_once('controleur/joueur/connexion_cookie.php');
		require_once ('controleur/joueur/joueur.class.php');
        $globalJoueur = new Joueur();
        // Cette variable contiens toutes les informations du joueur.
        $_Joueur_ = $globalJoueur->getArrayDonneesUtilisateur();
	}

	// On récupère la variable globale des grades $_PGrades_
	$switch = true;
	require_once('controleur/grades/grades.php');
	
	/* Si l'utilisateur est connecté, on met ses informations dans un tableau global, qui sera utilisable que 
	   le laps de temps du chargement de la page contrairement aux sessions. */
	if(isset($_SESSION['Player']['pseudo']) AND ($_SESSION['Player']['rang'] == 1 OR $_PGrades_['PermsPanel']['access'] == true))
	{
		/* On instancie un joueur, et on récupère le tableau de données. $_Joueur_ sera donc utilisable 
		   sur toutes les pages grâce au système de GET sur l'index.*/
		require_once('controleur/joueur/joueur.class.php');
		
		$globalJoueur = new Joueur();
		
		// Cette variable contiens toutes les informations du joueur.
		$_Joueur_ = $globalJoueur->getArrayDonneesUtilisateur();
		$connection = true;

		$switch = false;
		require_once('controleur/grades/grades.php');
		
		require_once('controleur/json/json.php');
		
		$admin = true;

		if(isset($_GET['action'])){
			include('admin/donnees.php');
			include('admin/action.php');
		}
		$pageadmin = $_GET['page'];
		include('admin/page.php');
	}
	else
	{
		//header('Location: index.php');
	}
	
?>
