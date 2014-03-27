<?php
namespace Produto\Form;

use Zend\InputFilter\InputFilter;

class NutricionalItemFilter extends InputFilter {
    
    public function __construct(){
    	
        $this->add(array(
            'name'       => 'nome',
            'required'   => true,
            'filters'    => array(
                array('name'=>'StripTags'),
                array('name'=>'StringTrim')
            ),
            'validators' => array(
                array(
                    'name'     => 'NotEmpty',
                    'options'  => array(
                        'messages' => array('isEmpty'=>'Favor preencher corretamente o campo Nome do Item.')
                    )    
                )
            )
        ));
       
    }
    
}