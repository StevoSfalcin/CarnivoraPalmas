<?php

if(isset($_POST['dados'])){

//PAC 04510
//SEDEX 04014

$valores['nCdEmpresa'] = "";
$valores['sDsSenha'] = "";
$valores['sCepOrigem'] = '77023038';

$valores['sCepDestino'] = $_POST['dados'][0];
$valores['nVlComprimento'] = $_POST['dados'][1];
$valores['nVlAltura'] = $_POST['dados'][1];
$valores['nVlLargura'] = $_POST['dados'][1];
$valores['nVlPeso'] = $_POST['dados'][2];

$valores['nVlValorDeclarado'] = '0';
$valores['nCdServico'] = $_POST['metodo'];
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
