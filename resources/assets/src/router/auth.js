import Layout from '@/layout/LayoutBlank'

export default [{
    path: '/login',
    component: Layout,
    name: 'login',
    component: () => import('@/components/Login'),
    meta: { auth: false },
}]
