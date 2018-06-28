<template>
  <div class="no-right pr">
      <img :src="imgs.noRight" class="pa" alt="">
  </div>
</template>

<script>
export default {
  data () {
    return {
      imgs:{
        noRight:require("../../assets/imgs/common/no_right.jpg")
      }
    }
  },
  components: {
    
  },
  created(){
    
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.no-right{
  height: 100%;
}
img{
  top: 50%;
  left: 50%;
  transform: translate(-50%,-50%)
}
</style>
