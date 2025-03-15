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
        'warning' => [
            'reasons' => [
                'linked_note_not_public' => 'Linked note is not public',
                'linked_note_not_found' => 'Not found linked note',
                'site_is_available' => 'The site is not available',
            ]
        ],
        'inactive' => [
            'reasons' => [
                'no_check' => 'There was no check'
            ],
        ],
        'page' => [
            'title' => 'Manage links in a note - ":title"',
            'breadcrumb' => 'Links',
            'navigation_label' => 'Note links',
        ]
    ],
    'notes' => [
        'warning' => [
            'reasons' => [
                'no_have_tags' => 'The note has no attached tags',
                'no_have_block' => 'The note has no text blocks',
                'have_problem_links' => 'The note has a problem with links'
            ],
        ],
    ]
];
