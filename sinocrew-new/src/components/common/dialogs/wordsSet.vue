<template>
  <el-dialog title="字段设置" :visible.sync="$store.state.allDialog.wordsSet" width="650px" class="border-common">
    <div class="query-box">
      <div>
        <p>字段的显示隐藏</p>

        <div v-if="wordSetType!=2" class="show-hide-box">
          <el-checkbox-group v-model="wordsShow">
            <el-checkbox class="option-one" v-for="item in wordsArray" :key="item" :label="item"></el-checkbox>
          </el-checkbox-group>
        </div>

        <div v-else-if="wordSetType==2" class="show-hide-box security-box">
          <div v-for="(item,index1) in wordsArray">
            <el-checkbox  v-model="item.isShow" @change="selectAllThis(item)">{{item.label}}</el-checkbox>
            <el-checkbox-group v-model="item.checked" v-if="item.noChildren!=true">
              <el-checkbox v-for="(option,index2) in item.children" :label="option.label" :key="index2" @change="selectThis(option)">{{option.label}}</el-checkbox>
            </el-checkbox-group>
          </div>
        </div>

      </div>
      <div>
        <p>字段排序</p>
        <ul class="show-hide-box">
          <li v-for="(one,index) in wordsShow" class="order-box">
            <span>{{one}}</span>
            <span class="fr" style="color:#0099FF;">{{index+1}}</span>
            <div class="btn-box">
              <span title="往后移一位" @click="changePlace('next',one,index)"><img class="cursor" src="../../../assets/imgs/common/next.png" alt=""></span>
              <span title="往前移一位" @click="changePlace('pre',one,index)"><img class="cursor" src="../../../assets/imgs/common/pre.png" alt=""  ></span>
              <span title="移动到第一位" @click="changePlace('first',one,index)"><img class="cursor" src="../../../assets/imgs/common/first.png" alt=""  ></span>
              <span title="移动到最后一位" @click="changePlace('last',one,index)"><img class="cursor" src="../../../assets/imgs/common/last.png" alt=""  ></span>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <span slot="footer" class="dialog-footer">
      <el-button @click="closeThis('cancel')">取 消</el-button>
      <el-button type="primary" @click="closeThis('save')">确 定</el-button>
    </span>
  </el-dialog>
</template>

<script>
export default {
  data() {
    return {
      wordsShow: [],
      wordsArray: [],

      boatersWordsArray:["简称","姓名","身份证号","CID","FLEET","OENER POOL","VESSEL","职务","MANNING OFFICE"],//船员基本数组

      securityArray:[],

      insuranceArray:[],

      wordSetType:1,//当前设置
      
    };
  },
  methods: {
    closeThis(str) {
      var goal = [];
      if (str == "save") {
        var routerpath = _g.getRouterPath(this);
        if(this.$store.state.reflash.wordsReset){
          localStorage.setItem(routerpath.slice(1)+"WordsArray","")
          this.$store.state.reflash.wordsReset=false
        }else{
          localStorage.setItem(routerpath.slice(1)+"WordsArray",JSON.stringify(this.wordsShow))
          // console.log(this.wordsShow)
        }

        _g.showloading("努力设置中")
        this.$store.state.reflash.contentReflash=false
        setTimeout(()=>{
          this.$store.state.reflash.contentReflash=true
          _g.closeloading(this,"设置成功")
        },500)
      }
      this.$store.state.allDialog.wordsSet = false;
    },

    changePlace(str,one,index){
      if(str=="pre"&&index!=0){
        var a=this.wordsShow[index-1]
        this.wordsShow.splice(index,1,a)
        this.wordsShow.splice(index-1,1,one)
      }else if(str=="next"&&index!=this.wordsShow.length-1){
        var a = this.wordsShow[index+1]
        this.wordsShow.splice(index+1,1,one)
        this.wordsShow.splice(index,1,a)
      }else if(str=="first"){
        this.wordsShow.splice(index,1)
        this.wordsShow.unshift(one)
      }else if(str=="last"){
        this.wordsShow.splice(index,1)
        this.wordsShow.push(one)
      }
    },

    // 总选
    selectAllThis(item){
      if(item.isShow){
        item.children.forEach(ele=>{
          item.checked.push(ele.label)
        })
        this.wordsShow=this.wordsShow.concat(item.checked)
      }else{
        item.checked=[]
        item.children.forEach(ele=>{
          if(this.wordsShow.includes(ele.label)){
            this.wordsShow.splice(this.wordsShow.indexOf(ele.label),1)
          }
        })
      }
    },
    selectThis(item){
      if(this.wordsShow.includes(item.label)){
        this.wordsShow.splice(this.wordsShow.indexOf(item.label),1)
      }else{
        this.wordsShow.push(item.label)
      }
    }

    
  },
  components: {},
  created() {
    var routerpath = _g.getRouterPath(this);
    // localStorage.setItem(routerpath.slice(1)+"WordsArray","")
    // console.log(this.$store.state.tableListData[routerpath.slice(1)+'List'])
    var OriginalArray=this.$store.state.tableListData[routerpath.slice(1)+'List']
    this.wordSetType=1
    OriginalArray.forEach(ele=>{
      if(ele.label&&ele.label!="序号"){
        this.wordsArray.push(ele.label)
      }
    })

    this.wordsShow=localStorage.getItem(routerpath.slice(1)+"WordsArray")&&JSON.parse(localStorage.getItem(routerpath.slice(1)+"WordsArray")).length>0?JSON.parse(localStorage.getItem(routerpath.slice(1)+"WordsArray")):[...this.wordsArray]
    if(routerpath=="/securityset"){
      this.wordSetType=2
      this.wordsArray=[...this.$store.state.tableListData.securitysetList]
      var goal=[]
      this.$store.state.tableListData.securitysetList.forEach(ele0=>{
       ele0.children.forEach(ele1=>{
         goal.push(ele1.label)
       })
      })
      this.wordsShow=(localStorage.getItem(routerpath.slice(1)+"WordsArray")&&JSON.parse(localStorage.getItem(routerpath.slice(1)+"WordsArray")).length>0)?JSON.parse(localStorage.getItem(routerpath.slice(1)+"WordsArray")):[...goal]
      this.wordsArray.forEach((ele0,index)=>{
        ele0.checked=[]
        ele0.children.forEach(ele1=>{
          if(this.wordsShow.includes(ele1.label)){
            ele0.checked.push(ele1.label)
            if(ele0.checked.length>0){
              ele0.isShow=true
            }else{
              ele0.isShow=false
            }
          }
        })
      })
    }

    
    
    
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.query-box {
  display: flex;
  height: 300px;
}
.query-box > div {
  width: 0;
  flex-grow: 1;
  /* background: red; */
}
.query-box > div:nth-child(1) {
  border-right: 1px solid #e4e4e4;
}
P {
  text-align: left;
  text-indent: 20px;
  height: 30px;
  line-height: 30px;
  box-sizing: border-box;
  border-bottom: 1px solid #e4e4e4;
  margin-bottom: 10px;
}
.option-one {
  display: block;
  margin-left: 30px;
}
.order-box{
  padding: 5px 10px;
}
.btn-box{
  display: inline-block;
  float:right;
  margin-right: 5px;
}
.btn-box img{
  display: inline-block;
  padding: 0 5px;
}
.show-hide-box{
  height: 260px;
  overflow-y: auto;
} 
span.fr{
  width: 20px;
}
</style>
