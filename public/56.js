(window.webpackJsonp=window.webpackJsonp||[]).push([[56],{bN0o:function(t,a,e){"use strict";e.r(a);var n={name:"laporan-rekap-input-penyuluh",metaInfo:{title:"Laporan Rekap Input Penyuluh"},data:function(){return{sortBy:"id",sortDesc:!1,perPage:10,filterJenisPenyuluh:"Pilih",data:[],currentPage:1,isBusy:!1,q:"",tanggal:"",filterKota:"Pilih",filterKecamatan:"Pilih",filterKelurahan:"Pilih",kotaList:[],kecamatanList:[],kelurahanList:[],listLevel:{},hasAccess:{},fields:[{key:"id",label:"#",sortable:!1,tdClass:"align-middle",thClass:"align-middle text-center"},{key:"nama",label:"Nama",sortable:!1,tdClass:"align-middle",thClass:"align-middle text-center"},{key:"jp",label:"Jenis Penyuluh",sortable:!1,tdClass:"align-middle",thClass:"align-middle text-center"},{key:"no_hp",label:"No Telepon",sortable:!1,tdClass:"align-middle",thClass:"align-middle text-center"},{key:"tempat_tugas",label:"Tempat Tugas",sortable:!1,tdClass:"align-middle",thClass:"align-middle text-center"},{key:"bakulahan",label:"Baku Lahan",sortable:!1,tdClass:"align-middle",thClass:"align-middle text-center"},{key:"rencana_tanam",label:"Rencana Tanam",sortable:!1,tdClass:"align-middle",thClass:"align-middle text-center"},{key:"realisasi_tanam",label:"Realisasi Tanam",sortable:!1,tdClass:"align-middle",thClass:"align-middle text-center"},{key:"luas_tanam",label:"Luas Tanam",sortable:!1,tdClass:"align-middle",thClass:"align-middle text-center"},{key:"tambah_tanam",label:"Tambah Tanam",sortable:!1,tdClass:"align-middle",thClass:"align-middle text-center"},{key:"luas_panen",label:"Luas Panen",sortable:!1,tdClass:"align-middle",thClass:"align-middle text-center"},{key:"produksi",label:"Produksi",sortable:!1,tdClass:"align-middle",thClass:"align-middle text-center"},{key:"htp",label:"Htp",sortable:!1,tdClass:"align-middle",thClass:"align-middle text-center"},{key:"opsin",label:"Opsin",sortable:!1,tdClass:"align-middle",thClass:"align-middle text-center"},{key:"ltt_ltp",label:"Ltt/Ltp",sortable:!1,tdClass:"align-middle",thClass:"align-middle text-center"},{key:"alsin",label:"Alsin",sortable:!1,tdClass:"align-middle",thClass:"align-middle text-center"}]}},filters:{formatTanggal:function(t){if(!t)return"";return(t=new Date(t)).getDate()+" "+["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"][t.getMonth()]+" "+t.getFullYear()}},computed:{totalItems:function(){return this.data.total},totalPages:function(){return Math.ceil(this.totalItems/this.perPage)},user:function(){return this.$store.state.auth.user},penyuluh:function(){var t=this.user;return!(!t||null==t.penyuluh)},currentDate:function(){return(new Date).toJSON().slice(0,10).replace(/-/g,"-")}},watch:{currentPage:function(){this.loadData()},perPage:function(){this.loadData()},filterJenisPenyuluh:function(){this.loadData()},q:function(t){""==t&&this.loadData()},tanggal:function(){this.loadData()},filterKota:function(t){"Pilih"==t?(this.kecamatanList=[],this.filterKecamatan="Pilih",this.kelurahanList=[],this.filterKelurahan="Pilih"):this.loadListKecamatan(),this.loadData()},filterKecamatan:function(t){"Pilih"==t?(this.kelurahanList=[],this.filterKelurahan="Pilih"):this.loadListKelurahan(),this.loadData()},filterKelurahan:function(){this.loadData()}},methods:{loadData:function(){var t=this;this.isBusy=!0;var a={};a.page=this.currentPage,a.order_by=this.sortBy,a.sort_desc=this.sortDesc,a.limit=this.perPage,""!=this.q&&(a.q=this.q),a.tanggal=this.tanggal,a.params={},"Pilih"!=this.filterJenisPenyuluh&&(a.params.jp=this.filterJenisPenyuluh),"Pilih"!=this.filterKota&&(a.params.tempat_tugas_kota_id=this.filterKota,"Pilih"!=this.filterKecamatan&&(a.params.tempat_tugas_kecamatan_id=this.filterKecamatan,"Pilih"!=this.filterKelurahan&&(a.params.tempat_tugas_kelurahan_id=this.filterKelurahan))),axios.get(this.publicUrl+"api/laporan/rekap_input_penyuluh",{params:a}).then((function(a){t.data=a.data,t.isBusy=!1})).catch((function(a){t.swr()}))},download:function(){var t=this,a={};""!=this.q&&(a.q=this.q),a.tanggal=this.tanggal,a.params={},"Pilih"!=this.filterJenisPenyuluh&&(a.params.jp=this.filterJenisPenyuluh),"Pilih"!=this.filterKota&&(a.params.tempat_tugas_kota_id=this.filterKota,"Pilih"!=this.filterKecamatan&&(a.params.tempat_tugas_kecamatan_id=this.filterKecamatan,"Pilih"!=this.filterKelurahan&&(a.params.tempat_tugas_kelurahan_id=this.filterKelurahan))),a.user_id=this.user.id,this.notif("info","Success","Data sedang disiapkan, silakan cek secara berkala di menu download"),axios({method:"post",url:this.publicUrl+"api/laporan/rekap_input_penyuluh/download",data:a}).catch((function(a){if(422==a.response.status)for(var e in a.response.data.errors)t.notif("danger","Error",a.response.data.errors[e][0]);else 400==a.response.status?t.notif("warning","Error",a.response.data.message):t.swr()}))},loadLevel:function(){var t=this;axios.get(this.publicUrl+"api/level").then((function(a){t.listLevel=a.data.data,t.loadAcl()}))},loadAcl:function(){var t=this;if(this.user)if(0!=this.user.level){this.hasAccess={laporan_rekap_input_penyuluh_download:!1};var a=this.listLevel.find((function(a){return a.level===t.user.level}));a.rule.find((function(t){return"laporan_rekap_input_penyuluh_download"===t.name}))&&(this.hasAccess.laporan_rekap_input_penyuluh_download=a.rule.find((function(t){return"laporan_rekap_input_penyuluh_download"===t.name})).active)}else this.hasAccess={laporan_rekap_input_penyuluh_download:!0}},loadListKota:function(){var t=this;axios.get(this.publicUrl+"api/kota").then((function(a){t.kotaList=a.data.data})).catch((function(a){t.swr()}))},loadListKecamatan:function(){var t=this;axios.get(this.publicUrl+"api/kecamatan",{params:{kota_id:this.filterKota}}).then((function(a){t.kecamatanList=a.data.data})).catch((function(a){t.swr()}))},loadListKelurahan:function(){var t=this;axios.get(this.publicUrl+"api/desa",{params:{kota_id:this.filterKota,kecamatan_id:this.filterKecamatan}}).then((function(a){t.kelurahanList=a.data.data})).catch((function(a){t.swr()}))}},created:function(){this.tanggal=(new Date).toJSON().slice(0,10).replace(/-/g,"-"),this.loadLevel(),this.loadData(),this.loadListKota()}},s=e("KHd+"),i=Object(s.a)(n,(function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",[t._m(0),t._v(" "),e("b-card",{attrs:{"no-body":""}},[e("div",{staticClass:"text-center"},[e("h4",{staticClass:"mt-3 mb-1"},[e("b",[t._v("Laporan Rekap Input Penyuluh")])]),t._v(" "),e("h5",[t._v(t._s(t._f("formatTanggal")(t.tanggal)))])]),t._v(" "),e("hr",{staticClass:"border-light m-0"}),t._v(" "),e("div",{staticClass:"table-responsive"},[e("b-table",{staticClass:"card-table",attrs:{items:t.data.data,fields:t.fields,"sort-by":t.sortBy,"sort-desc":t.sortDesc,striped:!0,bordered:!0,"current-page":1,"per-page":t.perPage,busy:t.isBusy},on:{"update:sortBy":function(a){t.sortBy=a},"update:sort-by":function(a){t.sortBy=a},"update:sortDesc":function(a){t.sortDesc=a},"update:sort-desc":function(a){t.sortDesc=a}},scopedSlots:t._u([{key:"cell(id)",fn:function(a){return[t._v("\n                        "+t._s(0!=a.item.id?a.index+1+(t.currentPage-1)*t.perPage:"-")+"\n                    ")]}},{key:"cell(jp)",fn:function(a){return[1==a.item.jp?e("b-badge",{attrs:{variant:"outline-success"}},[t._v("\n                            POPT\n                        ")]):e("b-badge",{attrs:{variant:"outline-info"}},[t._v("\n                            PPL\n                        ")])]}},{key:"cell(bakulahan)",fn:function(a){return[a.item.bakulahan?e("b-badge",{attrs:{variant:"success"}},[t._v("\n                            Sudah\n                        ")]):e("b-badge",{attrs:{variant:"danger"}},[t._v("\n                            Belum\n                        ")])]}},{key:"cell(rencana_tanam)",fn:function(a){return[a.item.rencana_tanam?e("b-badge",{attrs:{variant:"success"}},[t._v("\n                            Sudah\n                        ")]):e("b-badge",{attrs:{variant:"danger"}},[t._v("\n                            Belum\n                        ")])]}},{key:"cell(realisasi_tanam)",fn:function(a){return[a.item.realisasi_tanam?e("b-badge",{attrs:{variant:"success"}},[t._v("\n                            Sudah\n                        ")]):e("b-badge",{attrs:{variant:"danger"}},[t._v("\n                            Belum\n                        ")])]}},{key:"cell(luas_tanam)",fn:function(a){return[a.item.luas_tanam?e("b-badge",{attrs:{variant:"success"}},[t._v("\n                            Sudah\n                        ")]):e("b-badge",{attrs:{variant:"danger"}},[t._v("\n                            Belum\n                        ")])]}},{key:"cell(tambah_tanam)",fn:function(a){return[a.item.tambah_tanam?e("b-badge",{attrs:{variant:"success"}},[t._v("\n                            Sudah\n                        ")]):e("b-badge",{attrs:{variant:"danger"}},[t._v("\n                            Belum\n                        ")])]}},{key:"cell(luas_panen)",fn:function(a){return[a.item.luas_panen?e("b-badge",{attrs:{variant:"success"}},[t._v("\n                            Sudah\n                        ")]):e("b-badge",{attrs:{variant:"danger"}},[t._v("\n                            Belum\n                        ")])]}},{key:"cell(produksi)",fn:function(a){return[a.item.produksi?e("b-badge",{attrs:{variant:"success"}},[t._v("\n                            Sudah\n                        ")]):e("b-badge",{attrs:{variant:"danger"}},[t._v("\n                            Belum\n                        ")])]}},{key:"cell(htp)",fn:function(a){return[a.item.htp?e("b-badge",{attrs:{variant:"success"}},[t._v("\n                            Sudah\n                        ")]):e("b-badge",{attrs:{variant:"danger"}},[t._v("\n                            Belum\n                        ")])]}},{key:"cell(opsin)",fn:function(a){return[a.item.opsin?e("b-badge",{attrs:{variant:"success"}},[t._v("\n                            Sudah\n                        ")]):e("b-badge",{attrs:{variant:"danger"}},[t._v("\n                            Belum\n                        ")])]}},{key:"cell(ltt_ltp)",fn:function(a){return[a.item.ltt_ltp?e("b-badge",{attrs:{variant:"success"}},[t._v("\n                            Sudah\n                        ")]):e("b-badge",{attrs:{variant:"danger"}},[t._v("\n                            Belum\n                        ")])]}},{key:"cell(alsin)",fn:function(a){return[a.item.alsin?e("b-badge",{attrs:{variant:"success"}},[t._v("\n                            Sudah\n                        ")]):e("b-badge",{attrs:{variant:"danger"}},[t._v("\n                            Belum\n                        ")])]}},{key:"table-busy",fn:function(){return[e("div",{staticClass:"text-center text-danger my-2"},[e("b-spinner",{staticClass:"align-middle"}),t._v(" "),e("strong",[t._v("Loading...")])],1)]},proxy:!0}])})],1),t._v(" "),e("b-card-body",{staticClass:"pt-0 pb-3"},[e("div",{staticClass:"row"},[e("div",{staticClass:"col-sm text-sm-left text-center pt-3"},[t.totalItems?e("span",{staticClass:"text-muted"},[t._v("Page "+t._s(t.currentPage)+" of "+t._s(t.totalPages)+"\n\t\t\t\t\t\t\t"),e("br"),t._v("\n\t\t\t\t\t\t\tTotal Record : "+t._s(t.totalItems)+"\n\t\t\t\t\t\t")]):t._e()]),t._v(" "),e("div",{staticClass:"col-sm pt-3"},[t.totalItems?e("b-pagination",{staticClass:"justify-content-center justify-content-sm-end m-0",attrs:{"total-rows":t.totalItems,"per-page":t.perPage,size:"md"},model:{value:t.currentPage,callback:function(a){t.currentPage=a},expression:"currentPage"}}):t._e()],1)])])],1),t._v(" "),e("b-card",{staticClass:"px-3 py-2 mt-2",attrs:{"no-body":""}},[e("div",{staticClass:"row"},[e("div",{staticClass:"col-md-4 px-0"},[e("b-form-group",{staticClass:"w-auto m-1",attrs:{label:"Kota"}},[e("b-select",{attrs:{size:"md"},model:{value:t.filterKota,callback:function(a){t.filterKota=a},expression:"filterKota"}},[e("option",{attrs:{value:"Pilih"}},[t._v("Pilih")]),t._v(" "),t._l(t.kotaList,(function(a,n){return e("option",{key:n,domProps:{value:a.kota_id}},[t._v("\n\t\t\t\t\t\t\t\t"+t._s(a.name)+"\n\t\t\t\t\t\t\t")])}))],2)],1)],1),t._v(" "),e("div",{staticClass:"col-md-4 px-0"},[e("b-form-group",{staticClass:"w-auto m-1",attrs:{label:"Kecamatan"}},[e("b-select",{attrs:{size:"md"},model:{value:t.filterKecamatan,callback:function(a){t.filterKecamatan=a},expression:"filterKecamatan"}},[e("option",{attrs:{value:"Pilih"}},[t._v("Pilih")]),t._v(" "),t._l(t.kecamatanList,(function(a,n){return e("option",{key:n,domProps:{value:a.kecamatan_id}},[t._v("\n\t\t\t\t\t\t\t\t"+t._s(a.name)+"\n\t\t\t\t\t\t\t")])}))],2)],1)],1),t._v(" "),e("div",{staticClass:"col-md-4 px-0"},[e("b-form-group",{staticClass:"w-auto m-1",attrs:{label:"Desa"}},[e("b-select",{attrs:{size:"md"},model:{value:t.filterKelurahan,callback:function(a){t.filterKelurahan=a},expression:"filterKelurahan"}},[e("option",{attrs:{value:"Pilih"}},[t._v("Pilih")]),t._v(" "),t._l(t.kelurahanList,(function(a,n){return e("option",{key:n,domProps:{value:a.kelurahan_id}},[t._v("\n\t\t\t\t\t\t\t\t"+t._s(a.name)+"\n\t\t\t\t\t\t\t")])}))],2)],1)],1),t._v(" "),e("div",{staticClass:"col-md-1 px-0"},[e("b-form-group",{staticClass:"w-auto m-1",attrs:{label:"Limit"}},[e("b-select",{attrs:{size:"md",options:[10,20,30,40,50]},model:{value:t.perPage,callback:function(a){t.perPage=a},expression:"perPage"}})],1)],1),t._v(" "),e("div",{staticClass:"col-md-2 px-0"},[e("b-form-group",{staticClass:"w-auto m-1",attrs:{label:"Jenis Penyuluh"}},[e("b-select",{attrs:{size:"md",options:[{text:"Pilih",value:"Pilih"},{text:"POPT",value:1},{text:"PPL",value:0}]},model:{value:t.filterJenisPenyuluh,callback:function(a){t.filterJenisPenyuluh=a},expression:"filterJenisPenyuluh"}})],1)],1),t._v(" "),e("div",{staticClass:"col-md-3 px-0"},[e("b-form-group",{staticClass:"w-auto m-1",attrs:{label:"Tanggal"}},[e("b-datepicker",{attrs:{size:"md",placeholder:"Pilih Tanggal",locale:"id",max:t.currentDate,min:"2016-01-01"},model:{value:t.tanggal,callback:function(a){t.tanggal=a},expression:"tanggal"}})],1)],1),t._v(" "),e("div",{staticClass:"col-md-3 px-0"},[e("b-form-group",{staticClass:"w-auto m-1",attrs:{label:"Pencarian"}},[e("b-input-group",[e("b-input",{attrs:{placeholder:"Pencarian"},model:{value:t.q,callback:function(a){t.q=a},expression:"q"}}),t._v(" "),e("b-btn",{attrs:{variant:"success"},on:{click:function(a){return t.loadData()}}},[e("i",{staticClass:"ion ion-md-search"})])],1)],1)],1),t._v(" "),e("div",{staticClass:"col-md-3 px-0 text-right"},[t.hasAccess.laporan_rekap_input_penyuluh_download?e("b-btn",{directives:[{name:"b-tooltip",rawName:"v-b-tooltip.hover",modifiers:{hover:!0}}],staticClass:"mt-3",attrs:{variant:"success",title:"Download Data"},on:{click:function(a){return t.download()}}},[e("i",{staticClass:"ion ion-md-download"}),t._v(" Download\n                    ")]):t._e()],1)])])],1)}),[function(){var t=this.$createElement,a=this._self._c||t;return a("div",{staticClass:"d-flex justify-content-between align-items-center w-100 mb-0 border-bottom"},[a("div",{staticClass:"d-flex align-items-center"},[a("h5",{staticClass:"m-1 d-inline-block text-nowrap font-weight-normal"},[this._v("\n                    Laporan / Rekap Input Penyuluh\n                ")])])])}],!1,null,null,null);a.default=i.exports}}]);