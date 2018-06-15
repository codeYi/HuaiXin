<?php
/**
 * @desc Created by PhpStorm.
 * @author: CodeYi
 * @since: 2018-04-16 11:43
 */
namespace app\api\controller;
use app\api\common\Base;
use think\Db;
use think\Loader;

/**
 * 权限控制器
 * Class Privilege
 * @package app\api\controller
 */
class Privilege extends Base
{
    /**
     *员工列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function listStaff()
    {
        $data = $this->request->param();
        $where['username'] = ['LIKE',"%".$data['search']."%"];
        if($data['department']) $where['department']  = $data['department'] ;
        $list = Db::name('user')
            ->where($where)
            ->count();
        $info = Db::name('user')
            ->alias('a')
            ->field("a.*,c.name")
            ->join('role_user b','a.id = b.user_id','LEFT')
            ->join('role c','b.role_id = c.id','LEFT')
            ->where($where)
            ->order('department desc')
            ->page($data['page'],self::$listRows)
            ->select();
        $res = [
            'data'=>$info,
            'list'=>$list
        ];
        return json($res);
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
        $info = Db::name('user')->where(['id'=>$id])->find();
        $res = [
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 批量上传员工
     * @return \think\response\Json
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function importStaff()
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
            $end = ord($end) - 65;
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
        $idNumber = Db::name('user')->column('id_number');
        $info_ = [];
        foreach ($info as $key => $value) {
            if (!in_array((string)$value[10], $idNumber, true)) $info_[] = $value;
        }
        if (!$info_) return error_data('请勿重复导入员工');
        $insert = [];
        $success = 0;
        $fail = 0;
        foreach ($info_ as $k=>$v){
            //缺少必要信息
            if(empty($v[0]) || empty($v[1]) || empty($v[3]) || empty($v[5]) || empty($v[10])) continue;
            $data['fn'] = (string)$v[0];
            $data['username'] = (string)$v[1];
            $data['password'] = md5(substr($v[10],-6));
            $data['abbreviation'] = $this->staffList($v[1]);
            $str = encode($v[1],true);
            $str = explode(' ',$str);
            if($str[0]) $str[0] = ucfirst($str[0]);
            if($str[1]) $str[1] = " ".ucfirst($str[1]);
            $data['english'] = implode('',$str);
            $data['gender'] = (string)$v[2];
            $data['department'] = (string)$v[3];
            $shipperId = Db::name('shipowner')->where(['title'=>$v[4]])->value('id');
            if(!$shipperId) {
                $fail++;
                continue;
            }
            $data['shipowner'] = $shipperId;
            $data['duty'] = (string)$v[5];
            $data['appoint_duty'] = (string)$v[6];
            $data['appoint_date'] = formatTime(strtotime((string)$v[7]),'Y-m-d');
            $time = (time()-strtotime((string)$v[8]))/31566000;
            if($time)  $data['assume_office_date'] = round($time,1);
            $data['working_date'] = formatTime(strtotime((string)$v[8]),'Y-m-d');
            $data['dimission_date'] = formatTime(strtotime((string)$v[9]),'Y-m-d');
            $data['id_number'] = (string)$v[10];
            $data['birthday'] = formatTime(strtotime((string)$v[11]),'Y-m-d');
            $age = (time()-strtotime((string)$v[11]))/31566000;
            $data['age'] = round($age,1);
            $data['edu_background'] = (string)$v[12];
            $data['degree'] = (string)$v[13];
            $data['major'] = (string)$v[14];
            $data['school'] = (string)$v[15];
            $data['graduation_date'] = formatTime(strtotime((string)$v[16]),'Y-m-d');
            $data['sign_start'] = formatTime(strtotime((string)$v[17]),'Y-m-d');
            $data['sign_end'] = formatTime(strtotime((string)$v[18]),'Y-m-d');
            $data['professional_skill'] = (string)$v[19];
            $data['qualification'] = (string)$v[20];
            $data['residence'] = (string)$v[21];
            $data['political_status'] = (string)$v[22];
            $data['marry'] = (string)$v[23];
            $data['work_date'] = formatTime(strtotime((string)$v[24]),'Y-m-d');
            $data['birthplace'] = (string)$v[25];
            $data['regular_date'] = formatTime(strtotime((string)$v[26]),'Y-m-d');
            $data['address'] = (string)$v[27];
            $data['phone_number'] = (string)$v[28];
            $data['telnumber'] = (string)$v[29];
            $data['email'] = (string)$v[30];
            $data['remark'] = (string)$v[31];
            $data['url'] = "";
            $data['is_delete'] = 0;
            $success++;
            $insert[] = $data;
        }
       $res = Db::name('user')->insertAll($insert);
        if($res) return ok_data();
        return error_data();
    }

    /**
     * 获取员工的英文姓名
     * @return \think\response\Json
     */
    public function zw2py()
    {
        $chinese = input('chinese');
        $str = encode($chinese,true);
        $str = explode(' ',$str);
        if($str[0]) $str[0] = ucfirst($str[0]);
        if($str[1]) $str[1] = " ".ucfirst($str[1]);
        $str = implode('',$str);
        $res = [
            'english'=>$str
        ];
        return json($res);
    }

