<template>
  <div class="homepage">
      <div class="my-setting">
        <p class="box-size">
          <span>便捷入口</span>
          <span class="fr settings my-btn box-size" @click="$store.state.allDialog.homepageSetShow=true">设置</span>
        </p>
        <ul class="setting-list">
            <li v-for="item in tableData.settingData"  :class="item.label?'box-size':'box-size no-point'" @click="gotoHere(item)">{{item.label}}</li>
        </ul>
      </div>

      <ChartList :chartData="chartData"></ChartList>
      <dialogSet v-if="$store.state.allDialog.homepageSetShow" @reflashFunc="sayHello" :toDialog="toDialog"></dialogSet>
  </div>
</template>

<script>
import dialogSet from './dialog/dialogSet'
import ChartList from './chart/chart'
export default {
  data () {
    return {
      tableData:{
        settingData:[
          
        ], //每一行7个
      },
      toDialog:[],//

      chartData:{},

      urls:{
        list:"index/menuList"
      }
    }
  },
  methods:{
    sayHello(){
      this.matchingUrl()
    },
    gotoHere(item){
      this.$store.state.leftmenuData.forEach((ele1,index1)=>{
        if(index1==item.one){
          this.$set(this.$store.state.leftmenuData[item.one],"isactive",true)
        }else{
          ele1.isactive=false
        }
        ele1.children.forEach((ele2,index2)=>{
          ele2.data.forEach((ele3,index3)=>{
            if(item.two==index2&&item.there==index3&&index1==item.one){
              this.$set(this.$store.state.leftmenuData[item.one].children[item.two].data[item.there],"isactive",true)
            }else{
              ele3.isactive=false
            }
          })
        })
      })
      this.$router.push(item.url);
      this.$store.state.theader.showback=true
      this.$store.state.theader.label=item.label
    },
    // 匹配新设置的字符串
    matchingUrl(){
      var access=this.$store.state.leftmenuData
      this.apiPost(this.urls.list).then(res=>{
        this.chartData=res

        if(res.info.length>0){
          this.tableData.settingData=[]
          this.toDialog=[]
          res.info.forEach(ele=>{
            this.toDialog.push(ele.title)
            this.tableData.settingData.push({
              label:ele.title
            })
          })
          
        }else{
          this.tableData.settingData=[
            {
              label:"船员信息",
              url:""
            },{
              label:"船名信息",
              url:""
            },{
              label:"员工信息",
              url:""
            },{
              label:"社保设置",
              url:""
            },{
              label:"参保人员",
              url:""
            },{
              label:"社保信息",
              url:""
            },{
              label:"社保对账",
              url:""
            },{
              label:"船员差旅报销",
              url:""
            },{
              label:"员工差旅报销",
              url:""
            }
          ]
        }
      }).then(()=>{
        this.tableData.settingData.forEach(ele=>{
          for(var i=0;i<access.length;i++){
            for(var l=0;l<access[i].children.length;l++){
              for(var p=0;p<access[i].children[l].data.length;p++){
                if(ele.label===access[i].children[l].data[p].label){
                  ele.url=access[i].children[l].data[p].url
                  ele.one=i
                  ele.two=l
                  ele.there=p
                }
              }
            }
          }
        })
        // var length=this.tableData.settingData.length
        // var fillArray=new Array(Math.ceil(length/7)*7-length).fill({label:""})
        // this.tableData.settingData=this.tableData.settingData.concat(fillArray)
      })
    },
  },
  components: {
    dialogSet,ChartList
  },
  created(){
    this.matchingUrl()
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .my-setting>p{
    text-align: left;
    line-height: 45px;
    padding: 0;
    height: 45px;
    border: 1px solid #E4E4E4;
    text-indent: 20px;
    font-size: 13px;
    background: #f3f3f3;
    
  }
  .settings{
    margin-right: 20px;
    line-height: 20px;
    text-align: center;
    text-indent: 0;
    margin-top: 6px;
    font-size: 12px;
  }
  .setting-list{
    display: flex;
    flex-wrap: wrap;
    background-color: #fff;
    border-left: 1px solid #E4E4E4;
  }
  .setting-list li{
    width: 10%;
    height: 40px;
    line-height: 40px;
    text-align: center;
    cursor: pointer;
    border: 1px solid #E4E4E4;
    border-left: none;
    border-top: none
  }
  .setting-list li:hover{
    color:#0099FF
  }
  .no-point{
    cursor: auto;
  }
</style>
