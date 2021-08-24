<template>  
    <div>
		<div class="d-flex justify-content-between align-items-center w-100 mb-0 border-bottom">
            <div class="d-flex align-items-center">
                <h5 class="m-1 d-inline-block text-nowrap font-weight-normal">
                    Master / Wilayah Kerja
                </h5>
            </div>
			<div class="m-1">
                <b-btn
					variant="primary"
					v-b-tooltip.hover
					title="Tambah Data Luas"
					@click="showModal('add')"
                    v-if="hasAccess.wilayah_kerja_add"
					size="sm"
				>
					<i class="ion ion-md-add"></i> Tambah
				</b-btn>
            </div>
        </div>
		
		<b-card no-body>
			<!-- <b-card-body class="pb-2 pt-2">
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-sm-column flex-xl-row align-items-end">
                        <b-form-group label="Limit" class="d-inline-block w-auto m-1">
                            <b-select size="md" v-model="perPage" :options="[10, 20, 30, 40, 50]"/>
                        </b-form-group>
                    </div>
                </div>
			</b-card-body> -->
			<!-- / Table controls -->

			<!-- Table -->
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

					<template v-slot:cell(jenis_penyuluh)="data">
                        <b-badge variant="outline-info" v-if="data.item.jenis_penyuluh==0">
                            PPL
                        </b-badge>
                        <b-badge variant="outline-success" v-else>
                            POPT
                        </b-badge>
                    </template> 

					<!-- Action Template -->
					<template v-slot:cell(actions)="data">
						<b-btn
							variant="primary btn-sm icon-btn md-btn-flat"
							v-b-tooltip.hover
							:title="`Edit Wilayah Kerja`"
							@click="showModal('edit', data.index)"
                            v-if="hasAccess.wilayah_kerja_edit"
						>
							<i class="ion ion-md-create"></i>
						</b-btn>
						<b-btn
							variant="danger btn-sm icon-btn md-btn-flat"
							v-b-tooltip.hover
							:title="`Hapus Wilayah Kerja`"
                            v-if="hasAccess.wilayah_kerja_delete"
							@click="deleteModal(data.index)"
						>
							<i class="ion ion-md-trash"></i>
						</b-btn>
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
                <!-- Limit -->
                <div class="col-md-1 px-0">
                    <b-form-group label="Limit" class="w-auto m-1">
                        <b-select size="md" v-model="perPage" :options="[10, 20, 30, 40, 50]"/>
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

                <!-- Desa -->
                <div class="col-md-3 px-0">
                    <b-form-group label="Desa" class="w-auto m-1">
                        <b-select size="md" v-model="pilihDesa">
                            <option value="Pilih">Pilih</option>
                            <option
                                v-for="(desa, i) in desaList"
                                :key="i"
                                :value="desa.kelurahan_id"
                            >
                                {{ desa.name }}
                            </option>
                        </b-select>
                    </b-form-group>
                </div>
            </div>
        </b-card>

		
		<!-- Modal template -->
		<b-modal
			ref="modal-box"
			centered
			size="lg"
			no-close-on-esc
			no-close-on-backdrop
			hide-header-close
		>
			<div slot="modal-title">
				{{ mode == "add" ? "Add" : "Update" }} Wilayah Kerja
			</div>

            <!-- Penyuluh -->
			<b-form-row v-if="!penyuluh">
				<b-form-group label="Penyuluh" class="col">
                    <template v-if="mode == 'add'">
                        <multiselect
                            v-model="pilihPenyuluh"
                            :options="penyuluhList"
                            :multiple="false"
                            track-by="id"
                            placeholder="Pilih Penyuluh"
                            label="nama"
                        />
                    </template>
                    <template v-else>
                        <b-input v-model="penyuluhTerpilih" :disabled="true" />
                    </template>
				</b-form-group>
			</b-form-row>

            <!-- Kota -->
			<b-form-row>
				<b-form-group label="Kota" class="col">
                    <b-select size="md" v-model="formKotaId">
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
			</b-form-row>

            <!-- Kecamatan -->
			<b-form-row>
				<b-form-group label="Kecamatan" class="col">
                    <b-select size="md" v-model="formKecamatanId">
                        <option value="Pilih">Pilih</option>
                        <option
                            v-for="(kecamatan, i) in formKecamatanList"
                            :key="i"
                            :value="kecamatan.kecamatan_id"
                        >
                            {{ kecamatan.name }}
                        </option>
                    </b-select>
				</b-form-group>
			</b-form-row>

            <!-- Desa -->
			<b-form-row>
				<b-form-group label="Desa" class="col">
                    <b-select size="md" v-model="formDesaId">
                        <option value="Pilih">Pilih</option>
                        <option
                            v-for="(desa, i) in formDesaList"
                            :key="i"
                            :value="desa.kelurahan_id"
                        >
                            {{ desa.name }}
                        </option>
                    </b-select>
				</b-form-group>
			</b-form-row>

			<div slot="modal-footer">
				<b-btn
					variant="secondary"
					v-b-tooltip.hover
					title="Close"
					@click="hideModal()"
					>Close</b-btn
				>
				<ladda-btn
					:loading="loading"
					@click.native="process"
					data-style="zoom-out"
					class="btn btn-primary"
				>
					{{ mode == "add" ? "Save" : "Update" }}
				</ladda-btn>
			</div>
		</b-modal>
		
		<!-- Modal Delete -->
		<sweet-modal
			icon="warning"
			title="Delete User"
			ref="deleteModalAlert"
			hide-close-button
			blocking
		>
			{{ titleDeleteModal }}
			<template slot="button" style="text-align: center">
				<ladda-btn
					:loading="loading"
					@click.native="confirmDelete()"
					data-style="zoom-out"
					class="btn btn-primary"
					>Confirm</ladda-btn
				>
				<b-btn @click="$refs.deleteModalAlert.close()" variant="default"
					>Cancel</b-btn
				>
			</template>
		</sweet-modal>
    </div>
