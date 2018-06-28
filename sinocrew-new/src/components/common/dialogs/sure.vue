<template>
  <el-dialog class="sure-box" :title="$store.state.sureData.title" :visible.sync="$store.state.allDialog.sure" width="30%">
    <span>{{$store.state.sureData.content}}</span>
    <span slot="footer" class="dialog-footer">
      <el-button @click="$store.state.allDialog.sure = false">取 消</el-button>
      <el-button type="primary" @click="$store.state.sureData.OK">确 定</el-button>
    </span>
  </el-dialog>
</template>

<script>
export default {
  data() {
    return {
      
    };
  },
  methods:{
    
  },
  components: {},
  created() {
    
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
