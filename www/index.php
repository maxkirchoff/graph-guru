<?php
// necessary requirements to get config
$src_dir = realpath(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src');
require $src_dir . DIRECTORY_SEPARATOR . "config.php";
use GraphGuru\Config;
$config = Config::get_config();

// do route checking and var assignment up here
if (isset($_GET['route']))
{
    // route is considered anything left of a slash
    $route = strstr($_GET['route'], '/', true);
    // page id is anything right of a slash....then all slashes striped.
    // hacky but works for this
    $page_id = trim(strstr($_GET['route'], '/'), '/') ?: '';
}
?>
<!DOCTYPE html>
<html lang="en" class="fuelux">
    <head>
        <title>Graph Guru v1</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap -->
        <link rel="stylesheet" type="text/css" href="/css/app.css">
        <link href="/css/fuelux.css" rel="stylesheet" />
        <link href="/css/fuelux-responsive.css" rel="stylesheet" />
        <script type="text/javascript">
            var fb_app_id = '<?php echo $config['facebook']['app.id']; ?>';
        </script>
    </head>
    <body>
    <div id="headerWrapper" class="row">
        <div class="span3 offset1">
            <div id="branding">
                <h1 id="blogTitle">
                    <span><a href="https://www.shopigniter.com/" title="ShopIgniter" rel="home">ShopIgniter</a></span>
                </h1>
            </div>
        </div>
        <div class="span5 offset2" style="text-align: right">
            <h3 style="margin-top:20px; margin-right: 20px;color:#F36B22">Graph Guru App</h3>
        </div>
    </div>
    <div class="row">
        <div id="viewLoader" class="span10 offset1">
            <div class="progress progress-striped active" style="width:50%;margin:100px auto 0;">
                <div class="bar" style="width:100%;"></div>
            </div>
            <div style="text-align:center;"><h3>This may take a minute. Loading...</h3></div>
        </div>
    </div>
    <?php
        if (isset($route) && $route == 'posts')
        {
    ?>
    <div id="postsView" style="display:none;">
        <div class="row">
            <a href="/" class="btn btn-large btn-primary btn-fixed-width offset1" style="margin-bottom:10px;"><< Back To Page Selection</a>
        </div>
        <div class="row">
            <div id="postsViewHeader" class="span10 offset1">
                <div>
                    <h1>Overview by Post Type</h1>
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <td><strong>Post Type</strong></td>
                                <td>Photo</td>
                                <td>Video</td>
                                <td>Link</td>
                                <td>Status</td>
                            </tr>
                            <tr>
                                <td><strong>Average Score</strong></td>
                                <td class="photoScore"></td>
                                <td class="videoScore"></td>
                                <td class="linkScore"></td>
                                <td class="statusScore"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="posts" class="span10 offset1">
                <div id="postsWrapper">
                    <h1>Post Metrics</h1>
                    <div>
                        <table id="postGrid" class="table table-bordered datagrid">
                            <thead>
                            <tr>
                                <th>
                                    <span class="datagrid-header-title"><strong>Facebook Posts</strong></span>
                                    <div class="datagrid-header-right">
                                        <div class="select filter" data-resize="auto">
                                            <button data-toggle="dropdown" class="btn dropdown-toggle">
                                                <span class="dropdown-label"></span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li data-value="all" data-selected="true"><a href="#">All</a></li>
                                                <li data-value="photo"><a href="#">Photo Posts</a></li>
                                                <li data-value="link"><a href="#">Link Posts</a></li>
                                                <li data-value="video"><a href="#">Video Posts</a></li>
                                                <li data-value="status"><a href="#">Status Posts</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>
                                    <div class="datagrid-footer-left" style="display:none;">
                                        <div class="grid-controls">
                                                            <span>
                                                                <span class="grid-start"></span> -
                                                                <span class="grid-end"></span> of
                                                                <span class="grid-count"></span>
                                                            </span>
                                            <div class="select grid-pagesize" data-resize="auto">
                                                <button data-toggle="dropdown" class="btn dropdown-toggle">
                                                    <span class="dropdown-label"></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li data-value="5"><a href="#">5</a></li>
                                                    <li data-value="10" data-selected="true"><a href="#">10</a></li>
                                                    <li data-value="20"><a href="#">20</a></li>
                                                    <li data-value="50"><a href="#">50</a></li>
                                                    <li data-value="100"><a href="#">100</a></li>
                                                </ul>
                                            </div>
                                            <span>Per Page</span>
                                        </div>
                                    </div>
                                    <div class="datagrid-footer-right" style="display:none;">
                                        <div class="grid-pager">
                                            <button class="btn grid-prevpage"><i class="icon-chevron-left"></i></button>
                                            <span>Page</span>

                                            <div class="input-append dropdown combobox">
                                                <input class="span1" type="text">
                                                <button class="btn" data-toggle="dropdown"><i class="caret"></i></button>
                                                <ul class="dropdown-menu"></ul>
                                            </div>
                                            <span>of <span class="grid-pages"></span></span>
                                            <button class="btn grid-nextpage"><i class="icon-chevron-right"></i></button>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } else { ?>
    <div class="row">
        <div id="pageView" class="span10 offset1" style="display:none;">
            <div>
                <h1>Select a Facebook Busines Page</h1>
                <div id="pageList" style="margin-top:30px;"></div>
             </div>
        </div>
    </div>
    <?php } ?>
    <div class="row" id="fbLogin" style="display:none;">
        <div class="span10 offset1" id="fbLoginWrapper" style="text-align:center;">
            <div style="margin:10px;">
                <fb:login-button v="2" size="xlarge" onlogin="onFacebookConnect();" scope="manage_pages,read_insights">
                    Authorize Facebook App
                </fb:login-button>
                <h2>Authorize to view your Post Ratings</h2>
                <h4>This app will only read insights. It will not create or edit posts.</h4>
            </div>
        </div>
    </div>
    <div id="fb-root"></div>

    <div id="error"></div>
    <script type="text/javascript">
        window.fbAsyncInit = function() {
            // init the FB JS SDK
            FB.init({
                appId      : fb_app_id, // App ID from the App Dashboard
                status     : true, // check the login status upon init?
                cookie     : true, // set sessions cookies to allow your server to access the session?
                xfbml      : true  // parse XFBML tags on this page?
            });

            FB.getLoginStatus(function(response) {
                if (response.status === 'connected') {
                    $("#fbLogin").hide();
                    $("#viewLoader").show();
                    var access_token =   response.authResponse.accessToken;
                    var endpoint = '<?php echo isset($route) && $route == 'posts' ? $route : 'pages'; ?>';
                    switch (endpoint) {
                        case 'posts':
                            var page_id = '<?php echo (isset($page_id) && $page_id) ? $page_id : ''; ?>';
                            $.post('/api/v1/posts', {"access_token": access_token, "page_id": page_id}, function(data) { render_posts(data)}, 'json');
                            break;
                        case 'pages':
                            $.post('/api/v1/pages', {"access_token": access_token}, function(data) { render_pages(data)}, 'json');
                            break;
                    }
                } else {
                    $("#viewLoader").hide();
                    $("#fbLogin").show();
                }
            });
        };

        // Load the SDK's source Asynchronously
        // Note that the debug version is being actively developed and might
        // contain some type checks that are overly strict.
        // Please report such bugs using the bugs tool.
        (function(d, debug){
            var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement('script'); js.id = id; js.async = true;
            js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";
            ref.parentNode.insertBefore(js, ref);
        }(document, /*debug*/ false));
    </script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="/js/app.js?123"></script>
    <script src="/js/datasource.js?123"></script>
    <script src="/js/loader.min.js" type="text/javascript"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.4.4/underscore-min.js" type="text/javascript"></script>
    </body>
</html>