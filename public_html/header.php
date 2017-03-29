
<script type="text/javascript">

    function change_title() {
        var title = $(".dashboards_main_tabs li.active").find('small').text();
//        console.log("title", title);
        $('title').html(title);
    }
</script>
<header id="header" class="header">
    <div class="container">
        <div class="row">
            <div class="lg-col-12 col-md-12 col-sm-12 col-xs-12">
                <div class="logo"><a href="/">PAP Observatory Interface</a></div>
                <?php if (!isset($_SESSION['uid']) && !isset($_SESSION['pwd'])) { ?>
                    <div class="user-link">
                        <ul>
                            <li><a href="#" data-toggle="modal" data-target="#signin-modal">Sign In</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#signup-modal">sign Up</a></li>
                        </ul>
                    </div>
                    <?php
                } else {
                    include_once($_SERVER['DOCUMENT_ROOT'] . '/../server/user_actions.php');
                    $userinfo = get_user_info();
//                            loggedin_menu($userinfo['username'], $userinfo['picurl']);
                    include_once($_SERVER['DOCUMENT_ROOT'] . '/../server/db_utils.php');
                    ?>
                    <div class="user-dropdown dropdown">
                        <a id="user-dropdown1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <span class="user-pic">
                                <span class="online"></span>
                                <img src="<?php echo $userinfo['picurl'] ?>" alt="" />
                            </span>
                            <small><?php echo $userinfo['username']; ?></small>
                            <span class="caret"></span>
                        </a>	
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <?php if ($userinfo['role'] == 'admin' || $userinfo['role'] == 'premium') { ?>
                                <li><a href="/commands">My Account</a></li>
                            <?php } ?>
                            <li><a data-toggle="modal" data-target="#modal-user-profile">Profile</a></li>
                            <!--<li><a href="javascript:void(0)" onclick="tabManage('user_tab:updateprofile')" id="profile_tab">Profile</a></li>-->
                            <li><a id="logout_btn" href="/auth/logout.php">Logout</a></li>
                        </ul>
                    </div>
                <?php } ?>
                <div class="main-nav">
                    <nav class="navbar navbar-default">
                        <div class="container-fluid">
                            <!--Brand and toggle get grouped for better mobile display--> 
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a class="navbar-brand" href="#">Brand</a>
                            </div>

                            <!--Collect the nav links, forms, and other content for toggling--> 
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                <ul class="nav navbar-nav">
                                    <li><a href="/">Home</a></li>
                                    <li><a href="/operations" id="sbd_visualization">Logs</a></li>
                                    <?php if (isset($_SESSION['uid']) && isset($_SESSION['pwd'])) { ?>
                                    <li><a href="/commands" id="sbd_visualization">Commands</a></li>
                                    <?php } ?>
                                </ul>
                            </div> <!--/.navbar-collapse -->
                        </div> <!-- /.container-fluid -->
                    </nav>
                </div>	

            </div>
        </div>
    </div>
</header>