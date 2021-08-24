<template>
	<sidenav :orientation="orientation" :class="curClasses" v-if="user">
		<!-- Brand demo (see src/demo.css) -->
		<div class="app-brand demo" v-if="orientation !== 'horizontal'">
			<span class="app-brand-logo demo">
				<img :src="publicUrl + 'img/login/jabarprov.svg'" alt="" height="30px" width="auto">
			</span>
			<router-link to="/" class="app-brand-text demo sidenav-text font-weight-normal ml-2">SILAPOP</router-link>
			<a href="javascript:void(0)" class="layout-sidenav-toggle sidenav-link text-large ml-auto" @click="toggleSidenav()">
				<i class="ion ion-md-menu align-middle"></i>
			</a>
		</div>
		<!-- Inner -->
		<div class="sidenav-inner" :class="{ 'py-1': orientation !== 'horizontal' }">
			<sidenav-router-link 
				icon="ion ion-ios-contact" 
				to="/" 
				:active="isMenuActive('/home')"
				:exact="true">Home
			</sidenav-router-link>

			<!-- Master -->
			<sidenav-menu
				icon="ion ion-ios-albums"
				:active="isMenuActive('/master')"
				:open="isMenuOpen('/master')"
				v-if="hasAccess.master"
			>
				<template slot="link-text">Master</template>
				<sidenav-router-link
					to="/master/penyuluh"
					:exact="true"
					:active="isMenuActive('/master/penyuluh')"
					v-if="hasAccess.penyuluh"
					>Data Penyuluh</sidenav-router-link
				>
				<sidenav-router-link
					to="/master/wilayahkerja"
					:exact="true"
					:active="isMenuActive('/master/wilayahkerja')"
					v-if="hasAccess.wilayah_kerja"
					>Wilayah Kerja</sidenav-router-link
				>
				<sidenav-router-link
					to="/master/komoditas"
					:exact="true"
					:active="isMenuActive('/master/komoditas')"
					v-if="hasAccess.komoditas"
					>Komoditas</sidenav-router-link
				>
				<sidenav-router-link
					to="/master/komoditihtp"
					:exact="true"
					:active="isMenuActive('/master/komoditihtp')"
					v-if="hasAccess.komoditas_htp"
					>Harga Komoditas</sidenav-router-link
				>
				<sidenav-router-link
					to="/master/jenisalsin"
					:exact="true"
					:active="isMenuActive('/master/jenisalsin')"
					v-if="hasAccess.jenis_alsin"
					>Jenis Alsin</sidenav-router-link
				>
				<sidenav-router-link
					to="/master/namaopt"
					:exact="true"
					:active="isMenuActive('/master/namaopt')"
					v-if="hasAccess.nama_opt"
					>Nama OPT</sidenav-router-link
				>
				<sidenav-router-link
					to="/master/kota"
					:exact="true"
					:active="isMenuActive('/master/kota')"
					v-if="hasAccess.kota"
					>Data Kota</sidenav-router-link
				>
				<sidenav-router-link
					to="/master/korwil"
					:exact="true"
					:active="isMenuActive('/master/korwil')"
					v-if="hasAccess.korwil"
					>Koordinator Wilayah</sidenav-router-link
				>
			</sidenav-menu>

			<!-- Data -->
			<sidenav-menu
				icon="ion ion-ios-albums"
				:active="isMenuActive('/data')"
				:open="isMenuOpen('/data')"
				v-if="hasAccess.data"
			>
				<template slot="link-text">Data</template>
				<sidenav-router-link
					to="/data/bakulahan"
					:exact="true"
					:active="isMenuActive('/data/bakulahan')"
					v-if="hasAccess.baku_lahan"
					>Baku Lahan</sidenav-router-link
				>
				<sidenav-router-link
					to="/data/luas"
					:exact="true"
					:active="isMenuActive('/data/luas')"
					v-if="hasAccess.data_luas"
					>Data Luas</sidenav-router-link
				>
				<sidenav-router-link
					to="/data/htp"
					:exact="true"
					:active="isMenuActive('/data/htp')"
					v-if="hasAccess.htp"
					>Harga Tingkat Petani</sidenav-router-link
				>
				<sidenav-router-link
					to="/data/opsin"
					:exact="true"
					:active="isMenuActive('/data/opsin')"
					v-if="hasAccess.alsin"
					>Data Alsin</sidenav-router-link
				>
			</sidenav-menu>

			<!-- Laporan -->
			<sidenav-menu
				icon="ion ion-ios-stats"
				:active="isMenuActive('/laporan')"
				:open="isMenuOpen('/laporan')"
				v-if="hasAccess.laporan"
			>
				<template slot="link-text">Laporan</template>
				<sidenav-router-link
					to="/laporan/bakulahan"
					:exact="true"
					:active="isMenuActive('/laporan/bakulahan')"
					v-if="hasAccess.laporan_bakulahan"
					>Baku Lahan</sidenav-router-link
				>
				<sidenav-router-link
					to="/laporan/rencana_tanam"
					:exact="true"
					:active="isMenuActive('/laporan/rencana_tanam')"
					v-if="hasAccess.laporan_rencana_tanam"
					>Rencana Tanam</sidenav-router-link
				>
				<sidenav-router-link
					to="/laporan/realisasi_tanam"
					:exact="true"
					:active="isMenuActive('/laporan/realisasi_tanam')"
					v-if="hasAccess.laporan_realisasi_tanam"
					>Realisasi Tanam</sidenav-router-link
				>
				<sidenav-router-link
					to="/laporan/luas_tanam"
					:exact="true"
					:active="isMenuActive('/laporan/luas_tanam')"
					v-if="hasAccess.laporan_luas_tanam"
					>Luas Tanam</sidenav-router-link
				>
				<sidenav-router-link
					to="/laporan/tambah_tanam"
					:exact="true"
					:active="isMenuActive('/laporan/tambah_tanam')"
					v-if="hasAccess.laporan_tambah_tanam"
					>Tambah Tanam</sidenav-router-link
				>
				<sidenav-router-link
					to="/laporan/luas_panen"
					:exact="true"
					:active="isMenuActive('/laporan/luas_panen')"
					v-if="hasAccess.laporan_luas_panen"
					>Luas Panen</sidenav-router-link
				>
				<sidenav-router-link
					to="/laporan/produksi"
					:exact="true"
					:active="isMenuActive('/laporan/produksi')"
					v-if="hasAccess.laporan_produksi"
					>Produksi</sidenav-router-link
				>
				<sidenav-router-link
					to="/laporan/produktivitas"
					:exact="true"
					:active="isMenuActive('/laporan/produktivitas')"
					v-if="hasAccess.laporan_produktivitas"
					>Produktivitas</sidenav-router-link
				>
				<sidenav-router-link
					to="/laporan/luastanam"
					:exact="true"
					:active="isMenuActive('/laporan/luastanam')"
					v-if="hasAccess.laporan_luastanam_saat_ini"
					>Luas Tanam Saat Ini</sidenav-router-link
				>
				<sidenav-router-link
					to="/laporan/htp"
					:exact="true"
					:active="isMenuActive('/laporan/htp')"
					v-if="hasAccess.laporan_htp"
					>Harga Tingkat Petani</sidenav-router-link
				>
				<sidenav-router-link
					to="/laporan/opsin"
					:exact="true"
					:active="isMenuActive('/laporan/opsin')"
					v-if="hasAccess.laporan_opsin"
					>Alsin</sidenav-router-link
				>
				<sidenav-router-link
					to="/laporan/realisasi_opsin"
					:exact="true"
					:active="isMenuActive('/laporan/realisasi_opsin')"
					v-if="hasAccess.laporan_opsin_detail"
					>Realisasi Alsin</sidenav-router-link
				>
				<sidenav-router-link
					to="/laporan/rekap_input_penyuluh"
					:exact="true"
					:active="isMenuActive('/laporan/rekap_input_penyuluh')"
					v-if="hasAccess.laporan_rekap_input_penyuluh"
					>Rekap Input Penyuluh</sidenav-router-link
				>
				<sidenav-router-link
					to="/laporan/rekap_user_penyuluh"
					:exact="true"
					:active="isMenuActive('/laporan/rekap_user_penyuluh')"
					v-if="hasAccess.laporan_rekap_user_penyuluh"
					>Rekap User Penyuluh</sidenav-router-link
				>
			</sidenav-menu>
			
			<!-- Pengumuman -->
			<sidenav-router-link 
				icon="ion ion-md-megaphone" 
				to="/pengumuman" 
				:active="isMenuActive('/pengumuman')"
				v-if="hasAccess.pengumuman"
				:exact="true">Pengumuman
			</sidenav-router-link>
			
			<!-- Syarat & Ketentuan -->
			<sidenav-router-link 
				icon="ion ion-md-document" 
				to="/syarat-ketentuan/data" 
				:active="isMenuActive('/syarat-ketentuan/data')"
				v-if="hasAccess.syarat_ketentuan"
				:exact="true">Syarat & Ketentuan
			</sidenav-router-link>
			
			<!-- Kebijakan Privasi -->
			<sidenav-router-link 
				icon="ion ion-md-lock" 
				to="/kebijakan-privasi/data" 
				:active="isMenuActive('/kebijakan-privasi/data')"
				v-if="hasAccess.kebijakan_privasi"
				:exact="true">Kebijakan Privasi
			</sidenav-router-link>

			<!-- Managemen User -->
			<sidenav-menu
				icon="ion ion-ios-people"
				:active="isMenuActive('/user')"
				:open="isMenuOpen('/user')"
				v-if="hasAccess.managemen_user"
			>
				<template slot="link-text">Managemen User</template>
				<sidenav-router-link
					to="/user/list"
					:exact="true"
					:active="isMenuActive('/user/list')"
					v-if="hasAccess.user"
					>Data User</sidenav-router-link
				>
				<sidenav-router-link
					to="/user/level"
					:exact="true"
					:active="isMenuActive('/user/level')"
					v-if="hasAccess.level"
					>Level User</sidenav-router-link
				>
			</sidenav-menu>
			
			<!-- Download -->
			<sidenav-router-link 
				icon="ion ion-md-download" 
				to="/download" 
				:active="isMenuActive('/download')"
				v-if="hasAccess.download"
				:exact="true">Download
			</sidenav-router-link>

			<li class="sidenav-divider mb-1"></li>

			<!-- Profile -->
			<sidenav-router-link 
				icon="ion ion-ios-person" 
				to="/profile" 
				:active="isMenuActive('/profile')"
				:exact="true">Profile
			</sidenav-router-link>

			<!-- Logout -->
			<div class="sidenav-item">
				<a href="#" class="sidenav-link" @click="logout"
					><i class="sidenav-icon ion ion-ios-log-out"></i>
					<div>Logout</div>
				</a>
			</div>

		</div>
	</sidenav>
