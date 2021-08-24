import Layout from '@/layout/Layout2'

export default [{
    path: '/laporan',
    component: Layout,
    meta: { auth: true },
    children: [{
            path: 'bakulahan',
            component: () =>
                import ('@/components/laporan/Bakulahan'),
            'name': 'laporan_bakulahan',
            meta: { auth: true },
        },
        // RENCANA TANAM
        {
            path: 'rencana_tanam',
            component: () =>
                import ('@/components/laporan/RencanaTanam'),
            'name': 'laporan_rencana_tanam',
            meta: { auth: true },
        },
        {
            path: 'rencana_tanam/detail',
            component: () =>
                import ('@/components/laporan/RencanaTanamDetail'),
            'name': 'laporan_rencana_tanam_detail',
            meta: { auth: true },
        },
        // REALISASI TANAM
        {
            path: 'realisasi_tanam',
            component: () =>
                import ('@/components/laporan/RealisasiTanam'),
            'name': 'laporan_realisasi_tanam',
            meta: { auth: true },
        },
        {
            path: 'realisasi_tanam/detail',
            component: () =>
                import ('@/components/laporan/RealisasiTanamDetail'),
            'name': 'laporan_realisasi_tanam_detail',
            meta: { auth: true },
        },
        // HTP
        {
            path: 'htp',
            component: () =>
                import ('@/components/laporan/Htp'),
            'name': 'laporan_htp',
            meta: { auth: true },
        },
        {
            path: 'htp/detail',
            component: () =>
                import ('@/components/laporan/HtpDetail'),
            'name': 'laporan_htp_detail',
            meta: { auth: true },
        },
        {
            path: 'htp/area',
            component: () =>
                import ('@/components/laporan/HtpArea'),
            'name': 'laporan_htp_area',
            meta: { auth: true },
        },
        {
            path: 'htp/area/detail',
            component: () =>
                import ('@/components/laporan/HtpAreaDetail'),
            'name': 'laporan_htp_area_detail',
            meta: { auth: true },
        },
        // Luas tanam
        {
            path: 'luas_tanam',
            component: () =>
                import ('@/components/laporan/LuasTanam'),
            'name': 'laporan_luas_tanam',
            meta: { auth: true },
        },
        {
            path: 'luas_tanam/detail',
            component: () =>
                import ('@/components/laporan/LuasTanamDetail'),
            'name': 'laporan_luas_tanam_detail',
            meta: { auth: true },
        },
        // Tambah tanam
        {
            path: 'tambah_tanam',
            component: () =>
                import ('@/components/laporan/TambahTanam'),
            'name': 'laporan_tambah_tanam',
            meta: { auth: true },
        },
        {
            path: 'tambah_tanam/detail',
            component: () =>
                import ('@/components/laporan/TambahTanamDetail'),
            'name': 'laporan_tambah_tanam_detail',
            meta: { auth: true },
        },
        // Luas panen
        {
            path: 'luas_panen',
            component: () =>
                import ('@/components/laporan/LuasPanen'),
            'name': 'laporan_luas_panen',
            meta: { auth: true },
        },
        {
            path: 'luas_panen/detail',
            component: () =>
                import ('@/components/laporan/LuasPanenDetail'),
            'name': 'laporan_luas_panen_detail',
            meta: { auth: true },
        },
        // Produksi
        {
            path: 'produksi',
            component: () =>
                import ('@/components/laporan/Produksi'),
            'name': 'laporan_produksi',
            meta: { auth: true },
        },
        {
            path: 'produksi/detail',
            component: () =>
                import ('@/components/laporan/ProduksiDetail'),
            'name': 'laporan_produksi_detail',
            meta: { auth: true },
        },
        // Produktivitas
        {
            path: 'produktivitas',
            component: () =>
                import ('@/components/laporan/Produktivitas'),
            'name': 'laporan_produktivitas',
            meta: { auth: true },
        },
        {
            path: 'produktivitas/detail',
            component: () =>
                import ('@/components/laporan/ProduktivitasDetail'),
            'name': 'laporan_produktivitas_detail',
            meta: { auth: true },
        },
        // Luas tanam
        {
            path: 'luastanam',
            component: () =>
                import ('@/components/laporan/LuasTanamSaatIni'),
            'name': 'laporan_luastanam_saat_ini',
            meta: { auth: true },
        },
        // Rekap Input Penyuluh
        {
            path: 'rekap_input_penyuluh',
            component: () =>
                import ('@/components/laporan/RekapInputPenyuluh'),
            'name': 'rekap_input_penyuluh',
            meta: { auth: true },
        },
        // Rekap Input Penyuluh
        {
            path: 'rekap_user_penyuluh',
            component: () =>
                import ('@/components/laporan/RekapUserPenyuluh'),
            'name': 'rekap_user_penyuluh',
            meta: { auth: true },
        },
        // Opsin
        {
            path: 'opsin',
            component: () =>
                import ('@/components/laporan/Opsin'),
            'name': 'laporan_opsin',
            meta: { auth: true },
        },
        // Realisasi Alsin
        {
            path: 'realisasi_opsin',
            component: () =>
                import ('@/components/laporan/RealisasiAlsin'),
            'name': 'realisasi_opsin',
            meta: { auth: true },
        },
        {
            path: 'realisasi_opsin/detail',
            component: () =>
                import ('@/components/laporan/RealisasiAlsinDetail'),
            'name': 'realisasi_opsin_detail',
            meta: { auth: true },
        },
    ]
}]