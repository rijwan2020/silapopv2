<template>  
    <div>
		<div class="d-flex justify-content-between align-items-center w-100 mb-0 border-bottom">
            <div class="d-flex align-items-center">
                <h5 class="m-1 d-inline-block text-nowrap font-weight-normal">
                    Master / Koordinator Wilayah
                </h5>
            </div>
			<div class="m-1">
                <b-btn
					variant="primary"
					v-b-tooltip.hover
					title="Tambah Koordinator Wilayah"
					@click="showModal('add')"
                    v-if="hasAccess.korwil_add"
					size="sm"
				>
					<i class="ion ion-md-add"></i> Tambah
				</b-btn>
            </div>
        </div>
		
		<b-card no-body>

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

					<template v-slot:cell(username)="data">
                        {{ data.item.user.username }}
                    </template>

					<template v-slot:cell(level)="data">
                        {{ data.item.level == 1 ? 'Kabupaten/Kota' : 'Kecamatan' }}
                    </template>

					<template v-slot:cell(wilayah)="data">
                        {{ data.item.level == 1 ? data.item.kota : ('Kecamatan ' + data.item.kecamatan + ', ' + data.item.kota) }}
                    </template>

					<!-- Action Template -->
					<template v-slot:cell(actions)="data">
						<b-btn
							variant="primary btn-sm icon-btn md-btn-flat"
							v-b-tooltip.hover
							:title="`Edit Koordinator Wilayah`"
							@click="showModal('edit', data.index)"
                            v-if="hasAccess.korwil_edit"
						>
							<i class="ion ion-md-create"></i>
						</b-btn>
						<b-btn
							variant="danger btn-sm icon-btn md-btn-flat"
							v-b-tooltip.hover
							:title="`Hapus Koordinator Wilayah`"
                            v-if="hasAccess.korwil_delete"
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

                <!-- Level -->
                <div class="col-md-2 px-0">
                    <b-form-group label="Level" class="w-auto m-1">
                        <b-select size="md" v-model="pilihLevel" :options="[{text: 'Semua', value: 'Semua'}, {text: 'Kota/Kabupaten', value: 1}, {text: 'Kecamatan', value: 2}]"/>
                    </b-form-group>
                </div>

                <!-- Kota -->
                <div class="col-md-3 px-0">
                    <b-form-group label="Kota" class="w-auto m-1">
                        <b-select size="md" v-model="pilihKota">
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
                        <b-select size="md" v-model="pilihKecamatan">
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
				{{ mode == "add" ? "Add" : "Update" }} Koordinator Wilayah
			</div>
            
            <!-- Pilih Penyuluh -->
            <b-form-row>
				<b-form-group label="Pilih Penyuluh" class="col">
                    <multiselect
                        v-model="pilihPenyuluh"
                        :options="penyuluhList"
                        :custom-label="showLabel"
                        placeholder="Pilih Penyuluh"
                        v-if="mode == 'add'"/>
                    <b-input v-else disabled :value="saveData.nama"></b-input>
				</b-form-group>
			</b-form-row>
            
            <!-- Level -->
            <b-form-row>
				<b-form-group label="Level" class="col">
					<b-form-radio-group
					v-model="saveData.level"
					:options="{1: 'Kabupaten/Kota', 2: 'Kecamatan'}"
					class="mb-3"
					name="level"
					></b-form-radio-group>
				</b-form-group>
			</b-form-row>

            <!-- Kota -->
			<b-form-row>
				<b-form-group label="Kota" class="col">
                    <b-select size="md" v-model="formKotaId">
                        <option value="0">Pilih</option>
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
			<b-form-row v-if="saveData.level == 2">
				<b-form-group label="Kecamatan" class="col">
                    <b-select size="md" v-model="formKecamatanId">
                        <option value="0">Pilih</option>
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

            
			<hr>
			<h5>Akun Login</h5>
			
            <!-- Username -->
			<b-form-row>
				<b-form-group label="Username" class="col">
					<b-input placeholder="Username" v-model="saveData.username" />
				</b-form-group>
			</b-form-row>
			
            <!-- Password -->
			<b-form-row>
				<b-form-group label="Password" class="col">
					<b-input placeholder="Password" v-model="saveData.password" type="password" />
				</b-form-group>
			</b-form-row>

            <!-- Konfirmasi Password -->
			<b-form-row>
				<b-form-group label="Konfirmasi Password" class="col">
					<b-input placeholder="Konfirmasi Password" v-model="saveData.konfirmasi_password" type="password" />
					<small v-if="mode == 'edit'">Kosongkan password jika tidak ingin merubahnya</small>
				</b-form-group>
			</b-form-row>
            
            <!-- Modal footer -->
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
			title="Hapus Koordinator Wilayah"
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
<style src="@/vendor/libs/vue-multiselect/vue-multiselect.scss" lang="scss"></style>

<script>
import LaddaBtn from "@/vendor/libs/ladda/Ladda";
import { SweetModal } from "sweet-modal-vue";
import Multiselect from 'vue-multiselect'

