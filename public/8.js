(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[8],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/data/luas/List.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/data/luas/List.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _vendor_libs_ladda_Ladda__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/vendor/libs/ladda/Ladda */ "./resources/assets/src/vendor/libs/ladda/Ladda.vue");
/* harmony import */ var sweet_modal_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! sweet-modal-vue */ "./node_modules/sweet-modal-vue/src/main.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  name: 'list',
  metaInfo: {
    title: 'Data Luas'
  },
  components: {
    LaddaBtn: _vendor_libs_ladda_Ladda__WEBPACK_IMPORTED_MODULE_0__["default"],
    SweetModal: sweet_modal_vue__WEBPACK_IMPORTED_MODULE_1__["SweetModal"]
  },
  data: function data() {
    return {
      // Options
      sortBy: "tanggal",
      sortDesc: true,
      perPage: 10,
      fields: [{
        key: "id",
        label: "#",
        sortable: false,
        tdClass: "align-middle",
        thClass: "text-nowrap align-middle text-center"
      }, {
        key: "name",
        label: "Nama",
        sortable: false,
        tdClass: "text-nowrap align-middle",
        thClass: "text-nowrap align-middle text-center"
      }, {
        key: "nip",
        label: "NIP",
        sortable: false,
        tdClass: "align-middle",
        thClass: "text-nowrap align-middle text-center"
      }, {
        key: "status_penyuluh",
        label: "Status Penyuluh",
        sortable: false,
        tdClass: "align-middle",
        thClass: "text-nowrap align-middle text-center"
      }, {
        key: "kota",
        label: "Kota",
        sortable: false,
        tdClass: "align-middle",
        thClass: "text-nowrap align-middle text-center"
      }, {
        key: "kecamatan",
        label: "Kecamatan",
        sortable: false,
        tdClass: "align-middle",
        thClass: "text-nowrap align-middle text-center"
      }, {
        key: "desa",
        label: "Desa",
        sortable: false,
        tdClass: "align-middle",
        thClass: "text-nowrap align-middle text-center"
      }, {
        key: "tanggal",
        label: "Tanggal",
        sortable: false,
        tdClass: "text-nowrap align-middle",
        thClass: "text-nowrap align-middle text-center"
      }, {
        key: "jenis",
        label: "Jenis",
        sortable: false,
        tdClass: "align-middle",
        thClass: "text-nowrap align-middle text-center"
      }, {
        key: "komoditi_id",
        label: "Komoditas",
        sortable: false,
        tdClass: "align-middle",
        thClass: "text-nowrap align-middle text-center"
      }, {
        key: "luas_tanam",
        label: "Luas Tanam [Ha]",
        sortable: false,
        tdClass: "align-middle",
        thClass: "text-nowrap align-middle text-center"
      }, {
        key: "tambah_tanam",
        label: "Tambah Tanam [Ha]",
        sortable: false,
        tdClass: "align-middle",
        thClass: "text-nowrap align-middle text-center"
      }, {
        key: "produksi",
        label: "Produksi [Ton]",
        sortable: false,
        tdClass: "align-middle",
        thClass: "text-nowrap align-middle text-center"
      }, {
        key: "luas_panen",
        label: "Luas Panen [Ha]",
        sortable: false,
        tdClass: "align-middle",
        thClass: "text-nowrap align-middle text-center"
      }, {
        key: "produktivitas",
        label: "Produktivitas [Kw/Ha]",
        sortable: false,
        tdClass: "align-middle",
        thClass: "text-nowrap align-middle text-center"
      }, {
        key: "files",
        label: "Attachment",
        sortable: false,
        tdClass: "text-nowrap align-middle",
        thClass: "text-nowrap align-middle text-center"
      }, {
        key: "vrf_kota",
        label: "Vrf Kota/Kab",
        sortable: false,
        tdClass: "align-middle",
        thClass: "text-nowrap align-middle text-center"
      }, {
        key: "vrf_kec",
        label: "Vrf Kecamatan",
        sortable: false,
        tdClass: "align-middle",
        thClass: "text-nowrap align-middle text-center"
      }, {
        key: "actions",
        label: "Action",
        tdClass: "text-nowrap align-middle text-center",
        thClass: "text-nowrap align-middle text-center"
      }],
      loadParams: {},
      data: [],
      currentPage: 1,
      isBusy: false,
      saveData: {},
      formKotaId: "Pilih",
      formKecamatanId: "Pilih",
      formDesaId: "Pilih",
      formKecamatanList: [],
      formDesaList: [],
      loading: false,
      mode: "add",
      index: null,
      titleDeleteModal: "Are you sure?",
      pilihTahun: 'Pilih',
      pilihKota: 'Pilih',
      pilihKecamatan: 'Pilih',
      pilihDesa: 'Pilih',
      pilihJenis: 'Pilih',
      pilihKomoditas: 'Pilih',
      komoditasList: [],
      kotaList: [],
      kecamatanList: [],
      desaList: [],
      id: null,
      attachment: {
        luasTanam: null,
        tambahTanam: null,
        produksi: null,
        luasPanen: null
      },
      pilihBulan: 'Pilih',
      opsiBulan: {
        1: "Januari",
        2: "Februari",
        3: "Maret",
        4: "April",
        5: "Mei",
        6: "Juni",
        7: "Juli",
        8: "Agustus",
        9: "September",
        10: "Oktober",
        11: "November",
        12: "Desember"
      },
      listLevel: {},
      hasAccess: {},
      isKoordinatorKota: false,
      isKoordinatorKec: false,
      isVrf: "Kota"
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
    tahunList: function tahunList() {
      var min_year = 2016;
      var max_year = new Date().getFullYear();
      var year_option = [];
      year_option.push('Pilih');

      for (var i = max_year; i >= min_year; i--) {
        year_option.push(i);
      }

      return year_option;
    },
    penyuluh: function penyuluh() {
      var user = this.$store.state.auth.user;

      if (user.penyuluh != null) {
        return true;
      }

      return false;
    },
    currentDate: function currentDate() {
      var curDate = new Date().toJSON().slice(0, 10).replace(/-/g, '-');
      return curDate;
    }
  },
  watch: {
    currentPage: function currentPage(v) {
      this.loadData(v);
    },
    perPage: function perPage(v) {
      this.loadData();
    },
    pilihTahun: function pilihTahun() {
      this.loadData();
    },
    pilihBulan: function pilihBulan() {
      this.loadData();
    },
    pilihKomoditas: function pilihKomoditas() {
      this.loadData();
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
    pilihJenis: function pilihJenis() {
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
    }
  },
  methods: {
    loadData: function loadData() {
      var _this = this;

      var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 1;
      this.isBusy = true;
      this.loadParams.page = page;
      this.loadParams.order_by = this.sortBy;
      this.loadParams.sort_desc = this.sortDesc;
      this.loadParams.limit = this.perPage;
      this.loadParams.params = {};

      if (this.pilihTahun != "Pilih") {
        this.loadParams.params.tahun = this.pilihTahun;
      }

      if (this.pilihBulan != "Pilih") {
        this.loadParams.params.bulan = this.pilihBulan;
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

      if (this.pilihJenis != "Pilih") {
        this.loadParams.params.jenis = this.pilihJenis;
      }

      if (this.pilihKomoditas != "Pilih") {
        this.loadParams.params.komoditi_id = this.pilihKomoditas;
      }

      axios.get(this.publicUrl + "api/dataluas", {
        params: this.loadParams
      }).then(function (response) {
        // handle success
        _this.data = response.data;
        _this.isBusy = false;
      })["catch"](function (error) {
        _this.swr();
      });
    },
    download: function download() {
      var _this2 = this;

      this.loading = true;
      var params = {};
      params.order_by = this.sortBy;
      params.sort_desc = this.sortDesc;
      params.params = {};

      if (this.pilihTahun != "Pilih") {
        params.params.tahun = this.pilihTahun;
      }

      if (this.pilihBulan != "Pilih") {
        params.params.bulan = this.pilihBulan;
      }

      if (this.pilihKota != "Pilih") {
        params.params.kota_id = this.pilihKota;
      }

      if (this.pilihKecamatan != "Pilih") {
        params.params.kecamatan_id = this.pilihKecamatan;
      }

      if (this.pilihDesa != "Pilih") {
        params.params.kelurahan_id = this.pilihDesa;
      }

      if (this.pilihJenis != "Pilih") {
        params.params.jenis = this.pilihJenis;
      }

      if (this.pilihKomoditas != "Pilih") {
        params.params.komoditi_id = this.pilihKomoditas;
      }

      params.user_id = this.user.id;
      this.notif("info", "Success", "Data sedang disiapkan, silakan cek secara berkala di menu download");
      axios({
        method: "post",
        url: this.publicUrl + "api/dataluas/download",
        data: params
      })["catch"](function (error) {
        if (error.response.status == 422) {
          for (var i in error.response.data.errors) {
            _this2.notif("danger", "Error", error.response.data.errors[i][0]);
          }
        } else if (error.response.status == 400) {
          _this2.notif("warning", "Error", error.response.data.message);
        } else {
          _this2.swr();
        }
      });
    },
    // load parameter dari store state
    cekParam: function cekParam() {
      var _this3 = this;

      if (this.$route.params.id) {
        axios.get(this.publicUrl + "api/wilayahkerja/" + this.$route.params.id).then(function (response) {
          _this3.pilihKota = _this3.formKotaId = response.data.data.kota_id;
          _this3.pilihKecamatan = _this3.formKecamatanId = response.data.data.kecamatan_id;
          _this3.pilihDesa = _this3.formDesaId = response.data.data.kelurahan_id; // handle success
          // this.data = response.data;
        })["catch"](function (error) {
          _this3.swr();
        });
      }

      if (this.$route.params.tahun) {
        this.pilihTahun = this.saveData.tahun = this.$route.params.tahun;
      }
    },
    loadListKomoditas: function loadListKomoditas() {
      var _this4 = this;

      axios.get(this.publicUrl + "api/komoditas").then(function (response) {
        // handle success
        _this4.komoditasList = response.data.data;
      })["catch"](function (error) {
        _this4.swr();
      });
    },
    loadListKota: function loadListKota() {
      var _this5 = this;

      axios.get(this.publicUrl + "api/kota").then(function (response) {
        // handle success
        _this5.kotaList = response.data.data;
      })["catch"](function (error) {
        _this5.swr();
      });
    },
    loadListKecamatan: function loadListKecamatan() {
      var _this6 = this;

      var show = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'list';

      if (show == 'list') {
        axios.get(this.publicUrl + "api/kecamatan", {
          params: {
            kota_id: this.pilihKota
          }
        }).then(function (response) {
          // handle success
          _this6.kecamatanList = response.data.data;
        })["catch"](function (error) {
          _this6.swr();
        });
      } else {
        axios.get(this.publicUrl + "api/kecamatan", {
          params: {
            kota_id: this.formKotaId
          }
        }).then(function (response) {
          // handle success
          _this6.formKecamatanList = response.data.data;
        })["catch"](function (error) {
          _this6.swr();
        });
      }
    },
    // load list data desa
    loadListDesa: function loadListDesa() {
      var _this7 = this;

      var show = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : "list";

      if (show == "list") {
        axios.get(this.publicUrl + "api/desa", {
          params: {
            kota_id: this.pilihKota,
            kecamatan_id: this.pilihKecamatan
          }
        }).then(function (response) {
          // handle success
          _this7.desaList = response.data.data;
        })["catch"](function (error) {
          _this7.swr();
        });
      } else {
        axios.get(this.publicUrl + "api/desa", {
          params: {
            kota_id: this.formKotaId,
            kecamatan_id: this.formKecamatanId
          }
        }).then(function (response) {
          // handle success
          _this7.formDesaList = response.data.data;
        })["catch"](function (error) {
          _this7.swr();
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
        this.saveData = {
          tanggal: data.tanggal,
          jenis: data.jenis,
          komoditi_id: data.komoditi_id,
          luas_tanam: data.luas_tanam,
          luas_panen: data.luas_panen,
          produksi: data.produksi,
          tambah_tanam: data.tambah_tanam,
          ket_file_luas_tanam: data.ket_file_luas_tanam,
          ket_file_tambah_tanam: data.ket_file_tambah_tanam,
          ket_file_luas_panen: data.ket_file_luas_panen,
          ket_file_produksi: data.ket_file_produksi,
          lat: data.lat,
          "long": data["long"]
        };
        this.formKotaId = data.kota_id;
        this.formKecamatanId = data.kecamatan_id;
        this.formDesaId = data.kelurahan_id;
        this.id = data.id;
      } else {
        this.saveData = {
          jenis: "Pilih",
          komoditi_id: "Pilih",
          tanggal: this.currentDate,
          luas_tanam: 0,
          tambah_tanam: 0,
          produksi: 0,
          luas_panen: 0,
          lat: null,
          "long": null,
          ket_file_luas_tanam: "",
          ket_file_tambah_tanam: "",
          ket_file_luas_panen: "",
          ket_file_produksi: ""
        };

        if (!this.penyuluh) {
          this.formKotaId = "Pilih";
          this.formKecamatanId = "Pilih";
          this.formDesaId = "Pilih";
        }

        this.id = null;
      }
    },
    // Close modal add/edit baku lahan
    hideModal: function hideModal() {
      this.$refs["modal-box"].hide();
    },
    // get titik lokasi penginputan
    getLocation: function getLocation() {
      var _this8 = this;

      this.$getLocation().then(function (coordinates) {
        _this8.saveData.lat = coordinates.lat;
        _this8.saveData["long"] = coordinates.lng;
      });
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
      var _this9 = this;

      if (!this.validate()) {
        return;
      }

      this.loading = true;
      this.saveData.kota_id = this.formKotaId;
      this.saveData.kecamatan_id = this.formKecamatanId;
      this.saveData.kelurahan_id = this.formDesaId;
      this.saveData.user_id = this.user.id;
      axios({
        method: "post",
        url: this.publicUrl + "api/dataluas",
        data: this.saveData
      }).then(function (response) {
        _this9.id = response.data.data.id;

        _this9.uploadFile();

        _this9.notif("success", "Success", "Data berhasil ditambahkan");

        _this9.hideModal();

        var obj = {};
        _this9.saveData = obj;
        _this9.attachment = {
          luasTanam: {},
          tambahTanam: {},
          produksi: {},
          luasPanen: {}
        }; // reload data

        _this9.loadData();
      })["catch"](function (error) {
        if (error.response.status == 422) {
          for (var i in error.response.data.errors) {
            _this9.notif("danger", "Error", error.response.data.errors[i][0]);
          }
        } else if (error.response.status == 400) {
          _this9.notif("warning", "Error", error.response.data.message);
        } else {
          _this9.swr();
        }
      }).then(function () {
        _this9.loading = false;
      });
    },
    // Proses update data
    update: function update() {
      var _this10 = this;

      if (!this.validate()) {
        return;
      }

      this.loading = true;
      this.saveData.kota_id = this.formKotaId;
      this.saveData.kecamatan_id = this.formKecamatanId;
      this.saveData.kelurahan_id = this.formDesaId;
      this.saveData.user_id = this.user.id;
      axios({
        method: "put",
        url: this.publicUrl + "api/dataluas/" + this.id,
        data: this.saveData
      }).then(function (response) {
        _this10.uploadFile();

        _this10.notif("success", "Success", "Data berhasil diperbaharui.");

        _this10.hideModal();

        var obj = {};
        _this10.saveData = obj;
        _this10.attachment = {
          luasTanam: {},
          tambahTanam: {},
          produksi: {},
          luasPanen: {}
        }; // reload data

        _this10.loadData();
      })["catch"](function (error) {
        if (error.response.status == 422) {
          for (var i in error.response.data.errors) {
            _this10.notif("danger", "Error", error.response.data.errors[i][0]);
          }
        } else if (error.response.status == 400) {
          _this10.notif("warning", "Error", error.response.data.message);
        } else {
          _this10.swr();
        }
      }).then(function () {
        _this10.loading = false;
      });
    },
    // upload file
    uploadFile: function uploadFile() {
      var _this11 = this;

      if (this.attachment.luasTanam == null && this.attachment.tambahTanam == null && this.attachment.luasPanen == null && this.attachment.produksi == null) {
        return true;
      }

      console.log(this.attachment);
      var formData = new FormData();

      if (this.attachment.luasTanam != null) {
        formData.append('luas_tanam', this.attachment.luasTanam);
      }

      if (this.attachment.tambahTanam != null) {
        formData.append('tambah_tanam', this.attachment.tambahTanam);
      }

      if (this.attachment.luasPanen != null) {
        formData.append('luas_panen', this.attachment.luasPanen);
      }

      if (this.attachment.produksi != null) {
        formData.append('produksi', this.attachment.produksi);
      }

      formData.append('id', this.id);
      axios.post(this.publicUrl + "api/dataluas/file", formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(function () {
        _this11.id = null;
      });
    },
    // Validati input data
    validate: function validate() {
      if (this.formKotaId == "Pilih") {
        this.notif("danger", "Error", "Kota belum dipilih.");
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

      if (this.saveData.tanggal_input == "") {
        this.notif("danger", "Error", "Tanggal belum dipilih.");
        return false;
      }

      if (this.saveData.jenis == "Pilih") {
        this.notif("danger", "Error", "Jenis belum dipilih.");
        return false;
      }

      if (this.saveData.komoditi_id == "Pilih") {
        this.notif("danger", "Error", "Komoditas belum dipilih.");
        return false;
      }

      if (this.saveData.luas_tanam <= 0 && this.saveData.luas_panen <= 0 && this.saveData.produksi <= 0 && this.saveData.tambah_tanam <= 0) {
        this.notif("danger", "Error", "Isi salah satu nilai antara Luas Tanam / Tambah Tanam / Produksi / Luas Panen.");
        return false;
      }

      if (this.saveData.lat == null || this.saveData["long"] == null) {
        this.notif("danger", "Error", "Anda belum menentukan titik lokasi.");
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
      var _this12 = this;

      this.loading = true;
      axios({
        method: "delete",
        url: this.publicUrl + "api/dataluas/" + this.id
      }).then(function (response) {
        _this12.notif("success", "Success", "Data berhasil dihapus.");

        _this12.$refs.deleteModalAlert.close(); // reload data


        _this12.loadData();
      })["catch"](function (error) {
        if (error.response.status == 400) {
          _this12.notif("warning", "Error", error.response.data.message);
        } else {
          _this12.swr();
        }
      }).then(function () {
        _this12.loading = false;
      });
    },
    // Load level dan ACL
    loadLevel: function loadLevel() {
      var _this13 = this;

      axios.get(this.publicUrl + "api/level").then(function (response) {
        // handle success
        _this13.listLevel = response.data.data;

        _this13.loadAcl();
      });
    },
    loadAcl: function loadAcl() {
      var _this14 = this;

      if (this.user) {
        if (this.user.level != 0) {
          this.hasAccess = {
            add: false,
            edit: false,
            "delete": false,
            verkota: false,
            verkec: false
          };
          var data_rule = this.listLevel.find(function (o) {
            return o.level === _this14.user.level;
          });

          if (data_rule.rule.find(function (o) {
            return o.name === 'data_luas_add';
          })) {
            this.hasAccess.add = data_rule.rule.find(function (o) {
              return o.name === 'data_luas_add';
            }).active;
          }

          if (data_rule.rule.find(function (o) {
            return o.name === 'data_luas_edit';
          })) {
            this.hasAccess.edit = data_rule.rule.find(function (o) {
              return o.name === 'data_luas_edit';
            }).active;
          }

          if (data_rule.rule.find(function (o) {
            return o.name === 'data_luas_delete';
          })) {
            this.hasAccess["delete"] = data_rule.rule.find(function (o) {
              return o.name === 'data_luas_delete';
            }).active;
          }

          if (data_rule.rule.find(function (o) {
            return o.name === 'data_luas_ver_kota';
          })) {
            this.hasAccess.verkota = data_rule.rule.find(function (o) {
              return o.name === 'data_luas_ver_kota';
            }).active;
          }

          if (data_rule.rule.find(function (o) {
            return o.name === 'data_luas_ver_kec';
          })) {
            this.hasAccess.verkec = data_rule.rule.find(function (o) {
              return o.name === 'data_luas_ver_kec';
            }).active;
          }
        } else {
          this.hasAccess = {
            add: true,
            edit: true,
            "delete": true,
            verkota: true,
            verkec: true
          };
        }
      }
    },
    // Verifikasi Data
    verifikasi: function verifikasi(i, tingkat, val) {
      var _this15 = this;

      var data = this.data.data[i];
      var saveData = {
        user_id: this.user.id
      };

      if (tingkat == 'kota') {
        saveData.vrf_kota = val;
        data.vrf_kota = val;
      } else {
        saveData.vrf_kec = val;
        data.vrf_kec = val;
      }

      axios({
        method: "post",
        url: this.publicUrl + "api/dataluas/verifikasi/" + data.id,
        data: saveData
      }).then(function (response) {
        _this15.notif("success", "Success", "Update verifikasi berhasil.");
      })["catch"](function (error) {
        if (error.response.status == 422) {
          for (var _i in error.response.data.errors) {
            _this15.notif("danger", "Error", error.response.data.errors[_i][0]);
          }
        } else if (error.response.status == 400) {
          _this15.notif("warning", "Error", error.response.data.message);
        } else {
          _this15.swr();
        }
      });
    },
    loadKorwil: function loadKorwil() {
      var _this16 = this;

      if (this.user) {
        var user = this.user;

        if (user.level == 96) {
          axios.get(this.publicUrl + "api/korwil/byUser/" + user.id).then(function (response) {
            // handle success
            _this16.isKoordinatorKota = true;
            _this16.pilihKota = _this16.formKotaId = response.data.data.kota_id;
          })["catch"](function (error) {
            _this16.swr();
          });
        }

        if (user.level == 95) {
          axios.get(this.publicUrl + "api/korwil/byUser/" + user.id).then(function (response) {
            // handle success
            _this16.isKoordinatorKota = true;
            _this16.isKoordinatorKec = true;
            _this16.pilihKota = _this16.formKotaId = response.data.data.kota_id;
            _this16.pilihKecamatan = _this16.formKecamatanId = response.data.data.kecamatan_id;
          })["catch"](function (error) {
            _this16.swr();
          });
        }
      }
    },
    showModalVrf: function showModalVrf() {
      var tingkat = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'kec';

      if (tingkat == 'kota') {
        this.isVrf = "Kota";
      } else {
        this.isVrf = "Kecamatan";
      }

      this.$refs["modal-verifikasi"].show();
    },
    hideModalVrf: function hideModalVrf() {
      this.$refs["modal-verifikasi"].hide();
    },
    processVrf: function processVrf() {
      var _this17 = this;

      this.loading = true;
      var params = {};
      params.params = {};

      if (this.pilihTahun != "Pilih") {
        params.params.tahun = this.pilihTahun;
      }

      if (this.pilihBulan != "Pilih") {
        params.params.bulan = this.pilihBulan;
      }

      if (this.pilihKota != "Pilih") {
        params.params.kota_id = this.pilihKota;
      }

      if (this.pilihKecamatan != "Pilih") {
        params.params.kecamatan_id = this.pilihKecamatan;
      }

      if (this.pilihDesa != "Pilih") {
        params.params.kelurahan_id = this.pilihDesa;
      }

      if (this.pilihJenis != "Pilih") {
        params.params.jenis = this.pilihJenis;
      }

      if (this.pilihKomoditas != "Pilih") {
        params.params.komoditi_id = this.pilihKomoditas;
      }

      if (this.isVrf == "Kota") {
        params.vrf_kota = 1;
        params.params.vrf_kota = 0;
      } else {
        params.vrf_kec = 1;
        params.params.vrf_kec = 0;
      }

      params.user_id = this.user.id;
      axios({
        method: "post",
        url: this.publicUrl + "api/dataluas/vrf_all",
        data: params
      }).then(function (response) {
        _this17.notif("success", "Success", "Data berhasil diverifikasi");

        _this17.hideModalVrf(); // reload data


        _this17.loadData();
      })["catch"](function (error) {
        if (error.response.status == 422) {
          for (var i in error.response.data.errors) {
            _this17.notif("danger", "Error", error.response.data.errors[i][0]);
          }
        } else if (error.response.status == 400) {
          _this17.notif("warning", "Error", error.response.data.message);
        } else {
          _this17.swr();
        }
      }).then(function () {
        _this17.loading = false;
      });
    }
  },
  created: function created() {
    this.loadLevel();
    this.cekParam();
    this.loadListKota();
    this.loadListKomoditas();
    this.loadData();
    this.loadKorwil();
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/data/luas/List.vue?vue&type=template&id=c38129f2&":
/*!*************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/assets/src/components/data/luas/List.vue?vue&type=template&id=c38129f2& ***!
  \*************************************************************************************************************************************************************************************************************************/
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
              _vm.hasAccess.verkec
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
                        title: "Verifikasi Tingkat Kecamatan",
                        size: "sm"
                      },
                      on: {
                        click: function($event) {
                          return _vm.showModalVrf("kec")
                        }
                      }
                    },
                    [
                      _c("i", { staticClass: "ion ion-md-checkmark" }),
                      _vm._v(
                        " " +
                          _vm._s(
                            _vm.isKoordinatorKec
                              ? "Verifikasi"
                              : "Verifikasi Kecamatan"
                          ) +
                          "\n\t\t\t\t"
                      )
                    ]
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.hasAccess.verkota
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
                        title: "Verifikasi Tingkat Kota",
                        size: "sm"
                      },
                      on: {
                        click: function($event) {
                          return _vm.showModalVrf("kota")
                        }
                      }
                    },
                    [
                      _c("i", { staticClass: "ion ion-md-checkmark" }),
                      _vm._v(
                        " " +
                          _vm._s(
                            _vm.isKoordinatorKota
                              ? "Verifikasi"
                              : "Verifikasi Kota"
                          ) +
                          "\n\t\t\t\t"
                      )
                    ]
                  )
                : _vm._e(),
              _vm._v(" "),
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
            ],
            1
          )
        ]
      ),
      _vm._v(" "),
      _c("div", { staticClass: "row pt-3" }, [
        _c(
          "div",
          { staticClass: "col-md-2" },
          [
            _c("b-card", { staticClass: "mb-4" }, [
              _c("p", { staticClass: "card-text" }, [
                _vm._v("Total Luas Tanam")
              ]),
              _vm._v(" "),
              _c("h4", { staticClass: "card-title" }, [
                _vm._v(_vm._s(_vm.data.total_luas_tanam) + " Ha")
              ])
            ])
          ],
          1
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "col-md-2" },
          [
            _c("b-card", { staticClass: "mb-4" }, [
              _c("p", { staticClass: "card-text" }, [
                _vm._v("Total Tambah Tanam")
              ]),
              _vm._v(" "),
              _c("h4", { staticClass: "card-title" }, [
                _vm._v(_vm._s(_vm.data.total_tambah_tanam) + " Ha")
              ])
            ])
          ],
          1
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "col-md-2" },
          [
            _c("b-card", { staticClass: "mb-4" }, [
              _c("p", { staticClass: "card-text" }, [_vm._v("Total Produksi")]),
              _vm._v(" "),
              _c("h4", { staticClass: "card-title" }, [
                _vm._v(_vm._s(_vm.data.total_produksi) + " Ton")
              ])
            ])
          ],
          1
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "col-md-2" },
          [
            _c("b-card", { staticClass: "mb-4" }, [
              _c("p", { staticClass: "card-text" }, [
                _vm._v("Total Luas Panen")
              ]),
              _vm._v(" "),
              _c("h4", { staticClass: "card-title" }, [
                _vm._v(_vm._s(_vm.data.total_luas_panen) + " Ha")
              ])
            ])
          ],
          1
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "col-md-2" },
          [
            _c("b-card", { staticClass: "mb-4" }, [
              _c("p", { staticClass: "card-text" }, [
                _vm._v("Total Produktivitas")
              ]),
              _vm._v(" "),
              _c("h4", { staticClass: "card-title" }, [
                _vm._v(_vm._s(_vm.data.total_produktivitas) + " Kw/Ha")
              ])
            ])
          ],
          1
        )
      ]),
      _vm._v(" "),
      _c(
        "b-card",
        { attrs: { "no-body": "" } },
        [
          _c(
            "div",
            { staticClass: "table-responsive" },
            [
              _c("b-table", {
                staticClass: "card-table",
                attrs: {
                  items: _vm.data.data,
                  fields: _vm.fields,
                  striped: true,
                  bordered: true,
                  "current-page": 1,
                  "per-page": _vm.perPage,
                  busy: _vm.isBusy
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
                    key: "cell(jenis)",
                    fn: function(data) {
                      return [
                        data.item.jenis == 1
                          ? _c("label", [
                              _vm._v(
                                "\n                            Sawah\n                        "
                              )
                            ])
                          : _c("label", [
                              _vm._v(
                                "\n                            Ladang\n                        "
                              )
                            ])
                      ]
                    }
                  },
                  {
                    key: "cell(komoditi_id)",
                    fn: function(data) {
                      return [
                        _vm._v(
                          "\n                        " +
                            _vm._s(data.item.komoditi) +
                            "\n                    "
                        )
                      ]
                    }
                  },
                  {
                    key: "cell(vrf_kota)",
                    fn: function(data) {
                      return [
                        _vm.hasAccess.verkota
                          ? _c(
                              "div",
                              [
                                _c(
                                  "b-dropdown",
                                  {
                                    attrs: {
                                      variant:
                                        (data.item.vrf_kota == 1
                                          ? "success"
                                          : "danger") + " btn-sm md-btn-flat"
                                    }
                                  },
                                  [
                                    _c("template", { slot: "button-content" }, [
                                      data.item.vrf_kota === 1
                                        ? _c("span", [_vm._v("Sudah")])
                                        : _vm._e(),
                                      _vm._v(" "),
                                      data.item.vrf_kota === 0
                                        ? _c("span", [_vm._v("Belum")])
                                        : _vm._e()
                                    ]),
                                    _vm._v(" "),
                                    data.item.vrf_kota == 0
                                      ? _c(
                                          "b-dropdown-item",
                                          {
                                            on: {
                                              click: function($event) {
                                                return _vm.verifikasi(
                                                  data.index,
                                                  "kota",
                                                  1
                                                )
                                              }
                                            }
                                          },
                                          [
                                            _c("i", {
                                              staticClass:
                                                "ion ion-md-checkmark-circle-outline text-success"
                                            }),
                                            _vm._v("   Verifikasi")
                                          ]
                                        )
                                      : _vm._e(),
                                    _vm._v(" "),
                                    data.item.vrf_kota == 1
                                      ? _c(
                                          "b-dropdown-item",
                                          {
                                            on: {
                                              click: function($event) {
                                                return _vm.verifikasi(
                                                  data.index,
                                                  "kota",
                                                  0
                                                )
                                              }
                                            }
                                          },
                                          [
                                            _c("i", {
                                              staticClass:
                                                "ion ion-md-close-circle-outline text-danger"
                                            }),
                                            _vm._v("   Batalkan")
                                          ]
                                        )
                                      : _vm._e()
                                  ],
                                  2
                                )
                              ],
                              1
                            )
                          : _c(
                              "div",
                              [
                                data.item.vrf_kota == 0
                                  ? _c(
                                      "b-badge",
                                      { attrs: { variant: "danger" } },
                                      [
                                        _vm._v(
                                          "\n                                Belum\n                            "
                                        )
                                      ]
                                    )
                                  : _c(
                                      "b-badge",
                                      { attrs: { variant: "success" } },
                                      [
                                        _vm._v(
                                          "\n                                Sudah\n                            "
                                        )
                                      ]
                                    )
                              ],
                              1
                            )
                      ]
                    }
                  },
                  {
                    key: "cell(vrf_kec)",
                    fn: function(data) {
                      return [
                        _vm.hasAccess.verkec
                          ? _c(
                              "div",
                              [
                                _c(
                                  "b-dropdown",
                                  {
                                    attrs: {
                                      variant:
                                        (data.item.vrf_kec == 1
                                          ? "success"
                                          : "danger") + " btn-sm md-btn-flat"
                                    }
                                  },
                                  [
                                    _c("template", { slot: "button-content" }, [
                                      data.item.vrf_kec === 1
                                        ? _c("span", [_vm._v("Sudah")])
                                        : _vm._e(),
                                      _vm._v(" "),
                                      data.item.vrf_kec === 0
                                        ? _c("span", [_vm._v("Belum")])
                                        : _vm._e()
                                    ]),
                                    _vm._v(" "),
                                    data.item.vrf_kec == 0
                                      ? _c(
                                          "b-dropdown-item",
                                          {
                                            on: {
                                              click: function($event) {
                                                return _vm.verifikasi(
                                                  data.index,
                                                  "kec",
                                                  1
                                                )
                                              }
                                            }
                                          },
                                          [
                                            _c("i", {
                                              staticClass:
                                                "ion ion-md-checkmark-circle-outline text-success"
                                            }),
                                            _vm._v("   Verifikasi")
                                          ]
                                        )
                                      : _vm._e(),
                                    _vm._v(" "),
                                    data.item.vrf_kec == 1
                                      ? _c(
                                          "b-dropdown-item",
                                          {
                                            on: {
                                              click: function($event) {
                                                return _vm.verifikasi(
                                                  data.index,
                                                  "kec",
                                                  0
                                                )
                                              }
                                            }
                                          },
                                          [
                                            _c("i", {
                                              staticClass:
                                                "ion ion-md-close-circle-outline text-danger"
                                            }),
                                            _vm._v("   Batalkan")
                                          ]
                                        )
                                      : _vm._e()
                                  ],
                                  2
                                )
                              ],
                              1
                            )
                          : _c(
                              "div",
                              [
                                data.item.vrf_kec == 0
                                  ? _c(
                                      "b-badge",
                                      { attrs: { variant: "danger" } },
                                      [
                                        _vm._v(
                                          "\n                                Belum\n                            "
                                        )
                                      ]
                                    )
                                  : _c(
                                      "b-badge",
                                      { attrs: { variant: "success" } },
                                      [
                                        _vm._v(
                                          "\n                                Sudah\n                            "
                                        )
                                      ]
                                    )
                              ],
                              1
                            )
                      ]
                    }
                  },
                  {
                    key: "cell(files)",
                    fn: function(data) {
                      return _vm._l(data.item.files, function(file, i) {
                        return _c("div", { key: i }, [
                          _c(
                            "a",
                            { attrs: { href: file.url, target: "_blank" } },
                            [_vm._v(_vm._s(file.file))]
                          )
                        ])
                      })
                    }
                  },
                  {
                    key: "cell(actions)",
                    fn: function(data) {
                      return [
                        data.item.vrf_kota == 0 && data.item.vrf_kec == 0
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
                                  title: "Edit Data Luas"
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
                        data.item.vrf_kota == 0 && data.item.vrf_kec == 0
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
                                  title: "Hapus Data Luas"
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
              { staticClass: "col-md-4 px-0" },
              [
                _c(
                  "b-form-group",
                  { staticClass: "w-auto m-1", attrs: { label: "Kota" } },
                  [
                    _c(
                      "b-select",
                      {
                        attrs: {
                          size: "md",
                          disabled: _vm.penyuluh || _vm.isKoordinatorKota
                        },
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
              { staticClass: "col-md-4 px-0" },
              [
                _c(
                  "b-form-group",
                  { staticClass: "w-auto m-1", attrs: { label: "Kecamatan" } },
                  [
                    _c(
                      "b-select",
                      {
                        attrs: {
                          size: "md",
                          disabled: _vm.penyuluh || _vm.isKoordinatorKec
                        },
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
              { staticClass: "col-md-4 px-0" },
              [
                _c(
                  "b-form-group",
                  { staticClass: "w-auto m-1", attrs: { label: "Desa" } },
                  [
                    _c(
                      "b-select",
                      {
                        attrs: { size: "md", disabled: _vm.penyuluh },
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
          ]),
          _vm._v(" "),
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
              { staticClass: "col-md-2 px-0" },
              [
                _c(
                  "b-form-group",
                  { staticClass: "w-auto m-1", attrs: { label: "Tahun" } },
                  [
                    _c("b-select", {
                      attrs: { size: "md", options: _vm.tahunList },
                      model: {
                        value: _vm.pilihTahun,
                        callback: function($$v) {
                          _vm.pilihTahun = $$v
                        },
                        expression: "pilihTahun"
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
              { staticClass: "col-md-2 px-0" },
              [
                _c(
                  "b-form-group",
                  { staticClass: "w-auto m-1", attrs: { label: "Bulan" } },
                  [
                    _c(
                      "b-select",
                      {
                        attrs: { size: "md" },
                        model: {
                          value: _vm.pilihBulan,
                          callback: function($$v) {
                            _vm.pilihBulan = $$v
                          },
                          expression: "pilihBulan"
                        }
                      },
                      [
                        _c("option", { attrs: { value: "Pilih" } }, [
                          _vm._v("Pilih")
                        ]),
                        _vm._v(" "),
                        _vm._l(_vm.opsiBulan, function(bulan, i) {
                          return _c(
                            "option",
                            { key: i, domProps: { value: i } },
                            [
                              _vm._v(
                                "\n                                " +
                                  _vm._s(bulan) +
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
              { staticClass: "col-md-2 px-0" },
              [
                _c(
                  "b-form-group",
                  { staticClass: "w-auto m-1", attrs: { label: "Jenis" } },
                  [
                    _c(
                      "b-select",
                      {
                        attrs: { size: "md" },
                        model: {
                          value: _vm.pilihJenis,
                          callback: function($$v) {
                            _vm.pilihJenis = $$v
                          },
                          expression: "pilihJenis"
                        }
                      },
                      [
                        _c("option", { attrs: { value: "Pilih" } }, [
                          _vm._v("Pilih")
                        ]),
                        _vm._v(" "),
                        _c("option", { attrs: { value: "1" } }, [
                          _vm._v("Sawah")
                        ]),
                        _vm._v(" "),
                        _c("option", { attrs: { value: "2" } }, [
                          _vm._v("Ladang")
                        ])
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
              "div",
              { staticClass: "col-md-3 px-0" },
              [
                _c(
                  "b-form-group",
                  { staticClass: "w-auto m-1", attrs: { label: "Komoditas" } },
                  [
                    _c(
                      "b-select",
                      {
                        attrs: { size: "md" },
                        model: {
                          value: _vm.pilihKomoditas,
                          callback: function($$v) {
                            _vm.pilihKomoditas = $$v
                          },
                          expression: "pilihKomoditas"
                        }
                      },
                      [
                        _c("option", { attrs: { value: "Pilih" } }, [
                          _vm._v("Pilih")
                        ]),
                        _vm._v(" "),
                        _vm._l(_vm.komoditasList, function(komoditas, i) {
                          return _c(
                            "option",
                            { key: i, domProps: { value: komoditas.id } },
                            [
                              _vm._v(
                                "\n                                " +
                                  _vm._s(komoditas.nama) +
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
              { staticClass: "col-md-2 px-0 text-right" },
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
                    staticClass: "mt-3",
                    attrs: { variant: "success", title: "Download Data" },
                    on: {
                      click: function($event) {
                        return _vm.download()
                      }
                    }
                  },
                  [
                    _c("i", { staticClass: "ion ion-md-download" }),
                    _vm._v(" Download\n                    ")
                  ]
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
                " Data Luas\n\t\t\t"
            )
          ]),
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
                      attrs: { size: "md", disabled: _vm.penyuluh },
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
                      attrs: { size: "md", disabled: _vm.penyuluh },
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
                      attrs: { size: "md", disabled: _vm.penyuluh },
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
            "b-form-row",
            [
              _c(
                "b-form-group",
                {
                  staticClass: "d-inline-block w-auto m-1",
                  attrs: { label: "Tanggal" }
                },
                [
                  _c("b-datepicker", {
                    attrs: {
                      size: "md",
                      placeholder: "Pilih Tanggal",
                      locale: "id",
                      max: _vm.currentDate,
                      min: "2016-01-01"
                    },
                    model: {
                      value: _vm.saveData.tanggal,
                      callback: function($$v) {
                        _vm.$set(_vm.saveData, "tanggal", $$v)
                      },
                      expression: "saveData.tanggal"
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
            "b-form-row",
            [
              _c(
                "b-form-group",
                {
                  staticClass: "d-inline-block w-auto m-1",
                  attrs: { label: "Jenis" }
                },
                [
                  _c(
                    "b-select",
                    {
                      attrs: { size: "md" },
                      model: {
                        value: _vm.saveData.jenis,
                        callback: function($$v) {
                          _vm.$set(_vm.saveData, "jenis", $$v)
                        },
                        expression: "saveData.jenis"
                      }
                    },
                    [
                      _c("option", { attrs: { value: "Pilih" } }, [
                        _vm._v("Pilih")
                      ]),
                      _vm._v(" "),
                      _c("option", { attrs: { value: "1" } }, [
                        _vm._v("Sawah")
                      ]),
                      _vm._v(" "),
                      _c("option", { attrs: { value: "2" } }, [
                        _vm._v("Ladang")
                      ])
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
            "b-form-row",
            [
              _c(
                "b-form-group",
                {
                  staticClass: "d-inline-block w-auto m-1",
                  attrs: { label: "Komoditas" }
                },
                [
                  _c(
                    "b-select",
                    {
                      attrs: { size: "md" },
                      model: {
                        value: _vm.saveData.komoditi_id,
                        callback: function($$v) {
                          _vm.$set(_vm.saveData, "komoditi_id", $$v)
                        },
                        expression: "saveData.komoditi_id"
                      }
                    },
                    [
                      _c("option", { attrs: { value: "Pilih" } }, [
                        _vm._v("Pilih")
                      ]),
                      _vm._v(" "),
                      _vm._l(_vm.komoditasList, function(komoditas, i) {
                        return _c(
                          "option",
                          { key: i, domProps: { value: komoditas.id } },
                          [
                            _vm._v(
                              "\n\t\t\t\t\t\t\t" +
                                _vm._s(komoditas.nama) +
                                "\n\t\t\t\t\t\t"
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
                { staticClass: "col", attrs: { label: "Ambil Lokasi" } },
                [
                  _c(
                    "b-input-group",
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
                          attrs: {
                            variant: "dark",
                            title: "Ambil Lokasi",
                            size: "sm"
                          },
                          on: {
                            click: function($event) {
                              return _vm.getLocation()
                            }
                          }
                        },
                        [_vm._v("Get")]
                      ),
                      _vm._v(" "),
                      _c(
                        "b-input-group-text",
                        { attrs: { slot: "append" }, slot: "append" },
                        [
                          _vm._v(
                            "Anda berada di titik lat: " +
                              _vm._s(_vm.saveData.lat) +
                              ", long: " +
                              _vm._s(_vm.saveData.long)
                          )
                        ]
                      )
                    ],
                    1
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
                { staticClass: "col", attrs: { label: "Luas Tanam" } },
                [
                  _c(
                    "b-input-group",
                    [
                      _c("b-input", {
                        attrs: { placeholder: "Luas Tanam" },
                        model: {
                          value: _vm.saveData.luas_tanam,
                          callback: function($$v) {
                            _vm.$set(_vm.saveData, "luas_tanam", $$v)
                          },
                          expression: "saveData.luas_tanam"
                        }
                      }),
                      _vm._v(" "),
                      _c(
                        "b-input-group-text",
                        { attrs: { slot: "append" }, slot: "append" },
                        [_vm._v("Ha")]
                      )
                    ],
                    1
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
                {
                  staticClass: "col",
                  attrs: { label: "File Attachment Luas Tanam" }
                },
                [
                  _c(
                    "b-input-group",
                    [
                      _c("b-file", {
                        model: {
                          value: _vm.attachment.luasTanam,
                          callback: function($$v) {
                            _vm.$set(_vm.attachment, "luasTanam", $$v)
                          },
                          expression: "attachment.luasTanam"
                        }
                      }),
                      _vm._v(" "),
                      _c("b-input", {
                        attrs: { placeholder: "Keterangan File" },
                        model: {
                          value: _vm.saveData.ket_file_luas_tanam,
                          callback: function($$v) {
                            _vm.$set(_vm.saveData, "ket_file_luas_tanam", $$v)
                          },
                          expression: "saveData.ket_file_luas_tanam"
                        }
                      })
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _vm.mode == "edit"
                    ? _c("small", [
                        _vm._v(
                          "Kosongkan file attacment jika tidak akan diganti."
                        )
                      ])
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.attachment.luasTanam != null
                    ? _c("p", { staticClass: "mt-2" }, [
                        _vm._v("File yang dipilih: "),
                        _c("b", [
                          _vm._v(
                            _vm._s(
                              _vm.attachment.luasTanam
                                ? _vm.attachment.luasTanam.name
                                : ""
                            )
                          )
                        ])
                      ])
                    : _vm._e()
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
                { staticClass: "col", attrs: { label: "Tambah Tanam" } },
                [
                  _c(
                    "b-input-group",
                    [
                      _c("b-input", {
                        attrs: { placeholder: "Tambah Tanam" },
                        model: {
                          value: _vm.saveData.tambah_tanam,
                          callback: function($$v) {
                            _vm.$set(_vm.saveData, "tambah_tanam", $$v)
                          },
                          expression: "saveData.tambah_tanam"
                        }
                      }),
                      _vm._v(" "),
                      _c(
                        "b-input-group-text",
                        { attrs: { slot: "append" }, slot: "append" },
                        [_vm._v("Ha")]
                      )
                    ],
                    1
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
                {
                  staticClass: "col",
                  attrs: { label: "File Attachment Tambah Tanam" }
                },
                [
                  _c(
                    "b-input-group",
                    [
                      _c("b-file", {
                        model: {
                          value: _vm.attachment.tambahTanam,
                          callback: function($$v) {
                            _vm.$set(_vm.attachment, "tambahTanam", $$v)
                          },
                          expression: "attachment.tambahTanam"
                        }
                      }),
                      _vm._v(" "),
                      _c("b-input", {
                        attrs: { placeholder: "Keterangan File" },
                        model: {
                          value: _vm.saveData.ket_file_tambah_tanam,
                          callback: function($$v) {
                            _vm.$set(_vm.saveData, "ket_file_tambah_tanam", $$v)
                          },
                          expression: "saveData.ket_file_tambah_tanam"
                        }
                      })
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _vm.mode == "edit"
                    ? _c("small", [
                        _vm._v(
                          "Kosongkan file attacment jika tidak akan diganti."
                        )
                      ])
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.attachment.tambahTanam != null
                    ? _c("p", { staticClass: "mt-2" }, [
                        _vm._v("File yang dipilih: "),
                        _c("b", [
                          _vm._v(
                            _vm._s(
                              _vm.attachment.tambahTanam
                                ? _vm.attachment.tambahTanam.name
                                : ""
                            )
                          )
                        ])
                      ])
                    : _vm._e()
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
                { staticClass: "col", attrs: { label: "Produksi" } },
                [
                  _c(
                    "b-input-group",
                    [
                      _c("b-input", {
                        attrs: { placeholder: "Produksi" },
                        model: {
                          value: _vm.saveData.produksi,
                          callback: function($$v) {
                            _vm.$set(_vm.saveData, "produksi", $$v)
                          },
                          expression: "saveData.produksi"
                        }
                      }),
                      _vm._v(" "),
                      _c(
                        "b-input-group-text",
                        { attrs: { slot: "append" }, slot: "append" },
                        [_vm._v("Ton")]
                      )
                    ],
                    1
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
                {
                  staticClass: "col",
                  attrs: { label: "File Attachment Produksi" }
                },
                [
                  _c(
                    "b-input-group",
                    [
                      _c("b-file", {
                        model: {
                          value: _vm.attachment.produksi,
                          callback: function($$v) {
                            _vm.$set(_vm.attachment, "produksi", $$v)
                          },
                          expression: "attachment.produksi"
                        }
                      }),
                      _vm._v(" "),
                      _c("b-input", {
                        attrs: { placeholder: "Keterangan File" },
                        model: {
                          value: _vm.saveData.ket_file_produksi,
                          callback: function($$v) {
                            _vm.$set(_vm.saveData, "ket_file_produksi", $$v)
                          },
                          expression: "saveData.ket_file_produksi"
                        }
                      })
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _vm.mode == "edit"
                    ? _c("small", [
                        _vm._v(
                          "Kosongkan file attacment jika tidak akan diganti."
                        )
                      ])
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.attachment.produksi != null
                    ? _c("p", { staticClass: "mt-2" }, [
                        _vm._v("File yang dipilih: "),
                        _c("b", [
                          _vm._v(
                            _vm._s(
                              _vm.attachment.produksi
                                ? _vm.attachment.produksi.name
                                : ""
                            )
                          )
                        ])
                      ])
                    : _vm._e()
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
                { staticClass: "col", attrs: { label: "Luas Panen" } },
                [
                  _c(
                    "b-input-group",
                    [
                      _c("b-input", {
                        attrs: { placeholder: "Luas Panen" },
                        model: {
                          value: _vm.saveData.luas_panen,
                          callback: function($$v) {
                            _vm.$set(_vm.saveData, "luas_panen", $$v)
                          },
                          expression: "saveData.luas_panen"
                        }
                      }),
                      _vm._v(" "),
                      _c(
                        "b-input-group-text",
                        { attrs: { slot: "append" }, slot: "append" },
                        [_vm._v("Ha")]
                      )
                    ],
                    1
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
                {
                  staticClass: "col",
                  attrs: { label: "File Attachment Produksi" }
                },
                [
                  _c(
                    "b-input-group",
                    [
                      _c("b-file", {
                        model: {
                          value: _vm.attachment.luasPanen,
                          callback: function($$v) {
                            _vm.$set(_vm.attachment, "luasPanen", $$v)
                          },
                          expression: "attachment.luasPanen"
                        }
                      }),
                      _vm._v(" "),
                      _c("b-input", {
                        attrs: { placeholder: "Keterangan File" },
                        model: {
                          value: _vm.saveData.ket_file_luas_panen,
                          callback: function($$v) {
                            _vm.$set(_vm.saveData, "ket_file_luas_panen", $$v)
                          },
                          expression: "saveData.ket_file_luas_panen"
                        }
                      })
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _vm.mode == "edit"
                    ? _c("small", [
                        _vm._v(
                          "Kosongkan file attacment jika tidak akan diganti."
                        )
                      ])
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.attachment.luasPanen != null
                    ? _c("p", { staticClass: "mt-2" }, [
                        _vm._v("File yang dipilih: "),
                        _c("b", [
                          _vm._v(
                            _vm._s(
                              _vm.attachment.luasPanen
                                ? _vm.attachment.luasPanen.name
                                : ""
                            )
                          )
                        ])
                      ])
                    : _vm._e()
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
      ),
      _vm._v(" "),
      _c(
        "b-modal",
        {
          ref: "modal-verifikasi",
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
              "\n\t\t\t\tKonfirmasi Verifikasi " +
                _vm._s(_vm.isVrf) +
                "\n\t\t\t"
            )
          ]),
          _vm._v(" "),
          _c("b-form-row", [
            _vm._v(
              "\n\t\t\t\tSemua data yang tampil berdasarkan filter akan terverifikasi!\n\t\t\t"
            )
          ]),
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
                  attrs: { variant: "danger", title: "Close" },
                  on: {
                    click: function($event) {
                      return _vm.hideModalVrf()
                    }
                  }
                },
                [_vm._v("\n                    Batal\n                ")]
              ),
              _vm._v(" "),
              _c(
                "ladda-btn",
                {
                  staticClass: "btn btn-primary",
                  attrs: { loading: _vm.loading, "data-style": "zoom-out" },
                  nativeOn: {
                    click: function($event) {
                      return _vm.processVrf($event)
                    }
                  }
                },
                [_vm._v("\n\t\t\t\t\tKonfirmasi\n\t\t\t\t")]
              )
            ],
            1
          )
        ],
        1
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
        [_vm._v("\n                    Data / Data Luas\n                ")]
      )
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/assets/src/components/data/luas/List.vue":
/*!************************************************************!*\
  !*** ./resources/assets/src/components/data/luas/List.vue ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _List_vue_vue_type_template_id_c38129f2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./List.vue?vue&type=template&id=c38129f2& */ "./resources/assets/src/components/data/luas/List.vue?vue&type=template&id=c38129f2&");
/* harmony import */ var _List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./List.vue?vue&type=script&lang=js& */ "./resources/assets/src/components/data/luas/List.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _List_vue_vue_type_template_id_c38129f2___WEBPACK_IMPORTED_MODULE_0__["render"],
  _List_vue_vue_type_template_id_c38129f2___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/assets/src/components/data/luas/List.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/assets/src/components/data/luas/List.vue?vue&type=script&lang=js&":
/*!*************************************************************************************!*\
  !*** ./resources/assets/src/components/data/luas/List.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./List.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/data/luas/List.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/assets/src/components/data/luas/List.vue?vue&type=template&id=c38129f2&":
/*!*******************************************************************************************!*\
  !*** ./resources/assets/src/components/data/luas/List.vue?vue&type=template&id=c38129f2& ***!
  \*******************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_template_id_c38129f2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./List.vue?vue&type=template&id=c38129f2& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/assets/src/components/data/luas/List.vue?vue&type=template&id=c38129f2&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_template_id_c38129f2___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_List_vue_vue_type_template_id_c38129f2___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);