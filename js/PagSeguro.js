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

$('#numCartao').on('keyup', function () {
    var numCartao = $(this).val();
    var lenghtNum = numCartao.length;
    if(lenghtNum == 6){
        PagSeguroDirectPayment.getBrand({
            cardBin: numCartao,
            success: function(retorno) {
              var imgBand = retorno.brand.name;
              recupParcelas(imgBand);
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

function recupParcelas(imgBand){
    PagSeguroDirectPayment.getInstallments({
        amount: amount,
        maxInstallmentNoInterest: 2,
        brand: imgBand,
        success: function(retorno){
            console.log(retorno);
            $.each(retorno.installments, function (ia, obja) {
                $.each(obja,function(ib,objb) {
                    var valorParcela = objb.installmentAmount.toFixed(2).replace(".",",");
                   $('#qntParcelas').show().append("<option value='"+objb.quantity+"'data-parcelas='"+objb.installmentAmount+"'>"+objb.quantity+"Parcelas de R$"+valorParcela+"</option>");
                });
            });
       },
        error: function(retorno) {
            
       },
        complete: function(retorno){
            // Callback para todas chamadas.
       }
});
}

$('#qntParcelas').change(function () {
    $('#valorParcelas').val($('#qntParcelas').find(':selected').attr('data-parcelas'));
});



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