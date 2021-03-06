<?php
session_start()
?>
<!doctype html>
<html lang="fr">
    <head>
        <title>ReSoC - Paramètres</title> 
    </head>
    <body>
       
       <?php 
         include 'header.php';
         include 'database_connexion.php';
         if (!isset($_SESSION['connected_id'])) {
         header("Location: login.php");
         exit();
 }
        ?>
        
        <div id="wrapper" class='profile'>


            <aside>
            <?php
                switch ($userId) {
                    case 24:
                        echo " <img src='avart.png' alt='Portrait de l'utilisatrice'/>";
                        break;
                    case 25:
                        echo "<img src='alex.png' alt='Portrait de l'utilisatrice'/>";
                        break;
                    case 26:
                        echo "<img src='julia.png' alt='Portrait de l'utilisatrice'/>";
                        break;
                    case 27:
                        echo "<img src='suzon.png' alt='Portrait de l'utilisatrice'/>";
                        break;
                    case 28:
                        echo "<img src='joe.png' alt='Portrait de l'utilisatrice'/>";
                        break;
                    }
                        ?>
                <section>
                    <h3>Settings</h3>
                    <p>Voici, toutes les paramètres pour l'utilisateur.rice
                        n° <?php echo intval($_GET['user_id']) ?></p>

                </section>
            </aside>
            <main>
                <?php
                /**
                 * Etape 1: Les paramètres concernent une utilisatrice en particulier
                 * La première étape est donc de trouver quel est l'id de l'utilisatrice
                 * Celui ci est indiqué en parametre GET de la page sous la forme user_id=...
                 * Documentation : https://www.php.net/manual/fr/reserved.variables.get.php
                 * ... mais en résumé c'est une manière de passer des informations à la page en ajoutant des choses dans l'url
                 */
                $userId = intval($_GET['user_id']);

                /**
                 * Etape 3: récupérer le nom de l'utilisateur
                 */
                $laQuestionEnSql = "
                    SELECT users.*, 
                    count(DISTINCT posts.id) as totalpost, 
                    count(DISTINCT given.post_id) as totalgiven, 
                    count(DISTINCT recieved.user_id) as totalrecieved 
                    FROM users 
                    LEFT JOIN posts ON posts.user_id=users.id 
                    LEFT JOIN likes as given ON given.user_id=users.id 
                    LEFT JOIN likes as recieved ON recieved.post_id=posts.id 
                    WHERE users.id = '$userId' 
                    GROUP BY users.id
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }
                $user = $lesInformations->fetch_assoc();

                /**
                 * Etape 4: à vous de jouer
                 */
                //@todo: afficher le résultat de la ligne ci dessous, remplacer les valeurs ci-après puiseffacer la ligne ci-dessous
                // echo "<pre>" . print_r($user, 1) . "</pre>";
                ?>                
                <article class='parameters'>
                    <h3>Mes paramètres</h3>
                    <dl>
                        <dt>Pseudo</dt>
                        <dd><?php echo $user['alias'] ?></dd>
                        <dt>Email</dt>
                        <dd><?php echo $user['email'] ?></dd>
                        <dt>Nombre de message</dt>
                        <dd><?php echo $user['totalpost'] ?></dd>
                        <dt>Nombre de "J'aime" donnés </dt>
                        <dd><?php echo $user['totalgiven'] ?></dd>
                        <dt>Nombre de "J'aime" reçus</dt>
                        <dd><?php echo $user['totalrecieved'] ?></dd>
                    </dl>

                </article>
            </main>
        </div>
    </body>
</html>
