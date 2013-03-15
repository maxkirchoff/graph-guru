var postList = {};
var pageStuff = '';

function render_guide(sample_data)
{
    var guideItems = '';
    _.each(sample_data.data, function(item) {
        //console.log(item);
        // handle values

        // Name Key Description Possible Values
        guideItems += '<tr><td>'+item.title+'</td><td>'+item.name+'</td><td>'+item.description+'</td><td>';
        if (item.notes != null) {
            guideItems += item.notes;
        }
        guideItems += '</td>';

        if (item.values[0].value != null) {
            if (typeof item.values[0].value === 'object') {
                guideItems += '<td>';
                console.log(item.values[0].value);
                _.each(item.values[0].value, function(value) {
                    console.log(value);
                });
                for(var name in item.values[0].value) {
                    guideItems += name+'<br/>';
                }
                guideItems += '</td>';
            } else {
                guideItems += '<td>value</td>'
            }
        } else {
            guideItems += '<td>N/A</td>'
        }
        guideItems += '</tr>'
    });

    $('#insightsGuide').append(guideItems);
    $('#viewLoader').hide();
    $('#guideView').show();
}

function render_pages(page_data)
{
    // _.each(page_data, function(item) {
    //    $('#pageList').append("<div class='span' style='margin-left:0;margin-right:10px;margin-bottom:10px'><a href='/posts/"+item.id+"/' class='btn btn-large btn-primary btn-fixed-width'>"+item.name+"</a></div>");
    // });

    _.each(page_data, function(item) {
        pageStuff += "<div class='span' style='margin-left:0;margin-right:10px;margin-bottom:10px'><a href='/posts/"+item.id+"/' class='btn btn-large btn-fixed-width'>"+item.name+"</a></div>";
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