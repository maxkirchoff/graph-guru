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
                    var endpoint = '<?php echo $route_parts[0]; ?>';
                    switch (endpoint) {
                        case 'posts':
                            $("#analysis").addClass("active");
                            $('#backToPageSelect').show();
                            var page_id = '<?php echo isset($route_parts[1]) ? $route_parts[1] : 0; ?>';
                            $('#loadingText').empty().append("Receiving post data from Facebook...");
                            $.post('/api/v1/posts', {"access_token": access_token, "page_id": page_id}, function(data) {
                                pageData = data;
                                render_posts(pageData)
                            }, 'json');
                            break;
                        case 'pages':
                            $("#analysis").addClass("active");
                            $('#loadingText').empty().append("Receiving page data from Facebook...");
                            $.post('/api/v1/pages', {"access_token": access_token}, function(data) { render_pages(data)}, 'json');
                            break;
                        case 'guide':
                            $("#guide").addClass("active");
                            $('#loadingText').empty().append("Parsing insights sample data...");
                            $.getJSON('/fixture/insight_sample_with_notes.json?123', function(data) { render_guide(data) });
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
    <script src="/js/app.js?987"></script>
    <script src="/js/datasource.js?987"></script>
    <script src="/js/loader.min.js?987" type="text/javascript"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.4.4/underscore-min.js?987" type="text/javascript"></script>
    </body>
</html>