<?php
return [
    [
        'name' => 'master',
        'label' => 'Master',
        'active' => false,
        'level' => 0,
        'parent' => true,
        'child' => false,
        'parent_name' => ''
    ],
    // DATA PENYULUH
    [
        'name' => 'penyuluh',
        'label' => 'Data Penyuluh',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'master'
    ],
    [
        'name' => 'penyuluh_add',
        'label' => 'Tambah Penyuluh',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'penyuluh'
    ],
    [
        'name' => 'penyuluh_edit',
        'label' => 'Edit Penyuluh',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'penyuluh'
    ],
    [
        'name' => 'penyuluh_delete',
        'label' => 'Hapus Penyuluh',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'penyuluh'
    ],
    [
        'name' => 'penyuluh_detail',
        'label' => 'Detail Penyuluh',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'penyuluh'
    ],
    // WILAYAH KERJA
    [
        'name' => 'wilayah_kerja',
        'label' => 'Wilayah Kerja',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'master'
    ],
    [
        'name' => 'wilayah_kerja_add',
        'label' => 'Tambah Wilayah Kerja',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'wilayah_kerja'
    ],
    [
        'name' => 'wilayah_kerja_edit',
        'label' => 'Edit Wilayah Kerja',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'wilayah_kerja'
    ],
    [
        'name' => 'wilayah_kerja_delete',
        'label' => 'Hapus Wilayah Kerja',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'wilayah_kerja'
    ],
    // KOMODITAS
    [
        'name' => 'komoditas',
        'label' => 'Data Komoditas',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'master'
    ],
    [
        'name' => 'komoditas_add',
        'label' => 'Tambah Komoditas',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'komoditas'
    ],
    [
        'name' => 'komoditas_edit',
        'label' => 'Edit Komoditas',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'komoditas'
    ],
    [
        'name' => 'komoditas_delete',
        'label' => 'Hapus Komoditas',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'komoditas'
    ],
    // KOMODITAS HTP
    [
        'name' => 'komoditas_htp',
        'label' => 'Data Komoditas Harga Tingkat Petani',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'master'
    ],
    [
        'name' => 'komoditas_htp_add',
        'label' => 'Tambah Komoditas Harga Tingkat Petani',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'komoditas_htp'
    ],
    [
        'name' => 'komoditas_htp_edit',
        'label' => 'Edit Komoditas Harga Tingkat Petani',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'komoditas_htp'
    ],
    [
        'name' => 'komoditas_htp_delete',
        'label' => 'Hapus Komoditas Harga Tingkat Petani',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'komoditas_htp'
    ],
    
    // JENIS ALSIN
    [
        'name' => 'jenis_alsin',
        'label' => 'Data Jenis Alsin',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'master'
    ],
    [
        'name' => 'jenis_alsin_add',
        'label' => 'Tambah Jenis Alsin',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'jenis_alsin'
    ],
    [
        'name' => 'jenis_alsin_edit',
        'label' => 'Edit Jenis Alsin',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'jenis_alsin'
    ],
    [
        'name' => 'jenis_alsin_delete',
        'label' => 'Hapus Jenis Alsin',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'jenis_alsin'
    ],
    
    // NAMA OPT
    [
        'name' => 'nama_opt',
        'label' => 'Data Nama OPT',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'master'
    ],
    [
        'name' => 'nama_opt_add',
        'label' => 'Tambah Nama OPT',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'nama_opt'
    ],
    [
        'name' => 'nama_opt_edit',
        'label' => 'Edit Nama OPT',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'nama_opt'
    ],
    [
        'name' => 'nama_opt_delete',
        'label' => 'Hapus Nama OPT',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'nama_opt'
    ],
    
    // AREA - KOTA
    [
        'name' => 'kota',
        'label' => 'Data Kota',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'master'
    ],
    [
        'name' => 'kota_add',
        'label' => 'Tambah Kota',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'kota'
    ],
    [
        'name' => 'kota_edit',
        'label' => 'Edit Kota',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'kota'
    ],
    [
        'name' => 'kota_delete',
        'label' => 'Hapus Kota',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'kota'
    ],
    
    // AREA - Kecamatan
    [
        'name' => 'kecamatan',
        'label' => 'Data Kecamatan',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'master'
    ],
    [
        'name' => 'kecamatan_add',
        'label' => 'Tambah Kecamatan',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'kecamatan'
    ],
    [
        'name' => 'kecamatan_edit',
        'label' => 'Edit Kecamatan',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'kecamatan'
    ],
    [
        'name' => 'kecamatan_delete',
        'label' => 'Hapus Kecamatan',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'kecamatan'
    ],
    
    // AREA - Desa
    [
        'name' => 'desa',
        'label' => 'Data Desa',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'master'
    ],
    [
        'name' => 'desa_add',
        'label' => 'Tambah Desa',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'desa'
    ],
    [
        'name' => 'desa_edit',
        'label' => 'Edit Desa',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'desa'
    ],
    [
        'name' => 'desa_delete',
        'label' => 'Hapus Desa',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'desa'
    ],
    
    // Koordinator Wilayah
    [
        'name' => 'korwil',
        'label' => 'Koordinator Wilayah',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'master'
    ],
    [
        'name' => 'korwil_add',
        'label' => 'Tambah Koordinator Wilayah',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'korwil'
    ],
    [
        'name' => 'korwil_edit',
        'label' => 'Edit Koordinator Wilayah',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'korwil'
    ],
    [
        'name' => 'korwil_delete',
        'label' => 'Hapus Koordinator Wilayah',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'korwil'
    ],
];