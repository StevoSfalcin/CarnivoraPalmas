<?php

namespace App\Controller;

class LoginController{
    public function index(){
        try{
            $check = new \App\Model\loginCheck;
            $check->setUser(filter_var($_POST['usuario'],FILTER_SANITIZE_SPECIAL_CHARS));
            $check->setSenha($_POST['senha']);
            $check->check();
         
            $idCliente = $_SESSION['user']['id'];
            $carrinho = \App\Model\carrinho::selecionarIdCliente($idCliente);
            if($carrinho != NULL){
                $_SESSION['user']['carrinhoId'] = $carrinho->id;
            }

            header('Location:'.URL);
                              
        }catch(\Exception $e){
            $_SESSION['msg']=array('msg'=> $e->getMessage(),'count'=>0);
            header('Location:'.URL);
        }
    }
}