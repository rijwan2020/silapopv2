<template>  
    <div>
		<div class="d-flex justify-content-between align-items-center w-100 mb-0 border-bottom">
            <div class="d-flex align-items-center">
                <h5 class="m-1 d-inline-block text-nowrap font-weight-normal">
                    Laporan / Rekap User Penyuluh
                </h5>
            </div>
        </div>
		
		<b-card no-body>

			<!-- Table -->
            <div class="text-center">
                <h4 class="mt-3"><b>Laporan Rekap User Penyuluh</b></h4>
            </div>
			<hr class="border-light m-0" />
			<div class="table-responsive">
				<b-table
					:items="data.data"
					:fields="fields"
					:sort-by.sync="sortBy"
					:sort-desc.sync="sortDesc"
					:striped="true"
					:bordered="true"
					:current-page="1"
					:per-page="perPage"
					:busy="isBusy"
					class="card-table"
				>

					<template v-slot:cell(id)="data">
                        {{ data.item.id!=0?(data.index + 1 + ((currentPage-1) * perPage)):'-'}}
                    </template>

                    <template v-slot:cell(jp)="data">
						<b-badge variant="outline-success" v-if="data.item.jp==1">
                            POPT
                        </b-badge>
                        <b-badge variant="outline-info" v-else>
                            PPL
                        </b-badge>
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

			<!-- Pagination -->
			<b-card-body class="pt-0 pb-3">
				<div class="row">
					<div class="col-sm text-sm-left text-center pt-3">
						<span class="text-muted" v-if="totalItems"
							>Page {{ currentPage }} of {{ totalPages }}
							<br />
							Total Record : {{ totalItems }}
						</span>
					</div>
					<div class="col-sm pt-3">
						<b-pagination
							class="justify-content-center justify-content-sm-end m-0"
							v-if="totalItems"
							v-model="currentPage"
							:total-rows="totalItems"
							:per-page="perPage"
							size="md"
						/>
					</div>
				</div>
			</b-card-body>
			<!-- / Pagination -->
		</b-card>

		<!-- Filter Data -->
        <b-card no-body class="px-3 py-2 mt-2">
            <div class="row">
				<!-- Kota -->
                <div class="col-md-4 px-0">
                    <b-form-group label="Kota" class="w-auto m-1">
                        <b-select size="md" v-model="filterKota">
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
                <div class="col-md-4 px-0">
                    <b-form-group label="Kecamatan" class="w-auto m-1">
                        <b-select size="md" v-model="filterKecamatan">
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
                <div class="col-md-4 px-0">
                    <b-form-group label="Desa" class="w-auto m-1">
                        <b-select size="md" v-model="filterKelurahan">
							<option value="Pilih">Pilih</option>
							<option
								v-for="(kelurahan, i) in kelurahanList"
								:key="i"
								:value="kelurahan.kelurahan_id"
							>
								{{ kelurahan.name }}
							</option>
						</b-select>
                    </b-form-group>
                </div>
                <!-- Limit -->
                <div class="col-md-1 px-0">
                    <b-form-group label="Limit" class="w-auto m-1">
                        <b-select size="md" v-model="perPage" :options="[10, 20, 30, 40, 50]"/>
                    </b-form-group>
                </div>
                <!-- Jenis Penyuluh -->
                <div class="col-md-2 px-0">
                    <b-form-group label="Jenis Penyuluh" class="w-auto m-1">
                        <b-select size="md" v-model="filterJenisPenyuluh" :options="[{text: 'Pilih', value: 'Pilih'}, {text: 'POPT', value: 1}, {text: 'PPL', value: 0}]"/>
                    </b-form-group>
                </div>
                <!-- Pencarian -->
                <div class="col-md-3 px-0">
                    <b-form-group label="Pencarian" class="w-auto m-1">
						<b-input-group>
							<b-input v-model="q" placeholder="Pencarian" />
							<b-btn variant="success" @click="loadData()">
								<i class="ion ion-md-search"></i>
							</b-btn>
						</b-input-group>
                    </b-form-group>
                </div>

                <div class="col-md-6 px-0 text-right">
                    <b-btn
                        variant="success"
                        v-b-tooltip.hover
                        title="Download Data"
                        @click="download()"
                        class="mt-3"
                        v-if="hasAccess.laporan_rekap_user_penyuluh_download"
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
	name: 'laporan-rekap-user-penyuluh',
	metaInfo: {
		title: 'Laporan Rekap User Penyuluh'
    },
    data: () => ({
		// Options
		sortBy: "id",
		sortDesc: false,
		perPage: 10,
		filterJenisPenyuluh: "Pilih",
		data: [],
		currentPage: 1,
		isBusy: false,
		q: '', 
		
		listLevel: {},
		hasAccess: {},
        fields: [
            {key: 'id', label:"#", sortable: false, tdClass: "align-middle", thClass: "align-middle text-center"},
            {key: 'nama', label:"Nama", sortable: false, tdClass: "align-middle", thClass: "align-middle text-center"},
            {key: 'jp', label:"Jenis Penyuluh", sortable: false, tdClass: "align-middle", thClass: "align-middle text-center"},
            {key: 'no_hp', label:"No Telepon", sortable: false, tdClass: "align-middle", thClass: "align-middle text-center"},
            {key: 'tempat_tugas', label:"Tempat Tugas", sortable: false, tdClass: "align-middle", thClass: "align-middle text-center"},
            // {key: 'kota', label:"Kota/Kabupaten", sortable: false, tdClass: "align-middle", thClass: "align-middle text-center"},
            // {key: 'kecamatan', label:"Kecamatan", sortable: false, tdClass: "align-middle", thClass: "align-middle text-center"},
            // {key: 'kelurahan', label:"Kelurahan/Desa", sortable: false, tdClass: "align-middle", thClass: "align-middle text-center"},
            {key: 'username', label:"Username", sortable: false, tdClass: "align-middle", thClass: "align-middle text-center"},
		],
		
        filterKota: "Pilih",
        filterKecamatan: "Pilih",
        filterKelurahan: "Pilih",
        kotaList: [],
        kecamatanList: [],
        kelurahanList: [],
	}),
	computed: {
		totalItems() {
			return this.data.total;
		},

		totalPages() {
			return Math.ceil(this.totalItems / this.perPage);
		},
		
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

	},
	watch: {
        currentPage() {
            this.loadData();
        },
        perPage() {
            this.loadData();
		},
        filterJenisPenyuluh() {
            this.loadData();
		},
        q(v) {
			if (v == '') {
				this.loadData()
			}
		},
		filterKota(v){
            if(v == 'Pilih'){
                this.kecamatanList = []
                this.filterKecamatan = "Pilih";
                this.kelurahanList = []
                this.filterKelurahan = "Pilih";
            }else{
                this.loadListKecamatan();
			}
			this.loadData();
		},

		filterKecamatan(v){
            if(v == 'Pilih'){
                this.kelurahanList = []
                this.filterKelurahan = "Pilih";
            }else{
                this.loadListKelurahan();
			}
			this.loadData();
        },
        
		filterKelurahan(){
			this.loadData();
		},
    },
    methods:{
        loadData(){
			this.isBusy = true;
			var params = {}

			params.page = this.currentPage;
			params.order_by = this.sortBy;
			params.sort_desc = this.sortDesc;
			params.limit = this.perPage;
			if (this.q != '') {
				params.q = this.q;
			}
			params.params = {};
			if (this.filterJenisPenyuluh != "Pilih") {
				params.params.jp = this.filterJenisPenyuluh
			}

			if (this.filterKota != "Pilih") {
				params.params.tempat_tugas_kota_id = this.filterKota
				if (this.filterKecamatan != "Pilih") {
					params.params.tempat_tugas_kecamatan_id = this.filterKecamatan
					if (this.filterKelurahan != "Pilih") {
						params.params.tempat_tugas_kelurahan_id = this.filterKelurahan
					}
				}
			}

			axios
				.get(this.publicUrl + "api/laporan/rekap_user_penyuluh", {
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
        download(){
            var params = {}
            if (this.q != '') {
				params.q = this.q;
			}
			params.params = {};
			if (this.filterJenisPenyuluh != "Pilih") {
				params.params.jp = this.filterJenisPenyuluh
			}
			if (this.filterKota != "Pilih") {
				params.params.tempat_tugas_kota_id = this.filterKota
				if (this.filterKecamatan != "Pilih") {
					params.params.tempat_tugas_kecamatan_id = this.filterKecamatan
					if (this.filterKelurahan != "Pilih") {
						params.params.tempat_tugas_kelurahan_id = this.filterKelurahan
					}
				}
			}
            params.user_id = this.user.id

            this.notif("info", "Success", "Data sedang disiapkan, silakan cek secara berkala di menu download");
            axios({
                method: "post",
                url: this.publicUrl + "api/laporan/rekap_user_penyuluh/download",
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
						laporan_rekap_user_penyuluh_download: false,
					}
					let data_rule = this.listLevel.find(o => o.level === this.user.level)
					
					if (data_rule.rule.find(o => o.name === 'laporan_rekap_user_penyuluh_download')) {	
						this.hasAccess.laporan_rekap_user_penyuluh_download = data_rule.rule.find(o => o.name === 'laporan_rekap_user_penyuluh_download').active
					}
				}else{
					this.hasAccess = {
						laporan_rekap_user_penyuluh_download: true
					}
				}
			}
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
                    params: {kota_id: this.filterKota}
                })
                .then((response) => {
                    // handle success
                    this.kecamatanList = response.data.data;
                })
                .catch((error) => {
                    this.swr();
                });
        },

        // load list data desa
        loadListKelurahan(){
			axios
                .get(this.publicUrl + "api/desa", {
                    params: {kota_id: this.filterKota, kecamatan_id: this.filterKecamatan}
                })
                .then((response) => {
                    // handle success
                    this.kelurahanList = response.data.data;
                })
                .catch((error) => {
                    this.swr();
                });
        },
    },
	created() {
		this.loadLevel();
		this.loadData();
        this.loadListKota();
	}
}
</script>