<?php
return array(
    'router' => array(
        'routes' => array(
            'carrinho' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/carrinho',
                    'defaults' => array(
                        'controller' => 'CarrinhoCompras\Controller\Index',
                        'action'     => 'list',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                		'carrinho-adiciona' => array(
                				'type'    => 'Segment',
                				'options' => array(
                						'route'    => '/:action',
                						'defaults' => array(
                						    
                						),
                				),
                		),
                ),
                
            ),  
            'carrinho-rest' => array(
            		'type' => 'Segment',
            		'options' => array(
            				'route' => '/carrinhoRest',
            				'defaults' => array(
            						'controller' => 'CarrinhoCompras\Controller\CarrinhoRest'
            				)
            		)
            ),
        ),
    ),
    'controllers' => array(
    		'invokables' => array(
    				'CarrinhoCompras\Controller\Index' => 'CarrinhoCompras\Controller\IndexController',
    		        'CarrinhoCompras\Controller\CarrinhoRest' => 'CarrinhoCompras\Controller\CarrinhoRestController'
    		),
    ),
    'view_manager' => array(
    		'display_not_found_reason' => true,
    		'display_exceptions'       => true,
    		'doctype'                  => 'HTML5',
    		'not_found_template'       => 'error/404',
    		'exception_template'       => 'error/index',
    		'template_map' => array(
    				'layout/layout'           => __DIR__ . '/../view/layout/empty.phtml',
    				'CarrinhoCompras/index/index' => __DIR__ . '/../view/CarrinhoCompras/index/index.phtml',
    				'error/404'               => __DIR__ . '/../view/error/404.phtml',
    				'error/index'             => __DIR__ . '/../view/error/index.phtml',
    		),
    		'template_path_stack' => array(
    				__DIR__ . '/../view',
    		),
        'strategies' => array(
        		'ViewJsonStrategy'
        )
    ),
);