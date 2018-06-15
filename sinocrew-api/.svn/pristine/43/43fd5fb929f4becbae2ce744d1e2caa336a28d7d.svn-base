<?php
/**
 * @desc Created by PhpStorm.
 * @author: CodeYi
 * @since: 2018-04-25 15:10
 */
namespace app\api\controller;
use app\api\common\Base;
use think\Db;
use think\Loader;

/**
 * 社保控制器
 * Class SocialSecurity
 * @package app\api\controller
 */
class Social extends Base
{
    /**
     * 添加社保设置
     * @return \think\response\Json
     */
    public function addArea()
    {
        $data = input('post.');
        //时间的合理性
        if(strcmp($data['starttime'],$data['endtime'])>0) return error_data('填写时间有误');
        $insert = [
            'area'=>$data['area'],
            'starttime'=>formatTime($data['starttime'],'Y-m'),
            'endtime'=>formatTime($data['endtime'],'Y-m'),
            'formula_mode'=>$data['mode'],
            'remark'=>$data['remark'],
        ];
        Db::startTrans();
        try{
            $pid = Db::name('social_security')->insertGetId($insert);
            $project = $data['project'];
            $arr = ['养老保险','医疗保险','失业保险','工伤保险','生育保险'];
            foreach ($project as $k=>&$v){
                $v['pid'] = $pid;
                $v['total_person'] = $v['base_person']*$v['rate_person']/100+$v['amount_person'];
                $v['total_company'] = $v['base_company']*$v['rate_person']/100+$v['amount_company'];
                if(in_array($v['title'],$arr)){
                    $v['is_five'] = 1;
                }else{
                    $v['is_five'] = 0;
                }
            }
            Db::name('social_security_set')->insertAll($project);
            Db::commit();
            return ok_data();
        }catch (\Exception $e){
            Db::rollback();
            return error_data();
        }
    }

    /**
     * 社保设置详情
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function detailArea()
    {
        $id = input('id');
        $area = Db::name('social_security')->find($id);
        $info = Db::name('social_security_set')->where(['pid'=>$id])->select();
        $person = 0;
        $company = 0;
        $personElse = 0;
        $companyElse = 0;
        foreach ($info as $k=>$v){
            if($v['is_five'] == 1){
                $person += $v['total_person'];
                $company += $v['total_company'];
            }else{
                $personElse += $v['total_person'];
                $companyElse += $v['total_company'];
            }
        }
        $res = [
            'area'=>$area,
            'data'=>$info,
            'person'=>$person,
            'company'=>$company,
            'personElse'=>$personElse,
            'companyElse'=>$companyElse
        ];
        return json($res);
    }

    /**
     * 修改社保设置
     * @return \think\response\Json
     */
    public function editArea()
    {
        $data = input('post.');
        if($data['endtime'] < $data['starttime']) return error_data('填写时间有误');
        $update = [
            'id'=>$data['id'],
            'area'=>$data['area'],
            'starttime'=>formatTime($data['starttime'],'Y-m'),
            'endtime'=>formatTime($data['endtime'],'Y-m'),
            'formula_mode'=>$data['mode'],
            'remark'=>$data['remark'],
        ];
        Db::startTrans();
        try{
            Db::name('social_security')->update($update);
            Db::name('social_security_set')->where(['pid'=>$data['id']])->delete();
            $project = $data['project'];
            $arr = ['养老保险','医疗保险','失业保险','工伤保险','生育保险'];
            foreach ($project as $k=>&$v){
                $v['pid'] = $data['id'];
                $v['total_person'] = $v['base_person']*$v['rate_person']/100+$v['amount_person'];
                $v['total_company'] = $v['base_company']*$v['rate_person']/100+$v['amount_person'];
                if(in_array($v['title'],$arr)){
                    $v['is_five'] = 1;
                }else{
                    $v['is_five'] = 0;
                }
            }
            Db::name('social_security_set')->insertAll($project);
            Db::commit();
            return ok_data();
        }catch (\Exception $e){
            Db::rollback();
            return error_data();
        }
    }

    /**
     * 社保设置列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function listArea()
    {
        $area = input('area/a');
        $page = input('page');
        $listRows = input('listRows')?input('listRows'):self::$listRows;
        $starttime = formatTime(input('time/a')[0],'Y-m');
        $endtime = formatTime(input('time/a')[1],'Y-m');
        $where = [];
        if(empty($starttime) && $endtime) $where['endtime'] = ['ELT',$endtime];
        if(empty($endtime) && $starttime) $where['starttime'] = ['EGT',$starttime];
        if($starttime && $endtime){
            $where['starttime'] = ['EGT',$starttime];
            $where['endtime'] = ['ELT',$endtime];
        }
        if($area) $where['area'] = ['IN',$area];
        $list = Db::name('social_security')->where($where)->order('starttime')->count();
        $info = Db::name('social_security')->where($where)->order('starttime')->page($page,$listRows)->select();
        $totalCompany = 0;
        $totalPerson = 0;
        $fiveCompany = 0;
        $fivePerson = 0;
        $addCompany = 0;
        $addPerson = 0;
        $yanglaoCompany = 0;
        $yanglaoPerson = 0;
        $yiliaoCompany = 0;
        $yiliaoPerson = 0;
        $shiyeCompany = 0;
        $shiyePerson = 0;
        $gongshangCompany = 0;
        $gongshangPerson = 0;
        $shengyuCompany = 0;
        $shengyuPerson = 0;
        foreach ($info as $k=>$v){
            $person = 0;
            $company = 0;
            $personElse = 0;
            $companyElse = 0;
            $allInfo = Db::name('social_security_set')->where(['pid'=>$v['id']])->select();
            foreach ($allInfo as $k1=>$v1){
                if($v1['title'] == "医疗保险"){
                    $yiliaoCompany += $v1['total_company'];
                    $yiliaoPerson += $v1['total_person'];
                }
                if($v1['title'] == "养老保险"){
                    $yanglaoCompany += $v1['total_company'];
                    $yanglaoPerson += $v1['total_person'];
                }
                if($v1['title'] == "工伤保险"){
                    $gongshangCompany += $v1['total_company'];
                    $gongshangPerson += $v1['total_person'];
                }
                if($v1['title'] == "生育保险"){
                    $shengyuCompany += $v1['total_company'];
                    $shengyuPerson += $v1['total_person'];
                }
                if($v1['title'] == "失业保险"){
                    $shiyeCompany += $v1['total_company'];
                    $shiyePerson += $v1['total_person'];
                }
                if($v1['is_five'] == 1){
                    $person += $v1['total_person'];
                    $company += $v1['total_company'];
                }else{
                    $personElse += $v1['total_person'];
                    $companyElse += $v1['total_company'];
                }
            }
            $info[$k]['project'] = $allInfo;
            $info[$k]['person'] = $person;
            $info[$k]['company'] = $company;
           $info[$k]['personElse'] = $personElse;
           $info[$k]['companyElse'] = $companyElse;
           $info[$k]['totalPerson'] = $person +$personElse;
           $info[$k]['totalCompany'] = $company +$companyElse;
            $totalCompany += $company +$companyElse;
            $totalPerson += $person +$personElse;
            $fiveCompany += $company;
            $fivePerson += $person;
            $addCompany += $companyElse;
            $addPerson += $personElse;
        }
        $res = [
            'list'=>$list,
            'data'=>$info,
            'totalCompany' => $totalCompany,
            'totalPerson' => $totalPerson,
            'fiveCompany' => $fiveCompany,
            'fivePerson' => $fivePerson,
            'addCompany' => $addCompany,
            'addPerson' => $addPerson,
            'yanglaoCompany' => $yanglaoCompany,
            'yanglaoPerson' => $yanglaoPerson,
            'yiliaoCompany' => $yiliaoCompany,
            'yiliaoPerson' => $yiliaoPerson,
            'shiyeCompany' => $shiyeCompany,
            'shiyePerson' => $shiyePerson,
            'gongshangCompany' => $gongshangCompany,
            'gongshangPerson' => $gongshangPerson,
            'shengyuCompany' => $shengyuCompany,
            'shengyuPerson' => $shengyuPerson
        ];
        return json($res);
    }

    /**
     * 社保所有地区
     * @return \think\response\Json
     */
    public function allArea()
    {
       $allArea = Db::name('social_security')->column('area');
       $res = [
           'area'=>array_unique($allArea)
       ];
       return json($res);
    }

