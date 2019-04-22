<?php
namespace UserPass;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\UserPassTable::class => function($container) {
                    $tableGateway = $container->get(Model\UserPassTableGateway::class);
                    return new Model\UserPassTable($tableGateway);
                },
                Model\UserPassTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\UserPass());
                    return new TableGateway('userpass', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\UserPassController::class => function($container) {
                    return new Controller\UserPassController(
                        $container->get(Model\UserPassTable::class)
                    );
                },
            ],
        ];
    }
}