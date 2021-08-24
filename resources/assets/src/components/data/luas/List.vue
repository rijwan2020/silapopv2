<template>  
    <div>
        <div class="d-flex justify-content-between align-items-center w-100 mb-0 border-bottom">
            <div class="d-flex align-items-center">
                <h5 class="m-1 d-inline-block text-nowrap font-weight-normal">
                    Data / Data Luas
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
					title="Tambah Data Luas"
					@click="showModal('add')"
					size="sm" >
					<i class="ion ion-md-add"></i> Tambah
				</b-btn>
            </div>
        </div>
		
		<div class="row pt-3">
			<div class="col-md-2">
				<b-card class="mb-4">
					<p class="card-text">Total Luas Tanam</p>
					<h4 class="card-title">{{ data.total_luas_tanam }} Ha</h4>
				</b-card>
			</div>
			<div class="col-md-2">
				<b-card class="mb-4">
					<p class="card-text">Total Tambah Tanam</p>
					<h4 class="card-title">{{ data.total_tambah_tanam }} Ha</h4>
				</b-card>
			</div>
			<div class="col-md-2">
				<b-card class="mb-4">
					<p class="card-text">Total Produksi</p>
					<h4 class="card-title">{{ data.total_produksi }} Ton</h4>
				</b-card>
			</div>
			<div class="col-md-2">
				<b-card class="mb-4">
					<p class="card-text">Total Luas Panen</p>
					<h4 class="card-title">{{ data.total_luas_panen }} Ha</h4>
				</b-card>
			</div>
			<div class="col-md-2">
				<b-card class="mb-4">
					<p class="card-text">Total Produktivitas</p>
					<h4 class="card-title">{{ data.total_produktivitas }} Kw/Ha</h4>
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
					<template v-slot:cell(jenis)="data">
                        <label v-if="data.item.jenis == 1">
                            Sawah
                        </label>
                        <label v-else>
                            Ladang
                        </label>
                    </template>

                    <!-- Komoditas -->
					<template v-slot:cell(komoditi_id)="data">
                        {{ data.item.komoditi }}
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
                    <template v-slot:cell(files)="data">
                        <div 
                            v-for="(file, i) in data.item.files"
                            :key="i">
                            <a :href="file.url" target="_blank">{{ file.file }}</a>
                        </div>
                    </template> 

                    
					<!-- Action Template -->
					<template v-slot:cell(actions)="data">
						<b-btn
							variant="primary btn-sm icon-btn md-btn-flat"
							v-b-tooltip.hover
							:title="`Edit Data Luas`"
                            v-if="data.item.vrf_kota == 0 && data.item.vrf_kec == 0"
							@click="showModal('edit', data.index)"
						>
							<i class="ion ion-md-create"></i>
						</b-btn>
						<b-btn
							variant="danger btn-sm icon-btn md-btn-flat"
							v-b-tooltip.hover
							:title="`Hapus Data Luas`"
							@click="deleteModal(data.index)"
                            v-if="data.item.vrf_kota == 0 && data.item.vrf_kec == 0"
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
                <div class="col-md-1 px-0">
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

                <!-- Bulan -->
                <div class="col-md-2 px-0">
                    <b-form-group label="Bulan" class="w-auto m-1">
                        <b-select size="md" v-model="pilihBulan">
                            <option value="Pilih">Pilih</option>
                            <option
                                v-for="(bulan, i) in opsiBulan"
                                :key="i"
                                :value="i"
                            >
                                {{ bulan }}
                            </option>
                        </b-select>
                    </b-form-group>
                </div>

                <!-- Jenis -->
                <div class="col-md-2 px-0">
                    <b-form-group label="Jenis" class="w-auto m-1">
                        <b-select size="md" v-model="pilihJenis">
                            <option value="Pilih">Pilih</option>
                            <option value="1">Sawah</option>
                            <option value="2">Ladang</option>
                        </b-select>
                    </b-form-group>
                </div>

                <!-- Komoditas -->
                <div class="col-md-3 px-0">
                    <b-form-group label="Komoditas" class="w-auto m-1">
                        <b-select size="md" v-model="pilihKomoditas">
                            <option value="Pilih">Pilih</option>
                            <option
                                v-for="(komoditas, i) in komoditasList"
                                :key="i"
                                :value="komoditas.id"
                            >
                                {{ komoditas.nama }}
                            </option>
                        </b-select>
                    </b-form-group>
                </div>

                <div class="col-md-2 px-0 text-right">
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
				{{ mode == "add" ? "Add" : "Update" }} Data Luas
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

            <!-- Tanggal Input -->
            <b-form-row>
                <b-form-group label="Tanggal" class="d-inline-block w-auto m-1">
                    <b-datepicker 
                        size="md" 
                        placeholder="Pilih Tanggal" 
                        locale="id"
                        :max="currentDate"
                        min="2016-01-01"
                        v-model="saveData.tanggal" />
                </b-form-group>
            </b-form-row>

            <!-- Jenis -->
            <b-form-row>
                <b-form-group label="Jenis" class="d-inline-block w-auto m-1">
                    <b-select size="md" v-model="saveData.jenis">
                        <option value="Pilih">Pilih</option>
                        <option value="1">Sawah</option>
                        <option value="2">Ladang</option>
                    </b-select>
                </b-form-group>
            </b-form-row>

            <!-- Komoditas -->
            <b-form-row>
                <b-form-group label="Komoditas" class="d-inline-block w-auto m-1">
                    <b-select size="md" v-model="saveData.komoditi_id">
						<option value="Pilih">Pilih</option>
						<option
							v-for="(komoditas, i) in komoditasList"
							:key="i"
							:value="komoditas.id"
						>
							{{ komoditas.nama }}
						</option>
                    </b-select>
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

            <!-- Luas Tanam -->
			<b-form-row>
				<b-form-group label="Luas Tanam" class="col">
                    <b-input-group>
                        <b-input
                            placeholder="Luas Tanam"
                            v-model="saveData.luas_tanam"
                        />
                        <b-input-group-text slot="append">Ha</b-input-group-text>
                    </b-input-group>
				</b-form-group>
			</b-form-row>

            <!-- File Attachment -->
			<b-form-row>
				<b-form-group label="File Attachment Luas Tanam" class="col">
					<b-input-group>
						<b-file v-model="attachment.luasTanam"></b-file>
						<b-input placeholder="Keterangan File" v-model="saveData.ket_file_luas_tanam" />
					</b-input-group>
					<small v-if="mode == 'edit'">Kosongkan file attacment jika tidak akan diganti.</small>
					<p class="mt-2" v-if="attachment.luasTanam != null">File yang dipilih: <b>{{ attachment.luasTanam ? attachment.luasTanam.name : '' }}</b></p>
				</b-form-group>
			</b-form-row>

            <!-- Tambah Tanam -->
			<b-form-row>
				<b-form-group label="Tambah Tanam" class="col">
                    <b-input-group>
                        <b-input
                            placeholder="Tambah Tanam"
                            v-model="saveData.tambah_tanam"
                        />
                        <b-input-group-text slot="append">Ha</b-input-group-text>
                    </b-input-group>
				</b-form-group>
			</b-form-row>

            <!-- File Attachment -->
			<b-form-row>
				<b-form-group label="File Attachment Tambah Tanam" class="col">
					<b-input-group>
						<b-file v-model="attachment.tambahTanam"></b-file>
						<b-input placeholder="Keterangan File" v-model="saveData.ket_file_tambah_tanam" />
					</b-input-group>
					<small v-if="mode == 'edit'">Kosongkan file attacment jika tidak akan diganti.</small>
					<p class="mt-2" v-if="attachment.tambahTanam != null">File yang dipilih: <b>{{ attachment.tambahTanam ? attachment.tambahTanam.name : '' }}</b></p>
				</b-form-group>
			</b-form-row>

            <!-- Produksi -->
			<b-form-row>
				<b-form-group label="Produksi" class="col">
                    <b-input-group>
                        <b-input
                            placeholder="Produksi"
                            v-model="saveData.produksi"
                        />
                        <b-input-group-text slot="append">Ton</b-input-group-text>
                    </b-input-group>
				</b-form-group>
			</b-form-row>

            <!-- File Attachment -->
			<b-form-row>
				<b-form-group label="File Attachment Produksi" class="col">
					<b-input-group>
						<b-file v-model="attachment.produksi"></b-file>
						<b-input placeholder="Keterangan File" v-model="saveData.ket_file_produksi" />
					</b-input-group>
					<small v-if="mode == 'edit'">Kosongkan file attacment jika tidak akan diganti.</small>
					<p class="mt-2" v-if="attachment.produksi != null">File yang dipilih: <b>{{ attachment.produksi ? attachment.produksi.name : '' }}</b></p>
				</b-form-group>
			</b-form-row>

            <!-- Luas Panen -->
			<b-form-row>
				<b-form-group label="Luas Panen" class="col">
                    <b-input-group>
                        <b-input
                            placeholder="Luas Panen"
                            v-model="saveData.luas_panen"
                        />
                        <b-input-group-text slot="append">Ha</b-input-group-text>
                    </b-input-group>
				</b-form-group>
			</b-form-row>

            <!-- File Attachment -->
			<b-form-row>
				<b-form-group label="File Attachment Produksi" class="col">
					<b-input-group>
						<b-file v-model="attachment.luasPanen"></b-file>
						<b-input placeholder="Keterangan File" v-model="saveData.ket_file_luas_panen" />
					</b-input-group>
					<small v-if="mode == 'edit'">Kosongkan file attacment jika tidak akan diganti.</small>
					<p class="mt-2" v-if="attachment.luasPanen != null">File yang dipilih: <b>{{ attachment.luasPanen ? attachment.luasPanen.name : '' }}</b></p>
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

