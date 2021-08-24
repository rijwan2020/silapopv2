<template>
    <div>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div v-if="isBusy" class="text-center">
                    <b-spinner label="Spinning" variant="danger" />
                </div>
                <div class="card" v-else>
                    <div class="card-header h4">Profile</div>
                    <div class="card-body">

                        <!-- Nama -->
                        <b-form-row>
                            <b-form-group label="Nama" class="col">
                                <b-input placeholder="Nama" v-model="saveData.name" />
                            </b-form-group>
                        </b-form-row>

                        <!-- Username -->
                        <b-form-row>
                            <b-form-group label="Username" class="col">
                                <b-input placeholder="Username" v-model="saveData.username" />
                            </b-form-group>
                        </b-form-row>

                        <!-- Email -->
                        <b-form-row>
                            <b-form-group label="Email" class="col">
                                <b-input placeholder="Email" v-model="saveData.email" />
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
                                <small>Kosongkan password jika tidak akan mengubah password.</small>
                            </b-form-group>
                        </b-form-row>
                        
                        <b-form-row>
                            <ladda-btn
                                :loading="loading"
                                @click.native="process"
                                data-style="zoom-out"
                                class="btn btn-primary"
                            >
                                Update
                            </ladda-btn>
                        </b-form-row>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LaddaBtn from "@/vendor/libs/ladda/Ladda";
import { mapActions } from "vuex";

export default {
	name: 'profile-admin',
	metaInfo: {
		title: 'Profile'
	},
    components: {
        LaddaBtn
    },
	data: () => ({
        data:[],
        isBusy: false,
        loading:false,
    }),
	computed: {
        saveData(){
            const data = {
                name: this.data.name,
                email: this.data.email,
                username: this.data.username,
                password: '',
                konfirmasi_password: '',
                level: this.data.level,
                active: this.data.active,
            }
            return data
        },
		user(){
			return this.$store.state.auth.user;
        },
    },
	methods:{
        ...mapActions({
            setAuth: "auth/set",
            setRole: "auth/role",
        }),
		loadData(){
            this.isBusy = true
            axios
				.get(this.publicUrl + "api/user/" + this.user.id)
				.then((response) => { 
                    // handle success
                    this.isBusy = false
                    this.data = response.data.data;
				})
				.catch((error) => {
					this.swr();
				});
        },
        
        process(){
            if (!this.validate()) {
                return;
            }
            
            this.loading = true;
            this.saveData.user_id= this.user.id
            
            axios({
                method: "put",
                url: this.publicUrl + "api/user/" + this.data.id,
                data: this.saveData
            })
                .then((response) => {
                    this.notif("success", "Success", "Profile berhasil diperbaharui");
                    // reload data
                    this.loadData();
                    this.updateStateUser();
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

        validate() {
            if (this.saveData.name == "") {
                this.notif("danger", "Error", "Nama tidak boleh kosong.");
                return false;
            }
            if (this.saveData.usernname == "") {
                this.notif("danger", "Error", "Username tidak boleh kosong.");
                return false;
            }
            if (this.saveData.password != "") {
                if(this.saveData.password && this.saveData.password.length < 6){
                    this.notif("danger", "Error", "Password minimal 6 karakter.");
                    return false;
                }
                if (this.saveData.password != this.saveData.konfirmasi_password) {
                    this.notif("danger", "Error", "Konfirmasi password salah.");
                    return false;
                }
            }
            return true;
        },

        updateStateUser(){
            axios
				.get(this.publicUrl + "api/user/" + this.user.id)
				.then((response) => { 
                    // handle success
                    this.$store.commit("auth/set", response.data);
                    // this.penyuluh = response.data.data;
				})
				.catch((error) => {
					this.swr();
				});
        },
        // upload file
        uploadFile(){
            let formData = new FormData();
            formData.append('file', this.newFoto);
            formData.append('id', this.penyuluh.id)

            axios.post(
                this.publicUrl + "api/penyuluh/file",
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
	},
	created() {
		this.loadData()
        this.updateStateUser()
	}
}
</script>
