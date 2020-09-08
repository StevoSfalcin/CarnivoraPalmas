<?php

if(isset($_POST['dados'])){

    $teste = $_POST['dados'];

    $dados = filter_var($_POST['dados'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $dados = json_decode($dados,true);



//PAC 04510
//SEDEX 04014

$valores['nCdEmpresa'] = "";
$valores['sDsSenha'] = "";
$valores['sCepOrigem'] = '77023038';

$valores['sCepDestino'] = $dados[0];
$valores['nVlComprimento'] = $dados[1];
$valores['nVlAltura'] = $dados[1];
$valores['nVlLargura'] = $dados[1];
$valores['nVlPeso'] = $dados[2];

$valores['nVlValorDeclarado'] = '0';
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


}