<?php
require_once 'App/Model/Produto.php';

$dados = filter_var(INPUT_POST,FILTER_DEFAULT);

if($_REQUEST['numero1']){
    $dados = filter_var($dados['palavra'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $busca = \App\Model\Produto::pesquisa($_REQUEST['numero1']);

    echo json_encode($busca);
}

    


?>