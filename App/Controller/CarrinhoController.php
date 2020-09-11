<?php

namespace App\Controller;

class CarrinhoController{
//EXIBE CARRINHO PAGINA CARRINHO
    public function index(){
    $dadosProduto = new \App\Model\carrinho();
    $loader = new \Twig\Loader\FilesystemLoader('App/View/');
    $twig = new \Twig\Environment($loader, ['auto_reload'=>true]);
    $template = $twig->load('carrinho.html');

    $dadosRender['user'] = $_SESSION['user'] ?? '' ;
    $dadosRender['carrinho'] = $_SESSION['carrinho'] ?? '';

    //VERIFICA SE USUARIO ESTA LOGADO
    if(isset($_SESSION['user']['id'])){
    $idCliente = $_SESSION['user']['id'];
    $carrinho = \App\Model\carrinho::selecionarIdCliente($idCliente);
    if($carrinho != NULL){
        $_SESSION['user']['carrinhoId'] = $carrinho->id;
    }
    }

    //VERIFICA SE USUARIO LOGADO TEM CARRINHO
    if(isset($_SESSION['user']['carrinhoId'])){
    $dadosCar = \App\Model\carrinho::selecionaIdCarrinho($_SESSION['user']['carrinhoId']);
    $produto['id'] = $dadosCar->produto_id;
    $produto['qnt'] = $dadosCar->quantidade;

    }else{   
    //USUARIO NAO LOGADO 
    $produto['id'] = $_SESSION['carrinho']['produto_id'] ?? NULL;
    $produto['qnt'] = $_SESSION['carrinho']['quantidade'] ?? NULL;
    }

    //SE EXISTIR PRODUTO NO CARRINHO
    if($produto['id'] != NULL){
        if(strpos($produto['id'],';') !== true){
        $expProd = explode(';',$produto['id']);
        $expquant = explode(';', $produto['qnt']);
        $i=1;
        }else{
        $expProd = $produto['id'];
        $expquant = $produto['qnt'];
        }  
        //ADICIONA OS PRODUTOS AO ARRAY DO CARRINHO    
        $dadosProd = array();
        $i=0;
        foreach($expProd as $Idproduto){
            $produto = $dadosProduto::selecionarIdProduto($Idproduto);
            $produto->qntCarrinho = $expquant[$i]; 
            $produto->peso = $produto->peso * $expquant[$i]; 
            $produto->volume = ($produto->altura * $produto->largura * $produto->comprimento) * $expquant[$i];

            $produto->carrinhoId = $_SESSION['user']['carrinhoId'] ?? '';
            //SUBTOTAL DO PRODUTO
            $preco = str_replace(',','.',$produto->preco);
            $subTotal = $preco * $produto->qntCarrinho;
            $produto->subTotal = $subTotal;
            $produto->posicao = $i;
            $dadosProd[] = $produto;
            $i++;
        }
        //VALOR TOTAL DOS PRODUTOS
        $valorTotalProd = 0;
        $pesoTotal = 0;
        $volume = 0;
        $volumeTotal = 0;
        foreach($dadosProd as $produto){
            $valorTotalProd += $produto->subTotal;
            $valorTotalProd = str_replace('.',',',$valorTotalProd);
            $pesoTotal += $produto->peso;
            $volume += $produto->volume;
            $volumeTotal =+ round(pow($volume,1/3),2);
        }
    }

    $dadosRender['produtos'] = $dadosProd ?? '';

    $dadosRender['ValorTotalProd'] = $valorTotalProd ?? '';
    $dadosRender['volumeTotal'] = $volumeTotal ?? '';
    $dadosRender['pesoTotal'] = $pesoTotal ?? '';
    $dadosRender['msg'] = $_SESSION['msg'] ?? null;

    $dadosRender['config'] = array('url'=>URL,'emailLoja'=>EMAIL_LOJA,'moedaPagamento'=>MOEDA_PAGAMENTO,'urlNotificacao'=>URL_NOTIFICACAO,'scriptPagseguro'=>SCRIPT_PAGSEGURO);
   
    echo $template->render($dadosRender);
    }

//DELETAR PRODUTO DO CARRINHO
    public function deletarProdutoCarrinho(){
        //VERIFICA SE È UM CARRINHO PERMANENTE
        if(isset($_SESSION['user']['id'])){
            $ClassCarr = new \App\Model\carrinho();
            $dadosCar = $ClassCarr::selecionaIdCarrinho($_POST['carrinhoId']);
            $ClassCarr->setCarrinho($dadosCar);
            $ClassCarr->setPosicao($_POST['posicaoProduto']);

            try{
            $ClassCarr::deletaProdutoCarrinho();
            header('Location:'.URL);
            }catch(\Exception $e){
                var_dump($e->getMessage());
            }
            
        //DELETA PRODUTO DO CARRINHO TEMPORARIO
        }else{ 
        if(strpos($_SESSION['carrinho']['produto_id'],';')){
            $expPro = explode(';',$_SESSION['carrinho']['produto_id']);
            $expQuant = explode(';',$_SESSION['carrinho']['quantidade']);
            unset($expPro[$_POST['posicaoProduto']]);
            unset($expQuant[$_POST['posicaoProduto']]);
            $impPro = implode(';',$expPro);
            $impQuant = implode(';',$expQuant);
            $_SESSION['carrinho'] = array('produto_id'=>$impPro,'quantidade'=>$impQuant);
            header('Location:'.URL);

            //SE NAO HOUVER HOUVER MAIS PRODUTOS DELETAR CARRINHO
            }else{
            $_SESSION['carrinho'] = NULL;
            header('Location:'.URL);
            }
    
            }

    }

//INSERIR PRODUTO AO CARRINHO   
    public function InserirProduto(){
        $ClassCarr = new \App\Model\carrinho();

        //VERIFICA SE O CLIENTE JA POSSUI UM CARRINHO E ATUALIZA SESSAO CARRINHO
        if(isset($_SESSION['user']['carrinhoId'])){
        $carrinho = $ClassCarr::selecionaIdCarrinho($_SESSION['user']['carrinhoId']);
        }
    
        $produto['produto_id'] = $_POST['produto_id'];
        $produto['quantidade'] = $_POST['quantidade'];
    
        $ClassCarr->setProduto($produto);
        $ClassCarr->setCarrinho($carrinho);
    
        try{
        $ClassCarr::inserirProdutoCarrinho();
        header('Location:'.URL);
    }catch(\Exception $e){
        var_dump($e->getMessage());
    }
    } 


}