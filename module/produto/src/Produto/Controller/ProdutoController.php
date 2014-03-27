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
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
#use Produto\Entity\ProdutoProdutos;
use Zend\Validator\File\Size;
use Zend\File\Transfer\Adapter\Http;
use Zend\Filter\File\Rename;

use Produto\Form\Produto as FrmProduto;

class ProdutoController extends AbstractActionController
{
    protected $categoriaValue;
    protected $subCategoriaValue;
    protected $uploadPath = 'public/images/produtos/';

    public function getCategoryValuesOptions ()
    {
        if (! $this->categoriaValue) {
            $repository = $this->getServiceLocator()->get("Produto\Repository\Categorias");
            
            $this->categoriaValue[""] = "--SELECIONE--";
            foreach ($repository->findAll() as $result) {
                $this->categoriaValue[$result->getIdcategorias()] = $result->getNome();
            }
        }
        return $this->categoriaValue;
    }

    public function getSubCategoryValuesOptions ()
    {
        if (! $this->subCategoriaValue) {
            $repository = $this->getServiceLocator()->get("Produto\Repository\SubCategorias");
            
            $this->subCategoriaValue[""] = "--SELECIONE--";
            foreach ($repository->findAll() as $result) {
                $this->subCategoriaValue[$result->getIdsubcategoria()] = $result->getNome();
            }
        }
        return $this->subCategoriaValue;
    }
    
    public function indexAction ()
    {
        $repositor = $this->getServiceLocator()->get("Produto\Repository\Produtos");
        $repositorCat = $this->getServiceLocator()->get("Produto\Repository\Categorias");
        
        $list = $repositor->findAll();
        $page = $this->params()->fromRoute('page');
        
        $paginator = new Paginator(new ArrayAdapter($list));
        $paginator->setCurrentPageNumber($page);
        $paginator->setDefaultItemCountPerPage(10);
        
        return new ViewModel(array(
            "categorias" => $repositorCat->findAll(),
            "produto" => $paginator
        ));
    }

    // ajax
    public function subCategoriaByCategoriaAction ()
    {
        $repositor = $this->getServiceLocator()->get("Produto\Repository\Categorias");
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $this->getRequest()->getPost('valor');
            
            $valueOptions = "<option value=''>--SELECIONE--</option>\n";
            foreach ($repositor->findByIdcategorias($data) as $categoria) {
                foreach ($categoria->getSubcategorias() as $subcategoria) {
                    $valueOptions .= "<option value='{$subcategoria->getIdsubcategoria()}'>{$subcategoria->getNome()}</option>\n";
                }
            }
        } else {
            $valueOptions = "Erro interno.";
        }
        
