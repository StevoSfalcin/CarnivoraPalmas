<?php

namespace App\Model;

class admin{ 
    private $id;
    private $nome;
    private $descricao;
    private $preco;
    private $quantidade;
    private $categoria;
    private $destaque;
    private $img;
    private $largura;
    private $altura;
    private $comprimento;
    private $peso;

    /**************** PRODUTOS *****************/
    public function adicionaProduto(){
        $extensao = pathinfo($this->img['name'], PATHINFO_EXTENSION);
        $novoNome = md5(uniqid($this->img['name'])).".".$extensao;
        $diretorio = 'img/';
        move_uploaded_file($_FILES['arquivo']['tmp_name'],$diretorio.$novoNome);  

        $conn = \App\lib\Database\Conexao::Connect();
        $query = 'INSERT INTO produtos(nome,descricao,preco,quantidade,categoria,destaque,img,largura,altura,comprimento,peso) VALUES (:nome, :descricao, :preco, :quantidade, :categoria, :destaque, :img, :largura, :altura, :comprimento, :peso)';
        $sql = $conn->prepare($query);
        $sql->bindValue(1,$this->nome);
        $sql->bindValue(2,$this->descricao);
        $sql->bindValue(3,$this->preco);
        $sql->bindValue(4,$this->quantidade);
        $sql->bindValue(5,$this->categoria);
        $sql->bindValue(6,$this->destaque);
        $sql->bindValue(7,$novoNome);
        $sql->execute();
        if($sql->rowcount()){
            return true;
        }throw new \Exception("Erro ao Inserir Produto");
    }
    
    public function atualizaProduto(){
        $extensao = pathinfo($this->img['name'], PATHINFO_EXTENSION);
        $novoNome = md5(uniqid($this->img['name'])).".".$extensao;
        $diretorio = 'img/';
        move_uploaded_file($_FILES['arquivo']['tmp_name'],$diretorio.$novoNome);

        $conn = \App\lib\Database\Conexao::Connect();
        $query = 'UPDATE produtos SET nome=:nome, descricao=:descricao, preco=:preco, quantidade=:quantidade, categoria=:categoria, destaque=:destaque, img=:img, largura=:largura, altura=:altura, comprimento=:comprimento, peso=:peso WHERE id=:id';
        $sql = $conn->prepare($query);
        $sql->bindValue(1,$this->nome);
        $sql->bindValue(2,$this->descricao);
        $sql->bindValue(3,$this->preco);
        $sql->bindValue(4,$this->quantidade);
        $sql->bindValue(5,$this->categoria);
        $sql->bindValue(6,$this->destaque);
        $sql->bindValue(7,$novoNome);
        $sql->bindValue(6,$this->largura);
        $sql->bindValue(6,$this->altura);
        $sql->bindValue(6,$this->comprimento);
        $sql->bindValue(6,$this->peso);
        $sql->bindValue(8,$this->id);
        $sql->execute();
        if($sql->rowcount()){
            return true;
        }throw new \Exception("Erro ao atualizar Produto");
    }

    public function selecionaTodosprodutos(){
        $conn = \App\lib\Database\Conexao::connect();
        $query = "SELECT * FROM produtos";
		$sql = $conn->prepare($query);
        $sql->execute();
        $dados = array();
        while($row = $sql->fetchObject()){
            $dados[]=$row;
        }
        return $dados;
    }

    public function deletarProduto($id){
        $conn = \App\lib\Database\Conexao::Connect();
        $query = "DELETE FROM produtos WHERE id = :id";
		$sql = $conn->prepare($query);
		$sql->bindValue(':id', $id);
        $sql->execute();
        if($sql->rowcount()){
            return true;
        }throw new \Exception("Erro ao Deletar Produto");
    }

    /**************** CATEGORIAS *****************/