    /**
     * 禁用/启用操作
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function forbidden()
    {
        $id = input('id/a');
        $value = input('value');
        $res = Db::name('user')->where(['id'=>['IN',$id]])->update(['is_delete'=>$value]);
        if($res) return ok_data();
        return error_data("设置失败");
    }

    /**
     * 添加角色
     * @return \think\response\Json
     */
    public function addRole()
    {
        $roleName = input('roleName');
        $remark = input('remark');
        if(empty($roleName) || empty($remark)) return error_data("所填信息不能为空");
        $id = Db::name('role')->where(['name'=>$roleName])->value('id');
        if($id) return error_data('角色名重复');
        $res = Db::name('role')->insert(['name'=>$roleName,'remark'=>$remark,'time'=>formatTime(time())]);
        if($res) return ok_data();
        return error_data();
    }

    /**
     * 角色列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function listRole()
    {
        $page = input('page');
        $listRows = input('listRows');
        $listRows = $listRows?$listRows:self::$listRows;
        $sort = input('sort');
        if(empty($sort)){
            $sort = "id asc";
        }else{
            $sort = "$sort asc";
        }
        $list = Db::name('role')
            ->field('id,name,remark,time,status')
            ->count();
        $info = Db::name('role')
            ->field('id,name,remark,time,status')
            ->order($sort)
            ->page($page,$listRows)
            ->select();
        foreach ($info as $k=>$v){
         $info[$k]['number'] = Db::name('role_user')->where(['role_id'=>$v['id']])->count();
        }
        $res = [
            'list'=>$list,
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 角色详情
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function detailRole()
    {
        $id = input('id');
        $info = Db::name('role')
            ->field('id,name,remark')
            ->where(['id'=>$id])
            ->find();
        $res = [
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 编辑角色
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function editRole()
    {
        $id = input('id');
        $roleName = input('roleName');
        $remark = input('remark');
        if(empty($roleName) || empty($remark)) return error_data("所填信息不能为空");
        $update = [
            'name'=>$roleName,
            'remark'=>$remark,
        ];
        $res = Db::name('role')->where(['id'=>$id])->update($update);
        if($res) return ok_data("更新成功");
        error_data("更新失败");
    }

    /**
     * 删除角色
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function delRole()
    {
        $id = input('id');
        if($id == 1) return error_data('不能删除系统管理员');
       Db::startTrans();
       try{
           Db::name('role_user')->where(['role_id'=>$id])->delete();
           Db::name('role')->where(['id'=>$id])->delete();
           Db::commit();
           return ok_data();
       }catch (\Exception $e){
            Db::rollback();
            return error_data();
       }

    }

    /**
     * 禁用角色
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function forbiddenRole()
    {
        $id = input('id');
        $value = input('value');
        $res = Db::name('role')->where(['id'=>$id])->update(['status'=>$value]);
        if($res) return ok_data();
        return error_data();
    }

    /**
     * 角色列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function allRole()
    {
        $info = Db::name('role')->field('id,name')->where(['status'=>1,'id'=>['NEQ',1]])->select();
        return json($info);
    }

    /**
     *用户角色详情
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function detailUser()
    {
        $userId = input('userId');
        $roleInfo = Db::name('role_user')->where(['user_id'=>$userId])->find();
        $info = Db::name('role')->field('id,name')->where(['id'=>['NEQ',1]])->select();
        $res = [
            'roleInfo'=>$roleInfo,
            'info'=>$info
        ];
        return json($res);
    }

    /**
     * 账号管理列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function accountList()
    {
        $userId = input('userId');
        $idNumber = input('idNumber');
        $department = input('department');
        $roleId = input('roleId');
        $page = input('page')?input('page'):1;
        $listRows = input('listRows')?input('listRows'):self::$listRows;
        $where = [];
        if($department) $where['department'] = $department;
        if($idNumber) $where['id_number'] = ['LIKE',"%$idNumber%"];
        if($userId) $where['user_id'] = $userId;
        if($roleId) $where['role_id'] = $roleId;
        $list = Db::name('user')
            ->alias('a')
            ->join('role_user c','a.id=c.user_id','LEFT')
            ->join('role b','b.id=c.role_id','LEFT')
            ->where($where)
            ->count();
        $info = Db::name('user')
            ->alias('a')
            ->field('a.id,a.username,id_number,department,duty,c.role_id,b.name,a.is_delete')
            ->join('role_user c','a.id=c.user_id','LEFT')
            ->join('role b','b.id=c.role_id','LEFT')
            ->where($where)
            ->order('department')
            ->page($page,$listRows)
            ->select();
        $res = [
            'list'=>$list,
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 更新用户的角色
     * @return \think\response\Json
     */
    public function bindRole()
    {
        $useId = input('userId');
        $roleId = input('roleId');
        $insert = [
            'role_id'=>$roleId,
            'user_id'=>$useId
        ];
        Db::startTrans();
        try{
            Db::name('role_user')->where(['user_id'=>$useId])->delete();
            Db::name('role_user')->insert($insert);
            Db::commit();
            return ok_data();
        }catch (\Exception $e){
            Db::rollback();
            return error_data();
        }

    }

