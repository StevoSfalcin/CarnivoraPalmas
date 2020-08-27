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
          listarMeiosPag();
      },
      complete: function (retorno) {
        
      }
  });
}

function listarMeiosPag(){
    PagSeguroDirectPayment.getPaymentMethods({
        amount: amount,
        success: function (retorno) {

        console.log(retorno);


    },
    error: function(retorno) {
        console.log(retorno)
    },
    complete: function(retorno) {
        console.log(444);
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