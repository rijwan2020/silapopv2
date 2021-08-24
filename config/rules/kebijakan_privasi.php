<?php
return [
    [
        'name' => 'kebijakan_privasi',
        'label' => 'Kebijakan Privasi',
        'active' => false,
        'level' => 0,
        'parent' => true,
        'child' => false,
        'parent_name' => ''
    ],
    [
        'name' => 'kebijakan_privasi_edit',
        'label' => 'Edit Kebijakan Privasi',
        'active' => false,
        'level' => 1,
        'parent' => false,
        'child' => true,
        'parent_name' => 'kebijakan_privasi'
    ],
];