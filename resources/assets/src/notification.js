import Vue from "vue";
import Notifications from "vue-notification";

Vue.use(Notifications);

export default {
    data() {
        return {};
    },
    methods: {
        notif(classes = "primary", title = "", text = "") {
            this.$notify({
                group: "notifications-default",
                type:
                    "bg-" +
                    classes +
                    (classes == "warning" ? " text-body" : " text-white"),
                title: title,
                text: text
            });
        },

        swr() {
            this.$notify({
                group: "notifications-default",
                type: "bg-warning text-body",
                title: "Oops",
                text: "Koneksi Bermasalah!"
            });
        },
    },
};
