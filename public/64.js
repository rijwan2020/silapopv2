(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[64],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/master/wilayahkerja/List.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/master/wilayahkerja/List.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _vendor_libs_ladda_Ladda__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/vendor/libs/ladda/Ladda */ "./resources/assets/src/vendor/libs/ladda/Ladda.vue");
/* harmony import */ var sweet_modal_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! sweet-modal-vue */ "./node_modules/sweet-modal-vue/src/main.js");
/* harmony import */ var vue_multiselect__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue-multiselect */ "./node_modules/vue-multiselect/dist/vue-multiselect.min.js");
/* harmony import */ var vue_multiselect__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(vue_multiselect__WEBPACK_IMPORTED_MODULE_2__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ __webpack_exports__["default"] = ({
  name: 'wilayahkerja',
  metaInfo: {
    title: 'Wilayah Kerja'
  },
  components: {
    LaddaBtn: _vendor_libs_ladda_Ladda__WEBPACK_IMPORTED_MODULE_0__["default"],
    SweetModal: sweet_modal_vue__WEBPACK_IMPORTED_MODULE_1__["SweetModal"],
    Multiselect: vue_multiselect__WEBPACK_IMPORTED_MODULE_2___default.a
  },
  data: function data() {
    return {
      // Options
      sortBy: "id",
      sortDesc: false,
      perPage: 10,
      loadParams: {},
      data: [],
      currentPage: 1,
      isBusy: false,
      saveData: {},
      loading: false,
      mode: "add",
      index: null,
      titleDeleteModal: "Are you sure?",
      formKotaId: "Pilih",
      formKecamatanId: "Pilih",
      formDesaId: "Pilih",
      formKecamatanList: [],
      formDesaList: [],
      pilihKota: 'Pilih',
      pilihKecamatan: 'Pilih',
      pilihDesa: 'Pilih',
      pilihJenis: 'Pilih',
      kotaList: [],
      kecamatanList: [],
      desaList: [],
      id: null,
      penyuluh_id: "Pilih",
      pilihPenyuluh: "Pilih",
      penyuluhList: [],
      penyuluhTerpilih: "",
      listLevel: {},
      hasAccess: {},
      isKoordinatorKota: false,
      isKoordinatorKec: false,
      isKorwil: false
    };
  },
  computed: {
    totalItems: function totalItems() {
      return this.data.total;
    },
    totalPages: function totalPages() {
      return Math.ceil(this.totalItems / this.perPage);
    },
    user: function user() {
      return this.$store.state.auth.user;
    },
    penyuluh: function penyuluh() {
      var user = this.user;

      if (user && user.penyuluh != null) {
        return true;
      }

      return false;
    },
    fields: function fields() {
      var data = [];
      var i = 0;
      data[i++] = {
        key: "id",
        label: "#",
        sortable: false,
        tdClass: "align-middle",
        thClass: "text-nowrap align-middle text-center"
      };

      if (!this.penyuluh) {
        data[i++] = {
          key: "name",
          label: "Nama",
          sortable: false,
          tdClass: "align-middle",
          thClass: "text-nowrap align-middle text-center"
        };
        data[i++] = {
          key: "username",
          label: "Username",
          sortable: false,
          tdClass: "align-middle",
          thClass: "text-nowrap align-middle text-center"
        };
        data[i++] = {
          key: "jenis_penyuluh",
          label: "Jenis Penyuluh",
          sortable: false,
          tdClass: "align-middle",
          thClass: "text-nowrap align-middle text-center"
        };
      }

      data[i++] = {
        key: "kota",
        label: "Kota",
        sortable: false,
        tdClass: "align-middle",
        thClass: "text-nowrap align-middle text-center"
      };
      data[i++] = {
        key: "kecamatan",
        label: "Kecamatan",
        sortable: false,
        tdClass: "align-middle",
        thClass: "text-nowrap align-middle text-center"
      };
      data[i++] = {
        key: "desa",
        label: "Desa",
        sortable: false,
        tdClass: "align-middle",
        thClass: "text-nowrap align-middle text-center"
      };
      data[i] = {
        key: "actions",
        label: "Action",
        tdClass: "text-nowrap align-middle text-center",
        thClass: "text-nowrap align-middle text-center"
      };
      return data;
    }
  },
  watch: {
    currentPage: function currentPage(v) {
      this.loadData(v);
    },
    perPage: function perPage(v) {
      this.loadData();
    },
    formKotaId: function formKotaId(v) {
      if (v == 'Pilih') {
        this.formKecamatanList = [];
        this.formKecamatanId = "Pilih";
        this.formDesaList = [];
        this.formDesaId = "Pilih";
      } else {
        this.loadListKecamatan('form');
      }
    },
    formKecamatanId: function formKecamatanId(v) {
      if (v == 'Pilih') {
        this.formDesaList = [];
        this.formDesaId = "Pilih";
      } else {
        this.loadListDesa('form');
      }
    },
    pilihKota: function pilihKota(v) {
      this.loadData();

      if (v == 'Pilih') {
        this.kecamatanList = [];
        this.pilihKecamatan = "Pilih";
        this.desaList = [];
        this.pilihDesa = "Pilih";
      } else {
        this.loadListKecamatan('list');
      }
    },
    pilihKecamatan: function pilihKecamatan(v) {
      this.loadData();

      if (v == 'Pilih') {
        this.desaList = [];
        this.pilihDesa = "Pilih";
      } else {
        this.loadListDesa('list');
      }
    },
    pilihDesa: function pilihDesa() {
      this.loadData();
    },
    pilihPenyuluh: function pilihPenyuluh(v) {
      this.penyuluh_id = v.id;
    }
  },
  methods: {
    loadData: function loadData() {
      var _this = this;

      this.isBusy = true;
      this.loadParams.page = this.currentPage;
      this.loadParams.order_by = this.sortBy;
      this.loadParams.sort_desc = this.sortDesc;
      this.loadParams.limit = this.perPage;
      this.loadParams.params = {}; //jika yg login penyuluh

      if (this.user.penyuluh != null) {
        this.loadParams.params.penyuluh_id = this.user.penyuluh.id;
      }

      if (this.pilihKota != "Pilih") {
        this.loadParams.params.kota_id = this.pilihKota;
      }

      if (this.pilihKecamatan != "Pilih") {
        this.loadParams.params.kecamatan_id = this.pilihKecamatan;
      }

      if (this.pilihDesa != "Pilih") {
        this.loadParams.params.kelurahan_id = this.pilihDesa;
      }

      axios.get(this.publicUrl + "api/wilayahkerja", {
        params: this.loadParams
      }).then(function (response) {
        // handle success
        _this.data = response.data;
        _this.isBusy = false;
      })["catch"](function (error) {
        _this.swr();
      });
    },
    loadDataPenyuluh: function loadDataPenyuluh() {
      var _this2 = this;

      axios.get(this.publicUrl + "api/penyuluh/all").then(function (response) {
        // handle success
        _this2.penyuluhList = response.data.data;
      })["catch"](function (error) {
        _this2.swr();
      });
    },
    loadListKota: function loadListKota() {
      var _this3 = this;

      axios.get(this.publicUrl + "api/kota").then(function (response) {
        // handle success
        _this3.kotaList = response.data.data;
      })["catch"](function (error) {
        _this3.swr();
      });
    },
    loadListKecamatan: function loadListKecamatan() {
      var _this4 = this;

      var show = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'list';

      if (show == 'list') {
        axios.get(this.publicUrl + "api/kecamatan", {
          params: {
            kota_id: this.pilihKota
          }
        }).then(function (response) {
          // handle success
          _this4.kecamatanList = response.data.data;
        })["catch"](function (error) {
          _this4.swr();
        });
      } else {
        axios.get(this.publicUrl + "api/kecamatan", {
          params: {
            kota_id: this.formKotaId
          }
        }).then(function (response) {
          // handle success
          _this4.formKecamatanList = response.data.data;
        })["catch"](function (error) {
          _this4.swr();
        });
      }
    },
    // load list data desa
    loadListDesa: function loadListDesa() {
      var _this5 = this;

      var show = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : "list";

      if (show == "list") {
        axios.get(this.publicUrl + "api/desa", {
          params: {
            kota_id: this.pilihKota,
            kecamatan_id: this.pilihKecamatan
          }
        }).then(function (response) {
          // handle success
          _this5.desaList = response.data.data;
        })["catch"](function (error) {
          _this5.swr();
        });
      } else {
        axios.get(this.publicUrl + "api/desa", {
          params: {
            kota_id: this.formKotaId,
            kecamatan_id: this.formKecamatanId
          }
        }).then(function (response) {
          // handle success
          _this5.formDesaList = response.data.data;
        })["catch"](function (error) {
          _this5.swr();
        });
      }
    },
    // Show Modal box add
    showModal: function showModal(mode) {
      var i = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
      this.index = i;
      this.$refs["modal-box"].show();
      this.mode = mode;

      if (mode == 'edit') {
        var data = this.data.data[i];
        this.penyuluh_id = data.penyuluh_id;
        this.penyuluhTerpilih = data.penyuluh.nama;
        this.formKotaId = data.kota_id;
        this.formKecamatanId = data.kecamatan_id;
        this.formDesaId = data.kelurahan_id;
        this.id = data.id;
      } else {
        this.formKotaId = "Pilih";
        this.formKecamatanId = "Pilih";
        this.formDesaId = "Pilih";
      }
    },
    // Close modal add/edit baku lahan
    hideModal: function hideModal() {
      this.$refs["modal-box"].hide();
    },
    // Check proses input
    process: function process() {
      if (this.mode == "add") {
        this.save();
      } else {
        this.update();
      }
    },
    // Proses insert data user
    save: function save() {
      var _this6 = this;

      if (!this.validate()) {
        return;
      }

      this.loading = true;
      this.saveData.kota_id = this.formKotaId;
      this.saveData.kecamatan_id = this.formKecamatanId;
      this.saveData.kelurahan_id = this.formDesaId;
      this.saveData.user_id = this.user.id;
      this.saveData.penyuluh_id = this.penyuluh_id;
      axios({
        method: "post",
        url: this.publicUrl + "api/wilayahkerja",
        data: this.saveData
      }).then(function (response) {
        _this6.id = response.data.data.id;

        _this6.notif("success", "Success", "Data berhasil ditambahkan");

        _this6.hideModal();

        _this6.saveData = {}; // reload data

        _this6.loadData();
      })["catch"](function (error) {
        if (error.response.status == 422) {
          for (var i in error.response.data.errors) {
            _this6.notif("danger", "Error", error.response.data.errors[i][0]);
          }
        } else if (error.response.status == 400) {
          _this6.notif("warning", "Error", error.response.data.message);
        } else {
          _this6.swr();
        }
      }).then(function () {
        _this6.loading = false;
      });
    },
    // Proses update data
    update: function update() {
      var _this7 = this;

      if (!this.validate()) {
        return;
      }

      this.loading = true;
      this.saveData.kota_id = this.formKotaId;
      this.saveData.kecamatan_id = this.formKecamatanId;
      this.saveData.kelurahan_id = this.formDesaId;
      this.saveData.penyuluh_id = this.penyuluh_id;
      this.saveData.user_id = this.user.id;
      axios({
        method: "put",
        url: this.publicUrl + "api/wilayahkerja/" + this.id,
        data: this.saveData
      }).then(function (response) {
        _this7.notif("success", "Success", "Data berhasil diperbaharui.");

        _this7.hideModal();

        _this7.saveData = {};

        _this7.loadData();
      })["catch"](function (error) {
        if (error.response.status == 422) {
          for (var i in error.response.data.errors) {
            _this7.notif("danger", "Error", error.response.data.errors[i][0]);
          }
        } else if (error.response.status == 400) {
          _this7.notif("warning", "Error", error.response.data.message);
        } else {
          _this7.swr();
        }
      }).then(function () {
        _this7.loading = false;
      });
    },
    // Validati input data
    validate: function validate() {
      if (this.formKotaId == "Pilih") {
        this.notif("danger", "Error", "Kota belum dipilih.");
        return false;
      }

      if (this.penyuluh_id == "Pilih") {
        this.notif("danger", "Error", "Penyuluh belum dipilih.");
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

      return true;
    },
    // Show delete modal box
    deleteModal: function deleteModal(i) {
      this.id = this.data.data[i].id;
      this.$refs.deleteModalAlert.open();
    },
    // Confirm delete data
    confirmDelete: function confirmDelete() {
      var _this8 = this;

      this.loading = true;
      axios({
        method: "delete",
        url: this.publicUrl + "api/wilayahkerja/" + this.id
      }).then(function (response) {
        _this8.notif("success", "Success", "Data berhasil dihapus.");

        _this8.$refs.deleteModalAlert.close(); // reload data


        _this8.loadData();
      })["catch"](function (error) {
        if (error.response.status == 400) {
          _this8.notif("warning", "Error", error.response.data.message);
        } else {
          _this8.swr();
        }
      }).then(function () {
        _this8.$refs.deleteModalAlert.close();

        _this8.loading = false;
      });
    },
    // Load level dan ACL
    loadLevel: function loadLevel() {
      var _this9 = this;

      axios.get(this.publicUrl + "api/level").then(function (response) {
        // handle success
        _this9.listLevel = response.data.data;

        _this9.loadAcl();
      });
    },
    loadAcl: function loadAcl() {
      var _this10 = this;

      if (this.user) {
        if (this.user.level != 0) {
          this.hasAccess = {
            wilayah_kerja_add: false,
            wilayah_kerja_edit: false,
            wilayah_kerja_delete: false
          };
          var data_rule = this.listLevel.find(function (o) {
            return o.level === _this10.user.level;
          });

          if (data_rule.rule.find(function (o) {
            return o.name === 'wilayah_kerja_add';
          })) {
            this.hasAccess.wilayah_kerja_add = data_rule.rule.find(function (o) {
              return o.name === 'wilayah_kerja_add';
            }).active;
          }

          if (data_rule.rule.find(function (o) {
            return o.name === 'wilayah_kerja_edit';
          })) {
            this.hasAccess.wilayah_kerja_edit = data_rule.rule.find(function (o) {
              return o.name === 'wilayah_kerja_edit';
            }).active;
          }

          if (data_rule.rule.find(function (o) {
            return o.name === 'wilayah_kerja_delete';
          })) {
            this.hasAccess.wilayah_kerja_delete = data_rule.rule.find(function (o) {
              return o.name === 'wilayah_kerja_delete';
            }).active;
          }
        } else {
          this.hasAccess = {
            wilayah_kerja_add: true,
            wilayah_kerja_edit: true,
            wilayah_kerja_delete: true
          };
        }
      }
    },
    loadKorwil: function loadKorwil() {
      var _this11 = this;

      if (this.user) {
        var user = this.user;

        if (user.level == 96) {
          axios.get(this.publicUrl + "api/korwil/byUser/" + user.id).then(function (response) {
            // handle success
            _this11.isKorwil = response.data.data;
            _this11.isKoordinatorKota = true;
            _this11.pilihKota = response.data.data.kota_id;
          })["catch"](function (error) {
            _this11.swr();
          });
        }

        if (user.level == 95) {
          axios.get(this.publicUrl + "api/korwil/byUser/" + user.id).then(function (response) {
            // handle success
            _this11.isKorwil = response.data.data;
            _this11.isKoordinatorKota = true;
            _this11.isKoordinatorKec = true;
            _this11.pilihKota = response.data.data.kota_id;
            _this11.pilihKecamatan = response.data.data.kecamatan_id;
          })["catch"](function (error) {
            _this11.swr();
          });
        }
      }
    }
  },
  created: function created() {
    this.loadLevel();

    if (this.penyuluh) {
      this.penyuluh_id = this.user.penyuluh.id;
    }

    this.loadListKota();
    this.loadData();
    this.loadKorwil();
    this.loadDataPenyuluh();
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/master/wilayahkerja/List.vue?vue&type=template&id=31da7424&":
/*!***********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/master/wilayahkerja/List.vue?vue&type=template&id=31da7424& ***!
  \***********************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    [
      _c(
        "div",
        {
          staticClass:
            "d-flex justify-content-between align-items-center w-100 mb-0 border-bottom"
        },
        [
          _vm._m(0),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "m-1" },
            [
              _vm.hasAccess.wilayah_kerja_add
                ? _c(
                    "b-btn",
                    {
                      directives: [
                        {
                          name: "b-tooltip",
                          rawName: "v-b-tooltip.hover",
                          modifiers: { hover: true }
                        }
                      ],
                      attrs: {
                        variant: "primary",
                        title: "Tambah Data Luas",
                        size: "sm"
                      },
                      on: {
                        click: function($event) {
                          return _vm.showModal("add")
                        }
                      }
                    },
                    [
                      _c("i", { staticClass: "ion ion-md-add" }),
                      _vm._v(" Tambah\n\t\t\t\t")
                    ]
                  )
                : _vm._e()
            ],
            1
          )
        ]
      ),
      _vm._v(" "),
      _c(
        "b-card",
        { attrs: { "no-body": "" } },
        [
          _c("hr", { staticClass: "border-light m-0" }),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "table-responsive" },
            [
              _c("b-table", {
                staticClass: "card-table",
                attrs: {
                  items: _vm.data.data,
                  fields: _vm.fields,
                  "sort-by": _vm.sortBy,
                  "sort-desc": _vm.sortDesc,
                  striped: true,
                  bordered: true,
                  "current-page": 1,
                  "per-page": _vm.perPage,
                  busy: _vm.isBusy
                },
                on: {
                  "update:sortBy": function($event) {
                    _vm.sortBy = $event
                  },
                  "update:sort-by": function($event) {
                    _vm.sortBy = $event
                  },
                  "update:sortDesc": function($event) {
                    _vm.sortDesc = $event
                  },
                  "update:sort-desc": function($event) {
                    _vm.sortDesc = $event
                  }
                },
                scopedSlots: _vm._u([
                  {
                    key: "cell(id)",
                    fn: function(data) {
                      return [
                        _vm._v(
                          "\n                        " +
                            _vm._s(
                              data.item.id != 0
                                ? data.index +
                                    1 +
                                    (_vm.currentPage - 1) * _vm.perPage
                                : "-"
                            ) +
                            "\n                    "
                        )
                      ]
                    }
                  },
                  {
                    key: "cell(jenis_penyuluh)",
                    fn: function(data) {
                      return [
                        data.item.jenis_penyuluh == 0
                          ? _c(
                              "b-badge",
                              { attrs: { variant: "outline-info" } },
                              [
                                _vm._v(
                                  "\n                            PPL\n                        "
                                )
                              ]
                            )
                          : _c(
                              "b-badge",
                              { attrs: { variant: "outline-success" } },
                              [
                                _vm._v(
                                  "\n                            POPT\n                        "
                                )
                              ]
                            )
                      ]
                    }
                  },
                  {
                    key: "cell(actions)",
                    fn: function(data) {
                      return [
                        _vm.hasAccess.wilayah_kerja_edit
                          ? _c(
                              "b-btn",
                              {
                                directives: [
                                  {
                                    name: "b-tooltip",
                                    rawName: "v-b-tooltip.hover",
                                    modifiers: { hover: true }
                                  }
                                ],
                                attrs: {
                                  variant:
                                    "primary btn-sm icon-btn md-btn-flat",
                                  title: "Edit Wilayah Kerja"
                                },
                                on: {
                                  click: function($event) {
                                    return _vm.showModal("edit", data.index)
                                  }
                                }
                              },
                              [_c("i", { staticClass: "ion ion-md-create" })]
                            )
                          : _vm._e(),
                        _vm._v(" "),
                        _vm.hasAccess.wilayah_kerja_delete
                          ? _c(
                              "b-btn",
                              {
                                directives: [
                                  {
                                    name: "b-tooltip",
                                    rawName: "v-b-tooltip.hover",
                                    modifiers: { hover: true }
                                  }
                                ],
                                attrs: {
                                  variant: "danger btn-sm icon-btn md-btn-flat",
                                  title: "Hapus Wilayah Kerja"
                                },
                                on: {
                                  click: function($event) {
                                    return _vm.deleteModal(data.index)
                                  }
                                }
                              },
                              [_c("i", { staticClass: "ion ion-md-trash" })]
                            )
                          : _vm._e()
                      ]
                    }
                  },
                  {
                    key: "table-busy",
                    fn: function() {
                      return [
                        _c(
                          "div",
                          { staticClass: "text-center text-danger my-2" },
                          [
                            _c("b-spinner", { staticClass: "align-middle" }),
                            _vm._v(" "),
                            _c("strong", [_vm._v("Loading...")])
                          ],
                          1
                        )
                      ]
                    },
                    proxy: true
                  }
                ])
              })
            ],
            1
          ),
          _vm._v(" "),
          _c("b-card-body", { staticClass: "pt-0 pb-3" }, [
            _c("div", { staticClass: "row" }, [
              _c(
                "div",
                { staticClass: "col-sm text-sm-left text-center pt-3" },
                [
                  _vm.totalItems
                    ? _c("span", { staticClass: "text-muted" }, [
                        _vm._v(
                          "Page " +
                            _vm._s(_vm.currentPage) +
                            " of " +
                            _vm._s(_vm.totalPages) +
                            "\n\t\t\t\t\t\t\t"
                        ),
                        _c("br"),
                        _vm._v(
                          "\n\t\t\t\t\t\t\tTotal Record : " +
                            _vm._s(_vm.totalItems) +
                            "\n\t\t\t\t\t\t"
                        )
                      ])
                    : _vm._e()
                ]
              ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "col-sm pt-3" },
                [
                  _vm.totalItems
                    ? _c("b-pagination", {
                        staticClass:
                          "justify-content-center justify-content-sm-end m-0",
                        attrs: {
                          "total-rows": _vm.totalItems,
                          "per-page": _vm.perPage,
                          size: "md"
                        },
                        model: {
                          value: _vm.currentPage,
                          callback: function($$v) {
                            _vm.currentPage = $$v
                          },
                          expression: "currentPage"
                        }
                      })
                    : _vm._e()
                ],
                1
              )
            ])
          ])
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "b-card",
        { staticClass: "px-3 py-2 mt-2", attrs: { "no-body": "" } },
        [
          _c("div", { staticClass: "row" }, [
            _c(
              "div",
              { staticClass: "col-md-1 px-0" },
              [
                _c(
                  "b-form-group",
                  { staticClass: "w-auto m-1", attrs: { label: "Limit" } },
                  [
                    _c("b-select", {
                      attrs: { size: "md", options: [10, 20, 30, 40, 50] },
                      model: {
                        value: _vm.perPage,
                        callback: function($$v) {
                          _vm.perPage = $$v
                        },
                        expression: "perPage"
                      }
                    })
                  ],
                  1
                )
              ],
              1
            ),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "col-md-3 px-0" },
              [
                _c(
                  "b-form-group",
                  { staticClass: "w-auto m-1", attrs: { label: "Kota" } },
                  [
                    _c(
                      "b-select",
                      {
                        attrs: { size: "md", disabled: _vm.isKoordinatorKota },
                        model: {
                          value: _vm.pilihKota,
                          callback: function($$v) {
                            _vm.pilihKota = $$v
                          },
                          expression: "pilihKota"
                        }
                      },
                      [
                        _c("option", { attrs: { value: "Pilih" } }, [
                          _vm._v("Pilih")
                        ]),
                        _vm._v(" "),
                        _vm._l(_vm.kotaList, function(kota, i) {
                          return _c(
                            "option",
                            { key: i, domProps: { value: kota.kota_id } },
                            [
                              _vm._v(
                                "\n                                " +
                                  _vm._s(kota.name) +
                                  "\n                            "
                              )
                            ]
                          )
                        })
                      ],
                      2
                    )
                  ],
                  1
                )
              ],
              1
            ),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "col-md-3 px-0" },
              [
                _c(
                  "b-form-group",
                  { staticClass: "w-auto m-1", attrs: { label: "Kecamatan" } },
                  [
                    _c(
                      "b-select",
                      {
                        attrs: { size: "md", disabled: _vm.isKoordinatorKec },
                        model: {
                          value: _vm.pilihKecamatan,
                          callback: function($$v) {
                            _vm.pilihKecamatan = $$v
                          },
                          expression: "pilihKecamatan"
                        }
                      },
                      [
                        _c("option", { attrs: { value: "Pilih" } }, [
                          _vm._v("Pilih")
                        ]),
                        _vm._v(" "),
                        _vm._l(_vm.kecamatanList, function(kecamatan, i) {
                          return _c(
                            "option",
                            {
                              key: i,
                              domProps: { value: kecamatan.kecamatan_id }
                            },
                            [
                              _vm._v(
                                "\n                                " +
                                  _vm._s(kecamatan.name) +
                                  "\n                            "
                              )
                            ]
                          )
                        })
                      ],
                      2
                    )
                  ],
                  1
                )
              ],
              1
            ),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "col-md-3 px-0" },
              [
                _c(
                  "b-form-group",
                  { staticClass: "w-auto m-1", attrs: { label: "Desa" } },
                  [
                    _c(
                      "b-select",
                      {
                        attrs: { size: "md" },
                        model: {
                          value: _vm.pilihDesa,
                          callback: function($$v) {
                            _vm.pilihDesa = $$v
                          },
                          expression: "pilihDesa"
                        }
                      },
                      [
                        _c("option", { attrs: { value: "Pilih" } }, [
                          _vm._v("Pilih")
                        ]),
                        _vm._v(" "),
                        _vm._l(_vm.desaList, function(desa, i) {
                          return _c(
                            "option",
                            { key: i, domProps: { value: desa.kelurahan_id } },
                            [
                              _vm._v(
                                "\n                                " +
                                  _vm._s(desa.name) +
                                  "\n                            "
                              )
                            ]
                          )
                        })
                      ],
                      2
                    )
                  ],
                  1
                )
              ],
              1
            )
          ])
        ]
      ),
      _vm._v(" "),
      _c(
        "b-modal",
        {
          ref: "modal-box",
          attrs: {
            centered: "",
            size: "lg",
            "no-close-on-esc": "",
            "no-close-on-backdrop": "",
            "hide-header-close": ""
          }
        },
        [
          _c("div", { attrs: { slot: "modal-title" }, slot: "modal-title" }, [
            _vm._v(
              "\n\t\t\t\t" +
                _vm._s(_vm.mode == "add" ? "Add" : "Update") +
                " Wilayah Kerja\n\t\t\t"
            )
          ]),
          _vm._v(" "),
          !_vm.penyuluh
            ? _c(
                "b-form-row",
                [
                  _c(
                    "b-form-group",
                    { staticClass: "col", attrs: { label: "Penyuluh" } },
                    [
                      _vm.mode == "add"
                        ? [
                            _c("multiselect", {
                              attrs: {
                                options: _vm.penyuluhList,
                                multiple: false,
                                "track-by": "id",
                                placeholder: "Pilih Penyuluh",
                                label: "nama"
                              },
                              model: {
                                value: _vm.pilihPenyuluh,
                                callback: function($$v) {
                                  _vm.pilihPenyuluh = $$v
                                },
                                expression: "pilihPenyuluh"
                              }
                            })
                          ]
                        : [
                            _c("b-input", {
                              attrs: { disabled: true },
                              model: {
                                value: _vm.penyuluhTerpilih,
                                callback: function($$v) {
                                  _vm.penyuluhTerpilih = $$v
                                },
                                expression: "penyuluhTerpilih"
                              }
                            })
                          ]
                    ],
                    2
                  )
                ],
                1
              )
            : _vm._e(),
          _vm._v(" "),
          _c(
            "b-form-row",
            [
              _c(
                "b-form-group",
                { staticClass: "col", attrs: { label: "Kota" } },
                [
                  _c(
                    "b-select",
                    {
                      attrs: { size: "md" },
                      model: {
                        value: _vm.formKotaId,
                        callback: function($$v) {
                          _vm.formKotaId = $$v
                        },
                        expression: "formKotaId"
                      }
                    },
                    [
                      _c("option", { attrs: { value: "Pilih" } }, [
                        _vm._v("Pilih")
                      ]),
                      _vm._v(" "),
                      _vm._l(_vm.kotaList, function(kota, i) {
                        return _c(
                          "option",
                          { key: i, domProps: { value: kota.kota_id } },
                          [
                            _vm._v(
                              "\n                            " +
                                _vm._s(kota.name) +
                                "\n                        "
                            )
                          ]
                        )
                      })
                    ],
                    2
                  )
                ],
                1
              )
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "b-form-row",
            [
              _c(
                "b-form-group",
                { staticClass: "col", attrs: { label: "Kecamatan" } },
                [
                  _c(
                    "b-select",
                    {
                      attrs: { size: "md" },
                      model: {
                        value: _vm.formKecamatanId,
                        callback: function($$v) {
                          _vm.formKecamatanId = $$v
                        },
                        expression: "formKecamatanId"
                      }
                    },
                    [
                      _c("option", { attrs: { value: "Pilih" } }, [
                        _vm._v("Pilih")
                      ]),
                      _vm._v(" "),
                      _vm._l(_vm.formKecamatanList, function(kecamatan, i) {
                        return _c(
                          "option",
                          {
                            key: i,
                            domProps: { value: kecamatan.kecamatan_id }
                          },
                          [
                            _vm._v(
                              "\n                            " +
                                _vm._s(kecamatan.name) +
                                "\n                        "
                            )
                          ]
                        )
                      })
                    ],
                    2
                  )
                ],
                1
              )
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "b-form-row",
            [
              _c(
                "b-form-group",
                { staticClass: "col", attrs: { label: "Desa" } },
                [
                  _c(
                    "b-select",
                    {
                      attrs: { size: "md" },
                      model: {
                        value: _vm.formDesaId,
                        callback: function($$v) {
                          _vm.formDesaId = $$v
                        },
                        expression: "formDesaId"
                      }
                    },
                    [
                      _c("option", { attrs: { value: "Pilih" } }, [
                        _vm._v("Pilih")
                      ]),
                      _vm._v(" "),
                      _vm._l(_vm.formDesaList, function(desa, i) {
                        return _c(
                          "option",
                          { key: i, domProps: { value: desa.kelurahan_id } },
                          [
                            _vm._v(
                              "\n                            " +
                                _vm._s(desa.name) +
                                "\n                        "
                            )
                          ]
                        )
                      })
                    ],
                    2
                  )
                ],
                1
              )
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "div",
            { attrs: { slot: "modal-footer" }, slot: "modal-footer" },
            [
              _c(
                "b-btn",
                {
                  directives: [
                    {
                      name: "b-tooltip",
                      rawName: "v-b-tooltip.hover",
                      modifiers: { hover: true }
                    }
                  ],
                  attrs: { variant: "secondary", title: "Close" },
                  on: {
                    click: function($event) {
                      return _vm.hideModal()
                    }
                  }
                },
                [_vm._v("Close")]
              ),
              _vm._v(" "),
              _c(
                "ladda-btn",
                {
                  staticClass: "btn btn-primary",
                  attrs: { loading: _vm.loading, "data-style": "zoom-out" },
                  nativeOn: {
                    click: function($event) {
                      return _vm.process($event)
                    }
                  }
                },
                [
                  _vm._v(
                    "\n\t\t\t\t\t" +
                      _vm._s(_vm.mode == "add" ? "Save" : "Update") +
                      "\n\t\t\t\t"
                  )
                ]
              )
            ],
            1
          )
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "sweet-modal",
        {
          ref: "deleteModalAlert",
          attrs: {
            icon: "warning",
            title: "Delete User",
            "hide-close-button": "",
            blocking: ""
          }
        },
        [
          _vm._v("\n\t\t\t" + _vm._s(_vm.titleDeleteModal) + "\n\t\t\t"),
          _c(
            "template",
            { staticStyle: { "text-align": "center" }, slot: "button" },
            [
              _c(
                "ladda-btn",
                {
                  staticClass: "btn btn-primary",
                  attrs: { loading: _vm.loading, "data-style": "zoom-out" },
                  nativeOn: {
                    click: function($event) {
                      return _vm.confirmDelete()
                    }
                  }
                },
                [_vm._v("Confirm")]
              ),
              _vm._v(" "),
              _c(
                "b-btn",
                {
                  attrs: { variant: "default" },
                  on: {
                    click: function($event) {
                      return _vm.$refs.deleteModalAlert.close()
                    }
                  }
                },
                [_vm._v("Cancel")]
              )
            ],
            1
          )
        ],
        2
      )
    ],
    1
  )
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "d-flex align-items-center" }, [
      _c(
        "h5",
        { staticClass: "m-1 d-inline-block text-nowrap font-weight-normal" },
        [
          _vm._v(
            "\n                    Master / Wilayah Kerja\n                "
          )
        ]
      )
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/assets/src/components/master/wilayahkerja/List.vue":
/*!**********************************************************************!*\
  !*** ./resources/assets/src/components/master/wilayahkerja/List.vue ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _List_vue_vue_type_template_id_31da7424___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./List.vue?vue&type=template&id=31da7424& */ "./resources/assets/src/components/master/wilayahkerja/List.vue?vue&type=template&id=31da7424&");
/* harmony import */ var _List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./List.vue?vue&type=script&lang=js& */ "./resources/assets/src/components/master/wilayahkerja/List.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var vue_multiselect_dist_vue_multiselect_min_css_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue-multiselect/dist/vue-multiselect.min.css?vue&type=style&index=0&lang=css& */ "./node_modules/vue-multiselect/dist/vue-multiselect.min.css?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _List_vue_vue_type_template_id_31da7424___WEBPACK_IMPORTED_MODULE_0__["render"],
  _List_vue_vue_type_template_id_31da7424___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/src/components/master/wilayahkerja/List.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/src/components/master/wilayahkerja/List.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************!*\
  !*** ./resources/assets/src/components/master/wilayahkerja/List.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./List.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/master/wilayahkerja/List.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/src/components/master/wilayahkerja/List.vue?vue&type=template&id=31da7424&":
/*!*****************************************************************************************************!*\
  !*** ./resources/assets/src/components/master/wilayahkerja/List.vue?vue&type=template&id=31da7424& ***!
  \*****************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_template_id_31da7424___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./List.vue?vue&type=template&id=31da7424& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/master/wilayahkerja/List.vue?vue&type=template&id=31da7424&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_template_id_31da7424___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_template_id_31da7424___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);