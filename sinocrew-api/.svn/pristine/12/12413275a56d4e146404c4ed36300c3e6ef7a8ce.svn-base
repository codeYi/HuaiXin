<?php
/**
 * @desc Created by PhpStorm.
 * @author: CodeYi
 * @since: 2018-05-08 17:51
 */
namespace app\api\controller;
use app\api\common\Base;
use think\Db;

/**
 * 员工管理
 * Class Staff
 * @package app\api\controller
 */
class Staff extends Base
{
    /**
     * 添加员工
     * @return \think\response\Json
     */
    public function addStaff()
    {
        $data = input('post.');
        if(empty($data['fn']) || empty($data['username']) || empty($data['department']) || empty($data['duty']) || empty($data['idNumber'])) return error_data("档案号、姓名、身份证号码、部门、职务");
        $sure = Db::name('user')->where(['id_number'=>$data['idNumber']])->value('id');
        if($sure) return error_data('该身份证号被占用');
        $fn = Db::name('user')->where(['fn'=>$data['fn']])->value('id');
        if($fn) return error_data('档案号重复');
        if($data['principal'] == "是" && $data['department'] != "财务部") return error_data('只有财务部成员才可以是负责人');
        $abbreviation = $this->staffList($data['username']);
        $str = encode($data['username'],true);
        $str = explode(' ',$str);
        if($str[0]) $str[0] = ucfirst($str[0]);
        if($str[1]) $str[1] = " ".ucfirst($str[1]);
        $english = implode('',$str);
        $insert = [
            'fn'=>$data['fn'],
            'username'=>$data['username'],
            'id_number'=>$data['idNumber'],
            'gender'=>$data['gender'],
            'department'=>$data['department'],
            'duty'=>$data['duty'],
            'shipowner'=>$data['shipowner'],
            'appoint_duty'=>$data['appoint_duty'],
            'appoint_date'=>$data['appoint_date'],
            'working_date'=>$data['working_date'],
            'dimission_date'=>$data['dimission_date'],
            'assume_office_date'=>$data['assume_office_date'],
            'birthday'=>$data['birthday'],
            'age'=>$data['age'],
            'edu_background'=>$data['edu_background'],
            'degree'=>$data['degree'],
            'major'=>$data['major'],
            'school'=>$data['school'],
            'graduation_date'=>$data['graduation_date'],
            'sign_start'=>$data['sign_start'],
            'sign_end'=>$data['sign_end'],
            'professional_skill'=>$data['professional_skill'],
            'qualification'=>$data['qualification'],
            'political_status'=>$data['political_status'],
            'marry'=>$data['marry'],
            'residence'=>$data['residence'],
            'work_date'=>$data['work_date'],
            'regular_date'=>$data['regular_date'],
            'phone_number'=>$data['phone_number'],
            'telnumber'=>$data['telnumber'],
            'address'=>$data['address'],
            'email'=>$data['email'],
            'birthplace'=>$data['birthplace'],
            'remark'=>$data['remark'],
            'url'=>$data['url'],
            'password'=>md5(substr($data['idNumber'],-6)),
            'english'=>$english,
            'principal'=>$data['principal'],
            'abbreviation'=>$abbreviation
        ];
        $res = Db::name('user')->insert($insert);
        if($res) return ok_data();
        return error_data();
    }

    /**
     * 获取司龄
     * @return \think\response\Json
     */
    public function getOffice()
    {
        $workingDate = input('working_date');
        $dimissionDate = input('dimission_date');
        if($workingDate && $dimissionDate){
            $time = (strtotime($dimissionDate)-strtotime($workingDate))/31566000;
        }elseif ($workingDate && !$dimissionDate){
            $time = (time()-strtotime($workingDate))/31566000;
        }
        $res = [
            'year'=>round($time,1)
        ];
        return json($res);
    }

    /**
     * 通过身份证获取生日和年龄
     * @return \think\response\Json
     */
    public function getBirthday()
    {
        $idNumber = input('idNumber');
        $birthday = substr($idNumber,6,8);
        $age = floor((time()-strtotime($birthday))/31566000);
        $res = [
            'birthday'=>$birthday,
            'age'=>$age
        ];
        return json($res);
    }

    /**
     * 上传图片
     * @return \think\response\Json
     */
    public function uploadImg()
    {
        $info = $this->upload('image',['png','jpg','']);
        return json($info);
    }

    /**
     * 删除图片
     * @return \think\response\Json
     */
    public function deleteImg()
    {
        $url = input('url');
        $url = str_replace(SITE, ROOT_PATH . 'public' . DS . 'uploads' . DS, $url);
        if (!file_exists($url)) {
            return json(['status' => '001', 'msg' => '图片不存在!']);
        }
        $res = @unlink($url);
        if ($res) {
            return json(['status' => '200', 'msg' => '成功删除!']);
        } else {
            return json(['status' => '001', 'msg' => '参数错误!']);
        }
    }

