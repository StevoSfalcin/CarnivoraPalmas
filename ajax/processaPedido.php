<?php
session_start();
include 'config/config.php';
include 'App/lib/Database/Conexao.php';
include 'App/Model/carrinho.php';

//CONEXAO COM BANCO DE dados
$conn = \App\lib\Database\Conexao::Connect();

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

$dadosArray["email"]=EMAIL_PAGSEGURO;
$dadosArray["token"]=TOKEN_PAGSEGURO;

//PROCESSA CARRINHO
$carrinhoId = $dados['carrinhoId'];
$dadosCarrinho = \App\Model\carrinho::selecionaIdCarrinho($carrinhoId);
$produto['id'] = $dadosCarrinho->produto_id;
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
    $ValorTotalProd = 0;
    foreach($dadosProd as $produto){
        $ValorTotalProd += $produto->subTotal;
    }


//PRODUTO
$dadosArray['itemId1'] = $dados['itemId1'];
$dadosArray['itemDescription1'] = $dados['itemDescription1'];
$dadosArray['itemAmount1'] = $dados['itemAmount1'];
$dadosArray['itemQuantity1'] = $dados['itemQuantity1'];

//CREDITO
if($dados['paymentMethod'] == 'creditCard'){
    $dadosArray['creditCardToken'] = $dados['tokenCartao'];
    $dadosArray['installmentQuantity'] = $dados['qntParcelas'];
    $dadosArray['installmentValue'] = $dados['valorParcelas'];
    $dadosArray['noInterestInstallmentQuantity'] = $dados['noIntInstalQuantity'];
    $dadosArray['creditCardHolderName'] = $dados['creditCardHolderName'];
    $dadosArray['creditCardHolderCPF'] = $dados['creditCardHolderCPF'];
    $dadosArray['creditCardHolderBirthDate'] = $dados['creditCardHolderBirthDate'];
    $dadosArray['creditCardHolderAreaCode'] = $dados['senderAreaCode'];
    $dadosArray['creditCardHolderPhone'] = $dados['senderPhone'];
    //dados DONO DO CARTAO
    $dadosArray['billingAddressStreet'] = $dados['billingAddressStreet'];
    $dadosArray['billingAddressNumber'] = $dados['billingAddressNumber'];
    $dadosArray['billingAddressComplement'] = $dados['billingAddressComplement'];
    $dadosArray['billingAddressDistrict'] = $dados['billingAddressDistrict'];
    $dadosArray['billingAddressPostalCode'] = $dados['billingAddressPostalCode'];
    $dadosArray['billingAddressCity'] = $dados['billingAddressCity'];
    $dadosArray['billingAddressState'] = $dados['billingAddressState'];
    $dadosArray['billingAddressCountry'] = $dados['billingAddressCountry'];

//DEBITO ONLINE    
}elseif ($dados['paymentMethod'] == "eft") {
    $dadosArray['bankName'] = $dados['bankName'];
}

//dados DE PAGAMENTO
$dadosArray['paymentMode'] = 'default';
$dadosArray['paymentMethod'] = $dados['paymentMethod'];
$dadosArray['currency'] = $dados['currency'];
$dadosArray['extraAmount'] = $dados['extraAmount'];

$dadosArray['receiverEmail'] = 'stevosfalcin@gmail.com';
$dadosArray['notificationURL'] = URL_NOTIFICACAO;
$dadosArray['reference'] = $dados['reference'];
//dados COMPRADOR
$dadosArray['senderName'] = $dados['senderName'];
$dadosArray['senderCPF'] = $dados['senderCPF'];
$dadosArray['senderAreaCode'] = $dados['senderAreaCode'];
$dadosArray['senderPhone'] = $dados['senderPhone'];
$dadosArray['senderEmail'] = $dados['senderEmail'];
$dadosArray['senderHash'] = $dados['hashCartao'];
//ENDERECO DE ENTREGA
$dadosArray['shippingAddressRequired'] = $dados['shippingAddressRequired'];
$dadosArray['shippingAddressStreet'] = $dados['shippingAddressStreet'];
$dadosArray['shippingAddressNumber'] = $dados['shippingAddressNumber'];
$dadosArray['shippingAddressComplement'] = $dados['shippingAddressComplement'];
$dadosArray['shippingAddressDistrict'] = $dados['shippingAddressDistrict'];
$dadosArray['shippingAddressPostalCode'] = $dados['shippingAddressPostalCode'];
$dadosArray['shippingAddressCity'] = $dados['shippingAddressCity'];
$dadosArray['shippingAddressState'] = $dados['shippingAddressState'];
$dadosArray['shippingAddressCountry'] = $dados['shippingAddressCountry'];
$dadosArray['shippingType'] = $dados['shippingType'];
$dadosArray['shippingCost'] = $dados['shippingCost'];

//REQUISICAO HTTP PAGSEGURO
$buildQuery = http_build_query($dadosArray);
$url = URL_PAGSEGURO . "transactions";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type: application/x-www-form-urlencoded; charset=UTF-8"));
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $buildQuery);
$retorno = curl_exec($curl);
curl_close($curl);
$xml = simplexml_load_string($retorno);



//VERIFICA SE RETORNOU ERRO
if(isset($xml->error)){
    $retorna = ['erro' => true, 'dados' => $xml];
    header('Content-Type: application/json');
    echo json_encode($retorna);

//INSERIR NO BANCO DE dados
}else{
    //CREDITO   
    if($xml->paymentMethod->type == 1){
        $query = 'INSERT INTO transacoes(idCliente,tipoPagamento,codigoTransacao,status,data) VALUES (:idCliente, :tipoPagamento, :codigoTransacao, :status, :data)';
        $sql = $conn->prepare($query);
    
    //BOLETO
    }elseif($xml->paymentMethod->type == 2) {
    $query = 'INSERT INTO transacoes(idCliente,tipoPagamento,codigoTransacao,status,linkBoleto,data) VALUES (:idCliente, :tipoPagamento, :codigoTransacao, :status, :linkBoleto, :data)';
    $sql = $conn->prepare($query);
    $sql->bindParam(':linkBoleto', $xml->paymentLink); 

    //DEBITO ONLINE    
    }elseif($xml->paymentMethod->type == 3) {
        $query = 'INSERT INTO transacoes(idCliente,tipoPagamento,codigoTransacao,status,linkDebito,data) VALUES (:idCliente, :tipoPagamento, :codigoTransacao, :status, :linkDebito, :data)';
        $sql = $conn->prepare($query);
        $sql->bindParam(':linkDebito', $xml->paymentLink);
    }
     
    //EXECUTA INSERÇÃO
    $sql->bindValue(':idCliente', $_SESSION['user']['id']);
    $sql->bindValue(':tipoPagamento', $xml->paymentMethod->type);
    $sql->bindValue(':codigoTransacao', $xml->code);
    $sql->bindValue(':status', $xml->status);
    $sql->bindValue(':data', $xml->date);
    $sql->execute();

    //RETORNA
    $retorna = ['erro' => false, 'dados' => $xml];
    header('Content-Type: application/json');
    echo json_encode($retorna);

}


