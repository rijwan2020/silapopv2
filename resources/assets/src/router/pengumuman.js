import Layout from '@/layout/Layout2'

export default [
    {
        path: '/pengumuman',
        component: Layout,
        meta: { auth: true },
        children: [
            {
                path: '',
                component: () => import('@/components/pengumuman/List'), 
                name: 'pengumumanlist',
                meta: { auth: true },
            },
        ]
    },
]
