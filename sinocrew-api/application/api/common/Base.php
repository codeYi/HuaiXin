<?php
/**
 * @desc Created by PhpStorm.
 * @author: CodeYi
 * @since: 2018-04-13 18:28
 */
namespace app\api\common;
use think\Controller;
use think\Request;
use think\Session;
use think\Loader;
use think\Db;

/**
 * 基类控制器
 * Class Base
 * @package app\api\common
 */
class Base extends Controller
{
    //Request实例
    protected $request;
    //保存用户信息
    protected $user = [];
    //待审批数量
    protected $marinerSign=0;
    protected $userSign=0;
    protected $officeSign=0;
    protected $marinerWarn = 0;
    protected $userWarn = 0;
    protected $officeWarn = 0;
    protected $isMariner;
    protected $privilege=[];
    protected static $id;
    protected static $idnumber;
    protected static $listRows;
    //是否进行权限验证
    protected static $is_check_rule = true;

    /**
     * 构造函数
     * @param Request|null $request
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    function __construct(Request $request = null)
    {
        parent::__construct($request);
        //直接实例化request类方便后期调用
        $this->request = $request;
        //判断是否是post请求
        if(!($request->isPost())) return error_data('请求错误');
        self::$listRows = 15;
        $userInfo = cookie('user');
        if(!$userInfo){
            return error_data('请登录');
        }
        self::$id = Session::get('id');
        self::$idnumber = $userInfo['id_number'];
        $this->isMariner = Session::get('is_mariner');
        //用户信息
        $this->user = cookie('user');
        //获取用户的角色id
        $roleId = Db::name('role_user')->where(['user_id'=>$userInfo['id']])->value('role_id');
        $privilege = Db::name('access')
            ->alias('a')
            ->join('node b','a.node_id = b.id','LEFT')
            ->where(['role_id'=>$roleId])
            ->column('b.title');
        $this->privilege = Db::name('access')
            ->alias('a')
            ->join('node b','a.node_id = b.id','LEFT')
            ->where(['role_id'=>$roleId])
            ->column('b.name');
        $allPrivilege = Db::name('node')->column('title');
        //控制器、方法
        if($roleId == 1) self::$is_check_rule = false;
        if(self::$is_check_rule && !$this->isMariner) {
            $controller = mb_strtolower($this->request->controller());
            $action = mb_strtolower($this->request->action());
            $now = $controller . '/' . $action;
            //首页的功能列表可以查看
            if ( $now == "index/menulist" || !in_array($now,$allPrivilege)) {
                array_push($privilege,$now);
            }
            //权限判断 无权限无法操作
            if ( empty($privilege)||!in_array($now, $privilege)) {
                exit(json_encode(['error'=>2,'msg'=>"没有权限"]));
            }
        }
        //消息提醒
        if(!$this->isMariner){
            $this->userWarn = Db::name('user_expense')->where(['user_id'=>self::$id,'warn'=>1])->count();
            $this->officeWarn = Db::name('office_expense')->where(['user_id'=>self::$id,'warn'=>1])->count();
            $shiper_mariner = Db::name('business')
                ->where(['user_id'=>self::$id])
                ->column('pid');
            $shiper_principal = Db::name('principal')
                ->where(['user_id'=>self::$id])
                ->column('pid');
            if($shiper_mariner)
               //业务主管
                $this->marinerSign = Db::name('expense')
               ->alias('a')
               ->join('sign_mariner b','a.id=b.pid','LEFT')
               ->where(['shipowner_id'=>['IN',$shiper_mariner],'first_result'=>0,'principal_result'=>0])
               ->count();
            if($shiper_principal || !self::$is_check_rule)
                //财务或超级管理员
                $this->marinerSign = Db::name('expense')
                ->alias('a')
                ->join('sign_mariner b','a.id=b.pid','LEFT')
                ->where(['shipowner_id'=>['IN',$shiper_mariner],'second_result'=>['NEQ',0],'principal_result'=>0])
                ->count();
            if($this->user['duty'] == $this->user['department'].config('leader')){
                $userIds = Db::name('user')->where(['department'=>$this->user['department']])->column('id');
                //船员待签
                $this->marinerSign = Db::name('sign_mariner')->where(['first_id'=>['IN',$userIds],'first_result'=>1,'second_result'=>0])->count();
                //员工待签
                $this->userSign = Db::name('sign_user')->where(['first_result'=>0,'principal_result'=>0])->count();
                $this->officeSign = Db::name('sign_office')->where(['first_result'=>0,'principal_result'=>0])->count();
            }
            //员工签批
            if($this->user['principal']) {
                $this->userSign = Db::name('sign_user')->where(['first_result'=>1,'principal'=>0])->count();
                $this->officeSign = Db::name('sign_office')->where(['first_result'=>1,'principal'=>0])->count();
            }
        }else{
            $this->marinerWarn = Db::name('expense')->where(['mariner_id'=>self::$id,'warn'=>1])->count();
        }
        //每月1号生成所有社保费用数据
//       $create =  Db::name('social_info')->where(['pay_month'=>date('Y-m',time())])->value('id');
//        if(date('d',time()) == 1 && !$create){
//            $info = Db::name('insured')
//                ->field("id,mariner_id,area,starttime")
//                ->where(['is_stop'=>0,'fee_payable'=>"否"])
//                ->select();
//            $info_else = Db::name('insured')
//                ->field("id,mariner_id,area,starttime")
//                ->where(['fee_payable'=>"是","add_month"=>date('Y-m',time())])
//                ->select();
//            $info = array_merge_recursive($info,$info_else);
//            $insert = [];
//            foreach ($info as $k=>$v) {
//                $socialTotal = 0;//社保合计
//                $totalPerson = 0;//个人合计
//                $totalCompany = 0;//公司合计
//                $addPerson = 0;//个人添加项
//                $addCompany = 0;//公司添加项
//                $finalPerson = 0;//实际个人
//                $finalCompany = 0;//实际公司
//                $final = 0;//合计
//                $areaInfo = Db::name('social_security')
//                    ->where(['area' => $v['area'], 'starttime' => ['ELT', date('Y-m', time()), 'endtime' => ['EGT', date('Y-m', time())]]])
//                    ->find();
//               $stop = Db::name('insured_stop')->where(['mariner_id'=>$v['mariner_id'],'area'=>$v['area'],'starttime'=>date('Y-m', time())])->value('id');
//               if($stop) {
//                   Db::name('insured')->where(['id'=>$v['id']])->update(['is_stop'=>1]);
//                   continue;
//               }
//                $setInfo = Db::name('social_security_set')
//                    ->where(['pid' => $areaInfo['id']])
//                    ->select();
//                $data = [];
//                foreach ($setInfo as $key => $value) {
//                    if ($value['title'] == "养老保险") {
//                        $data['yanglao_company'] = $value['base_company'] * $value['rate_company']/100+$value['amount_company'];
//                        $data['yanglao_person'] = $value['base_person'] * $value['rate_person']/100+$value['amount_person'];
//                        $totalPerson += $data['yanglao_person'];
//                        $totalCompany += $data['yanglao_company'];
//                    } elseif ($value['title'] == "生育保险") {
//                        $data['shengyu_company'] = $value['base_company'] * $value['rate_company']/100+$value['amount_company'];
//                        $data['shengyu_person'] = $value['base_person'] * $value['rate_person']/100+$value['amount_person'];
//                        $totalPerson += $data['shengyu_person'];
//                        $totalCompany += $data['shengyu_company'];
//                    } elseif ($value['title'] == "工伤保险") {
//                        $data['gongshang_company'] = $value['base_company'] * $value['rate_company']/100+$value['amount_company'];
//                        $data['gongshang_person'] = $value['base_person'] * $value['rate_person']/100+$value['amount_person'];
//                        $totalCompany += $data['gongshang_company'];
//                    } elseif ($value['title'] == "失业保险") {
//                        $data['shiye_company'] = $value['base_company'] * $value['rate_company']/100+$value['amount_company'];
//                        $data['shiye_person'] = $value['base_person'] * $value['rate_person']/100+$value['amount_person'];
//                        $totalPerson += $data['shiye_person'];
//                        $totalCompany += $data['shiye_company'];
//                    } elseif ($value['title'] == "医疗保险") {
//                        $data['yiliao_company'] = $value['base_company'] * $value['rate_company']/100+$value['amount_company'];
//                        $data['yiliao_person'] = $value['base_person'] * $value['rate_person']/100+$value['amount_person'];
//                        $totalCompany += $data['yiliao_company'];
//                        $totalPerson += $data['yiliao_person'];
//                    } else {
//                        $addPerson += $value['base_person'] * $value['rate_person']/100+$value['amount_company'];
//                        $addCompany += $value['base_company'] * $value['rate_company']/100+$value['amount_person'];
//                    }
//                }
//                $socialTotal += $totalCompany+$totalPerson;
//                $finalPerson += $totalPerson + $addPerson;
//                $finalCompany += $totalCompany + $addCompany;
//                $final += $finalPerson + $finalCompany;
//                $type = "单位承担部分";
//                if($finalPerson == $final) $type = "个人全额自付";
//                if($finalCompany == $final) $type = "公司全额自付";
//                $data['mariner_id'] = $v['mariner_id'];
//                $data['area'] = $v['area'];
//                $data['pay_month'] = date('Y-m',time());
//                $data['first_date'] = $v['starttime'];
//                $data['add_company'] = $addCompany;
//                $data['add_person'] = $addPerson;
//                $data['else_person'] = 0;
//                $data['else_company'] = 0;
//                $data['amount_company'] = $totalCompany;
//                $data['amount_person'] = $totalPerson;
//                $data['assume_person'] = 0;
//                $data['social_total'] = $socialTotal;
//                $data['final_company'] = $finalCompany;
//                $data['final_person'] = $finalPerson;
//                $data['social_total'] = $socialTotal;
//                $data['receipt'] = 0;
//                $data['final'] = $final;
//                $data['debt'] = $finalPerson;
//                $data['remark'] = "";
//                $data['pay_type'] = $type;
//                $insert[] = $data;
//            }
//            Db::name('social_info')->insertAll($insert);
//            $this->create = 0;
//        }
    }

    /**
     * 验证token
     * @return array
     */
    public function checkToken()
    {
        $token = Session::get("token");
        $getToken = $this->request->post("token");
        if ($token != $getToken) {
            $this->msg("请不要重复提交", "数据提交", "error");
        }
    }

