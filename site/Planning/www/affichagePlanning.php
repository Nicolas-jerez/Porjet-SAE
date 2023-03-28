<?php
require_once __DIR__ . "/../../autoload.php";

$pageTitle = "Planning";
include_once __DIR__ . "/../../header.php";

echo '<form id="leForm" action="" method="post">';

// Lundi de la semaine courante
$now = new DateTime();
$idSemaine = $_POST['semaine'] ?? date("o/W");
$semaine = new DateTime();
$yw = explode('/', $idSemaine);
$semaine->setISODate($yw[0], $yw[1]);

Planning::listeSemaines($idSemaine);
echo '</form>';
Planning::afficheParSemaine($semaine);

include_once __DIR__ . "/../../footer.php";
