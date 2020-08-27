var amount = "600.00";
sessionId();

function sessionId() {

  //Endereco padrão do projeto
  $.ajax({
      url:"https://carnivorapalmas.com/pagamento.php",
      type: 'POST',
      dataType: 'json',
      success: function (retorno) {
         console.log(retorno); 
          PagSeguroDirectPayment.setSessionId(retorno.id);
          getPaymentMethods();
      },
      complete: function (retorno) {
        
      }
  });
}

function listarMeiosPag(){
  PagSeguroDirectPayment.getPaymentMethods({
    amount: 500.00,
    success: function(response) {
        $('.meioPag').append('<div>Cartao Credito</div>');
    },
    error: function(response) {
        // Callback para chamadas que falharam.
    },
    complete: function(response) {
        // Callback para todas chamadas.
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