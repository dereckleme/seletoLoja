<?php

namespace Usuario\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Storage\Session as SessionStorage;

class CadastroRestController extends AbstractRestfulController
{
    public function getList()
    {
    	return new JsonModel();
    }
    // Retornar o registro especifico - GET
    public function get($id)
    {
        return new JsonModel();
    }
    public function create($data)
    {
        $auth = new AuthenticationService;
        $auth->setStorage(new SessionStorage("Usuario"));
        if($auth->hasIdentity())
        {
            $service = $this->getServiceLocator()->get('Usuario\Service\Cadastro');
            $repository = $this->getServiceLocator()->get("Doctrine\ORM\EntityManager");
            $entity = $repository->getRepository('Usuario\Entity\UsuarioCadastro');
                $idEntityAlvo = $entity->findOneBy(array('usuariosusuarios' => $auth->getIdentity(),'ativo' => 1));
                $service->update(array("id" => $idEntityAlvo->getIdcadastro(), 'ativo'=> 0));
            $Cadastro = $service->insert($auth->getIdentity()->getIdusuario());
            $service->update(array("id" => $Cadastro->getIdcadastro(),
            		"cep" => $data['actionCep'],
            		"rua" => $data['actionRua'],
            		"numero" => $data['actionNumero'],
            		"bairro" => $data['actionBairro'],
            		"cidade" => $data['actionCidade'],
                    "ativo" => 1,
                    "padrao" => 0
            ));
        }
        return new JsonModel();
    }
    public function update($id, $data)
    {
        $auth = new AuthenticationService;
        $auth->setStorage(new SessionStorage("Usuario"));
        if($auth->hasIdentity())
        {
            $repository = $this->getServiceLocator()->get("Doctrine\ORM\EntityManager");
            $service = $this->getServiceLocator()->get('Usuario\Service\Cadastro');
            $entity = $repository->getRepository('Usuario\Entity\UsuarioCadastro');
               $verify = $entity->findOneBy(array('idcadastro' => $id));
                   if($verify->getUsuariosusuarios()->getIdusuario() == $auth->getIdentity()->getIdusuario())
                   {
            $idEntityAlvo = $entity->findOneBy(array('usuariosusuarios' => $auth->getIdentity(),'ativo' => 1));
            $service->update(array("id" => $idEntityAlvo->getIdcadastro(), 'ativo'=> 0));
            $service->update(array("id" => $id, 'ativo'=> 1));
                   }
        return new JsonModel();
        }
    }
    public function delete($id)
    {
        $auth = new AuthenticationService;
        $auth->setStorage(new SessionStorage("Usuario"));
        if($auth->hasIdentity())
        {
            $repository = $this->getServiceLocator()->get("Doctrine\ORM\EntityManager");
            $service = $this->getServiceLocator()->get('Usuario\Service\Cadastro');
            $entity = $repository->getRepository('Usuario\Entity\UsuarioCadastro');
            $verify = $entity->findOneBy(array('idcadastro' => $id));
            if($verify)
            {
                if($verify->getUsuariosusuarios()->getIdusuario() == $auth->getIdentity()->getIdusuario())
                {
                    $service = $this->getServiceLocator()->get('Usuario\Service\Cadastro');
                    if($verify->getAtivo() == 1)
                    {
                    $service->delete($id);
                    $idEntityAlvo = $entity->findOneBy(array('usuariosusuarios' => $auth->getIdentity(),'padrao' => 1));
                    $service->update(array("id" => $idEntityAlvo->getIdcadastro(), 'ativo'=> 1));
                    }
                    else {
                        $service->delete($id);
                    }
                    return new JsonModel(array("sucess" => "foi"));
                }
                else
                {
                	return new JsonModel(array("invalid" => "error"));
                }
            }
            else 
            {
            	return new JsonModel(array("invalid" => "error"));
            }
        }
    	
    }
}
