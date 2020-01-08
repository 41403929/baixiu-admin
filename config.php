<?php
session_start();
//引入数据文件
require_once dirname(__FILE__).'/../config.php'; //引入配置信息

require_once dirname(__FILE__).'/../functions.php'; //引入函数文件


//验证信息
function login(){
  if (empty($_POST['email'])) {
    $GLOBALS['error'] = '请输入邮箱！';
    return;
  }

  if (empty($_POST['password'])) {
    $GLOBALS['error'] = '请输入密码！';
    return;
  }
  
  $result=getSQLMessage_one("select * from users where email='{$_POST['email']}' limit 1;");

  if (!$result){
   $GLOBALS['error'] = '邮箱不存在！';
    return;
  }

  if ($result['password']!=$_POST['password']) {
    $GLOBALS['error'] = '密码错误！';
    return;
  }

  $_SESSION['user']=$result;

  header('location:/admin/');
}
//================main line==============

if($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (!empty($_GET['action']) && $_GET['action']=='logout'){
    unset($_SESSION['user']);
  }
}

if (isset($_SESSION['user'])) {
  echo "<h1> 您已经登录了！</h1>";
  sleep(3);
  header('location:/admin/');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  login();
};
    


 ?>

<!DOCTYPE html>          
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <link rel="stylesheet" href="/static/assets/vendors/animate/animate.css">
</head>
<body>
  <div class="login">
    <form class="login-wrap<?php echo isset($GLOBALS['error'])?' shake animated':'';?>" action="<?php $_SERVER['PHP_SELF'] ?>" method='post' novalidate autocomplete='off'> 
      <img class="avatar" src="/static/assets/img/default.png">
       <?php if (isset($GLOBALS['error'])):?>
            <div class="alert alert-danger">
             <?php echo $GLOBALS['error']?>
            </div> 
      <?php endif ?>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" name="email" type="email" class="form-control" placeholder="邮箱" autofocus value="<?php echo isset($_POST['email']) ? $_POST['email']:'' ?>">
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" name="password" type="password" class="form-control" placeholder="密码">
      </div>
      <button class="btn btn-primary btn-block">登 录</button>
    </form>
  </div>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>


  <script>//失去焦点获取头像
    $(function(){
      $('#email').on('blur',function(){
        var regexp=/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/
        var value=this.value
        if(regexp.test(value)){
          $.get('api/avatar.php?email='+ value,function(res){
            if (res) {
              var $avatar=$('.avatar')
              $avatar.fadeOut(function() {
                $avatar.on('load',function(){
                  $(this).fadeIn()
                }).attr('src',res)
             })
            }
          })
        }
      })
    }) 
  </script>
</body>
</html>
