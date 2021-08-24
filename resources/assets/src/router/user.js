import Layout from '@/layout/Layout2'

export default [{
    path: '/user',
    component: Layout,
    meta: { auth: true },
    children: [{
            path: 'list',
            component: () =>
                import ('@/components/user/List'),
            name: 'userlist',
            meta: { auth: true },
        },
        {
            path: 'level',
            component: () =>
                import ('@/components/user/Level'),
            name: 'level',
            meta: { auth: true },
        },
    ]
}, ]