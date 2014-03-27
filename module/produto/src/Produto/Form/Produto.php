<?php

namespace Produto\Form;

use Zend\Form\Form;
use Zend\Form\Element\Select,
    Zend\Form\Element\Radio,
    Zend\Form\Element\Hidden,
    Zend\Form\Element\File,
    Zend\Form\Element\Textarea;
use Doctrine\DBAL\Event\SchemaEventArgs;

class Produto extends Form {
	
	public function __construct($name = null) {
		parent::__construct("produto");
		
		$this->setAttribute('method','post')
			 ->setAttribute('id','formProduto')
			 ->setAttribute('class', 'form-horizontal')
		     ->setAttribute('enctype', 'multipart/form-data');
		
		$this->setInputFilter(new ProdutoFilter);
		
		$hidden = new Hidden('ativo');
		$hidden->setValue('1');
		$this->add($hidden);
		
		$id_produto = new Hidden('id');
		$this->add($id_produto);
		
		$select = new Select('inputCategoria');
		$select->setAttribute('id', 'inputCategoria');
		$this->add($select);		
		
		$select2 = new Select('inputSubCategoria');
		$select2->setAttribute('id', 'inputSubCategoria');
		$select2->setValueOptions(array(''=>'--SELECIONE--'));
		$this->add($select2);
		
		$this->add(array(
			'name' => 'codigoProduto',
			'options' => array(
				'type' => 'text',
			),
			'attributes' => array(
					'placeholder' => 'Código Interno do Produto',
					'id' => 'codigoProduto'
			)
		));
		
		$this->add(array(
				'name' => 'titulo',
				'options' => array(
					'type' => 'text',
				),
				'attributes' => array(
					'placeholder' => 'Titulo',
					'id' => 'inputTitulo'
				)
		));
		
		$this->add(array(
				'name' => 'valor',
				'options' => array(
					'type' => 'text',
				),
				'attributes' => array(
					'placeholder' => 'R$ 0,00',
					'id' => 'inputValor'
				)
		));
		
		$this->add(array(
		    'name'    =>    'peso',
		    'options' =>    array(
		        'type'    =>    'text',
		    ),
		    'attributes'   =>    array(
		        'placeholder' => '0.000',
		        'id' => 'inputPeso'
		    )
		));
		
		$this->add(array(
			'name'    =>    'comprimento',
			'options' =>    array(
				'type'    =>    'text'
			),
			'attributes'   =>    array(
				'placeholder' => '0',
				'class'       => 'input-medium myConfig'
			)
		));
		
		$this->add(array(
			'name'    =>    'altura',
			'options' =>    array(
				'type'    =>    'text'
			),
			'attributes'   =>    array(
				'placeholder' => '0',
				'class'       => 'input-medium myConfig'
			)
		));
		
		$this->add(array(
			'name'    =>    'largura',
			'options' =>    array(
				'type'    =>    'text'
			),
			'attributes'   =>    array(
				'placeholder' => '0',
				'class'       => 'input-medium myConfig'
			)
		));

		$this->add(array(
			'name'    =>    'quantidade',
			'options' =>    array(
				'type'    =>    'text'
			),
			'attributes'   =>    array(
				'placeholder' => '0',
			)
		));
		
		$textarea = new Textarea('descricao');
		$textarea->setAttributes(array(
		    'row' => '3',
		    'id'  => 'descricao'
		));
		$this->add($textarea);
		
		$imformacaoNutricional = new Textarea('imformacaoNutricional');
		$imformacaoNutricional->setAttributes(array(
		    'row' => '3',
		    'id'  => 'imformacaoNutricional'
		));
		$this->add($imformacaoNutricional);
		
		$complemento = new Textarea('complemento');
		$complemento->setAttributes(array(
		    'row' => '3',
		    'id'  => 'complemento'
		));
		$this->add($complemento);
		
		$file = new File('foto');
		$file->setAttribute('multiple', true);
		$file->setAttribute('id', 'up_img');
		$this->add($file);
		
	}
		
	
}