<?php

if(!isset($routes)) return;
$namespace = "\NodCMS\Installer\Controllers\\";

$routes->get('/installer', '\NodCMS\Installer\Controllers\Installer::start');