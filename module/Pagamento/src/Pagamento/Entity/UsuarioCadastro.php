<?php

namespace Pagamento\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsuarioCadastro
 *
 * @ORM\Table(name="usuario_cadastro")
 * @ORM\Entity
 */
class UsuarioCadastro
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idcadastro", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcadastro;

    /**
     * @var integer
     *
     * @ORM\Column(name="tipoUsuario", type="integer", nullable=true)
     */
    private $tipousuario;

    /**
     * @var integer
     *
     * @ORM\Column(name="padrao", type="integer", nullable=true)
     */
    private $padrao;

    /**
     * @var integer
     *
     * @ORM\Column(name="ativo", type="integer", nullable=true)
     */
    private $ativo;

    /**
     * @var string
     *
     * @ORM\Column(name="inscricaEstadual", type="string", length=255, nullable=true)
     */
    private $inscricaestadual;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=255, nullable=true)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="rua", type="string", length=255, nullable=true)
     */
    private $rua;

    /**
     * @var string
     *
     * @ORM\Column(name="bairro", type="string", length=255, nullable=true)
     */
    private $bairro;

    /**
     * @var string
     *
     * @ORM\Column(name="CEP", type="string", length=255, nullable=true)
     */
    private $cep;

    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=45, nullable=true)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="cpf", type="string", length=45, nullable=true)
     */
    private $cpf;

    /**
     * @var string
     *
     * @ORM\Column(name="cnpj", type="string", length=45, nullable=true)
     */
    private $cnpj;

    /**
     * @var string
     *
     * @ORM\Column(name="telefone_res", type="string", length=255, nullable=true)
     */
    private $telefoneRes;

    /**
     * @var string
     *
     * @ORM\Column(name="telefone_cel", type="string", length=255, nullable=true)
     */
    private $telefoneCel;

    /**
     * @var string
     *
     * @ORM\Column(name="telefone_com", type="string", length=255, nullable=true)
     */
    private $telefoneCom;

    /**
     * @var \MapeamentoCidade
     *
     * @ORM\ManyToOne(targetEntity="MapeamentoCidade")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="mapeamento_idcidade", referencedColumnName="idcidade")
     * })
     */
    private $mapeamentocidade;

    /**
     * @var \UsuarioUsuarios
     *
     * @ORM\ManyToOne(targetEntity="UsuarioUsuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Usuarios_idUsuarios", referencedColumnName="idUsuario")
     * })
     */
    private $usuariosusuarios;
    
	public function getIdcadastro() {
		return $this->idcadastro;
	}

	public function getTipousuario() {
		return $this->tipousuario;
	}

	public function getPadrao() {
		return $this->padrao;
	}

	public function getAtivo() {
		return $this->ativo;
	}

	public function getInscricaestadual() {
		return $this->inscricaestadual;
	}

	public function getNome() {
		return $this->nome;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getRua() {
		return $this->rua;
	}

	public function getBairro() {
		return $this->bairro;
	}

	public function getCep() {
		return $this->cep;
	}

	public function getNumero() {
		return $this->numero;
	}

	public function getCpf() {
		return $this->cpf;
	}

	public function getCnpj() {
		return $this->cnpj;
	}

	public function getTelefoneRes() {
		return $this->telefoneRes;
	}

	public function getTelefoneCel() {
		return $this->telefoneCel;
	}

	public function getTelefoneCom() {
		return $this->telefoneCom;
	}

	public function getMapeamentocidade() {
		return $this->mapeamentocidade;
	}

	public function getUsuariosusuarios() {
		return $this->usuariosusuarios;
	}

	public function setIdcadastro($idcadastro) {
		$this->idcadastro = $idcadastro;
	}

	public function setTipousuario($tipousuario) {
		$this->tipousuario = $tipousuario;
	}

	public function setPadrao($padrao) {
		$this->padrao = $padrao;
	}

	public function setAtivo($ativo) {
		$this->ativo = $ativo;
	}

	public function setInscricaestadual($inscricaestadual) {
		$this->inscricaestadual = $inscricaestadual;
	}

	public function setNome($nome) {
		$this->nome = $nome;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function setRua($rua) {
		$this->rua = $rua;
	}

	public function setBairro($bairro) {
		$this->bairro = $bairro;
	}

	public function setCep($cep) {
		$this->cep = $cep;
	}

	public function setNumero($numero) {
		$this->numero = $numero;
	}

	public function setCpf($cpf) {
		$this->cpf = $cpf;
	}

	public function setCnpj($cnpj) {
		$this->cnpj = $cnpj;
	}

	public function setTelefoneRes($telefoneRes) {
		$this->telefoneRes = $telefoneRes;
	}

	public function setTelefoneCel($telefoneCel) {
		$this->telefoneCel = $telefoneCel;
	}

	public function setTelefoneCom($telefoneCom) {
		$this->telefoneCom = $telefoneCom;
	}

	public function setMapeamentocidade($mapeamentocidade) {
		$this->mapeamentocidade = $mapeamentocidade;
	}

	public function setUsuariosusuarios($usuariosusuarios) {
		$this->usuariosusuarios = $usuariosusuarios;
	}



}
