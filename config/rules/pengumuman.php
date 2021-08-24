<?php
return [
    [
        'name' => 'pengumuman',
        'label' => 'Pengumuman',
        'active' => false,
        'level' => 0,
        'parent' => true,
        'child' => false,
        'parent_name' => ''
    ],
    [
        'name' => 'pengumuman_add',
        'label' => 'Tambah Pengumuman',
        'active' => false,
        'level' => 1,
        'parent' => false,
        'child' => true,
        'parent_name' => 'pengumuman'
    ],
    [
        'name' => 'pengumuman_edit',
        'label' => 'Edit Pengumuman',
        'active' => false,
        'level' => 1,
        'parent' => false,
        'child' => true,
        'parent_name' => 'pengumuman'
    ],
    [
        'name' => 'pengumuman_delete',
        'label' => 'Hapus Pengumuman',
        'active' => false,
        'level' => 1,
        'parent' => false,
        'child' => true,
        'parent_name' => 'pengumuman'
    ],
];