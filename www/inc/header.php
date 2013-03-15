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
        <div class="span10 offset1">
            <a href="/" class="btn btn-small btn-primary btn-fixed-width" style="margin-bottom:10px;" id="analysis">Insights Analysis</a>
            <a href="/guide" class="btn btn-small btn-primary btn-fixed-width" style="margin-bottom:10px;" id="guide">Insights Reference Guide</a>
            <a href="/" class="btn btn-small btn-primary btn-fixed-width" style="margin-bottom:10px;display:none;float:right;" id="backToPageSelect"><< Back To Page Selection</a>
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
