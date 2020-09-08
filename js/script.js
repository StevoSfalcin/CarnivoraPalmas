
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
    $(document).ready(function(){
        $('.frete input[name="cep"]').keyup(function(){
            var cep = $('input[name="cep"]').val();
            var dados = [];
            var metodoPac = 04510;
            var metodoSedex = 04014;
            dados[0] = $('input[name="cep"]').val();
            dados[1] = $('input[name="volumeTotal"]').val();
            dados[2] = $('input[name="pesoTotal"]').val();

            if(cep.length >= 8){
                //PAC
                $.ajax({
                    'url' : 'calculoFrete.php',
                    'method' : 'POST',
                    'dataType' : 'json',
                    'data' : {'dados' : dados,
                              'metodo':metodoPac},               
                })
                .done(function(retorno){
                    console.log(retorno.Valor);
                        $('.resultadoPac').html('');
                        $('.resultadoPac').show().append("<h2>R$"+retorno.Valor+"</h2>")                       
                })
                .fail(function(){
                    console.log('falhou')
                })
                //SEDEX
                $.ajax({
                    'url' : 'calculoFrete.php',
                    'method' : 'POST',
                    'dataType' : 'json',
                    'data' : {'dados' : dados,
                              'metodo':metodoSedex},               
                })
                .done(function(retorno){
                    console.log(retorno.Valor);
                        $('.resultadoSedex').html('');
                        $('.resultadoSedex').show().append("<h2>R$"+retorno.Valor+"</h2>")
                        
                });
                       
            }

        });
    })

    //BUSCA PRODUTO
    $.ajaxSetup ({
    cache: false
    });
    $(document).ready(function(){
        $('.busca input').keyup(function(){
            var words = $('input').val();
            if (words != '') {
                $.ajax({
                    'url' : 'buscaProduto.php',
                    'method' : 'POST',
                    'dataType' : 'json',
                    'data' : {palavra : words}
                })
                .done(function(retorno){
                    $('.results').html('');	                                       
                    if(retorno.length != 0){                                 
                        $('.results').append("<h2><a href='https://carnivorapalmas.com/produto/codigo/"+retorno[1]+"'>"+retorno[0]+"</a></h2>"); 
                        if(retorno[2] !== undefined){
                            $('.results').append("<h2><a href='https://carnivorapalmas.com/produto/codigo/"+retorno[3]+"'>"+retorno[2]+"</a></h2>");
                        }                                                            
                    }               
                })
                .fail(function(){
                    $('.results').html('');
                    console.log('semRetorno');
                });			
            }else{

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




