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
                        <div class="d-flex justify-content-center align-items-center">
							<div class="text-center">
								<h4 class="font-weight-bolder mt-3 text-white">{{ data.title }}</h4>
							</div>
						</div>
                        <div class="text-center" v-if="isBusy">
                            <b-spinner label="Spinning" variant="danger"/>
                        </div>
                        <div class="text-justify" v-else v-html="data.konten"></div>
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
export default {
    name: "kebijakan-privasi",
    metaInfo: {
        title: "Kebijakan Privasi",
    },
    data: () => ({
		data: [],
        isBusy: false,
    }),
    methods:{
        loadData(){
            this.isBusy = true;
            // this.loadParams.id = this.user.penyuluh.id
            axios
				.get(this.publicUrl + "api/config/2")
				.then((response) => { 
					// handle success
                    this.data = response.data.data;
                    this.isBusy = false;
				})
				.catch((error) => {
					this.swr();
                });
        }
    },
    created(){
        this.loadData()
    }
};
</script>
