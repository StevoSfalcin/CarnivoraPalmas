<?php

namespace App\Controller;

class ClienteController{
public function index(){
    $dados = new \App\Model\cliente();
    $cliente = $dados->selecionaClienteId($_SESSION['user']['id']) ?? NULL;
    $endereco = $dados->selecionaEndereco($_SESSION['user']['id']) ?? NULL;
    $transacoes = $dados->selecTodasTransacoes($_SESSION['user']['id']) ?? NULL;
    $loader = new \Twig\Loader\FilesystemLoader('App/View/');
    $twig = new \Twig\Environment($loader, ['auto_reload'=>true]);
    $template = $twig->load('cliente.html');

    $dadosRender = array();  
    $dadosRender['msg'] = $_SESSION['msg'] ?? NULL;
    $dadosRender['transacoes'] = $transacoes;
    $dadosRender['cliente'] = $cliente;
    $dadosRender['endereco'] = $endereco;
    $dadosRender['config'] = array('url'=>URL);

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

public function alterarSenha(){
    try{
    $senha = array();
    $senha['senhaAntiga'] = $_POST['senhaAntiga'];
    $senha['senhaNova'] = password_hash($_POST['senhaNova'],PASSWORD_BCRYPT,['cost'=>'11']);
    $dados = new \App\Model\cliente();
    $dados->setId($_POST['idCliente']);
    $dados->setSenha($senha);
    $dados->alterarSenha();
    header('Location:'.URL.'/cliente');
    }catch(\Exception $e){
        $_SESSION['msg']=array('msg'=> $e->getMessage(),'count'=>0);
        header('Location:'.URL.'/cliente');
}
}





}