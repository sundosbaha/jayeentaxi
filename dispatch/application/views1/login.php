<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $base; ?>assets/css/login.css"/>
<script type="text/javascript" src="<?php echo $base; ?>assets/js/login.js"></script>
</head>

<body style="background:url('<?php echo $base; ?>assets/images/bg.jpg');">
<div class="container">
  
  <div class="row" id="pwd-container">
    <div class="col-md-4"></div>
    
    <div class="col-md-4">
      <section class="login-form">
        <form method="post" action="#" role="login">
        <!--  <img src="http://i.imgur.com/RcmcLv4.png" class="img-responsive" alt="" />-->
        <h3 class="text-center" style="color:#e07a16;">Dispatcher</h3>
          <input type="text"  placeholder="UserName" name="username" required class="form-control input-lg" value="" />
          
          <input type="password" class="form-control input-lg" id="password" name="password" value="" placeholder="Password" required="" />
          
          <select class="form-control input-lg" name="type" required>
          <option name="admin">admin</option>
          <option name="staff">staff</option>
          
          </select>
          
          
          <div class="pwstrength_viewport_progress"></div>
          
          
          <button type="submit" name="go" class="btn btn-lg btn-primary btn-block" style="background:#e07a16; border-color: #e0711c;color: #fff;">Sign in</button>
          <div>
           <!-- <a href="#">Create account</a> or <a href="#">reset password</a>-->
          </div>
          
        </form>
      </section>  
      </div>
      
      <div class="col-md-4"></div>
      

  </div>
  
  
  
  
</div>
</body>
</html>