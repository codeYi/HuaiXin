<?php
/**
 * @desc Created by PhpStorm.
 * @author: CodeYi
 * @since: 2018-04-18 16:24
 */

namespace app\api\controller;

use app\api\common\Base;
use think\Config;
use think\Db;
use think\Cache;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * 登录控制器
 * Class Login
 * @package app\api\controller\login
 */
class Login
{
    /**
     * 用户登录
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function login()
    {
        //判断是否是船员
        $department = input('department');
        $idNumber = input('idNumber');
        if($department == "crew"){
            if($idNumber){
                $marinerInfo = Db::name('mariner')->where(['id_number'=>$idNumber])->find();
                if($marinerInfo['is_delete']) return error_data('用户被禁用,暂时不允许登录');
                if(!$marinerInfo || $marinerInfo['password'] != md5(input('password'))) return error_data("身份证或密码错误");
                cookie('user', $marinerInfo);
                session('id', $marinerInfo['id']);
                session('username', $marinerInfo['name']);
                session('is_mariner',true);
                return ok_data('登录成功');
            }else{
                return error_data('请输入身份证号码');
            }
        }
        //员工id和密码
        $userInfo = Db::name('user')->where(['id' => input('id')])->find();
        if(!$userInfo) return error_data('用户不存在');
        if ($userInfo['is_delete'] == 1) return error_data("用户被锁定,暂时不允许登录");
        if ($userInfo['password'] != md5(input('password'))) return error_data('密码错误');
        //用户没有绑定角色则不允登录
        $role = Db::name('role_user')
            ->alias('a')
            ->join('role b','a.role_id=b.id','LEFT')
            ->where(['user_id'=>$userInfo['id'],'b.status'=>1])
            ->value('role_id');
        if(!$role) return error_data('您的账号没有绑定角色，请联系管理员');
        //更新最后登录ip
        Db::name('user')->where(['id' => input('id')])->update(['logintime' => formatTime(time()), 'loginip' => getIp()]);
        session('is_mariner',false);
        cookie('user', $userInfo);
        session('id', $userInfo['id']);
        session('username', $userInfo['username']);
        session('loginip', $userInfo['ip']);
        session('logintime', $userInfo['logintime']);
        return ok_data('登录成功');
    }

    /**
     * 修改密码
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function changePassword()
    {
        $password = input('password');
        $newPassword = input('newPassword');
        $qrPassword = input('qrPassword');
        if($newPassword != $qrPassword) return error_data("两次输入的密码不一致");
        if(session('is_mariner')){
            $rightPassword = Db::name('mariner')->where(['id'=>session('id')])->value('password');
            if(md5($password) != $rightPassword) return error_data('密码错误');
            $res = Db::name('mariner')->where(['id'=>session('id')])->update(['password'=>md5($newPassword)]);
        }else{
            $rightPassword = Db::name('user')->where(['id'=>session('id')])->value('password');
            if(md5($password) != $rightPassword) return error_data('密码错误');
            $res = Db::name('user')->where(['id'=>session('id')])->update(['password'=>md5($newPassword)]);
        }
        if($res) return ok_data('修改密码成功');
        return error_data('修改密码失败');
    }

    /**
     * 获取部门下所有的员工
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function staff()
    {
        $department = input('department');
        //获取部门下所有的员工
        if($department == "crew") exit();
        $staffInfo = Db::name('user')->field('id,username')->where(['department' => $department])->select();
        return json($staffInfo);
    }

    /**
     * 发送发送邮箱验证码
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function sendMessage()
    {
        $userInfo = Db::name('user')->where(['id' => input('id')])->find();
        $email = $userInfo['email'];
        $verify = mt_rand(100000,999999);
        Cache::set($userInfo['id'],$verify,300);
        $body = $userInfo['username'].":<br />&nbsp;&nbsp;<font size='3'>您好！<br />您正在修改鑫裕盛费用系统密码,以下是您修改密码的验证码(有效期5分钟),请及时修改！<br /><br />&nbsp;&nbsp;验证码:<b>$verify</b></font>";
        //实例化
        $mail = new PHPMailer();
        try {
            //邮件调试模式
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.126.com';
            $mail->CharSet = 'UTF-8';
            $mail->SMTPAuth = true;
            $mail->Username = 'huaixinguangzhi@126.com';
            $mail->Password = 'xydk2018';
            $mail->setFrom('huaixinguangzhi@126.com', '鑫裕盛费用管理系统系统管理员');
            //  添加收件人1
            $mail->addAddress($email, $userInfo['username']);
//            $mail->addReplyTo('fajian@aliyun.com', $adminInfo['username']);
            // 将电子邮件格式设置为HTML
            $mail->isHTML(true);
            $mail->Subject = '【鑫裕盛】请查收您的验证码';
            $mail->Body = $body;
            //$mail->AltBody = '这是非HTML邮件客户端的纯文本';
            $mail->send();
            return ok_data('发送成功');
        } catch (\Exception $e) {
            return ok_data("错误信息:$mail->ErrorInfo");
        }
    }

    /**
     * 核对验证码
     * @return \think\response\Json
     */
    public function checkVerity()
    {
        $id = input('id');
        $verity = input('verity');
        if($verity != Cache::get($id)) return error_data('验证码错误');
        return ok_data("验证成功");
    }

    /**
     * 忘记密码
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function forgetPassword()
    {
        $id = input('id');
        $password = input('password');
        $qrpassword = input('qrpassword');
        if($password != $qrpassword) return error_data('两次输入的密码不一致');
        $res = Db::name('user')->where(['id'=>$id])->update(['password'=>md5($password)]);
        Cache::pull($id);
        if($res) return ok_data('密码重置成功');
        return error_data('密码重置失败');
    }

    /**
     * 所有的部门
     * @return \think\response\Json
     */
    public function department()
    {
        $info = Db::name('user')->column('department');
        if(empty($info)) return error_data("暂无数据");
        $info = array_values(array_unique($info));
        array_push($info,'crew');
        $res = [
            'data'=>$info
        ];
        return json($res);
    }
}