    /**
     * 导出社保设置数据
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function exportArea()
    {
        $area = input('area/a');
        $starttime = formatTime(input('time/a')[0],'Y-m');
        $endtime = formatTime(input('time/a')[1],'Y-m');
        $where = [];
        if(empty($starttime) && $endtime) $where['endtime'] = ['ELT',$endtime];
        if(empty($endtime) && $starttime) $where['starttime'] = ['EGT',$starttime];
        if($starttime && $endtime){
            $where['starttime'] = ['EGT',$starttime];
            $where['endtime'] = ['ELT',$endtime];
        }
        if($area) $where['area'] = ['IN',$area];
        $info = Db::name('social_security')->where($where)->order('starttime')->select();
        $person = 0;
        $company = 0;
        $personElse = 0;
        $companyElse = 0;
        foreach ($info as $k=>$v){
            $allInfo = Db::name('social_security_set')->where(['pid'=>$v['id']])->select();
            $info[$k]['project'] = $allInfo;
            foreach ($allInfo as $k1=>$v1){
                if($v1['is_five'] == 1){
                    $person += $v1['total_person'];
                    $company += $v1['total_company'];
                }else{
                    $personElse += $v1['total_person'];
                    $companyElse += $v1['total_company'];
                }
            }
            $info[$k]['person'] = $person;
            $info[$k]['company'] = $company;
            $info[$k]['personElse'] = $personElse;
            $info[$k]['companyElse'] = $companyElse;
            $info[$k]['totalPerson'] = $person +$personElse;
            $info[$k]['totalCompany'] = $company +$companyElse;
        }
        return json($info);
    }

    /**
     * 导入社保设置
     * @return \think\response\Json
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function importArea()
    {
        if (!$_FILES) return error_data('请选择文件');
        $key = array_keys($_FILES);
        $file = $_FILES[$key[0]]["tmp_name"];
        Loader::import('PHPExcel.PHPExcel.Reader.Excel5');
        $PHPReader = new \PHPExcel_Reader_Excel5();
        if (!$PHPReader->canRead($file)) {
            $PHPReader = new \PHPExcel_Reader_Excel2007();
            if (!$PHPReader->canRead($file)) {
                return error_data('文件类型不符');
            }
        }
        $E = $PHPReader->load($file);
        $cur = $E->getSheet(0);  // 读取第一个表
        $end = $cur->getHighestColumn(); // 获得最大的列数
        $line = $cur->getHighestRow(); // 获得最大总行数
        //将最大列转换为数字
        $length = strlen($end);
        if (strlen($end) > 1) {
            $single = ord(substr($end, $length - 1)) - 64;
            $end = ($length - 1) * 26 + $single;
        } else {
            $end = ord($end) - 64;
        }
        $info = [];
        $i = 0;
        for ($row = 2; $row <= $line; $row++) {
            for ($column = 1; $column < $end; $column++) {
                $val = $cur->getCellByColumnAndRow($column, $row)->getValue();
                if(is_object($val)){
                    $info[$i][] = $val->__toString();
                }else{
                    $info[$i][] = $val;
                }
            }
            $i++;
        }
        $success = 0;
        $fail = 0;
        $info_ = [];
        foreach ($info as $k=>&$v){
            $id = Db::name('social_security')->where(['area'=>$v[0],'starttime'=>['BETWEEN',[$v[1],$v[2]],'endtime'=>['BETWEEN',[$v[1],$v[2]]]]])->value('id');
            if(!$id){
                $info_[] = $v;
                $success++;
            }else{
                $fail++;
            }
        }
        if(empty($info_)) return error_data('请勿重复导入');
        $insertSet = [];
        Db::startTrans();
        try{
            foreach ($info_ as $k=>$v){
                $area['area'] = (string)$v[0];
                $area['starttime'] = (string)$v[1];
                $area['endtime'] = (string)$v[2];
                if($v[33] == "四舍五入") $area['formula_mode'] = 1;
                if($v[33] == "见分进角") $area['formula_mode'] = 2;
                $area['remark'] = (string)$v[34];
                $pid = Db::name('social_security')->insertGetId($area);
                $yanglao['pid'] = $pid;
                $yanglao['title'] = "养老保险";
                $yanglao['base_company'] = $v[3];
                $yanglao['base_person'] = $v[4];
                $yanglao['rate_company'] = $v[5]*100;
                $yanglao['rate_person'] = $v[6]*100;
                $yanglao['amount_company'] = $v[7];
                $yanglao['amount_person'] = $v[8];
                if($area['formula_mode'] == 1){
                    $yanglao['total_person'] = round($v[4]*$v[6]+$v[8],2);
                    $yanglao['total_company'] = round($v[3]*$v[5]+$v[7],2);
                }elseif($area['formula_mode'] == 2){
                    $yanglao['total_person'] = sprintf("%.2f",ceil(($v[4]*$v[6]+$v[8])*10)/10);
                    $yanglao['total_company'] = sprintf("%.2f",ceil(($v[3]*$v[5]+$v[7])*10)/10);
                }
                $yanglao['is_five'] =  1;
                $yiliao['pid'] = $pid;
                $yiliao['title'] = "医疗保险";
                $yiliao['base_company'] = $v[9];
                $yiliao['base_person'] = $v[10];
                $yiliao['rate_company'] = $v[11]*100;
                $yiliao['rate_person'] = $v[12]*100;
                $yiliao['amount_company'] = $v[13];
                $yiliao['amount_person'] = $v[14];
                if($area['formula_mode'] == 1){
                    $yiliao['total_person'] = round($v[10]*$v[12]+$v[14],2);
                    $yiliao['total_company'] = round($v[9]*$v[11]+$v[13],2);
                }elseif($area['formula_mode'] == 2){
                    $yiliao['total_person'] = sprintf("%.2f",ceil(($v[10]*$v[12]+$v[14])*10)/10);
                    $yiliao['total_company'] = sprintf("%.2f",ceil(($v[9]*$v[11]+$v[13])*10)/10);
                }
                $yiliao['is_five'] =  1;
                $shiye['pid'] = $pid;
                $shiye['title'] = "失业保险";
                $shiye['base_company'] = $v[15];
                $shiye['base_person'] = $v[16];
                $shiye['rate_company'] = $v[17]*100;
                $shiye['rate_person'] = $v[18]*100;
                $shiye['amount_company'] = $v[19];
                $shiye['amount_person'] = $v[20];
                if($area['formula_mode'] == 1){
                    $shiye['total_person'] = round($v[16]*$v[18]+$v[20],2);
                    $shiye['total_company'] = round($v[15]*$v[17]+$v[19],2);
                }elseif($area['formula_mode'] == 2){
                    $shiye['total_person'] = sprintf("%.2f",ceil(($v[16]*$v[18]+$v[20])*10)/10);
                    $shiye['total_company'] = sprintf("%.2f",ceil(($v[15]*$v[17]+$v[19])*10)/10);
                }
                $shiye['is_five'] =  1;
                $gongshang['pid'] = $pid;
                $gongshang['title'] = "工伤保险";
                $gongshang['base_company'] = $v[21];
                $gongshang['base_person'] = $v[22];
                $gongshang['rate_company'] = $v[23]*100;
                $gongshang['rate_person'] = $v[24]*100;
                $gongshang['amount_company'] = $v[25];
                $gongshang['amount_person'] = $v[26];
                if($area['formula_mode'] == 1){
                    $gongshang['total_person'] = round($v[22]*$v[24]+$v[26],2);
                    $gongshang['total_company'] = round($v[21]*$v[23]+$v[25],2);
                }elseif($area['formula_mode'] == 2){
                    $gongshang['total_person'] = sprintf("%.2f",ceil(($v[22]*$v[24]+$v[26])*10)/10);
                    $gongshang['total_company'] = sprintf("%.2f",ceil(($v[21]*$v[23]+$v[25])*10)/10);
                }
                $gongshang['is_five'] =  1;
                $shengyu['pid'] = $pid;
                $shengyu['title'] = "生育保险";
                $shengyu['base_company'] = $v[27];
                $shengyu['base_person'] = $v[28];
                $shengyu['rate_company'] = $v[29]*100;
                $shengyu['rate_person'] = $v[30]*100;
                $shengyu['amount_company'] = $v[31];
                $shengyu['amount_person'] = $v[32];
                if($area['formula_mode'] == 1){
                    $shengyu['total_person'] = round($v[22]*$v[24]+$v[26],2);
                    $shengyu['total_company'] = round($v[21]*$v[23]+$v[25],2);
                }elseif($area['formula_mode'] == 2){
                    $shengyu['total_person'] = sprintf("%.2f",ceil(($v[22]*$v[24]+$v[26])*10)/10);
                    $shengyu['total_company'] = sprintf("%.2f",ceil(($v[21]*$v[23]+$v[25])*10)/10);
                }
                $shengyu['is_five'] =  1;
                $insertSet[] = [
                    0=>$shengyu,
                    1=>$shiye,
                    2=>$yanglao,
                    3=>$gongshang,
                    4=>$yiliao,
                ];
            }
            foreach ($insertSet as $k=>$v){
                Db::name('social_security_set')->insertAll($v);
            }
            Db::commit();
            return ok_data("成功导入".$success."条数据,失败".$fail."条");
        }catch (\Exception $e){
            Db::rollback();
            return error_data();
        }
    }

    /**
     * 参保地区搜索
     */
    public function searchArea()
    {
        $condition = input('condition');
        $table = "social_security";
        $field = "area";
       return $this->search($table,$field,$condition);
    }

