<?php
require_once 'App/Model/Produto.php';


    $dados = filter_var($_REQUEST['palavra'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $busca = \App\Model\Produto::pesquisa($dados);

    echo json_encode($busca);


    


?>