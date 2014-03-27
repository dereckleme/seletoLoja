<?php

namespace Produto\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\DBAL\Schema\View;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

class CategoriaController extends AbstractActionController
{

    public function indexAction()
    {
        $em = $this->getServiceLocator()->get("Doctrine\ORM\EntityManager");
        $repository = $em->getRepository("Produto\Entity\ProdutoCategorias");
        return new ViewModel(array("data" => $repository->findAll()));
    }
    public function insertAction()
    {
        $request = $this->getRequest();
        if($request->isPost())
        {
            $postData = $request->getPost()->toArray();
                if(!empty($postData['eventCategoria']))
                {
        	$service = $this->getServiceLocator()->get('Produto\Service\Categoria');
        	$service->insert(array("nome" => $postData['eventCategoria']));
        	$viewModel = new ViewModel(); // chama uma view
                }
                else 
                {
                    $viewModel = new ViewModel(array("mensagem" => "- Campo nome da categoria está em branco.")); // chama uma view
                }    
        }    
        $viewModel->setTerminal(true); // desativa layout.phtml
        return $viewModel;
    }
    public function insertSubAction()
    {
        $request = $this->getRequest();
        if($request->isPost())
        {
            $postData = $request->getPost()->toArray();
                if(!empty($postData['eventCategoria']) && !empty($postData['eventSubcategoria']))
                {
                    $service = $this->getServiceLocator()->get('Produto\Service\Subcategoria');
                    $service->insert(array("nome" => $postData['eventSubcategoria'], "eventCategoria" => $postData['eventCategoria']));
                    $viewModel = new ViewModel(); // chama uma view
                }else {
                    $viewModel = new ViewModel(array("mensagem" => "- Existe algum campo em branco.")); // chama uma view
                }
            
        }
        $viewModel->setTerminal(true); // desativa layout.phtml
        return $viewModel;
    }
    
    public function listaProdutosByCategoriaAction()
    {
        $categoriaRepositorio = $this->getServiceLocator()->get('Produto\Repository\Categorias');
        $listaCategoria = $categoriaRepositorio->findAll();
        $listaCategoriaSlug = $categoriaRepositorio->findByslug($this->params()->fromRoute('slug',0));
        
        $produtoRepositorio = $this->getServiceLocator()->get('Produto\Repository\Produtos');
        $produtoRepositorio->setSlugCategoria($this->params()->fromRoute('slug',0));
        
        $produtolist = $produtoRepositorio->findProdutoFor();
        $page = $this->params()->fromRoute('page');
        
        $paginator = new Paginator(new ArrayAdapter($produtolist));
        $paginator->setCurrentPageNumber($page);
        $paginator->setDefaultItemCountPerPage(10);
        
        return new ViewModel(array(
        		#"data"    =>    $produtolist,
                "data"     =>    $paginator,
        		"categorias"    =>    $listaCategoria,
                "categoriaSlug"    =>    $listaCategoriaSlug,
        		"slugCategoria"    =>    $this->params()->fromRoute('slug',0)
        ));
        
        /*$busca = $this->params()->fromRoute('slug',0);
        $em = $this->getServiceLocator()->get("Doctrine\ORM\EntityManager");
        $repository = $em->getRepository("Produto\Entity\ProdutoCategorias");        
        $categoriaBySlug = $repository->findByslug($busca);
        
        return new ViewModel(array(
            "data"=>$categoriaBySlug, 
            "categorias"=>$repository->findAll(), 
            "slugCategoria"=>$busca            
        ));*/
    }
    
    public function listaProdutosBySubcategoriaAction()
    {
        $categoriaRepositorio = $this->getServiceLocator()->get('Produto\Repository\Categorias');
        $listaCategoria = $categoriaRepositorio->findAll();
        $listaCategoriaSlug = $categoriaRepositorio->findByslug($this->params()->fromRoute('slug',0));
        
    	$produtoRepositorio = $this->getServiceLocator()->get('Produto\Repository\Produtos');
    	$produtoRepositorio->setSlugCategoria($this->params()->fromRoute('slug',0));
    	$produtoRepositorio->setSlugSubcategoria($this->params()->fromRoute('slugSub',0));
    	
    	$produtolist = $produtoRepositorio->findProdutoFor();    	
    	$page = $this->params()->fromRoute('page');
    	
    	$paginator = new Paginator(new ArrayAdapter($produtolist));
    	$paginator->setCurrentPageNumber($page);
    	$paginator->setDefaultItemCountPerPage(10);
    	
    	return new ViewModel(array(
    	    #'data'              =>    $produtolist,
    	    'data'              =>    $paginator,
    	    'categorias'        =>    $listaCategoria,
    	    'listaCategoriaSlug'=>    $listaCategoriaSlug,
    	    'slugCategoria'     =>    $this->params()->fromRoute('slug',0),
    	    'slugSubCategoria'  =>    $this->params()->fromRoute('slugSub',0)
    	));
    }
    
}

