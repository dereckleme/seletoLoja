<?php

namespace Produto\Entity;

use Doctrine\ORM\EntityRepository;

class ProdutoNutricionalRepository extends EntityRepository 
{
    
    public function listNutricionalForProdutos()
    {
        $qb = $this->createQueryBuilder('n');
        $qb->select('n');
        $qb->innerJoin('Produto\Entity\ProdutoProdutos', 'p', 'WITH', 'n.produtoproduto = p.idproduto');
        $qb->orderBy('p.idproduto');
        $qb->addOrderBy('n.idprodutoNutricional');
        $query = $qb->getQuery();
        $results = $query->getResult();
        #echo "<pre>", print_r($query->getDQL()), "</pre>";
        return $results;
    }
	
}