</template>

<style>
	.app-brand.demo {
		height: 66px;
	}
</style>

<script>
import { Sidenav, SidenavLink, SidenavRouterLink, SidenavMenu, SidenavHeader, SidenavBlock, SidenavDivider } from '@/vendor/libs/sidenav'
import { mapActions } from "vuex";

export default {
	name: 'app-layout-sidenav',
	components: {
		Sidenav,
		SidenavLink,
		SidenavRouterLink,
		SidenavMenu,
		SidenavHeader,
		SidenavBlock,
		SidenavDivider
	},
	data: () => ({
		listLevel: {},
		hasAccess: {}
	}),

	props: {
		orientation: {
			type: String,
			default: 'vertical'
		}
	},

	computed: {
		curClasses () {
			let bg = this.layoutSidenavBg

			if (this.orientation === 'horizontal' && (bg.indexOf(' sidenav-dark') !== -1 || bg.indexOf(' sidenav-light') !== -1)) {
				bg = bg
				.replace(' sidenav-dark', '')
				.replace(' sidenav-light', '')
				.replace('-darker', '')
				.replace('-dark', '')
			}
			return `bg-${bg} ` + (
				this.orientation !== 'horizontal'
				? 'layout-sidenav'
				: 'layout-sidenav-horizontal container-p-x flex-grow-0'
			)
		},

		user() {
			return this.$store.getters["auth/user"];
		},

		penyuluh(){
            const user = this.$store.state.auth.user;
            if(user && user.penyuluh != null){
                return 1
            }
            return 0
        }
	},

	methods: {
		...mapActions({
			setAuth: "auth/set",
			setRole: "auth/role",
		}),
		
		isMenuActive (url) {
			return this.$route.path.indexOf(url) === 0
		},

		isMenuOpen (url) {
			return this.$route.path.indexOf(url) === 0 && this.orientation !== 'horizontal'
		},

		toggleSidenav () {
			this.layoutHelpers.toggleCollapsed()
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

		// Load level dan ACL
        loadLevel(){
			axios
				.get(this.publicUrl + "api/level")
				.then((response) => {
					// handle success
					this.listLevel = response.data.data;
					this.loadAcl()
				});
		},
		
		loadAcl(){
			if (this.user) {
				if (this.user.level != 0) {
					this.hasAccess = {
						master: false,
						penyuluh: false,
						wilayah_kerja: false,
						komoditas: false,
						komoditas_htp: false,
						jenis_alsin: false,
						nama_opt: false,
						data: false,
						baku_lahan: false,
						data_luas: false,
						htp: false,
						alsin: false,
						pengumuman: false,
						syarat_ketentuan: false,
						kebijakan_privasi: false,
						managemen_user: false,
						user: false,
						level: false,
						kota: false,
						korwil: false,
						download: false,
						laporan: false,
						laporan_bakulahan: false,
						laporan_rencana_tanam: false,
						laporan_realisasi_tanam: false,
						laporan_htp: false,
						laporan_luas_tanam: false,
						laporan_tambah_tanam: false,
						laporan_luas_panen: false,
						laporan_produksi: false,
						laporan_produktivitas: false,
						laporan_luastanam_saat_ini: false,
						laporan_rekap_input_penyuluh: false,
						laporan_rekap_user_penyuluh: false,
						laporan_opsin: false,
						laporan_opsin_detail: false,
					}
					let data_rule = this.listLevel.find(o => o.level === this.user.level)
					
					if (data_rule.rule.find(o => o.name === 'master')) {	
						this.hasAccess.master = data_rule.rule.find(o => o.name === 'master').active
					}
					if (data_rule.rule.find(o => o.name === 'wilayah_kerja')) {	
						this.hasAccess.wilayah_kerja = data_rule.rule.find(o => o.name === 'wilayah_kerja').active
					}
					if (data_rule.rule.find(o => o.name === 'kota')) {	
						this.hasAccess.kota = data_rule.rule.find(o => o.name === 'kota').active
					}
					if (data_rule.rule.find(o => o.name === 'penyuluh')) {	
						this.hasAccess.penyuluh = data_rule.rule.find(o => o.name === 'penyuluh').active
					}
					if (data_rule.rule.find(o => o.name === 'komoditas')) {
						this.hasAccess.komoditas = data_rule.rule.find(o => o.name === 'komoditas').active
					}
					if (data_rule.rule.find(o => o.name === 'komoditas_htp')) {	
						this.hasAccess.komoditas_htp = data_rule.rule.find(o => o.name === 'komoditas_htp').active
					}
					if (data_rule.rule.find(o => o.name === 'jenis_alsin')) {	
						this.hasAccess.jenis_alsin = data_rule.rule.find(o => o.name === 'jenis_alsin').active
					}
					if (data_rule.rule.find(o => o.name === 'nama_opt')) {	
						this.hasAccess.nama_opt = data_rule.rule.find(o => o.name === 'nama_opt').active
					}
					if (data_rule.rule.find(o => o.name === 'data')) {	
						this.hasAccess.data = data_rule.rule.find(o => o.name === 'data').active
					}
					if (data_rule.rule.find(o => o.name === 'baku_lahan')) {	
						this.hasAccess.baku_lahan = data_rule.rule.find(o => o.name === 'baku_lahan').active
					}
					if (data_rule.rule.find(o => o.name === 'data_luas')) {	
						this.hasAccess.data_luas = data_rule.rule.find(o => o.name === 'data_luas').active
					}
					if (data_rule.rule.find(o => o.name === 'htp')) {	
						this.hasAccess.htp = data_rule.rule.find(o => o.name === 'htp').active
					}
					if (data_rule.rule.find(o => o.name === 'alsin')) {	
						this.hasAccess.alsin = data_rule.rule.find(o => o.name === 'alsin').active
					}
					if (data_rule.rule.find(o => o.name === 'pengumuman')) {	
						this.hasAccess.pengumuman = data_rule.rule.find(o => o.name === 'pengumuman').active
					}
					if (data_rule.rule.find(o => o.name === 'syarat_ketentuan')) {	
						this.hasAccess.syarat_ketentuan = data_rule.rule.find(o => o.name === 'syarat_ketentuan').active
					}
					if (data_rule.rule.find(o => o.name === 'kebijakan_privasi')) {	
						this.hasAccess.kebijakan_privasi = data_rule.rule.find(o => o.name === 'kebijakan_privasi').active
					}
					if (data_rule.rule.find(o => o.name === 'managemen_user')) {	
						this.hasAccess.managemen_user = data_rule.rule.find(o => o.name === 'managemen_user').active
					}
					if (data_rule.rule.find(o => o.name === 'user')) {	
						this.hasAccess.user = data_rule.rule.find(o => o.name === 'user').active
					}
					if (data_rule.rule.find(o => o.name === 'level')) {	
						this.hasAccess.level = data_rule.rule.find(o => o.name === 'level').active
					}
					if (data_rule.rule.find(o => o.name === 'korwil')) {	
						this.hasAccess.korwil = data_rule.rule.find(o => o.name === 'korwil').active
					}
					if (data_rule.rule.find(o => o.name === 'laporan')) {	
						this.hasAccess.laporan = data_rule.rule.find(o => o.name === 'laporan').active
					}
					if (data_rule.rule.find(o => o.name === 'laporan_bakulahan')) {	
						this.hasAccess.laporan_bakulahan = data_rule.rule.find(o => o.name === 'laporan_bakulahan').active
					}
					if (data_rule.rule.find(o => o.name === 'laporan_rencana_tanam')) {	
						this.hasAccess.laporan_rencana_tanam = data_rule.rule.find(o => o.name === 'laporan_rencana_tanam').active
					}
					if (data_rule.rule.find(o => o.name === 'laporan_realisasi_tanam')) {	
						this.hasAccess.laporan_realisasi_tanam = data_rule.rule.find(o => o.name === 'laporan_realisasi_tanam').active
					}
					if (data_rule.rule.find(o => o.name === 'laporan_htp')) {	
						this.hasAccess.laporan_htp = data_rule.rule.find(o => o.name === 'laporan_htp').active
					}
					if (data_rule.rule.find(o => o.name === 'laporan_luas_tanam')) {	
						this.hasAccess.laporan_luas_tanam = data_rule.rule.find(o => o.name === 'laporan_luas_tanam').active
					}
					if (data_rule.rule.find(o => o.name === 'laporan_tambah_tanam')) {	
						this.hasAccess.laporan_tambah_tanam = data_rule.rule.find(o => o.name === 'laporan_tambah_tanam').active
					}
					if (data_rule.rule.find(o => o.name === 'laporan_luas_panen')) {	
						this.hasAccess.laporan_luas_panen = data_rule.rule.find(o => o.name === 'laporan_luas_panen').active
					}
					if (data_rule.rule.find(o => o.name === 'laporan_produksi')) {	
						this.hasAccess.laporan_produksi = data_rule.rule.find(o => o.name === 'laporan_produksi').active
					}
					if (data_rule.rule.find(o => o.name === 'laporan_produktivitas')) {	
						this.hasAccess.laporan_produktivitas = data_rule.rule.find(o => o.name === 'laporan_produktivitas').active
					}
					if (data_rule.rule.find(o => o.name === 'download')) {	
						this.hasAccess.download = data_rule.rule.find(o => o.name === 'download').active
					}
					if (data_rule.rule.find(o => o.name === 'laporan_luastanam_saat_ini')) {	
						this.hasAccess.laporan_luastanam_saat_ini = data_rule.rule.find(o => o.name === 'laporan_luastanam_saat_ini').active
					}
					if (data_rule.rule.find(o => o.name === 'laporan_rekap_input_penyuluh')) {	
						this.hasAccess.laporan_rekap_input_penyuluh = data_rule.rule.find(o => o.name === 'laporan_rekap_input_penyuluh').active
					}
					if (data_rule.rule.find(o => o.name === 'laporan_rekap_user_penyuluh')) {	
						this.hasAccess.laporan_rekap_user_penyuluh = data_rule.rule.find(o => o.name === 'laporan_rekap_user_penyuluh').active
					}
					if (data_rule.rule.find(o => o.name === 'laporan_opsin')) {	
						this.hasAccess.laporan_opsin = data_rule.rule.find(o => o.name === 'laporan_opsin').active
					}
					if (data_rule.rule.find(o => o.name === 'laporan_opsin_detail')) {	
						this.hasAccess.laporan_opsin_detail = data_rule.rule.find(o => o.name === 'laporan_opsin_detail').active
					}
				}else{
					this.hasAccess = {
						master: true,
						penyuluh: true,
						wilayah_kerja: true,
						komoditas: true,
						komoditas_htp: true,
						jenis_alsin: true,
						nama_opt: true,
						data: true,
						baku_lahan: true,
						data_luas: true,
						htp: true,
						alsin: true,
						pengumuman: true,
						syarat_ketentuan: true,
						kebijakan_privasi: true,
						managemen_user: true,
						user: true,
						level: true,
						kota: true,
						korwil: true,
						download: true,
						laporan: true,
						laporan_bakulahan: true,
						laporan_rencana_tanam: true,
						laporan_realisasi_tanam: true,
						laporan_htp: true,
						laporan_luas_tanam: true,
						laporan_tambah_tanam: true,
						laporan_luas_panen: true,
						laporan_produksi: true,
						laporan_produktivitas: true,
						laporan_luastanam_saat_ini: true,
						laporan_rekap_input_penyuluh: true,
						laporan_rekap_user_penyuluh: true,
						laporan_opsin: true,
						laporan_opsin_detail: true,
					}
				}
			}
		},
	},

	created(){
		this.loadLevel()
	}
}
</script>
