<?php
spl_autoload_register(function ($className) {
    @include __DIR__ . "/CRUDEquipe/" . $className . ".php";
});
spl_autoload_register(function ($className) {
    @include __DIR__ . "/CRUDMatch/" . $className . ".php";
});
spl_autoload_register(function ($className) {
    @include __DIR__ . "/CRUDFonction/" . $className . ".php";
});
spl_autoload_register(function ($className) {
    @include __DIR__ . "/CRUDOrganisation/" . $className . ".php";
});
spl_autoload_register(function ($className) {
    @include __DIR__ . "/Planning/" . $className . ".php";
});
spl_autoload_register(function ($className) {
    @include __DIR__ . "autoload.php/" . $className . ".php";
});
