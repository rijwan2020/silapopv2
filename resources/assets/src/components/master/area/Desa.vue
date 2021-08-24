<template>  
    <div>
		<div class="d-flex justify-content-between align-items-center w-100 mb-0 border-bottom">
            <div class="d-flex align-items-center">
                <h5 class="m-1 d-inline-block text-nowrap font-weight-normal">
                    Master / Jawa Barat / {{ kota.name }} / {{ kecamatan.name }}
                </h5>
            </div>
			<div class="m-1">
                <b-btn
					variant="primary"
					v-b-tooltip.hover
					title="Tambah Desa"
					@click="showModal('add')"
					v-if="hasAccess.desa_add"
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
					:busy="isBusy"
					class="card-table"
				>

					<template v-slot:cell(id)="data">
                        {{ data.index + 1 }}
                    </template>

					<!-- Action Template -->
					<template v-slot:cell(actions)="data">
						<b-btn
							variant="primary btn-sm icon-btn md-btn-flat"
							v-b-tooltip.hover
							:title="`Edit Desa`"
							@click="showModal('edit', data.index)"
							v-if="hasAccess.desa_edit"
						>
							<i class="ion ion-md-create"></i>
						</b-btn>
						<b-btn
							variant="danger btn-sm icon-btn md-btn-flat"
							v-b-tooltip.hover
							:title="`Hapus Desa`"
							@click="deleteModal(data.index)"
							v-if="hasAccess.desa_delete"
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
							>Total Record : {{ totalItems }}
						</span>
					</div>
				</div>
			</b-card-body>
			<!-- / Pagination -->
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
				{{ mode == "add" ? "Tambah" : "Edit" }} Desa
			</div>
            
            <!-- Kota/Kabupaten -->
			<b-form-row>
				<b-form-group label="Kota/Kabupaten" class="col">
					<b-input placeholder="Kota/Kabupaten" v-model="kota.name" disabled/>
				</b-form-group>
			</b-form-row>
            
            <!-- Kecamatan -->
			<b-form-row>
				<b-form-group label="Kecamatan" class="col">
					<b-input placeholder="Kecamatan" v-model="kecamatan.name" disabled />
				</b-form-group>
			</b-form-row>
            
            <!-- Nama Desa -->
			<b-form-row>
				<b-form-group label="Nama Desa" class="col">
					<b-input placeholder="Nama Desa" v-model="saveData.name" />
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
			title="Hapus Desa"
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

<script>
import LaddaBtn from "@/vendor/libs/ladda/Ladda";
import { SweetModal } from "sweet-modal-vue";
export default {
	name: 'data-desa',
	metaInfo: {
		title: 'Area Desa'
    },
    components: {
        LaddaBtn,
        SweetModal
    },
    data: () => ({
		// Options
		sortBy: "id",
		sortDesc: false,
		data: [],

		isBusy: false,
		saveData: {},
		loading: false,
		mode: "add",
		index: null,
		titleDeleteModal: "Are you sure?",
		
		id: null,
        
		listLevel: {},
        hasAccess: {},
        kota: {},
        kecamatan: {},
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
			data[i++] = { 
				key: "name", 
				label:"Nama Kota", 
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
        },
        
	},
    methods:{
        
        loadKota(){
            this.isBusy = true;
            axios
                .get(this.publicUrl + "api/area/" + this.$route.params.kecId)
                .then((response) => {
                    this.kota = response.data.data
                    this.loadKecamatan();
                })
                .catch((error) => {
                    this.swr();
                });
        },
        
        loadKecamatan(){
            this.isBusy = true;
            axios
                .get(this.publicUrl + "api/area/" + this.$route.params.id)
                .then((response) => {
                    this.kecamatan = response.data.data
                    this.loadData();
                })
                .catch((error) => {
                    this.swr();
                });
        },

        loadData(){
			axios
				.get(this.publicUrl + "api/desa",{
                    params: {kota_id: this.kota.kota_id, kecamatan_id: this.kecamatan.kecamatan_id}
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

        // Show Modal box add
        showModal(mode, i = null){
            this.index = i;
            this.$refs["modal-box"].show();
            this.mode = mode;
            if (mode == 'edit') {
				let data = this.data.data[i]
				this.saveData = {
					name: data.name,
					provinsi_id: data.provinsi_id,
					kota_id: data.kota_id,
					kecamatan_id: data.kecamatan_id,
					kelurahan_id: data.kelurahan_id,
					koordinat: data.koordinat,
					level: 'desa'
                }
                this.id = data.id
            }else{
				this.saveData = {
                    name: '',
					provinsi_id: 32,
					kota_id: this.kota.kota_id,
					kecamatan_id: this.kecamatan.kecamatan_id,
					kelurahan_id: 0,
					koordinat: '',
					level: 'desa'
                }
            }
        },

        // Close modal add/edit
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
		
		// Proses insert data
        save() {
            if (!this.validate()) {
                return;
            }
            
            this.loading = true;
            
            axios({
                method: "post",
                url: this.publicUrl + "api/area",
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

            axios({
                method: "put",
                url: this.publicUrl + "api/area/" + this.id,
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
            if (this.saveData.name == "") {
                this.notif("danger", "Error", "Nama Kota tidak boleh kosong.");
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
                url: this.publicUrl + "api/area/" + this.id,
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
						desa_add: false,
						desa_edit: false,
						desa_delete: false,

					}
					let data_rule = this.listLevel.find(o => o.level === this.user.level)
					
					if (data_rule.rule.find(o => o.name === 'desa_add')) {	
						this.hasAccess.desa_add = data_rule.rule.find(o => o.name === 'desa_add').active
					}
					if (data_rule.rule.find(o => o.name === 'desa_edit')) {	
						this.hasAccess.desa_edit = data_rule.rule.find(o => o.name === 'desa_edit').active
					}
					if (data_rule.rule.find(o => o.name === 'desa_delete')) {	
						this.hasAccess.desa_delete = data_rule.rule.find(o => o.name === 'desa_delete').active
					}
				}else{
					this.hasAccess = {
						desa_add: true,
						desa_edit: true,
						desa_delete: true,
					}
				}
			}
		},
    },
	created() {
        this.loadLevel();
        this.loadKota();
	}
}
</script>