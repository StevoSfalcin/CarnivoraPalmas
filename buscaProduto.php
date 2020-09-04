<?php
require_once 'App/Model/Produto.php';

$dados = filter_var(INPUT_POST,FILTER_DEFAULT);

if(isset($dados)){
    $dados = filter_var($dados['palavra'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $busca = \App\Model\Produto::pesquisa($dados['palavra']);

    echo json_encode($busca);
}

    


?>

