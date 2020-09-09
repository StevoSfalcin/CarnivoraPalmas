<?php
require_once '../config/config.php';
require_once '../App/lib/Database/Conexao.php';
require_once '../App/Model/Produto.php';

    if(isset($_POST['palavra'])){
    $dados = filter_var($_POST['palavra'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $busca = \App\Model\Produto::pesquisa($dados);

    echo json_encode($busca);


}


?>