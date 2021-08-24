<template>
	<div>
		<h4 class="font-weight-bold py-1 mb-4">Home</h4>
        <b-spinner label="Spinning" variant="danger" v-if="isBusy" />
        <div v-else>
            <div class="theme-bg-white mb-4">
                <div class="media col-md-10 col-lg-8 col-xl-7 py-5 mx-auto">
                    <img :src="user.image_url" alt class="d-block ui-w-100 rounded-circle">
                    <div class="media-body ml-5">
                        <h4 class="font-weight-bold mb-4">{{ user.penyuluh.nama }}</h4>

                        <div class="text-muted mb-4">
                            <span class="text-muted">{{ user.username }}</span>
                            <br>
                            <span class="text-muted">{{ statusPenyuluh[user.penyuluh.status_penyuluh] }}</span>
                        </div>
                    </div>
                </div>
                <hr class="m-0">
            </div>

            <b-card class="mb-4" no-body>
                <!-- Header -->
                <b-card-header header-tag="h4" class="media flex-wrap align-items-center">
                    <div class="media-body">
                        <router-link to="/pengumuman">
                            <i class="ion ion-md-megaphone"></i> &nbsp; Pengumuman
                        </router-link>
                    </div>
                </b-card-header>
                <!-- / Header -->
                <hr class="border-light m-0">
                <!-- / Controls -->
                <ul class="list-group messages-list"  v-if="pengumuman.total > 0">
                    <b-spinner label="Spinning" variant="danger" v-if="isPengumuman" />
                    <li class="list-group-item px-4" v-for="(message, i) in pengumuman.data" :key="i">
                        <span class="message-sender flex-shrink-1 d-block text-body">
                            {{ message.pengirim }} 
                        </span>
                        <a class="message-subject flex-shrink-1 d-block text-body">
                            | <b>{{ message.subjek }}</b>
                            <i class="ion ion-md-attach" v-if="message.file"></i>
                        </a>
                        <div class="message-date text-muted">
                            {{ message.waktu }}
                        </div>
                    </li>
                </ul>
                <ul class="list-group"  v-else>
                    <li class="list-group-item px-4 text-center">
                        <b-spinner label="Spinning" variant="danger" v-if="isPengumuman" />
                        <i v-else>Belum ada pengumuman</i>
                    </li>
                </ul>
            </b-card>
            
            <h4>Baku Lahan</h4>
            <vue-echart :options="bakuLahanChart" :autoresize="true"></vue-echart>
            <div class="row mb-4">
                <div class="col-md-3 p-2 detail" v-for="(value, i) in data.data" :key="i" @click="goToBakulahan(value.id)">
                    <div class="card text-center">
                        <div class="card-header h5">Desa {{ value.desa }}</div>
                        <div class="card-header h4">{{ value.bakulahan }} Ha</div>
                    </div>
                </div>
            </div>

            <h4>Luas Tanam</h4>
            <vue-echart :options="luasTanamChart" :autoresize="true"></vue-echart>
            <div class="row mb-4">
                <div class="col-md-3 p-2 detail" v-for="(value, i) in data.data" :key="i" @click="goToDataLuas(value.id)">
                    <div class="card text-center">
                        <div class="card-header h5">Desa {{ value.desa }}</div>
                        <div class="card-header h4">{{ value.luas_tanam }} Ha</div>
                    </div>
                </div>
            </div>

            <h4>Luas Tambah Tanam</h4>
            <vue-echart :options="tambahTanamChart" :autoresize="true"></vue-echart>
            <div class="row mb-4">
                <div class="col-md-3 p-2 detail" v-for="(value, i) in data.data" :key="i" @click="goToDataLuas(value.id)">
                    <div class="card text-center">
                        <div class="card-header h5">Desa {{ value.desa }}</div>
                        <div class="card-header h4">{{ value.tambah_tanam }} Ha</div>
                    </div>
                </div>
            </div>

            <h4>Produksi</h4>
            <vue-echart :options="produksiChart" :autoresize="true"></vue-echart>
            <div class="row mb-4">
                <div class="col-md-3 p-2 detail" v-for="(value, i) in data.data" :key="i" @click="goToDataLuas(value.id)">
                    <div class="card text-center">
                        <div class="card-header h5">Desa {{ value.desa }}</div>
                        <div class="card-header h4">{{ value.produksi }} Ton</div>
                    </div>
                </div>
            </div>

            <h4>Luas Panen</h4>
            <vue-echart :options="luasPanenChart" :autoresize="true"></vue-echart>
            <div class="row mb-4">
                <div class="col-md-3 p-2 detail" v-for="(value, i) in data.data" :key="i" @click="goToDataLuas(value.id)">
                    <div class="card text-center">
                        <div class="card-header h5">Desa {{ value.desa }}</div>
                        <div class="card-header h4">{{ value.luas_panen }} Ha</div>
                    </div>
                </div>
            </div>

            <h4>Produktivitas</h4>
            <vue-echart :options="produktivitasChart" :autoresize="true"></vue-echart>
            <div class="row mb-4">
                <div class="col-md-3 p-2 detail" v-for="(value, i) in data.data" :key="i" @click="goToDataLuas(value.id)">
                    <div class="card text-center">
                        <div class="card-header h5">Desa {{ value.desa }}</div>
                        <div class="card-header h4">{{ value.produktivitas }} Kwintal/Ha</div>
                    </div>
                </div>
            </div>

            <h4>Harga Tingkat Petani</h4>
            <vue-echart :options="htpChart" :autoresize="true"></vue-echart>
            <div class="row mb-4">
                <div class="col-md-3 p-2 detail" v-for="(value, i) in data.data" :key="i" @click="goToHtp(value.id)">
                    <div class="card text-center">
                        <div class="card-header h5">Desa {{ value.desa }}</div>
                        <div class="card-header h4">{{ value.htp }} Rp/Kg</div>
                    </div>
                </div>
            </div>

            <h4>Jumlah Alsin</h4>
            <vue-echart :options="jumlahAlsinChart" :autoresize="true"></vue-echart>
            <div class="row mb-4">
                <div class="col-md-3 p-2 detail" v-for="(value, i) in data.data" :key="i" @click="goToOpsin(value.id)">
                    <div class="card text-center">
                        <div class="card-header h5">Desa {{ value.desa }}</div>
                        <div class="card-header h4">{{ value.jumlah_alsin }} Unit</div>
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
<!-- Page -->
<style src="@/vendor/styles/pages/messages.scss" lang="scss"></style>
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

