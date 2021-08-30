<template>
    <div>
        <div class="d-flex justify-content-between align-items-center w-100 mb-0 border-bottom">
            <div class="d-flex align-items-center">
                <h5 class="m-1 d-inline-block text-nowrap font-weight-normal">
                    Data / Data Alsin
                </h5>
            </div>
            <div class="m-1">
                <b-btn
					variant="primary"
					v-b-tooltip.hover
					title="Verifikasi Tingkat Kecamatan"
					@click="showModalVrf('kec')"
                    v-if="hasAccess.verkec" 
                    size="sm" >
					<i class="ion ion-md-checkmark"></i> {{ isKoordinatorKec ? 'Verifikasi' : 'Verifikasi Kecamatan' }}
				</b-btn>
                <b-btn
					variant="primary"
					v-b-tooltip.hover
					title="Verifikasi Tingkat Kota"
					@click="showModalVrf('kota')"
                    v-if="hasAccess.verkota" 
                    size="sm" >
					<i class="ion ion-md-checkmark"></i> {{ isKoordinatorKota ? 'Verifikasi' : 'Verifikasi Kota' }}
				</b-btn>
                <b-btn
					variant="primary"
					v-b-tooltip.hover
					title="Tambah Data Alsin"
					@click="showModal('add')"
                    v-if="hasAccess.add"
                    size="sm" >
					<i class="ion ion-md-add"></i> Tambah
				</b-btn>
            </div>
        </div>

		<div class="row pt-3 text-center">
			<div class="col-md-12">
				<b-card class="mb-4">
					<p class="card-text">Total Jumlah Alsin</p>
					<h4 class="card-title">{{ data.total_alsin }} Unit</h4>
				</b-card>
			</div>
		</div>

        <b-card no-body>

			<!-- Table -->
			<div class="table-responsive">
				<b-table
					:items="data.data"
					:fields="fields"
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

                    <!-- Jenis -->
					<template v-slot:cell(jenis_id)="data">
                        {{ data.item.jenis.nama }}
                    </template>

                    <!-- Verifikasi Kota -->
                    <template v-slot:cell(vrf_kota)="data">
                        <div v-if="hasAccess.verkota">
                            <b-dropdown :variant="(data.item.vrf_kota==1?'success':'danger') + ' btn-sm md-btn-flat'">
                                <template slot="button-content">
                                    <span v-if="data.item.vrf_kota === 1">Sudah</span>
                                    <span v-if="data.item.vrf_kota === 0">Belum</span>
                                </template>
                                <b-dropdown-item @click="verifikasi(data.index, 'kota', 1)" v-if="data.item.vrf_kota == 0"><i class="ion ion-md-checkmark-circle-outline text-success"></i> &nbsp; Verifikasi</b-dropdown-item>
                                <b-dropdown-item @click="verifikasi(data.index, 'kota', 0)" v-if="data.item.vrf_kota == 1"><i class="ion ion-md-close-circle-outline text-danger"></i> &nbsp; Batalkan</b-dropdown-item>
                            </b-dropdown>
                        </div>
                        <div v-else>
                            <b-badge variant="danger" v-if="data.item.vrf_kota==0">
                                Belum
                            </b-badge>
                            <b-badge variant="success" v-else>
                                Sudah
                            </b-badge>
                        </div>
                    </template> 

                    <!-- Verifikasi Kecamatan -->
                    <template v-slot:cell(vrf_kec)="data">
                        <div v-if="hasAccess.verkec">
                            <b-dropdown :variant="(data.item.vrf_kec==1?'success':'danger') + ' btn-sm md-btn-flat'">
                                <template slot="button-content">
                                    <span v-if="data.item.vrf_kec === 1">Sudah</span>
                                    <span v-if="data.item.vrf_kec === 0">Belum</span>
                                </template>
                                <b-dropdown-item @click="verifikasi(data.index, 'kec', 1)" v-if="data.item.vrf_kec == 0"><i class="ion ion-md-checkmark-circle-outline text-success"></i> &nbsp; Verifikasi</b-dropdown-item>
                                <b-dropdown-item @click="verifikasi(data.index, 'kec', 0)" v-if="data.item.vrf_kec == 1"><i class="ion ion-md-close-circle-outline text-danger"></i> &nbsp; Batalkan</b-dropdown-item>
                            </b-dropdown>
                        </div>
                        <div v-else>
                            <b-badge variant="danger" v-if="data.item.vrf_kec==0">
                                Belum
                            </b-badge>
                            <b-badge variant="success" v-else>
                                Sudah
                            </b-badge>
                        </div>
                    </template> 

                    <!-- Attachment -->
                    <template v-slot:cell(file_url)="data">
                        <a :href="data.item.file_url" target="_blank" v-if="data.item.file_url != null">File</a>
                    </template> 

                    
					<!-- Action Template -->
					<template v-slot:cell(actions)="data">
                        <b-btn
							variant="success btn-sm icon-btn md-btn-flat"
							v-b-tooltip.hover
                            title="Detail"
							@click="goToDetail(data.index)"
                            v-if="hasAccess.detail"
						>
							<i class="ion ion-md-menu"></i>
						</b-btn>
						<b-btn
							variant="primary btn-sm icon-btn md-btn-flat"
							v-b-tooltip.hover
							:title="`Edit Data Alsin`"
                            v-if="hasAccess.edit && data.item.vrf_kota == 0 && data.item.vrf_kec == 0"
							@click="showModal('edit', data.index)"
						>
							<i class="ion ion-md-create"></i>
						</b-btn>
						<b-btn
							variant="danger btn-sm icon-btn md-btn-flat"
							v-b-tooltip.hover
							:title="`Hapus Data Alsin`"
							@click="deleteModal(data.index)"
                            v-if="hasAccess.delete && data.item.vrf_kota == 0 && data.item.vrf_kec == 0"
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
                <!-- Kota -->
                <div class="col-md-4 px-0">
                    <b-form-group label="Kota" class="w-auto m-1">
                        <b-select size="md" v-model="pilihKota" :disabled="penyuluh || isKoordinatorKota">
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
                <div class="col-md-4 px-0">
                    <b-form-group label="Kecamatan" class="w-auto m-1">
                        <b-select size="md" v-model="pilihKecamatan" :disabled="penyuluh || isKoordinatorKec">
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
                <div class="col-md-4 px-0">
                    <b-form-group label="Desa" class="w-auto m-1">
                        <b-select size="md" v-model="pilihDesa" :disabled="penyuluh">
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
            <div class="row">
                <!-- Limit -->
                <div class="col-md-2 px-0">
                    <b-form-group label="Limit" class="w-auto m-1">
                        <b-select size="md" v-model="perPage" :options="[10, 20, 30, 40, 50]"/>
                    </b-form-group>
                </div>
                <!-- Tahun -->
                <div class="col-md-2 px-0">
                    <b-form-group label="Tahun" class="w-auto m-1">
                        <b-select size="md" v-model="pilihTahun" :options="tahunList"/>
                    </b-form-group>
                </div>
                
                <!-- Jenis -->
                <div class="col-md-3 px-0">
                    <b-form-group label="Jenis" class="w-auto m-1">
                        <b-select size="md" v-model="pilihJenis">
                            <option value="Pilih">Pilih</option>
                            <option
                                v-for="(jenis, i) in jenisList"
                                :key="i"
                                :value="jenis.id"
                            >
                                {{ jenis.nama }}
                            </option>
                        </b-select>
                    </b-form-group>
                </div>

                <div class="col-md-5 px-0 text-right">
                    <b-btn
                        variant="success"
                        v-b-tooltip.hover
                        title="Download Data"
                        @click="download()"
                        class="mt-3"
                    >
                        <i class="ion ion-md-download"></i> Download
                    </b-btn>
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
				{{ mode == "add" ? "Add" : "Update" }} Data Alsin
			</div>

            <!-- Kota -->
			<b-form-row>
				<b-form-group label="Kota" class="col">
                    <b-select size="md" v-model="formKotaId" :disabled="penyuluh">
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
                    <b-select size="md" v-model="formKecamatanId" :disabled="penyuluh">
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
                    <b-select size="md" v-model="formDesaId" :disabled="penyuluh">
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

            <!-- Tahun -->
            <b-form-row>
                <b-form-group label="Tahun" class="d-inline-block w-auto m-1">
                    <b-select size="md" v-model="saveData.tahun" :options="tahunList"/>
                </b-form-group>
            </b-form-row>
            
            <!-- Jenis -->
            <b-form-row>
                <b-form-group label="Jenis" class="d-inline-block w-auto m-1">
                    <b-select size="md" v-model="saveData.jenis_id">
						<option value="Pilih">Pilih</option>
						<option
							v-for="(jenis, i) in jenisList"
							:key="i"
							:value="jenis.id"
						>
							{{ jenis.nama }}
						</option>
                    </b-select>
                </b-form-group>
            </b-form-row>

            <!-- Jumlah -->
			<b-form-row>
				<b-form-group label="Jumlah Alsin" class="col">
                    <b-input-group>
                        <b-input
                            placeholder="Jumlah Alsin"
                            v-model="saveData.jumlah_alsin"
                        />
                        <b-input-group-text slot="append">Unit</b-input-group-text>
                    </b-input-group>
				</b-form-group>
			</b-form-row>

            <!-- File Attachment -->
			<b-form-row>
				<b-form-group label="File" class="col">
					<b-file v-model="fileAttachment"></b-file>
                    <small v-if="mode == 'edit'">Kosongkan file attacment jika tidak akan diganti.</small>
                    <p class="mt-2" v-if="fileAttachment != null">File yang dipilih: <b>{{ fileAttachment ? fileAttachment.name : '' }}</b></p>
				</b-form-group>
			</b-form-row>

            <!-- Keterangan file -->
			<b-form-row>
				<b-form-group label="Keterangan File" class="col">
					<b-input placeholder="Keterangan File" v-model="saveData.ket_file" />
				</b-form-group>
			</b-form-row>

            <!-- Ambil Lokasi -->
            <b-form-row>
				<b-form-group label="Ambil Lokasi" class="col">
                    <b-input-group>
                        <b-btn
                            variant="dark"
                            v-b-tooltip.hover
                            title="Ambil Lokasi"
                            @click="getLocation()"
                            size="sm"
                            >Get</b-btn
                        >
                        <b-input-group-text slot="append">Anda berada di titik lat: {{ saveData.lat }}, long: {{ saveData.long }}</b-input-group-text>
                    </b-input-group>
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

        <!-- Modal verifikasi -->
		<b-modal
			ref="modal-verifikasi"
			centered
			size="lg"
			no-close-on-esc
			no-close-on-backdrop
			hide-header-close >
			<div slot="modal-title">
				Konfirmasi Verifikasi {{ isVrf }}
			</div>

            <!-- Kota -->
			<b-form-row>
				Semua data yang tampil berdasarkan filter akan terverifikasi!
			</b-form-row>

			<div slot="modal-footer">
				<b-btn
					variant="danger"
					v-b-tooltip.hover
					title="Close"
					@click="hideModalVrf()" >
                    Batal
                </b-btn>
				<ladda-btn
					:loading="loading"
					@click.native="processVrf"
					data-style="zoom-out"
					class="btn btn-primary">
					Konfirmasi
				</ladda-btn>
			</div>
		</b-modal>
    </div>
