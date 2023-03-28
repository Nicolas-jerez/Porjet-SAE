<?php
require_once __DIR__ . "/../../autoload.php";

$pageTitle = "Ajouter une équipe";
include_once __DIR__ . "/../../header.php";

echo '<form id="leForm" action="" method="post" enctype="multipart/form-data">';
CRUDEquipe::ajoutEquipe("leForm");
echo '</form>';

include_once __DIR__ . "/../../footer.php";
