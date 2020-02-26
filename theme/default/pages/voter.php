<header class="heading-pagination">
	<div class="container-fluid">
		<h1 class="text-uppercase wow fadeInRight" style="color:white;">Voter</h1>
	</div>
</header>
<section class="layout" id="page">
<div class="container">
				<?php
				if(isset($_GET['erreur']))
				{
					if($_GET['erreur'] == 1)
					{
						?><div class="alert alert-danger">Vous devez encore attendre <?php echo $_GET['time']; ?> avant de pouvoir voter sur ce site !<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><script>$(".alert").alert()</script></div><?php
					}
					if($_GET['erreur'] == 2)
					{
						?><div class="alert alert-danger">Vous devez vous connecter si vous voulez gagner une récompense...<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><script>$(".alert").alert()</script></div><?php
					}
				}
				elseif(isset($_GET['success']))
				{
					if($_GET['success'] != 'recupTemp')
					{
						?><div class="alert alert-success">Votre récompense arrive, si vous n'avez pas vu de fenêtre s'ouvrir pour voter, la fenêtre à dû s'ouvrir derrière votre navigateur, validez le vote et profitez de votre récompense In-Game !<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><script>$(".alert").alert()</script></div><?php
					}
					else
					{
						?><div class="alert alert-success">Votre(vos) récompense(s) arrive(nt), profitez de votre(vos) récompense(s) In-Game !<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><script>$(".alert").alert()</script></div><?php
					}
				}
				?>	

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title text-center"><?php echo $_Serveur_['General']['name']; ?> a besoin de vous !</h3>
  </div>
  <div class="panel-body">
    <p class="text-center"><strong>
		Voter pour le serveur permet d'améliorer son référencement ! Les votes sont récompensés par des items In-Game.<br /><br /><?php if(!isset($_Joueur_)) echo '<hr><a data-toggle="modal" data-target="#ConnectionSlide" class="btn btn-warning btn-lg" ><span class="glyphicon glyphicon-user"></span> Veuillez vous connecter.</a>';
		if(isset($_Joueur_))
		{
			if(!empty($donneesVotesTemp))
			{
				echo '<div class="alert alert-success"><center><ul style="list-style-position: inside; padding-left: 0px;">';
				foreach($donneesVotesTemp as $data)
				{
					echo '<li>';
					$action = explode(':', $data['action'], 2);
					if($action[0] == "give")
					{
						echo "Give de ";
						$action = explode(':', $action[1]);
						echo $action[3]. "x ".$action[1];
						if($data['methode'] == 2)
							echo ' sur le serveur '.$lecture['Json'][$data['serveur']]['nom'];
						else
							echo ' sur tout les serveurs de jeu';
					}
					elseif($action[0] == "jeton")
					{
						echo "Give de ".$action[1]." jetons sur le site";
					}
					else
					{
						echo "Vous récupérerez une surprise :D :P";
					}
					echo "</li>";
				}
				echo '</ul>';
				echo "<a class='btn btn-success' href='?action=recupVotesTemp' title='Récupérer mes récompenses'>Récupérer mes récompenses (Connectez-vous sur le serveur)</a></center></div>";
			}	
		}
		?>
	</strong></p>

  </div>
</div>	

			<h3 class="header-bloc">Voter pour votre serveur :</h3>
			<div class="tabbable">
				<form action="?&action=voter" method="post">
				<ul class="nav nav-tabs" style="margin-bottom:1vh;">
                
				<?php 
                if(!isset($jsonCon) OR empty($jsonCon))
                    echo '<p>Veuillez relier votre serveur à votre site avec JsonAPI depuis le panel pour avoir les liens de vote !</p>';
                
                for($i = 0; $i < count($jsonCon); $i++) { ?>
					
					<li class="nav-item"><a href="#voter<?php echo $i; ?>" data-toggle="tab" class="nav-link <?php if($i == 0) echo ' active'; ?>"><?php echo $lecture['Json'][$i]['nom']; ?></a></li>
					
				<?php } ?>
				</ul>
				
				<?php if(isset($_Joueur_))
				{ ?>
				<div class="tab-content">
				<?php for($i = 0; $i < count($jsonCon); $i++) { ?>
				
					<div id="voter<?php echo $i; ?>" class="tab-pane fade <?php if($i==0) echo 'in active show';?>" <?php if($i == 0) { echo 'aria-expanded="true"'; } else echo 'aria-expanded="false"'; ?>>  
						<div class="panel-body">
							<div class="alert alert-dismissable alert-success">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<center>Bienvenue dans la catégorie de vote pour le serveur : <?=$lecture['Json'][$i]['nom'];?></center>
							</div>
                    
					<?php  $req_vote->execute(array('serveur' => $i));
							$count_req->execute(array('serveur' => $i));
							$data_count = $count_req->fetch(PDO::FETCH_ASSOC);
							if($data_count['count'] > 0)
							{
								while($liensVotes = $req_vote->fetch(PDO::FETCH_ASSOC))
								{
									?>
										<button type="submit" style="cursor: pointer;" class="btn btn-primary bouton-vote" name="site" value="<?php echo $liensVotes['id']; ?>" onclick="window.open('<?php echo $liensVotes['lien']; ?>','Fiche','toolbar=no,status=no,width=1350 ,height=900,scrollbars=yes,location=no,resize=yes,menubar=yes')" >
											<?php echo $liensVotes['titre']; ?>
				                        </button>					
								<?php								
								}
							}
							else
								echo '</br><p>Aucun lien de vote n\'est disponible pour ce serveur...</p>';
                    ?>
					
					</div>
				</div>
				
				<?php } ?>
				</div>
				<?php
			}
			else
				{
					?><center>
		<h4>Veuillez vous connecter pour accéder aux votes:</h4>
		<a data-toggle="modal" data-target="#ConnectionSlide" class="btn btn-warning btn-lg" ><span class="glyphicon glyphicon-user"></span> Connexion</a>
		</center><?php
				} ?>				
				</form>				
				
			</div>
			<br/>	

			<h3 class="header-bloc">Top voteurs</h3>
			<div class="corp-bloc">

				<table class="table table-hover">

					<thead>
						<tr><th>#</th><th>Pseudo</th><th>Votes</th></tr>
					</thead>
				
						<?php 
						if(isset($topVoteurs))
						{
							for($i = 0; $i < count($topVoteurs) AND $i < 10; $i++) {
								$Img = new ImgProfil($topVoteurs[$i]['pseudo'], 'pseudo');
							 ?>
							<tr><td><?php echo $i+1 ?></td><td><img src="<?=$Img->getImgToSize(30, $width, $height);?>" style="width: <?=$width;?>px; height: <?=$height;?>px;" alt="none" /> <strong><?php echo $topVoteurs[$i]['pseudo']; ?></strong></td><td><?php echo $topVoteurs[$i]['nbre_votes']; ?></td></tr>
							<?php }
						} ?>
				</table>
			</div>
</div>
</section>
