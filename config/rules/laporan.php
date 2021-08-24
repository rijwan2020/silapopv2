<?php
return [
    [
        'name' => 'laporan',
        'label' => 'Laporan',
        'active' => false,
        'level' => 0,
        'parent' => true,
        'child' => false,
        'parent_name' => ''
    ],
    [
        'name' => 'laporan_bakulahan',
        'label' => 'Laporan Baku Lahan',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'laporan'
    ],
    [
        'name' => 'laporan_bakulahan_download',
        'label' => 'Download',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_bakulahan'
    ],
    [
        'name' => 'laporan_bakulahan_print',
        'label' => 'Print',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_bakulahan'
    ],
    // RENCANA TANAM
    [
        'name' => 'laporan_rencana_tanam',
        'label' => 'Laporan Rencana Tanam',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'laporan'
    ],
    [
        'name' => 'laporan_rencana_tanam_download',
        'label' => 'Download',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_rencana_tanam'
    ],
    [
        'name' => 'laporan_rencana_tanam_print',
        'label' => 'Print',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_rencana_tanam'
    ],
    // REALISASI TANAM
    [
        'name' => 'laporan_realisasi_tanam',
        'label' => 'Laporan Realisasi Tanam',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'laporan'
    ],
    [
        'name' => 'laporan_realisasi_tanam_download',
        'label' => 'Download',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_realisasi_tanam'
    ],
    [
        'name' => 'laporan_realisasi_tanam_print',
        'label' => 'Print',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_realisasi_tanam'
    ],
    // LUAS TANAM
    [
        'name' => 'laporan_luas_tanam',
        'label' => 'Laporan Luas Tanam',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'laporan'
    ],
    [
        'name' => 'laporan_luas_tanam_download',
        'label' => 'Download',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_luas_tanam'
    ],
    [
        'name' => 'laporan_luas_tanam_print',
        'label' => 'Print',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_luas_tanam'
    ],
    // TAMBAH TANAM
    [
        'name' => 'laporan_tambah_tanam',
        'label' => 'Laporan Tambah Tanam',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'laporan'
    ],
    [
        'name' => 'laporan_tambah_tanam_download',
        'label' => 'Download',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_tambah_tanam'
    ],
    [
        'name' => 'laporan_tambah_tanam_print',
        'label' => 'Print',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_tambah_tanam'
    ],
    // LUAS PANEN
    [
        'name' => 'laporan_luas_penen',
        'label' => 'Laporan Luas Panen',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'laporan'
    ],
    [
        'name' => 'laporan_luas_penen_download',
        'label' => 'Download',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_luas_penen'
    ],
    [
        'name' => 'laporan_luas_penen_print',
        'label' => 'Print',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_luas_penen'
    ],
    // PRODUKSI
    [
        'name' => 'laporan_produksi',
        'label' => 'Laporan Produksi',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'laporan'
    ],
    [
        'name' => 'laporan_produksi_download',
        'label' => 'Download',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_produksi'
    ],
    [
        'name' => 'laporan_produksi_print',
        'label' => 'Print',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_produksi'
    ],
    // PRODUKTIVITAS
    [
        'name' => 'laporan_produktivitas',
        'label' => 'Laporan Produktivitas',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'laporan'
    ],
    [
        'name' => 'laporan_produktivitas_download',
        'label' => 'Download',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_produktivitas'
    ],
    [
        'name' => 'laporan_produktivitas_print',
        'label' => 'Print',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_produktivitas'
    ],
    // HTP
    [
        'name' => 'laporan_htp',
        'label' => 'Laporan Harga Tingkat Petani',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'laporan'
    ],
    [
        'name' => 'laporan_htp_download',
        'label' => 'Download',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_htp'
    ],
    [
        'name' => 'laporan_htp_print',
        'label' => 'Print',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_htp'
    ],
    // Luas tanam saat ini
    [
        'name' => 'laporan_luastanam_saat_ini',
        'label' => 'Laporan Luas Tanam Saat Ini',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'laporan'
    ],
    [
        'name' => 'laporan_luastanam_saat_ini_download',
        'label' => 'Download',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_luastanam_saat_ini'
    ],
    [
        'name' => 'laporan_luastanam_saat_ini_print',
        'label' => 'Print',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_luastanam_saat_ini'
    ],
    // Rekap Input Penyuluh
    [
        'name' => 'laporan_rekap_input_penyuluh',
        'label' => 'Laporan Rekap Input Penyuluh',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'laporan'
    ],
    [
        'name' => 'laporan_rekap_input_penyuluh_download',
        'label' => 'Download',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_rekap_input_penyuluh'
    ],
    [
        'name' => 'laporan_rekap_input_penyuluh_print',
        'label' => 'Print',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_rekap_input_penyuluh'
    ],
    // Rekap user Penyuluh
    [
        'name' => 'laporan_rekap_user_penyuluh',
        'label' => 'Laporan Rekap User Penyuluh',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'laporan'
    ],
    [
        'name' => 'laporan_rekap_user_penyuluh_download',
        'label' => 'Download',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_rekap_user_penyuluh'
    ],
    [
        'name' => 'laporan_rekap_user_penyuluh_print',
        'label' => 'Print',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_rekap_user_penyuluh'
    ],
    // Optimalisasi mesin
    [
        'name' => 'laporan_opsin',
        'label' => 'Laporan Alsin',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'laporan'
    ],
    [
        'name' => 'laporan_opsin_download',
        'label' => 'Download',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_opsin'
    ],
    [
        'name' => 'laporan_opsin_print',
        'label' => 'Print',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_opsin'
    ],
    // Realisasi Alsin
    [
        'name' => 'laporan_opsin_detail',
        'label' => 'Laporan Realisasi Alsin',
        'active' => false,
        'level' => 1,
        'parent' => true,
        'child' => true,
        'parent_name' => 'laporan'
    ],
    [
        'name' => 'laporan_opsin_detail_download',
        'label' => 'Download',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_opsin_detail'
    ],
    [
        'name' => 'laporan_opsin_detail_print',
        'label' => 'Print',
        'active' => false,
        'level' => 2,
        'parent' => false,
        'child' => true,
        'parent_name' => 'laporan_opsin_detail'
    ],
];