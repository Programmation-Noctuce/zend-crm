<?php
namespace UserPass;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'userpass' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/user/pass[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\UserPassController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'userpass' => __DIR__ . '/../view',
        ],
    ],
];