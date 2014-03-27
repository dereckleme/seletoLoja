$(document).ready(function(){
	
	$(".ButtonNovoEndereco").on("click",function(){
		idCadastro = null;
		$.ajax({
			url: basePatch+"/actionUser/Usuario/editarCadastroRequest",
			type: "post",
			async:false,
			data: {idCadastro:idCadastro},
			success: function(data) {
				$("#ajaxRecebimento").html(data);
				$("#ajaxRecebimento").fadeIn();
				$(".BoxCEPResquest").mask("99999-999",{completed:function(){
					var cepSet = this.val();
					$.ajax({
						url: basePatch+"/correios/restCep",
						type: "post",
						beforeSend: function(){
							$(".ajaxMsg").html("<img src='"+basePatch+"/images/487.GIF'/> "+" Localizando endereço");
							$(".ajaxMsg").fadeIn();
						   },   
						data: {cep:cepSet},
						success: function(data) {
							$(".ajaxMsg").fadeOut();
							obj = JSON.parse(data);
							if(obj.cep == null)
							{
								$(".BoxEndereco").val("");
								$(".BoxBairro").val("");
								$(".BoxEstado option").removeAttr("selected");
								$(".BoxCidade").html('<option value="">Selecione</option>');
								$(".BoxCidade").attr('disabled', true);
								$(".ajaxMsg").html("Endereço não encontrado.");
								$(".ajaxMsg").fadeIn();
							}
							else
								{
									$(".formActionResquest .BoxEnderecoResquest").val(obj.rua);
									$(".formActionResquest .BoxBairroResquest").val(obj.bairro);
									jQuery.each($(".BoxEstado option"), function(i, val) {
											if(obj.uf == $(val).val())	
												{
													$(val).attr('selected', true);
													$.ajax({
														url: basePatch+"/mapeamento-cidades-estados/cidade/nomeclatura/"+obj.uf,
														async:false,
														success: function(data) {
															$(".BoxCidade").html(data);
															$(".BoxCidade").removeAttr("disabled");
															jQuery.each($(".BoxCidade option"), function(ix, value) {
																	if($(value).text() == obj.cid)
																		{
																			$(value).attr('selected', true);
																		}
																})
														},
														error: function(){}
													});	
												}
														    });
								}
						},
						error: function(){
							$("#form_erro").fadeOut();
							alert("Infelizmente não foi possível se comunicar com o gateway dos correios, por favor preencher seus dados manualmente.");
							}
					});
				}});
				
				$(document).on("click",".EventcadastrarEnderecoResquestNovo",function(){
					var actionCep = $(".formActionResquest .BoxCEPResquest").val();
					var actionRua = $(".formActionResquest .BoxEnderecoResquest").val();
					var actionNumero = $(".formActionResquest .BoxNumeroResquest").val();
					var actionBairro = $(".formActionResquest .BoxBairroResquest").val();
					var actionCidade = $(".formActionResquest .BoxCidade option:selected").val();
					var errorCadastro = 0;
					$(".formActionResquest input,select").each(function( index,element ) {
						if($(element).val() == "")
							{
								errorCadastro = 1;
							}
					});
					if(errorCadastro == 0)
					{
						$.ajax({
							url: basePatch+"/api/Cadastro",
							type: "post",
							async:false,
							data: {actionCep:actionCep,actionRua:actionRua,actionNumero:actionNumero,actionBairro:actionBairro,actionCidade:actionCidade},
							success: function(data) {
								$(".modelNotificacao").attr("id","modelSucesso");
								$(".erro").html("Adicionamos seu endereço alternativo com sucesso:"); // texto de erro
								$(".tentarNovamente").html("Continuar");
								$("#form_erro").fadeIn();
								$(".tentarNovamente").on("click",function(){
									//window.location.href += "#enderecos";
									location.reload();
								})
							}
						});
					}
						else
							{
							var msg = "Alguns campos abaixo estão em branco:\n";
							
							$(".formActionResquest input,select").each(function( index,element ) {
								if($(element).val() == "")
									{
										if(index == 0) msg = msg+"\n- Numero do CEP";
										if(index == 1) msg = msg+"\n- Endereço de entrega";
										if(index == 2) msg = msg+"\n- Numero";
										if(index == 3) msg = msg+"\n- Bairro";
										if(index == 4) msg = msg+"\n- Cidade";
									}
							});
							alert(msg);
							}
					return false;
				});
			}
		});
			
		return false;
	});
	$(".openEventEditCadastro").on("click",function(){
		var idCadastro = $(this).attr("rel");
		$.ajax({
			url: basePatch+"/actionUser/Usuario/editarCadastroRequest",
			type: "post",
			async:false,
			data: {idCadastro:idCadastro},
			success: function(data) {
				$("#ajaxRecebimento").html(data);
				$("#ajaxRecebimento").fadeIn();
				$(".BoxCEPResquest").mask("99999-999",{completed:function(){
					var cepSet = this.val();
					$.ajax({
						url: basePatch+"/correios/restCep",
						type: "post",
						beforeSend: function(){
							$(".ajaxMsg").html("<img src='"+basePatch+"/images/487.GIF'/> "+" Localizando endereço");
							$(".ajaxMsg").fadeIn();
						   },  
						data: {cep:cepSet},
						success: function(data) {
							$(".ajaxMsg").fadeOut();
							obj = JSON.parse(data);
							if(obj.cep == null)
							{
								$(".BoxEndereco").val("");
								$(".BoxBairro").val("");
								$(".BoxEstado option").removeAttr("selected");
								$(".BoxCidade").html('<option value="">Selecione</option>');
								$(".BoxCidade").attr('disabled', true);
								$(".ajaxMsg").html("Endereço não encontrado.");
								$(".ajaxMsg").fadeIn();
							}
							else
								{
									$(".formActionResquest .BoxEnderecoResquest").val(obj.rua);
									$(".formActionResquest .BoxBairroResquest").val(obj.bairro);
									jQuery.each($(".BoxEstado option"), function(i, val) {
											if(obj.uf == $(val).val())	
												{
													$(val).attr('selected', true);
													$.ajax({
														url: basePatch+"/mapeamento-cidades-estados/cidade/nomeclatura/"+obj.uf,
														async:false,
														success: function(data) {
															$(".BoxCidade").html(data);
															$(".BoxCidade").removeAttr("disabled");
															jQuery.each($(".BoxCidade option"), function(ix, value) {
																	if($(value).text() == obj.cid)
																		{
																			$(value).attr('selected', true);
																		}
																})
														},
														error: function(){}
													});	
												}
														    });
								}
						},
						error: function(){
							$("#form_erro").fadeOut();
							alert("Infelizmente não foi possível se comunicar com o gateway dos correios, por favor preencher seus dados manualmente.");
							}
					});
				}});
				
				$(document).on("click",".EventcadastrarEnderecoResquest",function(){
					var actionCep = $(".formActionResquest .BoxCEPResquest").val();
					var actionRua = $(".formActionResquest .BoxEnderecoResquest").val();
					var actionNumero = $(".formActionResquest .BoxNumeroResquest").val();
					var actionBairro = $(".formActionResquest .BoxBairroResquest").val();
					var actionCidade = $(".formActionResquest .BoxCidade option:selected").val();
					$.ajax({
						url: basePatch+"/actionUser/Usuario/atualiza",
						type: "post",
						async:false,
						data: {idCadastro:idCadastro,typeUpdate:3,actionCidade:actionCidade,actionBairro:actionBairro,actionNumero:actionNumero,actionRua:actionRua,actionCep:actionCep},
						success: function(data) {
							if(data != "")
								{
									$(".modelNotificacao").attr("id","atencao_erro");
									$(".erro").html("Alguns campos abaixo estão em branco:");
									$(".tipo_erro").html("");
									$(".tentarNovamente").css("display","block");
									$(".tipo_erro").html(data);
									$("#form_erro").fadeIn();
								}
							else{
									location.reload();
								}
						}
					});
					return false;
				});
			}
		});
		
		return false;
	});
	$(".EventRemoverCadastro").on("click",function(){
		var id = $(this).attr("rel");
		
		$.ajax({
			  url: basePatch+"/api/Cadastro/"+id,
			  type: 'DELETE',
			  success: function(data) {},
			  beforeSend:function(){
				  $(".modelNotificacao").attr("id","modelSucesso");
					$(".atencaoErro").html("Endereço de entrega.");
					$(".erro").html("Estamos removendo o endereço selecionado:");
					$(".tentarNovamente").css("display","none");
					$("#form_erro").fadeIn();
				},
				complete:function(){
					$(".tentarNovamente").html("Continuar").fadeIn();
					$(".tentarNovamente").on("click",function(){
						location.reload();
					})
				},
			});
		return false;
	});
	//Interface Meu Cadastro
	$(".EventSalvarEnderecoPainel").on("click",function(){
		var actionNome = $(".formActionCadastroUsuario .BoxNome").val();
		var actioncpfCnpj = $(".formActionCadastroUsuario .PessoaSeletc").val();
		var actionInscEstadual = $(".formActionCadastroUsuario .InscEstadual").val();
		var actiontipoUsuario = $('.formActionCadastroUsuario input[type="radio"]:checked').val();
		var actiontelRes =  $(".formActionCadastroUsuario .BoxTelResidencial").val();
		var actiontelCel = $(".formActionCadastroUsuario .BoxTelCelular").val();
		var actiontelCom = $(".formActionCadastroUsuario .BoxTelComercial").val();
		$.ajax({
			url: basePatch+"/actionUser/Usuario/atualiza",
			type: "post",
			async:false,
			data: {typeUpdate:1,actiontelCom:actiontelCom,actiontelCel:actiontelCel,actiontelRes:actiontelRes,actiontipoUsuario:actiontipoUsuario,actionInscEstadual:actionInscEstadual,actioncpfCnpj:actioncpfCnpj,actionNome:actionNome},
			success: function(data) {
				if(data != "")
					{
					$(".modelNotificacao").attr("id","atencao_erro");
						$(".erro").html("Alguns campos abaixo estão em branco:");
						$(".tipo_erro").html("");
						$(".tipo_erro").html(data);
						$(".tentarNovamente").html("Tentar Novamente");
						$("#form_erro").fadeIn();
					}
				else
					{
						$(".tipo_erro").html("");
						$(".modelNotificacao").attr("id","modelSucesso");
						$(".erro").html("Seus dados pessoais foram atualizado com sucesso:");
						$(".tentarNovamente").html("Continuar");
						$("#form_erro").fadeIn();
					}
			}
		});
		return false;
	});
	$(".formAction").on("click",'.EventcadastrarEndereco',function(){
		var actionNome = $(".formAction .BoxNome").val();
		var actioncpfCnpj = $(".formAction .PessoaSeletc").val();
		var actionInscEstadual = $(".formAction .InscEstadual").val();
		var actiontipoUsuario = $('.formAction input[type="radio"]:checked').val();
		var actiontelRes =  $(".formAction .BoxTelResidencial").val();
		var actiontelCel = $(".formAction .BoxTelCelular").val();
		var actiontelCom = $(".formAction .BoxTelComercial").val();
		var actionCep = $(".formAction .BoxCEP").val();
		var actionRua = $(".formAction .BoxEndereco").val();
		var actionNumero = $(".formAction .BoxNumero").val();
		var actionBairro = $(".formAction .BoxBairro").val();
		var actionCidade = $(".formAction .BoxCidade option:selected").val();
		$.ajax({
			url: basePatch+"/actionUser/Usuario/atualiza",
			type: "post",
			async:false,
			data: {typeUpdate:2,actionCidade:actionCidade,actionBairro:actionBairro,actionNumero:actionNumero,actionRua:actionRua,actionCep:actionCep,actiontelCom:actiontelCom,actiontelCel:actiontelCel,actiontelRes:actiontelRes,actiontipoUsuario:actiontipoUsuario,actionInscEstadual:actionInscEstadual,actioncpfCnpj:actioncpfCnpj,actionNome:actionNome},
			success: function(data) {
				if(data != "")
					{
						$(".modelNotificacao").attr("id","atencao_erro");
						$(".erro").html("Alguns campos abaixo estão em branco:");
						$(".tipo_erro").html("");
						$(".tipo_erro").html(data);
						$(".tentarNovamente").html("Tentar Novamente");
						$("#form_erro").fadeIn();
					}
				else{
						$(".tipo_erro").html("");
						$(".modelNotificacao").attr("id","modelSucesso");
						$(".erro").html("Seu endereço foi salvo com sucesso:");
						$(".tentarNovamente").html("Continuar");
						$("#form_erro").fadeIn();
					}
			},
			complete:function(){
				$(".tentarNovamente").html("Continuar").fadeIn();
				$(".tentarNovamente").on("click",function(){
					location.reload();
				})
			},
		});
		return false;
	})
	$(".formAction").on("click",'.EventsalvarEnderecoPainel',function(){		
		var actionCep = $(".formAction .BoxCEP").val();
		var actionRua = $(".formAction .BoxEndereco").val();
		var actionNumero = $(".formAction .BoxNumero").val();
		var actionBairro = $(".formAction .BoxBairro").val();
		var actionCidade = $(".formAction .BoxCidade option:selected").val();
		var cadastroIdAction = $(".cadastroIdAction").val();
		$.ajax({
			url: basePatch+"/actionUser/Usuario/atualiza",
			type: "post",
			async:false,
			data: {idCadastro:cadastroIdAction,typeUpdate:3,actionCidade:actionCidade,actionBairro:actionBairro,actionNumero:actionNumero,actionRua:actionRua,actionCep:actionCep},
			success: function(data) {
				if(data != "")
					{
					$(".modelNotificacao").attr("id","atencao_erro");
						$(".erro").html("Alguns campos abaixo estão em branco:");
						$(".tipo_erro").html("");
						$(".tentarNovamente").css("display","block");
						$(".tipo_erro").html(data);
						$("#form_erro").fadeIn();
					}
				else{
						$(".modelNotificacao").attr("id","modelSucesso");
						$(".erro").html("Seu endereço foi salvo com sucesso:");
						$(".tentarNovamente").html("Continuar");
						$("#form_erro").fadeIn();
					}
			},
			complete:function(){
				$(".tentarNovamente").html("Continuar").fadeIn();
				$(".tentarNovamente").on("click",function(){
					location.reload();
				})
			},	
		});
		return false;
	})
	
	//
	$( ".tipoUsuario" ).change(function(){
		if($(this).val() == 2)
			{
			$(".showInscEstadual").slideDown();
			}
		else
			{
			$(".showInscEstadual").slideUp();	
			}
		$(".PessoaSeletc").attr("value","");
	})
	$( ".actionRadioEndereco" ).change(function(){
		var element = this;
		if($(this).val() == "actionNewEndereco")
			{
			$(".formAction input,select").each(function( index,element ) {
				if(index != 6)
					{
				$(element).val("");
					}
			});
				$(".enderecoAlternativoAction").slideDown("fast");
				$("#despesaFrete").html("Indisponível");
				$("#valortotalMaisFrete").html("Indisponível");
				$(".restStatusService").html('<a href="#" onclick="return false"><span class="BoxConcluirButtonOff" title="Concluir Pedido">Concluir Pedido</span></a>');
			}
		else{
				var cepSet = $(this).attr("rel");
				$.ajax({
					url: basePatch+"/correios/frete",
					type: "post",
					data: {cep:cepSet},
					beforeSend:function(){
						$(".modelNotificacao").attr("id","atualizando_dados");
						$(".atencaoErro").html("Selecione um endereço de entrega.");
						$(".erro").html("");
						$(".tentarNovamente").css("display","none");
						$(".tipo_erro").html("Estamos atualizando os dados da sua compra.");
						$("#form_erro").fadeIn();
					},
					complete:function(){
						$(".tentarNovamente").html("Continuar").fadeIn();
						$(".tentarNovamente").on("click",function(){
							location.reload();
						})
					},
					success: function(data) {
						$("#despesaFrete").html(data);
						$.ajax({
							url: basePatch+"/correios/frete/total",
							async:false,
							type: "post",
							data: {cep:cepSet},
							success: function(data) {
								$("#valortotalMaisFrete").html(data);
								var value = $(element).val();
								$.ajax({
									  url: basePatch+"/api/Cadastro/"+value,
									  type: 'PUT',
									  data: {cep:cepSet},
									  success: function(data) {
									  }
									});
							},
							error: function(){}
						});
						
					},
					error: function(){}
				});	
			$(".enderecoAlternativoAction").slideUp("fast");
		}
		return false;
	});
	$(".tentarNovamente").on("click",function(){
		$("#form_erro").fadeOut();
		return false;
	});
	$(".BoxMenuInstitucional").on("click",".MeusDados",function(){
		$(this).attr("class","MeusDadosActive");
		$(".EndEntregaActive").attr("class","EndEntrega");
		$(".SeuPedidoActive").attr("class","SeuPedido");
		$("#BoxDetalheDoPedido").slideUp();
		$("#BoxMeuEndereco").slideUp();
		$("#BoxMeusDados").slideDown();
	});
	$(".BoxMenuInstitucional").on("click",".EndEntrega",function(){
		$(this).attr("class","EndEntregaActive");
		$(".MeusDadosActive").attr("class","MeusDados");
		$(".SeuPedidoActive").attr("class","SeuPedido");
		$("#BoxDetalheDoPedido").slideUp();
		$("#BoxMeusDados").slideUp();
		$("#BoxMeuEndereco").slideDown();
	});
	$(".BoxMenuInstitucional").on("click",".SeuPedido",function(){
		$(this).attr("class","SeuPedidoActive");
		$(".EndEntregaActive").attr("class","EndEntrega");
		$(".MeusDadosActive").attr("class","MeusDados");
		$("#BoxMeusDados").slideUp();
		$("#BoxMeuEndereco").slideUp();
		$("#BoxDetalheDoPedido").slideDown();
	});
	
	
	
	$(".status_criar_conta").on("click",function(){
		$("#form_login").css("display","none");
		$("#form_cadastro").css("display","block");
		$("#btn_form a.status_criar_conta").addClass("status_criar_conta_active");
		$("#btn_form a.status_entrar").removeClass("status_entrar_active");
		return false;
	});
	$(".status_entrar").on("click",function(){
		$("#form_cadastro").css("display","none");
		$("#form_login").css("display","block");
		$("#btn_form a.status_entrar").addClass("status_entrar_active");
		$("#btn_form a.status_criar_conta").removeClass("status_criar_conta_active");
		return false;
	});
	
	
	
	
	
	/*
	 * 
	 * PopUp User
	 */
	$('.eventOpenPopUp').on("click",function(){
		if($("#Login_Cadastro").css("display") != "none")
			{
				$("#Login_Cadastro").animate({left:"+=15"}, 50,function(){
					$(this).animate({left:"-=30"}, 50,function(){
						$(this).animate({left:"+=15"}, 50,function(){});
					});
				});
			}
		else
			{
		$("#Login_Cadastro").toggle("fast");
			}
		return false;
	})
	$('.status_fechar').on("click",function(){
		$("#form_erro").fadeOut();
		$("#Login_Cadastro").fadeOut("fast");
		return false;
	})
	
	/*
	 * Action cadastra-se;
	 */
	$('.Eventcadastrar').on("click",function(){
		var eventLogin = $(".formActionCadastro .login").val();
		var eventEmail = $(".formActionCadastro .email").val();
		var eventPassword = $(".formActionCadastro .Password").val();
		var eventPasswordConfirm = $(".formActionCadastro .PasswordConfirm").val();
		$.ajax({
			url: basePatch+"/actionUser/Usuario/novoUser",
			type: "post",
			async:false,
			data: {eventLogin:eventLogin,eventEmail:eventEmail,eventPassword:eventPassword,eventPasswordConfirm:eventPasswordConfirm},
			success: function(data) {
					if(data == "1")
						{
							$.ajax({
							url: basePatch+"/actionUser/Login/index",
							type: "post",
							async:false,
							data: {eventLogin:eventLogin,eventPassword:eventPassword},
							success: function(data) {
										if(data == "01")
										{
											$("#Login_Cadastro").fadeOut("fast",function(){
												location.reload();
											});
										}
							},
							error: function(){}
						});
						}
					else
						{
						$(".modelNotificacao").attr("id","atencao_erro");
						$(".tentarNovamente").html("Tentar Novamente");
							$("#Login_Cadastro").animate({left:"+=15"}, 50,function(){
								$(this).animate({left:"-=30"}, 50,function(){
									$(this).animate({left:"+=15"}, 50,function(){
										$(".tipo_erro").html(data);
										$("#form_erro").fadeIn();
									});
								});
							});
						}
			},
			error: function(){}
		});
			return false;
	})
	$('.EventcadastrarLoginFinaliza').on("click",function(){
		var eventLogin = $("#BoxLogin .formAction .actionLogin").val();
		var eventEmail = $("#BoxLogin .formAction .actionEmail").val();
		var eventPassword = $("#BoxLogin .formAction .actionPassoword").val();
		var eventPasswordConfirm = $("#BoxLogin .formAction .actionPassowordConfirm").val();
		$.ajax({
			url: basePatch+"/actionUser/Usuario/novoUser",
			type: "post",
			async:false,
			data: {eventLogin:eventLogin,eventEmail:eventEmail,eventPassword:eventPassword,eventPasswordConfirm:eventPasswordConfirm},
			success: function(data) {
					if(data == "1")
						{
						$.ajax({
							url: basePatch+"/actionUser/Login/index",
							type: "post",
							async:false,
							data: {eventLogin:eventLogin,eventPassword:eventPassword},
							success: function(data) {
										if(data == "01")
										{
											location.reload();
										}
										else
										{
											$(".modelNotificacao").attr("id","atencao_erro");
											$(".erro").html("Detalhe(s) do(s) erro(s) encontrado(s).");
											$(".tipo_erro").html(data);
											$(".tentarNovamente").html("Tentar Novamente");
											$("#form_erro").fadeIn();
											//$(".return").html(data);
										}
							},
							error: function(){}
						})
						}
					else
						{
						$(".modelNotificacao").attr("id","atencao_erro");
							$(".erro").html("Detalhe(s) do(s) erro(s) encontrado(s).");
							$(".tipo_erro").html(data);
							$(".tentarNovamente").html("Tentar Novamente");
							$("#form_erro").fadeIn();
							//$(".return").html(data);
						}
			},
			error: function(){}
		});
			return false;
	})
	/*
	 * Action Login
	 */
	$('.Eventlogin').on("click",function(){
		var eventLogin = $(".formActionLogin .login").val();
		var eventPassword = $(".formActionLogin .Password").val();
		$.ajax({
			url: basePatch+"/actionUser/Login/index",
			type: "post",
			async:false,
			data: {eventLogin:eventLogin,eventPassword:eventPassword},
			success: function(data) {
						if(data == "01")
						{
							$("#Login_Cadastro").fadeOut("fast",function(){
								location.reload();
							});
						}
						else
						{
							$(".modelNotificacao").attr("id","atencao_erro");
							$(".tentarNovamente").html("Tentar Novamente");
							$("#Login_Cadastro").animate({left:"+=15"}, 50,function(){
								$(this).animate({left:"-=30"}, 50,function(){
									$(this).animate({left:"+=15"}, 50,function(){
										$(".tipo_erro").html(data);
										$("#form_erro").fadeIn();
									});
								});
							});
							
						}
			},
			error: function(){}
		});
			return false;
	})
	$('.actionConcluirPedido').on("click",function(){
		setTimeout(function(){
			window.location =  basePatch+"/painel";
		},3000)
	})
	$(".formAction").on("click",'.EventcadastrarEnderecoAlternativo',function(){
		var actionCep = $("#BoxEndereco .BoxCEP").val();
		var actionRua = $("#BoxEndereco .BoxEndereco").val();
		var actionNumero = $("#BoxEndereco .BoxNumero").val();
		var actionBairro = $("#BoxEndereco .BoxBairro").val();
		var actionCidade = $("#BoxEndereco .BoxCidade option:selected").val();
		var errorCadastro = 0;
		$(".formAction input,select").each(function( index,element ) {
			if($(element).val() == "")
				{
					errorCadastro = 1;
				}
		});
		if(errorCadastro == 0)
		{
			$.ajax({
				url: basePatch+"/api/Cadastro",
				type: "post",
				async:false,
				data: {actionCep:actionCep,actionRua:actionRua,actionNumero:actionNumero,actionBairro:actionBairro,actionCidade:actionCidade},
				success: function(data) {
					$(".modelNotificacao").attr("id","modelSucesso");
					$(".erro").html("Endereço alternativo salvo com sucesso:");
					$(".tentarNovamente").html("Continuar");
					$("#form_erro").fadeIn();
				},
				complete:function(){
					$(".tentarNovamente").on("click",function(){
						location.reload();
					})
				},
			});
		}
			else
				{
				$(".modelNotificacao").attr("id","atencao_erro");
				$(".erro").html("Alguns campos abaixo estão em branco:");
				$(".tentarNovamente").html("Tentar Novamente");
				$(".formAction input,select").each(function( index,element ) {
					if($(element).val() == "")
						{
							if(index == 0) $(".tipo_erro").append("<Br/> - NÃºmero do CEP");
							if(index == 1) $(".tipo_erro").append("<Br/> - Endereço de entrega");
							if(index == 2) $(".tipo_erro").append("<Br/> - NÃºmero");
							if(index == 3) $(".tipo_erro").append("<Br/> - Bairro");
							if(index == 4) $(".tipo_erro").append("<Br/> - Cidade");
						}
				});
				$("#form_erro").fadeIn();
				}
		return false;
	})
	
	$('#bottaoAlterarCadastro').on("click",function(){
			$(".formAction input, .formAction select").removeAttr("disabled");
			$(".seletoInt").html('<input type="submit" value="Salvar Endereço" class="EventsalvarEnderecoPainel"><br/><br/><br/>');
		return false;
	})
	$(".BoxCEP").mask("99999-999",{completed:function(){
		var cepSet = this.val();
		$.ajax({
			url: basePatch+"/correios/restCep",
			type: "post",
			beforeSend: function(){
					$(".ajaxMsg").html("<img src='"+basePatch+"/images/487.GIF'/> "+" Localizando endereço");
					$(".ajaxMsg").fadeIn();
			   },  
			data: {cep:cepSet},
			success: function(data) {
				obj = JSON.parse(data);
				if(obj.cep == null)
				{
					$(".BoxEndereco").val("");
					$(".BoxBairro").val("");
					$(".BoxEstado option").removeAttr("selected");
					$(".BoxCidade").html('<option value="">Selecione</option>');
					$(".BoxCidade").attr('disabled', true);
					$(".ajaxMsg").html("Endereço não encontrado.");
					$(".ajaxMsg").fadeIn();
				}
				else
					{
						$(".ajaxMsg").fadeOut();
						$(".BoxEndereco").val(obj.rua);
						$(".BoxBairro").val(obj.bairro);
						jQuery.each($(".BoxEstado option"), function(i, val) {
								if(obj.uf == $(val).val())	
									{
										$(val).attr('selected', true);
										$.ajax({
											url: basePatch+"/mapeamento-cidades-estados/cidade/nomeclatura/"+obj.uf,
											async:false,
											success: function(data) {
												$(".BoxCidade").html(data);
												$(".BoxCidade").removeAttr("disabled");
												jQuery.each($(".BoxCidade option"), function(ix, value) {
														if($(value).text() == obj.cid)
															{
																$(value).attr('selected', true);
															}
													})
											},
											error: function(){}
										});	
									}
											    });
					}
				//Front Set Front
				$.ajax({
					url: basePatch+"/correios/frete",
					async:false,
					type: "post",
					data: {cep:cepSet},
					success: function(data) {
						$("#despesaFrete").html(data);
						$.ajax({
							url: basePatch+"/correios/frete/total",
							async:false,
							type: "post",
							data: {cep:cepSet},
							success: function(data) {
								$("#valortotalMaisFrete").html(data);
							},
							error: function(){}
						});
					},
					error: function(){}
				});	
			},
			error: function(){
				$("#form_erro").fadeOut();
				alert("Infelizmente não foi possível se comunicar com o gateway dos correios, por favor preencher seus dados manualmente.");
				}
		});	
	}});
	$(".DigCep1").on("click",function(){
		return false;
	})
	$(".DigCep1").mask("99999-999");
	$(".CalcFret").on("click",function(){ 
		var element = this;
		if($(".DigCep1").val().length == 9)
			{
			var cepSet = $(".DigCep1").val();
			 $(".DigCep1").css("background","#ffffff");
			//Front Set Frete
				$.ajax({
					url: basePatch+"/correios/frete",
					beforeSend:function(){
						$(element).html("<img src='"+basePatch+"/images/486.GIF'/> "+"Calculando Valor");
					},
					complete:function(){
						$(element).html("Calcular frete");
					},
					type: "post",
					data: {cep:cepSet},
					success: function(data) {
						if(data != "Indisponível")
						{
						$(".txt_TotalFrete").html("Total com frete: ");
						}
					else
						{
						$(".txt_TotalFrete").html("Total: ");
						}
						$(".txt_frete").html("Frete: "+data);
						$.ajax({
							url: basePatch+"/correios/frete/total",
							async:false,
							type: "post",
							data: {cep:cepSet},
							success: function(data) {
								$(".CalcSomaValor").html(data);
							},
							error: function(){}
						});
					},
					error: function(){}
				});	
			}
		else
			{
			$(".DigCep1").css("background","#ff0000");
			}
		return false;
	})
	$(document).on('change',".BoxEstado",function() {
		  	var val = $(this).val();
		  	if(val != "")
		  		{
			  		$.ajax({
						url: basePatch+"/mapeamento-cidades-estados/cidade/nomeclatura/"+val,
						async:false,
						success: function(data) {
							$(".BoxCidade").html(data);
							$(".BoxCidade").removeAttr("disabled");
						},
						error: function(){}
					});	
		  		}
		});

	$(".testeRestEstoque").on("click",function(){
		$.ajax({
			  url: basePatch+"/webservice/Estoque/03SEV",
			  data:{user:"dereck","senha":159753,"saldo":11},
			  type: 'PUT',
			  success: function(data) {
				 alert(data.mensagem);
			  },
			});
		return false;
	});
	
})