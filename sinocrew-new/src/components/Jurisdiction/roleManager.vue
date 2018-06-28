<template>
  <div class="boaters" v-show="!$store.state.allDialog.showNoRight">
    <!-- <Search btns="1" v-if="$store.state.reflash.searchReflash"></Search> -->
    <Table btns="9-2"  v-if="$store.state.reflash.tableReflash"></Table>
  </div>
</template>

<script>
import Search from '../common/search/search'
import Table from '../common/table/table'
export default {
  data () {
    return {
      
    }
  },
  components: {
    Search,Table
  },
  created(){
    
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
