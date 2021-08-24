<template>
  <b-navbar toggleable="lg" :variant="getLayoutNavbarBg()" class="layout-navbar align-items-lg-center container-p-x">

    <!-- Navbar toggle -->
    <!-- <b-navbar-toggle target="app-layout-navbar"></b-navbar-toggle> -->

	<b-navbar-nav class="layout-sidenav-toggle d-lg-none align-items-lg-center mr-auto" v-if="sidenavToggle">
		<a class="nav-item nav-link px-0 mr-lg-4" href="javascript:void(0)" @click="toggleSidenav">
			<i class="ion ion-md-menu text-large align-middle" />
		</a>
    </b-navbar-nav>
	<b-navbar-brand to="/" class="app-brand demo d-lg-none py-0 mr-4">
		<span class="app-brand-text demo font-weight-normal ml-2">Sistem Informasi Laporan Online Penyuluh Pertanian</span>
    </b-navbar-brand>

	
	<b-collapse is-nav id="app-layout-navbar">
		<!-- Divider -->
		<hr class="d-lg-none w-100 my-2">

		<b-navbar-nav class="align-items-lg-center">
			<!-- Search -->
			<label class="nav-item navbar-text navbar-search-box p-0 active">
				Sistem Informasi Laporan Online Penyuluh Pertanian
			</label>
		</b-navbar-nav>

		<b-navbar-nav class="align-items-lg-center ml-auto">

			<!-- Divider -->
			<div class="nav-item d-none d-lg-block text-big font-weight-light line-height-1 opacity-25 mr-3 ml-1">|</div>

			<b-nav-item-dropdown :right="!isRtlMode" class="demo-navbar-user">
			<template slot="button-content">
				<span class="d-inline-flex flex-lg-row-reverse align-items-center align-middle">
				<img :src="user.image_url" alt class="d-block ui-w-30 rounded-circle" v-if="user">
				<span class="px-1 mr-lg-2 ml-2 ml-lg-0" v-if="user">{{ user.name }}</span>
				</span>
			</template>

			<!-- <b-dd-item><i class="ion ion-ios-person text-lightest"></i> &nbsp; My profile</b-dd-item>
			<b-dd-item><i class="ion ion-ios-mail text-lightest"></i> &nbsp; Messages</b-dd-item>
			<b-dd-item><i class="ion ion-md-settings text-lightest"></i> &nbsp; Account settings</b-dd-item>
			<b-dd-divider /> -->
			<b-dd-item @click="logout"><i class="ion ion-ios-log-out text-danger"></i> &nbsp; Log Out</b-dd-item>
			</b-nav-item-dropdown>
		</b-navbar-nav>
    </b-collapse>

  </b-navbar>
</template>

<style>
@media screen and (max-width: 500px) {
	.app-brand-text.demo{
		font-size: 0.9rem;
	}		
}
@media screen and (max-width: 420px) {
	.app-brand-text.demo{
		font-size: 0.8rem;
	}		
}
</style>

<script>
import { mapActions } from "vuex";
export default {
	name: 'app-layout-navbar',

	props: {
		sidenavToggle: {
		type: Boolean,
		default: true
		}
	},

	computed: {
		user() {
			return this.$store.getters["auth/user"];
		},
	},

	methods: {
		...mapActions({
			setAuth: "auth/set",
			setRole: "auth/role",
		}),

		toggleSidenav() {
			this.layoutHelpers.toggleCollapsed();
		},

		getLayoutNavbarBg() {
			return this.layoutNavbarBg;
		},

		logout() {
			this.setAuth({ user: false, token: false });
			// this.setRole(false);
			setTimeout(() => {
				this.notif("success", "Success", "Logout Success");
				// window.location = "/home";
				this.$router.push({ name: "login" });
			}, 2000);
		},
	},
}
</script>