    /**
     * 添加社保人员
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function addInsured()
    {
        $data = input('post.');
        $info = Db::name('mariner')->where(['id_number'=>$data['idNumber']])->find();
        if(!$info) return error_data('没有找到该身份证对应的船员');
        if($info['name'] != $data['name']) return error_data('身份证和船员姓名不匹配');
        //是否重复提交
        $repeat = Db::name('insured')->where(['mariner_id'=>$info['id'],'starttime'=>$data['time']])->value('id');
        if($repeat) return error_data('请勿重复提交');
        $insert = [
            'mariner_id'=>$info['id'],
            'area'=>$data['area'],
            'starttime'=>(string)(formatTime($data['time'],'Y-m')),
            'fee_payable'=>$data['payable'],
            'add_month'=>date('Y-m',time())
        ];
       $res = Db::name('insured')->insert($insert);
       if($res) return ok_data();
       return error_data();
    }

    /**
     * 批量导入社保人员
     * @return \think\response\Json
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function importInsured()
    {
        if (!$_FILES) return error_data('请选择文件');
        $key = array_keys($_FILES);
        $file = $_FILES[$key[0]]["tmp_name"];
        Loader::import('PHPExcel.PHPExcel.Reader.Excel5');
        $PHPReader = new \PHPExcel_Reader_Excel5();
        if (!$PHPReader->canRead($file)) {
            $PHPReader = new \PHPExcel_Reader_Excel2007();
            if (!$PHPReader->canRead($file)) {
                return error_data('文件类型不符');
            }
        }
        $E = $PHPReader->load($file);
        $cur = $E->getSheet(0);  // 读取第一个表
        $end = $cur->getHighestColumn(); // 获得最大的列数
        $line = $cur->getHighestRow(); // 获得最大总行数
        //将最大列转换为数字
        $length = strlen($end);
        if (strlen($end) > 1) {
            $single = ord(substr($end, $length - 1)) - 64;
            $end = ($length - 1) * 26 + $single;
        } else {
            $end = ord($end) - 64;
        }
        $info = [];
        $i = 0;
        for ($row = 2; $row <= $line; $row++) {
            for ($column = 1; $column < $end; $column++) {
                $val = $cur->getCellByColumnAndRow($column, $row)->getValue();
                if(is_object($val)){
                    $info[$i][] = $val->__toString();
                }else{
                    $info[$i][] = $val;
                }
            }
            $i++;
        }
        $insert = [];
        $fail = 0;
        $success = 0;
        foreach ($info as $k=>&$v){
            $pid = Db::name('mariner')->where(['id_number'=>$v[1],'name'=>$v[0]])->value('id');
            if(!$pid){
                $fail++;
                continue;
            }
           $repeatId = Db::name('insured')->where(['mariner_id'=>$pid,'area'=>$v[2],'starttime'=>$v[3]])->value('id');
            if($repeatId){
                $fail++;
                continue;
            }
            $data['mariner_id'] = $pid;
            $data['area'] = (string)$v[2];
            $data['starttime'] = (string)$v[3];
            $data['fee_payable'] = (string)$v[4];
            $data['add_month'] = date('Y-m',time());
            $success++;
            $insert[] = $data;
        }
        if(empty($insert)) return error_data('重复导入或导入信息无效');
        $res = Db::name('insured')->insertAll($insert);
        if($res) return ok_data("成功导入".$success."条数据,"."失败".$fail."条");
        return error_data();
    }

    /**
     * 添加减员
     * @return \think\response\Json
     * @throws \Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function reduceInsured()
    {
        $data = input('post.');
        $info = Db::name('mariner')->where(['id_number'=>$data['idNumber']])->find();
        if(!$info) return error_data('没有找到该身份证对应的船员');
        if($info['name'] != $data['name']) return error_data('身份证和船员姓名不匹配');
        $stopInfo = Db::name('insured')->field('is_stop,id')
            ->where(['mariner_id'=>$info['id'],'area'=>$data['area']])
            ->order('starttime desc')
            ->find();
        if($stopInfo['is_stop']) return error_data('该船员已经处于停保状态');
        if(!$data['time']) return error_data('请填写停保时间');
        if(!$stopInfo['id']) return error_data('该船员没有在'.$data['area'].'缴过社保');
        Db::startTrans();
        try{
            $insert = [
                'mariner_id'=>$info['id'],
                'area'=>$data['area'],
                'starttime'=>formatTime($data['time'],"Y-m"),
            ];
            $repeat = Db::name('insured_stop')->where($insert)->value('id');
            if($repeat) return error_data('该船员已停缴过,请勿重复操作');
            Db::name('insured_stop')->insert($insert);
            $defaultDate = date('Y-m',strtotime("+1 month"));
            //减员是下个月才改变is_stop状态，否则在构造函数再进行判断
            if( $defaultDate == $insert['starttime']){
                $pid = Db::name('insured')->where(['area'=>$data['arae'],'mariner_id'=>$info['id']])->order('starttime')->limit(1)->value('id');
                Db::name('insured')->where(['id'=>$pid])->update(['is_stop'=>1]);
            }
            Db::commit();
            return ok_data();
        }catch (\Exception $e){
            Db::rollback();
            throw $e;
        }

    }

    /**
     * 批量导入减员
     * @return \think\response\Json
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function importReduce()
    {
        if (!$_FILES) return error_data('请选择文件');
        $key = array_keys($_FILES);
        $file = $_FILES[$key[0]]["tmp_name"];
        Loader::import('PHPExcel.PHPExcel.Reader.Excel5');
        $PHPReader = new \PHPExcel_Reader_Excel5();
        if (!$PHPReader->canRead($file)) {
            $PHPReader = new \PHPExcel_Reader_Excel2007();
            if (!$PHPReader->canRead($file)) {
                return error_data('文件类型不符');
            }
        }
        $E = $PHPReader->load($file);
        $cur = $E->getSheet(0);  // 读取第一个表
        $end = $cur->getHighestColumn(); // 获得最大的列数
        $line = $cur->getHighestRow(); // 获得最大总行数
        //将最大列转换为数字
        $length = strlen($end);
        if (strlen($end) > 1) {
            $single = ord(substr($end, $length - 1)) - 64;
            $end = ($length - 1) * 26 + $single;
        } else {
            $end = ord($end) - 64;
        }
        $info = [];
        $i = 0;
        for ($row = 2; $row <= $line; $row++) {
            for ($column = 1; $column < $end; $column++) {
                $val = $cur->getCellByColumnAndRow($column, $row)->getValue();
                if(is_object($val)){
                    $info[$i][] = $val->__toString();
                }else{
                    $info[$i][] = $val;
                }
            }
            $i++;
        }
        $insert = [];
        $fail = 0;
        $success = 0;
        foreach ($info as $k=>$v){
            $pid = Db::name('mariner')->where(['id_number'=>$v[1],'name'=>$v[0]])->value('id');
            if(!$pid){
                $fail++;
                continue;
            }
            $data['mariner_id'] = $pid;
            $data['area'] = (string)$v[2];
            $data['starttime'] = (string)$v[3];
            $success++;
            $insert[] = $data;
        }
        $res = Db::name('insured_stop')->insertAll($insert);
        if($res) return ok_data("成功导入".$success."条数据,"."失败".$fail."条");
        return error_data();
    }

    /**
     * 社保人员列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function listInsured()
    {
        $data = input('post.');
        $starttime = formatTime($data['time'][0],'Y-m');
        $endtime = formatTime($data['time'][1],'Y-m');
        if($data['id']) $where['mariner_id'] = $data['id'];
        if($data['idNumber']){
           $marinerId = Db::name('mariner')->where(['id_number'=>['LIKE',"%".$data['idNumber']."%"]])->value('id');
           $where['a.mariner_id'] = $marinerId;
        }
        if($data['cid']){
            $marinerId = Db::name('mariner')->where(['cid'=>['LIKE',"%".$data['cid']."%"]])->value('id');
            $where['a.mariner_id'] = $marinerId;
        }
        if($data['area']) $where['area'] = ['IN',$data['area']];
        if($data['status'] == "缴纳") $where['is_stop'] = 0;
        if($data['status'] == "停缴") $where['is_stop'] = 1;
        $ids = Db::name('insured_stop')->column('mariner_id');
        $ids = array_unique($ids);
        if($data['isStop'] == "是") $where['mariner_id'] = ['IN',$ids];
        if($data['isStop'] == "否") $where['mariner_id'] = ['NOT IN',$ids];
        //欠款截止时间
        $time = $endtime?$endtime:date('Y-m',time());
        $where1['pay_month'] = ['ELT',$time];
        if($starttime) $where1['pay_month'] = ['BETWEEN TIME',$starttime,$endtime];
        //费用承担方式
        $list = Db::name('insured')
            ->alias('a')
            ->field('b.id,b.cid,b.name,b.id_number,min(starttime) firsttime,max(starttime) starttime')
            ->join('mariner b','a.mariner_id=b.id','LEFT')
            ->where($where)
            ->group('mariner_id')
            ->count();
        $info = Db::name('insured')
            ->alias('a')
            ->field('b.id,b.cid,b.name,b.id_number,min(starttime) firsttime,max(starttime) starttime')
            ->join('mariner b','a.mariner_id=b.id','LEFT')
            ->where($where)
            ->group('mariner_id')
            ->page($data['page'],self::$listRows)
            ->select();
        foreach ($info as $k=>$v){
            $where1['mariner_id'] = $v['id'];
           $info[$k]['lastInsuredArea'] =  Db::name('insured')->where(['mariner_id'=>$v['id']])->order('starttime desc')->limit(1)->value('area');
           $stopInfo = Db::name('insured_stop')->field('area,starttime')->where(['mariner_id'=>$v['id']])->order('starttime desc')->find();
           $info[$k]['lastStopArea'] = $stopInfo['area'];
           $info[$k]['stoptime'] = $stopInfo['starttime'];
           $status = Db::name('insured')->where(['mariner_id'=>$v['id'],'starttime'=>['ELT',date('Y-m',time())],'is_stop'=>1])->order('starttime desc')->find();
           if($status) {
               $info[$k]['status'] = "停缴";
           }else{
               $info[$k]['status'] = "参保";
           }
            //欠款截止日期
            $info[$k]['arrearageDate'] = $time;
            $socialInfo = Db::name('social_info')->field('sum(debt) debt,sum(receipt) receipt,sum(amount_company) amount_company,sum(amount_person) amount_person,sum(assume_person) assume_person')->where($where1)->group('mariner_id')->find();
            $info[$k]['debt'] = $socialInfo['debt'];
            $info[$k]['receipt'] = $socialInfo['receipt'];
            $info[$k]['amount_company'] = $socialInfo['amount_company'];
            $info[$k]['amount_person'] = $socialInfo['amount_person'];
            $info[$k]['assume_person'] = $socialInfo['assume_person'];
        }
        $res = [
            'list'=>$list,
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 导出社保人员
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function exportList()
    {
        $data = input('post.');
        $starttime = formatTime($data['time'][0],'Y-m');
        $endtime = formatTime($data['time'][1],'Y-m');
        if($data['id']) $where['mariner_id'] = $data['id'];
        if($data['idNumber']){
            $marinerId = Db::name('mariner')->where(['id_number'=>$data['idNumber']])->value('id');
            $where['a.mariner_id'] = $marinerId;
        }
        if($data['cid']){
            $marinerId = Db::name('mariner')->where(['cid'=>$data['cid']])->value('id');
            $where['a.mariner_id'] = $marinerId;
        }
        if($data['area']) $where['area'] = ['IN',$data['area']];
        if($data['status'] == "参保") $where['is_stop'] = 0;
        if($data['status'] == "停保") $where['is_stop'] = 1;
        $ids = Db::name('insured_stop')->column('mariner_id');
        $ids = array_unique($ids);
        if($data['isStop'] == "是") $where['mariner_id'] = ['IN',$ids];
        if($data['isStop'] == "否") $where['mariner_id'] = ['NOT IN',$ids];
        //欠款截止时间
        $time = $endtime?$endtime:date('Y-m',time());
        $where1['pay_month'] = ['ELT',$time];
        if($starttime) $where1['pay_month'] = ['BETWEEN TIME',$starttime,$endtime];
        $info = Db::name('insured')
            ->alias('a')
            ->field('b.id,b.cid,b.name,b.id_number,min(starttime) firsttime,max(starttime) starttime')
            ->join('mariner b','a.mariner_id=b.id','LEFT')
            ->where($where)
            ->group('mariner_id')
            ->select();
        foreach ($info as $k=>$v){
            $where1['mariner_id'] = $v['id'];
            $info[$k]['lastInsuredArea'] =  Db::name('insured')->where(['mariner_id'=>$v['id']])->order('starttime desc')->limit(1)->value('area');
            $info[$k]['lastStopArea'] = Db::name('insured_stop')->where(['mariner_id'=>$v['id']])->order('starttime desc')->limit(1)->value('area');
            if($info[$k]['lastStopArea'] && $info[$k]['lastStopArea']>$info[$k]['starttime'])
            {
                $info[$k]['status'] = "停缴";
            }else{
                $info[$k]['status'] = "参保";
            }
            //欠款截止日期
            $info[$k]['arrearageDate'] = $time;
            $socialInfo = Db::name('social_info')->field('sum(debt) debt,sum(receipt) receipt,sum(amount_company) amount_company,sum(amount_person) amount_person,sum(assume_person) assume_person')->where($where1)->group('mariner_id')->find();
            $info[$k]['debt'] = $socialInfo['debt'];
            $info[$k]['receipt'] = $socialInfo['receipt'];
            $info[$k]['amount_company'] = $socialInfo['amount_company'];
            $info[$k]['amount_person'] = $socialInfo['amount_person'];
            $info[$k]['assume_person'] = $socialInfo['assume_person'];
        }
        return json($info);
    }

    /**
     * 社保时间段及地区
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function areaInsured()
    {
        $marinerId = input('marinerId');
        $info = Db::name('insured')->field('mariner_id,area,starttime')->where(['mariner_id'=>$marinerId])->order('starttime')->select();
        $time = Db::name('insured')->where(['mariner_id'=>$marinerId,"fee_payable"=>"否"])->order('starttime')->column('starttime');
        foreach ($info as $k=>$v){
            $starttime = Db::name('insured_stop')->where(['mariner_id'=>$marinerId,'area'=>$v['area'],'starttime'=>['BETWEEN',[$time[$k],$time[$k+1]]]])->value('starttime');
            if($starttime) {
                $info[$k]['endtime'] = $starttime;
            }else{
                $info[$k]['endtime'] = "";
            }
            if($v['starttime'] == end($time)){
                $maxtime = Db::name('insured_stop')->where(['mariner_id'=>$marinerId,'area'=>$v['area']])->order('starttime')->limit(1)->value('starttime');
                if($maxtime > $v['starttime']) {
                    $info[$k]['endtime'] = $maxtime;
                }else{
                    $info[$k]['endtime'] = "";
                }
            }
        }
        return json($info);
    }

    /**
     * 社保人员设置备注
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function addRemark()
    {
        $marinerId = input('marinerId');
        $remark = input('remark');
        $id = Db::name('social_security_remark')->where(['mariner_id'=>$marinerId])->value('mariner_id');
        if($id){
            $res = Db::name('social_security_remark')->where(['mariner_id'=>$id])->update(['remark'=>$remark]);
        }else{
            $res = Db::name('social_security_remark')->insert(['mariner_id'=>$marinerId,'remark'=>$remark]);
        }
        if($res) return ok_data();
        return error_data();
    }

    /**
     * 社保人员信息
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function infoInsured()
    {
        //查询条件
        $data = input('post.');
        $listRows = $data['listRows']?$data['listRows']:self::$listRows;
        $page = $data['page'];
        $cid = $data['cid'];
        $id = $data['id'];
        $idNumber = $data['idNumber'];
        $vessel = $data['vessel'];
        $ownerPool = $data['ownerPool'];
        $area = $data['area'];
        $starttime = $data['time'][0];
        $endtime = $data['time'][1];
        $type = $data['type'];
        $where = [];
        if($cid) $where['mariner_id'] = Db::name('mariner')->where(['cid'=>['LIKE',"%$cid%"]])->value('id');
        if($id) $where['mariner_id'] = $id;
        if($idNumber) $where['mariner_id'] = Db::name('mariner')->where(['id_number'=>$idNumber])->value('id');
        if($vessel){
            $marinerIds = Db::name('mariner')->where(['vessel'=>$vessel])->column('id');
            $where['mariner_id'] = ['IN',$marinerIds];
        }
        if($ownerPool){
            $marinerId = Db::name('mariner')->where(['owner_pool'=>$ownerPool])->column('id');
            $where['mariner_id'] = ['IN',$marinerId];
        }
        if($area) $where['area'] = ['IN',$area];
        if($starttime && empty($endtime)) $where['pay_month'] = ['EGT',$starttime];
        if($starttime && $endtime) $where['pay_month'] = ['BETWEEN TIME',[$starttime,$endtime]];
        if($type) $where['pay_type'] = $type;
        //缺少是否补缴
        $yanglao_company = 0;
        $yanglao_person = 0;
        $yiliao_company = 0;
        $yiliao_person = 0;
        $shiye_company = 0;
        $shiye_person = 0;
        $gongshang_company = 0;
        $gongshang_person = 0;
        $shengyu_company = 0;
        $shengyu_person = 0;
        $add_company = 0;
        $add_person = 0;
        $else_company = 0;
        $else_person = 0;
        $amount_company = 0;
        $amount_person = 0;
        $assume_person = 0;
        $social_total = 0;
        $final_company = 0;
        $final_person = 0;
        $final = 0;
        $debt = 0;
        $receipt = 0;
        $list = Db::name('social_info')
            ->where($where)
            ->count();
        $info = Db::name('social_info')
            ->alias('a')
            ->field("a.*,b.cid,b.name,b.id_number,b.duty,c.title vessel,d.title shipOwner")
            ->join('mariner b', 'a.mariner_id=b.id', 'LEFT')
            ->join('vessel c', 'b.vessel=c.id', 'LEFT')
            ->join('shipowner d', 'd.id=b.owner_pool', 'LEFT')
            ->where($where)
            ->order('pay_month desc')
            ->page($page,$listRows)
            ->select();
        foreach ($info as $k=>$v){
            $yanglao_company += $v['yanglao_company'];
            $yanglao_person += $v['yanglao_person'];
            $yiliao_company += $v['yiliao_company'];
            $yiliao_person += $v['yiliao_person'];
            $shiye_company += $v['shiye_company'];
            $shiye_person += $v['shiye_person'];
            $gongshang_company += $v['gongshang_company'];
            $gongshang_person += $v['gongshang_person'];
            $shengyu_company += $v['shengyu_company'];
            $shengyu_person += $v['shengyu_person'];
            $add_company += $v['add_company'];
            $add_person += $v['add_person'];
            $else_company += $v['else_company'];
            $else_person += $v['else_person'];
            $amount_company += $v['amount_company'];
            $amount_person += $v['amount_person'];
            $assume_person += $v['assume_person'];
            $social_total += $v['social_total'];
            $final_company += $v['final_company'];
            $final_person += $v['final_person'];
            $final += $v['final'];
            $debt += $v['debt'];
            $receipt += $v['receipt'];
        }
        $res = [
            'list'=>$list,
            'data'=>$info,
            'yanglao_company'=>$yanglao_company,
            'yanglao_person'=>$yanglao_person,
            'yiliao_company'=>$yiliao_company,
            "yiliao_person"=>$yiliao_person,
            "shiye_company"=>$shiye_company,
            "shiye_person"=>$shiye_person,
            "gongshang_company"=>$gongshang_company,
            "gongshang_person"=>$gongshang_person,
            "shengyu_company"=>$shengyu_company,
            "shengyu_person"=>$shengyu_person,
            "add_company"=>$add_company,
            "add_person"=>$add_person,
            "else_company"=>$else_company,
            "else_person"=>$else_person,
            "amount_company"=>$amount_company,
            "amount_person"=>$amount_person,
            "assume_person"=>$assume_person,
            "social_total"=>$social_total,
            "final_company"=>$final_company,
            "final_person"=>$final_person,
            "final"=>$final,
            "debt"=>$debt,
            "receipt"=>$receipt
        ];
        return json($res);
    }

    /**
     * 导出社保信息
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function exportInfo()
    {
        //查询条件
        $data = request()->param();
        $cid = $data['cid'];
        $id = $data['id'];
        $idNumber = $data['idNumber'];
        $vessel = $data['vessel'];
        $ownerPool = $data['ownerPool'];
        $area = $data['area'];
        $starttime = $data['time'][0];
        $endtime = $data['time'][1];
        $type = $data['type'];
        if($cid) $where['mariner_id'] = Db::name('mariner')->where(['cid'=>$cid])->value('id');
        if($id) $where['mariner_id'] = $id;
        if($idNumber) $where['mariner_id'] = Db::name('mariner')->where(['id_number'=>$idNumber])->value('id');
        if($vessel){
            $marinerIds = Db::name('mariner')->where(['vessel'=>$vessel])->column('id');
            $where['mariner_id'] = ['IN',$marinerIds];
        }
        if($ownerPool){
            $marinerId = Db::name('mariner')->where(['owner_pool'=>$vessel])->column('id');
            $where['mariner_id'] = ['IN',$marinerId];
        }
        if($area) $where['area'] = $area;
        if($starttime && empty($endtime)) $where['pay_month'] = ['EGT',$starttime];
        if($starttime && $endtime) $where['pay_month'] = ['BETWEEN TIME',[$starttime,$endtime]];
        if($type) $where['pay_type'] = $type;
        if($where) {
            $info = Db::name('social_info')
                ->alias('a')
                ->field("a.*,b.cid,b.name,b.id_number,b.duty,c.title vessel,d.title shipOwner")
                ->join('mariner b', 'a.mariner_id=b.id', 'LEFT')
                ->join('vessel c', 'b.vessel=c.id', 'LEFT')
                ->join('shipowner d', 'd.id=b.owner_pool', 'LEFT')
                ->where($where)
                ->order('pay_month desc')
                ->select();
        }else{
            $info = Db::name('social_info')
                ->alias('a')
                ->field("a.*,b.cid,b.name,b.id_number,b.duty,c.title vessel,d.title shipOwner")
                ->join('mariner b', 'a.mariner_id=b.id', 'LEFT')
                ->join('vessel c', 'b.vessel=c.id', 'LEFT')
                ->join('shipowner d', 'd.id=b.owner_pool', 'LEFT')
                ->order('pay_month desc')
                ->select();
        }
        return json($info);
    }

    /**
     * 编辑社保信息
     * @return \think\response\Json
     * @throws \Exception
     */
    public function editInsured()
    {
        $data = input('post.');
        Db::startTrans();
        try{
            foreach ($data as $k=>$v) {
                $update = [];
                $info = Db::name('social_info')->field('id,amount_person,amount_company,debt,receipt')->where(['id' => $v['id']])->find();
                $update['assume_person'] = $v['assume_person'];
                $update['else_person'] = $v['else_person'];
                $update['else_company'] = $v['else_company'];
                $update['add_company'] = $v['add_company'];
                $update['add_person'] = $v['add_person'];
                $update['amount_person'] = $v['else_person'] + $info['amount_person'];
                $update['amount_company'] = $v['else_company'] + $info['amount_company'];
                $total = $update['amount_company'] + $update['add_company'] - $v['assume_person'];
                if ($total < 0) {
                    $update['final_company'] = 0;
                } else {
                    $update['final_company'] = $total;
                }
                $update['final_person'] = $v['assume_person'] + $update['amount_person'] + $update['add_person'];
                $update['final'] = $update['final_company'] + $update['final_person'];
                $update['debt'] = abs($info['receipt'] - $update['final_person']);
                $update['pay_type'] = "单位承担部分";
                if($update['final_person'] == $update['final']) $update['pay_type'] = "个人全额自付";
                if($update['final_company'] == $update['final']) $update['pay_type'] = "单位全额承担";
                Db::name('social_info')->where(['id' => $v['id']])->update($update);
            }
            Db::commit();
            return ok_data();
        }catch (\Exception $e){
            Db::rollback();
            throw $e;
        }
    }

