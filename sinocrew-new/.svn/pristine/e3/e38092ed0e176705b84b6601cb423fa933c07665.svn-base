<template>
  <div class="security-set" v-show="!$store.state.allDialog.showNoRight">
    <Search btns="1" v-if="$store.state.reflash.searchReflash"></Search>
    <Extend v-if="$store.state.allDialog.extendShow"></Extend>
    <Table btns="3-2"  v-if="$store.state.reflash.tableReflash"></Table>
  </div>
</template>

<script>
import Search from '../common/search/search'
import Extend from '../common/extend.vue'
import Table from '../common/table/table'
export default {
  data () {
    return {
      
    }
  },
  components: {
    Search,Table,Extend
  },
  created(){
    
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
