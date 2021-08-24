<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <!-- Messages list -->
                <b-card no-body>
                    <!-- Header -->
                    <b-card-header header-tag="h4" class="media flex-wrap align-items-center py-4">
                        <div class="media-body"><i class="ion ion-md-megaphone"></i> &nbsp; Pengumuman</div>
                        <b-btn
                            variant="primary"
                            v-b-tooltip.hover
                            title="Tambah pengumuman"
                            @click="showModal('add')"
                            size="sm"
                            v-if="hasAccess.add"
                        >
                            <i class="ion ion-md-add"></i> Tambah
                        </b-btn>
                    </b-card-header>
                    <!-- / Header -->
                    <!-- Controls -->
                    <div class="media flex-wrap align-items-center py-1 px-2">
                        <div class="media-body d-flex flex-wrap flex-basis-100 flex-basis-sm-auto">
                            <b-input size="sm" placeholder="Search..." style="max-width: 20rem;" />
                        </div>
                        <div class="text-muted mr-3 ml-auto">{{ data.total > 20 ? data.total : data.total * currentPage }} of {{ data.total }}</div>
                        <div class="d-flex flex-wrap">
                            <b-pagination
                                class="justify-content-center justify-content-sm-end m-0"
                                v-if="data.total"
                                v-model="currentPage"
                                :total-rows="data.total"
                                :per-page="perPage"
                                size="sm"
                            />
                        </div>
                    </div>
                    <hr class="border-light m-0">
                    <!-- / Controls -->
                    <ul class="list-group messages-list"  v-if="data.total > 0">
                        <li class="list-group-item px-4" v-for="(message, i) in data.data" :key="i">
                            <a class="message-sender flex-shrink-1 d-block text-body">
                                <span class="badge badge-dot mr-1" :class="`badge-dark`"></span>
                                {{ message.pengirim }}
                            </a>
                            <a href="javascript:void(0)" @click="showDetail(i)" class="message-subject flex-shrink-1 d-block text-body">
                                | <b>{{ message.subjek }}</b>
                                <i class="ion ion-md-attach" v-if="message.file"></i>
                            </a>
                            <div class="message-date text-muted">
                                <span class="mr2" v-if="!penyuluh">{{ message.status == 1 ? '[Publish]' : '[Draft]' }}</span>
                                {{ message.waktu }}
                            </div>
                            <div class="ml-2" v-if="hasAccess.delete || hasAccess.edit">
                                <b-btn
                                    variant="primary btn-xs icon-btn md-btn-flat"
                                    v-b-tooltip.hover
                                    :title="`Edit Pengumuman`"
                                    v-if="hasAccess.edit"
                                    @click="showModal('edit', i)"
                                >
                                    <i class="ion ion-md-create"></i>
                                </b-btn>
                                <b-btn
                                    variant="danger btn-xs icon-btn md-btn-flat"
                                    v-b-tooltip.hover
                                    :title="`Hapus Pengumuman`"
                                    @click="deleteModal(i)"
                                    v-if="hasAccess.delete"
                                >
                                    <i class="ion ion-md-trash"></i>
                                </b-btn>
                            </div>
                        </li>
                    </ul>
                    <ul class="list-group"  v-else>
                        <li class="list-group-item px-4 text-center">
                            <i>Belum ada pengumuman</i>
                        </li>
                    </ul>
                </b-card>
                <!-- / Messages list -->
            </div><!-- / .row -->
        </div>

        <!-- Modal Detail -->
        <b-modal
			ref="show-pengumuman"
			centered
			size="lg"
            hide-footer
		>
            <div slot="modal-title">
				{{ detail.subjek }}
                <div style="font-size: 12px;" class="mb-0 mt-1">
                    {{ detail.waktu_lengkap }} - {{ detail.pengirim }}
                </div>
			</div>
            
            <div v-html="detail.pesan"></div>
            <div v-if="detail.file">
                <a :href="detail.file" target="_blank">File Attachment</a>
            </div>
        </b-modal>

        <!-- Modal Form -->
		<b-modal
			ref="modal-box"
			centered
			size="lg"
			no-close-on-esc
			no-close-on-backdrop
			hide-header-close
		>
			<div slot="modal-title">
				{{ mode == "add" ? "Add" : "Update" }} Pengumuman 
			</div>

            <!-- Subjek -->
			<b-form-row>
				<b-form-group label="Subjek" class="col">
					<b-input placeholder="Subjek" v-model="saveData.subjek" />
				</b-form-group>
			</b-form-row>

            <!-- Pesan -->
			<b-form-row>
				<b-form-group label="Pesan" class="col">
					<quill-editor v-model="saveData.pesan" :options="editorOptions" v-if="!isIE10Mode" />
                    <!-- Fallback -->
                    <b-textarea v-model="saveData.pesan" style="height: 200px" v-if="isIE10Mode" placeholder="Pesan" />
				</b-form-group>
			</b-form-row>

            <!-- File Attachment -->
			<b-form-row class="mt-5">
				<b-form-group label="File" class="col">
					<b-file v-model="fileAttachment"></b-file>
                    <p class="mt-2" v-if="fileAttachment != null">File yang dipilih: <b>{{ fileAttachment ? fileAttachment.name : '' }}</b></p>
				</b-form-group>
			</b-form-row>

            <!-- Status -->
            <b-form-row>
				<b-form-group label="Status" class="col">
                    <input type="radio" name="status" value="0" v-model="saveData.status" :checked="saveData.status == 0"> Draft
                    <input type="radio" name="status" value="1"  v-model="saveData.status" :checked="saveData.status == 1"> Publish
				</b-form-group>
			</b-form-row>

            <!-- Button Form -->
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

