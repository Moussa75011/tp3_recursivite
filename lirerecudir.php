<head>
   <script src="https://kit.fontawesome.com/45e38e596f.js" crossorigin="anonymous"></script>
</head>
<link rel="stylesheet" href="css/style.css">
<P>
<B>DEBUTTTTTT DU PROCESSUS :</B>
<BR>
<?php echo " ", date ("h:i:s"); ?>
</P>
<?php

//
set_time_limit (500);
//le dossier 
$path= "docs";


//fonction pour explorer le dossier 
function explorerDir($path)
{
	//declaration d'une variable en ouvrant un dossier 
	$folder = opendir($path);
	
	//tant que c'est un dossier en lisant une entrée du dossier
	while($entree = readdir($folder))
	{		
		//si la variable entree est different de . et de ..
		if($entree != "." && $entree != "..")
		{
		
			//si c'est un fichier .
			if(is_dir($path."/".$entree))
			{
				echo "+ <i class='far fa-folder-open'> </i> ".$entree." <br> ";
				//sauvergarder la route dans une variable
				$sav_path = $path;
				//le path est égale à /entree
				$path .= "/".$entree;
				//Explorer le dossier		
				explorerDir($path);
			    //sauvergarder la route
				$path = $sav_path;
				
			}
			else
			{
				//tableau pour le format
				$validformat = array('jpg','png');
				
				$path_source = $path."/".$entree;
			//	echo "<br>".$entree."<br>";
				//Ramener les extensions en miniscule puis les stoquer dans une variable
				$extension = strtolower(pathinfo($path_source,PATHINFO_EXTENSION));
				
                switch ($extension) {
					
                    case 'txt':
                        echo " <p> <i class='far fa-file'></i> ".$entree."</p>";
                        break;
                    case 'html':
                        echo " <p> <i class='far fa-file-code'></i> ".$entree."</p>";
                        break;
                    default :
                    echo " <p> <i class='far fa-image'></i> ".$entree."</p>";
                    }
				echo " <a href=''>".$entree."</a><br>";
			//	si c'est l'un des extensions est présent dans le tableau
			  if(in_array($extension,$validformat))

				{
					$con = mysqli_connect("localhost", "root", "root");
                    // Sélection de la base coursphp
					$m = mysqli_select_db($con,"img_galerie");
					//echo "<img width='300px' height='300px'  src='$path_source'>";
				
					$size=filesize("$path_source");
					
					//Inserer dans la base de données
					$sql = "INSERT INTO image(nom,taille,type1)values('$path_source',$size,'image/$extension')";  
					$resul = mysqli_query($con,$sql);
		 
					
				}
				
				/* pour afficher les fichiers  pdf, html,txt 
				if(in_array($extension,array('pdf','html','txt','htm'))){

					echo "<embed src=$path_source width=800 height=500 type='application/pdf'/>";
				}*/
			}
		}
	}
	closedir($folder);
}


?>
<!--div class="footer">
    ?php
    explorerDir($path);
    ?>
    </div>
?php
-->

<div class="recurdir">
    <?php
    explorerDir($path);
    ?>
    </div>
<?php


recurdir
?>
<P>
<B>FIN DU PROCESSUS : </B>
<BR>
<?php echo " ", date ("h:i:s"); ?>
</P>
