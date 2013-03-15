<?php
namespace GraphGuru;
use Facebook;

// establish src dir and require classes
$src_dir = realpath(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src');
require_once $src_dir . DIRECTORY_SEPARATOR . "vendors/facebook-php-sdk/src/facebook.php";

/**
 * Project's main class for all data grabbing.
 * TODO: Split into util classes anything that can be
 */
class Guru
{
    /**
     * @var array
     */
    protected $config = array();

    /**
     * @var \Facebook
     */
    protected $facebook;

    /**
     * @param array $config
     * @param $access_token
     */
    function __construct(Array $config, $access_token)
    {
        // set our config
        $this->config = $config;

        // instantiate our facebook sdk
        $facebook = new Facebook(array(
            'appId'  => $this->config['facebook']['app.id'],
            'secret' => $this->config['facebook']['app.secret'],
        ));

        // set the facebook access token
        $facebook->setAccessToken($access_token);

        // set the facebook
        $this->facebook = $facebook;
    }

    /**
     * Main FB page getter
     *
     * @return array
     */
    function get_pages()
    {
        // basic page request from facebook (API calls them 'accounts', it's all pages user is admin of)
        $managed_pages = $this->facebook->api('/me/accounts');

        // if there's a 'data' attribute, return that as that's where the page list is
        return isset($managed_pages['data']) ? $managed_pages['data'] : array();
    }

    /**
     * Main FB getter to grab page, page's posts, and some insights on those posts
     *
     * @param $page_id
     * @return mixed
     */
    function get_page_with_posts($page_id)
    {
        // basic page request by object id
        $page = $this->facebook->api('/' . $page_id);

        // grab posts for this page
        // amount to be grabbed determined in this method
        $page['posts'] = $this->get_page_posts($page_id);

        // grab post score averages by type and load into page array
        // $page['average_scores']['photo'] = $this->get_average_score_by_type($page['posts'], 'photo');
        // $page['average_scores']['link'] = $this->get_average_score_by_type($page['posts'], 'link');
        // $page['average_scores']['video'] = $this->get_average_score_by_type($page['posts'], 'video');
        // $page['average_scores']['status'] = $this->get_average_score_by_type($page['posts'], 'status');

        return $page;
    }

    /**
     * Calculates average scores of a page's posts by type
     * (in the context of posts provided)
     *
     * Current known types are video, photo, link, and status
     *
     * @param array $posts
     * @param string $type
     * @return float|int
     */
    protected function get_average_score_by_type(array $posts, $type = 'status')
    {
        // filters posts to only the chosen type
        $posts = $this->filter_posts_by_type($posts, $type);

        // load up and empty array to start adding to
        $post_scores = array();

        // loop the posts
        foreach ($posts as $post)
        {
            // add particular posts score to array
            $post_scores[] = $post['score'];
        }

        // Gotta protect against 0 since we're dividing
        if (count($post_scores) > 0)
        {
            // Operation is basic "mean".
            // Should read: add all post scores, divide that by number of scores, round it.
            $average_post_score = round(array_sum($post_scores) / count($post_scores));
        }
        else
        {
            // No posts scores, average is 0
            $average_post_score = 0;
        }

        return $average_post_score;
    }

    /**
     * Simple method to filter posts array
     *
     * @param array $posts
     * @param string $type
     * @return array
     */
    protected function filter_posts_by_type(array $posts, $type = 'status')
    {
        foreach ($posts as $post_index => $post)
        {
            if ($post['type'] != $type)
            {
                unset($posts[$post_index]);
            }
        }

        return $posts;
    }

    /**
     * Getter for a page's posts, insights data, and calculated scores
     *
     * @param $page_id
     * @return array
     */
    protected function get_page_posts($page_id)
    {
        // Get our post request limit from config
        // TODO: determine FB 'limit' restrictions for these requests
        $post_request_limit = $this->config['facebook']['limit'];

        // basic page_id/posts request with configured limit
        $response = $this->facebook->api('/' . $page_id . '/posts?limit=' . $post_request_limit);

        // grab actual post list from data attribute of response
        $posts = $response['data'];

        // get posts insights
        $posts = $this->get_posts_insights($posts);

        // remove weirdness means removing page posts that break the rules around engagement and impressions
        // better explained in method's docblock
        $posts = $this->remove_weirdness($posts);

        // grabs all the posts scores
        // $posts = $this->get_posts_scores($posts);

        return $posts;
    }

    protected function get_posts_insights(array $posts)
    {
        // This checks count as we use FB Graph API batcher which has a max of 50 requests per call
        if (count($posts) > 50)
        {
            // handles chunking and batching of anything larger than 50 posts
            $posts = $this->post_chunk_batcher($posts, __FUNCTION__);
        }
        else
        {
            // send out posts for batch processing!
            $posts_batch_response = $this->process_batch($posts, '/insights');

            // separate indices and value to match our response to it's requesting post index
            foreach ($posts_batch_response as $insight_key => $insight_response)
            {
                $insight_response_body = json_decode($insight_response['body'], TRUE);

                if (!empty($insight_response_body['data']))
                {
                    foreach ($insight_response_body['data'] as $insight)
                    {
                        if (isset($insight['values'][0]['value']))
                        {
                            $posts[$insight_key]['insights'][$insight['name']] = $insight['values'][0]['value'];
                        }
                    }
                }
                else
                {
                    unset($posts[$insight_key]);
                }
            }
        }

        return $posts;
    }

    /**
     * So when a business page performs an action (like, comment, share) on it's own posts,
     * that action is ALSO included as a post...with ZERO impressions but with ALL the original posts' unqiue action counts.
     * This totally borks the numbers calculation, so this function eliminates those posts that have ZERO impressions
     *
     * @param array $posts
     * @return array
     */
    protected function remove_weirdness(array $posts)
    {
        foreach ($posts as $post_index => $post)
        {
            // No impressions, then we have to assume the worst.
            if (!isset($post['insights']['post_impressions']) || $post['insights']['post_impressions'] <= 0)
            {
                unset($posts[$post_index]);
            }
        }

        return $posts;
    }

    protected function post_chunk_batcher(array $posts, $method)
    {
        // chunk our larger than 50 item array into an array of 50 piece post arrays
        $array_of_posts = array_chunk($posts, 50);

        // kill our previous post array to reload after calls are made
        $posts = array();

        // Loop through our chucked post arrays
        foreach ($array_of_posts as $set_of_posts)
        {
            // call the originating method (but this call will be 50 or less and pass into actual batching in that method)
            // merge with any previous calls to now have a re-assembled and completed post array of same size
            // as original, but now with lcs counts
            $posts = array_merge($posts, $this->$method($set_of_posts));
        }

        return $posts;
    }

    /**
     * Creates and calls batch process for posts on FB api
     *
     * @param array $posts
     * @param $endpoint
     * @return mixed
     */
    protected function process_batch(array $posts, $endpoint)
    {
        $batch_queries = array();

        foreach ($posts as $post)
        {
            $batch_queries[] = array('method' => 'GET', 'relative_url' => '/' . $post['id'] . $endpoint);
        }

        return $this->facebook->api('/?batch=' . json_encode($batch_queries), 'POST');
    }

    /**
     * Simple score calculation based on previously loaded insights data
     *
     * @param array $posts
     * @return array
     */
    protected function get_posts_scores(array $posts)
    {
        // allow our writes to the $post persist outside of foreach
        foreach ($posts as &$post)
        {
            // This is probably not needed as we are removing posts with 0 impressions in an earlier operation...
            // that said, I'll leave it to prevent future division by 0
            if ($post['insights']['post_impressions'] > 0)
            {
                // Fairly simple math operations of engagement over impressions
                // Should read: add likes comments shares, add total LCS to total clicks, divide that by impressions,
                // multiply that by 100 (take into the percentage realm), round that number
                $post['score'] = round(array_sum($post['insights']['post_stories_by_action_type']) / $post['insights']['post_impressions'] * 100);
            }
            else
            {
                // divide by 0 just becomes 0
                $post['score'] = 0;
            }
        }

        // Run adjusted score computation (score normalized for context of given posts / averaged)
        $posts = $this->compute_adjusted_scores($posts);

        return $posts;
    }

    /**
     * Adjusted score calculation
     * This is the normalized score for the given context of sibling posts
     * Just a really basic bell curve adjustment
     *
     * @param array $posts
     * @return array
     */
    protected function compute_adjusted_scores(array $posts)
    {
        // load score arrat
        $scores = array();

        // loop the posts
        foreach ($posts as $post)
        {
            // add score from post
            $scores[] =  $post['score'];
        }

        // if we have scores, find the max score, otherwise set max to 0 (as there aren't any posts)
        $max_score = !empty($scores) ? max($scores) : 0;

        // Loop through posts but allow post var edits to persist
        foreach ($posts as &$post)
        {
            // prevent division by 0!
            if ($max_score > 0)
            {
                // Simple operation to add adjusted score
                // should read: this post's score divided by max score, multiplied by 100, rounded
                // this adjusts for bell curve
                $post['adjusted_score'] = round(($post['score'] / $max_score) * 100);
            }
            else
            {
                // divide by 0 becomes 0
                $post['adjusted_score'] = 0;
            }

        }

        return $posts;
    }
}