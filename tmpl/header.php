<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $meta['title'] ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="/src/AdminLTE/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/src/AdminLTE/dist/css/AdminLTE.min.css">    
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/src/AdminLTE/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="/src/main.css">
    
    <!-- jQuery 2.1.4 -->
    <script src="/src/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="/src/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="/src/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="/src/AdminLTE/plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/src/AdminLTE/dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="/src/AdminLTE/dist/js/demo.js"></script>
    <script src="/src/main.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <!-- ADD THE CLASS layout-boxed TO GET A BOXED LAYOUT -->
  <body class="hold-transition skin-blue layout-boxed sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
        <a href="/" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini">C<b>CP</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg">Cent<b>CP</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
            <?php /*
              <!-- Messages: style can be found in dropdown.less-->
              <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-envelope-o"></i>
                  <span class="label label-success">4</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 4 messages</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li><!-- start message -->
                        <a href="#">
                          <div class="pull-left">
                            <img src="/src/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            Support Team
                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li><!-- end message -->
                    </ul>
                  </li>
                  <li class="footer"><a href="#">See All Messages</a></li>
                </ul>
              </li>*/ ?>
              <!-- Notifications: style can be found in dropdown.less -->
              <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-warning"><?php echo count($notifications); ?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">Last <?php echo count($notifications); ?> log records</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <?php foreach($notifications as $notification) { ?>
                      <li><!-- start message -->
                        <a href="#">
                          <div class="pull-left">
                            <i class="fa fa-user"></i>
                          </div>
                          <h4>
                            <?php echo $notification['log_user']?>
                            <small><i class="fa fa-clock-o"></i> <?php echo $notification['log_date']?></small>
                          </h4>
                          <p><b><?php echo ucfirst($notification['log_module'])?>:</b> <?php echo $notification['log_action']?> <?php echo $notification['log_target']?> (id: <?php echo $notification['log_target_id']?>)</p>
                        </a>
                      </li><!-- end message -->
                      <?php } ?>
                    </ul>
                  </li>
                  <li class="footer"><a href="/log/">View all</a></li>
                </ul>
              </li>
              <?php /*
              <!-- Tasks: style can be found in dropdown.less -->
              <li class="dropdown tasks-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-flag-o"></i>
                  <span class="label label-danger">9</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 9 tasks</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li><!-- Task item -->
                        <a href="#">
                          <h3>
                            Design some buttons
                            <small class="pull-right">20%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">20% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li><!-- end task item -->
                    </ul>
                  </li>
                  <li class="footer">
                    <a href="#">View all tasks</a>
                  </li>
                </ul>
              </li>
              */ ?>
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-user"></i>
                  <!--<img src="/src/AdminLTE/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">-->
                  <span class="hidden-xs"><?php echo $user['user_login']?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <!--<img src="/src/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">-->
                    <i class="fa fa-user" style="color: white; font-size:48px;"></i>
                    <p>
                      <?php echo $user['user_login']?>
                      <!--<small>Member since Nov. 2012</small>-->
                    </p>
                  </li>
                  <!-- Menu Body -->
                  <!--<li class="user-body">
                    <div class="col-xs-4 text-center">
                      <a href="#">Followers</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#">Sales</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#">Friends</a>
                    </div>
                  </li>-->
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="/user/edit/<?php echo $user['user_id']?>" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="/login/logout" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              <li>
                <a href="/settings/"><i class="fa fa-gears"></i></a>
              </li>
            </ul>
          </div>
        </nav>
      </header>

      <!-- =============================================== -->

      <!-- Left side column. contains the sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- search form -->
          <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li <?php echo $menu == '/' ? 'class="active"' : ''?>>
              <a href="/">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span> <i class="fa fa-angle-right pull-right"></i>
              </a>
            </li>
            <li <?php echo $menu == 'site' ? 'class="active"' : ''?>>
              <a href="/site/">
                <i class="fa fa-globe"></i> <span>Sites</span> <i class="fa fa-angle-right pull-right"></i>
              </a>
            </li>
            <?php if ($user['user_role'] == 'admin') { ?>
            <li <?php echo $menu == 'ip' ? 'class="active"' : ''?>>
              <a href="/ip/">
                <i class="fa fa-sitemap"></i> <span>IP</span> <i class="fa fa-angle-right pull-right"></i>
              </a>
            </li>
            <?php } ?>
            <li <?php echo $menu == 'database' ? 'class="active"' : ''?>>
              <a href="/mysql/">
                <i class="fa fa-database"></i> <span>MySql</span> <i class="fa fa-angle-right pull-right"></i>
              </a>
            </li>
            <?php if ($user['user_role'] == 'admin') { ?>
            <li <?php echo $menu == 'user' ? 'class="active"' : ''?>>
              <a href="/user/">
                <i class="fa fa-users"></i> <span>Users</span> <i class="fa fa-angle-right pull-right"></i>
              </a>
            </li>
            <?php } ?>
            <li <?php echo $menu == 'cron' ? 'class="active"' : ''?>>
              <a href="/cron/">
                <i class="fa fa-clock-o"></i> <span>Cron</span> <i class="fa fa-angle-right pull-right"></i>
              </a>
            </li>
            
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- =============================================== -->
