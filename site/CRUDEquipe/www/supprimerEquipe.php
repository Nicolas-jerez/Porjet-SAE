<?php
require_once __DIR__ . "/../../autoload.php";

$pageTitle = "Supprimer une équipe";
include_once __DIR__ . "/../../header.php";

echo '<form id="leForm" action="" method="post">';
CRUDEquipe::supEquipe("leForm");
echo '</form>';

include_once __DIR__ . "/../../footer.php";
