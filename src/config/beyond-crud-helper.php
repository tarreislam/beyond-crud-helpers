<?php

return [
    /*
     * File and path settings
     */
    'tenants' => [
        'controller' => [
            'home' => 'app',
            'relativePath' => 'Controllers',
            'prefix' => '',
            'suffix' => 'Controller',
            'extends' => null,
        ],
        'request' => [
            'home' => 'app',
            'relativePath' => 'Requests',
            'prefix' => '',
            'suffix' => 'Request',
            'extends' => \Illuminate\Foundation\Http\FormRequest::class,
        ],
        'resource' => [
            'home' => 'app',
            'relativePath' => 'Resources',
            'prefix' => '',
            'suffix' => 'Resource',
            'extends' => \Illuminate\Http\Resources\Json\JsonResource::class,
        ],
        'resourceCollection' => [
            'home' => 'app',
            'relativePath' => 'Resources',
            'prefix' => '',
            'suffix' => 'ResourceCollection',
            'extends' => \Illuminate\Http\Resources\Json\ResourceCollection::class,
        ],
        'policy' => [
            'home' => 'app',
            'relativePath' => 'Policies',
            'prefix' => '',
            'suffix' => 'Policy',
            'extends' => null,
        ],
        'query' => [
            'home' => 'app',
            'relativePath' => 'Queries',
            'prefix' => '',
            'suffix' => 'Query',
            'extends' => null,
        ],
        'rule' => [
            'home' => 'app',
            'relativePath' => 'Rules',
            'prefix' => '',
            'suffix' => 'Rule',
            'extends' => null,
        ],
        'exception' => [
            'home' => 'domain',
            'relativePath' => 'Exceptions/',
            'prefix' => '',
            'suffix' => 'Exception',
            'extends' => \Exception::class,
        ],
        'action' => [
            'home' => 'domain',
            'relativePath' => 'Actions',
            'prefix' => '',
            'suffix' => 'Action',
            'extends' => null,
        ],
        'dto' => [
            'home' => 'domain',
            'relativePath' => 'DataTransferObjects',
            'prefix' => '',
            'suffix' => 'DTO',
            'extends' => \Spatie\DataTransferObject\DataTransferObject::class,
        ],
        'dtoCollection' => [
            'home' => 'domain',
            'relativePath' => 'DataTransferObjects',
            'prefix' => '',
            'suffix' => 'CollectionDTO',
            'extends' => \Spatie\DataTransferObject\DataTransferObjectCollection::class,
        ],
        'event' => [
            'home' => 'domain',
            'relativePath' => 'Events',
            'prefix' => '',
            'suffix' => 'Event',
            'extends' => null,
        ],
        'job' => [
            'home' => 'domain',
            'relativePath' => 'Jobs',
            'prefix' => '',
            'suffix' => 'Job',
            'extends' => null,
        ],
        'model' => [
            'home' => 'domain',
            'relativePath' => 'Models',
            'prefix' => '',
            'suffix' => '',
            'extends' => \Illuminate\Database\Eloquent\Model::class,
        ],
        'notification' => [
            'home' => 'domain',
            'relativePath' => 'Notifications',
            'prefix' => '',
            'suffix' => '',
            'extends' => \Illuminate\Notifications\Notification::class,
        ],
        'observer' => [
            'home' => 'domain',
            'relativePath' => 'Observers',
            'prefix' => '',
            'suffix' => 'Observer',
            'extends' => null,
        ],
        'migration' => [
            'home' => 'migrations',
            'relativePath' => '',
            'prefix' => '',
            'suffix' => '',
            'extends' => \Illuminate\Database\Migrations\Migration::class
        ],
        'storage' => [
            'home' => 'storage',
            'relativePath' => '',
            'prefix' => '',
            'suffix' => '',
            'extends' => null,
        ]
    ],
    /*
     * Where tenants can live. available variable: {name}
     */
    'homes' => [
        'app' => [
            'dir' => 'app/App/{name}',
            'namespace' => 'App\\{name}',
        ],
        'domain' => [
            'dir' => 'app/Domain/{name}',
            'namespace' => 'Domain\\{name}',
        ],
        'support' => [
            'dir' => 'app/Support',
            'namespace' => 'Support',
        ],
        'migrations' => [
            'dir' => 'database/migrations',
            'namespace' => '',
        ],
        'storage' => [
            'dir' => 'storage',
            'namespace' => '',
        ]
    ]

];
