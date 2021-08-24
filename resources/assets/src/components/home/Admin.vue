<template>
	<div>
        <h4>Kepenyuluhan</h4>
        <div class="row mb-4">
            <div class="col-md-12 p-2 detail">
                <!-- <router-link to="/data/bakulahan" class="text-dark"> -->
                    <div class="card text-center">
                        <div class="card-header h5">Total Penyuluh</div>
                        <div class="card-header h4">{{ data.penyuluh.total | separator }} Orang</div>
                    </div>
                <!-- </router-link> -->
            </div>
            <div class="col-md-6 p-2 detail">
                <!-- <router-link to="/data/bakulahan" class="text-dark"> -->
                    <div class="card text-center">
                        <div class="card-header h5">Total PPL</div>
                        <div class="card-header h4">{{ data.penyuluh.total_ppl | separator }} Orang</div>
                    </div>
                <!-- </router-link> -->
            </div>
            <div class="col-md-6 p-2 detail">
                <!-- <router-link to="/data/bakulahan" class="text-dark"> -->
                    <div class="card text-center">
                        <div class="card-header h5">Total POPT</div>
                        <div class="card-header h4">{{ data.penyuluh.total_popt | separator }} Orang</div>
                    </div>
                <!-- </router-link> -->
            </div>
            <div class="col-md-2 offset-md-1 p-2 detail">
                <!-- <router-link to="/data/bakulahan" class="text-dark"> -->
                    <div class="card text-center">
                        <div class="card-header h5">Total PNS</div>
                        <div class="card-header h4">{{ data.penyuluh.total_pns | separator }} Orang</div>
                    </div>
                <!-- </router-link> -->
            </div>
            <div class="col-md-2 p-2 detail">
                <!-- <router-link to="/data/bakulahan" class="text-dark"> -->
                    <div class="card text-center">
                        <div class="card-header h5">Total TBPP</div>
                        <div class="card-header h4">{{ data.penyuluh.total_tbpp | separator }} Orang</div>
                    </div>
                <!-- </router-link> -->
            </div>
            <div class="col-md-2 p-2 detail">
                <!-- <router-link to="/data/bakulahan" class="text-dark"> -->
                    <div class="card text-center">
                        <div class="card-header h5">Total TBPPD</div>
                        <div class="card-header h4">{{ data.penyuluh.total_tbppd | separator }} Orang</div>
                    </div>
                <!-- </router-link> -->
            </div>
            <div class="col-md-2 p-2 detail">
                <!-- <router-link to="/data/bakulahan" class="text-dark"> -->
                    <div class="card text-center">
                        <div class="card-header h5">Total THL-POPT</div>
                        <div class="card-header h4">{{ data.penyuluh.total_thl_popt | separator }} Orang</div>
                    </div>
                <!-- </router-link> -->
            </div>
            <div class="col-md-2 p-2 detail">
                <!-- <router-link to="/data/bakulahan" class="text-dark"> -->
                    <div class="card text-center">
                        <div class="card-header h5">Total PPPK</div>
                        <div class="card-header h4">{{ data.penyuluh.total_pppk | separator }} Orang</div>
                    </div>
                <!-- </router-link> -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 p2 mb-4">
                <div class="card text-center">
                    <div class="card-header h6">Grafik Jenis Penyuluh</div>
                    <vue-echart :options="jenisPenyuluhChart" :autoresize="true"></vue-echart>
                </div>
            </div>
            <div class="col-md-6 p2 mb-4">
                <div class="card text-center">
                    <div class="card-header h6">Grafik Status Penyuluh</div>
                    <vue-echart :options="statusPenyuluhChart" :autoresize="true"></vue-echart>
                </div>
            </div>
        </div>
        
        <h4>Rekapitulasi Data</h4>
        <div class="row mb-4">
            <div class="col-md-3 p-2 detail">
                <router-link to="/data/bakulahan" class="text-dark">
                    <div class="card text-center">
                        <div class="card-header h5">Total Baku Lahan</div>
                        <div class="card-header h4">{{ data.bakulahan.total_luas | separator }} Ha</div>
                    </div>
                </router-link>
            </div>
            <div class="col-md-3 p-2 detail">
                <router-link to="data/luas" class="text-dark">
                    <div class="card text-center">
                        <div class="card-header h5">Total Luas Tanam</div>
                        <div class="card-header h4">{{ data.dataluas.total_luas_tanam | separator }} Ha</div>
                    </div>
                </router-link>
            </div>
            <div class="col-md-3 p-2 detail">
                <router-link to="data/luas" class="text-dark">
                    <div class="card text-center">
                        <div class="card-header h5">Total Luas Tambah Tanam</div>
                        <div class="card-header h4">{{ data.dataluas.total_tambah_tanam | separator }} Ha</div>
                    </div>
                </router-link>
            </div>
            <div class="col-md-3 p-2 detail">
                <router-link to="data/luas" class="text-dark">
                    <div class="card text-center">
                        <div class="card-header h5">Total Produksi</div>
                        <div class="card-header h4">{{ data.dataluas.total_produksi | separator }} Ton</div>
                    </div>
                </router-link>
            </div>
            <div class="col-md-3 p-2 detail">
                <router-link to="data/luas" class="text-dark">
                    <div class="card text-center">
                        <div class="card-header h5">Total Luas Panen</div>
                        <div class="card-header h4">{{ data.dataluas.total_luas_panen | separator }} Ha</div>
                    </div>
                </router-link>
            </div>
            <div class="col-md-3 p-2 detail">
                <router-link to="data/luas" class="text-dark">
                    <div class="card text-center">
                        <div class="card-header h5">Total Produktivitas</div>
                        <div class="card-header h4">{{ data.dataluas.total_produktivitas | separator }} Kw/Ha</div>
                    </div>
                </router-link>
            </div>
            <div class="col-md-3 p-2 detail">
                <router-link to="data/htp" class="text-dark">
                    <div class="card text-center">
                        <div class="card-header h5">Rata - Rata Harga Tingkat Petani</div>
                        <div class="card-header h4">{{ data.htp.rata_rata | separator }} Rp/Kg</div>
                    </div>
                </router-link>
            </div>
            <div class="col-md-3 p-2 detail">
                <router-link to="data/opsin" class="text-dark">
                    <div class="card text-center">
                        <div class="card-header h5">Total Jumlah Alsin</div>
                        <div class="card-header h4">{{ data.opsin.total_alsin | separator }} Unit</div>
                    </div>
                </router-link>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header h5">
                        Data Sawah Ladang
                    </div>
                    <div class="card-wrapper">
                        <div class="table-responsive">
                            <b-table
                                :items="dataSawahLadang"
                                :fields="sawahLadangField"
                                :striped="true"
                                :bordered="true"
                                :busy="isBusy"
                                class="card-table"
                            >
                                <!-- Baku Lahan -->
                                <template v-slot:cell(bakulahan)="data">
                                    {{ data.item.bakulahan | separator }}
                                </template>

                                <!-- Luas Tanam -->
                                <template v-slot:cell(luas_tanam)="data">
                                    {{ data.item.luas_tanam | separator }}
                                </template>

                                <!-- Tambah Tanam -->
                                <template v-slot:cell(tambah_tanam)="data">
                                    {{ data.item.tambah_tanam | separator }}
                                </template>

                                <!-- Produksi -->
                                <template v-slot:cell(produksi)="data">
                                    {{ data.item.produksi | separator }}
                                </template>

                                <!-- Luas Panen -->
                                <template v-slot:cell(luas_panen)="data">
                                    {{ data.item.luas_panen | separator }}
                                </template>

                                <!-- Produktivitas -->
                                <template v-slot:cell(produktivitas)="data">
                                    {{ data.item.produktivitas | separator }}
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
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header h5">
                        Data Komoditas
                    </div>
                    <div class="card-wrapper">
                        <div class="table-responsive">
                            <b-table
                                :items="dataKomoditas"
                                :fields="komoditasField"
                                :striped="true"
                                :bordered="true"
                                :busy="isBusy"
                                class="card-table"
                            >
                                <!-- Luas Tanam -->
                                <template v-slot:cell(luas_tanam)="data">
                                    {{ data.item.luas_tanam | separator }}
                                </template>

                                <!-- Tambah Tanam -->
                                <template v-slot:cell(tambah_tanam)="data">
                                    {{ data.item.tambah_tanam | separator }}
                                </template>

                                <!-- Produksi -->
                                <template v-slot:cell(produksi)="data">
                                    {{ data.item.produksi | separator }}
                                </template>

                                <!-- Luas Panen -->
                                <template v-slot:cell(luas_panen)="data">
                                    {{ data.item.luas_panen | separator }}
                                </template>

                                <!-- Produktivitas -->
                                <template v-slot:cell(produktivitas)="data">
                                    {{ data.item.produktivitas | separator }}
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
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header h5">
                        Data Komoditas Harga Tingkat Petani
                    </div>
                    <div class="card-wrapper">
                        <div class="table-responsive">
                            <b-table
                                :items="dataKomoditasHtp"
                                :fields="komoditasHtpField"
                                :striped="true"
                                :bordered="true"
                                :busy="isBusy"
                                class="card-table"
                            >
                                <!-- Rata Rata -->
                                <template v-slot:cell(rata_rata)="data">
                                    {{ data.item.rata_rata | separator }}
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
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header h5">
                        Data Jenis Alsin
                    </div>
                    <div class="card-wrapper">
                        <div class="table-responsive">
                            <b-table
                                :items="dataJenisAlsin"
                                :fields="jenisAlsinField"
                                :striped="true"
                                :bordered="true"
                                :busy="isBusy"
                                class="card-table"
                            >
                                <!-- Jumlah Alsin -->
                                <template v-slot:cell(jumlah_alsin)="data">
                                    {{ data.item.jumlah_alsin | separator }}
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
                    </div>
                </div>
            </div>
        </div>
	</div>