        $viewModel = new ViewModel(array(
            'mensagem' => $valueOptions
        )); // chama uma view
        $viewModel->setTerminal(true); // desativa layout.phtml
        return $viewModel;
    }

    public function adicionarAction()
    {               
        $form = new FrmProduto();
        $form->get('inputCategoria')->setValueOptions($this->getCategoryValuesOptions());
        
        $request = $this->getRequest();
        if ($request->isPost()) 
        {            
            $form->get('inputSubCategoria')->setValueOptions($this->getSubCategoryValuesOptions());
            
        	$noFile = $request->getPost()->toArray();
        	$File = $this->params()->fromFiles('foto');
        	$data = array_merge($noFile, array('foto' => $File[0]));        	
        	$form->setData($data);
                	
        	if ($form->isValid()) {
        		$size = new Size(array('max' => 2000000));
        		$adpter = new Http();
        		$adpter->setValidators(array($size), $File);
        
        		if (!$adpter->isValid()) {
                    $dataError = $adpter->getMessages();
                    $error = array();
                    foreach ($dataError as $erro) {
                        $error[] = $erro;
                    }
                    $form->setMessages(array('foto'=>$error));
                    #echo "<pre>", print_r($error), "</pre>";        		    
        		} else {
        
        			$diretorio = $request->getServer()->DOCUMENT_ROOT . '/lojaseleto/public/images/produtos/large';
        			$adpter->setDestination($diretorio);
        
        			$thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
        
        			foreach ($adpter->getFileInfo() as $file => $info) {
        				$name = $adpter->getFileName($file);
        				// fname = $diretorio . '/' . substr(md5(microtime()), 1, 4).$info['name'];
        				$fname = substr(md5(microtime()), 1, 4) . $info['name'];
        				$adpter->addFilter(new Rename(array(
        						"target" => $fname,
        						"randomize" => false
        				)), null, $file);
        
        				if ($adpter->receive($file)) {
        
        					$size = getimagesize($diretorio.'/'.$fname);
        					if($size[1] > 1000)
        					{
        						$resize = $thumbnailer->create('public/images/produtos/large/' . $fname, $options = array());
        						$resize->resize(1000);
        						$resize->save('public/images/produtos/large/'.$fname);
        					}
        					$names[] = $fname;
        				}
        			}
        
        			unset($data['foto']);
        			$data = array_merge($noFile, array('foto' => $names));
        		}
        
        		$service = $this->getServiceLocator()->get("Produto\Service\Produto");
        		$idproduto = $service->insert($data);
        		
        		return $this->redirect()->toRoute('admin-produto-home/admin-default', array('action' => 'imagens','id' => $idproduto));
        	}
        }
        
        
        return new ViewModel(array(
            'form' => $form,
            'getMessages' => (empty($message)) ? "" : $message
        ));
    }

    public function imagensAction()
    {
        $idproduto = $this->params()->fromRoute('id', 0);
        $repositor = $this->getServiceLocator()->get("Produto\Repository\Produtos");
        $produto = $repositor->findByIdproduto($idproduto);        
        
        return new ViewModel(array(
            'produto' => $produto
        ));
    }
    
    public function editarImagemAction()
    {
       $idproduto = $this->params()->fromRoute('id', 0);
       $repositor = $this->getServiceLocator()->get("Produto\Repository\Produtos");
       $produto = $repositor->findByIdproduto($idproduto);
       
       return new ViewModel(array(
       		'produto' => $produto
       ));
    }

    public function recortarAction()
    {
        $request = $this->getRequest();
        if($request->isPost())
        {
        	if($request->getPost("acao"))
        	{        	    
        	    $data = $request->getPost()->toArray();        	    
        	    $size = getimagesize("public/images/produtos/large/{$data['imagem']}");
        	    
        	    $thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
        	    $newimage = $thumbnailer->create('public/images/produtos/large/' . $data['imagem'], $options = array());
        	    $newimage->crop($data['x'],$data['y'],$data['w'],$data['h']);
        	    $newimage->save('public/images/produtos/large/'.$data['imagem']);
        	    
        	    
        	    $small = $thumbnailer->create('public/images/produtos/large/' . $data['imagem'], $options = array());
        	    $small->resize(212,159);
        	    $small->save('public/images/produtos/small/'.$data['imagem']);
        	    
        	    // ///////////////////////////////////////////////////////////////////////////////////////////////
        	    
        	    $thumbsmall = $thumbnailer->create('public/images/produtos/large/' . $data['imagem'], $options = array());
        	    $thumbsmall->resize(86,102);
        	    $thumbsmall->save('public/images/produtos/thumb_small/'.$data['imagem']);
        	    
        	    // ///////////////////////////////////////////////////////////////////////////////////////////////
        	    
        	    $thumb = $thumbnailer->create('public/images/produtos/large/' . $data['imagem'], $options = array());
        	    $thumb->resize(50,66);
        	    $thumb->save('public/images/produtos/thumb/'.$data['imagem']);
        	    
        	    return $this->redirect()->toRoute('admin-produto-home/admin-default', array('action' => 'imagens','id' => $data['idproduto']));
        	}
        	else 
        	{
        		$data['foto'] = $request->getPost("imagem");
        		$data['idproduto'] = $request->getPost("idproduto");
        		if($request->getPost("idprodutoImagem"))
        		{
        		    $data['idprodutoImagem'] = $request->getPost("idprodutoImagem");
        		}
        	}
        }
        
        return new ViewModel($data);
    }
    
    public function editarAction ()
    {
        $idproduto = $this->params()->fromRoute('id',0);
        $repositor = $this->getServiceLocator()->get("Produto\Repository\Produtos");
        $produto = $repositor->find($idproduto);
        
        
        $form = new FrmProduto();        
        $form->get('inputCategoria')->setValueOptions($this->getCategoryValuesOptions());
        $form->get('inputSubCategoria')->setValueOptions($this->getSubCategoryValuesOptions());
        
        $form->get('inputCategoria')->setAttribute('value', $produto->getProdutosubcategoria()->getCategorias()->getIdcategorias());
        $form->get('inputSubCategoria')->setAttribute('value', $produto->getProdutosubcategoria()->getIdsubcategoria());
        $form->get('id')->setAttribute('value', $produto->getIdproduto());
        $form->get('codigoProduto')->setAttribute('value', $produto->getCodigoProduto());
        $form->get('titulo')->setAttribute('value', $produto->getTitulo());
        $form->get('valor')->setAttribute('value', $produto->getValor());
        $form->get('peso')->setAttribute('value', $produto->getPeso());
        $form->get('comprimento')->setAttribute('value', $produto->getComprimento());
        $form->get('altura')->setAttribute('value', $produto->getAltura());
        $form->get('largura')->setAttribute('value', $produto->getLargura());
        $form->get('descricao')->setAttribute('value', $produto->getDescricao());
        $form->get('imformacaoNutricional')->setAttribute('value', $produto->getImformacaoNutricional());
        $form->get('complemento')->setAttribute('value', $produto->getComplemento());
        foreach ($produto->getEstoque() as $estoque)
        	$form->get('quantidade')->setAttribute('value', $estoque->getQuantidade());
        
        
        $request = $this->getRequest();
        if($request->isPost())
        {
            $data = $request->getPost()->toArray();
            
            $form->setData($data);
            if($form->isValid())
            {                   
            }
            else
            {
            	if(count($form->getMessages()) == 0)
            	{
            	   /* if($_FILES['foto']['error'][0] == 0)
            	    {
            	    
            	    }
            	    else
            	    {*/
            	        $service = $this->getServiceLocator()->get("Produto\Service\Produto");
            	        $service->update($data);
            	    //}
            	}
            }
            exit;
            return $this->redirect()->toRoute('admin-produto-home/admin-produto-gerenciar/admin-produto-gerenciar-edicao', array('id' => $idproduto));
        }
        
        return new ViewModel(array(
            'form'       =>    $form,
            'produto'    =>    $produto
        ));
    }

    public function excluirAction ()
    {
        $idproduto = $this->params()->fromRoute('id', 0);
        $repositor = $this->getServiceLocator()->get("Produto\Repository\Produtos");
        $produto = $repositor->find($idproduto);
        
        foreach ($produto->getEstoque() as $estoque) {
            $idprodutoestoque = $estoque->getIdcontroleestoque();
        }
        
        foreach ($produto->getImagens() as $imagens) {
        	if(count($produto->getImagens()) > 1) {
        	    $idprodutoimagem[] = $imagens->getIdprodutoImagens();
        	}
        	else {
        	    $idprodutoimagem = $imagens->getIdprodutoImagens();
        	}
        }
        
        $service = $this->getServiceLocator()->get("Produto\Service\Produto");
        if ($service->delete($idproduto, $idprodutoestoque, $idprodutoimagem)) {
            return $this->redirect()->toRoute($this->route, array('controller' => $this->controller));
        }
    }
    
}