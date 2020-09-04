<?php

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

$valores['nCdEmpresa'] = "";
$valores['sDsSenha'] = "";
$valores['sCepOrigem'] = $dados['cepOrigem'];
$valores['sCepDestino'] = $dados['cepOrigem'];
$valores['nVlPeso'] = $dados['peso'];
$valores['nCdFormato'] = "1";
$valores['nVlComprimento'] = $dados['peso'];
$valores['nVlAltura'] = $dados['peso'];
$valores['nVlLargura'] = $dados['peso'];
$valores['sCdMaoPropria'] = "n";
$valores['nVlValorDeclarado'] = "0";
$valores['sCdAvisoRecebimento'] = "n";
$valores['nCdServico'] = $dados['peso'];
$valores['nVlDiametro'] = "0";
$valores['StrRetorno'] = "xml";

$valores = http_build_query($valores);
$url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx";
$url = $url."?".$valores;
$xml = simplexml_load_file($url);
echo json_encode($xml->cServico);