    /**
     * 权限列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function listPrivilege()
    {
        $info = Db::name('node')->field('id,name')->where(['status'=>1,'pid'=>0])->order('sort desc')->select();
        //格式化数据
       foreach ($info as $k=>$v){
           $info[$k]['children'] = Db::name('node')->field('id,name')->where(['pid'=>$v['id']])->select();
       }
        //获取当前用户的权限数据
        $privilegeInfo = Db::name('role_user')
            ->alias('a')
            ->join('access b','a.role_id=b.role_id','LEFT')
            ->where(['user_id'=>self::$id])
            ->select();
        $res = [
            'data'=>$info,
            'info'=>$privilegeInfo
        ];
        return json($res);
    }

    /**
     * 添加权限
     * @return \think\response\Json
     */
    public function addPrivilege()
    {
        $roleId = input("roleId");
        $nodeId = input('ids/a');
        $insert = [];
        foreach ($nodeId as $v){
            $insert['role_id'] = $roleId;
            $insert['node_id'] = $v;
            $result[] = $insert;
        }
        Db::startTrans();
        try{
            Db::name('access')->where(['role_id'=>$roleId])->delete();
            Db::name('access')->insertAll($result);
            Db::commit();
            return ok_data();
        }catch(\Exception $e){
            Db::rollback();
            return error_data();
        }
    }

    /**
     * 获取角色对应的节点id
     * @return \think\response\Json
     */
    public function personPrivilege()
    {
        $roleId = input('roleId');
        $info = Db::name('access')->where(['role_id'=>$roleId])->column('node_id');
        return json($info);
    }

    /**
     * 添加代理
     * @return \think\response\Json
     */
    public function agentAdd()
    {
        $agentId = input('agentId');
        $date = input('date/a');
        if(empty($date[0]) || empty($date[1])) return error_data('开始日期和结束日期不能为空');
        $insert = [
            'user_id'=>self::$id,
            'agent_id'=>$agentId,
            'start_date'=>$date[0],
            'end_date'=>$date[1],
            'time'=>formatTime(time())
        ];
        $res = Db::name('agent')->insert($insert);
        if($res) return ok_data();
        return error_data();
    }

    /**
     * 代理列表
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function agentList()
    {
        Db::name('agent')->where(['end_date'=>['LT',formatTime(time(),'Y-m-d')]])->update(['status'=>3]);
        $page = input('page');
        $listRows = input('listRows')?input('listRows'):self::$listRows;
        $list = Db::name('agent')
            ->where(['user_id'=>self::$id])
            ->count();
        $info = Db::name('agent')
            ->where(['user_id'=>self::$id])
            ->page($page,$listRows)
            ->order('start_date')
            ->select();
        foreach ($info as $k=>$v){
            $info[$k]['username'] = Db::name('user')->where(['id'=>$v['user_id']])->value('username');
            $info[$k]['agentName'] = Db::name('user')->where(['id'=>$v['agent_id']])->value('username');
            if($v['status'] == 1) $info[$k]['status'] = "代理中";
            if($v['status'] == 2) $info[$k]['status'] = "已撤销";
            if($v['status'] == 3) $info[$k]['status'] = "已结束";
        }
        $res = [
            'list'=>$list,
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 代理详情
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function agentDetail()
    {
        $id =input('id');
        $info = Db::name('agent')->field('id,start_date,end_date')->find($id);
        $userInfo = Db::name('user')->where(['id'=>$info['agent_id']])->find();
        $info['agent_name'] = $userInfo['username']."/".$userInfo['duty']."/".substr($userInfo['id_number'],6,4)."-".substr($userInfo['id_number'],10,2)."-".substr($userInfo['id_number'],12,2);
        $res = [
            'data'=>$info
        ];
        return json($res);
    }

    /**
     * 更新代理权限
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function agentEdit()
    {
        $id = input('id');
        $agentId = input('agentId');
        $date = input('date/a');
        if(empty($date[0]) || empty($date[1])) return error_data('开始日期和结束日期不能为空');
        $status = 1;
        if($date[1] < date(time())) $status = 3;
        $update = [
            'user_id'=>self::$id,
            'agent_id'=>$agentId,
            'start_date'=>$date[0],
            'end_date'=>$date[1],
            'status'=>$status
        ];
        $res = Db::name('agent')->where(['id'=>$id])->update($update);
        if($res) return ok_data();
        return error_data();
    }

    /**
     * 撤销代理权限
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function agentStop()
    {
       $res = Db::name('agent')->where(['id'=>input('id')])->update(['status'=>2]);
       if($res) return ok_data();
       return error_data();
    }
}