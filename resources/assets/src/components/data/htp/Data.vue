<template>
    <div>
        <div v-if="penyuluh == 1">
            <penyuluh></penyuluh>
        </div>
        <div v-else>
            <admin></admin>
        </div>
    </div>
</template>

<script>
import admin from '@/components/data/htp/List'
import penyuluh from '@/components/data/htp/Penyuluh'
import Penyuluh from './Penyuluh.vue'
export default {
    name: 'htp',
	metaInfo: {
		title: 'Harga Tingkat Petani'
    },
    components:{
        admin,
        penyuluh
    },
    computed:{
        penyuluh(){
            const user = this.$store.state.auth.user;
            if(user && user.penyuluh != null){
                return 1
            }
            return 0
        }
    },
    created(){
    }
}
</script>