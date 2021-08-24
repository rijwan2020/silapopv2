<template>  
    <div>
		<div class="d-flex justify-content-between align-items-center w-100 mb-0 border-bottom">
            <div class="d-flex align-items-center">
                <h5 class="m-1 d-inline-block text-nowrap font-weight-normal">
                    Laporan / Baku Lahan
                </h5>
            </div>
        </div>
        
		<b-card no-body>
			<!-- Table -->
            <div class="text-center">
                <h4 class="mt-3 mb-1"><b>Laporan Luas Baku Lahan Tahun {{ tahun }}</b></h4>
                <h5 class="mb-1">{{ pilihJenis == 'Pilih' ? 'Sawah dan Ladang' : (pilihJenis == 1 ? 'Sawah' : 'Ladang') }}</h5>
                <h4>{{ judul | lower | capital }}</h4>
            </div>
			<hr class="border-light m-0" />
			<div class="table-responsive">
				<b-table
					:items="data.data"
					:fields="fields"
					:striped="true"
					:bordered="true"
					:current-page="1"
					:busy="isBusy"
					class="card-table"
				>

					<template v-slot:cell(id)="data">
                        {{ data.index + 1 }}
                    </template>

					<!-- Busi Loading -->
					<template v-slot:table-busy>
						<div class="text-center text-danger my-2">
							<b-spinner class="align-middle"></b-spinner>
							<strong>Loading...</strong>
						</div>
					</template>
				</b-table>
			</div>
		</b-card>
		
        <!-- Filter Data -->
        <b-card no-body class="px-3 py-2 mt-2">
			<div class="row">

                <!-- Tahun -->
                <div class="col-md-1 px-0">
                    <b-form-group label="Tahun" class="w-auto m-1">
                        <b-select size="md" v-model="tahun" :options="tahunList"/>
                    </b-form-group>
                </div>

                <!-- Jenis -->
                <div class="col-md-1 px-0">
                    <b-form-group label="Jenis" class="w-auto m-1">
                        <b-select size="md" v-model="pilihJenis">
                            <option value="Pilih">Pilih</option>
                            <option value="1">Sawah</option>
                            <option value="2">Ladang</option>
                        </b-select>
                    </b-form-group>
                </div>
                
                <!-- Kota -->
                <div class="col-md-3 px-0">
                    <b-form-group label="Kota" class="w-auto m-1">
                        <b-select size="md" v-model="pilihKota" :disabled="isKoordinatorKota">
                            <option value="Pilih">Pilih</option>
                            <option
                                v-for="(kota, i) in kotaList"
                                :key="i"
                                :value="kota.kota_id"
                            >
                                {{ kota.name }}
                            </option>
                        </b-select>
                    </b-form-group>
                </div>

                <!-- Kecamatan -->
                <div class="col-md-3 px-0">
                    <b-form-group label="Kecamatan" class="w-auto m-1">
                        <b-select size="md" v-model="pilihKecamatan" :disabled="isKoordinatorKec">
                            <option value="Pilih">Pilih</option>
                            <option
                                v-for="(kecamatan, i) in kecamatanList"
                                :key="i"
                                :value="kecamatan.kecamatan_id"
                            >
                                {{ kecamatan.name }}
                            </option>
                        </b-select>
                    </b-form-group>
                </div>

                <div class="col-md-4 px-0 text-right">
                    <b-btn
                        variant="success"
                        v-b-tooltip.hover
                        title="Download Data"
                        @click="download()"
                        class="mt-3"
                        v-if="hasAccess.laporan_bakulahan_download"
                    >
                        <i class="ion ion-md-download"></i> Download
                    </b-btn>
                </div>

            </div>
        </b-card>

    </div>
</template>