    /**
     * 统一的信息打印
     * @param $msg
     * @param $title
     * @param string $status
     */
    public function msg($msg='', $title='', $status = "success")
    {
        if ($status == "success") {
            $this->deleteToken();
        }
        exit(json_encode([
            "status" => $status,
            "msg" => $msg,
            "title" => $title
        ]));
    }

    /**
     * 删除token
     */
    public function deleteToken()
    {
        Session::delete("token");
    }

    /**
     * 生成token
     * @return string
     */
    public function buildToken()
    {
        $md5 = md5(md5(time() . chr(rand(65, 90)) . rand(1, 1000)) . rand(1, 1000));
        Session::set("token", $md5);
        return $md5;
    }

    /**
     * 成功
     * @param $name
     * @param $value
     */
    public function successSessionMsg($value)
    {
        Session::flash("success",$value);
        $this->msg();
    }

    /**
     * 导出Excel文件
     * @param string $fileName
     * @param array $headArr
     * @param array $data
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function excelExport($fileName = '', $headArr = [], $data = []) {
        $fileName .= "_" . date("Y_m_d", Request::instance()->time()) . ".xls";
        //引入PHPExcel类
        Loader::import('PHPExcel.PHPExcel.Reader.Excel5');
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties();

        $key = ord("A"); // 设置表头
        foreach ($headArr as $v) {
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            $key += 1;
        }
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();
        foreach ($data as $key => $rows) { // 行写入
            $span = ord("A");
            foreach ($rows as $keyName => $value) { // 列写入
                $objActSheet->setCellValue(chr($span) . $column, $value);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit(chr($span) . $column,$value,\PHPExcel_Cell_DataType::TYPE_STRING);
                $span++;
            }
            $column++;
        }
        $fileName = iconv("utf-8", "gb2312", $fileName); // 重命名表
        $objPHPExcel->setActiveSheetIndex(0); // 设置活动单指数到第一个表,所以Excel打开这是第一个表
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename='$fileName'");
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); // 文件通过浏览器下载
        exit();
    }

    /**
     * 获取姓名简称
     * @param $name
     * @return string
     */
    public function staffList($name = "")
    {
        return abbreviation($name);
    }