</template>

<style>
    .detail{
        cursor: pointer;
    }
    .echarts {
        height: 300px !important;
        width: 100% !important;
    }
</style>
<style src="@/vendor/libs/c3/c3.scss" lang="scss"></style>

<script>
import ECharts from 'vue-echarts/components/ECharts.vue'

import 'echarts/lib/chart/line'
import 'echarts/lib/chart/bar'
import 'echarts/lib/chart/pie'
import 'echarts/lib/component/tooltip'
import 'echarts/lib/component/legend'

import themeSettings from '../../vendor/libs/theme-settings/theme-settings'

const colors = ['#647c8a', '#3f51b5', '#2196f3', '#00b862', '#afdf0a', '#a7b61a', '#f3e562', '#ff9800', '#ff5722', '#ff4514', '#647c8a', '#3f51b5', '#2196f3', '#00b862', '#afdf0a']
const isDarkStyle = themeSettings.isDarkStyle()
var numeral = require('numeral')
export default {
	name: 'home',
	metaInfo: {
		title: 'Home'
    },
    data: () => ({
        isBusy: false,
        dataKomoditas: [],
        komoditasField: [
			{ key: "nama", label:"Nama Komoditas", sortable: false, tdClass: "text-nowrap align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "luas_tanam", label:"Luas Tanam [Ha]", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "tambah_tanam", label:"Tambah Tanam [Ha]", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "produksi", label:"Produksi [Ton]", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "luas_panen", label:"Luas Panen [Ha]", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "produktivitas", label:"Produktivitas [Kw/Ha]", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" }
        ],
        dataKomoditasHtp: [],
        komoditasHtpField: [
			{ key: "nama", label:"Nama Komoditas", sortable: false, tdClass: "text-nowrap align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "rata_rata", label:"Rata Rata [Rp/Kg]", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
        ],
        dataJenisAlsin: [],
        jenisAlsinField: [
			{ key: "nama", label:"Nama Jenis", sortable: false, tdClass: "text-nowrap align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "jumlah_alsin", label:"Jumlah [Unit]", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
        ],
        dataSawahLadang: [],
        sawahLadangField: [
			{ key: "nama", label:"Data", sortable: false, tdClass: "text-nowrap align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "bakulahan", label:"Baku Lahan [Ha]", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "luas_tanam", label:"Luas Tanam [Ha]", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "tambah_tanam", label:"Tambah Tanam [Ha]", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "produksi", label:"Produksi [Ton]", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "luas_panen", label:"Luas Panen [Ha]", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "produktivitas", label:"Produktivitas [Kw/Ha]", sortable: false, tdClass: "align-middle", thClass: "text-nowrap align-middle text-center" }
        ],
        data: {
            penyuluh: {},
            bakulahan: {},
            dataluas: {},
            htp: {},
            opsin: {},
        },

        yAxis: {
            splitLine: { show: false },
            axisLine: {
                lineStyle: { color: isDarkStyle ? '#383b40' : 'rgba(0, 0, 0, .08)' }
            },
            axisLabel: { color: isDarkStyle ? '#fff' : 'rgba(0, 0, 0, .5)' }
        },
        tooltip: {
            trigger: 'axis',
            backgroundColor: '#46494d',
            textStyle: {
                fontSize: 11
            }
        },
        itemStyle: {
            normal: {
                color: function (params) {
                    var num = colors.length
                    return colors[params.dataIndex % num]
                },
                opacity: 0.5
            },
            emphasis: {
                opacity: 1,
                shadowBlur: 50,
                shadowColor: 'rgba(0, 0, 0, 0.1)'
            }
        },
    }),
    
    filters:{
        separator(x){
            if(x == 0) return 0
            if(!x) return ''
            var parts = x.toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(".");
        },
    },
    components: {
        'vue-echart': ECharts
    },
    computed:{
        jenisPenyuluhChart(){
            const chart = {
                color: colors,
                tooltip: this.tooltip,
                xAxis: {
                    data: ["PPL", "POPT"],
                    axisLine: {
                        lineStyle: { color: isDarkStyle ? '#383b40' : 'rgba(0, 0, 0, .08)' }
                    },
                    axisLabel: { color: isDarkStyle ? '#fff' : 'rgba(0, 0, 0, .5)' }
                },
                yAxis: this.yAxis,
                series: [{
                    type: 'bar',
                    showSymbol: false,
                    data: [this.data.penyuluh.total_ppl, this.data.penyuluh.total_popt],
                    barCategoryGap: '60%',
                    itemStyle: this.itemStyle,
                }],
                animationDuration: 2000
            }
            return chart;
        },
        statusPenyuluhChart(){
            const chart = {
                color: colors,
                tooltip: this.tooltip,
                xAxis: {
                    data: ["PNS", "TBPP", "TBPPD", "THL-POPT", "PPPK"],
                    axisLine: {
                        lineStyle: { color: isDarkStyle ? '#383b40' : 'rgba(0, 0, 0, .08)' }
                    },
                    axisLabel: { color: isDarkStyle ? '#fff' : 'rgba(0, 0, 0, .5)' }
                },
                yAxis: this.yAxis,
                series: [{
                    type: 'bar',
                    showSymbol: false,
                    data: [
                        this.data.penyuluh.total_pns, 
                        this.data.penyuluh.total_tbpp, 
                        this.data.penyuluh.total_tbppd, 
                        this.data.penyuluh.total_thl_popt,
                        this.data.penyuluh.total_pppk
                    ],
                    barCategoryGap: '60%',
                    itemStyle: this.itemStyle,
                }],
                animationDuration: 2000
            }
            return chart;
        },
    },
    methods:{
        loadData(){
            axios
                .get(this.publicUrl + "api/bakulahan", {
                    params: {limit: 1}
                })
                .then((response) => {
                    // handle success
                    this.data.bakulahan = response.data;
                })
                .catch((error) => {
                    this.swr();
                });
            axios
                .get(this.publicUrl + "api/dataluas", {
                    params: {limit: 1}
                })
                .then((response) => {
                    // handle success
                    this.data.dataluas = response.data;
                })
                .catch((error) => {
                    this.swr();
                });
            axios
                .get(this.publicUrl + "api/htp", {
                    params: {limit: 1}
                })
                .then((response) => {
                    // handle success
                    this.data.htp = response.data;
                })
                .catch((error) => {
                    this.swr();
                });
            axios
                .get(this.publicUrl + "api/opsin", {
                    params: {limit: 1}
                })
                .then((response) => {
                    // handle success
                    this.data.opsin = response.data;
                })
                .catch((error) => {
                    this.swr();
                });
        },
        loadPenyuluh(){
            axios
                .get(this.publicUrl + "api/dashboard/penyuluh")
                .then((response) => {
                    // handle success
                    this.data.penyuluh = response.data.data;
                })
                .catch((error) => {
                    this.swr();
                });
        },
        loadKomoditas(){
            this.isBusy = true
            axios
                .get(this.publicUrl + "api/komoditas")
                .then((response) => {
                    // handle success
                    this.dataKomoditas = response.data.data;
                    this.isBusy = false
                })
                .catch((error) => {
                    this.swr();
                });
        },
        loadKomoditasHtp(){
            this.isBusy = true
            axios
                .get(this.publicUrl + "api/komoditashtp")
                .then((response) => {
                    // handle success
                    this.dataKomoditasHtp = response.data.data;
                    this.isBusy = false
                })
                .catch((error) => {
                    this.swr();
                });
        },
        loadSawahLadang(){
            this.isBusy = true
            axios
                .get(this.publicUrl + "api/dashboard/sawahladang")
                .then((response) => {
                    // handle success
                    this.dataSawahLadang = response.data.data;
                    this.isBusy = false
                })
                .catch((error) => {
                    this.swr();
                });
        },
        loadJenisAlsin(){
            this.isBusy = true
            axios
                .get(this.publicUrl + "api/jenisalsin")
                .then((response) => {
                    // handle success
                    this.dataJenisAlsin = response.data.data;
                    this.isBusy = false
                })
                .catch((error) => {
                    this.swr();
                });
        },
    },
    created(){
        this.loadData();
        this.loadPenyuluh();
        this.loadKomoditas();
        this.loadKomoditasHtp();
        this.loadSawahLadang();
        this.loadJenisAlsin();
    }
}
</script>
