<template>
    <div>
        <div class="row">
            <div class="col-xl-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="media">
                            <img :src="penyuluh.image_url" alt class="ui-w-60 rounded-circle">
                            <div class="media-body pt-2 ml-3">
                                <h5 class="mb-2">{{ penyuluh.nama }}</h5>
                                <div class="text-muted small">{{ penyuluh.user.username }}</div>
                                <div class="text-muted small">{{ statusPenyuluh[penyuluh.status_penyuluh] }}</div>
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
                        <div class="text-capitalize">
                            <p class="my-0" v-if="penyuluh.alamat">
                                {{ penyuluh.alamat }}<br>
                            </p>
                            <p class="my-0">
                                Desa {{ penyuluh.desa.name | lower }} <br>
                            </p>
                            <p class="my-0">
                                Kecamatan {{ penyuluh.kecamatan.name | lower }} <br>
                            </p>
                            <p class="my-0">
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
                    <div class="card-header font-weight-bold h5">Wilayah Kerja (Total: {{ wilayahkerja.length }})</div>
                    <div class="card-body">
                        <div class="row">
                            <b-spinner label="Spinning" variant="danger" v-if="isBusy" />
                            <div class="col-xl-6" v-for="(wilker, i) in wilayahkerja" :key="i">
                                <div class="card border-light mb-3">
                                    <div class="card-body text-dark">
                                        <p class="card-text"><span v-if="penyuluh.jp == 0">Desa {{ wilker.desa }},</span> Kecamatan {{ wilker.kecamatan }}, {{ wilker.kota }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

export default {
    name: 'detail-penyuluh',
    
	metaInfo: {
		title: 'Profile Penyuluh'
    },
    
	data: () => ({
        penyuluh: {
            user: {
                username: '',
            },
            desa: {
                name: ''
            },
            kecamatan: {
                name: ''
            },
            kota: {
                name: ''
            }
        },
        data:[],
        isBusy: false,
        statusPenyuluh: ['-','PNS','TBPP','TBPPD','THL-POPT', 'PPPK'],
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
        wilayahkerja: {},
        penyuluh_id: 0
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

	methods:{
        loadData(){
            if (this.$route.params.id) {
                this.isBusy = true;
                this.penyuluh_id = this.$route.params.id
                axios
                    .get(this.publicUrl + "api/penyuluh/" + this.penyuluh_id)
                    .then((response) => { 
                        // handle success
                        this.penyuluh = response.data.data;
                    })
                    .catch((error) => {
                        this.swr();
                    });

                axios
                    .get(this.publicUrl + "api/penyuluh/wilayahkerja", {
                        params: {id: this.penyuluh_id}
                    })
                    .then((response) => {
                        // handle success
                        this.wilayahkerja = response.data.data;
                        this.isBusy = false;
                    })
                    .catch((error) => {
                        this.swr();
                    });
            }
        },
    },
    
	created() {
        this.loadData()
	}
}
</script>
