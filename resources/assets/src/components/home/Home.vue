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
import admin from './Admin.vue'
import penyuluh from './Penyuluh.vue'
export default {
	name: 'home',
	metaInfo: {
		title: 'Home'
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
}
</script>
