var postList = {};
var pageStuff = '';

function render_pages(page_data)
{
    // _.each(page_data, function(item) {
    //    $('#pageList').append("<div class='span' style='margin-left:0;margin-right:10px;margin-bottom:10px'><a href='/posts/"+item.id+"/' class='btn btn-large btn-primary btn-fixed-width'>"+item.name+"</a></div>");
    // });

    _.each(page_data, function(item) {
        pageStuff += "<div class='span' style='margin-left:0;margin-right:10px;margin-bottom:10px'><a href='/posts/"+item.id+"/' class='btn btn-large btn-primary btn-fixed-width'>"+item.name+"</a></div>";
    });

    $('#pageList').append(pageStuff);
    $('#viewLoader').hide();
    $('#pageView').show();
}

function render_posts(page_data)
{
    $('.photoScore').append(page_data.average_scores.photo+'%');
    $('.linkScore').append(page_data.average_scores.link+'%');
    $('.videoScore').append(page_data.average_scores.video+'%');
    $('.statusScore').append(page_data.average_scores.status+'%');
    var i = 0;
    _.each(page_data.posts, function(item, index) {

        var url = '';
        if (item.type === 'photo') {
            url = '<a href="'+item.link+'" rel="external">View Post</a>';
        } else {
            url = '<a href="http://facebook.com/'+item.id+'" rel="external">View Post</a>';
        }

        postList[i] = {

            "url": url,
            "type": item.type,
            "likes": item.lcs.like,
            "comments": item.lcs.comment,
            "shares":   item.lcs.share,
            "clicks":   item.clicks.other + item.clicks.link,
            "impressions": item.impressions,
            // "adjusted_score": item.adjusted_score,
            "score": item.score
        };
        i++;
    });

    // INITIALIZING THE DATAGRID
    var dataSource = new StaticDataSource({
        columns: [
            {
                property: 'url',
                label: 'Link',
                sortable: true
            },
            {
                property: 'type',
                label: 'Type',
                sortable: true
            },
            {
                property: 'likes',
                label: 'Likes',
                sortable: true
            },
            {
                property: 'comments',
                label: 'Comments',
                sortable: true
            },
            {
                property: 'shares',
                label: 'Shares',
                sortable: true
            },
            {
                property: 'clicks',
                label: 'Clicks',
                sortable: true
            },
            {
                property: 'impressions',
                label: 'Impressions',
                sortable: true
            },
            // {
            //    property: 'adjusted_score',
            //    label: 'Adjusted Contextual Score (%)',
            //    sortable: true
            // },
            {
                property: 'score',
                label: 'Score (%)',
                sortable: true
            }
        ],
        data: postList,
        delay: 250
    });
    $('#postGrid').datagrid({ dataSource: dataSource, stretchHeight: true })

    $('#viewLoader').hide();
    $('#postsView').show();
}

function onFacebookConnect() {
    window.location = '';
}