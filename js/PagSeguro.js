sessionId();
var valorProdutos = document.getElementById('valorProdutos').getAttribute('value').replace(',','.');
var amount = valorProdutos;

$('#pac').click(function(){
    var valorFrete = document.getElementById('valorPac').getAttribute('value');
    total = parseFloat(valorProdutos.replace(',','.')) + parseFloat(valorFrete.replace(',','.'));
    amount = total;
    obterParcelas('visa');
});
$('#sedex').click(function(){
    var valorFrete = document.getElementById('valorSedex').getAttribute('value');
    total = parseFloat(valorProdutos.replace(',','.')) + parseFloat(valorFrete.replace(',','.'));
    amount = total;
    obterParcelas('visa');   
});
//ID DA SESSAO
function sessionId() {
  $.ajax({
      url:"https://carnivorapalmas.com/ajax/sessaoPag.php",
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
//TABS FORMA DE PAGAMENTO
$("#tabsPag li").click(function() {
  var valor = $(this).attr('id');
  $('#paymentMethod').val(valor);
  if(valor == 'boleto'){
  document.getElementById('selecFormaPag').innerHTML = '<h1>Pagamento Via Boleto Selecionado</h1>';
  console.log('boleto')
 }
});

//LISTAR MEIOS PAGAMENTOS
function listarMeiosPag() {
    PagSeguroDirectPayment.getPaymentMethods({
        amount: amount,
        success: function (retorno) {
            //CREDITO
            $.each(retorno.paymentMethods.CREDIT_CARD.options, function (i, obj) {
                $('.meioPagCred').append("<span class='img-band'><img src='https://stc.pagseguro.uol.com.br" + obj.images.SMALL.path + "'></span>");
            });
            //DEBITO
            $.each(retorno.paymentMethods.ONLINE_DEBIT.options, function (i, obj) {
                $('.meioPagDeb').append("<img src='https://stc.pagseguro.uol.com.br" + obj.images.SMALL.path + "'>");
                $('#bankName').show().append("<option value='" + obj.name + "'>" + obj.displayName + "</option>");            
            });
        },
        error: function (retorno) {
         
        },
        complete: function (retorno) {
            // Callback para todas chamadas.
            //recupTokemCartao();
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
              var bandeira = retorno.brand.name;
              obterParcelas(bandeira);
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
function obterParcelas(bandeira){
    var noIntInstalQuantity = $('#noIntInstalQuantity').val();
    $('#qntParcelas').html('<option value="">Selecione</option>');
    PagSeguroDirectPayment.getInstallments({
        amount: amount,    
        maxInstallmentNoInterest: noIntInstalQuantity,
        brand: bandeira,
        success: function(retorno){
            $.each(retorno.installments, function (ia, obja) {
                $.each(obja, function (ib, objb) {
                    var valorParcela = objb.installmentAmount.toFixed(2).replace(".", ",");
                    var valorParcelaDouble = objb.installmentAmount.toFixed(2);
                    $('#qntParcelas').show().append("<option value='" + objb.quantity + "' data-parcelas='" + valorParcelaDouble + "'>" + objb.quantity + " parcelas de R$ " + valorParcela + "</option>");
                });
            });
        },
        error: function (retorno) {
            // callback para chamadas que falharam.
        },
        complete: function (retorno) {
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
                url:"ajax/processaPedido.php",
                method:"POST",
                data:dados,
                dataType:"json",
                beforeSend: function(){
                    document.getElementById('btnComprar').innerHTML = '<img src="img/loadCep.gif" alt="carregando...">"';                 
                },
                success: function(retorno){
                    if(retorno.erro == 'false'){
                        $('#modalPagamento').modal('open');
                        $('.dadosCompra').append("<h1>TRANSAÇAO REALIZADA COM SUCESSO</h1>");
                        $('.dadosCompra').append("<h2>Codigo da Transação: "+retorno.dados.code+"</h2>");
                        $('.dadosCompra').append("<h2>Link de Pagamento: "+retorno.dados.paymentLink+"</h2>");
                    }
                },
                error:function(retorno){
                    $('#modalPagamento').modal('open');
                        $('.dadosCompra').append("<h1>TRANSAÇAO NAO REALIZADA</h1>");
                        $('.dadosCompra').append("<h2>Verifique todos os dados e tente novamente</h2>");
                }
            })
        }
    });
}

