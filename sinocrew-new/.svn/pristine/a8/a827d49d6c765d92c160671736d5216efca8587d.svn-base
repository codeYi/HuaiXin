<template>
  <el-dialog title="添加" :visible.sync="$store.state.allDialog.add" :width="addType==21?'900px':'600px'" class="border-common add-dialog">
    <!-- 船员 -->
    <div class="overflow-y add-set" v-if="addType==1">
      <el-form :model="formData.boatData" label-width="100px" :rules="rules" class="my-form" ref="addForm">
        <el-form-item label="身份证/护照"prop="idNumber">
          <el-input v-model="formData.boatData.idNumber"></el-input>
        </el-form-item>
        <el-form-item label="CID" prop="cid">
          <el-input v-model="formData.boatData.cid" ></el-input>
        </el-form-item>
        <el-form-item label="中文姓名" prop="name">
          <el-input v-model="formData.boatData.name" @blur="getEnglishName(formData.boatData.name)"></el-input>
        </el-form-item>
        <el-form-item label="英文姓名">
          <el-input :disabled="true" v-model="formData.boatData.english"></el-input>
        </el-form-item>
        <el-form-item label="职务"  class="allwidth">
           <el-select v-model="formData.boatData.duty" placeholder="请选择">
            <el-option v-for="(item,index) in jobs":key="index" :label="item" :value="item">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="VESSEL">
          <!-- <el-input v-model="formData.boatData.vessel"></el-input> -->
          <el-select v-model="formData.boatData.vessel" placeholder="请选择">
            <el-option v-for="(item,index) in shipnames" :key="index" :label="item.title" :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="FLEET">
          <el-input v-model="formData.boatData.fleet"></el-input>
        </el-form-item>
        <el-form-item label="OWNER POOL" class="double-blank allwidth">
          <el-select v-model="formData.boatData.ownerPool" placeholder="请选择">
            <el-option v-for="(item,index) in owenerpoolArray" :key="index" :label="item.title" :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="MANNING OFFICE" class="double-blank allwidth">
          <el-select v-model="formData.boatData.manningOffice" placeholder="请选择">
            <el-option v-for="(item,index) in manningofficeArray" :key="index" :label="item" :value="item">
            </el-option>
          </el-select>
        </el-form-item>
      </el-form>
    </div>
    
    <!-- 船东 -->
    <div class="overflow-y add-set" v-else-if="addType==2">
      <el-form label-width="100px" :model="formData.shipOwnerData"  class="my-form" ref="addForm">
        <el-form-item label="客户名称" prop="title" :rules="[{required: true, message: '请输入客户名称', trigger: 'blur' }]">
          <el-input v-model="formData.shipOwnerData.title"></el-input>
        </el-form-item>

        <el-form-item label="属性" class="allwidth" prop="attribute" :rules="[{ required: true, message: '属性必须有', trigger: 'blur' }]">
          <el-select v-model="formData.shipOwnerData.attribute" placeholder="请选择">
            <el-option v-for="(item,index) in attrArray" :key="index" :label="item" :value="item">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="简称" prop="alias" :rules="[{ required: true, message: '简称必须有', trigger: 'blur' }]">
          <el-input v-model="formData.shipOwnerData.alias"></el-input>
        </el-form-item>
        <el-form-item label="业务分组" class="allwidth">
          <el-input v-model="formData.shipOwnerData.group"></el-input>
        </el-form-item>
        <el-form-item label="发展日期" class="allwidth">
          <el-date-picker
            v-model="formData.shipOwnerData.developDate"
            type="date"
            placeholder="选择日期"
            >
          </el-date-picker>
        </el-form-item>

        <el-form-item label="业务主管" class="allwidth" prop="business" :rules="[{required: true, message: '业务主管为空', trigger: 'blur' }]">
          <el-select v-model="formData.shipOwnerData.business" filterable multiple placeholder="请选择" class="mul-box" title="可搜索">
            <el-option v-for="(item,index) in business" :key="index" :label="item.title" :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="财务负责人" class="allwidth float-none" prop="principal" :rules="[{required: true, message: '负责人为空', trigger: 'blur' }]" >
          <el-select v-model="formData.shipOwnerData.principal" filterable multiple placeholder="请选择" class="mul-box" title="可搜索">
            <el-option v-for="(item,index) in financeList" :key="index" :label="item.username" :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
      </el-form>
    </div>

    <!-- 船名 -->
    <div class="overflow-y add-set" v-else-if="addType==3">
      <el-form label-width="100px" :model="formData.shipnameData" class="my-form" ref="addForm">
        <el-form-item label="船名" prop="title" :rules="[{ required: true, message: '请输入船名', trigger: 'blur' }]">
          <el-input v-model="formData.shipnameData.title"></el-input>
        </el-form-item>

        <el-form-item label="属性" class="allwidth">
          <el-select v-model="formData.shipnameData.attribute" placeholder="请选择">
            <el-option v-for="item in attrArrayname" :key="item" :label="item" :value="item">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="FLEET">
          <el-input v-model="formData.shipnameData.fleet"></el-input>
        </el-form-item>
        <el-form-item label="发展日期" class="allwidth">
          <el-date-picker
            v-model="formData.shipnameData.developDate"
            type="date"
            placeholder="选择日期">
          </el-date-picker>
        </el-form-item>
      </el-form>
    </div>

    <div class="overflow-y add-set" v-else-if="addType=='14'">
      <el-form label-width="100px" class="my-form" ref="addForm">
        <el-form-item label="客户名称" prop="name" :rules="[{ required: true, message: '客户名称', trigger: 'blur' }]">
          <el-input v-model="formData.refundSet.name"></el-input>
        </el-form-item>
      </el-form>
    </div>

    <!-- 借还款 -->
    <div class="overflow-y add-set" v-else-if="addType==15">
      <el-form label-width="80px" :model="formData.repay" :rules="rules" class="my-form" ref="addForm">
        <el-form-item label="借款时间" class="allwidth">
          <el-date-picker v-model="formData.repay.date" type="date" placeholder="选择日期">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="记账年月" class="allwidth">
          <el-date-picker v-model="formData.repay.tally" type="month" placeholder="选择日期">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="姓名" prop="name">
          <el-autocomplete
            v-model="formData.repay.name"
            :fetch-suggestions="querySearchAsync"
            placeholder="请输入内容"
            @select="handleSelect"
          ></el-autocomplete>
        </el-form-item>
        <el-form-item label="身份证号" prop="id_number" >
          <el-input v-model="formData.repay.id_number"></el-input>
        </el-form-item>
        <el-form-item label="借款金额">
          <el-input v-model="formData.repay.amount">
            <el-select v-model="formData.repay.currency" slot="prepend" placeholder="请选择" style="width:83px">
              <el-option label="人民币" value="人民币"></el-option>
              <el-option label="美元" value="美元"></el-option>
            </el-select>
          </el-input>
        </el-form-item>
        <el-form-item label="借款原因" prop="reason">
          <el-input v-model="formData.repay.reason"></el-input>
        </el-form-item>
        <el-form-item label="变更人">
          <el-input v-model="formData.repay.changer" :readonly="true"></el-input>
        </el-form-item>
        <el-form-item label="变更时间">
          <el-input v-model="formData.repay.change_date" :readonly="true"></el-input>
        </el-form-item>
      </el-form>
    </div>

    <div class="overflow-y add-set" v-else-if="addType==16">
      <el-form label-width="90px" :model="formData.accident" class="my-form" ref="addForm" >
        <el-form-item label="姓名" prop="name" :rules="[{ required: true, message: '请输入姓名', trigger: 'blur' }]">
          <el-autocomplete
            v-model="formData.accident.name"
            :fetch-suggestions="querySearchAsync"
            placeholder="请输入内容"
            @select="handleSelect"
          ></el-autocomplete>
        </el-form-item>

        <el-form-item label="身份证号" class="allwidth">
          <el-input v-model="formData.accident.id_number" :readonly="true"></el-input>
        </el-form-item>

        <el-form-item label="投保单位" :rules="[{ required: true, message: '请选择投保单位', trigger: 'blur' }]">
          <el-select v-model="formData.accident.supplier" placeholder="请选择">
            <el-option v-for="(item,index) in suplieArray" :key="index" :label="item.title" :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="生效时间" class="allwidth"  :rules="[{ required: true, message: '请选择生效时间', trigger: 'blur' }]">
          <el-date-picker
            v-model="formData.accident.starttime"
            type="date"
            placeholder="选择日期">
          </el-date-picker>
        </el-form-item>

        <el-form-item label="到期时间" class="allwidth"  :rules="[{ required: true, message: '请选择到期时间', trigger: 'blur' }]">
          <el-date-picker
            v-model="formData.accident.endtime"
            type="date"
            placeholder="选择日期">
          </el-date-picker>
        </el-form-item>


        <el-form-item label="个人承担金额">
          <el-input v-model="formData.accident.person"></el-input>
        </el-form-item>
        <el-form-item label="公司承担金额">
          <el-input v-model="formData.accident.company"></el-input>
        </el-form-item>
      </el-form>
    </div>

    <div class="overflow-y add-set" v-else-if="addType==18">
      <el-form label-width="90px" :model="formData.changerInfo" class="my-form" ref="addForm" >
        <el-form-item label="姓名" prop="name" :rules="[{ required: true, message: '请输入姓名', trigger: 'blur' }]">
          <el-autocomplete
            v-model="formData.changerInfo.name"
            :fetch-suggestions="querySearchAsync"
            placeholder="请输入内容"
            @select="handleSelect"
          ></el-autocomplete>
        </el-form-item>

        <el-form-item label="身份证号" class="allwidth" :rules="rules.idNumber">
          <el-input v-model="formData.changerInfo.id_number" :readonly="true" ></el-input>
        </el-form-item>

        <el-form-item label="收费金额">
          <el-input v-model="formData.changerInfo.money"></el-input>
        </el-form-item>

        <el-form-item label="收费日期" class="allwidth">
          <el-date-picker
            v-model="formData.changerInfo.time"
            type="date"
            placeholder="选择日期">
          </el-date-picker>
        </el-form-item>

        <el-form-item label="变更人">
          <el-input v-model="formData.changerInfo.changer" :readonly="true"></el-input>
        </el-form-item>
        <el-form-item label="变更日期">
          <el-input v-model="formData.changerInfo.changer_date" :readonly="true"></el-input>
        </el-form-item>
      </el-form>
    </div>
    
    <!-- 供应商 -->
    <div class="overflow-y add-set" v-else-if="addType==20">
      <el-form label-width="100px" :model="formData.supplierInfo" :formRule="rules" class="my-form" ref="addForm">

        <el-form-item label="供应商名称" prop="title" :rules="[{ required: true, message: '请输入供应商民称', trigger: 'blur' }]">
          <el-input v-model="formData.supplierInfo.title"></el-input>
        </el-form-item>

        <el-form-item label="属性" class="allwidth">
          <el-input v-model="formData.supplierInfo.attribute" :readonly="false"></el-input>
        </el-form-item>

        <el-form-item label="发展日期" class="allwidth">
          <el-date-picker v-model="formData.supplierInfo.developDate" type="date" placeholder="选择日期">
          </el-date-picker>
        </el-form-item>

        <el-form-item label="备注">
          <el-input v-model="formData.supplierInfo.remark"></el-input>
        </el-form-item>
      </el-form>
    </div>

    <div class="overflow-y add-set" v-else-if="addType==21">
      <el-form label-width="100px" :model="formData.staffInfo" :rules="rules" class="my-form staff-form" ref="addForm">
        <div class="head">
          <div class="left-content">
            <el-form-item v-for="(item,index) in formData.staffData.head" :key="index" :prop="item.special" v-if="!item.hidden" :label="item.label" :class="item.class">
              <el-input v-if="item.type=='input'" v-model="item.value" @change="editStaff(item)"></el-input>
              <el-select v-else-if="item.type=='select'&&!item.isSpecial" class="select" v-model="item.value" placeholder="请选择" @change="editStaff(item,index)">
                <el-option v-for="(item,index) in item.array" :key="index" :label="item" :value="item">
                </el-option>
              </el-select>
              <el-select v-else-if="item.type=='select'&&item.isSpecial" class="select" v-model="item.value" placeholder="请选择" @change="editStaff(item)">
                <el-option v-for="(item,index) in item.array" :key="index" :label="item.title" :value="item.id">
                </el-option>
              </el-select>
              <el-date-picker v-else-if="item.type=='time'" v-model="item.value" type="date" placeholder="选择日期" @change="editStaff(item)"></el-date-picker>
              
              <el-radio-group v-else-if="item.type=='check'" v-model="item.value" @change="editStaff(item)">
                <el-radio v-for="(one,index1) in item.array" :label="one" :key="index1">{{one}}</el-radio>
              </el-radio-group>

            </el-form-item>
          </div>
          <div class="img-box right-content" title="上传图片">
            <el-upload
              class="avatar-uploader"
              :action="imgs.action"
              :show-file-list="false"
              :on-success="handleAvatarSuccess"
              accept="image/*"
              :on-change="changeImg"
              ref="uploadImg"
              :auto-upload="false"
              >
              <img v-if="imgs.loadImg" :src="imgs.loadImg" class="avatar">
              <img v-else :src="imgs.default" alt="">
            </el-upload>
          </div>
        </div>
        <div class="body">
          <el-form-item v-for="(item,index) in formData.staffData.body" :key="index" :prop="item.special" :label="item.label" :class="item.isLong?item.class+' long-hide':item.class">
            <el-input v-if="item.type=='input'" v-model="item.value" :readonly="item.readonly" @change="editStaff(item)"></el-input>
            <el-select v-else-if="item.type=='select'" class="select" v-model="item.value" placeholder="请选择" @change="editStaff(item)">
              <el-option v-for="(item,index) in item.array" :key="index" :label="item" :value="item">
                </el-option>
            </el-select>
            <el-date-picker v-else-if="item.type=='time'" v-model="item.value" type="date" placeholder="选择日期" @change="editStaff(item)"></el-date-picker>
          </el-form-item>
        </div>
      </el-form>
    </div>



    <!-- 权限 -->

    <div class="overflow-y" v-else-if="addType==22">
      <el-form label-width="90px" :model="formData.permissionsAgent" class="my-form" ref="addForm" >
        <el-form-item label="代理人" prop="name" :rules="[{ required: true, message: '代理人姓名必填', trigger: 'blur' }]">
          <el-autocomplete
            v-model="formData.permissionsAgent.name"
            :fetch-suggestions="querySearchAsync"
            placeholder="搜索代理人姓名简写"
            @select="handleSelect"
          ></el-autocomplete>
        </el-form-item>

        <el-form-item class="job-describle" label="代理时间" prop="date" :rules="[{ required: true, message: '请选择代理时间', trigger: 'blur' }]">
          <el-date-picker range-separator="至" start-placeholder="代理开始日期" end-placeholder="代理结束日期" v-model="formData.permissionsAgent.date" type="daterange" placeholder="选择日期" @change="changtime" ></el-date-picker>
        </el-form-item>
      </el-form>
    </div>

    <div class="overflow-y" v-else-if="addType==23">
      <el-form label-width="90px" :model="formData.roleManager" class="my-form" ref="addForm" >
        <el-form-item label="角色名称" prop="roleName" :rules="[{ required: true, message: '请输入角色名称', trigger: 'blur' }]">
          <el-input v-model="formData.roleManager.roleName"></el-input>
        </el-form-item>

        <el-form-item class="job-describle" label="职能描述" prop="remark" :rules="[{ required: true, message: '请输入职能描述', trigger: 'blur' }]">
          <el-input type="textarea" :rows="6" v-model="formData.roleManager.remark"></el-input>
        </el-form-item>
      </el-form>
    </div>

    <div class="upload-box" v-show="addType!=23&&addType!=22">
      <span style="display:inline-block;width:100px;text-align:right">批量添加：</span>
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
    var testID=(rule, value, callback)=>{
        if (value === '') {
          callback(new Error('请输入身份证/护照号'));
        }else if(!/^\d{6}(18|19|20)?\d{2}(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])\d{3}(\d|[xX])$/.test(value)&&!(/^1[45][0-9]{7}|G[0-9]{8}|P[0-9]{7}|S[0-9]{7,8}|D[0-9]+$/.test(value))){
          callback(new Error('身份证/护照号格式不正确'));
        }else{
          callback()
        }
    }
    return {
      // 在新增框中来判断是什么新增
      addType: 1, //1是船员新增
      formData: {
        boatData: {
          vessel: "",
          ownerPool: "",
          manningOffice: "",
          cid:"",
          duty:"",
          name:"",
          english:"",
          idNumber:"",
          fleet:"",
        },
        shipOwnerData: {
          attribute: "",
          group: "",
          title: "",
          developDate:"",
          alias:"",//简称
          business:[],
        },
        shipnameData:{
          title:"",
          attribute:"",
          fleet:"",
          developDate:"",
        },

        refundSet:{
          name:"",//客户名称
        },

        repay:{
          date:"",
          tally:"",
          id:"",
          name:"",
          id_number:"",
          amount:"0",
          reason:"意外险",
          currency:"人民币",
          changer:"",
          change_date:""
        },
        accident:{
          name:"",
          id_number:"",
          id:"",
          supplier:"",
          starttime:"",
          endtime:"",
          person:"0",
          company:"0",
        },
        changerInfo:{
          name:"",
          id:"",
          time:"",
          money:"",
          changer:"",
          id_number:"",
          changer_date:""
        },

        supplierInfo:{
          attribute:"",
          title:"",
          developDate:"",
          remark:"",
        },
        
        staffData:{
          head:[
            {
              label:"档案号",
              type:"input",
              value:"",
              class:"width_2",
              special:"fn",
            },{
              label:"姓名",
              type:"input",
              value:"",
              class:"width_2",
              special:"username",
            },{
              label:"身份证号",
              type:"input",
              value:"",
              class:"width_2",
              special:"idNumber",
            },{
              label:"部门",
              type:"select",
              array:[],
              arrayName:"department",
              value:"",
              class:"width_2",
              special:"department",
            },{
              label:"财务负责人",
              type:"check",
              array:["是","否"],
              value:"",
              class:"width_2",
              special:"principal",
              hidden:true
            },{
              label:"职务",
              type:"input",
              value:"",
              class:"width_2",
              special:"duty",
            },{
              label:"船东",
              type:"select",
              array:[],
              isSpecial:true,
              arrayName:"owners",
              value:"",
              class:"width_2",
              special:"shipowner",
            },{
              label:"性别",
              type:"select",
              array:['男',"女"],
              arrayName:"",
              value:"",
              class:"width_2",
              special:"gender",
            },{
              label:"任命职务",
              type:"input",
              value:"",
              class:"width_2",
              special:"appoint_duty",
            },{
              label:"任命时间",
              type:"time",
              value:"",
              class:"width_2",
              special:"appoint_date",
            }
          ],
          body:[
            {
              label:"到司日期",
              type:"time",
              value:"",
              class:"width_4",
               special:"working_date",
            },{
              label:"离职日期",
               special:"",
              type:"time",
              value:"",
              class:"width_4",
               special:"dimission_date",
            },{
              label:"司龄",
              type:"input",
              value:"",
              class:"width_4",
              special:"assume_office_date",
              readonly:true,
            },{
              label:"出生日期",
              type:"time",
              value:"",
              class:"width_4",
              special:"birthday",
            },{
              label:"年龄",
              type:"input",
              value:"",
              class:"width_4",
              special:"age",
              readonly:true,
            },{
              label:"学历",
              type:"input",
              value:"",
              class:"width_4",
               special:"edu_background",
            },{
              label:"学位",
              type:"input",
              value:"",
              class:"width_4",
               special:"degree",
            },{
              label:"专业",
              type:"input",
              value:"",
              class:"width_4",
               special:"major",
            },{
              label:"毕业院校",
              type:"input",
              value:"",
              class:"width_4",
               special:"school",
            },{
              label:"毕业时间",
              type:"time",
              value:"",
              class:"width_4",
               special:"graduation_date",
            },{
              label:"劳动合同开始日期",
              type:"time",
              isLong:true,
              value:"",
              class:"width_4",
               special:"sign_start",
            },{
              label:"劳动合同结束日期",
              type:"time",
              isLong:true,
              value:"",
              class:"width_4",
               special:"sign_end",
            },{
              label:"专业技术职称",
              type:"input",
              value:"",
              class:"width_4",
               special:"professional_skill",
            },{
              label:"船上任职资格",
              type:"input",
              value:"",
              class:"width_4",
               special:"qualification",
            },{
              label:"户口地",
              type:"input",
              value:"",
              class:"width_4",
               special:"residence",
            },{
              label:"政治面貌",
              type:"input",
              value:"",
              class:"width_4",
              special:"political_status",
            },{
              label:"婚否",
              type:"select",
              array:["是","否"],
              arrayName:"",
              value:"",
              class:"width_4",
               special:"marry",
            },{
              label:"参加工作日期",
              type:"time",
              value:"",
              class:"width_4",
               special:"work_date",
            },{
              label:"出生地",
              type:"input",
              value:"",
              class:"width_4",
              special:"birthplace",
            },{
              label:"转正日期",
               special:"",
              type:"time",
              value:"",
              class:"width_4",
              special:"regular_date",
            },{
              label:"手机",
              type:"input",
              value:"",
              class:"width_4",
               special:"phone_number",
            },{
              label:"电话",
              type:"input",
              value:"",
              class:"width_4",
               special:"telnumber",
            },{
              label:"邮箱",
              type:"input",
              value:"",
              class:"width_5",
               special:"email",
            },{
              label:"住址",
              type:"input",
              value:"",
              class:"width_5",
               special:"address",
            },{
              label:"备注",
              type:"input",
              value:"",
              class:"width_6",
               special:"remark",
            }
          ]
        },
        staffInfo:{
          fn:"",
          username:"",
          idNumber:"",
          gender:"",//性别
          department:"",
          duty:"",
          shipowner:"",
          appoint_duty:"",
          appoint_date:"",
          working_date:"",
          dimission_date:"",
          assume_office_date:"",
          birthday:"",
          age:"",
          edu_background:"",
          degree:"",
          major:"",
          school:"",
          graduation_date:"",
          sign_start:"",
          sign_end:"",
          professional_skill:"",
          qualification:"",
          residence:"",
          political_status:"",
          marry:"",
          work_date:"",
          birthplace:"",
          regular_date:"",
          phone_number:"",
          telnumber:"",
          email:"",
          address:"",
          remark:"",
          url:"",
          principal:"否"
        },

        permissionsAgent:{
          agentId:"",
          date:[],
          name:""
        },

        roleManager:{
          roleName:"",
          remark:"",
        }
      },

      imgs:{
        action:PATH+"staff/uploadImg",
        default:require("../../../assets/imgs/staff/default.png"),
        loadImg:"",
      },
      changer:{

      },

      rules:{
        idNumber:[{ validator: testID, trigger: 'blur',required: true}],
        id_number:[{ validator: testID, trigger: 'blur',required: true}],
        cid:[{required:true,message:'cid不能为空',trigger: 'blur'}],
        name:[{required:true,message:'姓名不能为空',trigger: 'blur'}],

        reason:[{required:true,message:'借款原因不能为空',trigger: 'blur'}],
        

        fn:[{trigger: 'blur',required:true,message:"档案号不能空"}],
        username:[{trigger: 'blur',required:true,message:"姓名不能为空"}],
        department:[{trigger: 'blur',required:true,message:"部门不能为空"}],
        duty:[{trigger: 'blur',required:true,message:"职务不能为空"}],
        // appoint_date:[{trigger: 'blur',required:true,message:"任命日期不能为空"}],
        // work_date:[{trigger: 'blur',required:true,message:"工作日期不能为空"}]
      },

      fileList: [],//上传的文件

      suplieArray:[],//供应商
      department:[],
      owners:[],
      owenerpoolArray:[],
      jobs:[],
      manningofficeArray: ["自由船员","分包方船员"],
      shipnames:[],
      attrArrayname:["整船","单排"],
      attrArray: ["境内客户","境外客户"], //属性下拉
      business:[],

      timeSet:null,

      urls:{
        importUrl:"",//通过路由判断上传的地址
        addBoaters:"Mariner/addMariner",//添加船员
        toEnglish:"privilege/zw2py",//转英文名
        importBoaters:"Mariner/importMariner",//导入船员
        
        addShipOwner:"Mariner/addShipowner",//添加船东
      
        addShipname:"Mariner/addVessel",//

        addBorrow:"borrow/addBorrow",

        addAccident:"borrow/addInsurance",

        addChargeInfo:"charge/addCharge",

        addSupplier:"supplier/addSupplier",

        addStaff:"staff/addStaff",

        addPermit:"privilege/agentAdd",//添加权限代理

        addManagerRole:"privilege/addRole",//添加角色管理


        searchStaff:"staff/getUser",
      }
    };
  },
  methods: {
    changtime(value){
      if(value){
        if((new Date(value[1]).getTime()+3600*1000*24)<new Date().getTime()){
          _g.toMessage(this,"warning","结束时间不能小于当前时间")
          this.formData.permissionsAgent.date=null
        }else{
          this.formData.permissionsAgent.date=[_g.formatTime(value[0]),_g.formatTime(value[1])]
        }
      }else{
        this.formData.permissionsAgent.date=null
      }
    },
    closeThis(str) {
      if (str == "save") {
        var allUrls=["","addBoaters","addShipOwner","addShipname","","addBorrow","addAccident","","addChargeInfo","","addSupplier","addStaff","addPermit","addManagerRole"]
        var allParams=["","boatData","shipOwnerData","shipnameData","","repay","accident","","changerInfo","","supplierInfo","staffInfo","permissionsAgent","roleManager"]
        var addType=this.addType<4?this.addType:this.addType-10

        var url=this.urls[allUrls[addType]]
        var params=this.formData[allParams[addType]]

        
        if(params.developDate)params.developDate=params.developDate.length==10?params.developDate:_g.formatTime(params.developDate)
        if(params.starttime)params.starttime=params.starttime.length==10?params.starttime:_g.formatTime(params.starttime)
        if(params.endtime)params.endtime=params.endtime.length==10?params.endtime:_g.formatTime(params.endtime)
        if(params.tally)params.tally=params.tally.length==7?params.tally:_g.formatTime(params.tally,"month")

        if(params.time)params.time=params.time.length==10?params.time:_g.formatTime(params.time)
        if(!Array.isArray(params.date)&&params.date)params.date=params.date.length==10?params.date:_g.formatTime(params.date)
        // if(Array.isArray(params.date))
        for(var key in params){
          // if(params[key])params[key]=params[key].toString().trim()
        }

        // console.log(params)

        // return 
        



        if(this.addType==23||this.$refs.upload.uploadFiles.length==0){
          // 上传文件优先
          this.$refs["addForm"].validate((valid) => {
            if (valid) {
              this.apiPost(url,params).then(res=>{
                _g.toMessage(this,res.error?"error":"success",res.msg)
                
                if(!res.error){
                  this.$store.state.allDialog.add =res.error?true:false
                  if(this.addType==20){
                    this.$store.state.reflash.contentReflash=false
                    setTimeout(()=>{this.$store.state.reflash.contentReflash=true},0)
                  }else{
                    setTimeout(()=>{
                      bus.$emit("search_new_result")
                    },100)
                  }
                  
                }
              })
            }
          });
        }
        if(this.$refs.upload.uploadFiles.length>0){
          this.$refs.upload.submit()
        }
      }else{
        this.$store.state.allDialog.add = false;
      }
      
    },

    editStaff(item,index){
      if(index==3){
        if(item.value=="财务部"){
          this.formData.staffData.head[4].hidden=false
        }else{
          this.formData.staffData.head[4].hidden=true
        }
      }
      if(item.type!="time"){
        this.formData.staffInfo[item.special]=item.value
        if(item.special=="idNumber"){
          var bir = _g.getMyItem(this.formData.staffData.body,"label","出生日期")
          var s_age=_g.getMyItem(this.formData.staffData.body,"label","年龄")
          var year=new Date().getFullYear()
          if(/^((\d{18})|([0-9x]{18})|([0-9X]{18}))$/.test(item.value)){
            s_age.value=year-item.value.slice(6,10)
            this.formData.staffInfo.age=s_age
            bir.value=item.value.slice(6,10)+"-"+item.value.slice(10,12)+"-"+item.value.slice(12,14)
            this.formData.staffInfo.birthday=bir.value
          }else{
            s_age.value=""
            this.formData.staffInfo.age=""
            bir.value=""
            this.formData.staffInfo.birthday=""
          }
        }
      }else if(item.special=="working_date"||item.special=="dimission_date"){
        this.formData.staffInfo[item.special]=_g.formatTime(item.value)
        var c_age=_g.getMyItem(this.formData.staffData.body,"label","司龄")
        var start_c_age=_g.getMyItem(this.formData.staffData.body,"label","到司日期")
        var end_c_age=_g.getMyItem(this.formData.staffData.body,"label","离职日期")

        var toYear= new Date().getFullYear()

        if(end_c_age.value&&start_c_age.value){
          c_age.value=_g.formatTime(end_c_age.value).slice(0,toYear.toString().length)-_g.formatTime(start_c_age.value).slice(0,toYear.toString().length)
        }else if(start_c_age.value){
          c_age.value=toYear-_g.formatTime(start_c_age.value).slice(0,toYear.toString().length)
        }else if(!start_c_age.value){
          c_age.value=null
        }

      }else if(item.special=="birthday"){
        // 联动bir
        var toYear= new Date().getFullYear()
        var bir_time=_g.getMyItem(this.formData.staffData.body,"label","出生日期")
        var age=_g.getMyItem(this.formData.staffData.body,"label","年龄")
        if(bir_time.value){
          age.value=toYear-_g.formatTime(bir_time.value).slice(0,toYear.toString().length)
        }else{
          age.value=null
        }
         this.formData.staffInfo[item.special]=_g.formatTime(bir_time.value)
         this.formData.staffInfo.age=age.value
         console.log(this.formData.staffInfo)
      }else{
        this.formData.staffInfo[item.special]=_g.formatTime(item.value)
      }
    },

    // 获取英文名
    getEnglishName(name){
      this.apiPost(this.urls.toEnglish,{chinese:name}).then(res=>{
        if(this.addType==1){
          this.formData.boatData.english=res.english
        }
      })
    },

    querySearchAsync(queryString, cb){
      if(queryString){
        if(this.addType==22){
          this.apiPost("staff/getUser",{abbreviation:queryString}).then(res=>{
            if(res.error){
              _g.toMessage(this,"error",res.msg)
              return
            }
            var results=[]
            res.forEach(ele=>{
              results.push({
                id:ele.id,
                value:ele.info,
                id_number:ele.id_number
              })
            })
            if(this.timeSet)clearTimeout(this.timeSet)
            this.timeSet=setTimeout(()=>{
              cb(results)
            },600)
          })
        }else{
          this.apiPost("borrow/nameSearch",{name:queryString}).then(res=>{
            if(res.error){
              _g.toMessage(this,"error",res.msg)
              return
            }
            var results=[]
            res.forEach(ele=>{
              results.push({
                id:ele.id,
                value:ele.info,
                id_number:ele.id_number
              })
            })
            if(this.timeSet)clearTimeout(this.timeSet)
            this.timeSet=setTimeout(()=>{
              cb(results)
            },600)
            if(results.length==0){
              this.formData.accident.name=""
              this.formData.accident.id_number=""
              this.formData.accident.id=""
              this.formData.repay.name=""
              this.formData.repay.id_number=""
              this.formData.repay.id=""
            }
          })
        }
        
      }
    },
    handleSelect(item){
      if(this.addType==15){
        this.formData.repay.name=item.value.slice(0,item.value.indexOf("+"))
        this.formData.repay.id_number=item.id_number
        this.formData.repay.id=item.id
      }else if(this.addType==16){
        this.formData.accident.name=item.value.slice(0,item.value.indexOf("+"))
        this.formData.accident.id_number=item.id_number
        this.formData.accident.id=item.id
      }else if(this.addType==18){
        this.formData.changerInfo.name=item.value.slice(0,item.value.indexOf("+"))
        this.formData.changerInfo.id_number=item.id_number
        this.formData.changerInfo.id=item.id
      }else if(this.addType==22){
        // console.log(item)
        this.formData.permissionsAgent.agentId=item.id
      }
    },

     // 上传图片成功
    handleAvatarSuccess(res,file){
      if(!res.error){
        this.formData.staffInfo.url=res.msg
        return true
      }
      return false
    },
    changeImg(file,fileList){
      this.imgs.loadImg=file.url
    },

    handleRemove(file, fileList) {
      // console.log(file, fileList);
    },
    handlePreview(file) {
      // console.log(file);
    },
    uploadResult(response){
      _g.toMessage(this,response.error?"error":"success",response.msg)
      this.$store.state.allDialog.add =response.error!=0
      this.$refs.upload.uploadFiles=[]
      if(!response.error)setTimeout(()=>{bus.$emit("search_new_result")},700)
    },
    uploadError(){
      _g.toMessage(this,"error","请检查网络")
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
    this.owenerpoolArray=this.$store.state.tableListData.owners
    this.jobs=this.$store.state.tableListData.jobs
    this.shipnames=this.$store.state.tableListData.shipnames
    this.suplieArray=this.$store.state.tableListData.Insure
    this.department=this.$store.state.tableListData.department
    this.owners=this.$store.state.tableListData.owners
    this.business=[...this.$store.state.tableListData.business]
    this.financeList=[...this.$store.state.tableListData.financeList]

    this.formData.staffData.head.forEach(ele=>{
      if(ele.type=="select"){
        if(ele.array.length==0){
          ele.array=this[ele.arrayName]
        }
      }
    })

    if (routerpath == "/boaters") {
      this.addType = 1;
      this.urls.importUrl=PATH+"Mariner/importMariner"
    } else if (routerpath == "/shipowners") {
      this.addType = 2;
      this.urls.importUrl=PATH+"Mariner/importShipowner"
    }else if (routerpath == "/shipnames") {
      this.addType = 3;
      this.urls.importUrl=PATH+"Mariner/importVessel"
    }else if(routerpath=="/refundSet"){
      this.addType =14;  //后台灭有
    }else if(routerpath=="/repayMoney"){
        this.addType=15  //借还款
        this.urls.importUrl=PATH+"borrow/importBorrow"
        this.apiPost("borrow/getChanger").then(res=>{
          this.formData.repay.changer=res.username
          this.formData.repay.change_date=res.time
        })
    }else if(routerpath=="/accident"){
        this.addType=16
        this.urls.importUrl=PATH+"borrow/importInsurance"

    }else if(routerpath=="/repaycompare"){
        this.addType=17
    }else if(routerpath=="/chargeInfo"){
        this.addType=18 
        this.urls.importUrl=PATH+"charge/importCharge"
        this.apiPost("borrow/getChanger").then(res=>{
          this.formData.changerInfo.changer=res.username
          this.formData.changerInfo.changer_date=res.time
        })
    }else if(routerpath=="/chargeCompare"){
        this.addType=19  
    }else if(routerpath=="/supplierInfo"){
        this.addType=20 
        this.urls.importUrl=PATH+"supplier/importSupplier"
    }else if(routerpath=="/staffInfo"){
        this.addType=21 
        this.urls.importUrl=PATH+"privilege/importStaff"
    }else if(routerpath=="/permissionsAgent"){
        this.addType=22
    }else if(routerpath=="/roleManager"){
        this.addType=23
    }else if(routerpath=="/accountManager"){
        this.addType=24
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
  padding: 8px 0 5px 0
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

.head,.body{
  width: 100%!important;
  display: flex;
}
.body{
  flex-wrap: wrap
}
.left-content{
  width: 0;
  flex-grow: 1;
  display: flex;
  flex-wrap: wrap;
}
.img-box{
  width: 120px;
  height: 140px;
  /* border: 1px solid red; */
  box-sizing: border-box;
  margin-left: 20px;
  position: relative
}
.img-box img{
  width: 100%;
  position: absolute;
  max-height: 100%;
  top: 50%;
  transform: translateY(-50%)
}

.width_1{
  width: 25%;
}
.width_2{
  width: 33%;
}
.width_3{
  width: 40%;
}
.width_4{
  width: 25%;
}
.width_5{
  width: 50%;
}
.width_6{
  width: 100%;
}
.avatar-uploader{
  overflow: hidden;
}
.my-form>.job-describle{
  width: 100%;
}

.float-none{
  margin-top: 0px!important;
}
.mul-box{
  height: 40px!important;
}

</style>
