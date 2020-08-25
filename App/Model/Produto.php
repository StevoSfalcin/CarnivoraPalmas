<?php

namespace App\Model;

class produto{
    public static function selecionaPorId($id){
        $con = \App\lib\Database\Conexao::connect();
        $sql = "SELECT * FROM produtos WHERE id LIKE :busc";
		$sql = $con->prepare($sql);
		$sql->bindValue(':busc', $id);
		$sql->execute();
        $dados = $sql->fetchObject();     
        return $dados;
    }

    public static function selecionaPorCat($cat){
        $con = \App\lib\Database\Conexao::connect();
        $sql = "SELECT * FROM produtos WHERE id LIKE :busc";
		$sql = $con->prepare($sql);
		$sql->bindValue(':busc', $cat);
		$sql->execute();
        $dados = $sql->fetchObject(); 
        return $dados;
    }

    public static function selecionaTodosprodutos(){
        $con = \App\lib\Database\Conexao::connect();
        $sql = "SELECT * FROM produtos";
		$sql = $con->prepare($sql);
        $sql->execute();
        $dados = array();
        while($row = $sql->fetchObject()){
            $dados[]=$row;
        }
        return $dados;
    }
    
    public static function pesquisa($word){
        $con = \App\lib\Database\Conexao::connect();
        $query = "SELECT * FROM produtos WHERE nome LIKE :busc";
		$sql = $con->prepare($query);
		$sql->bindValue(':busc', '%'.$word.'%');
		$sql->execute();

        while($row = $sql->fetch()){
            $dados[] = $row['nome'];
        }
		
            return $dados; 
    }
}