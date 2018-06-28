<template>
  <el-dialog :title="title.title1" :visible.sync="$store.state.allDialog.batch" width="600px" class="border-common">

    <div class="upload-box">
      <span style="display:inline-block;width:100px;text-align:right">{{title.title2}}：</span>
      <el-upload class="upload-demo" ref="upload" accept=".xls,.xlsx" :auto-upload="false" :action="urls.importUrl" :on-preview="handlePreview" :on-remove="handleRemove" :before-remove="beforeRemove" :on-success="uploadResult" :on-error="uploadError" :limit="1" :on-exceed="handleExceed">
        <el-button size="small" type="primary">点击上传</el-button>
        <div slot="tip" class="el-upload__tip">按模板导入</div>
      </el-upload>
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

      title:{
        title1:"社保--批量设置",
        title2:"批量设置",
      },

      urls:{
        importUrl:"",//通过路由判断上传的地址

        importSecurity:PATH+"Social/importInfo",//社保设置批量
      }
    };
  },
  methods: {
    closeThis(str) {
      if (str == "save") {
        if(this.$refs.upload.uploadFiles.length==0){
          _g.toMessage(this,"warning","没有选择上传文件")
        }else if(this.$refs.upload.uploadFiles.length>0){
          this.$refs.upload.submit()
        }
      }else{
        this.$store.state.allDialog.batch = false;
      }
      
    },

    handleRemove(file, fileList) {
      // console.log(file, fileList);
    },
    handlePreview(file) {
      // console.log(file);
    },
    uploadResult(response){
      _g.toMessage(this,response.error?"error":"success",response.msg)
      // if(!response.error){
      //   _g.toMessage(this,response.error?"error":"success",response.msg)
      // }else{
      //   this.$store.state.sureData={
      //     title:"提示",
      //     content:response.msg,
      //     OK:function(){
      //       console.log("clicking")
      //     }
      //   } 
      //   this.$store.state.allDialog.sure=true
      //   this.$store.state.allDialog.batch=false
      // }

      this.$store.state.allDialog.batch =response.error!=0
      this.$refs.upload.uploadFiles=[]
      if(!response.error)setTimeout(()=>{bus.$emit("search_new_result")},700)
    },
    uploadError(){
      _g.toMessage(this,"error","上传失败，请检查网络")
    },
    // 选多了提示
    handleExceed(files, fileList) {
      this.$message.warning(
        `当前限制选择 1 个文件，本次选择了 ${
          files.length
        } 个文件，共选择了 ${files.length + fileList.length} 个文件`
      );
    },
    beforeRemove(file, fileList) {
      return this.$confirm(`确定移除 ${file.name}?`);
    }
  },
  components: {},
  created() {
    var routerpath = _g.getRouterPath(this);
    if(routerpath=="/securityset"){
      this.urls.importUrl=PATH+"social/importArea"
      this.title={title1:"社保设置--批量设置", title2:"批量设置"}
    }else if(routerpath=="/securityinsurance"){
      this.title={title1:"参保人员--批量设置", title2:"批量设置"}
    }else if(routerpath=="/securityinfo"){
      this.title={title1:"社保信息--批量设置", title2:"批量设置"}
      this.urls.importUrl=PATH+"Social/importInfo"
    }else if(routerpath=="/boaters"){
      this.title={title1:"家汇信息", title2:"批量设置"}
      this.urls.importUrl=PATH+"Mariner/importRemittance"
    }else if(routerpath=="/refundAddBoaters"){
      this.title={title1:"船员费用", title2:"批量添加"}
      this.urls.importUrl=PATH+"expenses/importExpense"
    }else if(routerpath=="/refundAddStaffs"){
      this.title={title1:"员工差旅", title2:"批量设置"}
      this.urls.importUrl=PATH+"Mariner/importRemittance"
    }else if(routerpath=="/refundAddOffice"){
      this.title={title1:"办公费用", title2:"批量设置"}
      this.urls.importUrl=PATH+"Mariner/importRemittance"
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.my-form > div {
  float: left;
  width: 47%;
}
.my-form > div .el-input {
  width: 100%;
}
.double-blank > label {
  line-height: 15px;
  height: 15px;
}
.add-set {
  border-bottom: 1px solid #e4e4e4;
}
.upload-box {
  /* border-bottom: 1px solid #E4E4E4;  */
  padding: 15px 0;
}
.upload-demo {
  display: inline-block;
  width: 400px;
  margin-left: 10px;
  vertical-align: top;
}
.el-upload__tip::before {
  content: "*";
  color: #f56c6c;
  margin-right: 4px;
}
</style>
