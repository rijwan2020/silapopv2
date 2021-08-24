<?php
return [
    [
        'name' => 'syarat_ketentuan',
        'label' => 'Syarat & Ketentuan',
        'active' => false,
        'level' => 0,
        'parent' => true,
        'child' => false,
        'parent_name' => ''
    ],
    [
        'name' => 'syarat_ketentuan_edit',
        'label' => 'Edit Syarat & Ketentuan',
        'active' => false,
        'level' => 1,
        'parent' => false,
        'child' => true,
        'parent_name' => 'syarat_ketentuan'
    ],
];