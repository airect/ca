<?php

/**
 *  注册
 */
error_reporting(0);
if(isset($_POST['email']) && isset($_POST['passwd']) && isset($_POST['username'])){
    if($_POST['email'] =='' || $_POST['passwd'] == '' || $_POST['username'] ==''){
        $str=<<<EOT
            <script>
            
            if($("input[name='reg_uname']").val()==""){
                $("input[name='reg_uname']").focus();
            }else if($("input[name='reg_email']").val()==""){
                $("input[name='reg_email']").focus();
                }else{
                    $("input[name='reg_pwd']").focus();
                }
            $("#r").text("请完整填写信息！");
            
            
            </script>
EOT;
        exit($str);
    }elseif(preg_match("/^(\w)+(\.\w+)*@(\w)+((\.\w{2,3}){1,3})$/",$_POST['email'])){
        session_start();
        }else{
            exit("$<script>$('#r').text('您填写的邮箱格式不正确！');</script>");
        }
    
    //检测SQL注入
    include("function.php");
    if(check_sql_inject()){
        exit("<script>alert('非法字符！');location.href='index.php';</script>");
    }
    
    $username=$_POST['username'];
    $passwd=md5($_POST['passwd']);
    $email=$_POST['email'];
    
    //检测邮箱是否已存在
    require_once('ca_dbconn.php');
    $query="select `uid` from `user` where email='{$email}'";
    if($mysqli->query($query)){
        if($mysqli->affected_rows>0){
            die("$<script>$('#r').text('邮箱已存在');</script>");
        }
    }else{
        die("error".$mysqli->errno);
    }
    
    $query="insert into `user` (username,passwd,email,reg_time) values('$username','$passwd','$email',now())";
    if($mysqli->query($query)){
        
        $query="select `uid` from `user` where username='$username' and passwd='$passwd'";
        if($result=$mysqli->query($query)){
            
        }else{
            echo "SQL error". $mysqli->errno;
        }
        $row=$result->fetch_row();
        $_SESSION['uid']=$row[0];
        echo "location.href='view.php';</script>";
        
        
        
    }else{
        echo 'faild to insert';
    }
    
}else{
    header("location:index.php");
}




?>