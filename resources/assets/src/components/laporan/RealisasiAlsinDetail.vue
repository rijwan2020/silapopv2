<template>  
    <div>
		<div class="d-flex justify-content-between align-items-center w-100 mb-0 border-bottom">
            <div class="d-flex align-items-center">
                <h5 class="m-1 d-inline-block text-nowrap font-weight-normal">
                    Laporan / Realisasi Alsin / Detail
                </h5>
            </div>
        </div>
        
		<b-card no-body>
            <div class="text-center">
                <h4 class="mt-3 mb-1"><b>Laporan Realisasi Alsin {{ listBulan[bulan] }} {{ tahun }}</b></h4>
                <h5 class="mb-1">{{ field_jenis }}</h5>
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
                                <th class="align-middle" rowspan="3">#</th>
                                <th class="align-middle" rowspan="3">{{ field_nama }}</th>
                                <th :colspan="(jml_hari * 2)">Tanggal</th>
                                <th class="align-middle" rowspan="2" colspan="2">Jumlah</th>
                            </tr>
                            <tr>
                                <th v-for="(hari, i) in jml_hari" :key="i" colspan="2">
                                    {{ hari }}
                                </th>
                            </tr>
                            <tr>
                                <template v-for="i in (jml_hari*2)">
                                    <th :key="i" v-if="i%2">LTT/LTP</th>
                                    <th :key="i" v-else>Alsin</th>
                                </template>
                                <th>LTT/LTP</th>
                                <th>Alsin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(value, i) in data.data.data" :key="i">
                                <td>{{ i + 1 }}</td>
                                <td>{{ value.nama }}</td>
                                <td class="text-right" v-for="(res, j) in value.hari" :key="j">
                                    {{ res }}
                                </td>
                                <td class="text-right">{{ value.ltt_ltp }}</td>
                                <td class="text-right">{{ value.alsin }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="text-right">
                                <th colspan="2">Jumlah</th>
                                <th v-for="(jml, j) in data.jumlah" :key="j">
                                    {{ jml }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
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

                <!-- Bulan -->
                <div class="col-md-1 px-0">
                    <b-form-group label="Bulan" class="w-auto m-1">
                        <b-select size="md" v-model="bulan" :options="listBulan"/>
                    </b-form-group>
                </div>

                <!-- Jenis -->
                <div class="col-md-2 px-0">
                    <b-form-group label="Jenis" class="w-auto m-1">
                        <b-select size="md" v-model="pilihJenis">
                            <option value="Pilih">Pilih</option>
                            <option
                                v-for="(jenis, i) in jenisList"
                                :key="i"
                                :value="jenis.id"
                            >
                                {{ jenis.nama }}
                            </option>
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

                <div class="col-md-4 px-0 text-right">
                    <b-btn
                        variant="success"
                        v-b-tooltip.hover
                        title="Download Data"
                        @click="download()"
                        class="mt-3"
                        v-if="hasAccess.laporan_opsin_detail_download"
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
	name: 'laporan-realisasi-alsin-detail',
	metaInfo: {
		title: 'Laporan Realisasi Alsin'
    },
    data: () => ({
		data: [],
		isBusy: false,
		pilihLevel: "Semua",
		hasAccess: {},
        tahun: '',
        pilihJenis: "Pilih",
        jenisList: {},

        judul: "Provinsi Jawa Barat",
        pilihKota: "Pilih",
        kotaList: {},
        kecamatanList: {},
        pilihKecamatan: "Pilih",
        isKoordinatorKota: false,
        isKoordinatorKec: false,
        listBulan: {
            1: 'Januari',
            2: 'Februari',
            3: 'Maret',
            4: 'April',
            5: 'Mei',
            6: 'Juni',
            7: 'Juli',
            8: 'Agustus',
            9: 'September',
            10: 'Oktober',
            11: 'November',
            12: 'Desember'
        },
        field_nama: 'Kota/Kabupaten',
        bulan: '',
        jml_hari: 28,
        field_jenis: "Semua Jenis Alsin"
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
        bulan() {
            this.loadData();
		},
        pilihJenis(v) {
            this.field_jenis = 'Semua Jenis Alsin'
            if (v != "Pilih") {
                let jenis = this.jenisList.find(o => o.id === v)
                this.field_jenis =  jenis.nama
            }
            this.loadData();
        },
        pilihKota(v){
            this.pilihKecamatan = "Pilih";
            if(v == 'Pilih'){
                this.kecamatanList = []
                this.judul = 'Provinsi Jawa Barat'
                this.field_nama = 'Kota/Kabupaten'
            }else{
                this.loadListKecamatan();
                let kota = this.kotaList.find(o => o.kota_id === v)
                this.judul = kota.name + ', Provinsi Jawa Barat'
                this.field_nama = 'Kecamatan'
            }
            
            this.loadData();
        },
        pilihKecamatan(v){
            let kota = this.kotaList.find(o => o.kota_id === this.pilihKota)
            if (kota == undefined) {
                this.judul = "Provinsi Jawa Barat";
                this.field_nama = 'Kota/Kabupaten'
            }else{
                if(v == 'Pilih'){
                    this.judul = kota.name + ', Provinsi Jawa Barat'
                    this.field_nama = 'Kecamatan'
                }else{
                    let kecamatan = this.kecamatanList.find(o => o.kecamatan_id === v)
                    this.judul = 'Kecamatan ' + kecamatan.name + ', ' + kota.name + ', Provinsi Jawa Barat'
                    this.field_nama = 'Kelurahan/Desa'
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
            params.bulan = this.bulan;
            if(this.pilihJenis != "Pilih"){
                params.jenis_id = this.pilihJenis
            }
            if (this.pilihKota != "Pilih") {
                params.kota_id = this.pilihKota
            }
            if (this.pilihKecamatan != "Pilih") {
                params.kecamatan_id = this.pilihKecamatan
            }

			axios
				.get(this.publicUrl + "api/laporan/opsin/realisasi/detail", {
					params: params
				})
				.then((response) => {
					// handle success
                    this.data = response.data;
                    this.jml_hari = response.data.data.jml_hari
					this.isBusy = false;
				})
				.catch((error) => {
					this.swr();
				});
        },

        download(){
            var param = {}
            param.tahun = this.tahun;
            param.bulan = this.bulan;
            if(this.pilihJenis != "Pilih"){
                param.jenis_id = this.pilihJenis
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
                url: this.publicUrl + "api/laporan/opsin/realisasi/detail/download",
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
						laporan_opsin_detail_download: false,
						laporan_opsin_detail_print: false,
                    }
					let data_rule = this.listLevel.find(o => o.level === this.user.level)
					if (data_rule) {
						if (data_rule.rule.find(o => o.name === 'laporan_opsin_detail_download')) {	
							this.hasAccess.laporan_opsin_detail_download = data_rule.rule.find(o => o.name === 'laporan_opsin_detail_download').active
						}
						if (data_rule.rule.find(o => o.name === 'laporan_opsin_detail_print')) {	
							this.hasAccess.laporan_opsin_detail_print = data_rule.rule.find(o => o.name === 'laporan_opsin_detail_print').active
						}
					}
				}else{
					this.hasAccess = {
						laporan_opsin_detail_download: true,
						laporan_opsin_detail_print: true,
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
        
        loadParam(){
            // Set Tahun
            this.tahun = new Date().getFullYear();
            if (this.$route.params.tahun) {
                this.tahun = this.$route.params.tahun
            }
            // Set Bulan
            this.bulan = new Date().getMonth();
            if (this.$route.params.bulan) {
                this.bulan = this.$route.params.bulan
            }
            // Set Jenis
            if (this.$route.params.jenis) {
                this.pilihJenis = this.$route.params.jenis
            }
            // Set Kota
            if (this.$route.params.kota_id) {
                this.pilihKota = this.$route.params.kota_id
            }
            // Set Kecamatan
            if (this.$route.params.kecamatan_id) {
                this.pilihKecamatan = this.$route.params.kecamatan_id
            }
        },
        
        loadListJenis(){
            axios
				.get(this.publicUrl + "api/jenisalsin")
				.then((response) => {
					// handle success
                    this.jenisList = response.data.data;
				})
				.catch((error) => {
					this.swr();
                });
        },
    },
	created() {
        this.loadListKota();
        this.loadParam();
        this.loadLevel();
        this.loadKorwil();
		this.loadData();
		this.loadListJenis();
	}
}
</script>