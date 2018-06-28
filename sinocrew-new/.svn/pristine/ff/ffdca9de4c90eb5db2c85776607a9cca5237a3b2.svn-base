<template>
  <el-dialog
  title="提示"
  :visible.sync="$store.state.allDialog.homepageSetShow"
  width="600px"
  class="border-common"
  >
  <ul class="overflow-y homepage-set">
    <li v-for="(item,index) in list">
      <div class="title border-common"><el-checkbox v-if="showCheck" :checked="item.checked" :label="item.label" @change="chooseAll(index)"></el-checkbox></div>
      <el-checkbox-group v-model="item.checkList" class="content border-common" @change="changeThis(index)">
        <el-checkbox v-for="(option,index1) in item.children" :checked="option.checked" :label="option.label" :key="index1" ></el-checkbox>
      </el-checkbox-group>
    </li>
  </ul>

  <span slot="footer" class="dialog-footer">
    <el-button @click="closeThis('cancel')">取 消</el-button>
    <el-button type="primary" @click="closeThis('save')">确 定</el-button>
  </span>
</el-dialog>
</template>

<script>
export default {
  props:["toDialog"],
  data () {
    return {
      list:[
        {
          label:"船员",
          checked:false,//全选开关
          checkList:[],
          children:[
            {
              label:"船员信息",
              checked:false,
            },{
              label:"船东信息",
              checked:false,
            },{
              label:"船名信息",
              checked:false,
            }
          ]
        },{
          label:"社保",
          checked:false,//全选开关
          checkList:[],
          children:[
            {
              label:"社保设置",
              checked:false,
            },{
              label:"参保人员",
              checked:false,
            },{
              label:"社保信息",
              checked:false,
            },{
              label:"社保对账",
              checked:false,
            }
          ]
        },{
          label:"报销",
          checked:false,//全选开关
          checkList:[],
          children:[
            {
              label:"船员差旅报销",
              checked:false,
            },{
              label:"员工差旅报销",
              checked:false,
            },{
              label:"办公费用报销",
              checked:false,
            },{
              label:"船员差旅报销记录",
              checked:false,
            },{
              label:"员工差旅报销记录",
              checked:false,
            },{
              label:"办公费用报销记录",
              checked:false,
            },{
              label:"船员差旅报销签批",
              checked:false,
            },{
              label:"员工差旅报销签批",
              checked:false,
            },{
              label:"办公费用报销签批",
              checked:false,
            },{
              label:"报销模板",
              checked:false,
            }
          ]
        },{
          label:"借还款",
          checked:false,//全选开关
          checkList:[],
          children:[
            {
              label:"借还款",
              checked:false,
            },{
              label:"意外险",
              checked:false,
            },{
              label:"借还款对账",
              checked:false,
            }
          ]
        },
      ],

      showCheck:true,

      urls:{
        set:"index/menuAdd"
      }
    }
  },
  methods:{
    closeThis(str){
      var goal=[]
      if(str=="save"){
        this.list.forEach(ele=>{
          goal=goal.concat(ele.checkList)
        })
        this.apiPost(this.urls.set,{title:goal}).then(res=>{
          _g.toMessage(this,res.error?"error":"success",res.msg)
          if(!res.error){
            this.$emit("reflashFunc")
            this.$store.state.allDialog.homepageSetShow=false
          }
        })
      }else{
        this.$store.state.allDialog.homepageSetShow=false
      }
      
    },

    // 点击标题与下方所有联动
    chooseAll(index){
      this.list[index].checked=event.target.checked
      this.list[index].checkList=[]

      this.list[index].children.forEach(ele => {
        ele.checked=event.target.checked
        if(event.target.checked==true){
          this.list[index].checkList.push(ele.label)
        }
      });
    },

    changeThis(index){
      if(this.list[index].checkList.length==this.list[index].children.length){
        this.$set(this.list[index],"checked",true)
        this.showCheck=false
        setTimeout(()=>{this.showCheck=true},0)
      }else if((this.list[index].checkList.length==this.list[index].children.length-1)&&this.list[index].checked==true){
        this.$set(this.list[index],"checked",false)
        this.showCheck=false
        setTimeout(()=>{this.showCheck=true},0)
      }
    },

    getSelected(){
      // console.log(this.list)
      this.list.forEach(ele=>{
        ele.children.forEach(ele1=>{
          if(this.toDialog.includes(ele1.label)){
            ele.checkList.push(ele1.label)
            if(ele.checkList.length==ele.children.length){
              ele.checked=true
            }
          }
        })
      })
    },
  },
  components: {
    
  },
  created(){
    this.getSelected()
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .title{
    display: block;
     background-color: #F3F3F3;
     padding: 5px 10px;
  }
  .content{
    padding: 5px 10px;
    background-color: #fff;
    border-top: none;
    display: flex;
    flex-wrap: wrap;
    /* justify-content: space-around; */
  }
  .content>label{
    width: 25%;
    margin: 0;
  
  }
  .overflow-y{
    max-height: 400px;
  }
  .homepage-set>li{
    margin-bottom: 10px;
  }

</style>
