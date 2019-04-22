<?php
namespace CRM;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            Controller\CRMController::class => InvokableFactory::class,
        ],
    ],

    'router' => [
        'routes' => [
            'crm' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/crm[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\CRMController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'crm' => __DIR__ . '/../view',
        ],
    ],
];