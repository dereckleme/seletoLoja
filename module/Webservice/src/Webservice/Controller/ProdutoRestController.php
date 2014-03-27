<?php

namespace Webservice\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Storage\Session as SessionStorage;

class ProdutoRestController extends AbstractRestfulController
{
    public function getList()
    {
    	return new JsonModel(array("vaiiii","ok"));
    }
    public function get($id)
    {
    	return new JsonModel(array("aki","ok"));
    }
    public function update($id, $data)
    {
        if($data['user'] && $data['senha'])
        {
            // Criando Storage para gravar sessão da authtenticação
            $sessionStorage = new SessionStorage("Usuario");
            
                $auth = new AuthenticationService;
                $authAdapter = $this->getServiceLocator()->get("Usuario\Auth\Adapter");
                $authAdapter->setUsername($data['user']);
                $authAdapter->setPassword(md5($data['senha']));
                
                $result = $auth->authenticate($authAdapter);
                if($result->isValid())
                {
                    if($auth->getIdentity()['usuario']->getNivelUsuario() == 2)
                    {
                    	$service = $this->getServiceLocator()->get("Produto\Service\Produto");
                    	$em = $this->getServiceLocator()->get("Doctrine\ORM\EntityManager");
                    	$entity = $em->getRepository("Produto\Entity\ProdutoProdutos");
                    	    $produto = $entity->findOneBy(array("codigoProduto" => $id));
                    	    if($produto)
                    	    {
                    	        $service = $service->update(array("id" => $produto->getIdproduto(),"inputSubCategoria" => $produto->getProdutosubcategoria()->getIdsubcategoria(), "quantidade" => $data['saldo']));
                    	        return new JsonModel(array("mensagem" => "foi"));
                    	    }
                    	    else
                    	    {
                    	        return new JsonModel(array("mensagem" => "Produto nao encontrado"));
                    	    }
                    }
                    else
                    {
                        return new JsonModel(array("mensagem" => "usuario nao e administrador"));
                    }
                }
                else
                {
                    return new JsonModel(array("mensagem" => "usuario ou senha invalido"));
                }
        }
        else
        {
            return new JsonModel(array("mensagem" => "dados invalido"));
        }
       
    }
    public function delete($id)
    {
        return new JsonModel();
    }   
}

