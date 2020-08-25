<?php

namespace App\Core;

class Core{
    private $url;
    private $controller = 'HomeController';
    private $metodo = 'index';
    private $parametro = null;
    private $erro;
    
    public function start($request){

        //MSG ERRO
        $this->erro = $_SESSION['msg'] ?? null;
        if(isset($this->erro)){
        if($this->erro['count'] === 0){
            $_SESSION['msg']['count']++;
        }else{
            unset($_SESSION['msg']);
        }
        }

        //URL AMIGAVEL
        if(isset($request['url'])){
            $dados = filter_var($request['url'],FILTER_SANITIZE_URL);
            $this->url = explode('/',$dados);
            //CONTROLLER
            $this->controller = ucfirst($this->url[0]).'Controller';
            array_shift($this->url);
            //METODO
            if(isset($this->url[0])){
                $this->metodo = $this->url[0];
                array_shift($this->url);
            }
            //PARAMETRO
            if(isset($this->url[0])){
                $this->parametro = $this->url[0];
            }

        }
        $checkObjeto = '\\App\\Controller\\'.$this->controller;
        
        if(!class_exists($checkObjeto)){
            $checkObjeto = '\App\Controller\ErroController';
            $this->metodo = 'index';
        }

       return call_user_func_array(array(new $checkObjeto,$this->metodo),array($this->parametro)); 

    }
}