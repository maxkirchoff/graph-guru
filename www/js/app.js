var postList = {};
var pageStuff = '';
var pageData = {};

function render_guide(sample_data)
{
    var guideItems = '';
    _.each(sample_data.data, function(item) {

        // Name Key Description Possible Values
        guideItems += '<tr><td>'+item.title+'</td><td>'+item.name+'</td><td>'+item.description+'</td><td>';
        if (item.notes != null) {
            guideItems += item.notes;
        }
        guideItems += '</td>';

        if (item.values[0].value != null) {
            if (typeof item.values[0].value === 'object') {
                guideItems += '<td>';

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

function render_posts(page_data, formula)
{
    $('#loadingText').empty().append("Calculating post metrics...");
    var i = 0;

    try {
        _.each(page_data.posts, function(item, index) {

            var url = '';
            if (item.type === 'photo') {
                url = '<a href="'+item.link+'" rel="external">View Post</a>';
            } else {
                url = '<a href="http://facebook.com/'+item.id+'" target="_blank">View Post</a>';
            }

            var datapoints = {
                like: 0,
                comment: 0,
                share: 0,
                photo_view: 0,
                link_click: 0,
                video_play: 0,
                other_click: 0,
                total_click: 0,
                impression: item.insights.post_impressions,
                reach: item.insights.post_impressions_unique
            };

            if (!!item.insights.post_story_adds_by_action_type) {
                datapoints.like = (!!item.insights.post_stories_by_action_type.like) ? item.insights.post_stories_by_action_type.like : 0;
                datapoints.comment = (!!item.insights.post_stories_by_action_type.comment) ? item.insights.post_stories_by_action_type.comment : 0;
                datapoints.share = (!!item.insights.post_stories_by_action_type.share) ? item.insights.post_stories_by_action_type.share : 0;
            }

            if (!!item.insights.post_consumptions_by_type) {
                datapoints.other_click = (!!item.insights.post_consumptions_by_type["other clicks"]) ? item.insights.post_consumptions_by_type["other clicks"] : 0;
                datapoints.link_click = (!!item.insights.post_consumptions_by_type["link clicks"]) ? item.insights.post_consumptions_by_type["link clicks"] : 0;
                datapoints.photo_view = (!!item.insights.post_consumptions_by_type["photo views"]) ? item.insights.post_consumptions_by_type["photo views"] : 0;
                datapoints.video_play = (!!item.insights.post_consumptions_by_type["video play"]) ? item.insights.post_consumptions_by_type["video play"] : 0;

                datapoints.total_click = datapoints.photo_view + datapoints.link_click + datapoints.other_click + datapoints.video_play;
            }

            if (!formula) {
                formula = "(likes+comments+shares)/impressions"
            }

            postList[i] = {
                "url": url,
                "type": item.type,
                "likes": datapoints.like,
                "comments": datapoints.comment,
                "shares":   datapoints.share,
                "photo_views": datapoints.photo_view,
                "video_plays": datapoints.video_play,
                "link_clicks":   datapoints.link_click,
                // The 'non value' impressions are weeded out way earlier
                "impressions": datapoints.impression,
                "reach": datapoints.reach,
                // "adjusted_score": item.adjusted_score,
                "score": implement_formula(formula, datapoints)
                // "score": Math.round(((actions.like+actions.comment+actions.share)/item.insights.post_impressions)*100)/100
            };
            i++;
        });
    } catch (e) {
        alert("You put in a bad formula, please edit it and resubmit.");
        return false;
    }

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
                property: 'photo_views',
                label: 'Photo Views',
                sortable: true
            },
            {
                property: 'video_plays',
                label: 'Video Plays',
                sortable: true
            },
            {
                property: 'link_clicks',
                label: 'Link Clicks',
                sortable: true
            },
            {
                property: 'impressions',
                label: 'Impressions',
                sortable: true
            },
            {
                property: 'reach',
                label: 'Reach',
                sortable: true
            },
            {
                property: 'score',
                label: 'Score',
                sortable: true
            }
        ],
        data: postList,
        delay: 250
    });

    var averageScore = findAverageScoresByType();

    $('.photoScore').empty().append(averageScore.photo);
    $('.linkScore').empty().append(averageScore.link);
    $('.videoScore').empty().append(averageScore.video);
    $('.statusScore').empty().append(averageScore.status);

    $('#postGrid').datagrid({ dataSource: dataSource, stretchHeight: true }).data('datagrid').reload();
    $('#viewLoader').hide();
    $('#postsView').show();
}

function implement_formula(formula, datapoints) {
    var likes = datapoints.like;
    var comments = datapoints.comment;
    var shares = datapoints.share;
    var photo_views = datapoints.photo_view;
    var video_plays = datapoints.video_play;
    var link_clicks = datapoints.link_click;
    var impressions = datapoints.impression;
    var reach = datapoints.reach;

    try {
        return Math.round((eval(formula))*100)/100;
    } catch(e) {
        console.log(e);
        throw(e);
    }

}

function findAverageScoresByType()
{
    var scoreTotals = {
        photo: 0,
        video: 0,
        link: 0,
        status: 0
    };

    var postCount = {
        photo: 0,
        video: 0,
        link: 0,
        status: 0
    };

    _.each(postList, function(post) {

        switch (post.type) {
            case 'photo':
                scoreTotals.photo += post.score;
                postCount.photo++;
                break;
            case 'video':
                scoreTotals.video += post.score;
                postCount.video++;
                break;
            case 'link':
                scoreTotals.link += post.score;
                postCount.link++;
                break;
            case 'status':
                scoreTotals.status += post.score;
                postCount.status++;
                break;
        }
    });

    return {
        photo: Math.round((scoreTotals.photo/postCount.photo)*100)/100,
        video: Math.round((scoreTotals.video/postCount.video)*100)/100,
        link: Math.round((scoreTotals.link/postCount.link)*100)/100,
        status: Math.round((scoreTotals.status/postCount.status)*100)/100
    }
}

function onFacebookConnect() {
    window.location = '';
}

$(function() {
    $('#formulaForm').submit(function() {
        var formula = $("textarea:first").val();
        render_posts(pageData, formula);
        return false;
    });

    $(".collapse").collapse();
});