    /**
     * 员工列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function listStaff()
    {
        $data = input('post.');
        $page = input('page')?input('page'):1;
        $listRows = input('listRows')?input('listRows'):self::$listRows;
        $where = [];
        if($data['abbreviation']) $where['abbreviation ']= ['LIKE',"%".$data['abbreviation']."%"];
        if($data['username']) $where['username'] = ['LIKE',"%".$data['username']."%"];
        if($data['idNumber']) $where['id_number'] = ['LIKE',"%".$data['idNumber']."%"];
        if($data['gender']) $where['gender'] = $data['gender'];
        if($data['department']) $where['department'] = ['LIKE',"%".$data['department']."%"];
        if($data['duty']) $where['duty'] =  ['LIKE',"%".$data['duty']."%"];
        if($data['shipowner']) $where['shipowner'] = $data['shipowner'];
        if($data['fn']) $where['fn'] = ['LIKE',"%".$data['fn']."%"];
        if($data['appoint_duty']) $where['appoint_duty'] = ['LIKE',"%".$data['appoint_duty']."%"];
        if($data['age']) $where['age'] = $data['age'];
        if($data['working_date'][0] && $data['working_date'][1]){
            $where['working_date'] = ['BETWEEN',[$data['working_date'][0],$data['working_date'][1]]];
        }
        if($data['dimission_date'][0] && $data['dimission_date'][1]){
            $where['dimission_date'] = ['BETWEEN',[$data['dimission_date'][0],$data['dimission_date'][1]]];
        }
        if($data['assume_office_date'][0] && $data['assume_office_date'][1]){
            $where['assume_office_date'] = ['BETWEEN',[$data['assume_office_date'][0],$data['assume_office_date'][1]]];
        }
        if($data['marry']) $where['marry'] = $data['marry'];
        if($data['education']) $where['edu_background'] = $data['education'];
        if($data['degree']) $where['degree'] = $data['degree'];
        if($data['major']) $where['major'] = $data['major'];
        if($data['school']) $where['school'] = $data['school'];
        if($data['professional_skill']) $where['professional_skill'] = ['LIKE',"%".$data['professional_skill']."%"];
        if($data['organization']) $where["political_status"] = $data['organization'];
        if($data['residence']) $where['residence'] = ['LIKE',"%".$data['residence']."%"];
        if($data['sign_start'][0] && $data['sign_start'][1]){
            $where['sign_start'] = ['BETWEEN',[$data['sign_start'][0],$data['sign_start'][1]]];
        }
        if($data['sign_end'][0] && $data['sign_end'][1]){
            $where['sign_end'] = ['BETWEEN',[$data['sign_end'][0],$data['sign_end'][1]]];
        }
        if($data['work_date'][0] && $data['work_date'][1]){
            $where['work_date'] = ['BETWEEN',[$data['work_date'][0],$data['work_date'][1]]];
        }
        if($data['qualification']) $where['qualification'] = ['LIKE',"%".$data['qualification']."%"];
        if($data['birthplace']) $where['birthplace'] = ['LIKE',"%".$data['birthplace']."%"];
        if($data['regular_date'][0] && $data['regular_date'][1]){
            $where['regular_date'] = ['BETWEEN',[$data['regular_date'][0],$data['regular_date'][1]]];
        }
        if($data['phone_number']) $where['phone_number'] = ['LIKE',"%".$data['phone_number']."%"];
        if($data['telnumber']) $where['telnumber'] = ['LIKE',"%".$data['telnumber']."%"];
        if($data['email']) $where['email'] = $data['email'];
        if($data['address']) $where['address'] = ['LIKE',"%".$data['address']."%"];
        if($data['remark']) $where['remark'] = ['LIKE',"%".$data['remark']."%"];
        if($data['appoint_date'][0] && $data['appoint_date'][1]) $where['appoint_date'] = ['BETWEEN',[$data['appoint_date'][0],$data['appoint_date'][1]]];
        if($data['birthday'][0] && $data['birthday'][1]) $where['birthday'] = ['BETWEEN',[$data['birthday'][0],$data['birthday'][1]]];
        if($data['graduation_date'][0] && $data['graduation_date'][1]) $where['graduation_date'] = ['BETWEEN',[$data['graduation_date'][0],$data['graduation_date'][1]]];
            $list = Db::name('user')
                ->alias('a')
                ->field('a.*,b.title')
                ->join('shipowner b', 'a.shipowner=b.id', 'LEFT')
                ->where($where)
                ->count();
            $info = Db::name('user')
                ->alias('a')
                ->field('a.*,b.title')
                ->join('shipowner b', 'a.shipowner=b.id', 'LEFT')
                ->where($where)
                ->page($page, $listRows)
                ->select();
        $res = [
            'list'=>$list,
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 导出数据
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function exportStaff()
    {
        $data = input('post.');
        $where = [];
        if($data['abbreviation']) $where['abbreviation ']= ['LIKE',"%".$data['abbreviation']."%"];
        if($data['username']) $where['username'] = ['LIKE',"%".$data['username']."%"];
        if($data['idNumber']) $where['id_number'] = ['LIKE',"%".$data['idNumber']."%"];
        if($data['gender']) $where['gender'] = $data['gender'];
        if($data['department']) $where['department'] = ['LIKE',"%".$data['department']."%"];
        if($data['duty']) $where['duty'] =  ['LIKE',"%".$data['duty']."%"];
        if($data['shipowner']) $where['shipowner'] = $data['shipowner'];
        if($data['fn']) $where['fn'] = ['LIKE',"%".$data['fn']."%"];
        if($data['appoint_duty']) $where['appoint_duty'] = ['LIKE',"%".$data['appoint_duty']."%"];
        if($data['age']) $where['age'] = $data['age'];
        if($data['working_date'][0] && $data['working_date'][1]){
            $where['working_date'] = ['BETWEEN',[$data['working_date'][0],$data['working_date'][1]]];
        }
        if($data['dimission_date'][0] && $data['dimission_date'][1]){
            $where['dimission_date'] = ['BETWEEN',[$data['dimission_date'][0],$data['dimission_date'][1]]];
        }
        if($data['assume_office_date'][0] && $data['assume_office_date'][1]){
            $where['assume_office_date'] = ['BETWEEN',[$data['assume_office_date'][0],$data['assume_office_date'][1]]];
        }
        if($data['marry']) $where['marry'] = $data['marry'];
        if($data['education']) $where['edu_background'] = $data['education'];
        if($data['degree']) $where['degree'] = $data['degree'];
        if($data['major']) $where['major'] = $data['major'];
        if($data['school']) $where['school'] = $data['school'];
        if($data['professional_skill']) $where['professional_skill'] = ['LIKE',"%".$data['professional_skill']."%"];
        if($data['organization']) $where["political_status"] = $data['organization'];
        if($data['residence']) $where['residence'] = ['LIKE',"%".$data['residence']."%"];
        if($data['sign_start'][0] && $data['sign_start'][1]){
            $where['sign_start'] = ['BETWEEN',[$data['sign_start'][0],$data['sign_start'][1]]];
        }
        if($data['sign_end'][0] && $data['sign_end'][1]){
            $where['sign_end'] = ['BETWEEN',[$data['sign_end'][0],$data['sign_end'][1]]];
        }
        if($data['work_date'][0] && $data['work_date'][1]){
            $where['work_date'] = ['BETWEEN',[$data['work_date'][0],$data['work_date'][1]]];
        }
        if($data['qualification']) $where['qualification'] = ['LIKE',"%".$data['qualification']."%"];
        if($data['birthplace']) $where['birthplace'] = ['LIKE',"%".$data['birthplace']."%"];
        if($data['regular_date'][0] && $data['regular_date'][1]){
            $where['regular_date'] = ['BETWEEN',[$data['regular_date'][0],$data['regular_date'][1]]];
        }
        if($data['phone_number']) $where['phone_number'] = ['LIKE',"%".$data['phone_number']."%"];
        if($data['telnumber']) $where['telnumber'] = ['LIKE',"%".$data['telnumber']."%"];
        if($data['email']) $where['email'] = $data['email'];
        if($data['address']) $where['address'] = ['LIKE',"%".$data['address']."%"];
        if($data['remark']) $where['remark'] = ['LIKE',"%".$data['remark']."%"];
        if($data['appoint_date'][0] && $data['appoint_date'][1]) $where['appoint_date'] = ['BETWEEN',[$data['appoint_date'][0],$data['appoint_date'][1]]];
        if($data['birthday'][0] && $data['birthday'][1]) $where['birthday'] = ['BETWEEN',[$data['birthday'][0],$data['birthday'][1]]];
        if($data['graduation_date'][0] && $data['graduation_date'][1]) $where['graduation_date'] = ['BETWEEN',[$data['graduation_date'][0],$data['graduation_date'][1]]];
            $info = Db::name('user')
                ->alias('a')
                ->field('a.*,b.title')
                ->join('shipowner b', 'a.shipowner=b.id', 'LEFT')
                ->where($where)
                ->select();
        return json($info);
    }

    /**
     * 员工详情
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function detailStaff()
    {
        $id = input('id');
        $info = Db::name('user')
            ->alias('a')
            ->field('a.*,b.title')
            ->where(['a.id'=>$id])
            ->join('shipowner b','a.shipowner=b.id','LEFT')
            ->find();
        $res = [
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 编辑员工
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function editStaff()
    {
        $data = input('post.');
        if(empty($data['fn']) || empty($data['username']) || empty($data['department']) || empty($data['duty']) || empty($data['idNumber'])) return error_data("档案号、姓名、身份证号码、部门、职务");
        $sure = Db::name('user')->where(['id_number'=>$data['idNumber'],'id'=>['NEQ',$data['id']]])->value('id');
        if($sure) return error_data('该身份证号被占用');
        $fn = Db::name('user')->where(['fn'=>$data['fn'],'id'=>['NEQ',$data['id']]])->value('id');
        if($fn) return error_data('档案号重复');
        if($data['principal'] == "是" && $data['department'] != "财务部") return error_data('只有财务部成员才可以是负责人');
        $abbreviation = $this->staffList($data['username']);
        $str = encode($data['username'],true);
        $str = explode(' ',$str);
        if($str[0]) $str[0] = ucfirst($str[0]);
        if($str[1]) $str[1] = " ".ucfirst($str[1]);
        $english = implode('',$str);
        $update = [
            'fn'=>$data['fn'],
            'username'=>$data['username'],
            'id_number'=>$data['idNumber'],
            'gender'=>$data['gender'],
            'department'=>$data['department'],
            'duty'=>$data['duty'],
            'shipowner'=>$data['shipowner'],
            'appoint_duty'=>$data['appoint_duty'],
            'appoint_date'=>$data['appoint_date'],
            'working_date'=>$data['working_date'],
            'dimission_date'=>$data['dimission_date'],
            'assume_office_date'=>$data['assume_office_date'],
            'birthday'=>$data['birthday'],
            'age'=>$data['age'],
            'edu_background'=>$data['edu_background'],
            'degree'=>$data['degree'],
            'major'=>$data['major'],
            'school'=>$data['school'],
            'graduation_date'=>$data['graduation_date'],
            'sign_start'=>$data['sign_start'],
            'sign_end'=>$data['sign_end'],
            'professional_skill'=>$data['professional_skill'],
            'qualification'=>$data['qualification'],
            'political_status'=>$data['political_status'],
            'marry'=>$data['marry'],
            'residence'=>$data['residence'],
            'work_date'=>$data['work_date'],
            'regular_date'=>$data['regular_date'],
            'phone_number'=>$data['phone_number'],
            'telnumber'=>$data['telnumber'],
            'address'=>$data['address'],
            'email'=>$data['email'],
            'birthplace'=>$data['birthplace'],
            'remark'=>$data['remark'],
            'url'=>$data['url'],
            'password'=>md5(substr($data['idNumber'],-6)),
            'english'=>$english,
            'principal'=>$data['principal'],
            'abbreviation'=>$abbreviation
        ];
        $res = Db::name('user')->where(['id'=>$data['id']])->update($update);
        if($res) return ok_data();
        return error_data();
    }

    /**
     * 获取所有的学历
     * @return \think\response\Json
     */
    public function education()
    {
        $info = Db::name('user')->column('edu_background');
        $info = array_values(array_unique(array_filter($info)));
        $res = [
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 获取所有的学位
     * @return \think\response\Json
     */
    public function degree()
    {
        $info = Db::name('user')->column('degree');
        $info = array_values(array_unique(array_filter($info)));
        $res = [
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 获取所有的组织
     * @return \think\response\Json
     */
    public function organization()
    {
        $info = Db::name('user')->column('political_status');
        $info = array_values(array_unique(array_filter($info)));
        $res = [
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 简称查询员工
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUser()
    {
        $abbreviation = strtolower(input('abbreviation'));
        if(empty($abbreviation)) return error_data('请输入简称');
        $where['abbreviation|username'] = ['LIKE',"$abbreviation%"];
        $info = Db::name('user')->where($where)->select();
        if(!$info) return error_data('查无此人');
        $query = [];
        foreach ($info as $k=>$v){
            $query[] = [
                'id'=>$v['id'],
                'info'=> $v['username']."/".$v['duty']."/".substr($v['id_number'],6,4)."-".substr($v['id_number'],10,2)."-".substr($v['id_number'],12,2),
                'id_number'=>$v['id_number']
            ];
        }
        return json($query);
    }
}