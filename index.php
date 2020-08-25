<?php
session_start();

require_once 'config/config.php';
require_once 'vendor/autoload.php';

$core = new \App\Core\Core();
echo $core->start($_GET);

?>
