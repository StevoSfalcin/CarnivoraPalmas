var amount = "600.00";
sessionId();
//ID DA SESSAO
function sessionId() {
  $.ajax({
      url:"https://carnivorapalmas.com/pagamento.php",
      type: 'POST',
      dataType: 'json',
      success: function (retorno) {
         PagSeguroDirectPayment.setSessionId(retorno.id);       
      },
      complete: function (retorno) {
        listarMeiosPag();       
      }
  });
}
//LISTAR MEIOS PAGAMENTOS
function listarMeiosPag(){
    PagSeguroDirectPayment.getPaymentMethods({
        amount: amount,
        success: function (retorno) {
        //CREDITO
        $('.meioPag').append('<h2>CREDITO</h2>');
        $.each(retorno.paymentMethods.CREDIT_CARD.options,function(i,obj){
            $('.meioPag').append("<div class='bandPag'><img src='https://stc.pagseguro.uol.com.br"+obj.images.SMALL.path+"' alt='BanderiaCartao'></div>");
        });
        //BOLETO
        $('.meioPag').append('<h1>BOLETO</h1>');
        $('.meioPag').append("<div class='bandPag'><img src='https://stc.pagseguro.uol.com.br"+retorno.paymentMethods.BOLETO.options.BOLETO.images.SMALL.path+"'></div>");
        
        //DEBITO
        $('.meioPag').append('<h1>DEBITO</h2>');
        $.each(retorno.paymentMethods.ONLINE_DEBIT.options,function(i, obj){
            $('.meioPag').append("<div class='bandPag'><img src='https://stc.pagseguro.uol.com.br"+obj.images.MEDIUM.path+"'></div>");
        });
    },
    error: function(retorno) {     
    },
    complete: function(retorno) { 
        senderHash();   
    }
    });
}
//INDENTIFICADOR COM BASE NOS DADOS DO CLIENTE
function senderHash(){
    PagSeguroDirectPayment.onSenderHashReady(function(retorno){
        if(retorno.status == 'error') {       
            return false;
        }
        var hash = retorno.senderHash; 
    });
}
//OBTER BANDEIRA DO CARTAO
function bandCartao(){
    $('#numCartao').on('keyup', function () {
        var numCartao = $(this).val();
        var lenghtNum = numCartao.length;
        if(lenghtNum == 6){
            PagSeguroDirectPayment.getBrand({
                cardBin: numCartao,
                success: function(retorno) {
                  console.log(retorno);
                },
                error: function(retorno) {
                  //tratamento do erro
                },
                complete: function(retorno) {
                  //tratamento comum para todas chamadas
                }
            });
        }
    })
}




$(document).ready(function(){
    //MENU MOBILE//
    $(".button-collapse").sideNav();
    //MODAL
    $(".modal").modal();
    //ESCONDER MENU AO CLICAR
    $(".hide-menu").click(function(){
        $(".button-collapse").sideNav("hide");
    });
});