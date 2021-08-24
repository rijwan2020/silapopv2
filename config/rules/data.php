<?php
return [
    [
        'name' => 'data',
        'label' => 'Data',
        'active' => false,
        'level' => 0,
        'parent' => true,
        'child' => false,
        'parent_name' => ''
    ],
    // BAKU LAHAN
    [
        'name' => 'baku_lahan',
        'label' => 'Baku Lahan',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'data'
    ],
    [
        'name' => 'baku_lahan_add',
        'label' => 'Tambah Baku Lahan',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'baku_lahan'
    ],
    [
        'name' => 'baku_lahan_edit',
        'label' => 'Edit Baku Lahan',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'baku_lahan'
    ],
    [
        'name' => 'baku_lahan_delete',
        'label' => 'Hapus Baku Lahan',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'baku_lahan'
    ],
    [
        'name' => 'baku_lahan_ver_kota',
        'label' => 'Verifikasi Kota/Kabupaten',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'baku_lahan'
    ],
    [
        'name' => 'baku_lahan_ver_kec',
        'label' => 'Verifikasi Kecamatan',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'baku_lahan'
    ],
    [
        'name' => 'rencana_realisasi',
        'label' => 'Rencana dan Realisasi Tanam',
        'active' => false,
        'level' => 2,
        'parent' => true,
        'child' => true,
        'parent_name' => 'baku_lahan'
    ],
    [
        'name' => 'rencana_realisasi_add',
        'label' => 'Tambah Rencana dan Realisasi Tanam',
        'active' => false,
        'level' => 3,
        'parent' => false,
        'child' => true,
        'parent_name' => 'rencana_realisasi'
    ],
    [
        'name' => 'rencana_realisasi_edit',
        'label' => 'Edit Rencana dan Realisasi Tanam',
        'active' => false,
        'level' => 3,
        'parent' => false,
        'child' => true,
        'parent_name' => 'rencana_realisasi'
    ],
    [
        'name' => 'rencana_realisasi_delete',
        'label' => 'Hapus Rencana dan Realisasi Tanam',
        'active' => false,
        'level' => 3,
        'parent' => false,
        'child' => true,
        'parent_name' => 'rencana_realisasi'
    ],
    [
        'name' => 'rencana_realisasi_ver_kota',
        'label' => 'Verifikasi Kota/Kabupaten',
        'active' => false,
        'level' => 3,
        'parent' => false,
        'child' => true,
        'parent_name' => 'rencana_realisasi'
    ],
    [
        'name' => 'rencana_realisasi_ver_kec',
        'label' => 'Verifikasi Kecamatan',
        'active' => false,
        'level' => 3,
        'parent' => false,
        'child' => true,
        'parent_name' => 'rencana_realisasi'
    ],

    // DATA LUAS
    [
        'name' => 'data_luas',
        'label' => 'Data Luas',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'data'
    ],
    [
        'name' => 'data_luas_add',
        'label' => 'Tambah Data Luas',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'data_luas'
    ],
    [
        'name' => 'data_luas_edit',
        'label' => 'Edit Data Luas',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'data_luas'
    ],
    [
        'name' => 'data_luas_delete',
        'label' => 'Hapus Data Luas',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'data_luas'
    ],
    [
        'name' => 'data_luas_ver_kota',
        'label' => 'Verifikasi Kota/Kabupaten',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'data_luas'
    ],
    [
        'name' => 'data_luas_ver_kec',
        'label' => 'Verifikasi Kecamatan',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'data_luas'
    ],
    
    // HARGA TINGKAT PETANI
    [
        'name' => 'htp',
        'label' => 'Harga Tingkat Petani',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'data'
    ],
    [
        'name' => 'htp_add',
        'label' => 'Tambah Harga Tingkat Petani',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'htp'
    ],
    [
        'name' => 'htp_edit',
        'label' => 'Edit Harga Tingkat Petani',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'htp'
    ],
    [
        'name' => 'htp_delete',
        'label' => 'Hapus Harga Tingkat Petani',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'htp'
    ],
    [
        'name' => 'htp_ver_kota',
        'label' => 'Verifikasi Kota/Kabupaten',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'htp'
    ],
    [
        'name' => 'htp_ver_kec',
        'label' => 'Verifikasi Kecamatan',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'htp'
    ],
    
    // DATA ALSIN
    [
        'name' => 'alsin',
        'label' => 'Data Alsin',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'data'
    ],
    [
        'name' => 'alsin_add',
        'label' => 'Tambah Data Alsin',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'alsin'
    ],
    [
        'name' => 'alsin_edit',
        'label' => 'Edit Data Alsin',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'alsin'
    ],
    [
        'name' => 'alsin_delete',
        'label' => 'Hapus Data Alsin',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'alsin'
    ],
    [
        'name' => 'alsin_ver_kota',
        'label' => 'Verifikasi Kota/Kabupaten',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'alsin'
    ],
    [
        'name' => 'alsin_ver_kec',
        'label' => 'Verifikasi Kecamatan',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'alsin'
    ],
    [
        'name' => 'alsin_detail',
        'label' => 'Detail Alsin',
        'active' => false,
        'level' => 2,
        'parent' => true,
        'child' => true,
        'parent_name' => 'alsin'
    ],
    [
        'name' => 'alsin_detail_add',
        'label' => 'Tambah Detail Alsin',
        'active' => false,
        'level' => 3,
        'parent' => false,
        'child' => true,
        'parent_name' => 'alsin_detail'
    ],
    [
        'name' => 'alsin_detail_edit',
        'label' => 'Edit Detail Alsin',
        'active' => false,
        'level' => 3,
        'parent' => false,
        'child' => true,
        'parent_name' => 'alsin_detail'
    ],
    [
        'name' => 'alsin_detail_delete',
        'label' => 'Hapus Detail Alsin',
        'active' => false,
        'level' => 3,
        'parent' => false,
        'child' => true,
        'parent_name' => 'alsin_detail'
    ],
    [
        'name' => 'alsin_detail_ver_kota',
        'label' => 'Verifikasi Kota/Kabupaten',
        'active' => false,
        'level' => 3,
        'parent' => false,
        'child' => true,
        'parent_name' => 'alsin_detail'
    ],
    [
        'name' => 'alsin_detail_ver_kec',
        'label' => 'Verifikasi Kecamatan',
        'active' => false,
        'level' => 3,
        'parent' => false,
        'child' => true,
        'parent_name' => 'alsin_detail'
    ],
];