</template>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<script>
import LaddaBtn from "@/vendor/libs/ladda/Ladda";
import { SweetModal } from "sweet-modal-vue";
import Multiselect from 'vue-multiselect';

export default {
	name: 'wilayahkerja',
	metaInfo: {
		title: 'Wilayah Kerja'
    },
    components: {
        LaddaBtn,
        SweetModal,
        Multiselect
    },
    data: () => ({
		// Options
		sortBy: "id",
		sortDesc: false,
		perPage: 10,

		loadParams: {},

		data: [],

		currentPage: 1,

		isBusy: false,
		saveData: {},
		loading: false,
		mode: "add",
		index: null,
		titleDeleteModal: "Are you sure?",
		
        formKotaId: "Pilih",
        formKecamatanId: "Pilih",
        formDesaId: "Pilih",
        formKecamatanList: [],
		formDesaList: [],
		
        pilihKota: 'Pilih',
        pilihKecamatan: 'Pilih',
        pilihDesa: 'Pilih',
		pilihJenis: 'Pilih',
        kotaList: [],
        kecamatanList: [],
		desaList: [],
		
		id: null,
		penyuluh_id: "Pilih",
        pilihPenyuluh: "Pilih",
        penyuluhList: [],
        penyuluhTerpilih: "",
        
		listLevel: {},
		hasAccess: {},
		isKoordinatorKota: false,
		isKoordinatorKec: false,
		isKorwil: false,
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
			if (!this.penyuluh) {
				data[i++] = { 
					key: "name", 
					label:"Nama", 
					sortable: false, 
					tdClass: "align-middle", 
					thClass: "text-nowrap align-middle text-center" 
				}
				data[i++] = { 
					key: "username", 
					label:"Username", 
					sortable: false, 
					tdClass: "align-middle", 
					thClass: "text-nowrap align-middle text-center" 
				}
				data[i++] = { 
					key: "jenis_penyuluh", 
					label:"Jenis Penyuluh", 
					sortable: false, 
					tdClass: "align-middle", 
					thClass: "text-nowrap align-middle text-center" 
				}
			}
			data[i++] = { 
				key: "kota", 
				label:"Kota", 
				sortable: false, 
				tdClass: "align-middle", 
				thClass: "text-nowrap align-middle text-center" 
			}
			data[i++] = { 
				key: "kecamatan", 
				label:"Kecamatan", 
				sortable: false, 
				tdClass: "align-middle", 
				thClass: "text-nowrap align-middle text-center" 
			}
			data[i++] = { 
				key: "desa", 
				label:"Desa", 
				sortable: false, 
				tdClass: "align-middle", 
				thClass: "text-nowrap align-middle text-center" 
			}
			data[i] = {
				key: "actions",
				label: "Action",
				tdClass: "text-nowrap align-middle text-center",
				thClass: "text-nowrap align-middle text-center",
			}
			return data
		}
	},
	watch: {
        currentPage(v) {
            this.loadData(v);
        },
        perPage(v) {
            this.loadData();
		},
		formKotaId(v){
            if(v == 'Pilih'){
                this.formKecamatanList = []
                this.formKecamatanId = "Pilih";
                this.formDesaList = []
                this.formDesaId = "Pilih";
            }else{
                this.loadListKecamatan('form');
            }
        },
        
        formKecamatanId(v){
            if(v == 'Pilih'){
                this.formDesaList = []
                this.formDesaId = "Pilih";
            }else{
                this.loadListDesa('form');
            }
		},
		
		pilihKota(v){
            this.loadData();
            if(v == 'Pilih'){
                this.kecamatanList = []
                this.pilihKecamatan = "Pilih";
                this.desaList = []
                this.pilihDesa = "Pilih";
            }else{
                this.loadListKecamatan('list');
            }
        },

        pilihKecamatan(v){
            this.loadData();
            if(v == 'Pilih'){
                this.desaList = []
                this.pilihDesa = "Pilih";
            }else{
                this.loadListDesa('list');
            }
        },

        pilihDesa(){
            this.loadData();
        },

        pilihPenyuluh(v){
            this.penyuluh_id = v.id
        }
    },
    methods:{
        loadData(){
			this.isBusy = true;

			this.loadParams.page = this.currentPage;
			this.loadParams.order_by = this.sortBy;
			this.loadParams.sort_desc = this.sortDesc;
			this.loadParams.limit = this.perPage;
			this.loadParams.params = {};
			//jika yg login penyuluh
			if(this.user.penyuluh != null){
				this.loadParams.params.penyuluh_id = this.user.penyuluh.id
			}
			if (this.pilihKota != "Pilih") {
                this.loadParams.params.kota_id = this.pilihKota
            }
            if (this.pilihKecamatan != "Pilih") {
                this.loadParams.params.kecamatan_id = this.pilihKecamatan
            }
            if (this.pilihDesa != "Pilih") {
                this.loadParams.params.kelurahan_id = this.pilihDesa
            }

			axios
				.get(this.publicUrl + "api/wilayahkerja", {
					params: this.loadParams
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

        loadDataPenyuluh(){
            axios
				.get(this.publicUrl + "api/penyuluh/all")
				.then((response) => {
					// handle success
                    this.penyuluhList = response.data.data
				})
				.catch((error) => {
					this.swr();
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
		
		loadListKecamatan(show = 'list'){
            if (show == 'list') {
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
            }else{
                axios
                    .get(this.publicUrl + "api/kecamatan", {
                        params: {kota_id: this.formKotaId}
                    })
                    .then((response) => {
                        // handle success
                        this.formKecamatanList = response.data.data;
                    })
                    .catch((error) => {
                        this.swr();
                    });
            }
            
        },

        // load list data desa
        loadListDesa(show = "list"){
            if(show == "list"){
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
            }else{
                axios
                    .get(this.publicUrl + "api/desa", {
                        params: {kota_id: this.formKotaId, kecamatan_id: this.formKecamatanId}
                    })
                    .then((response) => {
                        // handle success
                        this.formDesaList = response.data.data;
                    })
                    .catch((error) => {
                        this.swr();
                    });
            }
        },

        // Show Modal box add
        showModal(mode, i = null){
            this.index = i;
            this.$refs["modal-box"].show();
            this.mode = mode;
            if (mode == 'edit') {
				let data = this.data.data[i]
				this.penyuluh_id = data.penyuluh_id
				this.penyuluhTerpilih = data.penyuluh.nama
                this.formKotaId = data.kota_id
                this.formKecamatanId = data.kecamatan_id
                this.formDesaId = data.kelurahan_id
                this.id = data.id
            }else{
				this.formKotaId = "Pilih"
				this.formKecamatanId = "Pilih"
				this.formDesaId = "Pilih"
            }
        },

        // Close modal add/edit baku lahan
        hideModal() {
            this.$refs["modal-box"].hide();
		},
		
		// Check proses input
        process() {
            if (this.mode == "add") {
                this.save();
            } else {
                this.update();
            }
		},
		
		// Proses insert data user
        save() {
            if (!this.validate()) {
                return;
            }
            
            this.loading = true;

            this.saveData.kota_id = this.formKotaId
            this.saveData.kecamatan_id = this.formKecamatanId
            this.saveData.kelurahan_id = this.formDesaId
			this.saveData.user_id= this.user.id
			this.saveData.penyuluh_id = this.penyuluh_id
            
            axios({
                method: "post",
                url: this.publicUrl + "api/wilayahkerja",
                data: this.saveData
            })
                .then((response) => {
                    this.id = response.data.data.id
                    this.notif("success", "Success", "Data berhasil ditambahkan");
                    this.hideModal();
                    this.saveData = {};
                    // reload data
                    this.loadData();
                })
                .catch((error) => {
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
                })
                .then(() => {
                    this.loading = false;
                });
		},

		// Proses update data
        update(){
            if (!this.validate()) {
                return;
            }
            this.loading = true;

            this.saveData.kota_id = this.formKotaId
            this.saveData.kecamatan_id = this.formKecamatanId
            this.saveData.kelurahan_id = this.formDesaId
            this.saveData.penyuluh_id= this.penyuluh_id
			this.saveData.user_id= this.user.id

            axios({
                method: "put",
                url: this.publicUrl + "api/wilayahkerja/" + this.id,
                data: this.saveData
            })
                .then((response) => {
                    this.notif("success", "Success", "Data berhasil diperbaharui.");
                    this.hideModal();
                    this.saveData = {};
                    this.loadData();
                })
                .catch((error) => {
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
                })
                .then(() => {
                    this.loading = false;
                });
        },
		
        // Validati input data
        validate() {
            if (this.formKotaId == "Pilih") {
                this.notif("danger", "Error", "Kota belum dipilih.");
                return false;
            }
            if (this.penyuluh_id == "Pilih") {
                this.notif("danger", "Error", "Penyuluh belum dipilih.");
                return false;
            }
            if (this.formKecamatanId == "Pilih") {
                this.notif("danger", "Error", "Kecamatan belum dipilih.");
                return false;
            }
            if (this.formDesaId == "Pilih") {
                this.notif("danger", "Error", "Desa belum dipilih.");
                return false;
            }
            return true;
		},
		
        // Show delete modal box
        deleteModal(i) {
            this.id = this.data.data[i].id;
            this.$refs.deleteModalAlert.open();
        },

        // Confirm delete data
        confirmDelete() {
            this.loading = true;
            axios({
                method: "delete",
                url: this.publicUrl + "api/wilayahkerja/" + this.id,
            })
                .then((response) => {
                    this.notif("success", "Success", "Data berhasil dihapus.");
                    this.$refs.deleteModalAlert.close();
                    // reload data
                    this.loadData();
                })
                .catch((error) => {
                    if(error.response.status == 400){
                        this.notif("warning", "Error", error.response.data.message);
                    } else {
                        this.swr();
                    }
                })
                .then(() => {
					this.$refs.deleteModalAlert.close();
                    this.loading = false;
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
						wilayah_kerja_add: false,
						wilayah_kerja_edit: false,
						wilayah_kerja_delete: false,

					}
					let data_rule = this.listLevel.find(o => o.level === this.user.level)
					
					if (data_rule.rule.find(o => o.name === 'wilayah_kerja_add')) {	
						this.hasAccess.wilayah_kerja_add = data_rule.rule.find(o => o.name === 'wilayah_kerja_add').active
					}
					if (data_rule.rule.find(o => o.name === 'wilayah_kerja_edit')) {	
						this.hasAccess.wilayah_kerja_edit = data_rule.rule.find(o => o.name === 'wilayah_kerja_edit').active
					}
					if (data_rule.rule.find(o => o.name === 'wilayah_kerja_delete')) {	
						this.hasAccess.wilayah_kerja_delete = data_rule.rule.find(o => o.name === 'wilayah_kerja_delete').active
					}
				}else{
					this.hasAccess = {
						wilayah_kerja_add: true,
						wilayah_kerja_edit: true,
						wilayah_kerja_delete: true,
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
        this.loadLevel()
		if(this.penyuluh){
			this.penyuluh_id = this.user.penyuluh.id
		}
		this.loadListKota();
		this.loadData();
		this.loadKorwil();
		this.loadDataPenyuluh();
	}
}
</script>
