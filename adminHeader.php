<?php

function logout() {
    $_SESSION = array();
    session_destroy();
    setcookie("email","",time()-3600);
    setcookie("password","",time()-3600);
    header("Location: index.html");
    exit;
}

if (isset($_GET["logout"])) {
    logout();
}

if (isset($_SESSION["adminLoggedIn"]) && $_SESSION["adminLoggedIn"] == true) {
    $topText = "Limerick Lovers ADMIN";
    $linkText = "adminDashboard.php";
    $profileText = "";
    $profileLink="";
    $msgText="";
    $msgLink="";
    $navTypeMiddle="navbar-brand";
}

$header = '<nav class="navbar navbar-light navbar-expand-md navigation-clean-search">
<div class="container-fluid"><a class="navbar-brand" href="adminDashboard.php">Limerick Lovers ADMIN</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse"
         id="navcol-1">
        <ul class="nav navbar-nav">
            <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#">Admin Data </a>
                <div class="dropdown-menu" role="menu">
                    <a class="dropdown-item" role="presentation" href="userList.php">Users</a>
                    <a class="dropdown-item" role="presentation" href="bannedUserList.php">Banned Users</a>
                    <a class="dropdown-item" role="presentation" href="reportedList.php">Reports</a>
            </div>
            </li>
        </ul>
        <form class="form-inline mr-auto" target="_self">
        </form><span class="nav navbar-nav navbar-right">
            <li class="nav-item" role="presentation"><a id="nav-logout" class="nav-link" href="?logout=true" style="color: #ffffff;"> <i class="fa fa-sign-out"></i></a></li></span>
    </span></div>
</div>
</nav>';

echo $header;