    public function CriaCategoria(){
        $conn = \App\lib\Database\Conexao::Connect();
        $query = "INSERT INTO categorias(categoria) VALUES(:cat);";
        $sql = $conn->prepare($query);
        $sql->bindValue(1,$this->categoria);
        $sql->execute();
        if($sql->rowCount()){
            return true;
        }throw new \Exception("Falha ao Criar Categoria");
    }

    public function atualizaCategoria(){
        $conn = \App\lib\Database\Conexao::Connect();
        $query = "UPDATE categorias SET categoria=:nome WHERE id=:id";
        $sql = $conn->prepare($query); 
        $sql->bindValue(1,$this->categoria);
        $sql->bindValue(2,$this->id);
        $sql->execute();
        if($sql->rowCount()){
            return true;
        }throw new \Exception("Falha ao atualizar categoria");
    }
    public function atualizaCategoriaProduto($nomeAtual){
        $conn = \App\lib\Database\Conexao::Connect();
        $query = "UPDATE produtos SET categoria=:cat WHERE categoria=:oldCat";
        $sql = $conn->prepare($query); 
        $sql->bindValue(1,$this->categoria);
        $sql->bindValue(2,$nomeAtual);
        $sql->execute();
    }

    public function selecionaTodasCategorias(){
        $conn = \App\lib\Database\Conexao::Connect();
        $query = "SELECT * FROM categorias ORDER BY categoria ASC";
        $sql = $conn->prepare($query); 
        $sql->execute();
        $dados = array();
        while($row = $sql->fetchObject()){
            $dados[] = $row;
        }
        return $dados;
    }

    public function selecionaCatPorId($id){
        $conn = \App\lib\Database\Conexao::Connect();
        $query = "SELECT * FROM categorias WHERE id = :?";
        $sql = $conn->prepare($query);
        $sql->bindValue(1,$id);
        $sql->execute();
        $dados = $sql->fetchObject();
        return $dados;
    }

    public function deletaCat($id){
        $conn = \App\lib\Database\Conexao::Connect();
        $query = 'DELETE FROM categorias WHERE id = :id';
		$sql = $conn->prepare($query);
		$sql->bindValue(1,$id);
        $sql->execute();
        if($sql->rowcount()){
            return true;
        }throw new \Exception("Erro ao Deletar Categoria");
    }
    public function deletaCategoriaProduto(){
        $conn = \App\lib\Database\Conexao::Connect();
        $query = "UPDATE produtos SET categoria = 'SemCategoria' WHERE categoria = :cat";
        $sql = $conn->prepare($query);
        $sql->bindValue(1,$this->categoria);
        $sql->execute();
    }





    /**************** GET AND SET *****************/
    public function setId($e){
        $this->id = $e;
    }
    public function getId(){
        return $this->id;
    }
    public function setNome($e){
        $this->nome = $e;
    }
    public function getNome(){
        return $this->nome;
    }
    public function setDescricao($e){
        $this->descricao = $e;
    }
    public function getDescricao(){
        return $this->descricao;
    }
    public function setPreco($e){
        $this->preco = $e;
    }
    public function getPreco(){
        return $this->preco;
    }
    public function setQuantidade($e){
        $this->quantidade = $e;
    }
    public function getQuantidade(){
        return $this->quantidade;
    }
    public function setCategoria($e){
        $this->categoria = $e;
    }
    public function getCategoria(){
        return $this->preco;
    }
    public function setDestaque($e){
        $this->destaque = $e;
    }
    public function getDestaque(){
        return $this->destaque;
    }
    public function setImg($e){
        $this->img = $e;
    }
    public function getImg(){
        return $this->Img;
    }
    public function setLargura($e){
        $this->largura = $e;
    }
    public function getLargura(){
        return $this->largura;
    }
    public function setAltura($e){
        $this->altura = $e;
    }
    public function getAltura(){
        return $this->altura;
    }
    public function setComprimento($e){
        $this->comprimento = $e;
    }
    public function getComprimento(){
        return $this->comprimento;
    }
    public function setPeso($e){
        $this->peso = $e;
    }
    public function getPeso(){
        return $this->peso;
    }
 
 
 
}




