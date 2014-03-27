<?php

namespace Produto\Form;

use Zend\Form\Form;
use Zend\Form\Element\Hidden;   
use Doctrine\DBAL\Event\SchemaEventArgs;

class NutricionalItem extends Form {
	
	public function __construct($name = null) {
		parent::__construct("nutricionalitem");
		
		$this->setAttribute('method','post')
			 ->setAttribute('id','formNutricionalItem')
		     ->setAttribute('class', 'form-inline');
		
		$this->setInputFilter(new NutricionalItemFilter);
		
		$hidden = new Hidden('id');		
		$this->add($hidden);
		
		$this->add(array(
			'name' => 'nome',
			'options' => array(
				'type' => 'text',
			),
			'attributes' => array(
				'id' => 'nome',
				'placeholder' => 'Nome do item',
			)
		));
		
	}
		
	
}