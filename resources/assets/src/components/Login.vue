<template>
	<div class="custom-login">
		<div class="authentication-wrapper authentication-3">
			<div class="leaf"></div>
			<div class="authentication-inner">

			<!-- Side container -->
			<!-- Do not display the container on extra small, small and medium screens -->
			<div class="d-none d-lg-flex col-lg-6 col-xl-7 align-items-center ui-bg-cover ui-bg-overlay-container p-5" style="background-image: url('/img/bg/bg-silapop.jpeg');">
				<div class="ui-bg-overlay bg-dark opacity-50"></div>

				<!-- Text -->
				<div class="w-100 text-white px-5 text-center ui-bg-text">
				<div class="logo-jabar">
					<img :src="publicUrl + 'img/login/jabarprov.svg'" alt="">
				</div>
				<h1 class="display-2 font-weight-bolder mb-1">SILAPOP</h1>
				<div class="text-large font-weight-light">
					Sistem Informasi laporan Online Penyuluh Pertanian
				</div>
				<h4 class="font-weight-bolder mt-4" style="line-height: 1.5;">Dinas Tanaman Pangan dan Hortikultura <br>JAWA BARAT</h4>
				</div>
				<!-- /.Text -->
			</div>
			<!-- / Side container -->

			<!-- Form container -->
			<div class="theme-bg-white d-flex col-lg-6 col-xl-5 align-items-center p-5 ui-bg-form">
				<!-- Inner container -->
				<!-- Have to add `.d-flex` to control width via `.col-*` classes -->
				<div class="d-flex flex-column col-sm-7 col-lg-12 px-0 px-xl-4 mx-auto ui-form">
					<div class="w-75 m-md-auto">

						<!-- Logo -->
						<div class="d-flex justify-content-center align-items-center">
							<div class="ui-w-100 text-center">
								<div class="w-100 position-relative">
									<img :src="publicUrl + 'img/login/logo.svg'" alt="">
								</div>
								<h4 class="font-weight-bolder mt-3 text-white">SILAPOP</h4>
							</div>
						</div>
						<!-- / Logo -->

						<h5 class="text-center font-weight-normal mt-0 mb-5 text-white">Sistem Informasi laporan Online Penyuluh Pertanian</h5>

						<!-- Form -->
						<form class="mt-2 mb-4">
							<div class="form-group">
								<label class="form-label text-white">Username</label>
								<b-input v-model="credentials.username" />
							</div>
							<div class="form-group">
								<label class="form-label d-flex justify-content-between align-items-end text-white">
									<div>Password</div>
									<!-- <a href="javascript:void(0)" class="d-block small">Forgot password?</a> -->
								</label>
								<b-input
									type="password"
									v-model="credentials.password"
								/>
							</div>
							<div class="d-flex justify-content-between align-items-center m-0">
								<!-- <label class="custom-control custom-checkbox m-0">
								<input type="checkbox" class="custom-control-input">
								<span class="custom-control-label">Remember me</span>
								</label> -->
								<ladda-btn
									@click.native="process"
									data-style="zoom-out"
									class="btn btn-primary d-block w-100 mt-3"
									>Login</ladda-btn
								>
							</div>
						</form>
						<!-- / Form -->

						<div class="text-center">
							<router-link to="/syarat-ketentuan">Syarat dan Ketentuan</router-link> | <router-link to="/kebijakan-privasi">Kebijakan Privasi</router-link>
							<br>
							<a :href="publicUrl + 'silapop.apk'" target="_blank"><span class="ion ion-md-download"></span> Download APK <span class="ion ion-md-download"></span></a>
						</div>

					</div>
					<div class="box-4vm mt-auto d-none d-lg-flex">
						Developed By 
						<a href="https://www.4visionmedia.com/id" target="_blank">
						<img :src="publicUrl + 'img/login/logo-4vm.svg'" alt="">
						</a>
					</div>
					<div class="box-jabar d-lg-none">
						<div class="logo-jabar-footer">
							<img :src="publicUrl + 'img/login/jabarprov.svg'" alt="">
						</div>
						<span style="line-height: 1.5; font-size: .87em;">Dinas Tanaman Pangan dan Hortikultura <br>PROVINSI JAWA BARAT</span>
					</div>
				</div>
			</div>
			<!-- / Form container -->

			</div>
		</div>
	</div>
</template>

<!-- Page -->
<style src="@/vendor/styles/pages/authentication.scss" lang="scss"></style>


<script>
	import LaddaBtn from "@/vendor/libs/ladda/Ladda";
	import { mapActions } from "vuex";
	export default {
		name: "login",
		metaInfo: {
			title: "Authenticate User",
		},
		components: {
			LaddaBtn,
		},
		data: () => ({
			credentials: {
				username: "",
				password: "",
				rememberMe: false,
			},
			aclList: [],
		}),
		methods: {
			...mapActions({
				setAuth: "auth/set",
				setRole: "auth/role",
			}),

			process() {
				if (this.credentials.username.trim() == "") {
					this.notif("danger", "Error", "Username field is required");
					return false;
				}
				if (this.credentials.password.trim() == "") {
					this.notif("danger", "Error", "Password field is required");
					return false;
                }
                // return false
				axios({
					method: "post",
					url: this.publicUrl + "api/login",
					data: this.credentials,
				})
					.then((response) => {
						this.notif("success", "Success", "Login Success");
						this.setAuth(response.data.data);
                        // this.loadAcl("id=" + response.data.data.data.role_id);
                        // window.location.href = '/';
                        this.$router.push({ name: "home" });
					})
					.catch((error) => {
						if (error.response.status == 422) {
							this.notif("danger", "Error", error.response.data.message);
						} else {
							this.swr();
						}
					})
			},
			// Load role acl
			/*loadAcl(param = "") {
				axios
					.get(this.publicUrl + "api/acl?" + param)
					.then((response) => {
						let hasil = {};
						response.data.map(function (value, key) {
							hasil[value.name] = value;
						});
						// this.setRole(response.data);
						this.setRole(hasil);
						this.$router.push({ name: "home" });
					})
					.catch((error) => {
						this.swr();
					});
			},*/
		},
	};
</script>
