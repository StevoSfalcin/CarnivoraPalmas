var amount = "600.00";
sessionId();
//ID DA SESSAO
function sessionId() {
  $.ajax({
      url:"https://carnivorapalmas.com/sessaoPag.php",
      type: 'POST',
      dataType: 'json',
      success: function (retorno) {
         PagSeguroDirectPayment.setSessionId(retorno.id); 
         console.log(retorno);      
      },
      complete: function (retorno) {
        listarMeiosPag();       
      }
  });
}
//FORMA DE PAGAMENTO
$("#tabsPag li").click(function() {
  var valor = $(this).attr('id');
  console.log(valor);
});
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
            $('#nomeBanco').show().append("<option value='"+obj.name+"'>"+obj.displayName+"</option>");
            $('.nomeBanco').hide();
        });
    },
    error: function(retorno) {     
    },
    complete: function(retorno) { 
        senderHash();   
    }
    });
}
//OBTER BANDEIRA DO CARTAO
$('#numCartao').on('keyup', function () {
    var numCartao = $(this).val();
    var lenghtNum = numCartao.length;
    if(lenghtNum >= 6){
        PagSeguroDirectPayment.getBrand({
            cardBin: numCartao,
            success: function(retorno) {
              var imgBand = retorno.brand.name;
              obterParcelas(imgBand);
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
//OBTER PARCELAS
function obterParcelas(imgBand){
    PagSeguroDirectPayment.getInstallments({
        amount: amount,
        maxInstallmentNoInterest: 2,
        brand: imgBand,
        success: function(retorno){
            console.log(retorno);
            $.each(retorno.installments, function (ia, obja) {
                $.each(obja,function(ib,objb) {
                    var valorParcela = objb.installmentAmount.toFixed(2).replace(".", ",");
                    $('#qntParcelas').show().append("<option value='" + objb.quantity + "' data-parcelas='" + objb.installmentAmount + "'>" + objb.quantity + " parcelas de R$ " + valorParcela + "</option>");
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

//Enviar o valor parcela para o formulário
$('#qntParcelas').change(function () {
    $('#valorParcelas').val($('#qntParcelas').find(':selected').attr('data-parcelas'));
});

$("#formPagamento").on("submit", function (event) {
    event.preventDefault();
    PagSeguroDirectPayment.createCardToken({
        cardNumber: $('#numCartao').val(),
        brand: $('#bandeiraCartao').val(), 
        cvv: $('#cvvCartao').val(), 
        expirationMonth: $('#mesValidade').val(),
        expirationYear: $('#anoValidade').val(),
        success: function (retorno) {
            $('#tokenCartao').val(retorno.card.token);
        },
        error: function (retorno) {
            // Callback para chamadas que falharam.
        },
        complete: function (retorno) {
            // Callback para todas chamadas.
            hashCartao();
        }
    });
})

function hashCartao(){
    PagSeguroDirectPayment.onSenderHashReady(function (retorno) {
        if (retorno.status == 'error') {
            return false;
        } else {
            $("#hashCartao").val(retorno.senderHash);
            var dados = $("#formPagamento").serialize();
            $.ajax({
                url:"procPag.php",
                method:"POST",
                data:dados,
                dataType:"json",
                success: function(retorno){
                    console.log("sucesso"+JSON.stringify(retorno));
                },
                error:function(retorno){
                    console.log(retorno)
                }
            })
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