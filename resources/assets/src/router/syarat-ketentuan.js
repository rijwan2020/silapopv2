import Layout from '@/layout/LayoutBlank'
import Layout2 from '@/layout/Layout2'

export default [
    {
        path: '/syarat-ketentuan',
        component: Layout,
        meta: { auth: false },
        children: [
            {
                path: '',
                component: () => import('@/components/syarat-ketentuan/Index'), 
                name: 'syarat-ketentuan',
                meta: { auth: false },
            },
        ]
    },
    {
        path: '/syarat-ketentuan/data',
        component: Layout2,
        meta: { auth: true },
        children: [
            {
                path: '',
                component: () => import('@/components/syarat-ketentuan/Data'), 
                name: 'syarat-ketentuan-data',
                meta: { auth: true },
            },
        ]
    },
]
