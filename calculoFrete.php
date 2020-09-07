<?php

if(isset($_POST['dados'])){

    $busca = 4200;

    echo json_encode($busca);


}










//PAC 04510
//SEDEX 04014
/*
$valores['nCdEmpresa'] = "";
$valores['sDsSenha'] = "";
$valores['sCepOrigem'] = $dados['77023038'];

$valores['sCepDestino'] = $dados['cepOrigem'];
$valores['nVlPeso'] = $dados['peso'];
$valores['nVlComprimento'] = $dados['volume'];
$valores['nVlAltura'] = $dados['volume'];
$valores['nVlLargura'] = $dados['volume'];

$valores['nVlValorDeclarado'] = $dados['precoProduto'];
$valores['nCdServico'] = '04014';
$valores['nCdFormato'] = "1";
$valores['sCdMaoPropria'] = "n";
$valores['sCdAvisoRecebimento'] = "n";
$valores['nVlDiametro'] = "0";
$valores['StrRetorno'] = "xml";

$valores = http_build_query($valores);
$url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx";
$url = $url."?".$valores;
$xml = simplexml_load_file($url);
echo json_encode($xml->cServico);
*/




