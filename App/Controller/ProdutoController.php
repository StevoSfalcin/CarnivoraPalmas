<?php

namespace App\Controller;

class ProdutoController{
    public function id($id){
        $dados = \App\Model\Produto::selecionaPorId($id);
        $dadosCat = \App\Model\Home::categorias();
        $loader = new \Twig\Loader\FilesystemLoader('App/View/');
        $twig = new \Twig\Environment($loader, ['auto_reload'=>true]);
        $template = $twig->load('home.html');
    
        $dadosRender = array();
        $dadosRender['produto'] = $dados;
        $dadosRender['categorias'] = $dadosCat;
        $dadosRender['user'] = $_SESSION['user'] ?? ''  ;
        $dadosRender['config'] = array('url'=>URL);

        echo $template->render($dadosRender);

    }

    public function categoria($cat){
        $dados = \App\Model\Produto::selecionaPorCat($cat);
        $dadosCat = \App\Model\Home::categorias();
        $loader = new \Twig\Loader\FilesystemLoader('App/View/');
        $twig = new \Twig\Environment($loader, ['auto_reload'=>true]);
        $template = $twig->load('home.html');
    
        $dadosRender = array();
        $dadosRender['destaques'] = $dados;
        $dadosRender['categorias'] = $dadosCat;
        $dadosRender['user'] = $_SESSION['user'] ?? ''  ;
        $dadosRender['config'] = array('url'=>URL,'scriptPagseguro'=>SCRIPT_PAGSEGURO);

        echo $template->render($dadosRender);

    }
}