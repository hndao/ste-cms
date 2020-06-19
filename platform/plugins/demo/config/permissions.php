<?php

return [
    [
        'name' => 'Demos',
        'flag' => 'demo.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'demo.create',
        'parent_flag' => 'demo.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'demo.edit',
        'parent_flag' => 'demo.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'demo.destroy',
        'parent_flag' => 'demo.index',
    ],
];