export default {
	name: 'home',
	metaInfo: {
		title: 'Home'
	},
	data: () => ({
		data: [],
        isBusy: false,
        statusPenyuluh: ['-','PNS','TBPP','TBPPD','THL-POPT', 'PPPK'],
        pengumuman: {},
        isPengumuman: false,
        loadParams: {},

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

        desa: [],
        dataChartBakuLahan: [],
        dataChartLuasTanam: [],
        dataChartTambahTanam: [],
        dataChartProduksi: [],
        dataChartLuasPanen: [],
        dataChartProduktivitas: [],
        dataChartHargaTingkatPetani: [],
        dataChartJumlahAlsin: [],
    }),
    components: {
        'vue-echart': ECharts
    },
	computed: {
		user(){
			return this.$store.state.auth.user;
        },
        bakuLahanChart() {
            const chart = {
                color: colors,
                tooltip: this.tooltip,
                xAxis: {
                    data: this.desa,
                    axisLine: {
                        lineStyle: { color: isDarkStyle ? '#383b40' : 'rgba(0, 0, 0, .08)' }
                    },
                    axisLabel: { color: isDarkStyle ? '#fff' : 'rgba(0, 0, 0, .5)' }
                },
                yAxis: this.yAxis,
                series: [{
                    type: 'bar',
                    showSymbol: false,
                    data: this.dataChartBakuLahan,
                    barCategoryGap: '60%',
                    itemStyle: this.itemStyle,
                }],
                animationDuration: 2000
            }
            return chart;
        },
        luasTanamChart() {
            const chart = {
                color: colors,
                tooltip: this.tooltip,
                xAxis: {
                    data: this.desa,
                    axisLine: {
                        lineStyle: { color: isDarkStyle ? '#383b40' : 'rgba(0, 0, 0, .08)' }
                    },
                    axisLabel: { color: isDarkStyle ? '#fff' : 'rgba(0, 0, 0, .5)' }
                },
                yAxis: this.yAxis,
                series: [{
                    type: 'bar',
                    showSymbol: false,
                    data: this.dataChartLuasTanam,
                    barCategoryGap: '60%',
                    itemStyle: this.itemStyle,
                }],
                animationDuration: 2000
            }
            return chart;
        },
        tambahTanamChart() {
            const chart = {
                color: colors,
                tooltip: this.tooltip,
                xAxis: {
                    data: this.desa,
                    axisLine: {
                        lineStyle: { color: isDarkStyle ? '#383b40' : 'rgba(0, 0, 0, .08)' }
                    },
                    axisLabel: { color: isDarkStyle ? '#fff' : 'rgba(0, 0, 0, .5)' }
                },
                yAxis: this.yAxis,
                series: [{
                    type: 'bar',
                    showSymbol: false,
                    data: this.dataChartTambahTanam,
                    barCategoryGap: '60%',
                    itemStyle: this.itemStyle,
                }],
                animationDuration: 2000
            }
            return chart;
        },
        produksiChart() {
            const chart = {
                color: colors,
                tooltip: this.tooltip,
                xAxis: {
                    data: this.desa,
                    axisLine: {
                        lineStyle: { color: isDarkStyle ? '#383b40' : 'rgba(0, 0, 0, .08)' }
                    },
                    axisLabel: { color: isDarkStyle ? '#fff' : 'rgba(0, 0, 0, .5)' }
                },
                yAxis: this.yAxis,
                series: [{
                    type: 'bar',
                    showSymbol: false,
                    data: this.dataChartProduksi,
                    barCategoryGap: '60%',
                    itemStyle: this.itemStyle,
                }],
                animationDuration: 2000
            }
            return chart;
        },
        luasPanenChart() {
            const chart = {
                color: colors,
                tooltip: this.tooltip,
                xAxis: {
                    data: this.desa,
                    axisLine: {
                        lineStyle: { color: isDarkStyle ? '#383b40' : 'rgba(0, 0, 0, .08)' }
                    },
                    axisLabel: { color: isDarkStyle ? '#fff' : 'rgba(0, 0, 0, .5)' }
                },
                yAxis: this.yAxis,
                series: [{
                    type: 'bar',
                    showSymbol: false,
                    data: this.dataChartLuasPanen,
                    barCategoryGap: '60%',
                    itemStyle: this.itemStyle,
                }],
                animationDuration: 2000
            }
            return chart;
        },
        produktivitasChart() {
            const chart = {
                color: colors,
                tooltip: this.tooltip,
                xAxis: {
                    data: this.desa,
                    axisLine: {
                        lineStyle: { color: isDarkStyle ? '#383b40' : 'rgba(0, 0, 0, .08)' }
                    },
                    axisLabel: { color: isDarkStyle ? '#fff' : 'rgba(0, 0, 0, .5)' }
                },
                yAxis: this.yAxis,
                series: [{
                    type: 'bar',
                    showSymbol: false,
                    data: this.dataChartProduktivitas,
                    barCategoryGap: '60%',
                    itemStyle: this.itemStyle,
                }],
                animationDuration: 2000
            }
            return chart;
        },
        htpChart() {
            const chart = {
                color: colors,
                tooltip: this.tooltip,
                xAxis: {
                    data: this.desa,
                    axisLine: {
                        lineStyle: { color: isDarkStyle ? '#383b40' : 'rgba(0, 0, 0, .08)' }
                    },
                    axisLabel: { color: isDarkStyle ? '#fff' : 'rgba(0, 0, 0, .5)' }
                },
                yAxis: this.yAxis,
                series: [{
                    type: 'bar',
                    showSymbol: false,
                    data: this.dataChartHargaTingkatPetani,
                    barCategoryGap: '60%',
                    itemStyle: this.itemStyle,
                }],
                animationDuration: 2000
            }
            return chart;
        },
        jumlahAlsinChart() {
            const chart = {
                color: colors,
                tooltip: this.tooltip,
                xAxis: {
                    data: this.desa,
                    axisLine: {
                        lineStyle: { color: isDarkStyle ? '#383b40' : 'rgba(0, 0, 0, .08)' }
                    },
                    axisLabel: { color: isDarkStyle ? '#fff' : 'rgba(0, 0, 0, .5)' }
                },
                yAxis: this.yAxis,
                series: [{
                    type: 'bar',
                    showSymbol: false,
                    data: this.dataChartJumlahAlsin,
                    barCategoryGap: '60%',
                    itemStyle: this.itemStyle,
                }],
                animationDuration: 2000
            }
            return chart;
        },
	},
	methods:{
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

                    for (let i = 0; i < response.data.total; i++) {
                        this.desa[i] = response.data.data[i].desa
                        this.dataChartBakuLahan[i] = response.data.data[i].bakulahan
                        this.dataChartLuasTanam[i] = response.data.data[i].luas_tanam
                        this.dataChartTambahTanam[i] = response.data.data[i].tambah_tanam
                        this.dataChartLuasPanen[i] = response.data.data[i].luas_panen
                        this.dataChartProduksi[i] = response.data.data[i].produksi
                        this.dataChartProduktivitas[i] = response.data.data[i].produktivitas
                        this.dataChartJumlahAlsin[i] = response.data.data[i].jumlah_alsin
                    }
				})
				.catch((error) => {
					this.swr();
                });
        },
        loadPengumuman(page = 1){
            this.isPengumuman = true;
            
            this.loadParams = {
                page: 1,
                order_by: 'created_at',
                sort_desc: true,
                limit: 5,
                params: {
                    status: 1
                }
            }
            
			axios
				.get(this.publicUrl + "api/pengumuman", {
					params: this.loadParams
				})
				.then((response) => {
					// handle success
                    this.pengumuman = response.data;
					this.isPengumuman = false;
				})
				.catch((error) => {
					this.swr();
				});
        },
        goToBakulahan(id){
            this.$router.push({ name: "bakulahanList", params: {id: id} });
        },
        goToDataLuas(id){
            this.$router.push({ name: "dataLuasList", params: {id: id} });
        },
        goToHtp(id){
            this.$router.push({ name: "htpList", params: {id: id} });
        },
        goToOpsin(id){
            this.$router.push({ name: "opsinList", params: {id: id} });
        },
	},
	created() {
        this.loadWilker()
        this.loadPengumuman()
    },
}
</script>
