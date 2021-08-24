<template>  
    <div>
		<div class="d-flex justify-content-between align-items-center w-100 mb-0 border-bottom">
            <div class="d-flex align-items-center">
                <h5 class="m-1 d-inline-block text-nowrap font-weight-normal">
                    Laporan / Harga Tingkat Petani
                </h5>
            </div>
			<div class="m-1">
                <router-link class="btn btn-sm btn-info" to="/laporan/htp/area">
                    Laporan Htp Area
                </router-link>
            </div>
        </div>
        
		<b-card no-body>
			<!-- Table -->
            <div class="text-center">
                <h4 class="mt-3 mb-1"><b>Laporan Harga Tingkat Petani Tahun {{ tahun }}</b></h4>
                <h5 class="mb-1">Komoditi {{ pilihKategori == 'Pilih' ? 'Gabah/Beras dan Palawija' : (pilihKategori == 1 ? 'Gabah/Beras' : 'Palawija') }}</h5>
                <h4>{{ judul | lower | capital }}</h4>
            </div>
			<hr class="border-light m-0" />
			<div class="table-responsive">
                <template v-if="isBusy">
                    <div class="text-center text-danger my-2">
                        <b-spinner class="align-middle"></b-spinner>
                        <strong>Loading...</strong>
                    </div>
                </template>
                <template v-else>
                    <table class="table table-striped table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th class="align-middle" rowspan="2">#</th>
                                <th class="align-middle" rowspan="2">Komoditi</th>
                                <th colspan="12">Bulan [Rp/Kg]</th>
                                <th class="align-middle" rowspan="2">Rata Rata [Rp/Kg]</th>
                                <th class="align-middle" rowspan="2">Min [Rp/Kg]</th>
                                <th class="align-middle" rowspan="2">Max [Rp/Kg]</th>
                            </tr>
                            <tr>
                                <th v-for="(bulan, i) in listBulan" :key="i">
                                    <span @click="toDetail(i + 1)" style="cursor: pointer">{{ bulan }}</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(value, i) in data.data" :key="i">
                                <td>{{ i + 1 }}</td>
                                <td>
                                    {{ value.nama }}
                                </td>
                                <td class="text-right" v-for="(res, j) in value.bulan" :key="j">
                                    {{ res }}
                                </td>
                                <td class="text-right">{{ value.rata_rata }}</td>
                                <td class="text-right">{{ value.min }}</td>
                                <td class="text-right">{{ value.max }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="text-right">
                                <th colspan="2">Rata Rata [Rp/Kg]</th>
                                <th v-for="(avr, j) in data.average" :key="j">
                                    {{ avr.rata_rata }}
                                </th>
                            </tr>
                            <tr class="text-right">
                                <th colspan="2">Min [Rp/Kg]</th>
                                <th v-for="(avr, j) in data.average" :key="j">
                                    {{ avr.min }}
                                </th>
                            </tr>
                            <tr class="text-right">
                                <th colspan="2">Max [Rp/Kg]</th>
                                <th v-for="(avr, j) in data.average" :key="j">
                                    {{ avr.max }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="mt-2 ml-2">
                        <small>
                            Catatan:
                            <ul>
                                <li>Klik pada nama bulan untuk melihat detail bulanan</li>
                            </ul>
                        </small>
                    </div>
                </template>

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

                <!-- Kategori -->
                <div class="col-md-2 px-0">
                    <b-form-group label="Kategori" class="w-auto m-1">
                        <b-select size="md" v-model="pilihKategori">
                            <option value="Pilih">Pilih</option>
                            <option value="1">Gabah/Beras</option>
                            <option value="2">Palawija</option>
                        </b-select>
                    </b-form-group>
                </div>
                
                <!-- Kota -->
                <div class="col-md-2 px-0">
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
                <div class="col-md-2 px-0">
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

                <!-- Desa -->
                <div class="col-md-2 px-0">
                    <b-form-group label="Desa" class="w-auto m-1">
                        <b-select size="md" v-model="pilihDesa">
                            <option value="Pilih">Pilih</option>
                            <option
                                v-for="(kelurahan, i) in desaList"
                                :key="i"
                                :value="kelurahan.kelurahan_id"
                            >
                                {{ kelurahan.name }}
                            </option>
                        </b-select>
                    </b-form-group>
                </div>

                <div class="col-md-3 px-0 text-right">
                    <b-btn
                        variant="success"
                        v-b-tooltip.hover
                        title="Download Data"
                        @click="download()"
                        class="mt-3"
                        v-if="hasAccess.laporan_htp_download"
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
	name: 'laporan-htp',
	metaInfo: {
		title: 'Laporan Harga Tingkat Petani'
    },
    data: () => ({
		data: [],
		isBusy: false,
		pilihLevel: "Semua",
		hasAccess: {},
        tahun: '',
        pilihKategori: "Pilih",
        judul: "Provinsi Jawa Barat",
        pilihKota: "Pilih",
        kotaList: {},
        pilihKecamatan: "Pilih",
        kecamatanList: {},
        pilihDesa: "Pilih",
        desaList: {},
        isKoordinatorKota: false,
        isKoordinatorKec: false,
        listBulan: [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ],
        field_nama: 'Kota/Kabupaten'
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
        pilihKategori() {
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
                this.pilihDesa = "Pilih";
                if(v == 'Pilih'){
                    this.desaList = []
                    this.judul = kota.name + ', Provinsi Jawa Barat'
                }else{
                    this.loadListDesa()
                    let kecamatan = this.kecamatanList.find(o => o.kecamatan_id === v)
                    this.judul = 'Kecamatan ' + kecamatan.name + ', ' + kota.name + ', Provinsi Jawa Barat'
                }
            }
            this.loadData();
        },
        pilihDesa(v){
            let kota = this.kotaList.find(o => o.kota_id === this.pilihKota)
            if (kota == undefined) {
                this.judul = "Provinsi Jawa Barat";
            }else{
                let kecamatan = this.kecamatanList.find(o => o.kecamatan_id === this.pilihKecamatan)
                if (kecamatan == undefined) {
                    this.judul = kota.name + ", Provinsi Jawa Barat";
                }else{
                    if(v == 'Pilih'){
                        this.judul = 'Kecamatan ' + kecamatan.name + ', ' + kota.name + ', Provinsi Jawa Barat'
                    }else{
                        let desa = this.desaList.find(o => o.kelurahan_id === v)
                        this.judul = 'Desa ' + desa.name + ', Kecamatan ' + kecamatan.name + ', ' + kota.name + ', Provinsi Jawa Barat'
                    }
                }
            }
            this.loadData();
        }
    },
    methods:{
        loadData(){
            this.isBusy = true;

            var params = {}

            params.tahun = this.tahun;
            params.params = {}
            if(this.pilihKategori != "Pilih"){
                params.params.kategori_id = this.pilihKategori
            }
            if (this.pilihKota != "Pilih") {
                params.kota_id = this.pilihKota
                if (this.pilihKecamatan != "Pilih") {
                    params.kecamatan_id = this.pilihKecamatan
                    if (this.pilihDesa != "Pilih") {
                        params.kelurahan_id = this.pilihDesa
                    }
                }
            }

			axios
				.get(this.publicUrl + "api/laporan/htp", {
					params: params
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

        toDetail(i){
            var params = {}

            params.tahun = this.tahun;
            params.bulan = i
            if(this.pilihKategori != "Pilih"){
                params.kategori_id = this.pilihKategori
            }
            if (this.pilihKota != "Pilih") {
                params.kota_id = this.pilihKota
                if (this.pilihKecamatan != "Pilih") {
                    params.kecamatan_id = this.pilihKecamatan
                    if (this.pilihDesa != "Pilih") {
                        params.kelurahan_id = this.pilihDesa
                    }
                }
            }
            this.$router.push({name: 'laporan_htp_detail', params: params});
        },

        download(){
            var params = {}

            params.tahun = this.tahun;
            params.params = {}
            if(this.pilihKategori != "Pilih"){
                params.params.kategori_id = this.pilihKategori
            }
            if (this.pilihKota != "Pilih") {
                params.kota_id = this.pilihKota
                if (this.pilihKecamatan != "Pilih") {
                    params.kecamatan_id = this.pilihKecamatan
                    if (this.pilihDesa != "Pilih") {
                        params.kelurahan_id = this.pilihDesa
                    }
                }
            }
            params.user_id = this.user.id

            this.notif("info", "Success", "Data sedang disiapkan, silakan cek secara berkala di menu download");
            axios({
                method: "post",
                url: this.publicUrl + "api/laporan/htp/download",
                data: params
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

        loadListDesa(){
            axios
                .get(this.publicUrl + "api/desa", {
                    params: {kota_id: this.pilihKota, kecamatan_id: this.pilihKecamatan}
                })
                .then((response) => {
                    // handle success
                    this.desaList = response.data.data;
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
						laporan_htp_download: false,
						laporan_htp_print: false,
                    }
					let data_rule = this.listLevel.find(o => o.level === this.user.level)
					if (data_rule) {
						if (data_rule.rule.find(o => o.name === 'laporan_htp_download')) {	
							this.hasAccess.laporan_htp_download = data_rule.rule.find(o => o.name === 'laporan_htp_download').active
						}
						if (data_rule.rule.find(o => o.name === 'laporan_htp_print')) {	
							this.hasAccess.laporan_htp_print = data_rule.rule.find(o => o.name === 'laporan_htp_print').active
						}
					}
				}else{
					this.hasAccess = {
						laporan_htp_download: true,
						laporan_htp_print: true,
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