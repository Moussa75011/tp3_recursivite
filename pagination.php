


	<!DOCTYPE html>
	<html>
	<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, intial-scale=1.0"/>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<title>Show Image in PHP - Campuslife</title>
	<style>
		body{background-color: #f2f2f2; color: #333;}
		.main{box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important; margin-top: 20px;}
		h3{background-color: #4294D1; color: #f7f7f7; padding: 15px; border-radius: 4px; box-shadow: 0 1px 6px rgba(57,73,76,0.35);}
		.img-box{margin-top: 20px;}
		.img-block{float: left; margin-right: 5px; text-align: center;}
		p{margin-top: 0;}
		img{width: 440px; min-height: 300px; margin-bottom: 10px; box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important; border:6px solid #f7f7f7;}
	</style>
	</head>

	<body>

    		<!-------------------Main Content------------------------------>
	<div class="container main">
		<h3>Showing Images from database</h3>
		<div class="img-box">
        <?php
        

        //Pour pagination.php
        try{
            // Connexion à la base
            $cnx = new PDO('mysql:host=localhost;dbname=img_galerie', 'root', 'root');
            $cnx->exec('SET NAMES "UTF8"');
            //echo'connexion reussi';
        } catch (PDOException $e){
            echo 'Erreur : '. $e->getMessage();
            die();
        }

        //header("Refresh:1");

        $page = (!empty($_GET['page']) ? $_GET['page'] : 1);

        //Ou limite <=> nb d'enregistrement/page 
        $limite = 4;

        // Partie "Requête"

        /* On commence par récupérer le nombre d'éléments total. Comme c'est une requête,
        * il ne faut pas oublier qu'on ne récupère pas directement le nombre.
        * Ici, comme la requête ne contient aucune donnée client pour fonctionner,
        * on peut l'exécuter ainsi directement */
        $resultFoundRows = $cnx->query('SELECT count(id_img) FROM `image`');

        /* On doit extraire le nombre du jeu de résultat */
        $nombredElementsTotal = $resultFoundRows->fetchColumn();

        /* On calcule le numéro du premier élément à récupérer */
        $debut = ($page - 1) * $limite;

        /* La requête contient désormais l'indication de l'élément de départ,
        * avec le nouveau marqueur … */

        /* On construit la requête, en remplaçant les valeurs par des marqueurs. Ici, on
        * n'a qu'une valeur, limite. On place donc un marqueur là où la valeur devrait se
        * trouver, sans oublier les deux points « : » */
        $query = 'SELECT * FROM `image` LIMIT :limite OFFSET :debut';

        /* On prépare la requête à son exécution. Les marqueurs seront identifiés */
        $query = $cnx->prepare($query);

        /* On lie ici une valeur à la requête, soit remplacer de manière sûre un marqueur par
        * sa valeur, nécessaire pour que la requête fonctionne. */
        $query->bindValue(
            'limite',         // Le marqueur est nommé « limite »
            $limite,         // Il doit prendre la valeur de la variable $limite
            PDO::PARAM_INT   // Cette valeur est de type entier
        );

        /* … auquel il faut aussi lier la valeur, comme pour la limite */
        $query->bindValue('debut', $debut, PDO::PARAM_INT);

        /* Maintenant qu'on a lié la valeur à la requête, on peut l'exécuter */
        $query->execute();

        // Partie "Boucle"
        while ($element = $query->fetch()) {
            // C'est là qu'on affiche les données  :)
                $img_name = $rows['nom'];
                $img_size = $rows['taille'];
            ?>
                            
            <div class="img-block">
            <img src="<?php echo $img_size; ?>" alt="" title="<?php echo $img_name; ?>" class="img-responsive" />
            <p><strong><?php echo $img_name; ?></strong></p>
            </div>
                            
            <?php
        }

        // Partie "Liens"
        /* On calcule le nombre de pages */
        $nombreDePages = ceil($nombredElementsTotal / $limite);

        /* Si on est sur la première page, on n'a pas besoin d'afficher de lien
        * vers la précédente. On va donc ne l'afficher que si on est sur une autre
        * page que la première */
        if ($page > 1):
            ?><a href="?page=<?php echo $page - 1; ?>">Précédent</a><?php
        endif;

        /* On va effectuer une boucle autant de fois que l'on a de pages */
        for ($i = 1; $i <= $nombreDePages; $i++):
            ?><a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a> <?php
        endfor;

        /* Avec le nombre total de pages, on peut aussi masquer le lien
        * vers la page suivante quand on est sur la dernière */
        if ($page < $nombreDePages):
            ?><a href="?page=<?php echo $page + 1; ?>">Page suivante</a><?php
        endif;



        ?>
		</div>
	</div>
</body>
</html>
