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
      },
      complete: function (retorno) {
        
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

var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var conteudoAba = this.nextElementSibling;
    if (conteudoAba.style.display === "block") {
      conteudoAba.style.display = "none";
    } else {
      conteudoAba.style.display = "block";
    }
  });
}


$.ajaxSetup ({
  cache: false
 });
 $(document).ready(function(){
     $('.busca input').keyup(function(){
     var words = $('#pesquisa').val();

     if (words != '') {

         $.ajax({
             'url' : 'search.php',
             'dataType': "json",
             'method' : 'POST',
             'data' : {numero1 : words}
         })
         .done(function(response){
             $('.results').html('');
             
             $.each(response, function(key, val){
                 $('.results').append('<h2>' + val + '</h2>');
             });
         })
         .fail(function(){
             $('.results').html('');
         });
         

     } else {
         $('.results').html('');
     }
 });
});




