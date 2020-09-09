
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
                var metodoPac = '04510';
                var metodoSedex = '04014';
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
                    beforeSend: function(){
                        $('.resultadoPac').show().html('<img src="img/loadCep.gif" alt="carregando...">"');
                    }         
                    })
                    .done(function(retorno){            
                            $('.resultadoPac').html('');
                            $('.resultadoPac').show().append("<div id='valorPac' value='"+retorno.Valor+"'><h2>R$"+retorno.Valor+"</h2></div>")    
                            $('.resultadoPac').show().append("<h2>Prazo:"+retorno.PrazoEntrega+" Dias Uteis.</h2>")

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
                    beforeSend: function(){
                        $('.resultadoSedex').show().html('<img src="img/loadCep.gif" alt="carregando...">"');
                    }             
                    })
                    .done(function(retorno){            
                            $('.resultadoSedex').html('');
                            $('.resultadoSedex').show().append("<div id='valorSedex' value='"+retorno.Valor+"'><h2>R$"+retorno.Valor+"</h2></div>")
                            $('.resultadoSedex').show().append("<h2>Prazo:"+retorno.PrazoEntrega+" Dias Uteis.</h2>")
                            
                    });
                        
                }

            });

            //VALORES ORÇAMENTO
            $('#pac').click(function(){
                var valorFrete = document.getElementById('valorPac').getAttribute('value');   
                var texto = "<h1>Frete R$"+valorFrete+"</h1>";   
                document.getElementById("orcamentoFrete").innerHTML = texto;

                var valorProdutos = document.getElementById('valorProdutos').getAttribute('value');
                var total = parseFloat(valorProdutos.replace(',','.')) + parseFloat(valorFrete.replace(',','.'));
                var texto = "<h1>Total:R$"+total.toFixed(2).replace('.',',')+"</h1>";   
                document.getElementById("orcamentoTotal").innerHTML = texto; 
      
            })
            $('#sedex').click(function(){
                var valorFrete = document.getElementById('valorSedex').getAttribute('value');  
                var texto = "<h1>Frete R$"+valorFrete+"</h1>";        
                document.getElementById("orcamentoFrete").innerHTML = texto;
                
                var valorProdutos = document.getElementById('valorProdutos').getAttribute('value');
                var total = parseFloat(valorProdutos.replace(',','.')) + parseFloat(valorFrete.replace(',','.'));
                var texto = "<h1>Total:R$"+total.toFixed(2).replace('.',',')+"</h1>";   
                document.getElementById("orcamentoTotal").innerHTML = texto;
            })
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




