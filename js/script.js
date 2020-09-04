
//COLAPSE DADOS COMPRA
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

//BUSCA PRODUTO
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


//TABS ADMIN
var trigger = Array.from(document.querySelectorAll('.title'));
trigger.forEach(function(el,index,all){
    el.addEventListener('click',function(){
        let container=document.querySelector('.conteudo');
        let content = Array.from(document.querySelectorAll('.conteudo .text'));
        content.forEach(function(el){
            el.style.display="none";
        });
        content[index].style.display="block";
        
    });
});
$(document).ready(function() {
$('.PrimeiraOpcao').trigger("click");
});




