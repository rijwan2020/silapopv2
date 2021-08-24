<template>  
    <div>
		<div class="d-flex justify-content-between align-items-center w-100 mb-0 border-bottom">
            <div class="d-flex align-items-center">
                <h5 class="m-1 d-inline-block text-nowrap font-weight-normal">
                    Managemen User / Data User
                </h5>
            </div>
			<div class="m-1">
                <b-btn
					variant="primary"
					v-b-tooltip.hover
					title="Tambah User"
					@click="showModal('add')"
					v-if="hasAccess.user_add"
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

					<template v-slot:cell(level)="data">
                        {{ data.item.roles.name }}
                    </template>

					<template v-slot:cell(active)="data">
						<b-badge variant="outline-success" v-if="data.item.active==1">
                            Aktif
                        </b-badge>
                        <b-badge variant="outline-danger" v-else>
                            Tidak Aktif
                        </b-badge>
                    </template>

					<!-- Action Template -->
					<template v-slot:cell(actions)="data">
						<b-btn
							variant="primary btn-sm icon-btn md-btn-flat"
							v-b-tooltip.hover
							:title="`Edit User`"
							@click="showModal('edit', data.index)"
							v-if="hasAccess.user_edit"
						>
							<i class="ion ion-md-create"></i>
						</b-btn>
						<b-btn
							variant="danger btn-sm icon-btn md-btn-flat"
							v-b-tooltip.hover
							:title="`Hapus User`"
							@click="deleteModal(data.index)"
							v-if="hasAccess.user_delete"
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

                <!-- Status -->
                <div class="col-md-2 px-0">
                    <b-form-group label="Status" class="w-auto m-1">
                        <b-select size="md" v-model="pilihStatus">
                            <option value="Semua">Semua</option>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </b-select>
                    </b-form-group>
                </div>

                <!-- Rule -->
                <div class="col-md-2 px-0">
                    <b-form-group label="Rule" class="w-auto m-1">
                        <b-select size="md" v-model="pilihLevel">
                            <option value="Semua">Semua</option>
							<option
                                v-for="(rule, i) in listLevel"
                                :key="i"
                                :value="rule.level"
                            >
                                {{ rule.name }}
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
			hide-header-close >
			<div slot="modal-title">
				{{ mode == "add" ? "Tambah" : "Edit" }} User
			</div>
            
            <!-- Nama -->
			<b-form-row>
				<b-form-group label="Nama" class="col">
					<b-input placeholder="Nama" v-model="saveData.name" />
				</b-form-group>
			</b-form-row>

            <!-- Email -->
			<b-form-row>
				<b-form-group label="Email" class="col">
					<b-input placeholder="Email" v-model="saveData.email" type="email" />
				</b-form-group>
			</b-form-row>
			
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
					<small v-if="mode == 'edit'">Kosongkan password jika tidak ingin merubahnya</small>
				</b-form-group>
			</b-form-row>
			
            <!-- Level -->
			<b-form-row>
				<b-form-group label="Level" class="col">
                    <b-select size="md" v-model="saveData.level">
                        <option value="Pilih">Pilih</option>
                        <option
                            v-for="(level, i) in listLevel"
                            :key="i"
                            :value="level.level"
                        >
                            {{ level.name }}
                        </option>
                    </b-select>
				</b-form-group>
			</b-form-row>
			
            <!-- Status -->
            <b-form-row>
				<b-form-group label="Status" class="col">
					<b-form-radio-group
					v-model="saveData.active"
					:options="{0: 'Tidak Aktif', 1: 'Aktif'}"
					class="mb-3"
					></b-form-radio-group>
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
			title="Delete Nama OPT"
			ref="deleteModalAlert"
			hide-close-button
			blocking >
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
	name: 'data-user',
	metaInfo: {
		title: 'Data User'
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
		listLevel: [],

		isBusy: false,
		saveData: {},
		loading: false,
		mode: "add",
		index: null,
		titleDeleteModal: "Are you sure?",
		
        id: null,
		pilihStatus: "Semua",
		pilihLevel: "Semua",
		hasAccess: {
			user_add: false,
			user_edit: false,
			user_delete: false
		}
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
				label:"Rule", 
				sortable: false, 
				tdClass: "align-middle", 
				thClass: "text-nowrap align-middle text-center" 
			}
			data[i++] = { 
				key: "active", 
				label:"Status", 
				sortable: false, 
				tdClass: "align-middle", 
				thClass: "text-nowrap align-middle text-center" 
			}
			data[i++] = { 
				key: "email", 
				label:"Email", 
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
	watch: {
        currentPage() {
            this.loadData();
        },
        perPage() {
            this.loadData();
		},
        pilihStatus() {
            this.loadData();
		},
        pilihLevel() {
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
			params.params = {};
			if (this.pilihStatus != "Semua") {
				params.params.active = this.pilihStatus
			}
			if (this.pilihLevel != "Semua") {
				params.params.level = this.pilihLevel
			}
            if (this.q != '') {
				params.q = this.q
			}

			axios
				.get(this.publicUrl + "api/user", {
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
					let data_rule = this.listLevel.find(o => o.level === this.user.level)
					if (data_rule) {
						if (data_rule.rule.find(o => o.name === 'user_add')) {	
							this.hasAccess.user_add = data_rule.rule.find(o => o.name === 'user_add').active
						}
						if (data_rule.rule.find(o => o.name === 'user_edit')) {	
							this.hasAccess.user_edit = data_rule.rule.find(o => o.name === 'user_edit').active
						}
						if (data_rule.rule.find(o => o.name === 'user_delete')) {	
							this.hasAccess.user_delete = data_rule.rule.find(o => o.name === 'user_delete').active
						}
					}
				}else{
					this.hasAccess = {
						user_add: true,
						user_edit: true,
						user_delete: true,
					}
				}
			}
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
                    email: data.email,
                    username: data.username,
                    level: data.level,
					active: data.active,
					password: ''
                }
                this.id = data.id
            }else{
				this.saveData = {
                    name: '',
                    email: '',
                    username: '',
                    password: '',
                    level: 'Pilih',
                    active: 0,
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
                url: this.publicUrl + "api/user",
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
                url: this.publicUrl + "api/user/" + this.id,
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
                this.notif("danger", "Error", "Nama tidak boleh kosong.");
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
                this.notif("danger", "Error", "Password tidak boleh kurang dari 8 karakter.");
                return false;
            }
            if (this.saveData.level == "Pilih") {
                this.notif("danger", "Error", "Level belum dipiih.");
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
                url: this.publicUrl + "api/user/" + this.id,
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
    },
	created() {
		this.loadLevel();
		this.loadData();
	}
}
</script>