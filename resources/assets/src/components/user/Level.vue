<template>  
    <div>
		<div class="d-flex justify-content-between align-items-center w-100 mb-0 border-bottom">
            <div class="d-flex align-items-center">
                <h5 class="m-1 d-inline-block text-nowrap font-weight-normal">
                    Managemen User / Level User
                </h5>
            </div>
			<div class="m-1">
                <b-btn
					variant="primary"
					v-b-tooltip.hover
					title="Tambah Level"
					@click="showModal('add')"
					size="sm"
				>
					<i class="ion ion-md-add"></i> Tambah
				</b-btn>
            </div>
        </div>
		
		<b-card no-body>
			<b-card-body class="pb-2 pt-2">
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-sm-column flex-xl-row align-items-end">
                        <b-form-group label="Limit" class="d-inline-block w-auto m-1">
                            <b-select size="md" v-model="perPage" :options="[10, 20, 30, 40, 50]"/>
                        </b-form-group>
                        <!-- <b-form-group label="Cari" class="d-inline-block w-auto m-1">
                            <b-input placeholder="Cari" v-model="q" />
                        </b-form-group> -->
                    </div>
                </div>
			</b-card-body>
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

					<!-- Action Template -->
					<template v-slot:cell(actions)="data">
						<b-btn
							variant="primary btn-sm icon-btn md-btn-flat"
							v-b-tooltip.hover
							:title="`Edit User`"
							@click="showModal('edit', data.index)"
						>
							<i class="ion ion-md-create"></i>
						</b-btn>
						<b-btn
							variant="danger btn-sm icon-btn md-btn-flat"
							v-b-tooltip.hover
							:title="`Hapus User`"
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

		
		<!-- Modal template -->
		<b-modal
			ref="modal-box"
			centered
			size="lg"
			no-close-on-backdrop
			hide-header-close >
			<div slot="modal-title">
				{{ mode == "add" ? "Tambah" : "Edit" }} Level User
			</div>
            
            <!-- No Level -->
			<b-form-row>
				<b-form-group label="No Level" class="col">
					<b-input placeholder="No Level" v-model="saveData.level" />
				</b-form-group>
			</b-form-row>

            <!-- Nama Level -->
			<b-form-row>
				<b-form-group label="Nama Level" class="col">
					<b-input placeholder="Nama Level" v-model="saveData.name" />
				</b-form-group>
			</b-form-row>

            <!-- Keterangan -->
			<b-form-row>
				<b-form-group label="Keterangan" class="col">
                    <b-textarea v-model="saveData.keterangan" style="height: 100px" placeholder="Keterangan" />
				</b-form-group>
			</b-form-row>
            
            <!-- Access Control List -->
			<b-form-row>
				<b-form-group label="Access Control List" class="col">
                    <b-table
                        :items="aclList"
                        :fields="fieldAcl"
                        :striped="true"
                        :bordered="true" >

                        <!-- Label Template -->
                        <template v-slot:cell(label)="data">
                            <label :style="`padding-left : ${data.item.level * 50}px;`">{{ data.item.label }}</label>
                        </template>

                        <!-- Aktivasi Template -->
                        <template v-slot:cell(active)="data">
                            <label class="switcher">
                                <input type="checkbox" v-model="data.item.active" class="switcher-input" v-on:change="switcRule(data.index)">
                                <span class="switcher-indicator">
                                    <span class="switcher-yes"></span>
                                    <span class="switcher-no"></span>
                                </span>
                            </label>
                        </template>

                    </b-table>

				</b-form-group>
			</b-form-row>

            <!-- Button -->
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
	name: 'level-user',
	metaInfo: {
		title: 'Level User'
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

		loadParams: {},

		data: [],

		currentPage: 1,

		isBusy: false,
		saveData: {},
		loading: false,
		mode: "add",
		index: null,
		titleDeleteModal: "Are you sure?",
		
        id: null,
        q: '',
        aclList: [],
        fieldAcl: [
            { key: "label", label: "Role Name", tdClass: "align-middle" },
            { key: "active", label: "Aktivasi", tdClass: "align-middle" },
            
        ],
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
				key: "level", 
				label:"Level", 
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
        currentPage() {
            this.loadData();
        },
        perPage() {
            this.loadData();
		},
        q(v) {
		},
    },
    methods:{
        loadData(){
			this.isBusy = true;

			this.loadParams.page = this.currentPage;
			this.loadParams.order_by = this.sortBy;
			this.loadParams.sort_desc = this.sortDesc;
			this.loadParams.limit = this.perPage;
			this.loadParams.params = {};

			axios
				.get(this.publicUrl + "api/level", {
					params: this.loadParams
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
            let param = '';
            if (mode == 'edit') {
                let data = this.data.data[i]
                param = 'id=' + data.id
				this.saveData = {
                    name: data.name,
                    level: data.level,
                    keterangan: data.keterangan
                }
                this.id = data.id
            }else{
				this.saveData = {
                    name: '',
					keterangan: '',
					level: ''
                }
            }
            this.loadAcl(param);
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
			this.saveData.rule = this.aclList
            
            axios({
                method: "post",
                url: this.publicUrl + "api/level",
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
			this.saveData.rule = this.aclList

            axios({
                method: "put",
                url: this.publicUrl + "api/level/" + this.id,
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
            if (this.saveData.level == "" || this.saveData.level == 0) {
                this.notif("danger", "Error", "Nomor Level tidak boleh kosong.");
                return false;
            }
            if (this.saveData.name == "") {
                this.notif("danger", "Error", "Nama Level tidak boleh kosong.");
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
                url: this.publicUrl + "api/level/" + this.id,
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

        loadAcl(param = "") {
            axios
                .get(this.publicUrl + "api/acl?" + param)
                .then((response) => {
                    // handle success
                    this.aclList = response.data.data;
                })
                .catch((error) => {
                    this.swr();
                });
        },

		// TODO - OTOMATIS ACTIVE / NON ACTIVE KETIKA SWITCH RULE 
        switcRule(i){
			let rule = this.aclList[i]
			
			if (rule.child) {
				if (rule.active) {
					let parent_rule = this.aclList.find(o => o.name === rule.parent_name);
					parent_rule.active = true
					if(parent_rule.chile){
						this.switcRule(i)
					}
				}
			}

            /*if (rule.parent) {
                if (!rule.active) {
                    let child_rule = this.aclList.filter(o => o.parent_name === rule.name);
                    for (let j = 0; j < child_rule.length; j++) {
						child_rule[j].active = false
                    }
                }else{
					console.log(rule);
                }
                // let obj = this.aclList.find(o => o.parent === rule.name);
                // let obj = this.aclList.find(o => o.parent === rule.name);
                // console.log(obj);
            }else{
				let parent_rule = this.aclList.find(o => o.name === rule.parent_name);
				parent_rule.active = true
				console.log(parent_rule);
            }*/

            // How to find data in object
            // let obj = this.aclList.find(o => o.name === 'user_add');
            // let obj = this.aclList.filter(o => o.name === 'user_add');

            // console.log(obj);
            // console.log(rule);
        },
    },
	created() {
		this.loadData();
	}
}
</script>