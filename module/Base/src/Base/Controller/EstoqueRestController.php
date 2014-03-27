<?php

namespace Base\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class EstoqueRestController extends AbstractRestfulController
{
    public function getList()
    {
    	return new JsonModel();
    }
    // Retornar o registro especifico - GET
    public function get($id)
    {
        $em = $this->getServiceLocator()->get("Doctrine\ORM\EntityManager");
        $produto = $em->getRepository("Produto\Entity\ProdutoProdutos")->findOneByidproduto($id);
        if($produto)
            {
                $entity = $em->getRepository("Pagamento\Entity\PagamentoControleestoque")->findOneByprodutoproduto($produto);
                return new JsonModel(array("num" => $entity->getQuantidade()));
            }
            else
            {
                return new JsonModel();
            }
    }
}
