<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Model\ArkDedicatedServer;
use Zend\View\Model\ViewModel;
use Zend\View\Resolver\TemplatePathStack;
use Zend\View\Renderer\PhpRenderer;

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
        	$serverInfo = [
        		'serverStatus' => $arkServer->getServerStatus(),
        		'game' => $arkServer->getGame(),
        		'serverVersion' => $arkServer->getServerVersion(),
        		'serverName' => $arkServer->getServerName(),
        		'passwordProtected' => $arkServer->getPasswordProtected(),
        		'map' => $arkServer->getMap(),
        		'playersOnline' => $arkServer->getPlayersOnline(),
        		'slots' => $arkServer->getSlots()
        	];
        	
        	$viewModel = new ViewModel($serverInfo);
        	$viewModel->setTemplate('application/index/status-page.phtml');
        	$viewModel->setTerminal(true);
        	$resolver = new TemplatePathStack();
        	$resolver->addPath(__DIR__ . '/../../../view');
        	
        	$renderer = new PhpRenderer();
        	$renderer->setResolver($resolver);
        	$html = $renderer->render($viewModel);
        	file_put_contents($statusPageConfig['target-directory'] . 'index.html', $html);
        } else {
        	copy(__DIR__ . '/../../../../../config/autoload/local.php.dist', __DIR__ . '/../../../../../config/autoload/local.php');
        }
    }
}
