<template>
    <div>
        <div class="container px-0">
            <h2 class="text-center font-weight-bolder pt-5">
                {{ data.title }}
            </h2>
            <div class="text-center text-muted text-big mx-auto mt-3" style="max-width: 500px;">
                <b-btn
                    variant="primary"
                    title="Edit"
                    @click="showModal()"
                    size="sm"
                    class="rounded-pill"
                    v-if="hasAccess.edit"
                >
                    <i class="ion ion-md-create"></i> Edit
                </b-btn>
            </div>

            <b-card no-body class="mt-5 px-4 py-4">
                <div class="text-center" v-if="isBusy">
                    <b-spinner label="Spinning" variant="danger" />
                </div>
                <div class="text-justify" v-else v-html="data.konten"></div>
            </b-card>
        </div>

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
				Update Kebijakan Privasi
			</div>

            <!-- Judul -->
			<b-form-row>
				<b-form-group label="Judul" class="col">
					<b-input placeholder="Judul" v-model="saveData.title" />
				</b-form-group>
			</b-form-row>

            <!-- Konten -->
			<b-form-row>
				<b-form-group label="Konten" class="col">
					<quill-editor v-model="saveData.konten" :options="editorOptions" v-if="!isIE10Mode" />
                    <!-- Fallback -->
                    <b-textarea v-model="saveData.konten" style="height: 200px" v-if="isIE10Mode" placeholder="Kebijakan Privasi" />
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
					@click.native="update"
					data-style="zoom-out"
					class="btn btn-primary"
				>
					Update
				</ladda-btn>
			</div>
		</b-modal>
    </div>
</template>
<style src="@/vendor/libs/vue-quill-editor/typography.scss" lang="scss"></style>
<style src="@/vendor/libs/vue-quill-editor/editor.scss" lang="scss"></style>

<style lang="scss">
  .ql-container.ql-snow {
    height: 200px;
  }
</style>

<script>
import LaddaBtn from "@/vendor/libs/ladda/Ladda";
export default {
    name: 'kebijakan-privasi-data',
	metaInfo: {
		title: 'Kebijakan Privasi'
    },
    data: () => ({
		data: [],
        isBusy: false,
        saveData: {},
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
            placeholder: 'Kebijakan Privasi'
        },
        loading: false,
		listLevel: {},
		hasAccess: {}
    }),
    components: {
        LaddaBtn,
        quillEditor: () => import('vue-quill-editor/dist/vue-quill-editor').then(m => m.quillEditor).catch(() => {})
    },
    computed: {
		user(){
			return this.$store.state.auth.user;
		},
	},
    methods:{
        loadData(){
            this.isBusy = true;
            // this.loadParams.id = this.user.penyuluh.id
            axios
				.get(this.publicUrl + "api/config/2")
				.then((response) => { 
					// handle success
                    this.data = response.data.data;
                    this.isBusy = false;
				})
				.catch((error) => {
					this.swr();
                });
        },
        showModal(){
            this.saveData = {
                konten: this.data.konten,
                title: this.data.title
            }
            this.$refs["modal-box"].show();
        },

        hideModal() {
            this.$refs["modal-box"].hide();
        },
        // Proses update data
        update(){
            if (!this.validate()) {
                return;
            }
            this.loading = true;

            axios({
                method: "put",
                url: this.publicUrl + "api/config/2",
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
            if (this.saveData.title == "") {
                this.notif("danger", "Error", "Judul tidak boleh kosong.");
                return false;
            }
            if (this.saveData.konten == "") {
                this.notif("danger", "Error", "Konten tidak boleh kosong.");
                return false;
            }
            return true;
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
						edit: false,
					}
					let data_rule = this.listLevel.find(o => o.level === this.user.level)
					
					if (data_rule.rule.find(o => o.name === 'kebijakan_privasi_edit')) {	
						this.hasAccess.edit = data_rule.rule.find(o => o.name === 'kebijakan_privasi_edit').active
					}
				}else{
					this.hasAccess = {
						edit: true,
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