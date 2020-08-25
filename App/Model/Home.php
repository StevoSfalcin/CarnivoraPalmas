<?php

namespace App\Model;

class Home{
    public static function produtosDestaque(){
        $con = \App\lib\Database\Conexao::connect();
        $query = 'SELECT * FROM produtos';
        $sql = $con->prepare($query);
        $sql->execute();

        $dados = array();

        while($row = $sql->fetchObject()){
            $dados[] = $row;     
        }
            
        return $dados;
    }

    public static function categorias(){
        $con = \App\lib\Database\Conexao::connect();
        $sql = "SELECT * FROM categorias";
		$sql = $con->prepare($sql);
		$sql->execute();

        while($row = $sql->fetchObject()){
            $dados[] = $row;
        }
        return $dados;
    }


    
}