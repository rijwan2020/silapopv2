<template>
    <div>
        <div class="d-flex justify-content-between align-items-center w-100 mb-0 border-bottom">
            <div class="d-flex align-items-center">
                <h5 class="m-1 d-inline-block text-nowrap font-weight-normal">
                    Data / Data Luas / Wilayah Kerja
                </h5>
            </div>
        </div>
        
		<b-card no-body>
            <b-card-body class="pb-2 pt-2">
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-sm-column flex-xl-row align-items-end">
                        <b-form-group label="Tahun" class="d-inline-block w-auto m-1">
                            <b-select size="md" v-model="currentYear" :options="yearOptions"/>
                        </b-form-group>
                    </div>
                </div>
			</b-card-body>
			<!-- / Table controls -->

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
					:busy="isBusy"
					class="card-table"
				>

					<template v-slot:cell(id)="data">
                        {{ data.index + 1 }}
                    </template>
                    
					<!-- Action Template -->
					<template v-slot:cell(actions)="data">
						<b-btn
							variant="primary btn-sm icon-btn md-btn-flat"
							v-b-tooltip.hover
							@click="goToDetail(data.index)"
						>
							<i class="ion ion-md-menu"></i>
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

			<b-card-body class="pt-0 pb-3">
				<div class="row">
					<div class="col-sm text-sm-left text-center">
						<span class="text-muted">
							Total Record : {{ data.total }}
						</span>
					</div>
				</div>
			</b-card-body>

		</b-card>
    </div>
</template>

<script>
export default {
    name: 'penyuluh',
	metaInfo: {
		title: 'Data Luas'
    },
    
    data: () => ({
		// Options
		sortBy: "id",
		sortDesc: false, 

		fields: [
			{ key: "id", label:"#", sortable: false,tdClass:"align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "kota", label:"Kota", sortable: false,tdClass:"align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "kecamatan", label:"Kecamatan", sortable: false,tdClass:"align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "desa", label:"Desa", sortable: false,tdClass:"align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "luas_tanam", label:"Luas Tanam [Ha]", sortable: false,tdClass:"align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "tambah_tanam", label:"Luas Tambah Tanam [Ha]", sortable: false,tdClass:"align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "produksi", label:"Produksi [Ton]", sortable: false,tdClass:"align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "luas_panen", label:"Luas Panen [Ha]", sortable: false,tdClass:"align-middle", thClass: "text-nowrap align-middle text-center" },
			{ key: "produktivitas", label:"Produktivitas [Kwintal/Ha]", sortable: false,tdClass:"align-middle", thClass: "text-nowrap align-middle text-center" },
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
        
        loading: false,
        
        currentYear: 'Semua'
    }),
	computed: {
		user(){
			return this.$store.state.auth.user;
        },
        yearOptions(){
            const min_year = 2016;
            const max_year = new Date().getFullYear();
            var year_option = [];
            year_option.push('Semua');
            for (let i = max_year; i >= min_year; i--) {
                year_option.push(i);
            }
            return year_option;
        }
	},
	watch: {
        currentYear() {
            this.loadData();
        },
    },
    methods:{
        goToDetail(i){
            const data = this.data.data[i]
            const param = {
                id: data.id
            }
            if (this.currentYear != "Semua") {
                param.tahun = this.currentYear
            }
            this.$router.push({ name: "dataLuasList", params: param });
        },
        loadData(){
			this.isBusy = true;
            this.loadParams.id = this.user.penyuluh.id
            this.loadParams.tahun = this.currentYear
            axios
				.get(this.publicUrl + "api/penyuluh/wilayahkerja", {
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
        }
    },
	created() {
        this.loadData();
	}
}
</script>