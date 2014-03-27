$(function(){
		//Ações para inserção carrinho de compras
	/*
	$(document).scroll(function() {
			if($(this).scrollTop() >= 71)
				{
					$( "#header" ).animate({
					    height: "30px"
					  }, 100, function() {
					    // Animation complete.
					  });
				}
		});
		*/
	$(".ButtonCestaVazia").on("click", function(){	$("#box_compras").slideUp();
	return false;
	});
	
		$(".actionAddCarrinho").on("click", function(){	
			var actionIdProduto = $(this).attr("rev");	
			var actionQuant = $(this).parents("#comprar_detalhe, .box_vejaTambem, .lista_categoria_produtos").find('input').val();
			var element = this;
			$.ajax({
				url: basePatch+"/carrinhoRest",
				beforeSend:function(){
					$(element).mouseenter(function() {
						$(element).css("background-color", "#582700");
						});
					
					$(element).css("background-image","url("+basePatch+"/images/loading.gif)");
					if($(element).attr("rel") != 0)
					{
					$(element).html("Adicionando o <br/>produto ao carrinho..");
					}
					$(element).css("background-color", "#582700");
				},
				complete:function(){
					$(element).css("background-image","url("+basePatch+"/images/btn_ir_cesta.png)");
					if($(element).attr("rel") != 0)
					{
					$(element).html("Adicionar ao<br/> carrinho de compras");
					}
					$(element).mouseenter(function() {
						$(element).css("background-color", "#351903");
						});
					$(element).mouseleave(function() {
						$(element).css("background-color", "#582700");
					  });
					},
				type: "post",
				data: {actionAddCart:actionIdProduto,actionQuant:actionQuant},
				success: function(data) {
					if(data.listaProdutos.length >= 1)	
					{
						if($("#box_compras #DescricaoPrecoQuatidade li").size() == 0)
							{
								$("#box_compras").html(
								'<ul class="header_conteudo_compra">\
										<li class="header_tt_box_produto">\
										<p class="header_txt_produto_compra">Produto</p>\
									</li>\
									<li class="header_tt_box_preco">\
										<p class="header_txt_preco_compra">Preços (R$)</p>\
									</li>\
									<li class="header_tt_box_Qtd">\
										<p class="header_txt_Qtd_compra">Qtde.</p>\
									</li>\
								</ul>\
								<ul id="DescricaoPrecoQuatidade">\
								</ul>\
								<ul id="buttonCarrinho">\
									<li class="ButtonCarrinhoDeCompra"><a href="'+basePatch+"/carrinho-compra"+'" title="Carrinho de compras">Carrinho de compras</a></li>\
									<li class="ButtonFinalizarCompra"><a href="'+basePatch+"/finaliza-compra"+'" title="Finalizar Compra">Finalizar Compra</a></li>\
								</ul>'
								)
							}
						$("#box_compras #DescricaoPrecoQuatidade").html("");
						$("#Box_Visor_Qtd .Visor_Qtd").html(data.listaProdutos.length);	
						if($("#Box_Visor_Qtd").css("display") == "none")
						{
							$("#Box_Visor_Qtd").fadeIn();
						}
						$(".valor_total").fadeOut("fast").html(data.valorTotal).fadeIn("fast");
						$.each(data.listaProdutos, function(i, item) { 
							$("#box_compras #DescricaoPrecoQuatidade").append(
									$('<li>').attr("class","BoxProdutosCesta").append(
											$('<div>').attr("id","BoxDescricaoFoto").append(
													
													$('<span>').attr("class","imagem_produto_cesta").append(
															$('<a>').attr("href",item.urlProduto).append(
																	$('<img>').attr("src",basePatch+"/images/produtos/thumb/"+item.foto)	
															)
													)
											).append($('<a>').attr("href",item.urlProduto).append(
													$('<p>').append(item.titulo).attr("class","descricao_produto_cesta")
											))
									).append(
							    		$('<div>').attr("id","BoxDescricaoPreco").append(
							    			
							    		).append(
												$('<p>').append(item.valor).attr("class","descricao_preco_cesta")
										)).append(
											$('<div>').attr("id","BoxDescricaoQuatidade").append(
													$('<p>').append(item.quantidade).attr("class","descricao_Qtd_cesta")
											)
								   )); 
						});
					}	
				},
				error: function(){}
			});
			return false;
		});
		$(".actionRemoveCart").on("click", function(){
			var actionIdProduto = $(this).attr("rev");
			$.ajax({
				url: basePatch+"/carrinho/delete",
				type: "post",
				data: {actionAddCart:actionIdProduto},
				success: function(data) {
					location.reload();
				},
				error: function(){}
					});	
			return false;
		});
		$(".btn_comprar_detalhe").on("click", function(){	
			var actionIdProduto = $(this).attr("rev");	
			var actionQuant = $(this).parents("#comprar_detalhe, .box_vejaTambem, .lista_categoria_produtos").find('input').val();
			
			$.ajax({
				url: basePatch+"/carrinho/insert",
				type: "post",
				async:false,
				data: {actionAddCart:actionIdProduto,actionQuant:actionQuant},
				success: function(data) {
					$.ajax({
						url: basePatch+"/carrinho/list",
						type: "post",
						async:false,
						success: function(data) {
							
								$("#box_compras").html(data);
								var quantidadeItens = $("#DescricaoPrecoQuatidade li").size();
									$("#Box_Visor_Qtd .Visor_Qtd").html(quantidadeItens);
									if($("#Box_Visor_Qtd").css("display") == "none")
										{
											$("#Box_Visor_Qtd").fadeIn();
										}
									$(".valor_total").slideUp("fast",function(){
										$.ajax({
											url: basePatch+"/carrinho/detalheCarrinho",
											type: "post",
											async:false,
											success: function(data) {
													$(".valor_total").html(data);
													$(".valor_total").slideDown("fast");
												}
											})
									})

									return true;
								},
						error: function(){}
							});
				},
				error: function(){}
					});	
		});
		$(".actionOpenCarrinho").on("click", function(){	
				$("#box_compras").slideToggle("fast", function () {});
				return false;
		});
		$("#header").on( "mouseleave", function() {
			$("#box_compras").slideUp("fast", function () {}); 
		});
		$("#box_compras").on("click",".actionCloseCarrinho", function(){	
				$("#box_compras").slideToggle("fast", function () {});
			return false;
		});
		$(".qtMais_PC, .qtMais").on("click",function(){
			var id = $(this).parents("#quantidade_produto_categoria, .qtd_produto").find('input').attr("id").split("produto_")[1];
			var valor = $(this).parents("#quantidade_produto_categoria, .qtd_produto").find('input').val();
			var element = $(this);
			if(id == null)
				{
					var id = $(this).parents(".box_vejaTambem").find('input').attr("id").split("relacionadoProd_")[1];
				}
			$.getJSON(basePatch+"/api/Estoque/"+id, function(data){
				if($.isNumeric(valor) && data.num >= (parseInt(valor)+1)) 
					{
					if(data.num == (parseInt(valor)+1))
						{
							$(element).parents("#quantidade_produto_categoria,#comprar_detalhe,.box_vejaTambem").find('.smsEstoque').html("Limite máximo do estoque!");
							$(element).parents("#quantidade_produto_categoria,#comprar_detalhe,.box_vejaTambem").find('.smsEstoque').fadeIn("slow");
							$(".modelNotificacao").attr("id","atencao_erro");
							$(".atencaoErro").html("Uma informação para você");
							$(".erro").html("Nosso limite de estoque foi atingido.");
							$(".tentarNovamente").html("Continuar");
							$("#form_erro").fadeIn();
							/*
							$("#comprar_detalhe .smsEstoque").html("Limite maximo estoque!");
							$("#comprar_detalhe .smsEstoque").fadeIn("slow");
							*/
						}
					$(element).parents("#quantidade_produto_categoria, .qtd_produto").find('input').val(parseInt(valor)+1);
					}
            });
			return false;
		});
		$(".qtMenos_PC, .qtMenos").on("click",function(){
			var id = $(this).parents("#quantidade_produto_categoria, .qtd_produto").find('input').attr("id").split("produto_")[1];
			var valor = $(this).parents("#quantidade_produto_categoria, .qtd_produto").find('input').val();
			var element = $(this);	
			if(id == null)
			{
				var id = $(this).parents(".box_vejaTambem").find('input').attr("id").split("relacionadoProd_")[1];
			}
			$.getJSON(basePatch+"/api/Estoque/"+id, function(data){
				if($.isNumeric(valor) && valor > 1 && data.num >= (parseInt(valor)-1)) 
				{
				$(element).parents("#quantidade_produto_categoria,#comprar_detalhe,.box_vejaTambem").find('.smsEstoque').fadeOut("slow");
				$(element).parents("#quantidade_produto_categoria, .qtd_produto").find('input').val(parseInt(valor)-1);
				}
			});
			return false;
		});
		$(".MoreItens").on("click",function(){
			
			var referenceId = $(this).parents(".ListaDaCesta").find(".actionCode").val();
			var valor = $(this).parents("#quantidade_produto_categoria, .qtd_produto, #ProductsFromBasket").find('input').val();
			var element = this;
			$.ajax({
				url: basePatch+"/estoque",
				type: "post",
				async:false,
				data: {actionId:referenceId},
				success: function(data) {
			if($.isNumeric(valor) && data >= (parseInt(valor))) 
				{
					if(data == (parseInt(valor)))
					{
						$(".modelNotificacao").attr("id","atencao_erro");
						$(".atencaoErro").html("Uma informação para você");
						$(".erro").html("Nosso limite de estoque foi atingido.");
						$(".tentarNovamente").html("Continuar");
						
						$("#form_erro").fadeIn();
					}
					else
						{
							var UpdateValor = parseInt(valor)+1;
							$(this).parents("#quantidade_produto_categoria, .qtd_produto, #ProductsFromBasket").find('input').val(UpdateValor);
							$.ajax({
								url: basePatch+"/carrinho/insert",
								type: "post",
								beforeSend:function(){
									$(element).parents(".ListaDaCesta").append('<div class="backloading" style="width:998px;height: 75px;position:absolute;background: url('+basePatch+'/images/fundo_form.png);color:#FFFFFF;padding-top:50px;display:none"><center><b>Calculando nova quantidade de produto.</b></center></div>');
									$(element).parents(".ListaDaCesta").find(".backloading").fadeIn();
								},
								complete:function(){},
								data: {actionAddCart:referenceId,actionQuant:UpdateValor},
								success: function(data) {
										location.reload();
									},
								error: function(){}
						});
						}
				}
			}});
			return false;
		});
		$(".LessItens").on("click",function(){
			var referenceId = $(this).parents(".ListaDaCesta").find(".actionCode").val();
			var valor = $(this).parents("#quantidade_produto_categoria, .qtd_produto, #ProductsFromBasket").find('input').val();
			if($.isNumeric(valor) && valor > 1) 
				{
				var UpdateValor = parseInt(valor)-1;
				$(this).parents("#quantidade_produto_categoria, .qtd_produto, #ProductsFromBasket").find('input').val(UpdateValor);
				$.ajax({
					url: basePatch+"/carrinho/insert",
					type: "post",
					async:false,
					data: {actionAddCart:referenceId,actionQuant:UpdateValor},
					success: function(data) {location.reload();},
					error: function(){}
						});
				}
			return false;
		});
		
		$( "#search, #search_footer" ).autocomplete({
    		minLength: 3,
 		    source: basePatch+"/autocomplete/"
    	 });	
		var posicaoAnterior = null;
		var addProdutoSlide = null;
			$(".ContSlider a").hover(function(){
				var leftPosition = $(this).position().left;
				var valor = $(this).attr("rev");
				var titulo =$(this).attr("rel");
				var link = $(this).attr("href");
				addProdutoSlide = $(this).attr("alt");
				
				$("#ProductDescription .txtProductDescription").html(titulo);
				$("#ProductDescription .ValueProductDescription").html(valor);
				$("#ProductDescriptionContent .linkProductDescriptionDesc").attr("href",link);
				if($("#ProductDescription").css("display") == "none")
				{
					$("#ProductDescription").css("left",leftPosition+"px");
					$("#ProductDescription").fadeIn();
				}
				else
					{
						$("#ProductDescription").fadeIn();
						$("#ProductDescription").css("left",leftPosition+"px");
					}
				posicaoAnterior = leftPosition;
			},function(){});
			$(".adicionarAcesta").on("click",function(){
				if(addProdutoSlide != null)
					{
					$.ajax({
						url: basePatch+"/carrinho/insert",
						type: "post",
						async:false,
						data: {actionAddCart:addProdutoSlide,actionQuant:1},
						success: function(data) {
							//location.reload();
						},
						complete:function(){
							$(".modelNotificacao").attr("id","modelSucesso");
							$(".atencaoErro").html("Uma informação para você");
							$(".erro").html("Produto adicionado com sucesso.");
							$(".tentarNovamente").html("Continuar");
							$("#form_erro").fadeIn();
							$(".tentarNovamente").on("click",function(){location.reload();});
						},
						error: function(){}
							});	
					}
				return false;
			});
			$( "#SliderProdutosGeral" ).on( "mouseleave", function() {$("#ProductDescription").fadeOut();})
			$(".maskTop").hover(function(){
				$(".bgPng",this).animate({top: "-96",},100);
			},function(){
				$(".bgPng",this).animate({top: "0",},100);
			});
			
			/*$(".tab").css({display: 'none', marginTop: 0, borderTopWidth: 0});
            $('#tab_1').css('display', 'block');
            $("a", $("#nav")).on("click",function(){
            	var i = $('a', $('#nav')).index(this);
            	var j = i + 1;
            	$("#conteudo_tabs").children(".tab:visible").hide();
            	$("#tab_" + j).show();                
            	$(this).parents('#nav').find('a').removeClass('activetab');
            	$(this).addClass('activetab');
            	return false;
            });*/
			
			$("#conteudo_tabs").hide();
			var duvida;
			var hash = window.location.hash;
			if(hash != '') {				
				duvida = $(hash).html();
				$('#nav li a[href="' + hash + '"]').addClass('activetab');				
			} else {
				duvida = $('#conteudo_tabs div:first-child').html();
				$('#nav li:first-child a').addClass('activetab');
			}
			$('#duvida').append(duvida).find('#duvida').slideDown();
			$("a", $("#nav")).on('click', function(){
				$("#nav li a").removeClass('activetab');
				$(this).addClass('activetab');
				var ancora = $(this).attr('href');
				var nome = ancora.substr(1, ancora.length);
				duvida = $('#conteudo_tabs div[id="' + nome +'"]').html();
				$('#duvida').empty();
				$('#duvida').append(duvida).find('#duvida').slideDown();
				return false;
			});
			
			$('.submenu li a').on('click', function(){
				var hash2 = $(this).attr('dir');
				var ancora = $(this).attr('href');				
				var nome = ancora.substr(38, ancora.length);
				$("#nav li a").removeClass('activetab');				
				$('#nav li a[href="' + hash2 + '"]').addClass('activetab');
				duvida = $('#Institucional #conteudo_tabs div[id="' + nome +'"]').html();				
				$('#duvida').empty();
				$('#duvida').append(duvida).find('#duvida').slideDown();				
			});
			
			
			
});		