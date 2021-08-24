import Layout from '@/layout/Layout2'

export default [
    {
        path: '/download',
        component: Layout,
        meta: { auth: true },
        children: [
            {
                path: '',
                component: () => import('@/components/download/List'), 
                name: 'downloadlist',
                meta: { auth: true },
            },
        ]
    },
]
