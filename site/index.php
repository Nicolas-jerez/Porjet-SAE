<?php
require_once __DIR__ . "/autoload.php";

$pageTitle = "Accueil";
include_once __DIR__ . "/header.php";
?>

<h1>Menu de l'application</h1>
<div class="tiers">
    <h2>Équipes</h2>
    <ul>
        <li><a href="CRUDEquipe/www/afficherEquipe.php">Afficher une équipe</a></li>
        <li><a href="CRUDEquipe/www/modifierEquipe.php">Modifier une équipe</a></li>
        <li><a href="CRUDEquipe/www/ajouterEquipe.php">Ajouter une équipe</a></li>
        <li><a href="CRUDEquipe/www/supprimerEquipe.php">Supprimer une équipe</a></li>
    </ul>
</div>
<div class="tiers">
    <h2>Matches</h2>
    <ul>
        <li><a href="CRUDMatch/www/afficherMatch.php">Afficher un match</a></li>
        <li><a href="CRUDMatch/www/modifierMatch.php">Modifier un match</a></li>
        <li><a href="CRUDMatch/www/ajouterMatch.php">Ajouter un match</a></li>
        <li><a href="CRUDMatch/www/supprimerMatch.php">Supprimer une match</a></li>
    </ul>
</div>
<div class="tiers">
    <h2>Plannings</h2>
    <ul>
        <li><a href="Planning/www/affichagePlanning.php">Voir le planning</a></li>
    </ul>
</div>
<?php
include_once __DIR__ . "/footer.php";
?>
