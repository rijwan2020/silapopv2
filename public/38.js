(window.webpackJsonp=window.webpackJsonp||[]).push([[38],{"/ngy":function(a,t,i){"use strict";i.r(t);var s={name:"laporan-htp",metaInfo:{title:"Laporan Harga Tingkat Petani"},data:function(){return{data:[],isBusy:!1,pilihLevel:"Semua",hasAccess:{},tahun:"",pilihKategori:"Pilih",judul:"Provinsi Jawa Barat",pilihKota:"Pilih",kotaList:{},pilihKecamatan:"Pilih",kecamatanList:{},pilihDesa:"Pilih",desaList:{},isKoordinatorKota:!1,isKoordinatorKec:!1,listBulan:["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"],field_nama:"Kota/Kabupaten"}},computed:{user:function(){return this.$store.state.auth.user},penyuluh:function(){var a=this.user;return!(!a||null==a.penyuluh)},tahunList:function(){for(var a=[],t=(new Date).getFullYear();t>=2016;t--)a.push(t);return a}},filters:{upper:function(a){return a?(a=a.toString()).toUpperCase():""},lower:function(a){return a?(a=a.toString()).toLowerCase():""},capital:function(a){if(!a)return"";for(var t=a.toString().split(" "),i=0;i<t.length;i++)t[i]=t[i][0].toUpperCase()+t[i].substr(1);return a=t.join(" ")}},watch:{tahun:function(){this.loadData()},pilihKategori:function(){this.loadData()},pilihKota:function(a){if(this.pilihKecamatan="Pilih","Pilih"==a)this.kecamatanList=[],this.judul="Provinsi Jawa Barat";else{this.loadListKecamatan();var t=this.kotaList.find((function(t){return t.kota_id===a}));this.judul=t.name+", Provinsi Jawa Barat"}this.loadData()},pilihKecamatan:function(a){var t=this,i=this.kotaList.find((function(a){return a.kota_id===t.pilihKota}));if(null==i)this.judul="Provinsi Jawa Barat";else if(this.pilihDesa="Pilih","Pilih"==a)this.desaList=[],this.judul=i.name+", Provinsi Jawa Barat";else{this.loadListDesa();var s=this.kecamatanList.find((function(t){return t.kecamatan_id===a}));this.judul="Kecamatan "+s.name+", "+i.name+", Provinsi Jawa Barat"}this.loadData()},pilihDesa:function(a){var t=this,i=this.kotaList.find((function(a){return a.kota_id===t.pilihKota}));if(null==i)this.judul="Provinsi Jawa Barat";else{var s=this.kecamatanList.find((function(a){return a.kecamatan_id===t.pilihKecamatan}));if(null==s)this.judul=i.name+", Provinsi Jawa Barat";else if("Pilih"==a)this.judul="Kecamatan "+s.name+", "+i.name+", Provinsi Jawa Barat";else{var n=this.desaList.find((function(t){return t.kelurahan_id===a}));this.judul="Desa "+n.name+", Kecamatan "+s.name+", "+i.name+", Provinsi Jawa Barat"}}this.loadData()}},methods:{loadData:function(){var a=this;this.isBusy=!0;var t={};t.tahun=this.tahun,t.params={},"Pilih"!=this.pilihKategori&&(t.params.kategori_id=this.pilihKategori),"Pilih"!=this.pilihKota&&(t.kota_id=this.pilihKota,"Pilih"!=this.pilihKecamatan&&(t.kecamatan_id=this.pilihKecamatan,"Pilih"!=this.pilihDesa&&(t.kelurahan_id=this.pilihDesa))),axios.get(this.publicUrl+"api/laporan/htp",{params:t}).then((function(t){a.data=t.data,a.isBusy=!1})).catch((function(t){a.swr()}))},toDetail:function(a){var t={};t.tahun=this.tahun,t.bulan=a,"Pilih"!=this.pilihKategori&&(t.kategori_id=this.pilihKategori),"Pilih"!=this.pilihKota&&(t.kota_id=this.pilihKota,"Pilih"!=this.pilihKecamatan&&(t.kecamatan_id=this.pilihKecamatan,"Pilih"!=this.pilihDesa&&(t.kelurahan_id=this.pilihDesa))),this.$router.push({name:"laporan_htp_detail",params:t})},download:function(){var a=this,t={};t.tahun=this.tahun,t.params={},"Pilih"!=this.pilihKategori&&(t.params.kategori_id=this.pilihKategori),"Pilih"!=this.pilihKota&&(t.kota_id=this.pilihKota,"Pilih"!=this.pilihKecamatan&&(t.kecamatan_id=this.pilihKecamatan,"Pilih"!=this.pilihDesa&&(t.kelurahan_id=this.pilihDesa))),t.user_id=this.user.id,this.notif("info","Success","Data sedang disiapkan, silakan cek secara berkala di menu download"),axios({method:"post",url:this.publicUrl+"api/laporan/htp/download",data:t}).catch((function(t){if(422==t.response.status)for(var i in t.response.data.errors)a.notif("danger","Error",t.response.data.errors[i][0]);else 400==t.response.status?a.notif("warning","Error",t.response.data.message):a.swr()}))},loadListKota:function(){var a=this;axios.get(this.publicUrl+"api/kota").then((function(t){a.kotaList=t.data.data})).catch((function(t){a.swr()}))},loadListKecamatan:function(){var a=this;axios.get(this.publicUrl+"api/kecamatan",{params:{kota_id:this.pilihKota}}).then((function(t){a.kecamatanList=t.data.data})).catch((function(t){a.swr()}))},loadListDesa:function(){var a=this;axios.get(this.publicUrl+"api/desa",{params:{kota_id:this.pilihKota,kecamatan_id:this.pilihKecamatan}}).then((function(t){a.desaList=t.data.data})).catch((function(t){a.swr()}))},loadLevel:function(){var a=this;axios.get(this.publicUrl+"api/level").then((function(t){a.listLevel=t.data.data,a.loadAcl()}))},loadAcl:function(){var a=this;if(this.user)if(0!=this.user.level){this.hasAccess={laporan_htp_download:!1,laporan_htp_print:!1};var t=this.listLevel.find((function(t){return t.level===a.user.level}));t&&(t.rule.find((function(a){return"laporan_htp_download"===a.name}))&&(this.hasAccess.laporan_htp_download=t.rule.find((function(a){return"laporan_htp_download"===a.name})).active),t.rule.find((function(a){return"laporan_htp_print"===a.name}))&&(this.hasAccess.laporan_htp_print=t.rule.find((function(a){return"laporan_htp_print"===a.name})).active))}else this.hasAccess={laporan_htp_download:!0,laporan_htp_print:!0}},loadKorwil:function(){var a=this;if(this.user){var t=this.user;96==t.level&&axios.get(this.publicUrl+"api/korwil/byUser/"+t.id).then((function(t){a.isKorwil=t.data.data,a.isKoordinatorKota=!0,a.pilihKota=t.data.data.kota_id})).catch((function(t){a.swr()})),95==t.level&&axios.get(this.publicUrl+"api/korwil/byUser/"+t.id).then((function(t){a.isKorwil=t.data.data,a.isKoordinatorKota=!0,a.isKoordinatorKec=!0,a.pilihKota=t.data.data.kota_id,a.pilihKecamatan=t.data.data.kecamatan_id})).catch((function(t){a.swr()}))}}},created:function(){this.tahun=(new Date).getFullYear(),this.loadData(),this.loadLevel(),this.loadListKota(),this.loadKorwil()}},n=i("KHd+"),e=Object(n.a)(s,(function(){var a=this,t=a.$createElement,i=a._self._c||t;return i("div",[i("div",{staticClass:"d-flex justify-content-between align-items-center w-100 mb-0 border-bottom"},[a._m(0),a._v(" "),i("div",{staticClass:"m-1"},[i("router-link",{staticClass:"btn btn-sm btn-info",attrs:{to:"/laporan/htp/area"}},[a._v("\n                    Laporan Htp Area\n                ")])],1)]),a._v(" "),i("b-card",{attrs:{"no-body":""}},[i("div",{staticClass:"text-center"},[i("h4",{staticClass:"mt-3 mb-1"},[i("b",[a._v("Laporan Harga Tingkat Petani Tahun "+a._s(a.tahun))])]),a._v(" "),i("h5",{staticClass:"mb-1"},[a._v("Komoditi "+a._s("Pilih"==a.pilihKategori?"Gabah/Beras dan Palawija":1==a.pilihKategori?"Gabah/Beras":"Palawija"))]),a._v(" "),i("h4",[a._v(a._s(a._f("capital")(a._f("lower")(a.judul))))])]),a._v(" "),i("hr",{staticClass:"border-light m-0"}),a._v(" "),i("div",{staticClass:"table-responsive"},[a.isBusy?[i("div",{staticClass:"text-center text-danger my-2"},[i("b-spinner",{staticClass:"align-middle"}),a._v(" "),i("strong",[a._v("Loading...")])],1)]:[i("table",{staticClass:"table table-striped table-bordered"},[i("thead",{staticClass:"text-center"},[i("tr",[i("th",{staticClass:"align-middle",attrs:{rowspan:"2"}},[a._v("#")]),a._v(" "),i("th",{staticClass:"align-middle",attrs:{rowspan:"2"}},[a._v("Komoditi")]),a._v(" "),i("th",{attrs:{colspan:"12"}},[a._v("Bulan [Rp/Kg]")]),a._v(" "),i("th",{staticClass:"align-middle",attrs:{rowspan:"2"}},[a._v("Rata Rata [Rp/Kg]")]),a._v(" "),i("th",{staticClass:"align-middle",attrs:{rowspan:"2"}},[a._v("Min [Rp/Kg]")]),a._v(" "),i("th",{staticClass:"align-middle",attrs:{rowspan:"2"}},[a._v("Max [Rp/Kg]")])]),a._v(" "),i("tr",a._l(a.listBulan,(function(t,s){return i("th",{key:s},[i("span",{staticStyle:{cursor:"pointer"},on:{click:function(t){return a.toDetail(s+1)}}},[a._v(a._s(t))])])})),0)]),a._v(" "),i("tbody",a._l(a.data.data,(function(t,s){return i("tr",{key:s},[i("td",[a._v(a._s(s+1))]),a._v(" "),i("td",[a._v("\n                                    "+a._s(t.nama)+"\n                                ")]),a._v(" "),a._l(t.bulan,(function(t,s){return i("td",{key:s,staticClass:"text-right"},[a._v("\n                                    "+a._s(t)+"\n                                ")])})),a._v(" "),i("td",{staticClass:"text-right"},[a._v(a._s(t.rata_rata))]),a._v(" "),i("td",{staticClass:"text-right"},[a._v(a._s(t.min))]),a._v(" "),i("td",{staticClass:"text-right"},[a._v(a._s(t.max))])],2)})),0),a._v(" "),i("tfoot",[i("tr",{staticClass:"text-right"},[i("th",{attrs:{colspan:"2"}},[a._v("Rata Rata [Rp/Kg]")]),a._v(" "),a._l(a.data.average,(function(t,s){return i("th",{key:s},[a._v("\n                                    "+a._s(t.rata_rata)+"\n                                ")])}))],2),a._v(" "),i("tr",{staticClass:"text-right"},[i("th",{attrs:{colspan:"2"}},[a._v("Min [Rp/Kg]")]),a._v(" "),a._l(a.data.average,(function(t,s){return i("th",{key:s},[a._v("\n                                    "+a._s(t.min)+"\n                                ")])}))],2),a._v(" "),i("tr",{staticClass:"text-right"},[i("th",{attrs:{colspan:"2"}},[a._v("Max [Rp/Kg]")]),a._v(" "),a._l(a.data.average,(function(t,s){return i("th",{key:s},[a._v("\n                                    "+a._s(t.max)+"\n                                ")])}))],2)])]),a._v(" "),i("div",{staticClass:"mt-2 ml-2"},[i("small",[a._v("\n                            Catatan:\n                            "),i("ul",[i("li",[a._v("Klik pada nama bulan untuk melihat detail bulanan")])])])])]],2)]),a._v(" "),i("b-card",{staticClass:"px-3 py-2 mt-2",attrs:{"no-body":""}},[i("div",{staticClass:"row"},[i("div",{staticClass:"col-md-1 px-0"},[i("b-form-group",{staticClass:"w-auto m-1",attrs:{label:"Tahun"}},[i("b-select",{attrs:{size:"md",options:a.tahunList},model:{value:a.tahun,callback:function(t){a.tahun=t},expression:"tahun"}})],1)],1),a._v(" "),i("div",{staticClass:"col-md-2 px-0"},[i("b-form-group",{staticClass:"w-auto m-1",attrs:{label:"Kategori"}},[i("b-select",{attrs:{size:"md"},model:{value:a.pilihKategori,callback:function(t){a.pilihKategori=t},expression:"pilihKategori"}},[i("option",{attrs:{value:"Pilih"}},[a._v("Pilih")]),a._v(" "),i("option",{attrs:{value:"1"}},[a._v("Gabah/Beras")]),a._v(" "),i("option",{attrs:{value:"2"}},[a._v("Palawija")])])],1)],1),a._v(" "),i("div",{staticClass:"col-md-2 px-0"},[i("b-form-group",{staticClass:"w-auto m-1",attrs:{label:"Kota"}},[i("b-select",{attrs:{size:"md",disabled:a.isKoordinatorKota},model:{value:a.pilihKota,callback:function(t){a.pilihKota=t},expression:"pilihKota"}},[i("option",{attrs:{value:"Pilih"}},[a._v("Pilih")]),a._v(" "),a._l(a.kotaList,(function(t,s){return i("option",{key:s,domProps:{value:t.kota_id}},[a._v("\n                                "+a._s(t.name)+"\n                            ")])}))],2)],1)],1),a._v(" "),i("div",{staticClass:"col-md-2 px-0"},[i("b-form-group",{staticClass:"w-auto m-1",attrs:{label:"Kecamatan"}},[i("b-select",{attrs:{size:"md",disabled:a.isKoordinatorKec},model:{value:a.pilihKecamatan,callback:function(t){a.pilihKecamatan=t},expression:"pilihKecamatan"}},[i("option",{attrs:{value:"Pilih"}},[a._v("Pilih")]),a._v(" "),a._l(a.kecamatanList,(function(t,s){return i("option",{key:s,domProps:{value:t.kecamatan_id}},[a._v("\n                                "+a._s(t.name)+"\n                            ")])}))],2)],1)],1),a._v(" "),i("div",{staticClass:"col-md-2 px-0"},[i("b-form-group",{staticClass:"w-auto m-1",attrs:{label:"Desa"}},[i("b-select",{attrs:{size:"md"},model:{value:a.pilihDesa,callback:function(t){a.pilihDesa=t},expression:"pilihDesa"}},[i("option",{attrs:{value:"Pilih"}},[a._v("Pilih")]),a._v(" "),a._l(a.desaList,(function(t,s){return i("option",{key:s,domProps:{value:t.kelurahan_id}},[a._v("\n                                "+a._s(t.name)+"\n                            ")])}))],2)],1)],1),a._v(" "),i("div",{staticClass:"col-md-3 px-0 text-right"},[a.hasAccess.laporan_htp_download?i("b-btn",{directives:[{name:"b-tooltip",rawName:"v-b-tooltip.hover",modifiers:{hover:!0}}],staticClass:"mt-3",attrs:{variant:"success",title:"Download Data"},on:{click:function(t){return a.download()}}},[i("i",{staticClass:"ion ion-md-download"}),a._v(" Download\n                    ")]):a._e()],1)])])],1)}),[function(){var a=this.$createElement,t=this._self._c||a;return t("div",{staticClass:"d-flex align-items-center"},[t("h5",{staticClass:"m-1 d-inline-block text-nowrap font-weight-normal"},[this._v("\n                    Laporan / Harga Tingkat Petani\n                ")])])}],!1,null,null,null);t.default=e.exports}}]);