var amount = "600.00";
sessionId();

function sessionId() {

  //Endereco padrão do projeto
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

function listarMeiosPag(){
    PagSeguroDirectPayment.getPaymentMethods({
        amount: amount,
        success: function (retorno) {

            console.log(retorno);
        $('.meioPag').append('<h1>Cartao de Credito</h1>');
        $.each(retorno.PaymentMethod.CREDIT_CARD.option, function(i, obj){
            $('.meioPag').append("<div class='bandPag'><img src='https://stc.pagseguro.uol.com.br/"+obj.imagens.MEDIUM.path+"'</div>");
        });
        $('.meioPag').append('<h1>BOLETO</h1>');
        $('.meioPag').append("<div class='bandPag'><img src='https://stc.pagseguro.uol.com.br/"+retorno.PaymentMethod.BOLETO.options.BOLETO.imagens.path+"'</div>");

    },
    error: function(retorno) {
        
   
    },
    complete: function(retorno) {
        
    }
});
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