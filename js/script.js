

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




