<?php

require_once __DIR__ . '/../../autoload.php';

$pageTitle = "Affichage match";
include_once __DIR__ . "/../../header.php";

echo '<form id="leForm" action="" method="post">';
CRUDMatch::afficherMatch("leForm");
echo '</form>';

include_once __DIR__ . "/../../footer.php";
