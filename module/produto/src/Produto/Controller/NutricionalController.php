<?php

/**
 * dereck
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Produto\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Produto\Form\Nutricional;
use Produto\Form\NutricionalItem;

class NutricionalController extends AbstractActionController {
    
    protected $itens;
    protected $produtos;
    
    public function setItensValues($items){
        if(!$this->itens){            
            #$this->itens[''] = '--SELECIONE--';
            $this->itens[''] = '--ITENS--';
            foreach ($items as $item){
            	$this->itens[$item->getIdnutricionalNomes()] = $item->getNome();
            }	
        }        
        return $this->itens;
    }
    
    public function setProdutosValues(){
    	if(!$this->produtos){
    		$repository = $this->getServiceLocator()->get('Produto\Repository\Produtos');
    		#$this->produtos[''] = '--SELECIONE--';
    		$this->produtos[''] = '--PRODUTO--';
    		foreach ($repository->findAll() as $produto){
    			$this->produtos[$produto->getIdproduto()] = $produto->getTitulo();
    		}
    	}
    	return $this->produtos;
    }
           
    public function indexAction() {
        $repository = $this->getServiceLocator()->get('Produto\Repository\Nutricional');
        $data['itens'] = $repository->findAll();
        
        $repository2 = $this->getServiceLocator()->get('Produto\Repository\NutricionalTabela');
        $data['tableNutricional'] = $repository2->listNutricionalForProdutos();
        
        $keys = array(); 
        $values = array();
        foreach($data['tableNutricional'] as $items)
        {            
        	if(!in_array($items->getProdutoproduto()->getIdproduto(), $keys))
        	    array_push($keys, $items->getProdutoproduto()->getIdproduto());
        }        
        
        for ($i=1; $i<=count($keys); $i++){
        	if($i%2 == 0){
        	    array_push($values, "warning");
        	}else{
        	    array_push($values, "info");
        	}
        }        
        $data['cores'] = array_combine($keys, $values);
        
        $formulario = new Nutricional();
        $formulario->get('idnutricionalNomes')->setValueOptions($this->setItensValues($data['itens']));
        $formulario->get('idproduto')->setValueOptions($this->setProdutosValues());
        $data['form'] = $formulario;
        
        return new ViewModel($data);
    }

    public function inserirlinhaAction(){
        $repository = $this->getServiceLocator()->get('Produto\Repository\Nutricional');
        $data['itens'] = $repository->findAll();
        
        $repository2 = $this->getServiceLocator()->get('Produto\Repository\Produtos');
        $produto = $repository2->find($this->params()->fromPost('produto'));
        $data['nomeproduto'] = $produto->getTitulo();
        
        $viewModel = new ViewModel($data); // chama uma view
        $viewModel->setTerminal(true); // desativa layout.phtml
        return $viewModel;
    }
    
    public function adicionarItemAction()
    {
        $formulario = new NutricionalItem();
        
        $request = $this->getRequest();
        if($request->isPost())
        {
        	$data = $request->getPost()->toArray();        	
        	$formulario->setData($data);
        	if($formulario->isValid()) 
        	{
        		$service = $this->getServiceLocator()->get('Produto\Service\Nutricional');
        		$service->insert($data);
        		$viewModel = new ViewModel();
        	} 
        	else {
        	    $error = $formulario->getMessages('nome');
        	    $viewModel = new ViewModel(array("mensagem" => $error['isEmpty']));        		
        	}
        }
        $viewModel->setTerminal(true);
        return $viewModel;
    }
    
    public function criarTabelaNutricionalAction()
    {
        $indices = array("idproduto","idnutricionalNomes","quantidade","vd");
        
        $request = $this->getRequest();
        if($request->isPost()) 
        {
            $info = $request->getPost()->toArray();
            $error = null;
            $i = 0;
            $j = 0;
            foreach ($info['matriz'] as $valores) {
                $data = array_combine($indices, $valores);
                foreach ($data as $key => $value)
                {
                	switch ($key)
                	{
                		case "idproduto": 
                		    if($value == "")
                		    {
                		    	$error[$i][$j] = "Selecione um produto corretamente.";
                		    }
                		break;
                		
                		case "idnutricionalNomes": 
                		    if($value == "")
                		    {
                		    	$error[$i][$j] = "Selecione um item corretamente.";
                		    }
                		break;
                		
                		case "quantidade": 
                		    if($value == "")
                		    {
                		    	$error[$i][$j] = "Favor preencher corretamente o campo quantidade.";
                		    }
                		break;
                		
                		case "vd": 
                		    if($value == "")
                		    {
                		    	$error[$i][$j] = "Favor preencher corretamente o campo valor diário.";
                		    }
                		break;
                	} 
                	$j++;                   
                }
                $i++;
            }
            
            if(count($error) == 0)
            {
                $service = $this->getServiceLocator()->get('Produto\Service\NutricionalTabela');
                foreach ($info['matriz'] as $campos)
                {
                	$service->insert($campos);
                }
                $viewModel = new ViewModel();
            }
            else 
            {
                $viewModel = new ViewModel(array('erros'=>'Há campos que devem ser preenchidos.'));
            }
        }
        
        $viewModel->setTerminal(true);
        return $viewModel;        
    }

    public function editarTabelaNutricionalAction()
    {
        $id = $this->params()->fromRoute('id',0);
        $data['idProdutoNutricional'] = $id;
        
        $repositoryNomes = $this->getServiceLocator()->get('Produto\Repository\Nutricional');
        $itens = $repositoryNomes->findAll();
         
    	$repository = $this->getServiceLocator()->get('Produto\Repository\NutricionalTabela');
    	$tabela = $repository->find($id);
    	
    	$formulario = new Nutricional();
    	$formulario->setAttribute('class', 'form-horizontal');
    	$formulario->get('idnutricionalNomes')->setValueOptions($this->setItensValues($itens));
    	$formulario->get('idproduto')->setValueOptions($this->setProdutosValues());
    	
    	$request = $this->getRequest();
    	if($request->isPost())
    	{
    		$data = $request->getPost()->toArray();
    		$formulario->setData($data);
    		if($formulario->isValid())
    		{
    		    $service = $this->getServiceLocator()->get('Produto\Service\NutricionalTabela');
    		    $service->update($data);

    		    return $this->redirect()->toRoute('admin-produto-home/admin-produto-nutricional');
    		}
    	}
        
    	$formulario->get('id')->setAttribute('value', $id);
    	$formulario->get('idnutricionalNomes')->setAttribute('value', $tabela->getProdutonutricionalNomes()->getIdnutricionalNomes());
    	$formulario->get('idproduto')->setAttributes(array('value'=>$tabela->getProdutoproduto()->getIdproduto(), 'style'=>'margin-left:10px;'));
    	$formulario->get('quantidade')->setAttribute('value', $tabela->getQuantidade());
    	$formulario->get('vd')->setAttribute('value', $tabela->getVd());
    	$data['form'] = $formulario;
        
        return new ViewModel($data);
    }
    
    public function editarItemAction()
    {
        $id = $this->params()->fromRoute('id',0);
        
        $repository = $this->getServiceLocator()->get('Produto\Repository\Nutricional');
        $item = $repository->find($id);
        
        $formulario = new NutricionalItem();
        $formulario->setAttribute('class', 'form-horizontal');
        
        $request = $this->getRequest();
        if($request->isPost())
        {
        	$data = $request->getPost()->toArray();
        	$formulario->setData($data);
        	if($formulario->isValid())
        	{       		
        		$service = $this->getServiceLocator()->get('Produto\Service\Nutricional');
        		$service->update($data);
        		 
        		return $this->redirect()->toRoute('admin-produto-home/admin-produto-nutricional');
        	}
        }
        
        $formulario->get('id')->setAttribute('value', $item->getIdnutricionalNomes());
        $formulario->get('nome')->setAttribute('value', $item->getNome());
        $data['form'] = $formulario;
        $data['idNutricionalNome'] = $item->getIdnutricionalNomes();
        
        return new ViewModel($data);
    }
    
    public function excluirItemAction()
    {
        $info = array();
        $id = $this->params()->fromRoute('id',0);
        
        $repository = $this->getServiceLocator()->get('Produto\Repository\NutricionalTabela');
        $tabelas = $repository->findByProdutonutricionalNomes($id);
        foreach ($tabelas as $tabela) {
        	 $info[] = $tabela->getIdprodutoNutricional();
        }
        
        $service = $this->getServiceLocator()->get('Produto\Service\Nutricional');
        $service->setIdtabelanutricional($info);
        if ($service->delete($id)) {
        	return $this->redirect()->toRoute($this->route, array('controller' => $this->controller));
        }
    }
    
    public function excluirTabelaNutricionalAction()
    {
        $service = $this->getServiceLocator()->get('Produto\Service\NutricionalTabela');
        if ($service->delete($this->params()->fromRoute('id',0))) {
        	return $this->redirect()->toRoute($this->route, array('controller' => $this->controller));
        }
    }
    
}