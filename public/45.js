(window.webpackJsonp=window.webpackJsonp||[]).push([[45],{"qU/F":function(a,t,i){"use strict";i.r(t);var s={name:"laporan-luas-tanam-detail",metaInfo:{title:"Laporan Luas Tanam"},data:function(){return{data:[],isBusy:!1,pilihLevel:"Semua",hasAccess:{},tahun:"",pilihJenis:"Pilih",pilihKomoditi:"Pilih",komoditiList:{},judul:"Provinsi Jawa Barat",pilihKota:"Pilih",kotaList:{},kecamatanList:{},pilihKecamatan:"Pilih",isKoordinatorKota:!1,isKoordinatorKec:!1,listBulan:{1:"Januari",2:"Februari",3:"Maret",4:"April",5:"Mei",6:"Juni",7:"Juli",8:"Agustus",9:"September",10:"Oktober",11:"November",12:"Desember"},field_nama:"Kota/Kabupaten",field_komoditi:"Semua Komoditi",bulan:"",jml_hari:28}},computed:{user:function(){return this.$store.state.auth.user},penyuluh:function(){var a=this.user;return!(!a||null==a.penyuluh)},tahunList:function(){for(var a=[],t=(new Date).getFullYear();t>=2016;t--)a.push(t);return a}},filters:{upper:function(a){return a?(a=a.toString()).toUpperCase():""},lower:function(a){return a?(a=a.toString()).toLowerCase():""},capital:function(a){if(!a)return"";for(var t=a.toString().split(" "),i=0;i<t.length;i++)t[i]=t[i][0].toUpperCase()+t[i].substr(1);return a=t.join(" ")}},watch:{tahun:function(){this.loadData()},bulan:function(){this.loadData()},pilihJenis:function(){this.loadData()},pilihKomoditi:function(a){if(this.field_komoditi="Semua Komoditi","Pilih"!=a){var t=this.komoditiList.find((function(t){return t.id===a}));this.field_komoditi="Komoditi "+t.nama}this.loadData()},pilihKota:function(a){if(this.pilihKecamatan="Pilih","Pilih"==a)this.kecamatanList=[],this.judul="Provinsi Jawa Barat",this.field_nama="Kota/Kabupaten";else{this.loadListKecamatan();var t=this.kotaList.find((function(t){return t.kota_id===a}));this.judul=t.name+", Provinsi Jawa Barat",this.field_nama="Kecamatan"}this.loadData()},pilihKecamatan:function(a){var t=this,i=this.kotaList.find((function(a){return a.kota_id===t.pilihKota}));if(null==i)this.judul="Provinsi Jawa Barat",this.field_nama="Kota/Kabupaten";else if("Pilih"==a)this.judul=i.name+", Provinsi Jawa Barat",this.field_nama="Kecamatan";else{var s=this.kecamatanList.find((function(t){return t.kecamatan_id===a}));this.judul="Kecamatan "+s.name+", "+i.name+", Provinsi Jawa Barat",this.field_nama="Kelurahan/Desa"}this.loadData()}},methods:{loadData:function(){var a=this;this.isBusy=!0;var t={};t.tahun=this.tahun,t.bulan=this.bulan,"Pilih"!=this.pilihJenis&&(t.jenis=this.pilihJenis),"Pilih"!=this.pilihKomoditi&&(t.komoditi_id=this.pilihKomoditi),"Pilih"!=this.pilihKota&&(t.kota_id=this.pilihKota),"Pilih"!=this.pilihKecamatan&&(t.kecamatan_id=this.pilihKecamatan),axios.get(this.publicUrl+"api/laporan/luas_tanam/detail",{params:t}).then((function(t){a.data=t.data,a.jml_hari=t.data.data.jml_hari,a.isBusy=!1})).catch((function(t){a.swr()}))},download:function(){var a=this,t={};t.tahun=this.tahun,t.bulan=this.bulan,"Pilih"!=this.pilihJenis&&(t.jenis=this.pilihJenis),"Pilih"!=this.pilihKomoditi&&(params.komoditi_id=this.pilihKomoditi),"Pilih"!=this.pilihKota&&(t.kota_id=this.pilihKota),"Pilih"!=this.pilihKecamatan&&(t.kecamatan_id=this.pilihKecamatan),t.user_id=this.user.id,this.notif("info","Success","Data sedang disiapkan, silakan cek secara berkala di menu download"),axios({method:"post",url:this.publicUrl+"api/laporan/luas_tanam/detail/download",data:t}).catch((function(t){if(422==t.response.status)for(var i in t.response.data.errors)a.notif("danger","Error",t.response.data.errors[i][0]);else 400==t.response.status?a.notif("warning","Error",t.response.data.message):a.swr()}))},loadListKota:function(){var a=this;axios.get(this.publicUrl+"api/kota").then((function(t){a.kotaList=t.data.data})).catch((function(t){a.swr()}))},loadListKecamatan:function(){var a=this;axios.get(this.publicUrl+"api/kecamatan",{params:{kota_id:this.pilihKota}}).then((function(t){a.kecamatanList=t.data.data})).catch((function(t){a.swr()}))},loadLevel:function(){var a=this;axios.get(this.publicUrl+"api/level").then((function(t){a.listLevel=t.data.data,a.loadAcl()}))},loadAcl:function(){var a=this;if(this.user)if(0!=this.user.level){this.hasAccess={laporan_luas_tanam_download:!1,laporan_luas_tanam_print:!1};var t=this.listLevel.find((function(t){return t.level===a.user.level}));t&&(t.rule.find((function(a){return"laporan_luas_tanam_download"===a.name}))&&(this.hasAccess.laporan_luas_tanam_download=t.rule.find((function(a){return"laporan_luas_tanam_download"===a.name})).active),t.rule.find((function(a){return"laporan_luas_tanam_print"===a.name}))&&(this.hasAccess.laporan_luas_tanam_print=t.rule.find((function(a){return"laporan_luas_tanam_print"===a.name})).active))}else this.hasAccess={laporan_luas_tanam_download:!0,laporan_luas_tanam_print:!0}},loadKorwil:function(){var a=this;if(this.user){var t=this.user;96==t.level&&axios.get(this.publicUrl+"api/korwil/byUser/"+t.id).then((function(t){a.isKorwil=t.data.data,a.isKoordinatorKota=!0,a.pilihKota=t.data.data.kota_id})).catch((function(t){a.swr()})),95==t.level&&axios.get(this.publicUrl+"api/korwil/byUser/"+t.id).then((function(t){a.isKorwil=t.data.data,a.isKoordinatorKota=!0,a.isKoordinatorKec=!0,a.pilihKota=t.data.data.kota_id,a.pilihKecamatan=t.data.data.kecamatan_id})).catch((function(t){a.swr()}))}},loadParam:function(){this.tahun=(new Date).getFullYear(),this.$route.params.tahun&&(this.tahun=this.$route.params.tahun),this.bulan=(new Date).getMonth(),this.$route.params.bulan&&(this.bulan=this.$route.params.bulan),this.$route.params.jenis&&(this.pilihJenis=this.$route.params.jenis),this.$route.params.kota_id&&(this.pilihKota=this.$route.params.kota_id),this.$route.params.kecamatan_id&&(this.pilihKecamatan=this.$route.params.kecamatan_id)},loadKomoditi:function(){var a=this;axios.get(this.publicUrl+"api/komoditas").then((function(t){a.komoditiList=t.data.data})).catch((function(t){a.swr()}))}},created:function(){this.loadListKota(),this.loadParam(),this.loadLevel(),this.loadKorwil(),this.loadData(),this.loadKomoditi()}},n=i("KHd+"),o=Object(n.a)(s,(function(){var a=this,t=a.$createElement,i=a._self._c||t;return i("div",[a._m(0),a._v(" "),i("b-card",{attrs:{"no-body":""}},[i("div",{staticClass:"text-center"},[i("h4",{staticClass:"mt-3 mb-1"},[i("b",[a._v("Laporan Luas Tanam "+a._s(a.listBulan[a.bulan])+" "+a._s(a.tahun))])]),a._v(" "),i("h5",{staticClass:"mb-1"},[a._v(a._s(a.field_komoditi)+" - "+a._s("Pilih"==a.pilihJenis?"Sawah dan Ladang":1==a.pilihJenis?"Sawah":"Ladang"))]),a._v(" "),i("h4",[a._v(a._s(a._f("capital")(a._f("lower")(a.judul))))])]),a._v(" "),i("hr",{staticClass:"border-light m-0"}),a._v(" "),i("div",{staticClass:"table-responsive"},[a.isBusy?[i("div",{staticClass:"text-center text-danger my-2"},[i("b-spinner",{staticClass:"align-middle"}),a._v(" "),i("strong",[a._v("Loading...")])],1)]:[i("table",{staticClass:"table table-striped table-bordered"},[i("thead",{staticClass:"text-center"},[i("tr",[i("th",{staticClass:"align-middle",attrs:{rowspan:"2"}},[a._v("#")]),a._v(" "),i("th",{staticClass:"align-middle",attrs:{rowspan:"2"}},[a._v(a._s(a.field_nama))]),a._v(" "),i("th",{attrs:{colspan:a.jml_hari}},[a._v("Tanggal [Ha]")]),a._v(" "),i("th",{staticClass:"align-middle",attrs:{rowspan:"2"}},[a._v("Jumlah [Ha]")])]),a._v(" "),i("tr",a._l(a.jml_hari,(function(t,s){return i("th",{key:s},[a._v("\n                                    "+a._s(t)+"\n                                ")])})),0)]),a._v(" "),i("tbody",a._l(a.data.data.data,(function(t,s){return i("tr",{key:s},[i("td",[a._v(a._s(s+1))]),a._v(" "),i("td",[a._v(a._s(t.nama))]),a._v(" "),a._l(t.hari,(function(t,s){return i("td",{key:s,staticClass:"text-right"},[a._v("\n                                    "+a._s(t)+"\n                                ")])})),a._v(" "),i("td",{staticClass:"text-right"},[a._v(a._s(t.jumlah))])],2)})),0),a._v(" "),i("tfoot",[i("tr",{staticClass:"text-right"},[i("th",{attrs:{colspan:"2"}},[a._v("Jumlah [Ha]")]),a._v(" "),a._l(a.data.jumlah.data,(function(t,s){return i("th",{key:s},[a._v("\n                                    "+a._s(t)+"\n                                ")])})),a._v(" "),i("th",[a._v(a._s(a.data.jumlah.total))])],2)])])]],2)]),a._v(" "),i("b-card",{staticClass:"px-3 py-2 mt-2",attrs:{"no-body":""}},[i("div",{staticClass:"row"},[i("div",{staticClass:"col-md-1 px-0"},[i("b-form-group",{staticClass:"w-auto m-1",attrs:{label:"Tahun"}},[i("b-select",{attrs:{size:"md",options:a.tahunList},model:{value:a.tahun,callback:function(t){a.tahun=t},expression:"tahun"}})],1)],1),a._v(" "),i("div",{staticClass:"col-md-1 px-0"},[i("b-form-group",{staticClass:"w-auto m-1",attrs:{label:"Bulan"}},[i("b-select",{attrs:{size:"md",options:a.listBulan},model:{value:a.bulan,callback:function(t){a.bulan=t},expression:"bulan"}})],1)],1),a._v(" "),i("div",{staticClass:"col-md-1 px-0"},[i("b-form-group",{staticClass:"w-auto m-1",attrs:{label:"Jenis"}},[i("b-select",{attrs:{size:"md"},model:{value:a.pilihJenis,callback:function(t){a.pilihJenis=t},expression:"pilihJenis"}},[i("option",{attrs:{value:"Pilih"}},[a._v("Pilih")]),a._v(" "),i("option",{attrs:{value:"1"}},[a._v("Sawah")]),a._v(" "),i("option",{attrs:{value:"2"}},[a._v("Ladang")])])],1)],1),a._v(" "),i("div",{staticClass:"col-md-2 px-0"},[i("b-form-group",{staticClass:"w-auto m-1",attrs:{label:"Komoditi"}},[i("b-select",{attrs:{size:"md"},model:{value:a.pilihKomoditi,callback:function(t){a.pilihKomoditi=t},expression:"pilihKomoditi"}},[i("option",{attrs:{value:"Pilih"}},[a._v("Pilih")]),a._v(" "),a._l(a.komoditiList,(function(t,s){return i("option",{key:s,domProps:{value:t.id}},[a._v("\n                                "+a._s(t.nama)+"\n                            ")])}))],2)],1)],1),a._v(" "),i("div",{staticClass:"col-md-2 px-0"},[i("b-form-group",{staticClass:"w-auto m-1",attrs:{label:"Kota"}},[i("b-select",{attrs:{size:"md",disabled:a.isKoordinatorKota},model:{value:a.pilihKota,callback:function(t){a.pilihKota=t},expression:"pilihKota"}},[i("option",{attrs:{value:"Pilih"}},[a._v("Pilih")]),a._v(" "),a._l(a.kotaList,(function(t,s){return i("option",{key:s,domProps:{value:t.kota_id}},[a._v("\n                                "+a._s(t.name)+"\n                            ")])}))],2)],1)],1),a._v(" "),i("div",{staticClass:"col-md-2 px-0"},[i("b-form-group",{staticClass:"w-auto m-1",attrs:{label:"Kecamatan"}},[i("b-select",{attrs:{size:"md",disabled:a.isKoordinatorKec},model:{value:a.pilihKecamatan,callback:function(t){a.pilihKecamatan=t},expression:"pilihKecamatan"}},[i("option",{attrs:{value:"Pilih"}},[a._v("Pilih")]),a._v(" "),a._l(a.kecamatanList,(function(t,s){return i("option",{key:s,domProps:{value:t.kecamatan_id}},[a._v("\n                                "+a._s(t.name)+"\n                            ")])}))],2)],1)],1),a._v(" "),i("div",{staticClass:"col-md-3 px-0 text-right"},[a.hasAccess.laporan_luas_tanam_download?i("b-btn",{directives:[{name:"b-tooltip",rawName:"v-b-tooltip.hover",modifiers:{hover:!0}}],staticClass:"mt-3",attrs:{variant:"success",title:"Download Data"},on:{click:function(t){return a.download()}}},[i("i",{staticClass:"ion ion-md-download"}),a._v(" Download\n                    ")]):a._e()],1)])])],1)}),[function(){var a=this.$createElement,t=this._self._c||a;return t("div",{staticClass:"d-flex justify-content-between align-items-center w-100 mb-0 border-bottom"},[t("div",{staticClass:"d-flex align-items-center"},[t("h5",{staticClass:"m-1 d-inline-block text-nowrap font-weight-normal"},[this._v("\n                    Laporan / Luas Tanam / Detail\n                ")])])])}],!1,null,null,null);t.default=o.exports}}]);