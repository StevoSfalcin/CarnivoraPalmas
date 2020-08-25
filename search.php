<?php
require_once 'App/Model/Produto.php';
require_once 'App/lib/Database/Conexao.php';

if($_REQUEST['numero1']){
    $dados = filter_var($_REQUEST['numero1'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $busca = \App\Model\Produto::pesquisa($_REQUEST['numero1']);

    echo json_encode($busca);
}

    


?>