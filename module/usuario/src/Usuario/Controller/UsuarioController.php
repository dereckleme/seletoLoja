<?php

/**
 * dereck
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Usuario\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


use Zend\Authentication\AuthenticationService,
Zend\Authentication\Storage\Session as SessionStorage;

class UsuarioController extends AbstractActionController
{
    public function indexAction()
    {
        $estados = null;
        $cidades = null;
        $cidadeEstadoUF = null;
        $cidadeSelected = null;
        
        $auth = new AuthenticationService;
        $auth->setStorage(new SessionStorage("Usuario"));
        if($auth->hasIdentity())
        {
           
            $em = $this->getServiceLocator()->get("Doctrine\ORM\EntityManager");
            $repoRecibo = $em->getRepository("Pagamento\Entity\PagamentoControlerecibo");
            $recibos = $repoRecibo->findByusuariousuarios($auth->getIdentity()->getIdusuario());
            
            $repo = $em->getRepository("Usuario\Entity\UsuarioCadastro");
            $entityAlternativos = $em->getRepository("Usuario\Entity\UsuarioCadastro")->findBy(array("usuariosusuarios" => $auth->getIdentity()->getIdusuario(),"padrao" => "0"));
            $estados = $em->getRepository("Usuario\Entity\MapeamentoEstado")->findAll();
            $entity = $repo->findOneBy(array("usuariosusuarios" => $auth->getIdentity()->getIdusuario(), "padrao" => 1));
          
            if($entity->getMapeamentocidade())
            {
            	$cidadeEstadoUF = $entity->getMapeamentocidade()->getMapeamentoestado()->getNomeclatura();
            	$cidadeSelected = $entity->getMapeamentocidade()->getIdcidade();
            	$cidades = $em->getRepository("Usuario\Entity\MapeamentoCidade")->findBynomeclatura($cidadeEstadoUF);
            }
            return new ViewModel(array("estados" => $estados,"cidades" => $cidades,"cidadeEstadoUF" => $cidadeEstadoUF,"cidadeSelected" => $cidadeSelected,"usuario" => $auth->getIdentity(), "cadastro" => $entity, "recibos" => $recibos,'cadastrosAlternativos' => $entityAlternativos));
        }
    }
    public function pedidoAction()
    {
        $auth = new AuthenticationService;
        $auth->setStorage(new SessionStorage("Usuario"));
        if($auth->hasIdentity())
        {
            $idUser = $auth->getIdentity()->getIdusuario();
            $param = $this->params()->fromRoute("idPedido");
            $em = $this->getServiceLocator()->get("Doctrine\ORM\EntityManager");
            $repoRecibo = $em->getRepository("Pagamento\Entity\PagamentoControlerecibo");
            $cadastro = $em->getRepository("Usuario\Entity\UsuarioCadastro")->findOneBy(array("usuariosusuarios" => $auth->getIdentity()));
            $recibo = $repoRecibo->findOneByidcontrolerecibo($param/3500);
               if(count($recibo) != 0)
               {
                   if($recibo->getUsuariousuarios()->getIdusuario() == $idUser)
                   {
                       //$cadastroPrincipal = $repoRecibo->findOneBy(array("usuariousuarios" => $auth->getIdentity()->getIdusuario(), ""));
                       return new viewModel(array("data" => $recibo,"cadastroPrincipal" => $cadastro));
                   }
                   else
                   {
                       return $this->redirect()->toRoute('Painel-usuario');
                   }
               }  
               else {
                   return $this->redirect()->toRoute('Painel-usuario');
               }
            
        }
        else 
        {
            return $this->redirect()->toRoute('Painel-usuario');
        }
    }
    public function statusAction()
    {
        $auth = new AuthenticationService;
        $auth->setStorage(new SessionStorage("Usuario"));
        if($auth->hasIdentity())
        {
            $params = $this->params()->fromRoute("idPedido");
            
        $status = null;    
            $em = $this->getServiceLocator()->get("Doctrine\ORM\EntityManager");
            $repoRecibo = $em->getRepository("Pagamento\Entity\PagamentoControlerecibo");
            $recibo = $repoRecibo->findOneByidcontrolerecibo($params);
            if($recibo)
            {
            if($recibo->getSpagamento())
            {
                $status = $recibo->getSpagamento()->getTitulo();
            }
            }
    	 $viewModel = new ViewModel(array('message' => $status));
    	 $viewModel->setTerminal(true);
    	 return $viewModel;
        } 
    }
    public function novoUserAction()
    {
        $error = array();
        if($this->request->isPost())
        {
            
            $em = $this->getServiceLocator()->get("Doctrine\ORM\EntityManager");
            $entity = $em->getRepository("Usuario\Entity\UsuarioUsuarios");
            $requestArray = $this->getRequest()->getPost()->toArray();
                if(count($entity->findOneByLogin($requestArray['eventLogin'])) != 0 || empty($requestArray['eventLogin'])) $error[] = "- Login usado já existe, ou está em branco;<br/>";
                if(count($entity->findOneByEmail($requestArray['eventEmail'])) != 0 || empty($requestArray['eventEmail'])) $error[] = "- Email já está cadastrado, ou está em branco;<br/>";
                if(empty($requestArray['eventPassword']) || ($requestArray['eventPassword'] != $requestArray['eventPasswordConfirm'])) $error[] = "- Senha cadastrada está em branco ou não coincide;";

                if(count($error) == 0)
                    {
                        $service = $this->getServiceLocator()->get('Usuario\Service\Usuario');
                        $action = $service->insert(array('login' => $requestArray['eventLogin'], 'email' => $requestArray['eventEmail'], 'senha' => md5($requestArray['eventPassword'])));
                            $idUsuario = $action->getIdusuario();
                            $service = $this->getServiceLocator()->get('Usuario\Service\Cadastro');
                            $service->insert($idUsuario);
                            $viewModel = new ViewModel(array('message' => 1));
                    }
                    else
                    {
                        $viewModel = new ViewModel(array('message' => implode($error)));
                    }
        }
        $viewModel->setTerminal(true);
        return $viewModel;
    }
    public function editaUserAction()
    {
        $teste = $this->getServiceLocator()->get("Produto\Repository\SubCategorias")->findAll();
        foreach ($teste as $dereck)
        {
        	print $dereck->getNome();
        }
        die();
    }

    public function atualizaAction()
    {
        $auth = new AuthenticationService;
        $auth->setStorage(new SessionStorage("Usuario"));
        $error = null;
        if($auth->hasIdentity())
        {
            if($this->request->isPost())
            {
                $requestArray = $this->getRequest()->getPost()->toArray();
                $em = $this->getServiceLocator()->get("Doctrine\ORM\EntityManager");
                    $entity = $em->getRepository("Usuario\Entity\UsuarioCadastro")->findOneByusuariosusuarios($auth->getIdentity()->getIdusuario());
                    $service = $this->getServiceLocator()->get('Usuario\Service\Cadastro');
                    if(isset($requestArray["actiontipoUsuario"]))
                    {    
                        if($requestArray["typeUpdate"] == 1)
                        {    
                            if($requestArray["actiontipoUsuario"] == 1)
                            {
                                if(empty($requestArray['actionNome'])) $error .= "- Nome do Destinatário<br/>";
                                if(empty($requestArray['actioncpfCnpj'])) $error .= "- Informe seu CPF ou CNPJ<br/>";
                                if(empty($requestArray['actiontelRes'])) $error .= "- Telefone Residencial";
                                    if(empty($error))
                                    {
                                        $service->update(array("id" => $entity->getIdcadastro(),
                                        		"tipoUser" => $requestArray["actiontipoUsuario"],
                                        		"nome" => $requestArray['actionNome'],
                                        		"cpf" => $requestArray["actioncpfCnpj"],
                                                "telefoneRes" => $requestArray["actiontelRes"],
                                                "telefoneCel" => $requestArray["actiontelCel"],
                                        ));
                                        $viewModel = new ViewModel(array('message' =>null));
                                    }
                                    else 
                                    {
                                        $viewModel = new ViewModel(array('message' =>$error));
                                    }
                            }
                            else if($requestArray["actiontipoUsuario"] == 2)
                            {
                                
                            }
                        }
                        else if($requestArray["typeUpdate"] == 2)
                        {
                            if($requestArray["actiontipoUsuario"] == 1)
                            {
                                if(empty($requestArray['actionNome'])) $error .= "- Nome do Destinatário<br/>";
                                if(empty($requestArray['actioncpfCnpj'])) $error .= "- Informe seu CPF ou CNPJ<br/>";
                                if(empty($requestArray['actiontelRes'])) $error .= "- Telefone Residencial<br/>";
                                if(empty($requestArray['actionCep'])) $error .= "- Número do CEP<br/>";
                                if(empty($requestArray['actionRua'])) $error .= "- Endereço de entrega<br/>";
                                if(empty($requestArray['actionNumero'])) $error .= "- Numero<br/>";
                                if(empty($requestArray['actionBairro'])) $error .= "- Digite seu bairro<br/>";
                                if(empty($requestArray['actionCidade'])) $error .= "- Selecione sua cidade<br/>";
                                    if(empty($error))
                                    {
                                        $service->update(array("id" => $entity->getIdcadastro(),
                                        		"tipoUser" => $requestArray["actiontipoUsuario"],
                                        		"nome" => $requestArray['actionNome'],
                                        		"cep" => $requestArray['actionCep'],
                                        		"rua" => $requestArray['actionRua'],
                                        		"numero" => $requestArray['actionNumero'],
                                        		"bairro" => $requestArray['actionBairro'],
                                        		"cidade" => $requestArray['actionCidade'],
                                        		"cpf" => $requestArray["actioncpfCnpj"],
                                                "telefoneRes" => $requestArray["actiontelRes"],
                                                "telefoneCel" => $requestArray["actiontelCel"],
                                        ));
                                        $viewModel = new ViewModel(array('message' =>null));
                                    }
                                    else
                                    {
                                        $viewModel = new ViewModel(array('message' =>$error));
                                    }
                            }
                        }
                 } 
                 else if($requestArray["typeUpdate"] == 3)
                 {
                     $entity = $em->getRepository("Usuario\Entity\UsuarioCadastro")->findOneByidcadastro($requestArray['idCadastro']);
                     
                    if($entity->getUsuariosusuarios()->getIdusuario() == $auth->getIdentity()->getIdusuario())
                    {
                            if(empty($requestArray['actionCep'])) $error .= "- Número do CEP<br/>";
                     		if(empty($requestArray['actionRua'])) $error .= "- Endereço de entrega<br/>";
                     		if(empty($requestArray['actionNumero'])) $error .= "- Numero<br/>";
                     		if(empty($requestArray['actionBairro'])) $error .= "- Digite seu bairro<br/>";
                     		if(empty($requestArray['actionCidade'])) $error .= "- Selecione sua cidade<br/>";
                     		if(empty($error))
                     		{
                     			$service->update(array("id" => $requestArray['idCadastro'],
                     					"cep" => $requestArray['actionCep'],
                     					"rua" => $requestArray['actionRua'],
                     					"numero" => $requestArray['actionNumero'],
                     					"bairro" => $requestArray['actionBairro'],
                     					"cidade" => $requestArray['actionCidade'],
                     			));
                     			$viewModel = new ViewModel(array('message' =>null));
                     		}
                     		else
                     		{
                     			$viewModel = new ViewModel(array('message' =>$error));
                     		} 	
                    } 		
                    else
                    {
                        $viewModel = new ViewModel(array('message' =>"Endereço Inválido"));
                    }	
                 } 
                 else {
                     $viewModel = new ViewModel(array('message' => "Selecione pessoa física ou pessoa jurídica"));
                 }        
            }
            else 
            {
                $viewModel = new ViewModel(array('message' => 0));
            }    
        }
        else
        {
            $viewModel = new ViewModel(array('message' => 0));
        }   
    	
    	$viewModel->setTerminal(true);
    	return $viewModel;
    }
    public function ativarUserAction()
    {
    	
    }
    public function editarCadastroRequestAction()
    {
        $repository = $this->getServiceLocator()->get("Doctrine\ORM\EntityManager");
        $data = array();
        $auth = new AuthenticationService;
        $auth->setStorage(new SessionStorage("Usuario"));
        $requestArray = $this->getRequest()->getPost()->toArray();
        $estados = $repository->getRepository("Usuario\Entity\MapeamentoEstado")->findAll();
        if($auth->hasIdentity() && !empty($requestArray['idCadastro']))
        {
            $entity = $repository->getRepository('Usuario\Entity\UsuarioCadastro');
            $idEntityAlvo = $entity->findOneBy(array('usuariosusuarios' => $auth->getIdentity(),'idcadastro' => $requestArray['idCadastro']));
            $data = $idEntityAlvo;
            if($idEntityAlvo->getMapeamentocidade())
            {
            	$cidadeEstadoUF = $idEntityAlvo->getMapeamentocidade()->getMapeamentoestado()->getNomeclatura();
            	$cidadeSelected = $idEntityAlvo->getMapeamentocidade()->getIdcidade();
            	$cidades = $repository->getRepository("Usuario\Entity\MapeamentoCidade")->findBynomeclatura($cidadeEstadoUF);
            }
            $viewModel = new ViewModel(array("data" => $data,"estados" => $estados,"cidades" => $cidades,"cidadeEstadoUF" => $cidadeEstadoUF,"cidadeSelected" => $cidadeSelected));
        }
        else {
        $viewModel = new ViewModel(array("data" => $data,"estados" => $estados));
        }
        $viewModel->setTerminal(true);
        return $viewModel;
    }
}