export default {
	name: 'korwil',
	metaInfo: {
		title: 'Koordinator Wilayah'
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

		q: '',

		data: [],

		currentPage: 1,

		isBusy: false,
		saveData: {},
		loading: false,
		mode: "add",
		index: null,
		titleDeleteModal: "Are you sure?",
		
        formKotaId: 0,
        formKecamatanList: [],
        formKecamatanId: 0,
		
        pilihKota: 'Pilih',
        pilihKecamatan: 'Pilih',
        kotaList: [],
        kecamatanList: [],
		
		id: null,
        penyuluhList: [],
        
		listLevel: {},
        hasAccess: {},
        pilihLevel: 'Semua',
        pilihPenyuluh: ''
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
            data[i++] = { 
                key: "nama", 
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
                key: "level", 
                label:"Level", 
                sortable: false, 
                tdClass: "align-middle", 
                thClass: "text-nowrap align-middle text-center" 
            }
            data[i++] = { 
                key: "wilayah", 
                label:"Wilayah", 
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
        pilihKota(v){
            this.loadData();
            if(v == 'Pilih'){
                this.kecamatanList = []
                this.pilihKecamatan = "Pilih";
            }else{
                this.loadListKecamatan('list');
            }
        },
        pilihLevel(){
            this.loadData()
        },
		formKotaId(v){
            if(v == 'Pilih'){
                this.formKecamatanList = []
                this.formKecamatanId = "Pilih";
            }else{
                this.loadListKecamatan('form');
            }
        },
        q(v) {
			if (v == '') {
				this.loadData()
			}
		},
    },
    methods:{
        showLabel({ nama }) {
            return `${nama}`
        },

        loadData(){
            this.isBusy = true;
            
            var params = {}

			params.page = this.currentPage;
			params.order_by = this.sortBy;
			params.sort_desc = this.sortDesc;
			params.limit = this.perPage;
			params.params = {};
			
            if (this.pilihKota != "Pilih") {
                params.params.kota_id = this.pilihKota
            }
            if (this.pilihKecamatan != "Pilih") {
                params.params.kecamatan_id = this.pilihKecamatan
            }
            if (this.pilihLevel != "Semua") {
                params.params.level = this.pilihLevel
            }
            if (this.q != '') {
				params.q = this.q
			}

			axios
				.get(this.publicUrl + "api/korwil", {
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

		loadPenyuluh(){
            axios
				.get(this.publicUrl + "api/penyuluh/all")
				.then((response) => {
					// handle success
                    this.penyuluhList = response.data.data;
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

        // Show Modal box add
        showModal(mode, i = null){
            this.index = i;
            this.$refs["modal-box"].show();
            this.mode = mode;
            if (mode == 'edit') {
				let data = this.data.data[i]
				this.penyuluh_id = data.penyuluh_id
                this.formKotaId = data.kota_id
                this.formKecamatanId = data.kecamatan_id
                this.id = data.id
                this.saveData = {
                    level: data.level,
                    nama: data.nama,
                    penyuluh_id: data.penyuluh_id,
                    username: data.user.username,
                    password: '',
                    konfirmasi_password: ''
                }
            }else{
                this.saveData = {
                    level: 1,
                    username: '',
                    password: '',
                    konfirmasi_password: '',
                }
				this.formKotaId = 0
				this.formKecamatanId = 0
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
            
            // this.loading = true;

            this.saveData.kota_id = this.formKotaId
            this.saveData.kecamatan_id = this.formKecamatanId
			this.saveData.user_id= this.user.id
			this.saveData.nama = this.pilihPenyuluh.nama
			this.saveData.penyuluh_id = this.pilihPenyuluh.id
            
            axios({
                method: "post",
                url: this.publicUrl + "api/korwil",
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
            this.saveData.user_id= this.user.id

            axios({
                method: "put",
                url: this.publicUrl + "api/korwil/" + this.id,
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
            if (this.mode == 'add' && this.pilihPenyuluh == "") {
                this.notif("danger", "Error", "Penyuluh belum dipilih.");
                return false;
            }
            if (this.formKotaId == "Pilih") {
                this.notif("danger", "Error", "Kota belum dipilih.");
                return false;
            }
            if (this.saveData.level == 2 && this.formKecamatanId == "Pilih") {
                this.notif("danger", "Error", "Kecamatan belum dipilih.");
                return false;
            }
            if (this.saveData.username == "") {
                this.notif("danger", "Error", "Username tidak boleh kosong.");
                return false;
			}
			if (this.mode == 'add' && this.saveData.password == "") {
				this.notif("danger", "Error", "Password tidak boleh kosong.");
                return false;
			}
			if (this.saveData.password != "" && this.saveData.password.length < 8) {
				this.notif("danger", "Error", "Password minimal 8 karakter.");
                return false;
			}
			if (this.saveData.password != "" && this.saveData.password != this.saveData.konfirmasi_password) {
				this.notif("danger", "Error", "Konfirmasi password tidak sesuai.");
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
                url: this.publicUrl + "api/korwil/" + this.id,
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
						korwil_add: false,
						korwil_edit: false,
						korwil_delete: false,

					}
					let data_rule = this.listLevel.find(o => o.level === this.user.level)
					
					if (data_rule.rule.find(o => o.name === 'korwil_add')) {	
						this.hasAccess.korwil_add = data_rule.rule.find(o => o.name === 'korwil_add').active
					}
					if (data_rule.rule.find(o => o.name === 'korwil_edit')) {	
						this.hasAccess.korwil_edit = data_rule.rule.find(o => o.name === 'korwil_edit').active
					}
					if (data_rule.rule.find(o => o.name === 'korwil_delete')) {	
						this.hasAccess.korwil_delete = data_rule.rule.find(o => o.name === 'korwil_delete').active
					}
				}else{
					this.hasAccess = {
						korwil_add: true,
						korwil_edit: true,
						korwil_delete: true,
					}
				}
			}
		},
    },
	created() {
        this.loadLevel()
		this.loadListKota();
		this.loadData();
        this.loadPenyuluh()
	}
}
</script>