<style src="@/vendor/libs/vue-quill-editor/typography.scss" lang="scss"></style>
<style src="@/vendor/libs/vue-quill-editor/editor.scss" lang="scss"></style>
<!-- Page -->
<style src="@/vendor/styles/pages/messages.scss" lang="scss"></style>

<style lang="scss">
  .ql-container.ql-snow {
    height: 200px;
  }
</style>
<script>
import LaddaBtn from "@/vendor/libs/ladda/Ladda";
import { SweetModal } from "sweet-modal-vue";
export default {
    name: 'pengumuman',
    metaInfo: {
        title: 'Pengumuman'
    },
    data: () => ({
        selected: [],
        isBusy: false,
		sortBy: "created_at",
		sortDesc: true,
		perPage: 20,
        loadParams: {},
        data: [],
        saveData: {},
        loading: false,
        mode: 'add',
        id: null,
        titleDeleteModal: "Are you sure?",
        // Editor
        editorOptions: {
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, 4, 5, 6, false] }, { font: [] }, { size: [] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ color: [] }, { background: [] }],
                    [{ script: 'sub' }, { script: 'super' }],
                    ['blockquote', 'code-block'],
                    [{ list: 'ordered' }, { list: 'bullet' }, { indent: '-1' }, { indent: '+1' }],
                    [{ direction: 'rtl' }, { align: [] }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            },
            placeholder: 'Tulis Pesan!'
        },
        fileAttachment: null,
        detail: {},
        currentPage: 1,
        
		listLevel: {},
		hasAccess: {}
    }),
    filters:{
        formatTanggal(value){
            if(!value) return ''

            value = value.toString()
            return value.toUpperCase()
        },
    },
    components: {
        LaddaBtn,
        SweetModal,
        quillEditor: () => import('vue-quill-editor/dist/vue-quill-editor').then(m => m.quillEditor).catch(() => {})
    },
    computed:{
        user(){
			return this.$store.state.auth.user;
        },
        penyuluh(){
            const user = this.$store.state.auth.user;
            if(user && user.penyuluh != null){
                return 1
            }
            return 0
        }
    },
    watch:{
        currentPage(v) {
            this.loadData(v);
        },
    },
    methods: {
        loadData(){
			this.isBusy = true;

			this.loadParams.page = this.currentPage;
			this.loadParams.order_by = this.sortBy;
			this.loadParams.sort_desc = this.sortDesc;
			this.loadParams.limit = this.perPage;
			this.loadParams.params = {};
			// //jika yg login penyuluh
			if(this.user.penyuluh != null){
				this.loadParams.params.status = 1
			}

			axios
				.get(this.publicUrl + "api/pengumuman", {
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

        // Show Modal box
        showModal(mode, i = null){
            this.index = i;
            this.$refs["show-pengumuman"].hide();
            this.$refs["modal-box"].show();
            this.mode = mode;
            if (mode == 'edit') {
                let data = this.data.data[i]
                this.saveData = {
                    subjek: data.subjek,
                    pesan: data.pesan,
                    status: data.status,
                }
                this.id = data.id
            }else if(mode == 'add'){
                this.saveData = {
                    subjek: '',
                    pesan: "",
                    status: 1,
                }
            }else{
                
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

            this.saveData.user_id= this.user.id
            
            axios({
                method: "post",
                url: this.publicUrl + "api/pengumuman",
                data: this.saveData
            })
                .then((response) => {
                    this.id = response.data.data.id
                    if(this.fileAttachment != null){
                        this.uploadFile()
                    }
                    this.notif("success", "Success", "Data berhasil ditambahkan");
                    this.hideModal();
                    this.saveData = {};
                    this.fileAttachment = null
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
                url: this.publicUrl + "api/pengumuman/" + this.id,
                data: this.saveData
            })
                .then((response) => {
                    if(this.fileAttachment != null){
                        this.uploadFile()
                    }
                    this.notif("success", "Success", "Data berhasil diperbaharui.");
                    this.hideModal();
                    this.saveData = {};
                    this.fileAttachment = null
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

        // upload file
        uploadFile(){
            let formData = new FormData();
            formData.append('file', this.fileAttachment);
            formData.append('id', this.id)

            axios.post(
                this.publicUrl + "api/pengumuman/file",
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            )
            .then(() => {
                this.id = null;
            });
        },

        // Validati input data
        validate() {
            if (this.saveData.subjek == "") {
                this.notif("danger", "Error", "Subjek tidak boleh kosong.");
                return false;
            }
            if (this.saveData.pesan == "") {
                this.notif("danger", "Error", "Pesan tidak boleh kosong.");
                return false;
            }
            return true;
        },

        showDetail(i){
            this.detail = this.data.data[i]
            this.$refs["show-pengumuman"].show();
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
                url: this.publicUrl + "api/pengumuman/" + this.id,
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
						add: false,
						edit: false,
                        delete: false,
					}
					let data_rule = this.listLevel.find(o => o.level === this.user.level)
					
					if (data_rule.rule.find(o => o.name === 'pengumuman_add')) {	
						this.hasAccess.add = data_rule.rule.find(o => o.name === 'pengumuman_add').active
					}
					if (data_rule.rule.find(o => o.name === 'pengumuman_edit')) {	
						this.hasAccess.edit = data_rule.rule.find(o => o.name === 'pengumuman_edit').active
					}
					if (data_rule.rule.find(o => o.name === 'pengumuman_delete')) {	
						this.hasAccess.delete = data_rule.rule.find(o => o.name === 'pengumuman_delete').active
					}
				}else{
					this.hasAccess = {
						add: true,
						edit: true,
                        delete: true,
					}
				}
			}
		},
    },
    created(){
        this.loadLevel()
        this.loadData()
    }
}
</script>
