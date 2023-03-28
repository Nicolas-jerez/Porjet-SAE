<?php
require_once __DIR__ . '/../../autoload.php';

$pageTitle = "Suppression d'un match";
include_once __DIR__ . "/../../header.php";

echo '<form id="leForm" action="" method="post">';
CRUDMatch::supMatch("leForm");
echo '</form>';

include_once __DIR__ . "/../../footer.php";
