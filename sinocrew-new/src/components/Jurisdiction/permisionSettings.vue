<template>
  <div class="permision-box">
    <p class="border-common">当前角色：{{title}}</p>
    <ul class="permision-set border-common">
      <li v-for="(item,index) in listData">
        <div class="title border-common"><el-checkbox :class="item.class?'choose-all title-select':'title-select'" :checked="item.checked" v-if="titleShow[index]" :label="item.label" @change="chooseAll(index)"></el-checkbox></div>
        <div class="content border-left box-size">
          <el-checkbox-group class="content-list" v-model="item.checkList" @change="changeThis(index)">
            <el-checkbox class="content-option" v-for="(option,index1) in item.children" :label="option" :key="index1"></el-checkbox>
          </el-checkbox-group>
        </div>
      </li>
    </ul>
    <div class="btn-box border-common box-size">
      <el-button type="primary btn pr" @click="submit">确定</el-button>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      listData:[
        
      ],
      urls:{
        list:"privilege/listPrivilege",
        detail:"privilege/personPrivilege",
        add:"privilege/addPrivilege"
      },
      titleShow:null,
      title:"",

      modelTitle:[],
    }
  },
  components: {
    
  },

  computed:{
    thisData(){
      return this.listData
    }
  },
  
  methods:{
    getData(){
      var id = _g.getRouterParams(this,"id")

      this.title=localStorage.getItem("permisionSettings")

      this.apiPost(this.urls.list).then(res=>{
        this.modelTitle=res.data
        res.data.forEach((ele,index)=>{
          var obj={
            label:ele.name,
            children:[],
            checked:false,
            checkList:[]
          }
          ele.children.forEach((ele2,index2)=>{
            obj.children.push(ele2.name)
          })
          this.listData.push(obj)
        })
        this.listData.push({
          label:"选中全部",
          checked:false,//全选开关
          children:[],
          class:"choose-all"
        })

        this.titleShow=new Array(this.listData.length).fill(true)
      }).then(()=>{
          this.apiPost(this.urls.detail,{roleId:id}).then(res=>{
            if(!res.error){
              res.forEach(ele=>{
                this.modelTitle.forEach((ele1,index1)=>{
                  ele1.children.forEach((ele2,index2)=>{
                    if(ele==ele2.id){
                      this.listData[index1].checkList.push(this.listData[index1].children[index2])
                      this.changeThis(index1)
                    }
                  })
                })
              })
            }
          })
        }
      )

      
    },
    // 点击标题与下方所有联动
    chooseAll(index){
      this.listData[index].checked=event.target.checked
      
      if(index==this.titleShow.length-1){
        this.listData.forEach((ele,index0) => {
          ele.checked=event.target.checked
          ele.checkList=[]
          if(index0<index)ele.children.forEach(ele1=>{
            if(ele.checked)ele.checkList.push(ele1)
          })
          this.$set(this.titleShow,index0,false)
          setTimeout(()=>{
            this.$set(this.titleShow,index0,true)
          },0)
        });
      }else{
        this.listData[index].checkList=[]
        this.listData[index].children.forEach(ele => {
          if(this.listData[index].checked)this.listData[index].checkList.push(ele)
        });
      }
    },
    changeThis(index){
      this.listData[index].checked=this.listData[index].checkList.length==this.listData[index].children.length
      if(this.listData[index].checked||(!this.listData[index].checked&&this.listData[index].checkList.length==this.listData[index].children.length-1)){
        this.$set(this.titleShow,index,false)
        setTimeout(()=>{
          this.$set(this.titleShow,index,true)
        },0)
      }
    },
    submit(){
      var params={
        roleId:_g.getRouterParams(this,"id"),
        ids:[]
      }
      this.listData.forEach((ele,index)=>{
        if(ele.checkList&&ele.checkList.length){
          this.modelTitle[index].children.forEach(ele1=>{
            if(ele.checkList.includes(ele1.name)){
              params.ids.push(ele1.id)
            }
          })
        }
        
      })
      this.apiPost(this.urls.add,params).then(res=>{
        _g.toMessage(this,res.error?"error":"success",res.msg)
        if(!res.error){
          this.$router.go(-1)
        }
      })
    }
  },
  created(){
    this.getData()
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.permision-box>p{
  text-align: left;
  padding: 10px 0;
  height: 28px;
  line-height: 28px;
  background-color: rgb(243, 243, 243);
  box-sizing: content-box;
  text-indent: 20px;
}
.permision-set{
  border-top: none;
  padding:20px
}
.permision-set>li{
  margin-bottom: 20px;
}
.permision-set .title{
  /* height: 50px;
  width: 100%;
  vertical-align: middle; */
}
.permision-set .title{
  background-color: rgb(243, 243, 243);
  height: 50px;
  box-sizing: border-box;
  padding: 0 10px;
}
.permision-set .content{
  /* padding: 0 10px; */
  border-top: none;
}
.title-select{
  transform: translateY(12px);
  width: 100%;
}

.content-list{
  display: flex;
  flex-wrap: wrap;
}
.content-option{
  width: 20%;
  margin-left: 0!important;
  height: 50px;
  box-sizing: border-box;
  border-right: 1px solid #ebeef5;
  border-bottom: 1px solid #ebeef5;
}
.btn-box{
  border-top: none;
  height: 100px;
}
.btn{
  width: 100px;
  left: 50%;
  top: 40%;
  transform: translateX(-50%)
}
</style>
