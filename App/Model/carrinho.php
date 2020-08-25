<?php

namespace App\Model;

class carrinho{
    static private $carrinho;
    static private $posicao;
    static private $produto;

//SELECIONA O CARRINHO POR ID
    public static function selecionaIdCarrinho($id){
        $conn = \App\lib\Database\Conexao::connect();
        $query = "SELECT * FROM carrinhos WHERE id LIKE :id";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':id',$id);
        $stmt->execute();
       
        return $stmt->fetchObject();
    }

//SELECIONA O PRODUTO POR ID
    public static function selecionarIdProduto($id){
        $conn = \App\lib\Database\Conexao::connect();
        $query = "SELECT * FROM produtos WHERE id LIKE :id";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':id',$id);
        $stmt->execute();
       
        return $stmt->fetchObject();
    }
//SELECIONA O PRODUTO POR ID DO CLIENTE    
    public static function selecionarIdCliente($idCliente){
        $conn = \App\lib\Database\Conexao::connect();
        $query = "SELECT * FROM carrinhos WHERE idCliente LIKE :id";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':id',$idCliente);
        $stmt->execute();
        if($stmt->rowCount()){
        return $stmt->fetchObject();
        }else{
            return NULL;
        }
    }

//DELETA PRODUTO DO CARRINHO
    public static function deletaProdutoCarrinho(){
        $conn = \App\lib\Database\Conexao::connect();
        $expPro = explode(';',self::$carrinho->produto_id);
        $expQuant = explode(';',self::$carrinho->quantidade);
        unset($expPro[self::$posicao]);
        unset($expQuant[self::$posicao]);

        //VERIFICA SE TEM PRODUTOS NO CARRINHO
        if($expPro and $expQuant !== ''){
            $impPro = implode(';',$expPro);
            $impQuant = implode(';',$expQuant);
            $query = 'UPDATE carrinhos SET produto_id=:pro, quantidade=:qnt, idCliente=:idC WHERE id=:id';
            $stmt = $conn->prepare($query);
            $stmt->bindValue(1,$impPro);
            $stmt->bindValue(2,$impQuant);
            $stmt->bindValue(3,self::$carrinho->idCliente);
            $stmt->bindValue(4,self::$carrinho->id);
            $stmt->execute();
            if($stmt->rowcount()){
                return true;
            }throw new \Exception("Erro ao Deletar Produto");

         //SE O CARRINHO ESTIVER VAZIO SERA DELETADO
        }else{
            $query = 'DELETE FROM carrinhos WHERE carrinhos.id LIKE :id';
            $stmt = $conn->prepare($query);
            $stmt->bindValue(1,self::$carrinho->id);
            $stmt->execute();
            if($stmt->rowcount()){
                $_SESSION['user']['carrinhoId'] = NULL;

                return true;
            }throw new \Exception("Erro ao Deletar Carrinho");
        }
    }

//INSERE PRODUTO NO CARRINHO
    public static function inserirProdutoCarrinho(){
        if(isset($_SESSION['user'])){
        //USUARIO LOGADO COM CARRINHO
            if(isset($_SESSION['user']['carrinhoId'])){
            $conn = \App\lib\Database\Conexao::connect();
            $expPro = explode(';',self::$carrinho->produto_id);
            $expQuant = explode(';',self::$carrinho->quantidade);

            $expPro[] = self::$produto['produto_id'];
            $expQuant[] = self::$produto['quantidade'];

            $impPro = implode(';',$expPro);
            $impQuant = implode(';',$expQuant);

            $conn = \App\lib\Database\Conexao::connect();
            $query = "UPDATE carrinhos SET produto_id=:idp, quantidade=:qnt, idCliente=:idC WHERE id=:id";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(1,$impPro);
            $stmt->bindValue(2,$impQuant);
            $stmt->bindValue(3,$_SESSION['user']['id']);
            $stmt->bindValue(4,self::$carrinho->id);
            $stmt->execute();

            //USUARIO LOGADO SEM CARRINHO
            }else{
            $conn = \App\lib\Database\Conexao::connect();
            $query = "INSERT INTO carrinhos(produto_id,quantidade,idCliente) VALUES (:prodId, :qnt, :idCliente)";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(1,self::$produto['produto_id']);
            $stmt->bindValue(2,self::$produto['quantidade']);
            $stmt->bindValue(3,$_SESSION['user']['id']);
            $stmt->execute();
            if($stmt->rowCount()){
               return true;
            }throw new \Exception("Nao Foi Possivel adicionar produto ao carrinho");
            }

        //USUARIO NAO LOGADO
        }else{
            //USUARIO NAO LOGADO COM CARRINHO
            if(isset($_SESSION['carrinho'])){
            $expPro = explode(';',$_SESSION['carrinho']['produto_id']);
            $expQuant = explode(';',$_SESSION['carrinho']['quantidade']);
            $expPro[] = self::$produto['produto_id'];
            $expQuant[] = self::$produto['quantidade'];
            $impPro = implode(';',$expPro);
            $impQuant = implode(';',$expQuant);
            $_SESSION['carrinho'] = array('produto_id'=>$impPro,'quantidade'=>$impQuant);
            return true;
            //USUARIO NAO LOGADO SEM CARRINHO
            }else{
                $_SESSION['carrinho'] = array('produto_id'=>self::$produto['produto_id'],'quantidade'=>self::$produto['quantidade']);
                return true;
          
            }
      
    }
    }



    public function setCarrinho($i){
        self::$carrinho = $i;
    }
    public function returnCarrinho(){
        return self::$carrinho;
    }

    public function setPosicao($i){
        self::$posicao = $i;
    }
    public function returnPosicao(){
        return self::$posicao;
    }

    public function setProduto($i){
        self::$produto = $i;
    }
    public function returnProduto(){
        return self::$produto;
    }




}