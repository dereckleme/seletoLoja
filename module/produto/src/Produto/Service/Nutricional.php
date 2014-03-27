<?php
namespace Produto\Service;

use Base\Service\AbstractService;
use Doctrine\ORM\EntityManager;

class Nutricional extends AbstractService
{
    protected $idtabelanutricional = array();
    
    public function __construct(EntityManager $em){
    	parent::__construct($em);
    	$this->entity = "Produto\Entity\ProdutoNutricionalNomes";
    }
    
    public function delete($id)
    {
        $this->entity = "Produto\Entity\ProdutoNutricional";
        foreach ($this->idtabelanutricional as $idtabela) {
        	parent::delete($idtabela);
        }
        
        $this->entity = "Produto\Entity\ProdutoNutricionalNomes";
        return parent::delete($id);
    }
    
	/**
	 * @param multitype: $idtabelanutricional
	 */
	public function setIdtabelanutricional($idtabelanutricional) {
		$this->idtabelanutricional = $idtabelanutricional;
	}

}