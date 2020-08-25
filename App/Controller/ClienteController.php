<?php

namespace App\Controller;

class ClienteController{
public function index(){
    $dados = new \App\Model\cliente();
    $cliente = $dados->selecionaClienteId($_SESSION['user']['id']) ?? NULL;
    $endereco = $dados->selecionaEndereco($_SESSION['user']['id']) ?? NULL;
    $loader = new \Twig\Loader\FilesystemLoader('App/View/');
    $twig = new \Twig\Environment($loader, ['auto_reload'=>true]);
    $template = $twig->load('cliente.html');

    $dadosRender = array();
    $dadosRender['config'] = array('url'=>URL);
    $dadosRender['cliente'] = $cliente;
    $dadosRender['endereco'] = $endereco;

    echo $template->render($dadosRender);
}
/* CADASTRO */
public function cadastrarCliente(){
    $senha = password_hash($_POST['senha'],PASSWORD_BCRYPT,['cost'=>11]);
    try{
        $dados = new \App\Model\cliente();
        $dados->setNome($_POST['nome']);
        $dados->setEmail($_POST['email']);
        $dados->setSenha($senha);
        $dados->cadastraCliente();
    
        header('Location:'.URL);

        }catch(\Exception $e){
          
            echo $e->getMessage();
            
        }
}

/* ATUALIZA CADASTRO */
public function atualizaProduto(){
try{
    $dados = new \App\Model\admin();
    $dados->setId($_POST['id']);
    $dados->setNome($_POST['nome']);
    $dados->setDescricao($_POST['descricao']);
    $dados->setPreco($_POST['preco']);
    $dados->setQuantidade($_POST['quantidade']);
    $dados->setCategoria($_POST['categoria']);
    $dados->setDestaque(10);
    $dados->setImg($_FILES['arquivo']);
    $dados->atualizaProduto();
    header('Location:'.URL.'/admin');
    }catch(\Exception $e){
        var_dump($e->getMessage());
    }
}
public function deletarProduto($id){
    try{
    $admin = new \App\Model\admin();
    $admin->deletarProduto($id);
    header('Location:'.URL.'/admin');
    }catch(\Exception $e){
    var_dump($e->getMessage());
    }
    }





}