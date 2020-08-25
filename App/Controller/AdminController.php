<?php

namespace App\Controller;

class AdminController{
    public function index(){
        $dados = new \App\Model\admin();
        $produtos = $dados->selecionaTodosprodutos();
        $categorias = $dados->selecionaTodasCategorias();
        $loader = new \Twig\Loader\FilesystemLoader('App/View/');
        $twig = new \Twig\Environment($loader, ['auto_reload'=>true]);
        $template = $twig->load('admin.html');

        $dadosRender = array();
        $dadosRender['produtos'] = $produtos;
        $dadosRender['categorias'] = $categorias;
        $dadosRender['config'] = array('url'=>URL);

        echo $template->render($dadosRender);
    }

    /******************** PRODUTOS *******************/

    public function adicionaProduto(){
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
        $dados->adicionaProduto();
        header('Location:'.URL.'/admin');

        }catch(\Exception $e){
            var_dump($e->getMessage());
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

    /******************** CATEGORIAS *******************/

    public function criarCategoria(){
        try{
            $admin = new \App\Model\admin();
            $admin->setCategoria($_POST['categoria']);
            $admin->CriaCategoria();
            header('Location:'.URL.'/admin');

        }catch(\Exception $e){
            var_dump($e->getMessage());
        }
    }

    public function atualizaCategoria(){
        try{
            $admin = new \App\Model\admin();
            $admin->setCategoria($_POST['categoria']);
            $admin->setId($_POST['id']);
            $admin->atualizaCategoria();
            $admin->atualizaCategoriaProduto($_POST['nomeAtual']);
            header('Location:'.URL.'/admin');

        }catch(\Exception $e){
            var_dump($e->getMessage());
        }
    }

    public function deletarCategoria($id){
        try{
            $admin = new \App\Model\admin();
            $admin->setCategoria($_POST['categoria']);
            $admin->deletaCat($id);
            $admin->deletaCategoriaProduto();
            header('Location:'.URL.'/admin');

        }catch(\Exception $e){
            var_dump($e->getMessage());
        }

    }



}