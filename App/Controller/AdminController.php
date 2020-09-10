<?php

namespace App\Controller;

class AdminController{
    public function index(){
        $dados = new \App\Model\admin();
        $produtos = $dados->selecionaTodosprodutos();
        $categorias = $dados->selecionaTodasCategorias();
        $transacoes = $dados->selecTodasTransacoes();
        $clientes = $dados->selecTodosClientes();
        $loader = new \Twig\Loader\FilesystemLoader('App/View/');
        $twig = new \Twig\Environment($loader, ['auto_reload'=>true]);
        $template = $twig->load('admin.html');

        $dadosRender = array();
        $dadosRender['transacoes'] = $transacoes;
        $dadosRender['produtos'] = $produtos;
        $dadosRender['categorias'] = $categorias;
        $dadosRender['clientes'] = $clientes;
        $dadosRender['config'] = array('url'=>URL);

        echo $template->render($dadosRender);
    }
    /******************** PRODUTOS *******************/
    public function adicionaProduto(){
    try{
        $dados = new \App\Model\admin();
        $dados->setNome($_POST['nome']);
        $dados->setDescricao($_POST['descricao']);
        $dados->setPreco($_POST['preco']);
        $dados->setQuantidade($_POST['quantidade']);
        $dados->setCategoria($_POST['categoria']);
        $dados->setDestaque(10);
        $dados->setImg($_FILES['arquivo']);
        $dados->setAltura($_POST['altura']);
        $dados->setLargura($_POST['largura']);
        $dados->setComprimento($_POST['comprimento']);
        $dados->setPeso($_POST['peso']);
        $dados->adicionaProduto();

        $_SESSION['msg']=array('msg'=> 'Produto Adicionado Com Sucesso','count'=>0);
        header('Location:'.URL.'/admin');
        }catch(\Exception $e){
        $_SESSION['msg']=array('msg'=> $e->getMessage(),'count'=>0);
        header('Location:'.URL.'/admin');
        }
    }
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
        $dados->setAltura($_POST['altura']);
        $dados->setLargura($_POST['largura']);
        $dados->setComprimento($_POST['comprimento']);
        $dados->setPeso($_POST['peso']);
        $dados->atualizaProduto();
        $_SESSION['msg']=array('msg'=> 'Produto Atualizado Com Sucesso','count'=>0);
        header('Location:'.URL.'/admin');
        }catch(\Exception $e){
        $_SESSION['msg']=array('msg'=> $e->getMessage(),'count'=>0);
        header('Location:'.URL.'/admin');
        }
    }
    public function deletarProduto($id){
        try{
        $admin = new \App\Model\admin();
        $admin->deletarProduto($id);
        $_SESSION['msg']=array('msg'=> 'Produto Deletado Com Sucesso','count'=>0);
        header('Location:'.URL.'/admin');

        }catch(\Exception $e){
        $_SESSION['msg']=array('msg'=> $e->getMessage(),'count'=>0);
        header('Location:'.URL.'/admin');
        }
    }
    /******************** CATEGORIAS *******************/
    public function criarCategoria(){
        try{
            $admin = new \App\Model\admin();
            $admin->setCategoria($_POST['categoria']);
            $admin->CriaCategoria();
            $_SESSION['msg']=array('msg'=> 'Categoria Criada Com Sucesso','count'=>0);
            header('Location:'.URL.'/admin');

        }catch(\Exception $e){
            $_SESSION['msg']=array('msg'=> $e->getMessage(),'count'=>0);
            header('Location:'.URL.'/admin')
        }
    }
    public function atualizaCategoria(){
        try{
            $admin = new \App\Model\admin();
            $admin->setCategoria($_POST['categoria']);
            $admin->setId($_POST['id']);
            $admin->atualizaCategoria();
            $admin->atualizaCategoriaProduto($_POST['nomeAtual']);
            $_SESSION['msg']=array('msg'=> 'Categoria Atualizada Com Sucesso','count'=>0);
            header('Location:'.URL.'/admin');
        }catch(\Exception $e){
            $_SESSION['msg']=array('msg'=> $e->getMessage(),'count'=>0);
            header('Location:'.URL.'/admin');
        }
    }
    public function deletarCategoria($id){
        try{
            $admin = new \App\Model\admin();
            $admin->setCategoria($_POST['categoria']);
            $admin->deletaCat($id);
            $admin->deletaCategoriaProduto();
             
            $_SESSION['msg']=array('msg'=> 'Categoria Deletada Com Sucesso','count'=>0);
            header('Location:'.URL.'/admin');

        }catch(\Exception $e){
            $_SESSION['msg']=array('msg'=> $e->getMessage(),'count'=>0);
            header('Location:'.URL.'/admin');
        }
    }
}