<script>
export default {
	name: 'laporan-bakulahan',
	metaInfo: {
		title: 'Laporan Baku Lahan'
    },
    data: () => ({
		loadParams: {},
		data: [],
		isBusy: false,
		pilihLevel: "Semua",
		hasAccess: {},
        tahun: '',
        pilihJenis: "Pilih",
        judul: "Provinsi Jawa Barat",
        pilihKota: "Pilih",
        kotaList: {},
        kecamatanList: {},
        pilihKecamatan: "Pilih",
        isKoordinatorKota: false,
		isKoordinatorKec: false,
	}),
	computed: {
		user(){
			return this.$store.state.auth.user;
		},
		
		penyuluh(){
			const user = this.user
			if(user && user.penyuluh != null){
				return true;
			}
			return false;
		},

		fields(){
			const data = []
			let i = 0
			data[i++] = { 
				key: "id", 
				label:"#", 
				sortable: false, 
				tdClass: "align-middle", 
				thClass: "text-nowrap align-middle text-center" 
            }
            var label = 'Kota/Kabupaten';
            if (this.pilihKota != "Pilih") {
                label = "Kecamatan"
                if (this.pilihKecamatan != "Pilih") {
                    label = "Kelurahan/Desa"
                }
            }
			data[i++] = { 
				key: "nama", 
				label:label, 
				sortable: false, 
				tdClass: "align-middle", 
				thClass: "text-nowrap align-middle text-center" 
			}
			data[i++] = { 
				key: "bakulahan", 
				label:"Luas Baku Lahan [Ha]", 
				sortable: false, 
				tdClass: "align-middle", 
				thClass: "text-nowrap align-middle text-center" 
			}
			data[i++] = { 
				key: "rencana_tanam", 
				label:"Rencana Tanam [Ha]", 
				sortable: false, 
				tdClass: "align-middle", 
				thClass: "text-nowrap align-middle text-center" 
			}
			data[i++] = { 
				key: "realisasi_tanam", 
				label:"Realisasi Tanam [Ha]", 
				sortable: false, 
				tdClass: "align-middle", 
				thClass: "text-nowrap align-middle text-center" 
			}
			return data
        },

        tahunList(){
            const min_year = 2016;
            const max_year = new Date().getFullYear();
            var year_option = [];
            for (let i = max_year; i >= min_year; i--) {
                year_option.push(i);
            }
            return year_option;
        },
    },
    filters:{
        upper(value){
            if(!value) return ''
            value = value.toString()
            return value.toUpperCase()
        },
        lower(value){
            if(!value) return ''
            value = value.toString()
            return value.toLowerCase()
        },
        capital(value){
            if(!value) return ''

            const mySentence = value.toString();
            const words = mySentence.split(" ");

            for (let i = 0; i < words.length; i++) {
                words[i] = words[i][0].toUpperCase() + words[i].substr(1);
            }

            value = words.join(" ");

            return value
        }
    },
	watch: {
        
        tahun() {
            this.loadData();
		},
        pilihJenis() {
            this.loadData();
        },
        pilihKota(v){
            this.pilihKecamatan = "Pilih";
            if(v == 'Pilih'){
                this.kecamatanList = []
                this.judul = 'Provinsi Jawa Barat'
            }else{
                this.loadListKecamatan();
                let kota = this.kotaList.find(o => o.kota_id === v)
                this.judul = kota.name + ', Provinsi Jawa Barat'
            }
            
            this.loadData();
        },
        pilihKecamatan(v){
            let kota = this.kotaList.find(o => o.kota_id === this.pilihKota)
            if (kota == undefined) {
                this.judul = "Provinsi Jawa Barat";
            }else{
                if(v == 'Pilih'){
                    this.judul = kota.name + ', Provinsi Jawa Barat'
                }else{
                    let kecamatan = this.kecamatanList.find(o => o.kecamatan_id === v)
                    this.judul = 'Kecamatan ' + kecamatan.name + ', ' + kota.name + ', Provinsi Jawa Barat'
                }
            }
            this.loadData();
        }
    },
    methods:{
        loadData(){
            this.isBusy = true;
            var param = {}
            param.tahun = this.tahun;
            if(this.pilihJenis != "Pilih"){
                param.jenis = this.pilihJenis
            }
            if (this.pilihKota != "Pilih") {
                param.kota_id = this.pilihKota
            }
            if (this.pilihKecamatan != "Pilih") {
                param.kecamatan_id = this.pilihKecamatan
            }

			axios
				.get(this.publicUrl + "api/laporan/bakulahan", {
					params: param
				})
				.then((response) => {
					// handle success
                    this.data = response.data;
					this.isBusy = false;
				})
				.catch((error) => {
					this.swr();
				});
        },

        download(){
            var param = {}
            param.tahun = this.tahun;
            if(this.pilihJenis != "Pilih"){
                param.jenis = this.pilihJenis
            }
            if (this.pilihKota != "Pilih") {
                param.kota_id = this.pilihKota
            }
            if (this.pilihKecamatan != "Pilih") {
                param.kecamatan_id = this.pilihKecamatan
            }
            param.user_id = this.user.id

            this.notif("info", "Success", "Data sedang disiapkan, silakan cek secara berkala di menu download");
            axios({
                method: "post",
                url: this.publicUrl + "api/laporan/bakulahan/download",
                data: param
            }).catch((error) => {
                if (error.response.status == 422) {
                    for (let i in error.response.data.errors) {
                        this.notif(
                            "danger",
                            "Error",
                            error.response.data.errors[i][0]
                        );
                    }
                }else if(error.response.status == 400){
                    this.notif(
                        "warning",
                        "Error",
                        error.response.data.message
                    );
                }else {
                    this.swr();
                }
            });
        },
        
        loadListKota(){
            axios
				.get(this.publicUrl + "api/kota")
				.then((response) => {
					// handle success
                    this.kotaList = response.data.data;
				})
				.catch((error) => {
					this.swr();
                });
        },

        loadListKecamatan(){
            axios
                .get(this.publicUrl + "api/kecamatan", {
                    params: {kota_id: this.pilihKota}
                })
                .then((response) => {
                    // handle success
                    this.kecamatanList = response.data.data;
                })
                .catch((error) => {
                    this.swr();
                });
            
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
						laporan_bakulahan_download: false,
						laporan_bakulahan_print: false,
                    }
					let data_rule = this.listLevel.find(o => o.level === this.user.level)
					if (data_rule) {
						if (data_rule.rule.find(o => o.name === 'laporan_bakulahan_download')) {	
							this.hasAccess.laporan_bakulahan_download = data_rule.rule.find(o => o.name === 'laporan_bakulahan_download').active
						}
						if (data_rule.rule.find(o => o.name === 'laporan_bakulahan_print')) {	
							this.hasAccess.laporan_bakulahan_print = data_rule.rule.find(o => o.name === 'laporan_bakulahan_print').active
						}
					}
				}else{
					this.hasAccess = {
						laporan_bakulahan_download: true,
						laporan_bakulahan_print: true,
					}
				}
			}
        },

        loadKorwil(){
			if (this.user) {
				const user = this.user
				if (user.level == 96) {
					axios
						.get(this.publicUrl + "api/korwil/byUser/" + user.id)
						.then((response) => {
							// handle success
							this.isKorwil = response.data.data;
							this.isKoordinatorKota = true
							this.pilihKota = response.data.data.kota_id
						})
						.catch((error) => {
							this.swr();
						});
				}
				if (user.level == 95) {
					axios
						.get(this.publicUrl + "api/korwil/byUser/" + user.id)
						.then((response) => {
							// handle success
							this.isKorwil = response.data.data;
							this.isKoordinatorKota = true
							this.isKoordinatorKec = true
							this.pilihKota = response.data.data.kota_id
							this.pilihKecamatan = response.data.data.kecamatan_id
						})
						.catch((error) => {
							this.swr();
						});
				}
				
			}
		},
    },
	created() {
        this.tahun = new Date().getFullYear();
		this.loadData();
        this.loadLevel();
        this.loadListKota();
        this.loadKorwil();
	}
}
</script>