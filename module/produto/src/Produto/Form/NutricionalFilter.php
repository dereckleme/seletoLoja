<?php
namespace Produto\Form;

use Zend\InputFilter\InputFilter;

class NutricionalFilter extends InputFilter {
    
    public function __construct(){
    	
        /*$this->add(array(
            'name'    => 'idprodutoNutricional',
            'required'   => true,
            'filters'    => array(
        		array('name'=>'Int')
            )        
        ));

        $this->add(array(
        	'name'    => 'idnutricionalNomes',
        	'required'   => true,
        	'filters'    => array(
        		array('name'=>'Int')
        	)            
        ));
        
        $this->add(array(
    		'name'    => 'idproduto',
    		'required'   => true,
    		'filters'    => array(
				array('name'=>'Int')
    		)          
        ));*/
        
        $this->add(array(
            'name'       => 'quantidade',
            'required'   => true,
            'filters'    => array(
                array('name'=>'StripTags'),
                array('name'=>'StringTrim')
            ),
            'validators' => array(
                array(
                    'name'     => 'NotEmpty',
                    'options'  => array(
                        'messages' => array('isEmpty'=>'Favor preencher corretamente o campo quantidade')
                    )    
                )
            )
        ));
        
       $this->add(array(
    		'name'       => 'vd',
    		'required'   => true,
    		'filters'    => array(
				array('name'=>'StripTags'),
				array('name'=>'StringTrim')
    		),
           'validators' => array(
           		array(
       				'name'     => 'NotEmpty',
       				'options'  => array(
   						'messages' => array('isEmpty'=>'Favor preencher corretamente o campo valor diário')
       				)
           		)
           )
        ));
        
    }
    
}