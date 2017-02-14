<?php
return [
    'readPreviewPost' => [
        'type' => 2,
        'description' => 'Preview a post',
    ],
    'guest' => [
        'type' => 1,
        'children' => [
            'readPreviewPost',
        ],
    ],
    'readPost' => [
        'type' => 2,
        'description' => 'Read post',
    ],
    'reader' => [
        'type' => 1,
        'children' => [
            'guest',
            'readPost',
        ],
    ],
    'createPost' => [
        'type' => 2,
        'description' => 'Create a post',
    ],
    'updatePost' => [
        'type' => 2,
        'description' => 'Update a post',
    ],
    'deletePost' => [
        'type' => 2,
        'description' => 'Delete a post',
    ],
    'moder' => [
        'type' => 1,
        'children' => [
            'reader',
            'createPost',
            'updatePost',
            'deletePost',
        ],
    ],
    'createPerson' => [
        'type' => 2,
        'description' => 'Create a person',
    ],
    'readPerson' => [
        'type' => 2,
        'description' => 'Read info about person',
    ],
    'updatePerson' => [
        'type' => 2,
        'description' => 'Update info about person',
    ],
    'deletePerson' => [
        'type' => 2,
        'description' => 'Delete info about person',
    ],
    'admin' => [
        'type' => 1,
        'children' => [
            'moder',
            'createPerson',
            'readPerson',
            'updatePerson',
            'deletePerson',
        ],
    ],
];
