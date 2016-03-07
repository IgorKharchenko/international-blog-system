<?php
return [
    'createPost' => [
        'type' => 2,
        'description' => 'Create a post',
    ],
    'updatePost' => [
        'type' => 2,
        'description' => 'Update any post',
    ],
    'updateOwnPost' => [
        'type' => 2,
        'description' => 'Update own post',
        'ruleName' => 'isAuthor',
    ],
    'deletePost' => [
        'type' => 2,
        'description' => 'Delete any post',
    ],
    'deleteOwnPost' => [
        'type' => 2,
        'description' => 'Delete own post',
        'ruleName' => 'isAuthor',
    ],
    'createUser' => [
        'type' => 2,
        'description' => 'Create a user',
    ],
    'readUser' => [
        'type' => 2,
        'description' => 'Read a user',
    ],
    'updateUser' => [
        'type' => 2,
        'description' => 'Update any user',
    ],
    'updateOwnUser' => [
        'type' => 2,
        'description' => 'Update own user info',
        'ruleName' => 'isAuthorUserRules',
    ],
    'deleteUser' => [
        'type' => 2,
        'description' => 'Delete any user',
    ],
    'deleteOwnUser' => [
        'type' => 2,
        'description' => 'Delete own user info',
        'ruleName' => 'isAuthorUserRules',
    ],
    'createComment' => [
        'type' => 2,
        'description' => 'Create a comment',
    ],
    'updateComment' => [
        'type' => 2,
        'description' => 'Update any comment',
    ],
    'updateOwnComment' => [
        'type' => 2,
        'description' => 'Update own comment',
        'ruleName' => 'isAuthor',
    ],
    'deleteComment' => [
        'type' => 2,
        'description' => 'Delete any comment',
    ],
    'deleteOwnComment' => [
        'type' => 2,
        'description' => 'Delete own comment',
        'ruleName' => 'isAuthor',
    ],
    'unapproved' => [
        'type' => 1,
        'description' => 'Unapproved',
        'children' => [
            'readUser',
        ],
    ],
    'author' => [
        'type' => 1,
        'description' => 'Author',
        'children' => [
            'unapproved',
            'createPost',
            'updateOwnPost',
            'deleteOwnPost',
            'createComment',
            'updateOwnComment',
            'deleteOwnComment',
            'updateOwnUser',
            'deleteOwnUser',
        ],
    ],
    'admin' => [
        'type' => 1,
        'description' => 'Admin',
        'children' => [
            'author',
            'updatePost',
            'deletePost',
            'updateComment',
            'deleteComment',
            'updateUser',
            'deleteUser',
            'createUser',
        ],
    ],
];
