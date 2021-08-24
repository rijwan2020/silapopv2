import Layout from '@/layout/Layout2'

export default [{
    path: '/data',
    component: Layout,
    meta: { auth: true },
    children: [
        {
            path: 'luas',
            component: () => import('@/components/data/luas/Luas'),
            name: 'luas',
            meta: { auth: true },
        }, 
        {
            path: 'luas/:id',
            component: () => import('@/components/data/luas/List'),
            'name': 'dataLuasList',
            meta: { auth: true },
        },
        {
            path: 'bakulahan',
            component: () => import('@/components/data/bakulahan/Bakulahan'),
            'name': 'bakulahan',
            meta: { auth: true },
        },
        {
            path: 'bakulahan/:id',
            component: () => import('@/components/data/bakulahan/List'),
            'name': 'bakulahanList',
            meta: { auth: true },
        },
        {
            path: 'bakulahan/detail/:id',
            component: () => import('@/components/data/bakulahan/Detail'),
            'name': 'bakulahanDetail',
            meta: { auth: true },
        },
        {
            path: 'htp',
            component: () => import('@/components/data/htp/Data'),
            name: 'htp',
            meta: { auth: true },
        }, 
        {
            path: 'htp/:id',
            component: () => import('@/components/data/htp/List'),
            'name': 'htpList',
            meta: { auth: true },
        },
        {
            path: 'opsin',
            component: () => import('@/components/data/opsin/Data'),
            'name': 'opsin',
            meta: { auth: true },
        },
        {
            path: 'opsin/:id',
            component: () => import('@/components/data/opsin/List'),
            'name': 'opsinList',
            meta: { auth: true },
        },
        {
            path: 'opsin/detail/:id',
            component: () => import('@/components/data/opsin/Detail'),
            'name': 'opsinDetail',
            meta: { auth: true },
        },
]
}]
