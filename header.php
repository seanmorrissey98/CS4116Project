<?php

function logout() {
    $_SESSION = array();
    session_destroy();
    header("Location: index.html");
    exit;
}

if (isset($_GET["logout"])) {
    logout();
}

$header = '<nav class="navbar navbar-light navbar-expand-md navigation-clean" id="discover-navbar">
    <div class="container-fluid">
        <button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon" style="opacity: 1;filter: brightness(200%) hue-rotate(0deg) invert(100%);"></span></button>
        <div class="collapse navbar-collapse" id="navcol-1">
            <ul class="nav navbar-nav ml-auto d-flex justify-content-between" id="discover-nav" style="padding: 0 80px;">
                <li class="nav-item" role="presentation" id="messaging-link">
                    <a class="nav-link" id="messaging-nav" href="messaging.php" style="color: #ffffff;">Messages</a>
                </li>
                <li class="nav-item" role="presentation" id="discover-link-1"><a class="nav-link" id="discover-nav-1" href="discover.php" style="color: #ffffff;">Discover</a>
                </li>
                <li class="nav-item" role="presentation"><a class="nav-link" href="accountInfo.php" style="color: #ffffff;">Profile</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="nav-item" role="presentation"><a id="advanced-search" class="nav-link" href="#" style="color: #ffffff;"> <i class="fas fa-search"></i></a></li>
                <li class="nav-item" role="presentation"><a id="hide-advanced-search" class="nav-link" href="#" style="color: #ffffff; display: none;"> <i class="fas fa-search-minus"></i></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="nav-item" role="presentation"><a id="nav-logout" class="nav-link" href="?logout=true" style="color: #ffffff;"> <i class="fa fa-sign-out"></i></a></li></ul>
        </div>
    </div>
</nav>';

echo $header;