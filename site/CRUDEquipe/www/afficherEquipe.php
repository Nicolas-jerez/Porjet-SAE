<?php
require_once __DIR__ . "/../../autoload.php";

$pageTitle = "Affichage équipe";
include_once __DIR__ . "/../../header.php";

echo '<form id="leForm" action="" method="post">';
CRUDEquipe::afficherEquipe("leForm");
echo '</form>';

include_once __DIR__ . "/../../footer.php";
