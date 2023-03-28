<?php
require_once __DIR__ . "/../../autoload.php";

$pageTitle = "Modifier une équipe";
include_once __DIR__ . "/../../header.php";

echo '<form id="leForm" action="" method="post" enctype="multipart/form-data">';
CRUDEquipe::modifEquipe("leForm");
echo '</form>';

include_once __DIR__ . "/../../footer.php";
