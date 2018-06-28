const state = {
  // 登录成功保存的用户信息
  userInfo:{
     
  },
  changerInfo:{
    time:"",
    username:"",
  },
  // 页签
  theader:{
    label:"系统首页",
    showback:false, //显示返回
    showfrash:true,//显示刷新
  },

  
  // 信息通知
  infoNotice:{
    showList:false,//右上角铃铛
    warningData:{},
    infoNum:0
  },

  // 刷新本地
  reflash:{
    searchReflash:true,//使用v-if
    contentReflash:true,
    wordsReset:false,
    searchReset:false,

    tableReflash:true,

    leftmenu:true
  },

  // 所有的对话框
  allDialog:{
    homepageSetShow:false, //首页设置
    add:false,  //新增
    query:false,//搜索设置
    wordsSet:false,//字段设置
    editShow:false,//船东编辑
    batch:false, //批量上传
    extendShow:false,//extend是否展开
    extendAdd:false,//增加--extend
    repay:false, //还款信息窗口

    sure:false,//确认窗口

    searchExtend:false,//是否藏起搜索

    download:false,//是否下载

    showNoRight:false,//是否有访问权限
  },
  sureData:{
    title:"提示",
    content:"这是需要提示的信息",
    OK:function(){console.log("clicking")}
  },
  addData:{
    extendTitle:"设置比例"
  },
  editData:{
    editId:"",//正在编辑的id
    info:"",//正在编辑的info

    isCopy:false,
    
    printData:null,//即将打印的数据

    roleName:"",//正在修改权限的角色名
  },
  // 选中下载的id
  selectIdArray:[],

  downExcell:{
    rule:[],//下载的规则
    data:[],//需要进行下载的数据
    isSpecial:false,//是否是要下载费用明细
  },
  searchSetData:{ //搜索设置起始数据
    boaters:[
      {
        label:"简称",
        type:"input",
        value:"",
        special:"jc"
      },{
        label:"姓名",
        type:"select",
        arrayName:"nameData",
        array:[],
        value:"",
        special:"name"
      },{
        label:"身份证/护照",
        type:"input",
        value:"",
        special:"idNumber"
      },{
        label:"CID",
        type:"input",
        value:"",
        special:"cid"
      },{
        label:"职务",
        type:"select",
        arrayName:"jobs",
        isMul:true,
        array:[],
        value:"",
        special:"duty"
      },{
        label:"FLEET",
        type:"input",
        value:"",
        special:"fleet",
      },{
        label:"OWNER POOL",
        type:"select",
        isSpecial:true,
        filterable:true,
        arrayName:"owners",
        isMul:true,
        array:[],
        value:"",
        special:"ownerPool"
      },{
        label:"MANNING OFFICE",
        type:"select",
        arrayName:"offices",
        array:[],
        value:"",
        special:"office"
      },{
        label:"VESSEL",
        type:"select",
        arrayName:"shipnames",
        isSpecial:true,
        isMul:true,
        array:[],
        value:"",
        special:"vessel",
      }
    ],
    shipowners:[
      {
        label:"简称",
        type:"input",
        value:"",
        special:"alias"
      },{
        label:"客户名称",
        type:"input",
        value:"",
        special:"title"
      },{
        label:"属性",
        type:"select",
        arrayName:"attributes",
        array:[],
        value:"",
        special:"attribute"
      },{
        label:"业务分组",
        type:"input",
        value:"",
        special:"group"
      },{
        label:"发展日期",
        isBig:true,
        type:"time",
        value:"",
      }
    ],
    shipnames:[
      {
        label:"船名",
        type:"input",
        value:"",
        special:"vessel",
      },{
        label:"属性",
        type:"select",
        arrayName:"attrArray",
        array:[],
        value:"",
        special:"attribute"
      },{
        label:"发展日期",
        type:"time",
        isBig:true,
        value:"",
      },{
        label:"FLEET",
        type:"input",
        value:"",
        special:"fleet"
      }
    ],
    securityset:[
      {
        label:"参保地区",
        type:"select",
        arrayName:"allareaArray",
        isMul:true,
        array:"",
        value:"",
        special:"area"
      },{
        label:"时间范围",
        type:"month",
        value:"",
        startTime:"",
        endTime:"",
        title:"时间范围包括开始结束时间"
      }
    ],
    securityinsurance:[
      {
        label:"简称",
        type:"input",
        value:"",
        special:"jc"
      },{
        label:"姓名",
        type:"select",
        arrayName:"nameData",
        array:[],
        value:"",
        special:"name"
      },{
        label:"身份证/护照",
        type:"input",
        value:"",
        special:"idNumber"
      },{
        label:"CID",
        type:"input",
        value:"",
        special:"cid"
      },{
        label:"社保状态",
        type:"select",
        arrayName:"insuranceState",
        array:[],
        value:"",
        special:"status"
      },{
        label:"参保地区",
        type:"select",
        arrayName:"allareaArray",
        array:[],
        isMul:true,
        value:"",
        special:"area",
        ismulti:true,
      },{
        label:"缴费时间",
        type:"month",
        type:"month",
        isBig:true,
        width_50:false,
        value:[],
        formal:true,
        startTime:"",
        endTime:""
      },{
        label:"是否停过保",
        type:"select",
        arrayName:"stopArray",
        array:[],
        value:"",
        special:"isStop",
      }
    ],
    securityinfo:[
      {
        label:"简称",
        type:"input",
        value:"",
        special:"jc"
      },{
        label:"CID",
        type:"input",
        value:"",
        special:"cid"
      },{
        label:"姓名",
        type:"select",
        arrayName:"nameData",
        array:[],
        value:"",
        special:"name"
      },{
        label:"身份证/护照",
        type:"input",
        value:"",
        special:"idNumber"
      },{
        label:"是否补缴",
        type:"select",
        arrayName:"stopArray",
        value:"",
        special:""
      },{
        label:"船队",
        type:"select",
        arrayName:"",
        value:"",
        special:"fleet",
      },{
        label:"船东",
        type:"select",
        array:[],
        arrayName:"owners",
        filterable:true,
        isSpecial:true,
        value:"",
        special:"ownerPool",
      },{
        label:"参保地区",
        type:"select",
        arrayName:"allareaArray",
        array:[],
        value:"",
        special:"area",
        ismulti:true,
      },{
        label:"缴费时间",
        type:"month",
        isBig:true,
        width_50:false,
        value:[],
        formal:true,
        startTime:"",
        endTime:""
      },{
        label:"缴费类型",
        type:"select",
        arrayName:"payType",
        value:"",
        special:"type",
      }
    ],

    securitycompare:[
      {
        label:"参保地区",
        type:"select",
        arrayName:"allareaArray",
        isMul:true,
        array:[],
        value:"",
        special:"area"
      },{
        label:"时间范围",
        type:"month",
        isBig:true,
        width_50:false,
        value:[],
        formal:true,
        startTime:"",
        endTime:""
      }
    ],

    refundRecordBoaters:[ //报销记录
      {
        label:"简称",
        type:"input",
        value:"",
        special:"jc"
      },{
        label:"姓名",
        type:"select",
        arrayName:"nameData",
        array:[],
        value:"",
        special:"name"
      },{
        label:"船队",
        type:"input",
        value:"",
        special:"fleet"
      },{
        label:"客户",
        type:"select",
        array:[],
        arrayName:"owners",
        isMul:true,
        filterable:true,
        isSpecial:true,
        value:[],
        special:"shipownerId"
      },{
        label:"船名",
        type:"select",
        array:[],
        arrayName:"shipnames",
        filterable:true,
        isSpecial:true,
        isMul:true,
        value:"",
        special:"vesselId"
      },
      {
        label:"状态",
        type:"select",
        array:[],
        arrayName:"stateR",
        // isMul:true,
        isSpecial:true,
        value:[],
        special:"status"
      },{
        label:"报销总计",
        type:"number_2",
        start:"",
        end:"",
        special:"total"
      },{
        label:"报销时间",
        type:"time",
        isBig:true,
        value:"",
        special:"date"
      },{
        label:"报销地点",
        type:"select",
        array:[],
        arrayName:"refundArea",
        filterable:true,
        isMul:true,
        value:[],
        special:"address"
      },{
        label:"供应商",
        type:"select",
        array:[],
        arrayName:"Insure",
        filterable:true,
        isMul:true,
        value:[],
        special:"suppliers",
        isSpecial:true,
      }
    ], 
    refundRecordStaff:[
      {
        label:"简称",
        type:"input",
        value:"",
        special:"jc_b"
      },{
        label:"姓名",
        type:"select",
        arrayName:"nameData",
        array:[],
        value:"",
        special:"name"
      },
      {
        label:"部门",
        type:"select",
        arrayName:"department",
        array:[],
        isMul:true,
        value:"",
        special:"department"
      },
      {
        label:"状态",
        type:"select",
        array:[],
        arrayName:"stateR",
        isSpecial:true,
        value:"",
        special:"status"
      },{
        label:"报销总计",
        type:"number_2",
        start:"",
        end:"",
        special:"total"
      },{
        label:"报销时间",
        type:"time",
        isBig:true,
        value:"",
        special:"date"
      },{
        label:"客户",
        type:"select",
        array:[],
        arrayName:"owners",
        isMul:true,
        filterable:true,
        isSpecial:true,
        value:[],
        special:"shipownerId"
      },{
        label:"报销地点",
        type:"select",
        array:[],
        arrayName:"refundArea",
        isMul:true,
        filterable:true,
        value:[],
        special:"address"
      },{
        label:"差旅天数",
        type:"number_2",
        start:"",
        end:"",
        special:"days"
      }
    ],
    refundRecordOffice:[
      {
        label:"简称",
        type:"input",
        value:"",
        special:"jc_b"
      },{
        label:"姓名",
        type:"select",
        arrayName:"nameData",
        array:[],
        value:"",
        special:"name"
      },{
        label:"部门",
        type:"select",
        arrayName:"department",
        array:[],
        isMul:true,
        value:"",
        special:"department"
      },
      {
        label:"状态",
        type:"select",
        array:[],
        arrayName:"stateR",
        // isMul:true,
        isSpecial:true,
        value:[],
        special:"status"
      },{
        label:"报销总计",
        type:"number_2",
        value:[],
        special:"expense"
      },{
        label:"报销时间",
        type:"time",
        isBig:true,
        value:"",
        special:"date"
      },{
        label:"报销地点",
        type:"select",
        array:[],
        arrayName:"refundArea",
        isMul:true,
        filterable:true,
        value:[],
        special:"address"
      },{
        label:"客户",
        type:"select",
        array:[],
        arrayName:"owners",
        isMul:true,
        filterable:true,
        isSpecial:true,
        value:[],
        special:"shipOwner"
      },{
        label:"报销项目",
        type:"select",
        array:[],
        arrayName:"project",
        orderGroup:false, //分组
        isMul:true,
        filterable:true,
        value:[],
        special:"project"
      }
    ],
    refundSignBoater:[//报销签批
      {
        label:"简称",
        type:"input",
        value:"",
        special:"jc"
      },{
        label:"姓名",
        type:"select",
        arrayName:"nameData",
        array:[],
        value:"",
        special:"name"
      },{
        label:"船队",
        type:"input",
        value:"",
        special:"fleet",
      },{
        label:"船名",
        type:"select",
        value:"",
        isSpecial:true,
        array:[],
        isMul:true,
        arrayName:"shipnames",
        special:"vessel_id"
      },{
        label:"客户",
        type:"select",
        array:[],
        arrayName:"owners",
        isMul:true,
        filterable:true,
        isSpecial:true,
        value:[],
        special:"shipowner_id"
      },{
        label:"CID",
        type:"input",
        value:"",
        special:"cid"
      },{
        label:"状态",
        type:"select",
        array:[],
        arrayName:"stateR",
        isSpecial:true,
        isMul:true,
        value:[],
        special:"status"
      },{
        label:"报销总计",
        type:"number_2",
        value:"",
        special:"total"
      },{
        label:"报销时间",
        type:"time",
        isBig:true,
        value:"",
        special:"time"
      },{
        label:"报销地点",
        type:"select",
        array:[],
        arrayName:"refundArea",
        isMul:true,
        filterable:true,
        value:[],
        special:"address"
      }
    ],
    refundSignStaff:[
      {
        label:"简称",
        type:"input",
        value:"",
        special:"abbreviation"
      },{
        label:"报销人",
        type:"input",
        value:"",
        special:"username"
      },{
        label:"部门",
        type:"select",
        arrayName:"department",
        array:[],
        isMul:true,
        value:"",
        special:"department"
      },{
        label:"职务",
        type:"select",
        arrayName:"userjobs",
        array:[],
        isMul:true,
        value:"",
        special:"duty"
      },{
        label:"状态",
        type:"select",
        array:[],
        arrayName:"state",
        isMul:true,
        value:[],
        special:"status"
      },{
        label:"报销总计",
        type:"number_2",
        value:"",
        special:"total"
      },{
        label:"报销时间",
        type:"time",
        isBig:true,
        value:"",
        special:"time"
      },{
        label:"出发日期",
        type:"time",
        isBig:true,
        value:"",
        special:"start_date"
      },{
        label:"报销地点",
        type:"select",
        array:[],
        arrayName:"refundArea",
        isMul:true,
        filterable:true,
        value:[],
        special:"address"
      },{
        label:"差旅天数",
        type:"number_2",
        value:"",
        special:"days"
      },{
        label:"返程日期",
        type:"time",
        isBig:true,
        value:"",
        special:"end_date"
      }
    ],
    refundSignOffice:[
      {
        label:"简称",
        type:"input",
        value:"",
        special:"abbreviation"
      },{
        label:"报销人",
        type:"input",
        value:"",
        special:"username"
      },{
        label:"部门",
        type:"select",
        arrayName:"department",
        array:[],
        isMul:true,
        value:"",
        special:"department"
      },{
        label:"职务",
        type:"select",
        arrayName:"userjobs",
        array:[],
        isMul:true,
        value:"",
        special:"duty"
      },{
        label:"状态",
        type:"select",
        array:[],
        arrayName:"state",
        isMul:true,
        value:[],
        special:"status"
      },{
        label:"报销总计",
        type:"number_2",
        value:"",
        special:"total"
      },{
        label:"报销时间",
        type:"time",
        isBig:true,
        value:"",
        special:"time"
      },{
        label:"报销地点",
        type:"select",
        array:[],
        arrayName:"refundArea",
        isMul:true,
        filterable:true,
        value:[],
        special:"address"
      },{
        label:"报销项目",
        type:"select",
        array:[],
        arrayName:"project",
        orderGroup:false, //分组
        isMul:true,
        filterable:true,
        value:[],
        special:"project"
      }
    ],
    refundStastics:[
      {
        label:"简称",
        type:"input",
        value:"",
        special:"jc"
      },{
        label:"姓名",
        type:"select",
        arrayName:"nameData",
        array:[],
        value:"",
        special:"name"
      },{
        label:"部门",
        type:"select",
        arrayName:"department",
        array:[],
        value:"",
        special:""
      },{
        label:"职务",
        type:"select",
        arrayName:"jobs",
        array:[],
        value:"",
        special:""
      },{
        label:"客户",
        type:"select",
        array:[],
        arrayName:"owners",
        isMul:true,
        filterable:true,
        isSpecial:true,
        value:[],
        special:""
      },{
        label:"船队",
        type:"select",
        arrayName:"",
        value:"",
        special:"fleet",
      },{
        label:"船名",
        type:"input",
        value:"",
        special:""
      },{
        label:"报销总计",
        type:"number_2",
        value:"",
        special:""
      },{
        label:"报销时间",
        type:"time",
        isBig:true,
        value:"",
        special:""
      },{
        label:"报销地点",
        type:"select",
        array:[],
        arrayName:"refundArea",
        isMul:true,
        filterable:true,
        value:[],
        special:""
      },{
        label:"费用发生日期",
        type:"time",
        isBig:true,
        value:"",
        special:""
      },{
        label:"报销项目",
        type:"select",
        array:[],
        arrayName:"project",
        isMul:true,
        orderGroup:false, //分组
        filterable:true,
        value:[],
        special:""
      }
    ],

    repayMoney:[
      {
        label:"简称",
        type:"input",
        value:"",
        special:"jc",
        title:"搜索姓名的首字母"
      },{
        label:"姓名",
        type:"select",
        arrayName:"nameData",
        array:[],
        value:"",
        special:"name"
      },{
        label:"币种",
        type:"select",
        arrayName:"currencyData",
        array:[],
        value:"",
        special:"currency"
      },{
        label:"借款原因",
        type:"input",
        // type:"select",
        // isMul:true,//是否多选
        // arrayName:"reasonData",
        // array:[],
        value:"",
        special:"reason"
      },{
        label:"是否结清",
        type:"select",
        arrayName:"stopArray",
        array:[],
        value:"",
        special:"ifSettle"
      },{
        label:"借款日期",
        type:"time",
        isBig:true,
        value:"",
        special:"date"

      },{
        label:"记账年月",
        type:"month",
        formal:true,
        type:"month",
        startTime:"",
        endTime:"",
        special:"tally",
      }
    ],
    accident:[
      {
        label:"简称",
        type:"input",
        value:"",
        special:"jc",
        title:"搜索姓名的首字母"
      },{
        label:"姓名",
        type:"select",
        arrayName:"nameData",
        array:[],
        value:"",
        special:"name"
      },{
        label:"投保单位",
        type:"select",
        isSpecial:true,
        arrayName:"Insure",
        array:[],
        isMul:true,
        value:"",
        special:"supplier"
      },{
        label:"身份证号",
        type:"input",
        value:"",
        special:"idNumber"
      },{
        label:"时间范围",
        type:"time",
        isBig:true,
        value:"",
      }
    ],
    repaycompare:[
      {
        label:"时间范围",
        type:"month",
        formal:true,
        isBig:true,
        value:[],
        startTime:"",
        endTime:""
      }
    ],

    chargeInfo:[
      {
        label:"简称",
        type:"input",
        value:"",
        special:"jc",
        title:"搜索姓名的首字母"
      },{
        label:"姓名",
        type:"select",
        arrayName:"nameData",
        array:[],
        value:"",
        special:"name"
      },{
        label:"借款日期",
        type:"time",
        isBig:true,
        value:"",
      },{
        label:"是否结清",
        type:"select",
        isSpecial:true,
        arrayName:"settleArray",
        array:[],
        value:"",
        special:"settle"
      }
    ],
    chargeCompare:[
      {
        label:"时间范围",
        type:"month",
        isBig:true,
        width_50:false,
        value:[],
        formal:true,
        startTime:"",
        endTime:""
      }
    ],
    supplierInfo:[
      {
        label:"供应商名称",
        type:"input",
        value:"",
        special:"title",
      },{
        label:"属性",
        type:"select",
        filterable:true,
        // isSpecial:true,
        isMul:true,
        arrayName:"attributesArray",
        array:[],
        value:"全部",
        special:"attribute"
      },{
        label:"发展日期",
        type:"time",
        isBig:true,
        value:"",
      }
    ],
    staffInfo:[
      {
        label:"简称",
        type:"input",
        value:"",
        special:"abbreviation"
      },{
        label:"姓名",
        type:"input",
        value:"",
        special:"username"
      },{
        label:"身份证号",
        type:"input",
        value:"",
        special:"idNumber"
      },{
        label:"性别",
        type:"select",
        arrayName:"sexData",
        array:[],
        value:"",
        special:"gender"
      },{
        label:"部门",
        type:"select",
        arrayName:"department",
        array:[],
        value:"",
        special:"department"
      },{
        label:"职务",
        type:"input",
        arrayName:"jobs",
        array:[],
        value:"",
        special:"duty"
      },{
        label:"船东",
        type:"select",
        arrayName:"owners",
        isSpecial:true,
        filterable:true,
        array:[],
        value:"",
        special:"shipowner"
      },{
        label:"任命职务",
        type:"input",
        value:"",
        special:"appoint_duty"
      },{
        label:"年龄",
        type:"number",
        value:"",
        special:"age"
      },{
        label:"任命时间",
        type:"time",
        isBig:true,
        value:"",
        special:"appoint_date"
      },{
        label:"到司时间",
        type:"time",
        isBig:true,
        value:"",
        special:"working_date"
      },{
        label:"离职时间",
        type:"time",
        isBig:true,
        value:"",
        special:"dimission_date"
      },{
        label:"司龄",
        type:"number_2",
        value:"",
        special:"assume_office_date"
      },{
        label:"婚否",
        type:"select",
        arrayName:"isMarried",
        array:[],
        value:"",
        special:"marry"
      },{
        label:"出生时间",
        type:"time",
        isBig:true,
        value:"",
        special:"birthday"
      },{
        label:"学历",
        type:"select",
        arrayName:"education",
        array:[],
        value:"",
        special:"education"
      },{
        label:"学位",
        type:"select",
        arrayName:"degree",
        array:[],
        value:"",
        special:"degree"
      },{
        label:"专业",
        type:"input",
        value:"",
        special:"major"
      },{
        label:"毕业日期",
        type:"time",
        isBig:true,
        value:"",
        special:"graduation_date"
      },{
        label:"毕业院校",
        type:"input",
        value:"",
        special:"school"
      },{
        label:"专业技术职称",
        type:"input",
        value:"",
        special:""
      },{
        label:"政治面貌",
        type:"select",
        arrayName:"organization",
        array:[],
        value:"",
        special:"organization"
      },{
        label:"户口地",
        type:"input",
        value:"",
        special:"residence"
      },{
        label:"劳动合同起始日",
        type:"time",
        isBig:true,
        value:"",
        special:"sign_start"
      },{
        label:"劳动合同终止日",
        type:"time",
        isBig:true,
        value:"",
        special:"sign_end"
      },{
        label:"参加工作日期",
        type:"time",
        isBig:true,
        value:"",
        special:"work_date"
      },{
        label:"船上任职资格",
        type:"input",
        value:"",
        special:"qualification"
      },{
        label:"出生地",
        type:"input",
        value:"",
        special:"birthplace"
      },{
        label:"转正日期",
        type:"time",
        isBig:true,
        value:"",
        special:"regular_date"
      },{
        label:"电话",
        type:"input",
        value:"",
        special:"phone_number"
      },{
        label:"手机",
        type:"input",
        value:"",
        special:"telnumber"
      },{
        label:"邮箱",
        type:"input",
        value:"",
        special:"email"
      },{
        label:"住址",
        type:"input",
        value:"",
        special:"address"
      },{
        label:"备注",
        type:"input",
        value:"",
        special:"remark"
      },
    ],
    
    accountManager:[
      {
        label:"简称",
        type:"input",
        value:"",
        special:"jc_b"
      },{
        label:"姓名",
        type:"select",
        arrayName:"nameData",
        array:[],
        value:"",
        special:"name"
      },{
        label:"身份证号",
        type:"input",
        value:"",
        special:"idNumber"
      },{
        label:"部门",
        type:"select",
        arrayName:"department",
        array:[],
        isMul:true,
        value:"",
        special:"department"
      },{
        label:"角色",
        type:"select",
        arrayName:"roles",
        isSpecial:true,
        array:[],
        value:"",
        special:"roleId"
      }
    ]
  },
  searchData:{  //搜索存放的数据
    boaters:{
      listRows:10,//pagesize
      page:1,  
      idNumber:"",
      cid:"",
      fleet:"",
      ownerPool:[],
      vessel:[],
      manningOffice:"",
      id:"", //
      duty:[],
    },
    shipowners:{
      listRows:10,//pagesize
      alias:"",//简称
      page:1,  
      title:"",
      attribute:"",
      group:"",
      time:[],
    },
    shipnames:{
      page:1,
      time:[],
      fleet:"",
      listRows:10,//pagesize
      vessel:"",//船名
      attribute:""
    },
    securityset:{
      listRows:10,//pagesize
      page:1,
      area:[],//地区名
      time:[],
    },
    securityinsurance:{
      listRows:10,//pagesize
      page:1,
      idNumber:"",
      time:[],
      id:"",
      cid:"",
      area:[],
      status:"",//社保状态
      isStop:"",//是否停过宝
    },
    securityinfo:{
      listRows:10,//pagesize
      id:"",
      page:1,
      cid:"",
      vessel:"",//船队id
      idNumber:"",
      ownerPool:"",//船队id
      area:"",
      time:[],
      type:"",
    },
    securitycompare:{
      page:1,
      listRows:10,//pagesize
      area:[],
      time:[],
    },
    refundRecordBoaters:{//船员报销记录
      page:1,
      listRows:10,
    },
    refundRecordStaff:{ //员工报销记录
      page:1,
      listRows:10,
    },
    refundRecordOffice:{
      page:1,
      listRows:10,
    },
    refundSignBoater:{
      page:1,
      listRows:10,
    },
    refundSignStaff:{
      page:1,
      listRows:10,
      id:""
    },
    refundSignOffice:{
      page:1,
      listRows:10,
    },
    refundStastics:{
      page:1,
      listRows:10,
    },

    repayMoney:{
      page:1,
      listRows:10,
    },
    accident:{
      page:1,
      listRows:10,
    },
    repaycompare:{
      page:1,
      listRows:10,
      time:[],
    },

    chargeInfo:{
      page:1,
      listRows:10,
      time:[],
      settle:"",//1，2
    },

    chargeCompare:{
      page:1,
      listRows:10,
      time:[],
    },

    supplierInfo:{
      title:"",
      attribute:"",
      page:1,
      listRows:10,
      time:[],
    },

    staffInfo:{
      page:1,
      listRows:10,
      abbreviation:"",
      username:"",
      idNumber:"",
      gender:"",
      department:"",
      duty:"",
      shipowner:"",//船东id
      fn:"",
      appoint_duty:"",
      age:"",
      working_date:[],
      // "working_date[0]":"",
      // "working_date[1]":"",
      dimissing_date:[],
      // "dimissing_date[0]":"",
      // "dimissing_date[1]":"",
      assume_office:[],
      // "assume_office[0]":"",
      // "assume_office[0]":"",
      marry:"",
      education:"",
      degree:"",
      major:"",
      school:"",
      professional_skill:"",
      organization:"",
      residence:"",
      sign_start:[],
      // "sign_start[0]":"",
      // "sign_start[1]":"",
      work_date:[],
      // "work_date[0]":"",
      // "work_date[1]":"",
      qualification:"",
      birthplace:"",
      regular_date:[],
      "regular_date[0]":"",
      "regular_date[1]":"",
      phone_number:"",
      telnumber:"",
      email:"",
      address:"",
      remark:""
    },

    permissionsAgent:{
      page:1,
      listRows:10
    },

    accountManager:{
      id:"",
      idNumber:"",
      department:"",
      roleId:"",
      page:1,
      listRows:10
    },
    roleManager:{
      page:1,
      listRows:10,
    }

  },
  tableListData:{
    listData:[],//列表
    total:0,//总条数

    owners:[],//船东
    shipnames:[],//船名
    jobs:[],//职务
    roles:[],//角色
    department:[],//部门信息
    education:[],//学历
    degree:[],//学位
    organization:[],//政治面貌
    business:[],//业务主管
    financeList:[],//负责人
    allareaArray:[],
    attributesArray:[],//
    shiperList:[],
    reasonData:[],

    Insure:[],//全部的供应商信息


    boatersList:[
      {type: "selection" ,isShow: true},
      {type: "index",label:"序号",isShow: true},
      {prop: "cid",label: "CID",width: "100",isShow: true},
      {prop: "name",label: "姓名",width: "80",isShow: true},
      {prop: "id_number",label:"身份证/护照", width:"150",isShow:true},
      {prop: "duty",label: "职务",isShow: true,width: "80"},
      {prop: "manning_office",label: "MANNING OFFICE",isShow: true,width: "130"},
      {prop: "fleet",label: "FLEET",isShow: true},
      {prop: "ownerPool",label: "OWNER POOL",isShow: true,width:"120"},
      {prop: "vessel",label: "VESSEL",isShow: true}
    ],  
    shipownersList:[
      {type: "index",label:"序号",isShow: true},
      {prop: "title",label: "客户名称",width: "80",isShow: true},
      {prop: "alias",label: "简称",isShow: true},
      {prop: "attribute",label:"属性", width:"150",isShow:true},
      {prop: "group",label: "业务分组",isShow: true,width: "150"},
      {prop: "develop_date",label: "发展日期",isShow: true,width: "120"},
      {prop: "business",label: "业务主管",isShow: true,width:"150",showOverflowTooltip:true},
      {prop: "principal",label: "财务负责人",isShow: true,width:"150",showOverflowTooltip:true},
    ],
    shipnamesList:[
      {type: "index",label:"序号",isShow: true},
      {prop: "title",label: "船名",isShow: true},
      {prop: "attribute",label: "属性",isShow: true},
      {prop: "fleet",label:"FLEET",isShow:true},
      {prop: "develop_date",label: "发展日期",isShow: true,},
    ],
    securitysetList:[//没有进行字段设置的社保模板
      {
        label:"合计",
        isShow:false,
        checked:[],
        children:[
          {prop: "totalCompany",label: "合计(公司)",isShow: true},
          {prop: "totalPerson",label: "合计(个人)",isShow: true},
          {prop: "company",label: "五险合计(公司)",isShow: true},
          {prop: "person",label: "五险合计(个人)",isShow: true},
          {prop: "companyElse",label: "添加项(公司)",isShow: true},
          {prop: "personElse",label: "添加项(个人)",isShow: true}]
      },
      {
        label:"养老",
        isShow:false,
        checked:[],
        children:[
          {prop: "base_company0",label: "养老基数(公司)",isShow: true},
          {prop: "rate_company0",label: "养老费率(公司)%",isShow: true},
          {prop: "amount_company0",label: "养老固定金额(公司)",isShow: true},
          {prop: "total_company0",label: "养老合计(公司)",isShow: true},
          {prop: "base_person0",label: "养老基数(个人)",isShow: true},
          {prop: "rate_person0",label: "养老费率(个人)%",isShow: true},
          {prop: "amount_person0",label: "养老固定金额(个人)",isShow: true},
          {prop: "total_person0",label: "养老合计(个人)",isShow: true}]
      },{
        label:"医疗",
        isShow:false,
        checked:[],
        children:[
          {prop: "base_company1",label: "医疗基数(公司)",isShow: true},
          {prop: "rate_company1",label: "医疗费率(公司)%",isShow: true},
          {prop: "amount_company1",label: "医疗固定金额(公司)",isShow: true},
          {prop: "total_company1",label: "医疗合计(公司)",isShow: true},
          {prop: "base_person1",label: "医疗基数(个人)",isShow: true},
          {prop: "rate_person1",label: "医疗费率(个人)%",isShow: true},
          {prop: "amount_person1",label: "医疗固定金额(个人)",isShow: true},
          {prop: "total_person1",label: "医疗合计(个人)",isShow: true}]
      },{
        label:"失业",
        isShow:false,
        checked:[],
        children:[
          {prop: "base_company2",label: "失业基数(公司)",isShow: true},
          {prop: "rate_company2",label: "失业费率(公司)%",isShow: true},
          {prop: "total_company2",label: "失业固定金额(公司)",isShow: true},
          {prop: "total_company2",label: "失业合计(公司)",isShow: true},
          {prop: "base_person2",label: "失业基数(个人)",isShow: true},
          {prop: "rate_person2",label: "失业费率(个人)%",isShow: true},
          {prop: "amount_person2",label: "失业固定金额(个人)",isShow: true},
          {prop: "total_person2",label: "失业合计(个人)",isShow: true}]
      },{
        label:"工伤",
        isShow:false,
        checked:[],
        children:[
          {prop: "base_company3",label: "工伤基数(公司)",isShow: true},
          {prop: "rate_company3",label: "工伤费率(公司)%",isShow: true},
          {prop: "total_company3",label: "工伤固定金额(公司)",isShow: true},
          {prop: "total_company3",label: "工伤合计(公司)",isShow: true},
          {prop: "base_person3",label: "工伤基数(个人)",isShow: true},
          {prop: "rate_person3",label: "工伤费率(个人)%",isShow: true},
          {prop: "amount_person3",label: "工伤固定金额(个人)",isShow: true},
          {prop: "total_person3",label: "工伤合计(个人)",isShow: true}]
      },{
        label:"生育",
        isShow:false,
        checked:[],
        children:[
          {prop: "base_company4",label: "生育基数(公司)",isShow: true},
          {prop: "rate_company4",label: "生育费率(公司)%",isShow: true},
          {prop: "total_company4",label: "生育固定金额(公司)",isShow: true},
          {prop: "total_company4",label: "生育合计(公司)",isShow: true},
          {prop: "base_person4",label: "生育基数(个人)",isShow: true},
          {prop: "rate_person4",label: "生育费率(个人)%",isShow: true},
          {prop: "amount_person4",label: "生育固定金额(个人)",isShow: true},
          {prop: "total_person4",label: "生育合计(个人)",isShow: true}]
      },{
        label:"备注",
        isShow:false,
        noChildren:true,
        checked:[],
        children:[{prop: "remark",label: "备注",width:"150",isShow: true,showOverflowTooltip:true}]
      }
    ],

    securityinsuranceList:[
      // {prop: "cid",label: "CID",isShow: true,fixed:true},
      // {prop: "name",label: "姓名",isShow: true,fixed:true},
      // {prop: "id_number",label: "身份证号",isShow: true,fixed:true,width:"180"},
      {prop: "lastInsuredArea",label: "最后参保地",isShow: true,width:"100"},
      {prop: "firsttime",label: "首次开始参保时间",isShow: true,width:"100"},
      {prop: "starttime",label: "最后一次参保时间",isShow: true,width:"100"},
      {prop: "stoptime",label: "最后一次停保开始月份",isShow: true,width:"100"},
      {prop: "status",label: "社保状态",isShow: true},
      {prop: "debt",label: "欠款金额",isShow: true},
      {prop: "arrearageDate",label: "欠款金额截止时间",isShow: true},
      {prop: "receipt",label: "财务已收金额合计",isShow: true},
      {prop: "amount_company",label: "公司合计",isShow: true},
      {prop: "amount_person",label: "个人合计",isShow: true},
      {prop: "assume_person",label: "个人承担单位金额合计",isShow: true,width:"100"},
      {prop: "remark",label: "备注",isShow: true,inputChange:true},
    ],

    securityinfoList:[ //社保信息
      {prop: "id_number",label: "身份证/护照",isShow: true,width:"160"},
      {prop: "duty",label: "职务",isShow: true},
      {prop: "vessel",label: "船队",isShow: true,showOverflowTooltip:true,width:"80"},
      {prop: "shipOwner",label: "船东",isShow: true,width:""},
      {prop: "area",label: "参保地",isShow: true,width:""},
      {prop: "first_date",label: "参保时间",isShow: true,width:""},
      {prop: "yanglao_company",label: "养老(公司)",isShow: true},
      {prop: "yiliao_company",label: "医疗(公司)",isShow: true},
      {prop: "shiye_company",label: "失业(公司)",isShow: true,width:""},
      {prop: "gongshang_company",label: "工伤(公司)",isShow: true,width:""},
      {prop: "shengyu_company",label: "生育(公司)",isShow: true,width:""},
      {prop: "else_company",label: "公司其他",isShow: true,width:"",editHere:true},
      {prop: "amount_company",label: "合计(公司)",isShow: true,width:""},
      {prop: "yanglao_person",label: "养老(个人)",isShow: true},
      {prop: "yiliao_person",label: "医疗(个人)",isShow: true},
      {prop: "shiye_person",label: "失业(个人)",isShow: true,width:""},
      {prop: "gongshang_person",label: "工伤(个人)",isShow: true,width:""},
      {prop: "shengyu_person",label: "生育(个人)",isShow: true,width:""},
      {prop: "else_person",label: "个人其他",isShow: true,width:"",editHere:true},
      {prop: "amount_person",label: "合计(个人)",isShow: true,width:""},
      {prop: "assume_person",label: "个人承担",isShow: true,width:"",editHere:true},
      {prop: "add_company",label: "添加项(公司)",isShow: true,width:"95",editHere:true},
      {prop: "add_person",label: "添加项(个人)",isShow: true,width:"95",editHere:true},
      {prop: "final_company",label: "实际合计(公司)",isShow: true,width:"110"},
      {prop: "final_person",label: "实际合计(个人)",isShow: true,width:"110"},
      {prop: "social_total",label: "社保合计",isShow: true,width:""},
      {prop: "final",label: "合计",isShow: true,width:""},
      {prop: "receipt",label: "已收金额",isShow: true,width:""},
      {prop: "debt",label: "欠款金额",isShow: true,width:""},
      // {prop: "",label: "是否补缴",isShow: true,width:""},
      {prop: "remark",label: "备注",isShow: true,width:""},
    ],

    securitycompareList:[
      {prop: "pay_month",label: "时间(年月)",isShow: true},
      {prop: "area",label: "参保地",isShow: true},
      {prop: "debt",label:"社保欠款(元)",isShow:true},
      {prop: "receipt",label: "社保还款(元)",isShow: true},
    ],

    refundRecordBoatersList:[
      {type: "index",label:"序号",isShow: true},
      {prop: "date",label: "报销时间",isShow: true},
      {prop: "name",label: "船员姓名",isShow: true},
      {prop: "duty",label:"职务", isShow:true},
      {prop: "shipowner",label: "客户",isShow: true,},
      {prop: "vessel",label: "船名",isShow: true,},
      {prop: "fleet",label: "船队",isShow: true,},
      {prop: "address",label: "报销地点",isShow: true},
      {prop: "reason",label:"差旅事由", isShow:true},
      {prop: "explain",label: "事由说明",isShow: true,},
      {prop: "total",label: "报销总计",isShow: true,},
      {prop: "remark",label: "备注",isShow: true,},
      {prop: "status",label: "状态",isShow: true,},
    ],
    
    refundRecordStaffList:[
      {type: "index",label:"序号",isShow: true},
      {prop: "username",label: "员工姓名",isShow: true},
      {prop: "date",label: "报销时间",isShow: true},
      {prop: "address",label: "报销地点",isShow: true},
      {prop: "start_date",label:"出发日期", isShow:true},
      {prop: "end_date",label: "返程日期",isShow: true,},
      {prop: "days",label: "差旅天数",isShow: true,},
      {prop: "reason",label: "差旅事由",isShow: true,},
      {prop: "explain",label: "事由说明",isShow: true},
      {prop: "total",label:"报销总计", isShow:true},
      {prop: "status",label: "状态",isShow: true,},
    ],

    refundRecordOfficeList:[
      {type: "index",label:"序号",isShow: true},
      {prop: "date",label: "报销时间",isShow: true},
      {prop: "username",label: "报销人",isShow: true},
      {prop: "address",label: "报销地点",isShow: true},
      {prop: "",label: "报销项目",isShow: true,notOne:true,childrenArrayName:'project',childrenKey:"project"},
      {prop: "",label: "费用",isShow: true,notOne:true,childrenArrayName:'project',childrenKey:"sum"},//childrenArrayName 需要遍历的scope.row中的key
      {prop: "total",label: "报销总计",isShow: true},
      {prop: "status",label: "状态",isShow: true},
    ],
    // 未匹配table的字符
    refundSignBoaterList:[
      // {type: "selection",isShow: true},
      {type: "index",label:"序号",isShow: true},
      {prop: "date",label: "报销时间",isShow: true},
      {prop: "name",label: "船员姓名",isShow: true},
      {prop: "duty",label: "职务",isShow: true},
      {prop: "shipowner",label: "客户",isShow: true,},
      {prop: "vessel",label: "船名",isShow: true},
      {prop: "fleet",label: "船队",isShow: true},
      {prop: "address",label: "报销地点",isShow: true},
      {prop: "reason",label: "差旅事由",isShow: true},
      {prop: "explain",label: "事由说明",isShow: true,},
      {prop: "total",label: "报销总计",isShow: true},
      {prop: "status",label: "状态",isShow: true},
    ],
    // 未匹配table的字符
    refundSignStaffList:[
      // {type: "selection",isShow: true},
      {type: "index",label:"序号",isShow: true},
      {prop: "date",label: "报销时间",isShow: true,width:"100"},
      {prop: "username",label: "报销人",isShow: true},
      {prop: "department",label: "部门",isShow: true},
      {prop: "duty",label: "职务",isShow: true,},
      {prop: "address",label: "报销地点",isShow: true},
      {prop: "start_date",label: "出发日期",isShow: true,width:"100"},
      {prop: "end_date",label: "返程日期",isShow: true,width:"100"},
      {prop: "days",label: "差旅天数",isShow: true},
      {prop: "reason",label: "差旅事由",isShow: true},
      {prop: "explain",label: "事由说明",isShow: true,showOverflowTooltip:true,},
      {prop: "total",label: "报销总计",isShow: true},
      {prop: "status",label: "状态",isShow: true},
    ],
    // 未匹配table的字符
    refundSignOfficeList:[
      // {type: "selection",isShow: true},
      {type: "index",label:"序号",isShow: true},
      {prop: "date",label: "报销时间",isShow: true},
      {prop: "username",label: "报销人",isShow: true},
      {prop: "department",label: "部门",isShow: true},
      {prop: "duty",label: "职务",isShow: true,},
      {prop: "address",label: "报销地点",isShow: true},
      {prop: "",label: "报销项目",isShow: true,notOne:true,childrenArrayName:'project',childrenKey:"project"},
      {prop: "",label: "费用",isShow: true,notOne:true,childrenArrayName:'project',childrenKey:"sum"},//childrenArrayName 需要遍历的scope.row中的key
      {prop: "total",label: "报销总计",isShow: true},
      {prop: "status",label: "状态",isShow: true},
    ],
    // 未匹配table的字符
    refundStasticsList:[
      {type: "index",label:"序号",isShow: true},
      {prop: "pay_month",label: "报销时间",isShow: true},
      {prop: "area",label: "报销人",isShow: true},
      {prop: "pay_month",label: "人员类型",isShow: true},
      {prop: "area",label: "出生日期",isShow: true,},
      {prop: "pay_month",label: "服务/部门",isShow: true},
      {prop: "area",label: "客户",isShow: true,},
      {prop: "pay_month",label: "船员",isShow: true,},
      {prop: "pay_month",label: "船队",isShow: true},
      {prop: "area",label: "报销地点",isShow: true},
      {prop: "pay_month",label: "报销项目",isShow: true},
      {prop: "pay_month",label: "费用承担方",isShow: true},
      {prop: "pay_month",label: "报销总计",isShow: true},
    ], 

    refundSetList:[
      {type: "selection",isShow: true},
      {type: "index",label:"序号",isShow: true},
      {prop: "pay_month",label: "客户",isShow: true},
      {prop: "area",label: "报销模板",isShow: true,isLink:true},
    ],

    repayMoneyList:[
      {type: "index",label:"序号",isShow: true},
      {prop: "date",label: "借款日期",isShow: true},
      {prop: "tally",label: "记账年月",isShow: true},
      {prop: "name",label:"中文姓名", isShow:true},
      {prop: "id_number",label: "身份证/护照",isShow: true,width:"180"},
      {prop: "currency",label: "币种",isShow: true,},
      {prop: "reason",label: "借款原因",isShow: true,},
      {prop: "amount",label: "借款金额",isShow: true},
      {prop: "repayment",label:"还款金额", isShow:true},
    ],
    accidentList:[
      {type: "index",label:"序号",isShow: true},
      {prop: "name",label: "中文姓名",isShow: true},
      {prop: "id_number",label: "身份证/护照",isShow: true,width:"180"},
      {prop: "title",label:"投保单位", isShow:true},
      {prop: "effect_time",label: "生效时间",isShow: true},
      {prop: "finish_time",label: "到期时间",isShow: true,},
      {prop: "person",label: "个人承担金额",isShow: true,},
      {prop: "company",label: "单位承担金额",isShow: true},
    ],

    repaycompareList:[
      {type: "index",label:"序号",isShow: true},
      {prop: "tally",label:"时间(年月)", isShow:true},
      {prop: "rmb_accident",label: "意外险借款(RMB)",isShow: true},
      {prop: "rmb_repayAccident",label: "意外险还款(RMB)",isShow: true,},
      {prop: "rmb_another",label: "其他借款(RMB)",isShow: true},
      {prop: "rmb_repayAnother",label: "其他还款(RMB)",isShow: true},
      {prop: "usd_amount",label: "借款金额(USD)",isShow: true,},
      {prop: "usd_repayment",label: "还款金额(USD)",isShow: true},
    ],

    chargeInfoList:[
      {type: "index",label:"序号",isShow: true},
      {prop: "date",label: "收款日期",isShow: true},
      {prop: "name",label: "船员姓名",isShow: true,},
      {prop: "id_number",label:"身份证/护照", isShow:true,width:"180"},
      {prop: "amount",label: "收款金额",isShow: true},
      {prop: "receipt",label: "已还款金额",isShow: true,},
      {prop: "surplus",label: "剩余金额",isShow: true,},
    ],
    chargeCompareList:[
      {type: "index",label:"序号",isShow: true},
      {prop: "month",label: "时间(年月)",isShow: true},
      {prop: "amount",label: "收费金额",isShow: true,},
      {prop: "repayment",label:"还款金额)", isShow:true},
    ],

    supplierInfoList:[
      {type: "index",label:"序号",isShow: true},
      {prop: "title",label: "供应商名称",isShow: true},
      {prop: "attribute",label: "属性",isShow: true,},
      {prop: "develop_date",label:"发展日期", isShow:true},
      {prop: "remark",label: "备注",isShow: true,showOverflowTooltip:true},
      {prop: "pay_before",label:"应付金额总计", isShow:true},
      {prop: "pay_after",label:"实付金额合计", isShow:true},
    ],

    staffInfoList:[
      {type: "index",label:"序号",isShow: true,fixed:"left"},
      {prop: "fn",label: "档案号",isShow: true,fixed:"left"},
      {prop: "username",label: "姓名",isShow: true,fixed:"left"},
      {prop: "id_number",label: "身份证号",isShow: true,width:"150",fixed:"left"},
      {prop: "gender",label: "性别",isShow: true},
      {prop: "department",label: "部门",isShow: true},
      {prop: "title",label: "船东",isShow: true,width:"100"},
      {prop: "duty",label: "职务",isShow: true},
      {prop: "appoint_duty",label: "任命职务",isShow: true},
      {prop: "appoint_date",label: "任命时间",isShow: true,width:"150"},
      {prop: "working_date",label: "到司日期",isShow: true,width:"150"},
      {prop: "dimission_date",label: "离职日期",isShow: true,width:"150"},
      {prop: "assume_office_date",label: "司龄",isShow: true},
      {prop: "birthday",label: "出生日期",isShow: true,width:"150"},
      {prop: "age",label: "年龄",isShow: true},
      {prop: "edu_background",label: "学历",isShow: true},
      {prop: "degree",label: "学位",isShow: true},
      {prop: "major",label: "专业",isShow: true,width:"100"},
      {prop: "school",label: "毕业院校",isShow: true,width:"100"},
      {prop: "graduation_date",label: "毕业日期",isShow: true,width:"150"},
      {prop: "sign_start",label: "劳动合同起始日期",isShow: true,width:"150"},
      {prop: "sign_end",label: "劳动合同终止日期",isShow: true,width:"150"},
      {prop: "professional_skill",label: "专业技术职称",isShow: true,width:"100"},
      {prop: "qualification",label: "船上任职资格",isShow: true,width:"100"},
      {prop: "residence",label: "户口地",isShow: true,width:"180"},
      {prop: "political_status",label: "政治面貌",isShow: true,width:"100"},
      {prop: "marry",label: "婚否",isShow: true,width:"100"},
      {prop: "work_date",label: "参加工作日期",isShow: true,width:"150"},
      {prop: "birthplace",label: "出生地",isShow: true,width:"100"},
      {prop: "regular_date",label: "转正日期",isShow: true,width:"150"},
      {prop: "address",label: "住址",isShow: true,width:"180"},
      {prop: "phone_number",label: "电话",isShow: true,width:"120"},
      {prop: "telnumber",label: "手机",isShow: true,width:"100"},
      {prop: "email",label: "邮箱",isShow: true,width:"150"},
      {prop: "remark",label: "备注",isShow: true,width:"100"},

    ],

    permissionsAgentList:[
      {prop: "agenttime",label: "代理时间",isShow: true},
      {prop: "agentName",label: "代理人",isShow: true,},
      {prop: "status",label:"状态", isShow:true},
    ],

    accountManagerList:[
      // {type: "selection" ,isShow: true},
      {type: "index",label:"序号",isShow: true},
      {prop: "id_number",label:"身份证号", isShow:true,width:"180"},
      {prop: "username",label: "姓名",isShow: true},
      {prop: "department",label: "部门",isShow: true,},
      {prop: "duty",label: "职位",isShow: true,},
      {prop: "name",label: "账户角色",isShow: true,},
    ]
 

  },

  // 加载中
  loading:{
    loadingEnable:false, //是否启用loading
    load:null,
    
  },

  // 保存leftmenu的相关数据，常用功能路径匹配字段
  leftmenuData:[
    {
      label: "首页",
      isactive: true,
      show:true,
      children: [
        {
          label: "系统首页",
          show:true,
          data: [
            {
              label: "系统首页",
              isactive: true,
              url: "/homepage",
              show:true,
            }
          ]
        }
      ]
    },
    {
      label: "船员",
      isactive: false,
      show:true,
      children: [
        {
          label: "船员管理",
          show:true,
          data: [
            {
              label: "船员信息",
              isactive: false,
              url: "/boaters",
              show:true,
            }
          ]
        },
        {
          label: "船东管理",
          show:true,
          data: [
            {
              label: "船东信息",
              isactive: false,
              url: "/shipowners",
              show:true,
            },
            {
              label: "船名信息",
              isactive: false,
              url: "/shipnames",
              show:true,
            }
          ]
        }
      ]
    },
    {
      label: "社保",
      isactive: false,
      show:true,
      children: [
        {
          label: "社保办理",
          show:true,
          data: [
            {
              label: "社保设置",
              isactive: false,
              url: "/securityset",
              show:true,
            },
            {
              label: "参保人员",
              isactive: false,
              url: "/securityinsurance",
              show:true,
            },
            {
              label: "社保信息",
              isactive: false,
              url: "/securityinfo",
              show:true,
            },
            {
              label: "社保对账",
              isactive: false,
              url: "/securitycompare",
              show:true,
            }
          ]
        }
      ]
    },
    {
      label: "报销",
      isactive: false,
      show:true,
      children: [
        {
          label: "添加报销",
          show:true,
          data: [
            {
              label: "船员费用报销",
              isactive: false,
              url: "/refundAddBoaters",
              show:true,
            },
            {
              label: "员工差旅报销",
              isactive: false,
              url: "/refundAddStaffs",
              show:true,
            },
            {
              label: "办公费用报销",
              isactive: false,
              url: "/refundAddOffice",
              show:true,
            }
          ]
        },
        {
          label: "报销记录",
          show:true,
          data: [
            {
              label: "船员差旅报销记录",
              isactive: false,
              url: "/refundRecordBoaters",
              show:true,
            },
            {
              label: "员工差旅报销记录",
              isactive: false,
              url: "/refundRecordStaff",
              show:true,
            },
            {
              label: "办公费用报销记录",
              isactive: false,
              url: "/refundRecordOffice",
              show:true,
            }
          ]
        },{
          label: "报销签批",
          show:true,
          data: [
            {
              label: "船员差旅报销签批",
              isactive: false,
              url: "/refundSignBoater",
              show:true,
            },
            {
              label: "员工差旅报销签批",
              isactive: false,
              url: "/refundSignStaff",
              show:true,
            },
            {
              label: "办公费用报销签批",
              isactive: false,
              url: "/refundSignOffice",
              show:true,
            }
          ]
        },
        // {
        //   label: "报销统计",
        //   show:true,
        //   data: [
        //     {
        //       label: "报销统计",
        //       isactive: false,
        //       url: "/refundStastics",
        //       show:true,
        //     }
        //   ]
        // },
        {
          label: "报销设置",
          show:true,
          data: [
            {
              label: "报销模板",
              isactive: false,
              url: "/refundSet",
              show:true,
            }
          ]
        }
      ]
    },{
      label: "借还款",
      isactive: false,
      show:true,
      children: [
        {
          label: "借还款管理",
          show:true,
          data: [
            {
              label: "借还款",
              isactive: false,
              url: "/repayMoney",
              show:true,
            },
            {
              label: "意外险",
              isactive: false,
              url: "/accident",
              show:true,
            },
            {
              label: "对账",
              isactive: false,
              url: "/repaycompare",
              show:true,
            },
          ]
        },
      ]
    },{
      label: "收费",
      show:true,
      isactive: false,
      children: [
        {
          label: "收费管理",
          show:true,
          data: [
            {
              label: "收费信息",
              isactive: false,
              url: "/chargeInfo",
              show:true,
            },
            {
              label: "收费对账",
              isactive: false,
              url: "/chargeCompare",
              show:true,
            },
          ]
        },
      ]
    },{
      label: "供应商",
      isactive: false,
      show:true,
      children: [
        {
          label: "供应商管理",
          show:true,
          data: [
            {
              label: "供应商信息",
              isactive: false,
              url: "/supplierInfo",
              show:true,
            },
          ]
        },
      ]
    },{
      label: "员工",
      isactive: false,
      show:true,
      children: [
        {
          label: "员工管理",
          show:true,
          data: [
            {
              label: "员工信息",
              isactive: false,
              url: "/staffInfo",
              show:true,

            },
            // {
            //   label: "组织",
            //   isactive: false,
            //   url: ""
            // },
          ]
        },
      ]
    },{
      label: "权限",
      isactive: false,
      show:true,
      children: [
        {
          label: "权限管理",
          show:true,
          data: [
            {
              label: "权限代理",
              isactive: false,
              url: "/permissionsAgent",
              show:true,
            },{
              label: "角色管理",
              isactive: false,
              url: "/roleManager",
              show:true,
            },{
              label: "账号管理",
              isactive: false,
              url: "/accountManager",
              show:true,
            },
          ]
        },
      ]
    }
  ],
}

export default state
