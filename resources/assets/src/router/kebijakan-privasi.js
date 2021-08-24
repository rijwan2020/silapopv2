import Layout from '@/layout/LayoutBlank'
import Layout2 from '@/layout/Layout2'

export default [
    {
        path: '/kebijakan-privasi',
        component: Layout,
        meta: { auth: false },
        children: [
            {
                path: '',
                component: () => import('@/components/kebijakan-privasi/Index'), 
                name: 'kebijakan-privasi',
                meta: { auth: false },
            },
        ]
    },
    {
        path: '/kebijakan-privasi/data',
        component: Layout2,
        meta: { auth: true },
        children: [
            {
                path: '',
                component: () => import('@/components/kebijakan-privasi/Data'), 
                name: 'kebijakan-privasi-data',
                meta: { auth: true },
            },
        ]
    },
]
