<?php
//error_reporting(E_ALL);
error_reporting(0);
session_start('mineservers');
if (!isset($_SESSION["username"])) {
    $_SESSION["id"] = "0";
    $_SESSION["username"] = "Gast";
}
if (isset($_COOKIE["StayLoggedIn"]) && !empty($_COOKIE["StayLoggedIn"]) && $_SESSION["id"] == "0") {
    $hash = $db->real_escape_string($_COOKIE["StayLoggedIn"]);
    $stayliq = $db->query("SELECT * FROM `sl_sessions` WHERE `hash` = '" . $hash . "'");
    if ($stayliq->num_rows == 1) {
        $cookiedata = $stayliq->fetch_object();

        $_SESSION["id"] = $cookiedata->id;
        $_SESSION["username"] = $cookiedata->username;
        $_SESSION["mail"] = $cookiedata->mail;
    } else {
        setcookie("StayLoggedIn", "", time() - 7200);
        $db->query("DELETE FROM `sl_sessions` WHERE `hash` = '" . $hash . "'");
    }
}
require_once 'includes/class.phpmailer.php';
$mailer = new PHPMailer;
require_once('includes/recaptchalib.php');
$cfgkey = "MineServers.EuisteinfachHamma";
require_once("config.php");
require_once("includes/names.inc.php");
require_once("includes/functions.php");
$site = (empty($_GET['site'])) ? "start" : $db->real_escape_string($_GET['site']);
if (!empty($_GET['id']) && !is_id($_GET['id']) or !empty($_GET['page']) && !is_id($_GET['page'])) {
    header("HTTP/1.0 404 Not Found");
    exit();
}
?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <?php 
        if ($site == "serverview") {
            $titleq = $db->query("SELECT servername FROM sl_servers WHERE id = " . $_GET['id']);
            $title = $titleq->fetch_object();
            echo "<title>MineServers - " . $title->servername . "</title>";
        } elseif ($site == "vote") {
            $titleq = $db->query("SELECT servername FROM sl_servers WHERE id = " . $_GET['id']);
            $title = $titleq->fetch_object();
            echo "<title>MineServers - Voten für " . $title->servername . "</title>";
        } else {
            echo "<title>MineServers - Deutsche Minecraft Server Liste</title>";
        }
        ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Wir bieten dir eine umfangreiche Serverliste mit vielen Servern, in der du sicherlich deinen Traumserver findest.">
        <meta name="keywords" content="Minecraft, Server, Liste, List, German, Deutsch, Cracked, Premium, 1.6.4, Version, Crack, Download, suchen, finden, smpminecraft, minecraft servers, minecraft server list, server, servers, server list, status, uptime, votes, scores, rank">
        <meta name="author" content="MineServers.eu">
        <meta name="robots" content="index, follow">
        <meta property="og:image" content="assets/img/fbthumb.png">

        <!-- Le styles -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/css/bootstrap-responsive.min.css" rel="stylesheet">
        <link href="assets/css/font-awesome.min.css" rel="stylesheet">
        <link href="assets/css/style.css" rel="stylesheet">

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Le JavaScipt -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            (function(i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function() {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-43253923-1', 'mineservers.eu');
            ga('send', 'pageview');

            window.___gcfg = {lang: 'de'};
        </script>

        <?php 
        if (in_array($_GET["admin"], $ckesites) or in_array($site, $ckesites)) {
            echo '<script type="text/javascript" src="assets/js/ckeditor/ckeditor.js"></script>';
        }
        ?>

        <script type="text/javascript">
            var RecaptchaOptions = {
                theme: 'clean',
                lang: 'de'
            };

            WebFontConfig = {
                google: {families: ['Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800:cyrillic-ext,latin']}
            };
            (function() {
                var wf = document.createElement('script');
                wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
                        '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
                wf.type = 'text/javascript';
                wf.async = 'true';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(wf, s);
            })();
        </script>
    </head>
    <body>
        <div class="header">
            <div class="navbar-wrapper">
                <div class="container">
                    <div class="navbar">
                        <div class="navbar-inner" style="margin-top: 10px;">
                            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a href="index.php" style="margin-left:0px;" class="brand">MineServers.eu</a>
                            <div class="nav-collapse collapse">
                                <ul class="nav">
                                    <?php
                                    foreach ($mainmenu as $menuitem) {
                                        echo ($menuitem["site"] == $site) ? '<li class="active">' : '<li>';

                                        echo '<a href="' . $menuitem["link"] . '" title="' . $menuitem["name"] . '">' . $menuitem["name"] . '</a>';
                                        echo "</li>";
                                    }
                                    ?>
                                </ul>
                                <ul class="nav pull-right">
                                    <li><form class="navbar-search pull-right" method="get" action="index.php"><input type="hidden" value="search" name="site"><input type="text" class="search-query" name="q" style="width: 140px;" placeholder="Suche..."></form></li>
                                    <?php if ($_SESSION['id'] == "0") { ?>
                                        <li class="divider-vertical"></li>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img style="border: 1px white solid" class="gravatar" src="images/avatar.php?name=char&size=24" alt="Gast"> Nicht eingeloggt <b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <li style="margin:8px;">
                                                    <form action="index.php?method=login" method="POST">
                                                        <div class="input-prepend">
                                                            <span class="add-on"><i class="icon-user"></i></span><input name="username" type="text" placeholder="Benutzername">
                                                        </div>
                                                        <br>
                                                        <div class="input-prepend">
                                                            <span class="add-on"><i class="icon-lock"></i></span><input name="password" type="password" placeholder="Passwort">
                                                        </div>
                                                        <br>
                                                        <input type="submit" class="btn btn-success" value="Login"> <a href="index.php?site=register" class="btn btn-info">Registrieren</a>
                                                    </form>
                                                </li>
                                                <li class="divider"></li>
                                                <li><a href="index.php?site=forgotpassword">Passwort vergessen?</a></li>
                                            </ul>
                                        </li> 
                                    <?php } else { ?>
                                        <li class="divider-vertical"></li>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img class="gravatar" src="images/avatar.php?name=<?php echo $_SESSION["username"] ?>&size=24"> Willkommen, <?php echo $_SESSION['username'] ?>! <b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="index.php?site=myservers">Meine Server</a></li>
                                                <li><a href="index.php?site=mysettings">Einstellungen</a></li>
                                                <?php 
                                                if (is_mod($_SESSION['username'])) {
                                                    echo '<li class="divider"></li>';
                                                    echo '<li><a href="index.php?admin=servermanager">Server Manager</a></li>';
                                                    echo '<li><a href="index.php?admin=usermanager">User Manager</a></li>';
                                                    echo '<li><a href="index.php?admin=newsletter">Newsletter</a></li>';
                                                    echo '<li><a href="index.php?admin=commentsmanager">Kommentare</a></li>';
                                                    echo '<li><a href="index.php?admin=donators">Spender</a></li>';
                                                    echo '<li><a href="index.php?admin=stats">Statistiken</a></li>';
                                                }
                                                ?>
                                                <li class="divider"></li>
                                                <li><a href="index.php?method=logout">Logout</a></li>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($site == "start" && empty($_GET["method"])) { ?>
                <div class="container">
                    <div class="header-text">
                        <h1>Willkommen auf MineServers.eu</h1>
                        <p>Du suchst einen Server für dich und deine Freunde? Oder willst du neue Minecraft Kumpels finden? Oder willst du einfach nur andere Spieler verhauen?</p>
                        <p><strong>Hier bist du genau richtig!</strong> Wir bieten dir eine umfangreiche <a href="index.php?site=serverlist">Serverliste</a>, in der du sicherlich deinen Traumserver findest. Um die Suche nach diesem einen perfekten Server zu erleichtern, müssen Serverbesitzer genaue Angaben zu Spielgeschehen, Spieltyp, Zusatzfeatures usw. machen.</p>
                        <div class="socials"><div class="gplusonebtn"><div class="g-plusone" data-size="medium" data-href="http://www.mineservers.eu"></div></div><a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.mineservers.eu" data-text="MineServers.eu Die beste Minecraft Serverliste" data-lang="de" data-hashtags="mcserverseu">Tweet</a><script>!function(d, s, id) {
                                var js, fjs = d.getElementsByTagName(s)[0];
                                if (!d.getElementById(id)) {
                                    js = d.createElement(s);
                                    js.id = id;
                                    js.src = "//platform.twitter.com/widgets.js";
                                    fjs.parentNode.insertBefore(js, fjs);
                                }
                            }(document, "script", "twitter-wjs");</script><div class="fblikebtn"><iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2FMcServers.eu&amp;width=110&amp;height=21&amp;colorscheme=light&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;send=false&amp;appId=322669341196136" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:110px; height:21px;" allowTransparency="true"></iframe></div></div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="container">
            <div class="row-fluid">
                <div class="span9">
                    <?php
                    if (isset($_GET['method']) && file_exists('routines/web.' . $_GET["method"] . '.php')) {
                        include('routines/web.' . $_GET["method"] . '.php');
                    } elseif (is_mod($_SESSION['username']) && file_exists('sites/admin/admin.' . $_GET['admin'] . '.php')) {
                        include 'sites/admin/admin.' . $_GET['admin'] . '.php';
                    } elseif (is_mod($_SESSION['username']) && file_exists('routines/admin/admin.' . $_GET['amethod'] . '.php')) {
                        include 'routines/admin/admin.' . $_GET['amethod'] . '.php';
                    } elseif (file_exists('sites/' . $site . '.php')) {
                        include 'sites/' . $site . '.php';
                    } else {
                        include 'sites/404.php';
                    }
                    ?>
                </div>
                <div class="span3">
                    <?php include("includes/sidebar.php") ?>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <div class="row row-fluid">
                    <div class="span6">
                        <p><small>&copy; 2012-2013 by MineServers.eu - All rights reserved</small><br>Designed and built with all the love in the world by <a href="http://twitter.com/cruzer0" target="_blank">@cruzer0</a>.</p>
                        <ul class="footer-links">
                            <li><a href="index.php?site=regeln">Regeln</a></li>
                            <li class="muted">&middot;</li>
                            <li><a href="index.php?site=impressum">Impressum</a></li>
                            <li class="muted">&middot;</li>
                            <li><a href="index.php?site=nutzungsbestimmungen">ANB</a></li>
                        </ul>
                    </div>
                    <div class="span6">
                        <p class="text-right">
                            <a href="http://www.fsit.com" target="_blank"><img width="139" src="http://fsit.ch/banner/FSIT-Logo-300px.png"></a>
                        </p>
                    </div>
                </div>
            </div>
        </footer>
        <script type="text/javascript">
            (function() {
                var po = document.createElement('script');
                po.type = 'text/javascript';
                po.async = true;
                po.src = 'https://apis.google.com/js/plusone.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(po, s);
            })();

            $('.gametypes').tooltip({selector: "a[rel=tooltip]"});
            $('.features').tooltip({selector: "div[rel=tooltip]"});
            $('.players').tooltip({selector: "a[rel=tooltip]"});
            $('.uptime').tooltip({selector: "div[rel=tooltip]"});
        </script>
        <noscript><p><img src="http://analytics.postiglione.at/piwik.php?idsite=1" style="border:0" alt="" /></p></noscript>
    </body>
</html>