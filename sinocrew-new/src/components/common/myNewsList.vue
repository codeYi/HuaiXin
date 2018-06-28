<template>
  <div class="news-list" v-if="$store.state.infoNotice.infoNum>0" @mouseleave="$store.state.infoNotice.showList=false">
      <p class="title box-size">系统消息</p>
      <ul>
        <li v-for="item in $store.state.infoNotice.warningData" v-show="item.count>0" @click="gotoInfo(item)">
          <p class="box-size"><span>{{item.label}}</span><span class="fr">( <span class="color-red">{{item.count}}</span> )</span></p>
        </li>
      </ul>
  </div>
  <div v-else class="news-list" @mouseleave="$store.state.infoNotice.showList=false">
      <p class="title">暂时没有需要处理的消息</p>
  </div>
</template>

<script>
export default {
  props:["listData"],
  data () {
    return {
      
    }
  },
  components: {
    
  },
  methods:{
    gotoInfo(item){
      this.$router.push(item.url)
      var indexA=item.menu.split("-")
      this.$store.state.theader.label=item.title
      this.$store.state.leftmenuData.forEach((ele1,index1) => {
        if(index1==indexA[0]){
          ele1.isactive=true
        }else{
          ele1.isactive=false
        }
          ele1.children.forEach((ele2,index2)=>{
            ele2.data.forEach((ele3,index3)=>{
              if(index2==indexA[1]&&index3==indexA[2]){
                ele3.isactive=true
              }else{
                ele3.isactive=false
              }
            })
          })
      });
    }
  },
  computed:{
    
  },
  created(){

  },
  mounted(){
    
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
p.title{
  height: 45px;
  line-height: 45px;
  color: #999;
}
p{
  padding: 0;
  text-align: left;
  height: 40px;
  line-height: 40px;
  border-bottom: 1px solid #E4E4E4;
  padding: 0 20px;
  color: #333;
}
li:hover{
  cursor: pointer;
  background-color: #E4E4E4;
}
</style>
