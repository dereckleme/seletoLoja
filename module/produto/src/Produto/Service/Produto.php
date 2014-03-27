<?php
namespace Produto\Service;

use Doctrine\ORM\EntityManager;
use Zend\Stdlib\Hydrator;
use Base\Service\AbstractService;

class Produto extends AbstractService {
    
    protected $subCategoriaReferenc;
    protected $servicePagamento;
    protected $serviceImagens;
    
    protected $id_produto;
    
	public function __construct(EntityManager $em) {
	    parent::__construct($em);
	    $this->entity = "Produto\Entity\ProdutoProdutos";
	}
	
	public function insert(array $data) {
	    $this->setTargetEntity("Produto\Entity\ProdutoSubcategoria");
	    $this->setCampo("setProdutosubcategoria");
	    $this->setActionReference($data['inputSubCategoria']);
		$produto = parent::insert($data);
		
		//Add estoque
		$this->entity = "Produto\Entity\PagamentoControleestoque";		
		$this->setTargetEntity("Produto\Entity\ProdutoProdutos");
		$this->setCampo("setProdutoproduto");
		$this->setActionReference($produto->getIdproduto());
		$estoque = parent::insert(array("quantidade" => $data['quantidade']));    
		
		// add imagens
		if(count($data['foto']) >= 2){
		    $this->entity = "Produto\Entity\ProdutoImagens";
		    $this->setTargetEntity("Produto\Entity\ProdutoProdutos");
		    $this->setCampo("setProdutoProdutosproduto");
		    $this->setActionReference($produto->getIdproduto());		    
		    unset($data['foto'][0]);
		    
    		foreach($data['foto'] as $image)
    		{    		    
    		    parent::insert(array('images'=>$image));
    		}
		
		}
		return $produto->getIdproduto();
	}
	
	public function update(array $data){
	    
	    $this->setTargetEntity("Produto\Entity\ProdutoSubcategoria");
	    $this->setCampo("setProdutosubcategoria");
	    $this->setActionReference($data['inputSubCategoria']);
	    $produto = parent::update($data);
	    
	    // edit estoque	    
	    $this->entity = "Produto\Entity\PagamentoControleestoque";
	    $this->setTargetEntity("Produto\Entity\ProdutoProdutos");
	    $this->setCampo("setProdutoproduto");
	    $this->setActionReference($produto->getIdproduto());
	    foreach ($produto->getEstoque() as $stoke){
	    	$idestoque = $stoke->getIdcontroleestoque();
	    }
	    $estoque = parent::update(array("id" => $idestoque,"quantidade" => $data['quantidade']));
	    
	    //edit images
	    
	    
	    return $produto->getIdproduto();
	}
	
	public function delete($idproduto, $idprodutoestoque, $idprodutoimagem) {
	    if(!empty($idprodutoimagem)){
	        $this->entity = "Produto\Entity\ProdutoImagens";
	        if(is_array($idprodutoimagem)) {
	        	foreach ($idprodutoimagem as $id){
	        	    $imagens = parent::delete($id);
	        	}
	        } else {
	            $imagens = parent::delete($idprodutoimagem);
	        }			
		}
		
		$this->entity = "Produto\Entity\PagamentoControleestoque";
		$estoque = parent::delete($idprodutoestoque);
		
		$this->entity = "Produto\Entity\ProdutoProdutos";
		return parent::delete($idproduto);
	}	
	
	/**
	 * @param field_type $id_produto
	 */
	public function setId_produto($id_produto) {
		$this->id_produto = $id_produto;
	}
	
}