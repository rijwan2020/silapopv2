<template>  
    <div>
		<div class="d-flex justify-content-between align-items-center w-100 mb-0 border-bottom">
            <div class="d-flex align-items-center">
                <h5 class="m-1 d-inline-block text-nowrap font-weight-normal">
                    Download
                </h5>
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
                        {{ data.item.id!=0?(data.index + 1 + ((currentPage-1) * perPage)):'-'}}
                    </template>

					<template v-slot:cell(status)="data">
						<b-badge variant="outline-info" v-if="data.item.status==1">
                            Selesai
                        </b-badge>
                        <b-badge variant="outline-success" v-else>
                            Proses
                        </b-badge>
                    </template>

					<template v-slot:cell(link)="data">
                        <a :href="data.item.link" class="btn btn-sm btn-info" v-if="data.item.status==1"><i class="ion ion-md-download"></i> Download</a>
                        <i v-else>Belum tersedia</i>
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
                <!-- Limit -->
                <div class="col-md-1 px-0">
                    <b-form-group label="Limit" class="w-auto m-1">
                        <b-select size="md" v-model="perPage" :options="[10, 20, 30, 40, 50]"/>
                    </b-form-group>
                </div>

                <!-- Status -->
                <div class="col-md-2 px-0">
                    <b-form-group label="Status" class="w-auto m-1">
                        <b-select size="md" v-model="pilihStatus">
                            <option value="Semua">Semua</option>
                            <option value="1">Selesai</option>
                            <option value="0">Proses</option>
                        </b-select>
                    </b-form-group>
                </div>
            </div>
        </b-card>
		
    </div>
</template>

<script>
export default {
	name: 'data-user',
	metaInfo: {
		title: 'Data User'
    },
    data: () => ({
		// Options
		sortBy: "id",
		sortDesc: true,
		perPage: 10,
		loadParams: {},
		data: [],
		currentPage: 1,
		isBusy: false,
		pilihStatus: "Semua",
        fields: [
            { 
				key: "id", 
				label:"#", 
				sortable: false, 
				tdClass: "align-middle", 
				thClass: "text-nowrap align-middle text-center" 
            },
            { 
				key: "keterangan", 
				label:"Keterangan", 
				sortable: false, 
				tdClass: "align-middle", 
				thClass: "text-nowrap align-middle text-center" 
			},
            { 
				key: "date", 
				label:"Tanggal", 
				sortable: false, 
				tdClass: "align-middle", 
				thClass: "text-nowrap align-middle text-center" 
			},
            { 
				key: "status", 
				label:"Status", 
				sortable: false, 
				tdClass: "align-middle", 
				thClass: "text-nowrap align-middle text-center" 
            },
            { 
				key: "link", 
				label:"File", 
				sortable: false, 
				tdClass: "align-middle text-center", 
				thClass: "text-nowrap align-middle text-center" 
            },
        ]
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
	},
	watch: {
        currentPage() {
            this.loadData();
        },
        perPage() {
            this.loadData();
		},
        pilihStatus() {
            this.loadData();
		},
    },
    methods:{
        loadData(){
			this.isBusy = true;

			this.loadParams.page = this.currentPage;
			this.loadParams.order_by = this.sortBy;
			this.loadParams.sort_desc = this.sortDesc;
			this.loadParams.limit = this.perPage;
			this.loadParams.params = {};
			if (this.pilihStatus != "Semua") {
				this.loadParams.params.status = this.pilihStatus
            }
            this.loadParams.params.user_id = this.user.id

			axios
				.get(this.publicUrl + "api/download", {
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

    },
	created() {
		this.loadData();
	}
}
</script>