<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Model\ArkDedicatedServer;

class CronController extends AbstractActionController
{
    public function generateStatusPageAction()
    {
        $serviceManager = $this->getServiceLocator();
        $config = $serviceManager->get('Config');
        if (array_key_exists('ark-survival-evolved', $config) && array_key_exists('status-page', $config['ark-survival-evolved'])) {
        	$statusPageConfig = $config['ark-survival-evolved']['status-page'];
        	$port = 27015;
        	if (array_key_exists('server-port', $statusPageConfig)) {
        		$port = $statusPageConfig['server-port'];
        	}
        	$arkServer = new ArkDedicatedServer($statusPageConfig['server-ip'], $port);
        	
        	
        } else {
        	copy(__DIR__ . '/../../../../../config/autoload/local.php.dist', __DIR__ . '/../../../../../config/autoload/local.php');
        }
    }
}
