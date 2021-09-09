import Layout from '@/layout/Layout2'

export default [{
    path: '/master',
    component: Layout,
    meta: { auth: true },
    children: [
        {
            path: 'penyuluh',
            component: () =>
                import ('@/components/master/penyuluh/List'),
            name: 'penyuluh',
            meta: { auth: true },
        },
        {
            path: 'penyuluh/:id',
            component: () =>
                import ('@/components/master/penyuluh/Detail'),
            name: 'penyuluhDetail',
            meta: { auth: true },
        },
        {
            path: 'wilayahkerja',
            component: () =>
                import ('@/components/master/wilayahkerja/List'),
            name: 'wilayahkerja',
            meta: { auth: true },
        },
        {
            path: 'komoditas',
            component: () =>
                import ('@/components/master/komoditas/List'),
            name: 'komoditas',
            meta: { auth: true },
        },
        {
            path: 'komoditihtp',
            component: () =>
                import ('@/components/master/komoditashtp/List'),
            name: 'komoditihtp',
            meta: { auth: true },
        },
        {
            path: 'jenisalsin',
            component: () =>
                import ('@/components/master/jenisalsin/List'),
            name: 'jenisalsin',
            meta: { auth: true },
        },
        {
            path: 'namaopt',
            component: () =>
                import ('@/components/master/namaopt/List'),
            name: 'namaopt',
            meta: { auth: true },
        },
        {
            path: 'kota',
            component: () =>
                import ('@/components/master/area/Kota'),
            name: 'kota',
            meta: { auth: true },
        },
        {
            path: 'kota/:id',
            component: () =>
                import ('@/components/master/area/Kecamatan'),
            name: 'kecamatan',
            meta: { auth: true },
        },
        {
            path: 'kota/:kecId/:id',
            component: () =>
                import ('@/components/master/area/Desa'),
            name: 'desa',
            meta: { auth: true },
        },
        {
            path: 'korwil',
            component: () =>
                import ('@/components/master/korwil/List'),
            name: 'korwil',
            meta: { auth: true },
        }
    ]
}]