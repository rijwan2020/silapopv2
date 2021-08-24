<template>
    <div>
        <div class="row" v-if="user && penyuluh">
            <div class="col-xl-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="media">
                            <img :src="user.image_url" alt class="ui-w-60 rounded-circle" v-if="user">
                            <div class="media-body pt-2 ml-3">
                                <h5 class="mb-2">{{ penyuluh.nama }}</h5>
                                <div class="text-muted small" v-if="user">{{ user.username }}</div>
                                <div class="text-muted small">{{ statusPenyuluh[penyuluh.status_penyuluh] }}</div>

                                <div class="mt-3">
                                    <b-btn
                                        variant="primary"
                                        v-b-tooltip.hover
                                        title="Edit Profile"
                                        @click="showModal('add')"
                                        size="sm"
                                        class="rounded-pill"
                                    >
                                        <i class="ion ion-md-create"></i> Edit
                                    </b-btn>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="border-light m-0">
                    <div class="card-body">
                        <div class="mb-2">
                            <span class="text-muted">NIP:</span>&nbsp;
                            {{ penyuluh.nip }}
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">Jenis Kelamin:</span>&nbsp;
                            {{ penyuluh.jk == 1 ? 'Perempuan' : 'Laki-Laki' }}
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">Email:</span>&nbsp;
                            {{ penyuluh.email }}
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">No Handphone:</span>&nbsp;
                            {{ penyuluh.no_hp }}
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header font-weight-bold h5">Tentang</div>
                    <div class="card-body">

                        <div class="text-muted">Tempat / Tanggal Lahir</div>
                        <div>{{ penyuluh.tempat_lahir }} / {{ penyuluh.tanggal_lahir }}</div>

                        <hr>

                        <div class="text-muted">Alamat</div>
                        <div class="text-capitalize" v-if="penyuluh">
                            <p class="my-0" v-if="penyuluh.alamat">
                                {{ penyuluh.alamat }}<br>
                            </p>
                            <p class="my-0" v-if="penyuluh.desa">
                                Desa {{ penyuluh.desa.name | lower }} <br>
                            </p>
                            <p class="my-0" v-if="penyuluh.kecamatan">
                                Kecamatan {{ penyuluh.kecamatan.name | lower }} <br>
                            </p>
                            <p class="my-0" v-if="penyuluh.kota">
                                {{ penyuluh.kota.name | lower }}
                            </p>
                        </div>

                        <hr>

                        <div class="text-muted">Pendidikan Terakhir</div>
                        <div>{{ penyuluh.pendidikan }}</div>
                        
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header font-weight-bold h5">Kepenyuluhan</div>
                    <div class="card-body">
                        <div class="text-muted">Jenis Penyuluh</div>
                        <div>{{ penyuluh.jp == 0 ? 'Penyuluh' : 'POPT' }}</div>

                        <hr>

                        <div class="text-muted">Status Penyuluh</div>
                        <div>{{ statusPenyuluh[penyuluh.status_penyuluh] }}</div>

                        <hr>

                        <div class="text-muted">Pangkat</div>
                        <div>{{ penyuluh.pangkat }}</div>

                        <hr>

                        <div class="text-muted">Jabatan Fungsional</div>
                        <div>{{ penyuluh.jabatan_fungsional }}</div>

                        <hr>

                        <div class="text-muted">Nama Kelembagaan</div>
                        <div>{{ penyuluh.nama_kelembagaan }}</div>

                        <hr>

                        <div class="text-muted">Nama BP3K</div>
                        <div>{{ penyuluh.bp3k }}</div>

                        <hr>

                        <div class="text-muted">Pertanian (WKPP)</div>
                        <div>{{ penyuluh.pertanian_wkpp }}</div>

                        <hr>

                        <div class="text-muted">Jumlah Kelompok Tani</div>
                        <div>{{ penyuluh.jml_kel_tani }}</div>

                        <hr>

                        <div class="text-muted">Komoditi Unggulan</div>
                        <div>{{ penyuluh.komoditi_unggulan }}</div>

                        <hr>

                        <div class="text-muted">Tanggal Evaluasi</div>
                        <div>{{ penyuluh.tanggal_evaluasi }}</div>

                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header font-weight-bold h5">Wilayah Kerja (Total: {{ data.total }})</div>
                    <div class="card-body">
                        <div class="row">
                            <b-spinner label="Spinning" variant="danger" v-if="isBusy" />
                            <div class="col-xl-6" v-for="(wilker, i) in data.data" :key="i">
                                <div class="card border-light mb-3">
                                    <div class="card-body text-dark">
                                        <p class="card-text">Desa {{ wilker.desa }}, {{ wilker.kecamatan }}, {{ wilker.kota }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
				Edit Profile
			</div>
            
            <!-- Nama -->
			<b-form-row>
				<b-form-group label="Nama" class="col">
					<b-input placeholder="Nama" v-model="saveData.nama" />
				</b-form-group>
			</b-form-row>
            
            <!-- Tempat Lahir -->
			<b-form-row>
				<b-form-group label="Tempat Lahir" class="col">
					<b-input placeholder="Tempat Lahir" v-model="saveData.tempat_lahir" />
				</b-form-group>
			</b-form-row>
            
            <!-- Tanggal Lahir -->
			<b-form-row>
				<b-form-group label="Tanggal Lahir" class="col">
                    <b-datepicker 
                        size="md" 
                        placeholder="Pilih Tanggal" 
                        locale="id"
                        :max="currentDate"
                        v-model="saveData.tanggal_lahir" />
				</b-form-group>
			</b-form-row>

            <!-- Status Penyuluh -->
            <b-form-row>
                <b-form-group label="Status Penyuluh" class="col">
                    <b-select size="md" v-model="saveData.status_penyuluh">
						<option
							v-for="(sp, i) in statusPenyuluh"
							:key="i"
							:value="i"
						>
							{{ sp }}
						</option>
                    </b-select>
                </b-form-group>
            </b-form-row>
            
            <!-- NIP -->
			<b-form-row>
				<b-form-group label="NIP" class="col">
					<b-input placeholder="NIP" v-model="saveData.nip" />
				</b-form-group>
			</b-form-row>

            <!-- Jenis Kelamin -->
            <b-form-row>
				<b-form-group label="Jenis Kelamin" class="col">
                    <input type="radio" name="jk" id="" value="0" :checked="saveData.jk == 0"> Laki Laki
                    <input type="radio" name="jk" id="" value="1" :checked="saveData.jk == 1"> Perempuan
				</b-form-group>
			</b-form-row>
            
            <!-- Jabatan Fungsional PP -->
			<b-form-row>
				<b-form-group label="Jabatan Fungsional PP" class="col">
					<b-input placeholder="Jabatan Fungsional PP" v-model="saveData.jabatan_fungsional" />
				</b-form-group>
			</b-form-row>
            
            <!-- Pangkat/Golongan -->
            <b-form-row>
                <b-form-group label="Pangkat/Golongan" class="col">
                    <b-select size="md" v-model="saveData.pangkat">
						<option
							v-for="(pangkat, i) in pangkatList"
							:key="i"
							:value="pangkat"
						>
							{{ pangkat }}
						</option>
                    </b-select>
                </b-form-group>
            </b-form-row>
            
            <!-- Pendidikan Terakhir -->
			<b-form-row>
				<b-form-group label="Pendidikan Terakhir" class="col">
					<b-input placeholder="Pendidikan Terakhir" v-model="saveData.pendidikan" />
				</b-form-group>
			</b-form-row>
            
            <!-- Nama Kelembagaan Penyuluh -->
			<b-form-row>
				<b-form-group label="Nama Kelembagaan Penyuluh" class="col">
					<b-input placeholder="Nama Kelembagaan Penyuluh" v-model="saveData.nama_kelembagaan" />
				</b-form-group>
			</b-form-row>
            
            <!-- Nama BP3K/BPP -->
			<b-form-row>
				<b-form-group label="Nama BP3K/BPP" class="col">
					<b-input placeholder="Nama BP3K/BPP" v-model="saveData.bp3k" />
				</b-form-group>
			</b-form-row>
            
            <!-- Pertanian (WKPP) -->
			<b-form-row>
				<b-form-group label="Pertanian (WKPP)" class="col">
					<b-input placeholder="Pertanian (WKPP)" v-model="saveData.pertanian_wkpp" />
				</b-form-group>
			</b-form-row>

            <!-- Kelompok Tani -->
			<b-form-row>
				<b-form-group label="Kelompok Tani" class="col">
					<b-input placeholder="Kelompok Tani" v-model="saveData.kelompok_tani" />
				</b-form-group>
			</b-form-row>
            
            <!-- Jumlah Kelompok Tani Binaan -->
			<b-form-row>
				<b-form-group label="Jumlah Kelompok Tani Binaan" class="col">
					<b-input placeholder="Jumlah Kelompok Tani Binaan" v-model="saveData.jml_kel_tani" />
				</b-form-group>
			</b-form-row>
            
            <!-- Komoditi Unggulan -->
			<b-form-row>
				<b-form-group label="Komoditi Unggulan" class="col">
					<b-input placeholder="Komoditi Unggulan" v-model="saveData.komoditi_unggulan" />
				</b-form-group>
			</b-form-row>
            
            
            <!-- No Telepon/Hp -->
			<b-form-row>
				<b-form-group label="No Telepon/Hp" class="col">
					<b-input placeholder="No Telepon/Hp" v-model="saveData.no_hp" />
				</b-form-group>
			</b-form-row>

            <!-- Email -->
			<b-form-row>
				<b-form-group label="Email" class="col">
					<b-input placeholder="Email" v-model="saveData.email" />
				</b-form-group>
			</b-form-row>
            
            <!-- Tanggal Pelaksanaan Evaluasi -->
			<b-form-row>
				<b-form-group label="Tanggal Pelaksanaan Evaluasi" class="col">
                    <b-datepicker 
                        size="md" 
                        placeholder="Pilih Tanggal" 
                        locale="id"
                        :max="currentDate"
                        v-model="saveData.tanggal_evaluasi" />
				</b-form-group>
			</b-form-row>
            
            <!-- Ganti Foto -->
			<b-form-row>
				<b-form-group label="Ganti Foto" class="col">
					<b-file v-model="newFoto"></b-file>
                    <small>Kosongkan jika tidak akan mengubah foto profil.</small>
                    <p class="mt-2" v-if="newFoto != null">File yang dipilih: <b>{{ newFoto ? newFoto.name : '' }}</b></p>
				</b-form-group>
			</b-form-row>
            
			<hr>
			<h5>Alamat</h5>

            <!-- Alamat -->
			<b-form-row>
				<b-form-group label="Alamat" class="col">
					<b-input placeholder="Alamat" v-model="saveData.alamat" />
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

			<hr>
			<h5>Tempat Tugas</h5>
            <!-- Kota -->
			<b-form-row>
				<b-form-group label="Kota" class="col">
                    <b-select size="md" v-model="formTempatTugasKotaId">
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
                    <b-select size="md" v-model="formTempatTugasKecamatanId">
                        <option value="Pilih">Pilih</option>
                        <option
                            v-for="(kecamatan, i) in formTempatTugasKecamatanList"
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
                    <b-select size="md" v-model="formTempatTugasDesaId">
                        <option value="Pilih">Pilih</option>
                        <option
                            v-for="(desa, i) in formTempatTugasDesaList"
                            :key="i"
                            :value="desa.kelurahan_id"
                        >
                            {{ desa.name }}
                        </option>
                    </b-select>
				</b-form-group>
			</b-form-row>
            
			<hr>
			<h5>Akun Login</h5>

            <!-- Username -->
			<b-form-row>
				<b-form-group label="Username" class="col">
					<b-input placeholder="Username" v-model="saveData.username" :disabled="true" />
				</b-form-group>
			</b-form-row>
            
            <!-- Password -->
			<b-form-row>
				<b-form-group label="Password" class="col">
					<b-input type="password" placeholder="Password" v-model="saveData.password" />
				</b-form-group>
			</b-form-row>
            
            <!-- Konfirmasi Password -->
			<b-form-row>
				<b-form-group label="Konfirmasi Password" class="col">
					<b-input type="password" placeholder="Konfirmasi Password" v-model="saveData.konfirmasi_password" />
                    <small>Kosongkan password jika tidak akan mengubah password.</small>
				</b-form-group>
			</b-form-row>

            <!-- Button Save/Update -->
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
					Update
				</ladda-btn>
			</div>
		</b-modal>
    </div>
</template>

<script>
import LaddaBtn from "@/vendor/libs/ladda/Ladda";
import { mapActions } from "vuex";

export default {
	name: 'profile-penyuluh',
	metaInfo: {
		title: 'Profile'
	},
    components: {
        LaddaBtn
    },
	data: () => ({
        penyuluh: [],
        data:[],
        isBusy: false,
        loading:false,
        statusPenyuluh: ['-','PNS','TBPP','TBPPD','THL-POPT','PPPK'],
        pangkatList: [
            '-',
            'Juru Muda (I/a)',
            'Juru Muda Tingkat I (I/b)',
            'Juru (I/c)',
            'Juru Tingkat I (I/d)',
            'Pengatur Muda (II/a)',
            'Pengatur Muda Tingkat I (II/b)',
            'Pengatur (II/c)',
            'Pengatur Tingkat I (II/d)',
            'Penata Muda (III/a)',
            'Penata Muda Tingkat I (III/b)',
            'Penata (III/c)',
            'Penata Tingkat I (III/d)',
            'Pembina (IV/a)',
            'Pembina Tingkat I (IV/b)',
            'Pembina Utama Muda (IV/c)',
            'Pembina Utama Madya (IV/d)',
            'Pembina Utama (IV/e)'
        ],
        saveData: {},
        kotaList: [],
        formKotaId: "Pilih",
        formKecamatanId: "Pilih",
        formDesaId: "Pilih",
        formKecamatanList: [],
        formDesaList: [],
        newFoto: null,

        formTempatTugasKotaId: "Pilih",
        formTempatTugasKecamatanId: "Pilih",
        formTempatTugasKecamatanList: [],
        formTempatTugasDesaId: "Pilih",
        formTempatTugasDesaList: [],
    }),
    filters:{
        upper(value){
            if(!value) return ''
            value = value.toString()
            return value.toUpperCase()
        },
        lower(value){
            if(!value) return ''
            value = value.toString()
            return value.toLowerCase()
        },
    },
	computed: {
		user(){
			return this.$store.state.auth.user;
        },
        currentDate(){
            const curDate = new Date().toJSON().slice(0,10).replace(/-/g,'-');
            return curDate
        }
    },
    watch:{
        formKotaId(v){
            if(v != 'Pilih'){
                this.loadListKecamatan();
            }else{
                this.formKecamatanList = []
                this.formDesaList = []
                this.formKecamatanId = "Pilih";
                this.formDesaId = "Pilih";

            }
        },
        
        formKecamatanId(v){
            if(v != 'Pilih'){
                this.loadListDesa();
            }else{
                this.formDesaList = []
                this.formDesaId = "Pilih";
            }
        },

        formTempatTugasKotaId(v){
            if(v == 'Pilih'){
                this.formTempatTugasKecamatanList = []
                this.formTempatTugasKecamatanId = "Pilih";
                this.formTempatTugasDesaList = []
                this.formTempatTugasDesaId = "Pilih";
            }else{
                this.loadListKecamatan('tempat_tugas');
            }
		},
		
		formTempatTugasKecamatanId(v){
            if(v == 'Pilih'){
                this.formTempatTugasDesaList = []
                this.formTempatTugasDesaId = "Pilih";
            }else{
                this.loadListDesa('tempat_tugas');
            }
		},
    },
	methods:{
        ...mapActions({
            setAuth: "auth/set",
            setRole: "auth/role",
        }),
		loadData(){
            axios
				.get(this.publicUrl + "api/penyuluh/" + this.user.penyuluh.id)
				.then((response) => { 
					// handle success
                    this.penyuluh = response.data.data;
				})
				.catch((error) => {
					this.swr();
				});
        },
        
		loadWilker(){
			this.isBusy = true;
            // this.loadParams.id = this.user.penyuluh.id
            axios
				.get(this.publicUrl + "api/penyuluh/wilayahkerja", {
					params: {id: this.user.penyuluh.id}
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

        loadListKecamatan(v = 'list'){
            if (v == "tempat_tugas") {
                axios
                .get(this.publicUrl + "api/kecamatan", {
                    params: {kota_id: this.formTempatTugasKotaId}
                })
                .then((response) => {
                    // handle success
                    this.formTempatTugasKecamatanList = response.data.data;
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

        loadListDesa(show = "list"){
            if (show == 'tempat_tugas') {
                axios
                    .get(this.publicUrl + "api/desa", {
                        params: {kota_id: this.formTempatTugasKotaId, kecamatan_id: this.formTempatTugasKecamatanId}
                    })
                    .then((response) => {
                        // handle success
                        this.formTempatTugasDesaList = response.data.data;
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

        showModal(){
            this.loadListKota()
            this.saveData = {
                nama: this.penyuluh.nama,
                tempat_lahir: this.penyuluh.tempat_lahir,
                tanggal_lahir: this.penyuluh.tanggal_lahir,
                status_penyuluh: this.penyuluh.status_penyuluh,
                nip: this.penyuluh.nip,
                jk: this.penyuluh.jk,
                jabatan_fungsional: this.penyuluh.jabatan_fungsional,
                pangkat: this.penyuluh.pangkat,
                pendidikan: this.penyuluh.pendidikan,
                nama_kelembagaan: this.penyuluh.nama_kelembagaan,
                bp3k: this.penyuluh.bp3k,
                pertanian_wkpp: this.penyuluh.pertanian_wkpp,
                jml_kel_tani: this.penyuluh.jml_kel_tani,
                komoditi_unggulan: this.penyuluh.komoditi_unggulan,
                alamat: this.penyuluh.alamat,
                no_hp: this.penyuluh.no_hp,
                email: this.penyuluh.email,
                tanggal_evaluasi: this.penyuluh.tanggal_evaluasi,
                kelompok_tani: this.penyuluh.kelompok_tani,
                username: this.penyuluh.user.username,
            }
            // this.saveData = this.penyuluh
            this.formKotaId = this.penyuluh.kota_id
            this.formKecamatanId = this.penyuluh.kecamatan_id
            this.formDesaId = this.penyuluh.kelurahan_id
            this.formTempatTugasKotaId = this.penyuluh.tempat_tugas_kota_id
            this.formTempatTugasKecamatanId = this.penyuluh.tempat_tugas_kecamatan_id
            this.formTempatTugasDesaId = this.penyuluh.tempat_tugas_kelurahan_id
            // this.saveData.username = this.penyuluh.user.username
            this.$refs["modal-box"].show();
        },

        hideModal() {
            this.$refs["modal-box"].hide();
        },

        process(){
            if (!this.validate()) {
                return;
            }
            
            this.loading = true;
            if (this.formKotaId != "Pilih") {
				this.saveData.kota_id = this.formKotaId
				if (this.formKecamatanId != "Pilih") {
					this.saveData.kecamatan_id = this.formKecamatanId
					if (this.formDesaId != "Pilih") {
						this.saveData.kelurahan_id = this.formDesaId
					}
				}
			}
			if (this.formTempatTugasKotaId != "Pilih") {
				this.saveData.tempat_tugas_kota_id = this.formTempatTugasKotaId
				if (this.formTempatTugasKecamatanId != "Pilih") {
					this.saveData.tempat_tugas_kecamatan_id = this.formTempatTugasKecamatanId
					if (this.formTempatTugasDesaId != "Pilih") {
						this.saveData.tempat_tugas_kelurahan_id = this.formTempatTugasDesaId
					}
				}
			}

            this.saveData.user_id= this.user.id
            this.saveData.penyuluh_user_id= this.user.id
            
            axios({
                method: "put",
                url: this.publicUrl + "api/penyuluh/" + this.penyuluh.id,
                data: this.saveData
            })
                .then((response) => {
                    this.id = response.data.data.id
                    if(this.newFoto != null){
                        this.uploadFile()
                    }
                    this.notif("success", "Success", "Profile berhasil diperbaharui");
                    this.hideModal();
                    this.saveData = {};
                    this.newFoto = null
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
            // if (this.formKotaId == "Pilih") {
            //     this.notif("danger", "Error", "Kota belum dipilih.");
            //     return false;
            // }
            // if (this.formKecamatanId == "Pilih") {
            //     this.notif("danger", "Error", "Kecamatan belum dipilih.");
            //     return false;
            // }
            // if (this.formDesaId == "Pilih") {
            //     this.notif("danger", "Error", "Desa belum dipilih.");
            //     return false;
            // }
            if (this.saveData.nama == "") {
                this.notif("danger", "Error", "Nama tidak boleh kosong.");
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
        this.loadWilker()
        this.updateStateUser()
	}
}
</script>
