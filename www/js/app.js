var postList = {};
var pageStuff = '';
var pageData = {};
var showCount = 0;

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
                url = '<a href="'+item.link+'" target="_blank">View</a>';
            } else {
                url = '<a href="http://facebook.com/'+item.id+'" target="_blank">View</a>';
            }

            var datapoints = {
                total_stories: (!!item.insights.post_story_adds) ? item.insights.post_story_adds : 0,
                ptat: (!!item.insights.post_story_adds_unique) ? item.insights.post_story_adds_unique : 0,
                likes: 0,
                likes_unique: 0,
                comments: 0,
                comments_unique: 0,
                shares: 0,
                shares_unique: 0,
                photo_views: 0,
                photo_views_unique: 0,
                link_clicks: 0,
                link_clicks_unique: 0,
                video_plays: 0,
                video_plays_unique: 0,
                other_clicks: 0,
                other_clicks_unique: 0,
                total_clicks: 0,
                total_clicks_unique: 0,
                impressions: item.insights.post_impressions,
                impressions_unique: item.insights.post_impressions_unique,
                impressions_paid: (!!item.insights.post_impressions_paid) ? item.insights.post_impressions_paid : 0,
                impressions_paid_unique: (!!item.insights.post_impressions_paid_unique) ? item.insights.post_impressions_paid_unique : 0,
                impressions_organic: (!!item.insights.post_impressions_organic) ? item.insights.post_impressions_organic : 0,
                impressions_organic_unique: (!!item.insights.post_impressions_organic_unique) ? item.insights.post_impressions_organic_unique : 0,
                impressions_viral: (!!item.insights.post_impressions_viral) ? item.insights.post_impressions_viral : 0,
                impressions_viral_unique: (!!item.insights.post_impressions_viral_unique) ? item.insights.post_impressions_viral_unique : 0
            };

            if (!!item.insights.post_story_adds_by_action_type) {
                datapoints.likes += (!!item.insights.post_stories_by_action_type.like) ? item.insights.post_stories_by_action_type.like : 0;
                datapoints.comments += (!!item.insights.post_stories_by_action_type.comment) ? item.insights.post_stories_by_action_type.comment : 0;
                datapoints.shares += (!!item.insights.post_stories_by_action_type.share) ? item.insights.post_stories_by_action_type.share : 0;
            }

            if (!!item.insights.post_story_adds_by_action_type_unique) {
                datapoints.likes_unique += (!!item.insights.post_story_adds_by_action_type_unique.like) ? item.insights.post_story_adds_by_action_type_unique.like : 0;
                datapoints.comments_unique += (!!item.insights.post_story_adds_by_action_type_unique.comment) ? item.insights.post_story_adds_by_action_type_unique.comment : 0;
                datapoints.shares_unique += (!!item.insights.post_story_adds_by_action_type_unique.share) ? item.insights.post_story_adds_by_action_type_unique.share : 0;
            }

            if (!!item.insights.post_consumptions_by_type) {
                datapoints.other_clicks += (!!item.insights.post_consumptions_by_type["other clicks"]) ? item.insights.post_consumptions_by_type["other clicks"] : 0;
                datapoints.link_clicks += (!!item.insights.post_consumptions_by_type["link clicks"]) ? item.insights.post_consumptions_by_type["link clicks"] : 0;
                datapoints.photo_views += (!!item.insights.post_consumptions_by_type["photo views"]) ? item.insights.post_consumptions_by_type["photo views"] : 0;
                datapoints.video_plays += (!!item.insights.post_consumptions_by_type["video play"]) ? item.insights.post_consumptions_by_type["video play"] : 0;
                datapoints.total_clicks += datapoints.photo_views + datapoints.link_clicks + datapoints.other_clicks + datapoints.video_plays;
            }

            if (!!item.insights.post_consumptions_by_type_unique) {
                datapoints.other_clicks_unique += (!!item.insights.post_consumptions_by_type_unique["other clicks"]) ? item.insights.post_consumptions_by_type_unique["other clicks"] : 0;
                datapoints.link_clicks_unique += (!!item.insights.post_consumptions_by_type_unique["link clicks"]) ? item.insights.post_consumptions_by_type_unique["link clicks"] : 0;
                datapoints.photo_views_unique += (!!item.insights.post_consumptions_by_type_unique["photo views"]) ? item.insights.post_consumptions_by_type_unique["photo views"] : 0;
                datapoints.video_plays_unique += (!!item.insights.post_consumptions_by_type_unique["video play"]) ? item.insights.post_consumptions_by_type_unique["video play"] : 0;
                datapoints.total_clicks_unique += datapoints.photo_views_unique + datapoints.link_clicks_unique + datapoints.other_clicks_unique + datapoints.video_plays_unique;
            }

            if (!formula) {
                formula = "(likes+comments+shares)/impressions"
            }

            postList[i] = {
                "url": url,
                "type": item.type,
                "total_stories": datapoints.total_stories,
                "ptat": datapoints.ptat,
                "likes": datapoints.likes,
                "likes_unique": datapoints.likes_unique,
                "comments": datapoints.comments,
                "comments_unique": datapoints.comments_unique,
                "shares":   datapoints.shares,
                "shares_unique": datapoints.shares_unique,
                "photo_views": datapoints.photo_views,
                "photo_views_unique": datapoints.photo_views_unique,
                "video_plays": datapoints.video_plays,
                "video_plays_unique": datapoints.video_plays_unique,
                "link_clicks":   datapoints.link_clicks,
                "link_clicks_unique":   datapoints.link_clicks_unique,
                "other_clicks": datapoints.other_clicks,
                "other_clicks_unique": datapoints.other_clicks_unique,
                "total_clicks": datapoints.total_clicks,
                "total_clicks_unique": datapoints.total_clicks_unique,
                "impressions": datapoints.impressions,
                "impressions_unique": datapoints.impressions_unique,
                "impressions_paid": datapoints.impressions_paid,
                "impressions_paid_unique": datapoints.impressions_paid_unique,
                "impressions_organic": datapoints.impressions_organic,
                "impressions_organic_unique": datapoints.impressions_organic_unique,
                "impressions_viral": datapoints.impressions_viral,
                "impressions_viral_unique": datapoints.impressions_viral_unique,
                "score": implement_formula(formula, datapoints)
                // "score": Math.round(((actions.like+actions.comment+actions.share)/item.insights.post_impressions)*100)/100
            };
            i++;
        });
    } catch (e) {
        console.log(e.message);
        alert("You put in a bad formula, please edit it and resubmit.");
        return false;
    }

    var table_columns = [
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
            label: 'L',
            sortable: true
        },
        {
            property: 'likes_unique',
            label: 'L u',
            sortable: true
        },
        {
            property: 'comments',
            label: 'C',
            sortable: true
        },
        {
            property: 'comments_unique',
            label: 'C u',
            sortable: true
        },
        {
            property: 'shares',
            label: 'S',
            sortable: true
        },
        {
            property: 'shares_unique',
            label: 'S u',
            sortable: true
        },
        {
            property: 'photo_views',
            label: 'PV',
            sortable: true
        },
        {
            property: 'photo_views_unique',
            label: 'PV u',
            sortable: true
        },
        {
            property: 'video_plays',
            label: 'VP',
            sortable: true
        },
        {
            property: 'video_plays_unique',
            label: 'VP u',
            sortable: true
        },
        {
            property: 'link_clicks',
            label: 'LC',
            sortable: true
        },
        {
            property: 'link_clicks_unique',
            label: 'LC u',
            sortable: true
        },
        {
            property: 'other_clicks',
            label: 'OC',
            sortable: true
        },
        {
            property: 'other_clicks_unique',
            label: 'OC u',
            sortable: true
        },
        {
            property: 'impressions',
            label: 'I',
            sortable: true
        },
        {
            property: 'impressions_unique',
            label: 'I u',
            sortable: true
        },
        {
            property: 'impressions_organic',
            label: 'I o',
            sortable: true
        },
        {
            property: 'impressions_organic_unique',
            label: 'I ou',
            sortable: true
        },
        {
            property: 'impressions_viral',
            label: 'I v',
            sortable: true
        },
        {
            property: 'impressions_viral_unique',
            label: 'I vu',
            sortable: true
        },
        {
            property: 'impressions_paid',
            label: 'I p',
            sortable: true
        },
        {
            property: 'impressions_paid_unique',
            label: 'I pu',
            sortable: true
        },
        {
            property: 'score',
            label: 'Score',
            sortable: true
        }
    ];

    // INITIALIZING THE DATAGRID
    var dataSource = new StaticDataSource({
        columns: table_columns,
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

function build_metric_checkboxes(table_columns) {
    var checkboxHTML = '<table><tbody><tr>';
    var i = 0;
    _.each(table_columns, function(column) {
        if ((i !== 0) && (i % 8 === 0)) {
            checkboxHTML += '</tr><tr>';
        }
        checkboxHTML += '<td><input type="button" class="metricToggle btn btn-small btn-primary btn-fixed-width" data-metric-name="'+column.property+'" value="hide" /><br />'+column.label+'</td>';
        i++;
    });

    checkboxHTML += '</tr></tbody></table>';

    $('#metricCheckboxes').append(checkboxHTML);
}

function implement_formula(formula, datapoints) {

    var self = this;

    _.each(datapoints, function(datapoint, index) {
        self[index] = datapoint;
    });

    try {
        // (comments+likes)/impressions
        var new_score = Math.round((eval(formula))*100)/100;
        if (new_score === Infinity) {
            new_score = 0;
        }
        return new_score;
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
    $(".fuelux").on("submit", "#formulaForm", function(){
        var formula = $("textarea:first").val();
        render_posts(pageData, formula);
        return false;
    });

    $(".collapse").collapse();
});