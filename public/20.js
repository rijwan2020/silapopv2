(window.webpackJsonp=window.webpackJsonp||[]).push([[20],{"9ANw":function(a,t,n){"use strict";n("R71c")},R71c:function(a,t,n){var e=n("xSvt");"string"==typeof e&&(e=[[a.i,e,""]]);var d={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(e,d);e.locals&&(a.exports=e.locals)},"a6H+":function(a,t,n){"use strict";n.r(t);var e=n("hFjC"),d=n("sK6a"),l={name:"data-kecamatan",metaInfo:{title:"Area Kecamatan"},components:{LaddaBtn:e.a,SweetModal:d.a},data:function(){return{sortBy:"id",sortDesc:!1,data:[],isBusy:!1,saveData:{},loading:!1,mode:"add",index:null,titleDeleteModal:"Are you sure?",id:null,listLevel:{},hasAccess:{},kota:{}}},computed:{totalItems:function(){return this.data.total},totalPages:function(){return Math.ceil(this.totalItems/this.perPage)},user:function(){return this.$store.state.auth.user},penyuluh:function(){var a=this.user;return!(!a||null==a.penyuluh)},fields:function(){var a=[],t=0;return a[t++]={key:"id",label:"#",sortable:!1,tdClass:"align-middle",thClass:"text-nowrap align-middle text-center"},a[t++]={key:"name",label:"Nama Kota",sortable:!1,tdClass:"align-middle",thClass:"text-nowrap align-middle text-center"},a[2]={key:"actions",label:"Action",tdClass:"text-nowrap align-middle text-center",thClass:"text-nowrap align-middle text-center"},a}},methods:{loadKota:function(){var a=this;this.isBusy=!0,axios.get(this.publicUrl+"api/area/"+this.$route.params.id).then((function(t){a.kota=t.data.data,a.loadData()})).catch((function(t){a.swr()}))},loadData:function(){var a=this;axios.get(this.publicUrl+"api/kecamatan",{params:{kota_id:this.kota.kota_id}}).then((function(t){a.data=t.data,a.isBusy=!1})).catch((function(t){a.swr()}))},showModal:function(a){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;if(this.index=t,this.$refs["modal-box"].show(),this.mode=a,"edit"==a){var n=this.data.data[t];this.saveData={name:n.name,provinsi_id:n.provinsi_id,kota_id:n.kota_id,kecamatan_id:n.kecamatan_id,kelurahan_id:n.kelurahan_id,koordinat:n.koordinat,level:"kecamatan"},this.id=n.id}else this.saveData={name:"",provinsi_id:32,kota_id:this.kota.kota_id,kecamatan_id:0,kelurahan_id:0,koordinat:"",level:"kecamatan"}},hideModal:function(){this.$refs["modal-box"].hide()},process:function(){"add"==this.mode?this.save():this.update()},save:function(){var a=this;this.validate()&&(this.loading=!0,axios({method:"post",url:this.publicUrl+"api/area",data:this.saveData}).then((function(t){a.id=t.data.data.id,a.notif("success","Success","Data berhasil ditambahkan"),a.hideModal(),a.saveData={},a.loadData()})).catch((function(t){if(422==t.response.status)for(var n in t.response.data.errors)a.notif("danger","Error",t.response.data.errors[n][0]);else 400==t.response.status?a.notif("warning","Error",t.response.data.message):a.swr()})).then((function(){a.loading=!1})))},update:function(){var a=this;this.validate()&&(this.loading=!0,axios({method:"put",url:this.publicUrl+"api/area/"+this.id,data:this.saveData}).then((function(t){a.notif("success","Success","Data berhasil diperbaharui."),a.hideModal(),a.saveData={},a.loadData()})).catch((function(t){if(422==t.response.status)for(var n in t.response.data.errors)a.notif("danger","Error",t.response.data.errors[n][0]);else 400==t.response.status?a.notif("warning","Error",t.response.data.message):a.swr()})).then((function(){a.loading=!1})))},validate:function(){return""!=this.saveData.name||(this.notif("danger","Error","Nama Kota tidak boleh kosong."),!1)},deleteModal:function(a){this.id=this.data.data[a].id,this.$refs.deleteModalAlert.open()},confirmDelete:function(){var a=this;this.loading=!0,axios({method:"delete",url:this.publicUrl+"api/area/"+this.id}).then((function(t){a.notif("success","Success","Data berhasil dihapus."),a.$refs.deleteModalAlert.close(),a.loadData()})).catch((function(t){400==t.response.status?a.notif("warning","Error",t.response.data.message):a.swr()})).then((function(){a.$refs.deleteModalAlert.close(),a.loading=!1}))},loadLevel:function(){var a=this;axios.get(this.publicUrl+"api/level").then((function(t){a.listLevel=t.data.data,a.loadAcl()}))},loadAcl:function(){var a=this;if(this.user)if(0!=this.user.level){this.hasAccess={kecamatan_add:!1,kecamatan_edit:!1,kecamatan_delete:!1,desa:!1};var t=this.listLevel.find((function(t){return t.level===a.user.level}));t.rule.find((function(a){return"kecamatan_add"===a.name}))&&(this.hasAccess.kecamatan_add=t.rule.find((function(a){return"kecamatan_add"===a.name})).active),t.rule.find((function(a){return"kecamatan_edit"===a.name}))&&(this.hasAccess.kecamatan_edit=t.rule.find((function(a){return"kecamatan_edit"===a.name})).active),t.rule.find((function(a){return"kecamatan_delete"===a.name}))&&(this.hasAccess.kecamatan_delete=t.rule.find((function(a){return"kecamatan_delete"===a.name})).active),t.rule.find((function(a){return"desa"===a.name}))&&(this.hasAccess.desa=t.rule.find((function(a){return"desa"===a.name})).active)}else this.hasAccess={kecamatan_add:!0,kecamatan_edit:!0,kecamatan_delete:!0,desa:!0}}},created:function(){this.loadLevel(),this.loadKota()}},i=n("KHd+"),o=Object(i.a)(l,(function(){var a=this,t=a.$createElement,n=a._self._c||t;return n("div",[n("div",{staticClass:"d-flex justify-content-between align-items-center w-100 mb-0 border-bottom"},[n("div",{staticClass:"d-flex align-items-center"},[n("h5",{staticClass:"m-1 d-inline-block text-nowrap font-weight-normal"},[a._v("\n                    Master / Jawa Barat / "+a._s(a.kota.name)+"\n                ")])]),a._v(" "),n("div",{staticClass:"m-1"},[a.hasAccess.kecamatan_add?n("b-btn",{directives:[{name:"b-tooltip",rawName:"v-b-tooltip.hover",modifiers:{hover:!0}}],attrs:{variant:"primary",title:"Tambah Kecamatan",size:"sm"},on:{click:function(t){return a.showModal("add")}}},[n("i",{staticClass:"ion ion-md-add"}),a._v(" Tambah\n\t\t\t\t")]):a._e()],1)]),a._v(" "),n("b-card",{attrs:{"no-body":""}},[n("hr",{staticClass:"border-light m-0"}),a._v(" "),n("div",{staticClass:"table-responsive"},[n("b-table",{staticClass:"card-table",attrs:{items:a.data.data,fields:a.fields,"sort-by":a.sortBy,"sort-desc":a.sortDesc,striped:!0,bordered:!0,"current-page":1,busy:a.isBusy},on:{"update:sortBy":function(t){a.sortBy=t},"update:sort-by":function(t){a.sortBy=t},"update:sortDesc":function(t){a.sortDesc=t},"update:sort-desc":function(t){a.sortDesc=t}},scopedSlots:a._u([{key:"cell(id)",fn:function(t){return[a._v("\n                        "+a._s(t.index+1)+"\n                    ")]}},{key:"cell(actions)",fn:function(t){return[a.hasAccess.desa?n("router-link",{directives:[{name:"b-tooltip",rawName:"v-b-tooltip.hover",modifiers:{hover:!0}}],staticClass:"btn btn-sm icon-btn btn-success md-btn-flat",attrs:{to:{name:"desa",params:{kecId:a.kota.id,id:t.item.id}},title:"List Desa"}},[n("i",{staticClass:"ion ion-md-menu"})]):a._e(),a._v(" "),a.hasAccess.kecamatan_edit?n("b-btn",{directives:[{name:"b-tooltip",rawName:"v-b-tooltip.hover",modifiers:{hover:!0}}],attrs:{variant:"primary btn-sm icon-btn md-btn-flat",title:"Edit Kecamatan"},on:{click:function(n){return a.showModal("edit",t.index)}}},[n("i",{staticClass:"ion ion-md-create"})]):a._e(),a._v(" "),a.hasAccess.kecamatan_delete?n("b-btn",{directives:[{name:"b-tooltip",rawName:"v-b-tooltip.hover",modifiers:{hover:!0}}],attrs:{variant:"danger btn-sm icon-btn md-btn-flat",title:"Hapus Kecamatan"},on:{click:function(n){return a.deleteModal(t.index)}}},[n("i",{staticClass:"ion ion-md-trash"})]):a._e()]}},{key:"table-busy",fn:function(){return[n("div",{staticClass:"text-center text-danger my-2"},[n("b-spinner",{staticClass:"align-middle"}),a._v(" "),n("strong",[a._v("Loading...")])],1)]},proxy:!0}])})],1),a._v(" "),n("b-card-body",{staticClass:"pt-0 pb-3"},[n("div",{staticClass:"row"},[n("div",{staticClass:"col-sm text-sm-left text-center pt-3"},[a.totalItems?n("span",{staticClass:"text-muted"},[a._v("Total Record : "+a._s(a.totalItems)+"\n\t\t\t\t\t\t")]):a._e()])])])],1),a._v(" "),n("b-modal",{ref:"modal-box",attrs:{centered:"",size:"lg","no-close-on-esc":"","no-close-on-backdrop":"","hide-header-close":""}},[n("div",{attrs:{slot:"modal-title"},slot:"modal-title"},[a._v("\n\t\t\t\t"+a._s("add"==a.mode?"Tambah":"Edit")+" Kecamatan\n\t\t\t")]),a._v(" "),n("b-form-row",[n("b-form-group",{staticClass:"col",attrs:{label:"Kota/Kabupaten"}},[n("b-input",{attrs:{placeholder:"Kota/Kabupaten",disabled:""},model:{value:a.kota.name,callback:function(t){a.$set(a.kota,"name",t)},expression:"kota.name"}})],1)],1),a._v(" "),n("b-form-row",[n("b-form-group",{staticClass:"col",attrs:{label:"Nama Kecamatan"}},[n("b-input",{attrs:{placeholder:"Nama Kecamatan"},model:{value:a.saveData.name,callback:function(t){a.$set(a.saveData,"name",t)},expression:"saveData.name"}})],1)],1),a._v(" "),n("div",{attrs:{slot:"modal-footer"},slot:"modal-footer"},[n("b-btn",{directives:[{name:"b-tooltip",rawName:"v-b-tooltip.hover",modifiers:{hover:!0}}],attrs:{variant:"secondary",title:"Close"},on:{click:function(t){return a.hideModal()}}},[a._v("Close")]),a._v(" "),n("ladda-btn",{staticClass:"btn btn-primary",attrs:{loading:a.loading,"data-style":"zoom-out"},nativeOn:{click:function(t){return a.process(t)}}},[a._v("\n\t\t\t\t\t"+a._s("add"==a.mode?"Save":"Update")+"\n\t\t\t\t")])],1)],1),a._v(" "),n("sweet-modal",{ref:"deleteModalAlert",attrs:{icon:"warning",title:"Hapus Kecamatan","hide-close-button":"",blocking:""}},[a._v("\n\t\t\t"+a._s(a.titleDeleteModal)+"\n\t\t\t"),n("template",{staticStyle:{"text-align":"center"},slot:"button"},[n("ladda-btn",{staticClass:"btn btn-primary",attrs:{loading:a.loading,"data-style":"zoom-out"},nativeOn:{click:function(t){return a.confirmDelete()}}},[a._v("Confirm")]),a._v(" "),n("b-btn",{attrs:{variant:"default"},on:{click:function(t){return a.$refs.deleteModalAlert.close()}}},[a._v("Cancel")])],1)],2)],1)}),[],!1,null,null,null);t.default=o.exports},hFjC:function(a,t,n){"use strict";var e=n("yhyq"),d={name:"ladda-btn",props:{loading:{default:!1},tag:{type:String,default:"button"}},render:function(a){var t="button"===this.tag?{type:"button"}:{};return a(this.tag,{attrs:t},this.$slots.default)},beforeDestroy:function(){this.ladda&&(this.ladda.remove(),this.ladda=null)},mounted:function(){this.ladda=e.a(this.$el)},watch:{loading:function(a){this.ladda&&("boolean"==typeof a?this.ladda[a?"start":"stop"]():"number"==typeof a&&(this.ladda.isLoading()||this.ladda.start(),this.ladda.setProgress(a)))}}},l=(n("9ANw"),n("KHd+")),i=Object(l.a)(d,void 0,void 0,!1,null,null,null);t.a=i.exports},xSvt:function(a,t,n){(a.exports=n("I1BE")(!1)).push([a.i,'/*!\r\n * Ladda\r\n * http://lab.hakim.se/ladda\r\n * MIT licensed\r\n *\r\n * Copyright (C) 2018 Hakim El Hattab, http://hakim.se\r\n */\n@-webkit-keyframes ladda-spinner-line-fade {\n0%, 100% {\n    opacity: 0.22;\n}\n1% {\n    opacity: 1;\n}\n}\n@keyframes ladda-spinner-line-fade {\n0%, 100% {\n    opacity: 0.22;\n}\n1% {\n    opacity: 1;\n}\n}\n.ladda-button {\n  position: relative;\n}\n.ladda-button .ladda-spinner {\n  position: absolute;\n  z-index: 2;\n  display: inline-block;\n  width: 32px;\n  top: 50%;\n  margin-top: 0;\n  opacity: 0;\n  pointer-events: none;\n}\n.ladda-button .ladda-label {\n  position: relative;\n  z-index: 3;\n}\n.ladda-button .ladda-progress {\n  position: absolute;\n  width: 0;\n  height: 100%;\n  left: 0;\n  top: 0;\n  background: rgba(0, 0, 0, 0.2);\n  display: none;\n  transition: 0.1s linear all !important;\n}\n.ladda-button[data-loading] .ladda-progress {\n  display: block;\n}\n.ladda-button, .ladda-button .ladda-spinner, .ladda-button .ladda-label {\n  transition: 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) all !important;\n}\n.ladda-button[data-style=zoom-in], .ladda-button[data-style=zoom-in] .ladda-spinner, .ladda-button[data-style=zoom-in] .ladda-label, .ladda-button[data-style=zoom-out], .ladda-button[data-style=zoom-out] .ladda-spinner, .ladda-button[data-style=zoom-out] .ladda-label {\n  transition: 0.3s ease all !important;\n}\n.ladda-button[data-style=expand-right] .ladda-spinner {\n  right: -6px;\n}\n.ladda-button[data-style=expand-right][data-size="s"] .ladda-spinner, .ladda-button[data-style=expand-right][data-size="xs"] .ladda-spinner {\n  right: -12px;\n}\n.ladda-button[data-style=expand-right][data-loading] {\n  padding-right: 56px;\n}\n.ladda-button[data-style=expand-right][data-loading] .ladda-spinner {\n  opacity: 1;\n}\n.ladda-button[data-style=expand-right][data-loading][data-size="s"], .ladda-button[data-style=expand-right][data-loading][data-size="xs"] {\n  padding-right: 40px;\n}\n.ladda-button[data-style=expand-left] .ladda-spinner {\n  left: 26px;\n}\n.ladda-button[data-style=expand-left][data-size="s"] .ladda-spinner, .ladda-button[data-style=expand-left][data-size="xs"] .ladda-spinner {\n  left: 4px;\n}\n.ladda-button[data-style=expand-left][data-loading] {\n  padding-left: 56px;\n}\n.ladda-button[data-style=expand-left][data-loading] .ladda-spinner {\n  opacity: 1;\n}\n.ladda-button[data-style=expand-left][data-loading][data-size="s"], .ladda-button[data-style=expand-left][data-loading][data-size="xs"] {\n  padding-left: 40px;\n}\n.ladda-button[data-style=expand-up] {\n  overflow: hidden;\n}\n.ladda-button[data-style=expand-up] .ladda-spinner {\n  top: -32px;\n  left: 50%;\n  margin-left: 0;\n}\n.ladda-button[data-style=expand-up][data-loading] {\n  padding-top: 54px;\n}\n.ladda-button[data-style=expand-up][data-loading] .ladda-spinner {\n  opacity: 1;\n  top: 26px;\n  margin-top: 0;\n}\n.ladda-button[data-style=expand-up][data-loading][data-size="s"], .ladda-button[data-style=expand-up][data-loading][data-size="xs"] {\n  padding-top: 32px;\n}\n.ladda-button[data-style=expand-up][data-loading][data-size="s"] .ladda-spinner, .ladda-button[data-style=expand-up][data-loading][data-size="xs"] .ladda-spinner {\n  top: 4px;\n}\n.ladda-button[data-style=expand-down] {\n  overflow: hidden;\n}\n.ladda-button[data-style=expand-down] .ladda-spinner {\n  top: 62px;\n  left: 50%;\n  margin-left: 0;\n}\n.ladda-button[data-style=expand-down][data-size="s"] .ladda-spinner, .ladda-button[data-style=expand-down][data-size="xs"] .ladda-spinner {\n  top: 40px;\n}\n.ladda-button[data-style=expand-down][data-loading] {\n  padding-bottom: 54px;\n}\n.ladda-button[data-style=expand-down][data-loading] .ladda-spinner {\n  opacity: 1;\n}\n.ladda-button[data-style=expand-down][data-loading][data-size="s"], .ladda-button[data-style=expand-down][data-loading][data-size="xs"] {\n  padding-bottom: 32px;\n}\n.ladda-button[data-style=slide-left] {\n  overflow: hidden;\n}\n.ladda-button[data-style=slide-left] .ladda-label {\n  position: relative;\n}\n.ladda-button[data-style=slide-left] .ladda-spinner {\n  left: 100%;\n  margin-left: 0;\n}\n.ladda-button[data-style=slide-left][data-loading] .ladda-label {\n  opacity: 0;\n  left: -100%;\n}\n.ladda-button[data-style=slide-left][data-loading] .ladda-spinner {\n  opacity: 1;\n  left: 50%;\n}\n.ladda-button[data-style=slide-right] {\n  overflow: hidden;\n}\n.ladda-button[data-style=slide-right] .ladda-label {\n  position: relative;\n}\n.ladda-button[data-style=slide-right] .ladda-spinner {\n  right: 100%;\n  margin-left: 0;\n  left: 16px;\n}\n[dir="rtl"] .ladda-button[data-style=slide-right] .ladda-spinner {\n  right: auto;\n}\n.ladda-button[data-style=slide-right][data-loading] .ladda-label {\n  opacity: 0;\n  left: 100%;\n}\n.ladda-button[data-style=slide-right][data-loading] .ladda-spinner {\n  opacity: 1;\n  left: 50%;\n}\n.ladda-button[data-style=slide-up] {\n  overflow: hidden;\n}\n.ladda-button[data-style=slide-up] .ladda-label {\n  position: relative;\n}\n.ladda-button[data-style=slide-up] .ladda-spinner {\n  left: 50%;\n  margin-left: 0;\n  margin-top: 1em;\n}\n.ladda-button[data-style=slide-up][data-loading] .ladda-label {\n  opacity: 0;\n  top: -1em;\n}\n.ladda-button[data-style=slide-up][data-loading] .ladda-spinner {\n  opacity: 1;\n  margin-top: 0;\n}\n.ladda-button[data-style=slide-down] {\n  overflow: hidden;\n}\n.ladda-button[data-style=slide-down] .ladda-label {\n  position: relative;\n}\n.ladda-button[data-style=slide-down] .ladda-spinner {\n  left: 50%;\n  margin-left: 0;\n  margin-top: -2em;\n}\n.ladda-button[data-style=slide-down][data-loading] .ladda-label {\n  opacity: 0;\n  top: 1em;\n}\n.ladda-button[data-style=slide-down][data-loading] .ladda-spinner {\n  opacity: 1;\n  margin-top: 0;\n}\n.ladda-button[data-style=zoom-out] {\n  overflow: hidden;\n}\n.ladda-button[data-style=zoom-out] .ladda-spinner {\n  left: 50%;\n  margin-left: 32px;\n  -webkit-transform: scale(2.5);\n          transform: scale(2.5);\n}\n.ladda-button[data-style=zoom-out] .ladda-label {\n  position: relative;\n  display: inline-block;\n}\n.ladda-button[data-style=zoom-out][data-loading] .ladda-label {\n  opacity: 0;\n  -webkit-transform: scale(0.5);\n          transform: scale(0.5);\n}\n.ladda-button[data-style=zoom-out][data-loading] .ladda-spinner {\n  opacity: 1;\n  margin-left: 0;\n  -webkit-transform: none;\n          transform: none;\n}\n.ladda-button[data-style=zoom-in] {\n  overflow: hidden;\n}\n.ladda-button[data-style=zoom-in] .ladda-spinner {\n  left: 50%;\n  margin-left: -16px;\n  -webkit-transform: scale(0.2);\n          transform: scale(0.2);\n}\n.ladda-button[data-style=zoom-in] .ladda-label {\n  position: relative;\n  display: inline-block;\n}\n.ladda-button[data-style=zoom-in][data-loading] .ladda-label {\n  opacity: 0;\n  -webkit-transform: scale(2.2);\n          transform: scale(2.2);\n}\n.ladda-button[data-style=zoom-in][data-loading] .ladda-spinner {\n  opacity: 1;\n  margin-left: 0;\n  -webkit-transform: none;\n          transform: none;\n}\n.ladda-button[data-style=contract] {\n  overflow: hidden;\n  width: 100px;\n}\n.ladda-button[data-style=contract] .ladda-spinner {\n  left: 50%;\n  margin-left: 0;\n}\n.ladda-button[data-style=contract][data-loading] {\n  border-radius: 50%;\n  width: 52px;\n}\n.ladda-button[data-style=contract][data-loading] .ladda-label {\n  opacity: 0;\n}\n.ladda-button[data-style=contract][data-loading] .ladda-spinner {\n  opacity: 1;\n}\n.ladda-button[data-style=contract-overlay] {\n  overflow: hidden;\n  width: 100px;\n  box-shadow: 0px 0px 0px 2000px rgba(0, 0, 0, 0);\n}\n.ladda-button[data-style=contract-overlay] .ladda-spinner {\n  left: 50%;\n  margin-left: 0;\n}\n.ladda-button[data-style=contract-overlay][data-loading] {\n  border-radius: 50%;\n  width: 52px;\n  box-shadow: 0px 0px 0px 2000px rgba(0, 0, 0, 0.8);\n}\n.ladda-button[data-style=contract-overlay][data-loading] .ladda-label {\n  opacity: 0;\n}\n.ladda-button[data-style=contract-overlay][data-loading] .ladda-spinner {\n  opacity: 1;\n}\n[dir="rtl"] .ladda-spinner > div {\n  left: 25% !important;\n}\n.ladda-button[data-style=contract-overlay][data-loading] {\n  box-shadow: 0px 0px 0px 2000px rgba(0, 0, 0, 0.8) !important;\n}\n.ladda-button[data-style="contract"],\n.ladda-button[data-style="contract-overlay"] {\n  width: auto;\n}\n',""])}}]);