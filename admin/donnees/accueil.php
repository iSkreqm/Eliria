<?php
if($_Joueur_['rang'] == 1 OR $_PGrades_['PermsPanel']['home']['showPage'] == true) {
    $lectureAccueil = new Lire('modele/config/accueil.yml');
    $lectureAccueil = $lectureAccueil->GetTableau();

    for($i = 0; $i < 3; $i++) {
        $explode = explode('=', $lectureAccueil['Infos'][$i]['lien']);
        if($explode[0] == '?page')
        {    
            $typeNavRap[$i] = 1;
            $pageActive[$i] = $explode[1];
        }
        else 
            $typeNavRap[$i] = 2;
    }

    $images = scandir('theme/upload/navRap/');
    $imagesSlider = scandir('theme/upload/slider');

    $pagesReq = $bddConnection->query('SELECT titre FROM cmw_pages');
    $i = 0;
    while($pagesDonnees = $pagesReq->fetch(PDO::FETCH_ASSOC))
    {
       $pages[$i] = $pagesDonnees['titre'];
       $i++;
   }
   if(empty($pages)) $pages[0] = '- Aucune Page -';
   
}
?>