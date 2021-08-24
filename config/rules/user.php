<?php
return [
    [
        'name' => 'managemen_user',
        'label' => 'Managemen User',
        'active' => false,
        'level' => 0,
        'parent' => true,
        'child' => false,
        'parent_name' => ''
    ],
    [
        'name' => 'user',
        'label' => 'Data User',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'managemen_user'
    ],
    [
        'name' => 'user_add',
        'label' => 'Tambah User',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'user'
    ],
    [
        'name' => 'user_edit',
        'label' => 'Edit User',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'user'
    ],
    [
        'name' => 'user_delete',
        'label' => 'Hapus User',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'user'
    ],
    [
        'name' => 'user_activate',
        'label' => 'Aktivasi User',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'user'
    ],
    [
        'name' => 'level',
        'label' => 'Level User',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'managemen_user'
    ],
    [
        'name' => 'level_add',
        'label' => 'Tambah Level',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'level'
    ],
    [
        'name' => 'level_edit',
        'label' => 'Edit Level',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'level'
    ],
    [
        'name' => 'level_delete',
        'label' => 'Hapus Level',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'level'
    ],
];