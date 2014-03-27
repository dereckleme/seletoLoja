<?php

namespace Produto\Form;

use Zend\Form\Form;
use Zend\Form\Element\Select,
    Zend\Form\Element\Hidden;   
use Doctrine\DBAL\Event\SchemaEventArgs;

class Nutricional extends Form {
	
	public function __construct($name = null) {
		parent::__construct("nutricional");
		
		$this->setAttribute('method','post')
			 ->setAttribute('id','formNutricional')
			 #->setAttribute('class', 'form-horizontal');
		     ->setAttribute('class', 'form-inline');
		
		$this->setInputFilter(new NutricionalFilter);
		
		$hidden = new Hidden('id');		
		$this->add($hidden);
		
		$select = new Select('idnutricionalNomes');
		$select->setAttribute('id', 'idnutricionalNomes');
		$select->setAttribute('style', 'margin-left:10px;');
		$this->add($select);		
		
		$select2 = new Select('idproduto');
		$select2->setAttribute('id', 'idproduto');		
		$this->add($select2);
		
		
		$this->add(array(
			'name' => 'quantidade',
			'options' => array(
				'type' => 'text',
			),
			'attributes' => array(
				'id' => 'quantidade',
			    'placeholder' => 'Quantidade',
			    'style' => 'margin-left:10px;'
			)
		));
		
		$this->add(array(
			'name' => 'vd',
			'options' => array(
				'type' => 'text',
			),
			'attributes' => array(
				'id' => 'vd',
			    'placeholder' => 'Valor Diário',
			    'style' => 'margin-left:10px;'
			)
		));
		
		$this->add(array(
			'name' => 'produtos',
			'options' => array(
				'type' => 'text',
			),
			'attributes' => array(
				'id' => 'produtos',
			    'placeholder' => '',
				'disabled' => 'disabled',
			)
		));
		
	}
		
	
}