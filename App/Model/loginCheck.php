<?php

namespace App\Model;

class loginCheck{
    private $user;
    private $senha;

    public function check(){
        $conn = \App\lib\Database\Conexao::Connect();
        $query = 'SELECT * FROM usuarios WHERE usuario = :usuario OR email = :user';
        $sql = $conn->prepare($query);
        $sql->bindValue(1,$this->user);
        $sql->bindValue(2,$this->user);
        $sql->execute();

        if($sql->rowCount()){
           $resultado = $sql->fetch();

            if(password_verify($this->senha,$resultado['senha'])){

                $_SESSION['user']=array('user'=>$resultado['usuario'],'id'=>$resultado['id']);
             
                return true;

            }throw new \Exception("Senha Incorreta");

            
        } throw new \Exception("Usuario nao Encontrado");


    }
    public function setUser($user){
        $this->user = $user;
    }
    public function getUser(){
        return $this->user;
    }
    public function setSenha($senha){
        $this->senha = $senha;
    }
    public function getSenha(){
        return $this->senha;
    }
}