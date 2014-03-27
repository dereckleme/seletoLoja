$(document).ready(function(){	
	setInterval(function(){
	$(".statusNpedido").each(function( i, val ) {
			var idRecibo = $(val).attr("rel");
					$.ajax({
					url: basePatch+"/painel/status/"+idRecibo,
					async:false,
					success: function(data) {
						if(data != "")
							{
							$("p",val).html(data);
							}
					},
					error: function(){}
						});
		});
	 }, 55000);
	if(window.location.hash == "#pedido") 
		{
		$(".SeuPedido").attr("class","SeuPedidoActive");
		$("#BoxDetalheDoPedido").css("display","block");
		}
	else if(window.location.hash == "#enderecos") 
		{
		$(".EndEntrega").attr("class","EndEntregaActive");
		$("#BoxMeuEndereco").css("display","block");
		}
	else if(window.location.hash == "#meusDados") 
		{
		$(".MeusDados").attr("class","MeusDadosActive");
		$("#BoxMeusDados").css("display","block");
		}
	else
		{
		$(".SeuPedido").attr("class","SeuPedidoActive");
		$("#BoxDetalheDoPedido").css("display","block");
		}
})