    /**
     * 社保信息批量设置
     * @return \think\response\Json
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function importInfo()
    {
        if (!$_FILES) return error_data('请选择文件');
        $key = array_keys($_FILES);
        $file = $_FILES[$key[0]]["tmp_name"];
        Loader::import('PHPExcel.PHPExcel.Reader.Excel5');
        $PHPReader = new \PHPExcel_Reader_Excel5();
        if (!$PHPReader->canRead($file)) {
            $PHPReader = new \PHPExcel_Reader_Excel2007();
            if (!$PHPReader->canRead($file)) {
                return error_data('文件类型不符');
            }
        }
        $E = $PHPReader->load($file);
        $cur = $E->getSheet(0);  // 读取第一个表
        $end = $cur->getHighestColumn(); // 获得最大的列数
        $line = $cur->getHighestRow(); // 获得最大总行数
        //将最大列转换为数字
        $length = strlen($end);
        if (strlen($end) > 1) {
            $single = ord(substr($end, $length - 1)) - 64;
            $end = ($length - 1) * 26 + $single;
        } else {
            $end = ord($end) - 64;
        }
        $info = [];
        $i = 0;
        for ($row = 2; $row <= $line; $row++) {
            for ($column = 1; $column < $end; $column++) {
                $val = $cur->getCellByColumnAndRow($column, $row)->getValue();
                if(is_object($val)){
                    $info[$i][] = $val->__toString();
                }else{
                    $info[$i][] = $val;
                }
            }
            $i++;
        }
        $insert = [];
        $fail = 0;
        $success = 0;
        $repeat = 0;
        foreach ($info as $k=>$v){
            $pid = Db::name('mariner')->where(['id_number'=>$v[3],'name'=>$v[2]])->value('id');
            if(!$pid){
                $fail++;
                continue;
            }
            $infoId = Db::name('social_info')->where(['mariner_id'=>$pid,'pay_month'=>$v[1]])->value('id');
            if($infoId){
                $repeat++;
                continue;
            }
            $success++;
            $type = "单位承担部分金额";
            if(!$v[27]) $type = "单位全额自付";
            if(!$v[26]) $type = "个人全额自付";
            $insert[] = [
                'mariner_id'=>$pid,
                'pay_month'=>(string)$v[0],
                'first_date'=>(string)$v[8],
                'area'=>(string)$v[7],
                'yanglao_company'=>(float)$v[9],
                'yiliao_company'=>(float)$v[10],
                'shiye_company'=>(float)$v[11],
                'gongshang_company'=>(float)$v[12],
                'shengyu_company'=>(float)$v[13],
                'else_company'=>(float)$v[14],
                'amount_company'=>(float)$v[15],
                'yanglao_person'=>(float)$v[16],
                'yiliao_person'=>(float)$v[17],
                'shiye_person'=>(float)$v[18],
                'gongshang_person'=>(float)$v[19],
                'shengyu_person'=>(float)$v[20],
                'else_person'=>(float)$v[21],
                'amount_person'=>(float)$v[22],
                'assume_person'=>(float)$v[23],
                'add_company'=>(float)$v[24],
                'add_person'=>(float)$v[25],
                'final_company'=>(float)$v[26],
                'final_person'=>(float)$v[27],
                'social_total'=>(float)$v[28],
                'final'=>(float)$v[29],
                'receipt'=>(float)$v[30],
                'debt'=>(float)$v[31],
                'remark'=>(string)$v[32],
                'pay_type'=>$type
            ];
        }
        $res = Db::name('social_info')->insertAll($insert);
        if($res) return ok_data("成功导入".$success."条数据,"."重复".$repeat."条,失败".$fail."条");
        return error_data();
    }

    /**
     * 社保对账信息
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function checkInsured()
    {
        $area = input('area/a');
        $page = input('page');
        $starttime = formatTime(input('time/a')[0],'Y-m');
        $endtime = formatTime(input('time/a')[1],'Y-m');
        $where = [];
        if($area) $where['area'] = ['IN',$area];
        if($starttime && empty($endtime)) $where['pay_month'] = ['EGT',$starttime];
        if($starttime && $endtime) $where['pay_month'] = ['BETWEEN',[$starttime,$endtime]];
            $list = Db::name('social_info')
                ->field('area,pay_month,sum(debt) debt,sum(receipt) receipt')
                ->where($where)
                ->group('area,pay_month')
                ->count();
            $info = Db::name('social_info')
                ->field('area,pay_month,sum(debt) debt,sum(receipt) receipt')
                ->where($where)
                ->group('area,pay_month')
                ->order('pay_month')
                ->page($page,self::$listRows)
                ->select();
        $debtTotal = 0;
        $receiptTotal = 0;
        foreach ($info as $k=>$v){
            $debtTotal += $v['debt'];
            $receiptTotal += Db::name('social_info_receipt')->where(['area'=>$info['area'],'month'=>$v['pay_month']])->sum('receipt');
            $sure = Db::name('social_info_sure')->where(['area'=>$v['area'],'month'=>$v['pay_month']])->value('area');
            if($sure){
                $info[$k]['sure'] = 1;
            }else{
                $info[$k]['sure'] = 0;
            }
        }
        $res = [
            'list'=>$list,
            'data'=>$info,
            'debtTotal'=>$debtTotal,
            'receipt'=>$receiptTotal
        ];
        return json($res);
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function exportInsured()
    {
        $area = input('area/a');
        $starttime = input('time/a')[0];
        $endtime = input('time/a')[1];
        $where = [];
        if($area) $where['area'] = ['IN',$area];
        if($starttime && empty($endtime)) $where['pay_month'] = ['EGT',$starttime];
        if($starttime && $endtime) $where['pay_month'] = ['BETWEEN TIME',[$starttime,$endtime]];
            $info = Db::name('social_info')
                ->field('area,pay_month,sum(debt) debt,sum(receipt) receipt')
                ->where($where)
                ->group('area,pay_month')
                ->order('pay_month')
                ->select();
        $debtTotal = 0;
        $receiptTotal = 0;
        foreach ($info as $k=>$v){
            $debtTotal += $v['debt'];
            $receiptTotal += $v['receipt'];
            $sure = Db::name('social_info_sure')->where(['area'=>$v['area'],'month'=>$v['pay_month']])->value('area');
            if($sure){
                $info[$k]['sure'] = 1;
            }else{
                $info[$k]['sure'] = 0;
            }
        }
        $res = [
            'data'=>$info,
            'debtTotal'=>$debtTotal,
            'receipt'=>$receiptTotal
        ];
        return json($res);
    }

    /**
     * 社保对账确认
     * @return \think\response\Json
     */
    public function sureInsured()
    {
        $area = input('area');
        $payMonth = input('month');
        $debt = input('debt');
        $receipt = input('receipt');
        $insert = [
            'month'=>$payMonth,
            'area'=>$area,
            'debt'=>$debt,
            'receipt'=>$receipt,
            'time'=>formatTime(time())
        ];
        $res = Db::name('social_info_sure')->insert($insert);
        if($res) return ok_data();
        return error_data();
    }

