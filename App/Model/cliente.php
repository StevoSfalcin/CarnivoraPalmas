<?php

namespace App\Model;

class cliente{ 
    private $id;
    private $nome;
    private $email;
    private $senha;
    private $endereco;
    private $img;


/**************** SELECIONA DADOS DO USUARIO *****************/
public function selecionaClienteId($id){
    $conn = \App\lib\Database\Conexao::Connect();
    $query = "SELECT * FROM usuarios WHERE id = :id";
    $sql = $conn->prepare($query);
    $sql->bindValue(1,$id);
    $sql->execute();
    if($sql->rowCount()){
        return $sql->fetch();
    }else{
        return NULL;
    }
}

/**************** CADASTRO *****************/
public function cadastraCliente(){
    $conn = \App\lib\Database\Conexao::Connect();
    $query = "SELECT * FROM usuarios WHERE email = :email";
    $sql = $conn->prepare($query);
    $sql->bindValue(1,$this->email);
    $sql->execute();
    if($sql->rowCount()){
        throw new \Exception("Esse email ja esta cadastrado");
    }else{
    $query = 'INSERT INTO usuarios(usuario,email,senha) VALUES (:nome, :email, :senha)';
    $sql = $conn->prepare($query);
    $sql->bindValue(1,$this->nome);
    $sql->bindValue(2,$this->email);
    $sql->bindValue(3,$this->senha);
    $sql->execute();
    if($sql->rowcount()){
        return true;
    }throw new \Exception("Erro ao Cadastrar");
}
}
/**************** SELECIONA ENDEREÇO *****************/
public function selecionaEndereco($id){
    $conn = \App\lib\Database\Conexao::Connect();
    $query = "SELECT * FROM enderecousuarios WHERE idCliente = :idCliente";
    $sql = $conn->prepare($query);
    $sql->bindValue(1,$id);
    $sql->execute();
    if($sql->rowCount()){
        return $sql->fetchObject();
    }else{
        return NULL;
    }
}

/**************** ATUALIZA CADASTRO *****************/
public function atualizaCadastro(){
    //FOTO
    if($this->img != null){
    $extensao = pathinfo($this->img['name'], PATHINFO_EXTENSION);
    $novoNome = md5(uniqid($this->img['name'])).".".$extensao;
    $diretorio = 'img/';
    move_uploaded_file($_FILES['arquivo']['tmp_name'],$diretorio.$novoNome);
    }else{
    $extensao = 'png';
    $novoNome = 'semImg';
    $diretorio = 'img/';
    }  
    $conn = \App\lib\Database\Conexao::Connect();
    $query = 'UPDATE produtos SET nome=:nome, descricao=:descricao, preco=:preco, quantidade=:quantidade, categoria=:categoria, destaque=:destaque, img=:img WHERE id=:id';
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


public function selecTodasTransacoes($id){
    $conn = \App\lib\Database\Conexao::connect();
    $query = "SELECT * FROM transacoes WHERE idCliente=:idCliente";
	$sql = $conn->prepare($query);
    $sql->bindValue(1,$id);
    $sql->execute();
    $dados = array();
    while($row = $sql->fetchObject()){
        $dados[]=$row;
    }
    return $dados;
}

public function alterarSenha(){
    $conn = \App\lib\Database\Conexao::connect();
    $query = "SELECT * FROM usuarios WHERE id=:id";
	$sql = $conn->prepare($query);
    $sql->bindValue(1,$this->id);
    $sql->execute();
    $resultado = $sql->fetch();
    if(password_verify($this->senha['senhaAntiga'],$resultado['senha'])){
        $query = "UPDATE usuarios SET senha=:senha WHERE id=:id";
        $sql = $conn->prepare($query);
        $sql->bindValue(1,$this->senha['senhaNova']);
        $sql->bindValue(1,$this->id);
        $sql->execute();
        if($sql->rowCount()){
            return true;
        }throw new \Exception('Nao Foi Possivel Alterar a Senha');
    }throw new \Exception('Senha Antiga Incorreta');
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
public function setEmail($e){
    $this->email = $e;
}
public function getEmail(){
    return $this->email;
}
public function setSenha($e){
    $this->senha = $e;
}
public function getSenha(){
    return $this->senha;
}
public function setImg($e){
    $this->img = $e;
}
public function getImg(){
    return $this->Img;
}


 
}




