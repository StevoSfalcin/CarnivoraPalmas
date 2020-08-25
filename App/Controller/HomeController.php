<?php

namespace App\Controller;

class HomeController{
    public function index(){
    $dados = \App\Model\Home::produtosDestaque();
    $dadosCat = \App\Model\Home::categorias();
    $loader = new \Twig\Loader\FilesystemLoader('App/View/');
    $twig = new \Twig\Environment($loader, ['auto_reload'=>true]);
    $template = $twig->load('home.html');

    $dadosRender = array();
    $dadosRender['destaques'] = $dados;
    $dadosRender['categorias'] = $dadosCat;
    $dadosRender['user'] = $_SESSION['user'] ?? ''  ;
    $dadosRender['msg'] = $_SESSION['msg'] ?? null;
    $dadosRender['carrinho'] = $_SESSION['carrinho'] ?? '';
    $dadosRender['config'] = array('url'=>URL,'scriptPagseguro'=>SCRIPT_PAGSEGURO);


    echo $template->render($dadosRender);
    }

    public function logout(){
        session_destroy();
        header('Location:'.URL);
    }


}