<?php

$LocalHost = false;

if($LocalHost == true){
define('URL','http://portfolio/Ecommerce');
define('HOST','localhost');
define('DBNAME','carnivorapalmas');
define('USER','root');
define('PASSWORD','');
}else{
define('URL','https://carnivorapalmas.com');
define('HOST','mysql669.umbler.com');
define('DBNAME','carnivorapalmas');
define('USER','stevo');
define('PASSWORD','Safira357');
define("EMAIL_LOJA", "carnivorapalmas@gmail.com");
define("MOEDA_PAGAMENTO", "BRL");
define("EMAIL_PAGSEGURO", "stevosfalcin@gmail.com");
define("TOKEN_PAGSEGURO", "E8B0DF25317C41FFAEB7ED2860DB32E3");
define("URL_PAGSEGURO", "https://ws.sandbox.pagseguro.uol.com.br/v2/");
define("URL_NOTIFICACAO", "https://sualoja.com.br/notifica.html");
define("SCRIPT_PAGSEGURO", "https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js");
}



