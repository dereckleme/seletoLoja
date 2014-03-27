<?php
namespace Webservice;
use Zend\Mvc\MvcEvent;
class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    public function onBootstrap(MvcEvent $event)
    {
    	$eventManager       = $event->getApplication()->getEventManager();
    	$sharedEventManager = $eventManager->getSharedManager();
        /*
    	$sharedEventManager->attach('Pagamento\Service\Recibo', 'insert', function($e) {
    	    
    	}, 100);
    	*/
    }
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
}
