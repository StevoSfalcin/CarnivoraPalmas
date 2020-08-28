<?php

include "config/config.php";


$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$retorna = ['erro'=>true, 'dados'=>$dados];

header('Content-Type:aplication/json');

echo json_encode($retorna);