    /**
     * 社保还款
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function refund()
    {
        $id = input('id');
        $receipt = input('receipt');
        //还款信息
        $info = Db::name('social_info')->field('pay_month month,debt,receipt,area')->where(['id'=>$id])->find();
        $sureId = Db::name('social_info_sure')->where(['month'=>$info['month'],'area'=>$info['area']])->value('month');
        $maxMonth =  Db::name('social_info_sure')->max('month');
        if($receipt > $info['debt'] || $receipt < 0) return error_data("还款金额输入错误");
        if($sureId){
            $insert = [
                'pid'=>$id,
                'area'=>$info['area'],
                'month'=>date('Y-m',strtotime("$maxMonth +1 month")),
                'receipt'=>$receipt-$info['receipt']
            ];
        }else{
            $insert = [
                'pid'=>$id,
                'area'=>$info['area'],
                'month'=>date('Y-m',time()),
                'receipt'=>$receipt-$info['receipt']
            ];
        }
        Db::startTrans();
        try{
            Db::name('social_info_receipt')->insert($insert);
            Db::name('social_info')->where(['id'=>$id])->update(['receipt'=>$receipt]);
            Db::commit();
            return ok_data();
        }catch (\Exception $e){
            Db::rollback();
            return error_data();
        }
    }
}