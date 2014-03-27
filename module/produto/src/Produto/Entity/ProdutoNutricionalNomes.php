<?php

namespace Produto\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProdutoNutricionalNomes
 *
 * @ORM\Table(name="produto_nutricional_nomes")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Produto\Entity\ProdutoNutricionalNomesRepository")
 */
class ProdutoNutricionalNomes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idNutricional_nomes", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idnutricionalNomes;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=255, nullable=true)
     */
    private $nome;
    
	/**
	 * @return the $idnutricionalNomes
	 */
	public function getIdnutricionalNomes() {
		return $this->idnutricionalNomes;
	}

	/**
	 * @return the $nome
	 */
	public function getNome() {
		return $this->nome;
	}

	/**
	 * @param number $idnutricionalNomes
	 */
	public function setIdnutricionalNomes($idnutricionalNomes) {
		$this->idnutricionalNomes = $idnutricionalNomes;
	}

	/**
	 * @param string $nome
	 */
	public function setNome($nome) {
		$this->nome = $nome;
	}



}
