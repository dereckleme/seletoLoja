<?php

namespace CarrinhoCompras\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Mvc\Controller\Plugin\Url;

class CarrinhoRestController extends AbstractRestfulController
{
    public function create($data)
    {
        if($data)
        {
        	$service = $this->getServiceLocator()->get("CarrinhoCompras\Service\Carrinho");
        	$service->setIdProduto($data['actionAddCart']);
        	$service->setQuantProduto($data['actionQuant']);
        	$service->adiciona();
        	//event carrinho
        	$eventoCarrinho = $this->getServiceLocator()->get('CarrinhoCompras\Model\Carrinho');
        	$dataInfo['valorTotal'] = $eventoCarrinho->calculoTotal();
        	$getList = $eventoCarrinho->lista();
        	
        	foreach($getList AS $produto)
        	{
        	    $dataInfo['listaProdutos'][] = array(
        	        "titulo" => $produto['produto']->getTitulo(),
        	        "valor" => $produto['produto']->getValor(true),
        	        "quantidade" => $produto['quantidade'],
        	        "foto" => $produto['produto']->getFoto(),
        	        "urlProduto" => $this->url()->fromRoute('publico-categoria/publico-categoria-e-subcategoria/publico-produto', array('categoriaslug'=>$produto['produto']->getProdutosubcategoria()->getCategorias()->getSlug(), 'subcategoriaslugSub'=>$produto['produto']->getProdutosubcategoria()->getSlugSubcategoria(), 'produtoSlug'=>$produto['produto']->getSlugProduto() ))
        	    );
        	}  
        	return new JsonModel($dataInfo);
        }
        else {
            return new JsonModel();
        }
    }
}
