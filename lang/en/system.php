<?php

return [
    'tags' => [
        'actions' => [
            'failed' => [
                'delete' => [
                    'has_notes' => 'The tag can\'t be deleted; it\'s  attached to notes',
                ]
            ]
        ]
    ],
    'note_links' => [
        'inactive' => [
            'reasons' => [
                'no_check' => 'There was no check'
            ],
        ]
    ],
    'notes' => [
        'warning' => [
            'reasons' => [
                'no_have_tags' => 'The note has no attached tags',
                'have_problem_links' => 'The note has a problem with links'
            ],
        ]
    ]
];