<script>
import LaddaBtn from "@/vendor/libs/ladda/Ladda";
import { SweetModal } from "sweet-modal-vue";
export default {
	name: 'list',
	metaInfo: {
		title: 'Data Luas'
	},
    components: {
        LaddaBtn,
        SweetModal
    },
    data: () => ({
		// Options
		sortBy: "tanggal",
		sortDesc: true,
		perPage: 10,

		fields: [
			{ key: "id", label:"#", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "name", label:"Nama", sortable: false, tdClass: "text-nowrap align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "nip", label:"NIP", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "status_penyuluh", label:"Status Penyuluh", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "kota", label:"Kota", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "kecamatan", label:"Kecamatan", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "desa", label:"Desa", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "tanggal", label:"Tanggal", sortable: false, tdClass: "text-nowrap align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "jenis", label:"Jenis", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "komoditi_id", label:"Komoditas", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "luas_tanam", label:"Luas Tanam [Ha]", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "tambah_tanam", label:"Tambah Tanam [Ha]", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "produksi", label:"Produksi [Ton]", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "luas_panen", label:"Luas Panen [Ha]", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "produktivitas", label:"Produktivitas [Kw/Ha]", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "files", label:"Attachment", sortable: false, tdClass: "text-nowrap align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "vrf_kota", label:"Vrf Kota/Kab", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "vrf_kec", label:"Vrf Kecamatan", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
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

		loading: false,
		mode: "add",
		index: null,
        titleDeleteModal: "Are you sure?",

        pilihTahun: 'Pilih',
        pilihKota: 'Pilih',
        pilihKecamatan: 'Pilih',
        pilihDesa: 'Pilih',
		pilihJenis: 'Pilih',
		pilihKomoditas: 'Pilih',

        komoditasList: [],
        kotaList: [],
        kecamatanList: [],
        desaList: [],
		id: null,

        attachment: {
            luasTanam: null,
            tambahTanam: null,
            produksi: null,
            luasPanen: null,
        },
		
		pilihBulan: 'Pilih',
        opsiBulan: {
            1: "Januari",
            2: "Februari",
            3: "Maret",
            4: "April",
            5: "Mei",
            6: "Juni",
            7: "Juli",
            8: "Agustus",
            9: "September",
            10: "Oktober",
            11: "November",
            12: "Desember",
        },
        
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
		
        currentDate(){
            const curDate = new Date().toJSON().slice(0,10).replace(/-/g,'-');
            return curDate
        }
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

        pilihBulan() {
            this.loadData();
        },

        pilihKomoditas() {
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
            if (this.pilihBulan != "Pilih") {
                this.loadParams.params.bulan = this.pilihBulan
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
                this.loadParams.params.jenis = this.pilihJenis
			}
            if (this.pilihKomoditas != "Pilih") {
                this.loadParams.params.komoditi_id = this.pilihKomoditas
			}

			axios
				.get(this.publicUrl + "api/dataluas", {
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
            if (this.pilihBulan != "Pilih") {
                params.params.bulan = this.pilihBulan
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
                params.params.jenis = this.pilihJenis
			}
            if (this.pilihKomoditas != "Pilih") {
                params.params.komoditi_id = this.pilihKomoditas
			}

            params.user_id = this.user.id

			this.notif("info", "Success", "Data sedang disiapkan, silakan cek secara berkala di menu download");
            axios({
                method: "post",
                url: this.publicUrl + "api/dataluas/download",
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

        loadListKomoditas(){
            axios
				.get(this.publicUrl + "api/komoditas")
				.then((response) => {
					// handle success
                    this.komoditasList = response.data.data;
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
                    tanggal: data.tanggal,
                    jenis: data.jenis,
                    komoditi_id: data.komoditi_id,
                    luas_tanam: data.luas_tanam,
                    luas_panen: data.luas_panen,
                    produksi: data.produksi,
                    tambah_tanam: data.tambah_tanam,
                    ket_file_luas_tanam: data.ket_file_luas_tanam,
                    ket_file_tambah_tanam: data.ket_file_tambah_tanam,
                    ket_file_luas_panen: data.ket_file_luas_panen,
                    ket_file_produksi: data.ket_file_produksi,
                    lat: data.lat,
                    long: data.long,
                }
                this.formKotaId = data.kota_id
                this.formKecamatanId = data.kecamatan_id
                this.formDesaId = data.kelurahan_id
                this.id = data.id
            }else{
                this.saveData = {
					jenis: "Pilih",
					komoditi_id: "Pilih",
					tanggal: this.currentDate,
					luas_tanam: 0,
					tambah_tanam: 0,
					produksi: 0,
					luas_panen: 0,
					lat: null,
					long: null,
                    ket_file_luas_tanam: "",
                    ket_file_tambah_tanam: "",
                    ket_file_luas_panen: "",
                    ket_file_produksi: "",
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
                url: this.publicUrl + "api/dataluas",
                data: this.saveData
            })
                .then((response) => {
                    this.id = response.data.data.id
                        this.uploadFile()
                    this.notif("success", "Success", "Data berhasil ditambahkan");
                    this.hideModal();
                    let obj = {};
                    this.saveData = obj;
                    this.attachment = {
                        luasTanam: {},
                        tambahTanam: {},
                        produksi: {},
                        luasPanen: {},
                    }
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
                url: this.publicUrl + "api/dataluas/" + this.id,
                data: this.saveData
            })
                .then((response) => {
                    this.uploadFile()
                    this.notif("success", "Success", "Data berhasil diperbaharui.");
                    this.hideModal();
                    let obj = {};
                    this.saveData = obj;
                    this.attachment = {
                        luasTanam: {},
                        tambahTanam: {},
                        produksi: {},
                        luasPanen: {},
                    }
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
            if (this.attachment.luasTanam == null && this.attachment.tambahTanam == null && this.attachment.luasPanen == null && this.attachment.produksi == null) {
                return true
            }
            console.log(this.attachment);

            let formData = new FormData();
            if(this.attachment.luasTanam != null){
                formData.append('luas_tanam', this.attachment.luasTanam);
            }
            if(this.attachment.tambahTanam != null){
                formData.append('tambah_tanam', this.attachment.tambahTanam);
            }
            if (this.attachment.luasPanen != null) {
                formData.append('luas_panen', this.attachment.luasPanen);
            }
            if (this.attachment.produksi != null) {
                formData.append('produksi', this.attachment.produksi);
            }
            formData.append('id', this.id)


            axios.post(
                this.publicUrl + "api/dataluas/file",
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
            if (this.saveData.tanggal_input == "") {
                this.notif("danger", "Error", "Tanggal belum dipilih.");
                return false;
            }
            if (this.saveData.jenis == "Pilih") {
                this.notif("danger", "Error", "Jenis belum dipilih.");
                return false;
            }
            if (this.saveData.komoditi_id == "Pilih") {
                this.notif("danger", "Error", "Komoditas belum dipilih.");
                return false;
            }
            if (this.saveData.luas_tanam <= 0 && this.saveData.luas_panen <= 0 && this.saveData.produksi <= 0 && this.saveData.tambah_tanam <= 0) {
                this.notif("danger", "Error", "Isi salah satu nilai antara Luas Tanam / Tambah Tanam / Produksi / Luas Panen.");
                return false;
            }
            if (this.saveData.lat == null || this.saveData.long == null) {
                this.notif("danger", "Error", "Anda belum menentukan titik lokasi.");
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
                url: this.publicUrl + "api/dataluas/" + this.id,
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
                        verkota: false,
                        verkec: false,
					}
					let data_rule = this.listLevel.find(o => o.level === this.user.level)
					
					if (data_rule.rule.find(o => o.name === 'data_luas_add')) {	
						this.hasAccess.add = data_rule.rule.find(o => o.name === 'data_luas_add').active
					}
					if (data_rule.rule.find(o => o.name === 'data_luas_edit')) {	
						this.hasAccess.edit = data_rule.rule.find(o => o.name === 'data_luas_edit').active
					}
					if (data_rule.rule.find(o => o.name === 'data_luas_delete')) {	
						this.hasAccess.delete = data_rule.rule.find(o => o.name === 'data_luas_delete').active
					}
					if (data_rule.rule.find(o => o.name === 'data_luas_ver_kota')) {	
						this.hasAccess.verkota = data_rule.rule.find(o => o.name === 'data_luas_ver_kota').active
					}
					if (data_rule.rule.find(o => o.name === 'data_luas_ver_kec')) {	
						this.hasAccess.verkec = data_rule.rule.find(o => o.name === 'data_luas_ver_kec').active
					}
				}else{
					this.hasAccess = {
						add: true,
						edit: true,
                        delete: true,
                        verkota: true,
                        verkec: true,
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
                url: this.publicUrl + "api/dataluas/verifikasi/" + data.id,
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
            if (this.pilihBulan != "Pilih") {
                params.params.bulan = this.pilihBulan
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
                params.params.jenis = this.pilihJenis
			}
            if (this.pilihKomoditas != "Pilih") {
                params.params.komoditi_id = this.pilihKomoditas
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
                url: this.publicUrl + "api/dataluas/vrf_all",
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
        this.loadLevel();
		this.cekParam();
		this.loadListKota();
        this.loadListKomoditas();
        this.loadData();
        this.loadKorwil();
	} 
}
</script>