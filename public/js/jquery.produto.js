$(function(){
		$(".actionCategoriaGerenciar").on("click",function(){
			$(".gerCategorias").slideToggle();
		});
		$(".actionSubcategoriaGerenciar").on("click",function(){
			$(".gerSubCategorias").slideToggle();
		});
		$('#criaCategorias,#criaSubCategorias,#addProdutos,#myModalItens,#myModalTable').on('hidden', function () {
			location.reload();
		});
		$("#actionAddCat").on("click",function(){
			var eventCategoria = $("#criaCategorias .tituloCat").val();
			$.ajax({
				url: basePatch+"/administrador/categorias/insert",
				type: "post",
				async:false,
				data: {eventCategoria:eventCategoria},
				success: function(data) {
					if(data == "")
						{
						$("#criaCategorias #message").removeClass('alert-error');
						$("#criaCategorias #message").addClass("alert-success");
						$("#actionAddCat").fadeOut();
						$("#criaCategorias #message").html("- Categoria adicionada com sucesso.");
						
						}
					else
						{
						$("#criaCategorias #message").addClass("alert-error");
						$("#criaCategorias #message").html(data);
						}
					$("#criaCategorias #message").fadeIn();
				}
			});
			
			return false;
		});
		
		$("#actionAddSubCat").on("click",function(){
			var eventCategoria = $("#criaSubCategorias .categoriaId").val();
			var eventSubcategoria = $("#criaSubCategorias .tituloSubCat").val();
			$.ajax({
				url: basePatch+"/administrador/categorias/insertSub",
				type: "post",
				async:false,
				data: {eventCategoria:eventCategoria,eventSubcategoria:eventSubcategoria},
				success: function(data) {
					if(data == "")
					{
					$("#criaSubCategorias #message").removeClass('alert-error')
					$("#criaSubCategorias #message").addClass("alert-success");
					$("#actionAddSubCat").fadeOut();
					$("#criaSubCategorias #message").html("- SubCategoria adicionada com sucesso.");
					
					}
				else
					{
					$("#criaSubCategorias #message").addClass("alert-error");
					$("#criaSubCategorias #message").html(data);
					}
					$("#criaSubCategorias #message").fadeIn();
				}
			});
			return false;
		});
		
		$("select[name=inputCategoria]").on("change", function(){
			var value = $(this).val();
			
			$.ajax({
				type	:	"post",
				url		:	basePatch+"/administrador/produto/subCategoriaByCategoria",
				data	:	{valor:value},
				beforeSend: function(){
					$('select[name=inputSubCategoria]').empty();
					$('select[name=inputSubCategoria]').append("<option>Caregando.....</option>");
				},
				success	:	function(data){
					$('select[name=inputSubCategoria]').empty();
					$('select[name=inputSubCategoria]').append(data);
				},
				error	:	function(){
					
				}
			});
		});		
		
		$(".input-medium").mask("9?99");
		$("#inputPeso").mask("9.999");
		$('#inputValor').priceFormat({
		    prefix: false,
		    centsSeparator: ',',
		    thousandsSeparator: '.'
		});
		
		$("#actionAddReferencia").on("click", function(){	
			var eventReferencia = $("#criaReferencias .tituloReferencia").val();
			$.ajax({
				url: basePatch+"/administrador/referencia/insert",
				type: "post",
				async:false,
				data: {eventReferencia:eventReferencia},
				success: function(data) {
					if(data == "")
						{
						$("#criaReferencias #message").removeClass('alert-error');
						$("#criaReferencias #message").addClass("alert-success");
						$("#actionAddCat").fadeOut();
						$("#criaReferencias #message").html("- Referencia adicionada com sucesso.");
						
						}
					else
						{
						$("#criaReferencias #message").addClass("alert-error");
						$("#criaReferencias #message").html(data);
						}
					$("#criaReferencias #message").fadeIn();
				}
			});
			return false;
		});
		
		$(".my-accordion-toggle").on('click', function(){
			var identif = $(this).attr('href');
			$(identif).slideToggle();
			return false;
		});
		
		$("#addItems").on("click", function(){
			var item = $("input[name=item]").val();
			$.ajax({
				url: basePatch+"/administrador/produto/nutricional/adicionarItem",
				type: "POST",
				data: {nome:item},
				success: function(data){					
					if(data == "")
					{
						$("#mensagensItens").removeClass('alert-error');
						$("#mensagensItens").addClass('alert-success');
						$("#mensagensItens").html("Item adicionado com sucesso.");
						$("#addItems").fadeOut();
					}
					else
					{
						$("#mensagensItens").removeClass('alert-success');
						$("#mensagensItens").addClass('alert-error');
						$("#mensagensItens").html(data);
					}
					$("#mensagensItens").fadeIn();
				}
			});
		});
		
		$("#idproduto").on("change", function(){			
			var valor = $(this).val();			
			var texto = $("#idproduto option[value="+valor+"]").text();			
			if(valor != ""){
				$("#produtos[name=produtos]").each(function(e){
					$("#produtos[name=produtos]").val(texto);
				});
			}else{
				$("#produtos[name=produtos]").each(function(e){
					$("#produtos[name=produtos]").val("");
				});
			}
		});
		
		$("#addLinha").on("click", function(){
			var produto = $("select[name=idproduto]").val();
			var qtd;
			
			$("#formNutricional div#line-form").each(function(e){				
				qtd = e;
			});			
			qtd = parseInt(qtd) + 1;
			
			if(produto) {
				$.ajax({
					url: basePatch+"/administrador/produto/nutricional/inserirlinha",
					type: "post",
					data:{produto:produto},
					success: function(data){
						
						$('<div>',{
							id:"line-form",
							class:"row line"+qtd+"",
							html:data
						}).appendTo("#formNutricional");						
						
					}
				});
			} else {
				alert("Por favor, escolha um produto.");
			}
		});
		
		$("#addTableNutricional").on("click", function(){
			/*var inputs = new Array();
			$('#formNutricional input').each(function(){
				inputs.push($(this).val());
			});*/
			
			var dados = new Array();			
			$("#formNutricional div#line-form").each(function(e){				
				dados[e] = [];
				dados[e].push($("#formNutricional div#line-form #idproduto").val());
				dados[e].push($("#formNutricional div.line"+e+" #idnutricionalNomes").val());
				dados[e].push($("#formNutricional div.line"+e+" #quantidade").val());
				dados[e].push($("#formNutricional div.line"+e+" #vd").val());		
			});
			//console.log( dados );
			
			$.ajax({
				url: basePatch+"/administrador/produto/nutricional/criarTabelaNutricional",
				type: "POST",
				data: {matriz:dados},
				success: function(data){					
					console.log(data);
					if(data == "")
					{
						$("#mensagensTables").removeClass('alert-error');
						$("#mensagensTables").addClass('alert-success');
						$("#mensagensTables").html("Adicionado com sucesso.");
						$("#addTableNutricional, #addLinha").fadeOut();
					}
					else
					{
						$("#mensagensTables").removeClass('alert-success');
						$("#mensagensTables").addClass('alert-error');
						$("#mensagensTables").html(data);
					}
					$("#mensagensTables").fadeIn();
				}
			});
			
		});
		
		$("form#formCrop").on("click", function(){
			if (parseInt($('#w').val())) return true;
			alert('Por favor, escolha uma região para o recorte e pressione o botão de recorte.');
			return false;
		});
		$('#cropbox').Jcrop({
		    onChange: showCoords,	
	        onSelect: showCoords
	    });
		function showCoords(e) {
			$('#w').val(e.w);
		    $('#h').val(e.h);		    
		    $('#x').val(e.x);
		    $('#y').val(e.y);
		    $('#x2').val(e.x2);
		    $('#y2').val(e.y2);
		}
		
		
		
});