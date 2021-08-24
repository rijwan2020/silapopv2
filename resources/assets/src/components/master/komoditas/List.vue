<template>  
    <div>
		<div class="d-flex justify-content-between align-items-center w-100 mb-0 border-bottom">
            <div class="d-flex align-items-center">
                <h5 class="m-1 d-inline-block text-nowrap font-weight-normal">
                    Master / Komoditas
                </h5>
            </div>
			<div class="m-1">
                <b-btn
					variant="primary"
					v-b-tooltip.hover
					title="Tambah Komoditas"
					@click="showModal('add')"
					size="sm"
					v-if="hasAccess.komoditas_add"
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

					<!-- Action Template -->
					<template v-slot:cell(actions)="data">
						<b-btn
							variant="primary btn-sm icon-btn md-btn-flat"
							v-b-tooltip.hover
							:title="`Edit Komoditas`"
							@click="showModal('edit', data.index)"
							v-if="hasAccess.komoditas_edit"
						>
							<i class="ion ion-md-create"></i>
						</b-btn>
						<b-btn
							variant="danger btn-sm icon-btn md-btn-flat"
							v-b-tooltip.hover
							:title="`Hapus Komoditas`"
							@click="deleteModal(data.index)"
							v-if="hasAccess.komoditas_delete"
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
				{{ mode == "add" ? "Tambah" : "Edit" }} Komoditas
			</div>

            
            <!-- Nama Komoditas -->
			<b-form-row>
				<b-form-group label="Nama Komoditas" class="col">
					<b-input placeholder="Nama Komoditas" v-model="saveData.nama" />
				</b-form-group>
			</b-form-row>

            <!-- Keterangan -->
			<b-form-row>
				<b-form-group label="Keterangan" class="col">
                    <b-textarea v-model="saveData.keterangan" style="height: 100px" placeholder="Keterangan" />
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
			title="Delete Komoditas"
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
	name: 'komoditas',
	metaInfo: {
		title: 'Komoditas'
    },
    components: {
        LaddaBtn,
        SweetModal
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
		
		id: null,
        
		listLevel: {},
		hasAccess: {}
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
				key: "nama", 
				label:"Nama Komoditas", 
				sortable: false, 
				tdClass: "align-middle", 
				thClass: "text-nowrap align-middle text-center" 
			}
			data[i++] = { 
				key: "keterangan", 
				label:"Keterangan", 
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
        q(v) {
			if (v == '') {
				this.loadData()
			}
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
				params.q = this.q
			}
			params.params = {};

			axios
				.get(this.publicUrl + "api/komoditas", {
					params: params
				})
				.then((response) => {
					// handle success
                    this.data = response.data.data;
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
                    nama: data.nama,
                    keterangan: data.keterangan
                }
                this.id = data.id
            }else{
				this.saveData = {
                    nama: '',
                    keterangan: ''
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

            this.saveData.user_id= this.user.id
            
            axios({
                method: "post",
                url: this.publicUrl + "api/komoditas",
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

			this.saveData.user_id= this.user.id

            axios({
                method: "put",
                url: this.publicUrl + "api/komoditas/" + this.id,
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
            if (this.saveData.nama == "") {
                this.notif("danger", "Error", "Nama komoditas tidak boleh kosong.");
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
                url: this.publicUrl + "api/komoditas/" + this.id,
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
						komoditas_add: false,
						komoditas_edit: false,
						komoditas_delete: false,

					}
					let data_rule = this.listLevel.find(o => o.level === this.user.level)
					
					if (data_rule.rule.find(o => o.name === 'komoditas_add')) {	
						this.hasAccess.komoditas_add = data_rule.rule.find(o => o.name === 'komoditas_add').active
					}
					if (data_rule.rule.find(o => o.name === 'komoditas_edit')) {	
						this.hasAccess.komoditas_edit = data_rule.rule.find(o => o.name === 'komoditas_edit').active
					}
					if (data_rule.rule.find(o => o.name === 'komoditas_delete')) {	
						this.hasAccess.komoditas_delete = data_rule.rule.find(o => o.name === 'komoditas_delete').active
					}
				}else{
					this.hasAccess = {
						komoditas_add: true,
						komoditas_edit: true,
						komoditas_delete: true,
					}
				}
			}
		},
    },
	created() {
        this.loadLevel();
		this.loadData();
	}
}
</script>