</template>

<style src="@/vendor/libs/sweet-modal-vue/sweet-modal-vue.scss" lang="scss"></style>

<script>
import LaddaBtn from "@/vendor/libs/ladda/Ladda";
import { SweetModal } from "sweet-modal-vue";

export default {
    name: 'list',
	metaInfo: {
		title: 'Optimalisasi Alsin'
    },
    components: {
        LaddaBtn,
        SweetModal
    },
    data: () => ({
		// Options
		sortBy: "tahun",
		sortDesc: true,
		perPage: 10,

		fields: [
			{ key: "id", label:"#", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center"  },
			{ key: "tahun", label:"Tahun", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center"  },
			{ key: "name", label:"Nama", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center"  },
			{ key: "nip", label:"NIP", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center"  },
			{ key: "status_penyuluh", label:"Status Penyuluh", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center"  },
			{ key: "kota", label:"Kota", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center"  },
			{ key: "kecamatan", label:"Kecamatan", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center"  },
			{ key: "desa", label:"Desa", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center"  },
			{ key: "jenis_id", label:"Jenis", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center"  },
			{ key: "jumlah_alsin", label:"Jumlah Alsin [Unit]", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center"  },
			{ key: "file_url", label:"Attachment", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center"  },
			{ key: "vrf_kota", label:"Vrf Kota/Kab", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center"  },
			{ key: "vrf_kec", label:"Vrf Kecamatan", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center"  },
			{
				key: "actions",
				label: "Action",
				tdClass: "text-nowrap align-middle text-center",
				thClass: "text-nowrap align-middle text-center",
			},
		],

		loadParams: {},

        data: [],

		currentPage: 1,

        isBusy: false,
        
		saveData: {},
        formKotaId: "Pilih",
        formKecamatanId: "Pilih",
        formDesaId: "Pilih",
        formKecamatanList: [],
        formDesaList: [],
        fileAttachment: null,

		loading: false,
		mode: "add",
		index: null,
        titleDeleteModal: "Are you sure?",

        pilihTahun: 'Pilih',
        pilihKota: 'Pilih',
        pilihKecamatan: 'Pilih',
        pilihDesa: 'Pilih',
        pilihJenis: 'Pilih',

        jenisList: [],
        kotaList: [],
        kecamatanList: [],
        desaList: [],
        id: null,
        
		listLevel: {},
		hasAccess: {},
        
        isKoordinatorKota: false,
        isKoordinatorKec: false,
        isVrf: "Kota"
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
        
        tahunList(){
            const min_year = 2016;
            const max_year = new Date().getFullYear();
            var year_option = [];
            year_option.push('Pilih');
            for (let i = max_year; i >= min_year; i--) {
                year_option.push(i);
            }
            return year_option;
        },

        penyuluh(){
            const user = this.$store.state.auth.user;
            if(user.penyuluh != null){
                return true
            }
            return false
        },
    },
    
	watch: {
        currentPage(v) {
            this.loadData(v);
        },

        perPage(v) {
            this.loadData();
        },

        pilihTahun() {
            this.loadData();
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

        pilihJenis(){
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

    },

    methods:{

        loadData(page = 1){
			this.isBusy = true;

			this.loadParams.page = page;
			this.loadParams.order_by = this.sortBy;
			this.loadParams.sort_desc = this.sortDesc;
            this.loadParams.limit = this.perPage;

            this.loadParams.params = {};
            if (this.pilihTahun != "Pilih") {
                this.loadParams.params.tahun = this.pilihTahun
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
            if (this.pilihJenis != "Pilih") {
                this.loadParams.params.jenis_id = this.pilihJenis
            }

			axios
				.get(this.publicUrl + "api/opsin", {
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

        download(){
			this.loading = true;

            var params = {}
			
			params.order_by = this.sortBy;
			params.sort_desc = this.sortDesc;

            params.params = {};
            if (this.pilihTahun != "Pilih") {
                params.params.tahun = this.pilihTahun
            }
            if (this.pilihKota != "Pilih") {
                params.params.kota_id = this.pilihKota
            }
            if (this.pilihKecamatan != "Pilih") {
                params.params.kecamatan_id = this.pilihKecamatan
            }
            if (this.pilihDesa != "Pilih") {
                params.params.kelurahan_id = this.pilihDesa
            }
            if (this.pilihJenis != "Pilih") {
                params.params.jenis_id = this.pilihJenis
            }

            params.user_id = this.user.id

			this.notif("info", "Success", "Data sedang disiapkan, silakan cek secara berkala di menu download");
            axios({
                method: "post",
                url: this.publicUrl + "api/opsin/download",
                data: params
            }).catch((error) => {
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
            });
        },
        
        loadListJenis(){
            axios
				.get(this.publicUrl + "api/jenisalsin")
				.then((response) => {
					// handle success
                    this.jenisList = response.data.data;
				})
				.catch((error) => {
					this.swr();
                });
        },

        // load parameter dari store state
        cekParam(){
            if (this.$route.params.id) {
                axios
                    .get(this.publicUrl + "api/wilayahkerja/" + this.$route.params.id)
                    .then((response) => {
                        this.pilihKota = this.formKotaId = response.data.data.kota_id
                        this.pilihKecamatan = this.formKecamatanId = response.data.data.kecamatan_id
                        this.pilihDesa = this.formDesaId = response.data.data.kelurahan_id
                        // handle success
                        // this.data = response.data;
                    })
                    .catch((error) => {
                        this.swr();
                    });
            }
            if (this.$route.params.tahun) {
                this.pilihTahun = this.saveData.tahun = this.$route.params.tahun
            }
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
                this.saveData = {
                    tahun: data.tahun,
                    jenis_id: data.jenis_id,
                    jumlah_alsin: data.jumlah_alsin,
                    ket_file: data.ket_file,
                    lat: data.lat,
                    long: data.long,
                }
                this.formKotaId = data.kota_id
                this.formKecamatanId = data.kecamatan_id
                this.formDesaId = data.kelurahan_id
                this.id = data.id
            }else{
                this.saveData = {
                    tahun: "Pilih",
                    jenis_id: "Pilih",
                    jumlah_alsin: 0,
                    ket_file: null,
                    lat: null,
                    long: null,
                }
                if(!this.penyuluh){
                    this.formKotaId = "Pilih"
                    this.formKecamatanId = "Pilih"
                    this.formDesaId = "Pilih"
                }
                this.id = null
            }
        },

        // Close modal add/edit baku lahan
        hideModal() {
            this.$refs["modal-box"].hide();
        },

        // get titik lokasi penginputan
        getLocation(){
            this.$getLocation()
            .then(coordinates => {
                this.saveData.lat = coordinates.lat
                this.saveData.long = coordinates.lng
            });
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
            
            axios({
                method: "post",
                url: this.publicUrl + "api/opsin",
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

            this.saveData.kota_id = this.formKotaId
            this.saveData.kecamatan_id = this.formKecamatanId
            this.saveData.kelurahan_id = this.formDesaId
            this.saveData.user_id= this.user.id

            axios({
                method: "put",
                url: this.publicUrl + "api/opsin/" + this.id,
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
                this.publicUrl + "api/opsin/file",
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
            if (this.formKotaId == "Pilih") {
                this.notif("danger", "Error", "Kota belum dipilih.");
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
            if (this.saveData.tahun == "Pilih") {
                this.notif("danger", "Error", "Tahun belum dipilih.");
                return false;
            }
            if (this.saveData.jenis_id == "Pilih") {
                this.notif("danger", "Error", "Jenis belum dipilih.");
                return false;
            }
            // if (this.saveData.jumlah_alsin <= 0) {
            //     this.notif("danger", "Error", "Jumlah alsin tidak boleh 0.");
            //     return false;
            // }
            // if (this.saveData.lat == null || this.saveData.long == null) {
            //     this.notif("danger", "Error", "Anda belum menentukan titik lokasi.");
            //     return false;
            // }
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
                url: this.publicUrl + "api/opsin/" + this.id,
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

        goToDetail(i){
            const data = this.data.data[i]
            this.$router.push({ name: "opsinDetail", params: {id: data.id} });
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
                        verkota: false,
                        verkec: false,
                        detail: false,
					}
					let data_rule = this.listLevel.find(o => o.level === this.user.level)
					
					if (data_rule.rule.find(o => o.name === 'alsin_add')) {	
						this.hasAccess.add = data_rule.rule.find(o => o.name === 'alsin_add').active
					}
					if (data_rule.rule.find(o => o.name === 'alsin_edit')) {	
						this.hasAccess.edit = data_rule.rule.find(o => o.name === 'alsin_edit').active
					}
					if (data_rule.rule.find(o => o.name === 'alsin_delete')) {	
						this.hasAccess.delete = data_rule.rule.find(o => o.name === 'alsin_delete').active
					}
					if (data_rule.rule.find(o => o.name === 'alsin_ver_kota')) {	
						this.hasAccess.verkota = data_rule.rule.find(o => o.name === 'alsin_ver_kota').active
					}
					if (data_rule.rule.find(o => o.name === 'alsin_ver_kec')) {	
						this.hasAccess.verkec = data_rule.rule.find(o => o.name === 'alsin_ver_kec').active
					}
					if (data_rule.rule.find(o => o.name === 'alsin_detail')) {	
						this.hasAccess.detail = data_rule.rule.find(o => o.name === 'alsin_detail').active
					}
				}else{
					this.hasAccess = {
						add: true,
						edit: true,
                        delete: true,
                        verkota: true,
                        verkec: true,
                        detail: true,
					}
				}
			}
        },

        // Verifikasi Data
        verifikasi(i, tingkat, val){
            let data = this.data.data[i]
            let saveData = {
                user_id: this.user.id
            }
            if (tingkat == 'kota') {
                saveData.vrf_kota = val
                data.vrf_kota = val
            }else{
                saveData.vrf_kec = val
                data.vrf_kec = val
            }
            axios({
                method: "post",
                url: this.publicUrl + "api/opsin/verifikasi/" + data.id,
                data: saveData
            })
                .then((response) => {
                    this.notif("success", "Success", "Update verifikasi berhasil.");
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
                });
        },

        loadKorwil(){
			if (this.user) {
				const user = this.user
				if (user.level == 96) {
					axios
						.get(this.publicUrl + "api/korwil/byUser/" + user.id)
						.then((response) => {
							// handle success
							this.isKoordinatorKota = true
							this.pilihKota = this.formKotaId = response.data.data.kota_id
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
							this.isKoordinatorKota = true
                            this.isKoordinatorKec = true
                            
							this.pilihKota = this.formKotaId = response.data.data.kota_id
							this.pilihKecamatan = this.formKecamatanId = response.data.data.kecamatan_id
						})
						.catch((error) => {
							this.swr();
						});
				}
				
			}
		},

        
        showModalVrf(tingkat = 'kec'){
            if (tingkat == 'kota') {
                this.isVrf = "Kota"
            }else{
                this.isVrf = "Kecamatan"
            }
            this.$refs["modal-verifikasi"].show();
        },

        hideModalVrf() {
            this.$refs["modal-verifikasi"].hide();
        },

        processVrf(){
            this.loading = true
            var params = {}
            params.params = {};

            if (this.pilihTahun != "Pilih") {
                params.params.tahun = this.pilihTahun
            }
            if (this.pilihKota != "Pilih") {
                params.params.kota_id = this.pilihKota
            }
            if (this.pilihKecamatan != "Pilih") {
                params.params.kecamatan_id = this.pilihKecamatan
            }
            if (this.pilihDesa != "Pilih") {
                params.params.kelurahan_id = this.pilihDesa
            }
            if (this.pilihJenis != "Pilih") {
                params.params.jenis_id = this.pilihJenis
            }
            if (this.isVrf == "Kota") {
                params.vrf_kota = 1
                params.params.vrf_kota = 0
            }else{
                params.vrf_kec = 1
                params.params.vrf_kec = 0
            }
            params.user_id= this.user.id
            axios({
                method: "post",
                url: this.publicUrl + "api/opsin/vrf_all",
                data: params
            })
            .then((response) => {
                this.notif("success", "Success", "Data berhasil diverifikasi");
                this.hideModalVrf();
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
        }
    },

	created() {
        this.loadLevel()
		this.cekParam();
		this.loadListJenis();
		this.loadListKota();
        this.loadData();
        this.loadKorwil();
	} 
}
</script>