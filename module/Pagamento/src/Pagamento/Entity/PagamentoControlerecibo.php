<?php
namespace Pagamento\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * PagamentoControlerecibo
 *
 * @ORM\Table(name="pagamento_controlerecibo")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Pagamento\Entity\PagamentoControlereciboRepository")
 */
class PagamentoControlerecibo
{
	public function __construct() {
       $this->dtVenda = new \DateTime("now");
       $this->produtosRecibo = new ArrayCollection();
    }
    /**
     * @var integer
     *
     * @ORM\Column(name="idControleRecibo", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcontrolerecibo;

    /**
     * @var string
     *
     * @ORM\Column(name="nPedido", type="string", length=255, nullable=true)
     */
    private $npedido;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_venda", type="datetime", nullable=true)
     */
    private $dtVenda;

    /**
     * @var float
     *
     * @ORM\Column(name="valor", type="decimal", nullable=true)
     */
    private $valor;

    /**
     * @var float
     *
     * @ORM\Column(name="valor_frete", type="decimal", nullable=true)
     */
    private $valorFrete;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;

    /**
     * @var \idcad
     *
     * @ORM\ManyToOne(targetEntity="UsuarioCadastro")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idcadastroRef", referencedColumnName="idcadastro")
     * })
     */
    private $idcad;

    /**
     * @var \UsuarioUsuarios
     *
     * @ORM\ManyToOne(targetEntity="UsuarioUsuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Usuario_idUsuarios", referencedColumnName="idUsuario")
     * })
     */
    private $usuariousuarios;

    /**
     * @var \PagamentoStatusFpagamento
     *
     * @ORM\ManyToOne(targetEntity="PagamentoStatusFpagamento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fPagamento", referencedColumnName="idStatus")
     * })
     */
    private $fpagamento;

    /**
     * @var \PagamentoStatusSpagamento
     *
     * @ORM\ManyToOne(targetEntity="PagamentoStatusSpagamento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sPagamento", referencedColumnName="idStatus")
     * })
     */
    private $spagamento;
	/**
     * @ORM\OneToMany(targetEntity="Pagamento\Entity\PagamentoControlepedido", mappedBy="idcontrolerecibo")
     */
    private $produtosRecibo;
    
    
	public function getProdutosRecibo() {
		return $this->produtosRecibo;
	}

	public function setProdutosRecibo($produtosRecibo) {
		$this->produtosRecibo = $produtosRecibo;
	}

	public function getIdcad() {
		return $this->idcad;
	}

	public function setIdcad($idcad) {
		$this->idcad = $idcad;
	}

	public function setDtVenda($dtVenda) {
	    $this->dtVenda = new \DateTime("now");
	}
	public function getIdcontrolerecibo() {
		return $this->idcontrolerecibo;
	}

	public function getNpedido() {
		return $this->npedido;
	}

	public function getDtVenda() {
		return $this->dtVenda;
	}

	public function getValor() {
		return $this->valor;
	}

	public function getValorFrete() {
		return $this->valorFrete;
	}

	public function getStatus() {
		return $this->status;
	}


	public function getUsuariousuarios() {
		return $this->usuariousuarios;
	}

	public function getFpagamento() {
		return $this->fpagamento;
	}

	public function getSpagamento() {
		return $this->spagamento;
	}

	public function setIdcontrolerecibo($idcontrolerecibo) {
		$this->idcontrolerecibo = $idcontrolerecibo;
	}

	public function setNpedido($npedido) {
		$this->npedido = $npedido;
	}

	public function setValor($valor) {
		$this->valor = $valor;
	}

	public function setValorFrete($valorFrete) {
		$this->valorFrete = $valorFrete;
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	public function setUsuariousuarios($usuariousuarios) {
		$this->usuariousuarios = $usuariousuarios;
	}

	public function setFpagamento($fpagamento) {
		$this->fpagamento = $fpagamento;
	}

	public function setSpagamento($spagamento) {
		$this->spagamento = $spagamento;
	}

	
}
