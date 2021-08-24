<template>  
    <div>
		<div class="d-flex justify-content-between align-items-center w-100 mb-0 border-bottom">
            <div class="d-flex align-items-center">
                <h5 class="m-1 d-inline-block text-nowrap font-weight-normal">
                    Master / Penyuluh
                </h5>
            </div>
			<div class="m-1">
                <b-btn
					variant="primary"
					v-b-tooltip.hover
					title="Tambah Penyuluh"
					@click="showModal('add')"
					v-if="hasAccess.add"
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
                        {{ (data.index + 1 + ((currentPage-1) * perPage)) }}
                    </template>

					<template v-slot:cell(username)="data">
                        {{ data.item.user.username }}
                    </template>

                    <template v-slot:cell(jp)="data">
						<b-badge variant="outline-success" v-if="data.item.jp==1">
                            POPT
                        </b-badge>
                        <b-badge variant="outline-info" v-else>
                            PPL
                        </b-badge>
                    </template>

                    <template v-slot:cell(status_penyuluh)="data">
						{{ statusPenyuluh[data.item.status_penyuluh].text }}
                    </template>

                    <template v-slot:cell(jk)="data">
						<b-badge variant="outline-success" v-if="data.item.jk==1">
                            Perempuan
                        </b-badge>
                        <b-badge variant="outline-info" v-else>
                            Laki Laki
                        </b-badge>
                    </template>

					<!-- Action Template -->
					<template v-slot:cell(actions)="data">
                        <router-link 
                            :to="{ name: 'penyuluhDetail', params: { id: data.item.id }}"
                            class="btn btn-sm icon-btn btn-success md-btn-flat"
                            v-if="hasAccess.detail">
                            <i class="ion ion-md-person"></i>
                        </router-link>
						<b-btn
							variant="primary btn-sm icon-btn md-btn-flat"
							v-b-tooltip.hover
							:title="`Edit Penyuluh`"
							@click="showModal('edit', data.index)"
							v-if="hasAccess.edit"
						>
							<i class="ion ion-md-create"></i>
						</b-btn>
						<b-btn
							variant="danger btn-sm icon-btn md-btn-flat"
							v-b-tooltip.hover
							:title="`Hapus Penyuluh`"
							@click="deleteModal(data.index)"
							v-if="hasAccess.delete"
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
                        <b-select size="md" v-model="filterTempatTugasKota">
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
                        <b-select size="md" v-model="filterTempatTugasKecamatan">
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
                        <b-select size="md" v-model="filterTempatTugasDesa">
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
                <!-- Limit -->
                <div class="col-md-1 px-0">
                    <b-form-group label="Limit" class="w-auto m-1">
                        <b-select size="md" v-model="perPage" :options="[10, 20, 30, 40, 50]"/>
                    </b-form-group>
                </div>
                <!-- Jenis Penyuluh -->
                <div class="col-md-2 px-0">
                    <b-form-group label="Jenis Penyuluh" class="w-auto m-1">
                        <b-select size="md" v-model="filterJenisPenyuluh" :options="[{text: 'Pilih', value: 'Pilih'}, {text: 'POPT', value: 1}, {text: 'PPL', value: 0}]"/>
                    </b-form-group>
                </div>
                <!-- Status Penyuluh -->
                <div class="col-md-2 px-0">
                    <b-form-group label="Status Penyuluh" class="w-auto m-1">
                        <b-select size="md" v-model="filterStatusPenyuluh" :options="statusPenyuluh"/>
                    </b-form-group>
                </div>
                <!-- Jenis Kelamin -->
                <div class="col-md-1 px-0">
                    <b-form-group label="Jenis Kelamin" class="w-auto m-1">
                        <b-select size="md" v-model="filterJenisKelamin" :options="[{text: 'Semua', value: 'Pilih'}, {text: 'Perempuan', value: 1}, {text: 'Laki - Laki', value: 0}]"/>
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
				<div class="col-md-3 px-0 text-right">
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
				{{ mode == "add" ? "Tambah" : "Edit" }} Penyuluh
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
                <b-form-group label="Tanggal Lahir" class="d-inline-block w-auto m-1">
                    <b-datepicker 
                        size="md" 
                        placeholder="Pilih Tanggal" 
                        locale="id"
                        :max="currentDate"
                        min="2016-01-01"
                        v-model="saveData.tanggal_lahir" />
                </b-form-group>
            </b-form-row>
			
            <!-- Jenis Penyuluh -->
            <b-form-row>
				<b-form-group label="Jenis Penyuluh" class="col">
					<b-form-radio-group
					v-model="saveData.jp"
					:options="{0: 'Penyuluh/PPL', 1: 'POPT'}"
					class="mb-3"
					name="jenis-penyuluh"
					></b-form-radio-group>
				</b-form-group>
			</b-form-row>
			
            <!-- Status Penyuluh -->
            <b-form-row>
				<b-form-group label="Status Penyuluh" class="col">
					<b-select size="md" v-model="saveData.status_penyuluh" :options="statusPenyuluh">
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
					<b-form-radio-group
					v-model="saveData.jk"
					:options="{0: 'Laki Laki', 1: 'Perempuan'}"
					class="mb-3"
					name="jk"
					></b-form-radio-group>
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
					<b-select size="md" v-model="saveData.pangkat" :options="pangkatList">
                    </b-select>
				</b-form-group>
			</b-form-row>

            <!-- Pendidikan Terakhir -->
			<b-form-row>
				<b-form-group label="Pendidikan Terakhir" class="col">
					<b-input placeholder="Pendidikan Terakhir" v-model="saveData.pendidikan" />
				</b-form-group>
			</b-form-row>

            <!-- Nama Kelambagaan -->
			<b-form-row>
				<b-form-group label="Nama Kelambagaan" class="col">
					<b-input placeholder="Nama Kelambagaan" v-model="saveData.nama_kelembagaan" />
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

            <!-- Jumlah Kelompok Tani -->
			<b-form-row>
				<b-form-group label="Jumlah Kelompok Tani" class="col">
					<b-input placeholder="Jumlah Kelompok Tani" type="number" v-model="saveData.jml_kel_tani" />
				</b-form-group>
			</b-form-row>

            <!-- Komoditi Unggulan -->
			<b-form-row>
				<b-form-group label="Komoditi Unggulan" class="col">
					<b-input placeholder="Komoditi Unggulan" v-model="saveData.komoditi_unggulan" />
				</b-form-group>
			</b-form-row>
			
            <!-- No Hp -->
			<b-form-row>
				<b-form-group label="No Hp" class="col">
					<b-input placeholder="No Hp" v-model="saveData.no_hp" />
				</b-form-group>
			</b-form-row>
			
            <!-- Email -->
			<b-form-row>
				<b-form-group label="Email" class="col">
					<b-input placeholder="Email" v-model="saveData.email" />
				</b-form-group>
			</b-form-row>
			
            <!-- Tanggal Evaluasi -->
            <b-form-row>
                <b-form-group label="Tanggal Evaluasi" class="d-inline-block w-auto m-1">
                    <b-datepicker 
                        size="md" 
                        placeholder="Pilih Tanggal" 
                        locale="id"
                        :max="currentDate"
                        min="2016-01-01"
                        v-model="saveData.tanggal_evaluasi" />
                </b-form-group>
            </b-form-row>
			
            <!-- Foto -->
			<b-form-row>
				<b-form-group label="Foto" class="col">
					<b-file v-model="fileAttachment"></b-file>
                    <small v-if="mode == 'edit'">Kosongkan foto jika tidak akan diganti.</small>
                    <p class="mt-2" v-if="fileAttachment != null">File yang dipilih: <b>{{ fileAttachment ? fileAttachment.name : '' }}</b></p>
				</b-form-group>
			</b-form-row>

			<hr>
			<h5>Alamat</h5>
			
            <!-- Alamat -->
			<b-form-row>
				<b-form-group label="Alamat" class="col">
					<b-textarea v-model="saveData.alamat" style="height: 100px" />
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

			<!-- Modal Footer -->
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
	name: 'nama-opt',
	metaInfo: {
		title: 'Penyuluh'
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
		filterJenisPenyuluh: "Pilih",
		filterJenisKelamin: "Pilih",
		filterStatusPenyuluh: 0,
		filterTempatTugasKota: "Pilih",
		filterTempatTugasKecamatan: "Pilih",
		filterTempatTugasDesa: "Pilih",

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
		
		listLevel: {},
		hasAccess: {},
		statusPenyuluh:[
			{value: 0, text: 'Pilih'},
			{value: 1, text: 'PNS'},
			{value: 2, text: 'TBPP'},
			{value: 3, text: 'TBPPD'},
			{value: 4, text: 'THL-POPT'},
			{value: 5, text: 'PPPK'},
		],
		
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
		
        kotaList: [],
        kecamatanList: [],
        desaList: [],
        formKotaId: "Pilih",
        formKecamatanId: "Pilih",
        formDesaId: "Pilih",
        formKecamatanList: [],
		formDesaList: [],
		fileAttachment: null,

        formTempatTugasKotaId: "Pilih",
        formTempatTugasKecamatanId: "Pilih",
        formTempatTugasKecamatanList: [],
        formTempatTugasDesaId: "Pilih",
        formTempatTugasDesaList: [],
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
				key: "jp", 
				label:"Jenis Penyuluh", 
				sortable: false, 
				tdClass: "align-middle", 
				thClass: "text-nowrap align-middle text-center" 
			}
			data[i++] = { 
				key: "status_penyuluh", 
				label:"Status Penyuluh", 
				sortable: false, 
				tdClass: "align-middle", 
				thClass: "text-nowrap align-middle text-center" 
			}
			data[i++] = { 
				key: "nip", 
				label:"NIP", 
				sortable: false, 
				tdClass: "align-middle", 
				thClass: "text-nowrap align-middle text-center" 
			}
			data[i++] = { 
				key: "jk", 
				label:"Jenis Kelamin", 
				sortable: false, 
				tdClass: "align-middle", 
				thClass: "text-nowrap align-middle text-center" 
			}
			data[i++] = { 
				key: "alamat", 
				label:"Alamat", 
				sortable: false, 
				tdClass: "align-middle", 
				thClass: "text-nowrap align-middle text-center" 
			}
			data[i++] = { 
				key: "tempat_tugas", 
				label:"Tempat Tugas", 
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

		currentDate(){
            const curDate = new Date().toJSON().slice(0,10).replace(/-/g,'-');
            return curDate
        }
	},
	watch: {
        currentPage() {
            this.loadData();
        },
        perPage() {
            this.loadData();
		},
        filterJenisPenyuluh() {
            this.loadData();
		},
        filterStatusPenyuluh() {
            this.loadData();
		},
        filterJenisKelamin() {
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
		
        q(v) {
			if (v == '') {
				this.loadData()
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

		filterTempatTugasKota(v){
            if(v == 'Pilih'){
                this.kecamatanList = []
                this.filterTempatTugasKecamatan = "Pilih";
                this.desaList = []
                this.filterTempatTugasDesa = "Pilih";
            }else{
                this.loadListKecamatan('filter');
			}
			this.loadData();
		},

		filterTempatTugasKecamatan(v){
            if(v == 'Pilih'){
                this.desaList = []
                this.filterTempatTugasDesa = "Pilih";
            }else{
                this.loadListDesa('filter');
			}
			this.loadData();
		},
		filterTempatTugasDesa(){
			this.loadData();
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
				params.q = this.q;
			}

			params.params = {};
			if (this.filterJenisPenyuluh != "Pilih") {
				params.params.jp = this.filterJenisPenyuluh
			}
			if (this.filterJenisKelamin != "Pilih") {
				params.params.jk = this.filterJenisKelamin
			}
			if (this.filterStatusPenyuluh != 0) {
				params.params.status_penyuluh = this.filterStatusPenyuluh
			}
			if (this.filterTempatTugasKota != "Pilih") {
				params.params.tempat_tugas_kota_id = this.filterTempatTugasKota
				if (this.filterTempatTugasKecamatan != "Pilih") {
					params.params.tempat_tugas_kecamatan_id = this.filterTempatTugasKecamatan
					if (this.filterTempatTugasDesa != "Pilih") {
						params.params.tempat_tugas_kelurahan_id = this.filterTempatTugasDesa
					}
				}
			}

			axios
				.get(this.publicUrl + "api/penyuluh", {
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

		download(){
            var params = {}
            if (this.q != '') {
				params.q = this.q;
			}

			params.params = {};
			if (this.filterJenisPenyuluh != "Pilih") {
				params.params.jp = this.filterJenisPenyuluh
			}
			if (this.filterJenisKelamin != "Pilih") {
				params.params.jk = this.filterJenisKelamin
			}
			if (this.filterStatusPenyuluh != 0) {
				params.params.status_penyuluh = this.filterStatusPenyuluh
			}
			if (this.filterTempatTugasKota != "Pilih") {
				params.params.tempat_tugas_kota_id = this.filterTempatTugasKota
				if (this.filterTempatTugasKecamatan != "Pilih") {
					params.params.tempat_tugas_kecamatan_id = this.filterTempatTugasKecamatan
					if (this.filterTempatTugasDesa != "Pilih") {
						params.params.tempat_tugas_kelurahan_id = this.filterTempatTugasDesa
					}
				}
			}
            params.user_id = this.user.id

            this.notif("info", "Success", "Data sedang disiapkan, silakan cek secara berkala di menu download");
            axios({
                method: "post",
                url: this.publicUrl + "api/penyuluh/download",
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
			switch (show) {
				case "form":
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
					break;

				case "tempat_tugas":
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
					break;

				case "filter":
					axios
						.get(this.publicUrl + "api/kecamatan", {
							params: {kota_id: this.filterTempatTugasKota}
						})
						.then((response) => {
							// handle success
							this.kecamatanList = response.data.data;
						})
						.catch((error) => {
							this.swr();
						});
					break;
			
				default:
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
					break;
			}
            
        },

        // load list data desa
        loadListDesa(show = "list"){
			switch (show) {
				case "tempat_tugas":
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
					break;

				case "form":
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
					break;

				case "filter":
					axios
						.get(this.publicUrl + "api/desa", {
							params: {kota_id: this.filterTempatTugasKota, kecamatan_id: this.filterTempatTugasKecamatan}
						})
						.then((response) => {
							// handle success
							this.desaList = response.data.data;
						})
						.catch((error) => {
							this.swr();
						});
					break;
			
				default:
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
					break;
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
                    nama: data.nama,
                    tempat_lahir: data.tempat_lahir,
                    tanggal_lahir: data.tanggal_lahir,
                    jp: data.jp,
                    status_penyuluh: data.status_penyuluh,
                    nip: data.nip,
					jk: data.jk,
					jabatan_fungsional: data.jabatan_fungsional,
					pangkat: data.pangkat,
					pendidikan: data.pendidikan,
					nama_kelembagaan: data.nama_kelembagaan,
					bp3k: data.bp3k,
					pertanian_wkpp: data.pertanian_wkpp,
					kelompok_tani: data.kelompok_tani,
					jml_kel_tani: data.jml_kel_tani,
					komoditi_unggulan: data.komoditi_unggulan,
					alamat: data.alamat,
					provinsi_id: data.provinsi_id,
					no_hp: data.no_hp,
					email: data.email,
					tanggal_evaluasi: data.tanggal_evaluasi,
					username: data.user.username,
					password: '',
					konfirmasi_password: '',
					penyuluh_user_id: data.user_id,
				}
				this.formKotaId = data.kota_id
				this.formKecamatanId = data.kecamatan_id
				this.formDesaId = data.kelurahan_id
				this.formTempatTugasKotaId = data.tempat_tugas_kota_id
				this.formTempatTugasKecamatanId = data.tempat_tugas_kecamatan_id
				this.formTempatTugasDesaId = data.tempat_tugas_kelurahan_id
				this.id = data.id
            }else{
				this.saveData = {
                    nama: '',
                    tempat_lahir: '',
                    tanggal_lahir: '',
                    jp: 0,
                    status_penyuluh: 0,
                    nip: '',
					jk: 0,
					jabatan_fungsional: '',
					pangkat: '-',
					pendidikan: '',
					nama_kelembagaan: '',
					bp3k: '',
					pertanian_wkpp: '',
					kelompok_tani: '',
					jml_kel_tani: 0,
					komoditi_unggulan: '',
					alamat: '',
					provinsi_id: 32,
					no_hp: '',
					email: '',
					tanggal_evaluasi: '',
					username: '',
					password: '',
					konfirmasi_password: '',
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

            axios({
                method: "post",
                url: this.publicUrl + "api/penyuluh",
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

            axios({
                method: "put",
                url: this.publicUrl + "api/penyuluh/" + this.id,
                data: this.saveData
            })
                .then((response) => {
					this.notif("success", "Success", "Data berhasil diperbaharui.");
					if(this.fileAttachment != null){
                        this.uploadFile()
                    }
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
				this.notif("danger", "Error", "Password minimal 8 karakter.");
                return false;
			}
			if (this.saveData.password != "" && this.saveData.password != this.saveData.konfirmasi_password) {
				this.notif("danger", "Error", "Konfirmasi password tidak sesuai.");
                return false;
			}
			/*if (this.formKotaId == "Pilih") {
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
			}*/
            return true;
		},

		// upload file
        uploadFile(){
            let formData = new FormData();
            formData.append('file', this.fileAttachment);
            formData.append('id', this.id)

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
                url: this.publicUrl + "api/penyuluh/" + this.id,
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
						add: false,
						edit: false,
                        delete: false,
                        detail: false
					}
					let data_rule = this.listLevel.find(o => o.level === this.user.level)
					
					if (data_rule.rule.find(o => o.name === 'penyuluh_add')) {	
						this.hasAccess.add = data_rule.rule.find(o => o.name === 'penyuluh_add').active
					}
					if (data_rule.rule.find(o => o.name === 'penyuluh_edit')) {	
						this.hasAccess.edit = data_rule.rule.find(o => o.name === 'penyuluh_edit').active
					}
					if (data_rule.rule.find(o => o.name === 'penyuluh_delete')) {	
						this.hasAccess.delete = data_rule.rule.find(o => o.name === 'penyuluh_delete').active
					}
					if (data_rule.rule.find(o => o.name === 'penyuluh_detail')) {	
						this.hasAccess.detail = data_rule.rule.find(o => o.name === 'penyuluh_detail').active
					}
				}else{
					this.hasAccess = {
						add: true,
						edit: true,
                        delete: true,
                        detail: true
					}
				}
			}
		},
    },
	created() {
		this.loadLevel();
		this.loadData();
		this.loadListKota();

	}
}
</script>