    /**
     * 模糊搜索下拉
     * @param $table
     * @param $field
     * @param $condition
     * @return \think\response\Json
     */
    public function search($table,$field,$condition)
    {
       $info =  Db::name($table)->where(["$field"=>['LIKE',"%$condition%"]])->column($field);
        $result = array_slice(array_unique($info),0,self::$listRows,false);
        $res = [
           'data'=>$result
       ];
       return json($res);
    }

    /**
     * 删除操作
     * @param $table
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function delete($table)
    {
        $id = input('id/a');
        if($id == 1) return error_data('超级管理员不允许删除');
        $res = Db::name($table)->where(['id'=>['IN',$id]])->delete();
        if($res) return ok_data("删除成功");
        return error_data();
    }

    /**
     * 上传类
     * @param $path
     * @param array $ext
     * @return array
     */
    protected function upload($path, array $ext)
    {
        if ($_FILES) {
            $key = array_keys($_FILES);
            $file = request()->file($key[0]);
            $arr = explode('.', $_FILES[$key[0]]['name']);
            if (!in_array(strtolower(end($arr)), $ext)) return ['error' => 1, 'msg' => '文件类型不符'];
            if (!$file->checkSize(8388608)) return ['error' => 1, 'msg' => '文件过大,上限8MB'];
            $info = $file->rule('date')->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . $path);
            if ($info) {
                return ['status' => '200', 'msg' => SITE . $path . '/' . $info->getSaveName()];
            } else {
                return ['status' => '001', 'msg' => $info->getError()];
            }
        } else {
            return ['status' => '002', 'msg' => '没有选择文件'];
        }
    }

}