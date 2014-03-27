<?php
return array(
    'router' => array(
        'routes' => array(
            'produto-webservice-rest' => array(
            		'type' => 'Segment',
            		'options' => array(
            				'route' => '/webservice/Estoque[/:id]',
            				'defaults' => array(
            						'controller' => 'Webservice\Controller\ProdutoRestEstoque'
            				)
            		)
            ),  
        ),
    ),
    'view_manager' => array(
    		'strategies' => array(
    				'ViewJsonStrategy'
    		)
    ),
    'controllers' => array(
    		'invokables' => array(
    		        'Webservice\Controller\ProdutoRestEstoque' => 'Webservice\Controller\ProdutoRestController'
    		),
    ),
);