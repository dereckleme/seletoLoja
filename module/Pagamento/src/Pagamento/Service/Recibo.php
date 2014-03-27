<?php 
namespace Pagamento\Service;

use Doctrine\ORM\EntityManager;
use Zend\Stdlib\Hydrator;
use Base\Service\AbstractService;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;


class Recibo extends AbstractService implements EventManagerAwareInterface{
    /**
     * @var EventManagerInterface
     */
    protected $events;
    
	public function __construct(EntityManager $em){
    	parent::__construct($em);
    	$this->entity = "Pagamento\Entity\PagamentoControlerecibo";
    }
    public function insert(array $data)
    { 
        $this->getEventManager()->trigger(__FUNCTION__, $this, $data);
    	$this->setTargetEntity(array(
    			array("setTargetEntity" => "Pagamento\Entity\UsuarioUsuarios",
    					"setCampo" => "setUsuariousuarios",
    					"setActionReference" => $data['idUsuario']),
    	    array("setTargetEntity" => "Pagamento\Entity\UsuarioCadastro",
    	    		"setCampo" => "setIdcad",
    	    		"setActionReference" => $data['Idcadastro']),
    	    
    	));
    	$data = parent::insert($data);
    	return $data;
    }
    public function update(array $data)
    {
    	$this->setTargetEntity(array(
    			array("setTargetEntity" => "Pagamento\Entity\PagamentoStatusFpagamento",
    					"setCampo" => "setFpagamento",
    					"setActionReference" => $data['SetfPagamento']),
    			array("setTargetEntity" => "Pagamento\Entity\PagamentoStatusSpagamento",
    					"setCampo" => "setSpagamento",
    					"setActionReference" => $data['Setspagamento']),
    	));
    	if($data['Setspagamento'] == 3) $data['status'] = 1;
    	parent::update($data);
    }
    
    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers(array(
            __CLASS__,
            get_class($this)
        ));
        $this->events = $events;
    }

    public function getEventManager()
    {
        if (!$this->events) {
            $this->setEventManager(new EventManager());
        }
        return $this->events;
    }
}
?>