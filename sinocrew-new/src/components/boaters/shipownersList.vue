<template>
  <div class="ship-owner" v-show="!$store.state.allDialog.showNoRight">
    <Search btns="1-1"></Search>
    <Table btns="2-2" v-if="$store.state.reflash.tableReflash"></Table>
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
