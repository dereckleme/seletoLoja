<?php

namespace Produto\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProdutoNutricional
 *
 * @ORM\Table(name="produto_nutricional")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Produto\Entity\ProdutoNutricionalRepository")
 */
class ProdutoNutricional
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idProduto_Nutricional", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idprodutoNutricional;

    /**
     * @var string
     *
     * @ORM\Column(name="quantidade", type="string", length=45, nullable=true)
     */
    private $quantidade;

    /**
     * @var string
     *
     * @ORM\Column(name="vd", type="string", length=45, nullable=true)
     */
    private $vd;

    /**
     * @var \ProdutoProdutos
     *
     * @ORM\ManyToOne(targetEntity="Produto\Entity\ProdutoProdutos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Produto_idProduto", referencedColumnName="idProduto")
     * })
     */
    private $produtoproduto;

    /**
     * @var \ProdutoNutricionalNomes
     *
     * @ORM\ManyToOne(targetEntity="Produto\Entity\ProdutoNutricionalNomes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Produto_idNutricional_nomes", referencedColumnName="idNutricional_nomes")
     * })
     */
    private $produtonutricionalNomes;
    
    
	/**
	 * @return the $idprodutoNutricional
	 */
	public function getIdprodutoNutricional() {
		return $this->idprodutoNutricional;
	}

	/**
	 * @return the $quantidade
	 */
	public function getQuantidade() {
		return $this->quantidade;
	}

	/**
	 * @return the $vd
	 */
	public function getVd() {
		return $this->vd;
	}

	/**
	 * @return the $produtoproduto
	 */
	public function getProdutoproduto() {
		return $this->produtoproduto;
	}

	/**
	 * @return the $produtonutricionalNomes
	 */
	public function getProdutonutricionalNomes() {
		return $this->produtonutricionalNomes;
	}

	/**
	 * @param number $idprodutoNutricional
	 */
	public function setIdprodutoNutricional($idprodutoNutricional) {
		$this->idprodutoNutricional = $idprodutoNutricional;
	}

	/**
	 * @param string $quantidade
	 */
	public function setQuantidade($quantidade) {
		$this->quantidade = $quantidade;
	}

	/**
	 * @param string $vd
	 */
	public function setVd($vd) {
		$this->vd = $vd;
	}

	/**
	 * @param ProdutoProdutos $produtoproduto
	 */
	public function setProdutoproduto($produtoproduto) {
		$this->produtoproduto = $produtoproduto;
	}

	/**
	 * @param ProdutoNutricionalNomes $produtonutricionalNomes
	 */
	public function setProdutonutricionalNomes($produtonutricionalNomes) {
		$this->produtonutricionalNomes = $produtonutricionalNomes;
	}



}
