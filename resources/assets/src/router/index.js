import Vue from 'vue'
import Router from 'vue-router'
import Meta from 'vue-meta'
import NotFound from '@/components/NotFound'

import globals from '@/globals'

// Layouts
import Layout from '@/layout/Layout2'

// store
import store from '@/store/index'

// Login
import authRoutes from './auth'
// Data Luas
import dataRoutes from './data'
// Laporan
import laporanRoutes from './laporan'
// Data Master
import masterRoutes from './master'
// Data User
import userRoutes from './user'
// Data User
import pengumumanRoutes from './pengumuman'
// Syarat dan Ketentuan
import syaratKetentuanRoutes from './syarat-ketentuan'
// Kebijakan Privasi
import kebijakanPrivasiRoutes from './kebijakan-privasi'
// Download
import download from './download'

Vue.use(Router)
Vue.use(Meta)

const ROUTES = [
	// Default route
	{
		path: "",
		component: Layout,
		meta: { auth: true },
		children: [
			{
				path: "",
				component: () => import("@/components/home/Home"),
				meta: { auth: true },
				name: 'home'
			},
			{
				path: "/profile",
				component: () => import("@/components/user/profile/Profile"),
				meta: { auth: true },
				name: 'profile'
			}
		]
	}, {
		// 404 Not Found page
		path: '*',
		component: NotFound
	}
]
	.concat(masterRoutes)
	.concat(userRoutes)
	.concat(pengumumanRoutes)
	.concat(kebijakanPrivasiRoutes)
	.concat(syaratKetentuanRoutes)
	.concat(dataRoutes)
	.concat(laporanRoutes)
	.concat(download)
	.concat(authRoutes);

const router = new Router({
	base: "/",
	mode: "history",
	routes: ROUTES
});


router.afterEach(() => {
	// On small screens collapse sidenav
	if (window.layoutHelpers && window.layoutHelpers.isSmallScreen() && !window.layoutHelpers.isCollapsed()) {
		setTimeout(() => window.layoutHelpers.setCollapsed(true, true), 10)
	}

	// Scroll to top of the page
	globals().scrollTop(0, 0)
})

router.beforeEach((to, from, next) => {
	// Set loading state
	document.body.classList.add('app-loading')

	if (to.meta.auth) {
		if (!store.getters['auth/user']) {
			next('/login');
		}
	} else {
		if(to.name != 'syarat-ketentuan' && to.name != 'kebijakan-privasi'){
			if (store.getters['auth/user']) {
				next('/');
			}
		}
	}

	// Add tiny timeout to finish page transition
	setTimeout(() => next(), 10)
})

export default router
