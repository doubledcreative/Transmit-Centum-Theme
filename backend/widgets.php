<?php

//TODO latest posts with thumbnail


add_action('widgets_init', 'purepress_load_widgets');

function purepress_load_widgets() {

    register_widget('incredible_flickr');
   // register_widget('incredible_popular');
    register_widget('incredible_latest');
    register_widget('incredible_twitter_widget');

    //register_widget('incredible_followers_widget');

}

  function image_from_description($data) {  
            preg_match_all('/<img src="([^"]*)"([^>]*)>/i', $data, $matches);  
            return $matches[1][0];  
        }  

             function select_image($img, $size) {
            $img = explode('/', $img);
            $filename = array_pop($img);

    // The sizes listed here are the ones Flickr provides by default.  Pass the array index in the        $size variable to selct one.
    // 0 for square, 1 for thumb, 2 for small, etc.
            $s = array(
        '_s.', // square
        '_t.', // thumb
        '_m.', // small
        '.',   // medium
        '_b.'  // large
        );

            $img[] = preg_replace('/(_(s|t|m|b))?\./i', $s[$size], $filename);
            return implode('/', $img);
        }
class incredible_flickr extends WP_Widget {

    function incredible_flickr() {
        $widget_ops = array('classname' => 'incredible-flickr', 'description' => 'Widget for popular posts');
        $control_ops = array('width' => 300);
        $this->WP_Widget('incredible_flickr', 'Centum Flickr', $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);
        echo $before_widget;
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $count = $instance['count'];
        $category = $instance['category'];

        if (!empty($title)) {
            echo '<div class="headline no-margin"><h4>'.$title.'</h4></div>' ;
        };

        if ($instance['type'] == "user") { $rss_url = 'http://api.flickr.com/services/feeds/photos_public.gne?id=' . $instance['id'] . '&tags=' . $instance['tags'] . '&format=rss_200'; }
        elseif ($instance['type'] == "favorite") { $rss_url = 'http://api.flickr.com/services/feeds/photos_faves.gne?id=' . $instance['id'] . '&format=rss_200'; }
        elseif ($instance['type'] == "set") { $rss_url = 'http://api.flickr.com/services/feeds/photoset.gne?set=' . $instance['set'] . '&nsid=' . $instance['id'] . '&format=rss_200'; }
        elseif ($instance['type'] == "group") { $rss_url = 'http://api.flickr.com/services/feeds/groups_pool.gne?id=' . $instance['id'] . '&format=rss_200'; }
        elseif ($instance['type'] == "public" || $instance['type'] == "community") { $rss_url = 'http://api.flickr.com/services/feeds/photos_public.gne?tags=' . $instance['tags'] . '&format=rss_200'; }
        else {
            print '<strong>No "type" parameter has been setup. Check your flickr widget settings.</strong>';

        }


            // Check if another plugin is using RSS, may not work
        include_once (ABSPATH . WPINC . '/class-simplepie.php');
        error_reporting(E_ERROR);
        $feed = new SimplePie($rss_url);  
        $feed->handle_content_type();  

       $items = array_slice($rss->items, 0, $instance['count']);

        $print_flickr = '<div class="flickr-widget-blog"><ul>';

        $i = 0;
        foreach ($feed->get_items() as $item): 

        if(++$i > $count)
            break;

        if ($enclosure = $item->get_enclosure()) {
            $img = image_from_description($item->get_description());
            $thumb_url = select_image($img, 0);
            $full_url = select_image($img, 4);  
            $print_flickr .= '<li><a  href="' .$item->get_link() . '" title="'. $enclosure->get_title(). '"><img alt="'. $enclosure->get_title().'" id="photo_' . $i . '" src="' . $thumb_url . '" /></a></li>'."\n";
        }
        endforeach;



        echo $print_flickr.'</ul></div>';
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['count'] = $new_instance['count'];
        $instance['type'] = $new_instance['type'];
        $instance['id'] = $new_instance['id'];
        $instance['set'] = $new_instance['set'];
        $instance['tas'] = $new_instance['tags'];


        return $instance;
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => ''));
        $title = strip_tags($instance['title']);
        $count = $instance['count'];
        $type = $instance['type'];
        $id = $instance['id'];
        $set = $instance['set'];
        $tags = $instance['tags'];

        ?>


        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('type'); ?>">Display photos from</label>
            <select id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>" id="type">
                <option <?php if($instance['type'] == 'user') { echo 'selected'; } ?> value="user">user</option>
                <option <?php if($instance['type'] == 'set') { echo 'selected'; } ?> value="set">set</option>
                <option <?php if($instance['type'] == 'favorite') { echo 'selected'; } ?> value="favorite">favorite</option>
                <option <?php if($instance['type'] == 'group') { echo 'selected'; } ?> value="group">group</option>
                <option <?php if($instance['type'] == 'public') { echo 'selected'; } ?> value="public">community</option>
            </select>
        </p>


        <p>
            <label for="<?php echo $this->get_field_id('id'); ?>">User or Group ID (<a href="http://idgettr.com/">find ID</a>)</label>
            <input  id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo $id; ?>" size="20" />

        </p>

        <p>
            <label for="<?php echo $this->get_field_id('set'); ?>">Set ID (<a href="http://idgettr.com/">find your ID</a> )</label>
            <input  id="<?php echo $this->get_field_id('set'); ?>" name="<?php echo $this->get_field_name('set'); ?>"  type="text"  value="<?php echo $set; ?>" size="40" />

        </p>

        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>">Tags (optional) <small>Comma separated, no spaces</small> </label>
            <input  id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>"  type="text" value="<?php echo $tags; ?>" size="40" />

        </p>

        <p>
            <label for="<?php echo $this->get_field_id('count'); ?>">How many photos? 
                <select class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" >
                    <?php for ($i=1; $i<=20; $i++) { ?>
                    <option <?php if ($count == $i) { echo 'selected'; } ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>
                </select>
            </label>
        </p>

        <?php
    }

}




class incredible_twitter_widget extends WP_Widget {

    function incredible_twitter_widget() {
        $widget_ops = array('classname' => 'incredible-twitter', 'description' => 'Place for Twitter widget');
        $control_ops = array('width' => 300, 'height' => 350);

        $this->WP_Widget('incredible_twitter_widget', 'Centum Twitter Widget', $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);

        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $text = empty($instance['text']) ? '' : apply_filters('widget_title', $instance['text']);
        $user = empty($instance['user']) ? '' : apply_filters('widget_title', $instance['user']);
        $limit = empty($instance['limit']) ? '' : apply_filters('widget_title', $instance['limit']);

        echo $before_widget;    
        echo $before_title . $title . $after_title;

        ?>

        <ul id="twitter-blog"></ul>
        <script type="text/javascript">
        jQuery(document).ready(function($){
            $.getJSON('http://api.twitter.com/1/statuses/user_timeline/<?php echo $user; ?>.json?count=<?php echo $limit; ?>&callback=?', function(tweets){
                $("<?php echo '#'.$this->id; ?> #twitter-blog").html(tz_format_twitter(tweets));
            }); });
        </script>
        <div class="clearfix"></div>

        <?php

        echo $text;
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['text'] = $new_instance['text'];
        $instance['user'] = $new_instance['user'];
        $instance['limit'] = $new_instance['limit'];
        return $instance;
    }

    function form($instance) {
        global $mag_social_widgets_services;
        $instance = wp_parse_args((array) $instance, array('title' => ''));
        $title = strip_tags($instance['title']);

        $user = $instance['user'];
        $limit = $instance['limit'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'cloudfw'); ?>:
                <input  id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" size="20" /></label>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('user'); ?>"><?php _e('User', 'cloudfw'); ?>:
                    <input  id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo $user; ?>" size="20" />
                </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Limit', 'cloudfw'); ?>:
                    <input  id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>" size="20" />
                </label>
            </p>
            <?php
        }

    }





    class incredible_followers_widget extends WP_Widget {

        function incredible_followers_widget() {
            $widget_ops = array('classname' => 'inc-followers', 'description' => 'Displays number of twitter and feedburner followers');
            $control_ops = array('width' => 300, 'height' => 350);
            $this->WP_Widget('incredible_followers_widget', 'IncredibleWP Followers Widget', $widget_ops, $control_ops);
        }

        function widget($args, $instance) {
            extract($args, EXTR_SKIP);

            $twitter = $instance['twitter'];
            $rss = $instance['rss'];
            $rssman = $instance['rssman'];


            echo $before_widget;
            ?>

            <?php if($twitter) { ?>
            <div class="widget social">
                <div class="social-blog tooltips">
                    <a href="https://twitter.com/#!/<?php echo $twitter; ?>" rel="tooltip" title="Follow on Twitter" class="feed">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/social_icons/twitter.png" alt="Twitter" />
                        <p><?php echo rarst_twitter_user($twitter, 'followers_count'); ?> <br/><span>Followers</span></p>
                    </a>
                </div>
                <?php }

                if($rss) { ?>
                <div class="social-blog tooltips">
                    <a href="http://feeds.feedburner.com/<?php echo $rss; ?>" rel="tooltip" title="Join RSS Feed" class="feed">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/social_icons/rss.png" alt="RSS" />

                        <p><?php if($rssman) { echo $rssman; } else { echo getRssCount($rss); } ?> <br/><span>Subscribers</span></p>

                    </a>
                </div>
            </div>



            <?php 
        }
        echo $after_widget;
    }


    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['twitter'] = $new_instance['twitter'];
        $instance['rss'] = $new_instance['rss'];
        $instance['rssman'] = $new_instance['rssman'];

        return $instance;
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => ''));
        $twitter = strip_tags($instance['twitter']);
        $rss = strip_tags($instance['rss']);
        $rssman = strip_tags($instance['rssman']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter', 'cloudfw'); ?>:
                <input  id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" type="text" value="<?php echo $twitter; ?>" size="20" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('rss'); ?>"><?php _e('Feedburner', 'cloudfw'); ?>:
                <input  id="<?php echo $this->get_field_id('rss'); ?>" name="<?php echo $this->get_field_name('rss'); ?>" type="text" value="<?php echo $rss; ?>" size="20" />
            </label>
        </p>
        <p>It could happened for many reasons that you're hosting won't allow cURL or any other way to get data from Feedburner, in that case you might want to put number of followers manually</p>
        <p>
            <label for="<?php echo $this->get_field_id('rssman'); ?>"><?php _e('Feedburner manual number', 'cloudfw'); ?>:
                <input  id="<?php echo $this->get_field_id('rssman'); ?>" name="<?php echo $this->get_field_name('rssman'); ?>" type="text" value="<?php echo $rssman; ?>" size="20" />
            </label>
        </p>


        <?php
    }


}



class incredible_popular extends WP_Widget {

    function incredible_popular() {
        $widget_ops = array('classname' => 'incredible-popular', 'description' => 'IncredibleWP popular/recent posts');
        $control_ops = array('width' => 300);
        $this->WP_Widget('incredible_popular', 'IncredibleWP Popular Posts', $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);
        echo $before_widget;
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $title2 = $instance['title2'];
        $count = $instance['count'];
        $orderby = $instance['orderby'];

        wp_reset_query();
        rewind_posts();
        query_posts(
            array(
                'posts_per_page' => $count,
                'post_status' => 'publish',
                'gdsr_sort' => 'rating',
                'nopaging' => 0,
                'post__not_in' => get_option('sticky_posts'),
                'gdsr_order' => 'desc'
                )
            );
            ?>
            <ul class="tabs-nav">
                <li class="active"><a href="#tab1"><?php if($title) {echo $title;} else { echo "Popular";} ?></a></li>
                <li><a href="#tab2"><?php if($title2) {echo $title2;} else { echo "Recent";} ?></a></li>
            </ul>
            <?php
            $postnum = 0;
            echo '<div class="tabs-container">
            <div class="tab-content" id="tab1">';
            if (have_posts()) :while (have_posts()) : the_post();

            $postnum++;
            $class = ( $postnum % 2 ) ? ' even' : ' odd';
            ?>
            <div class="latest-post-blog">
                <a href="<?php the_permalink() ?>"> <?php the_post_thumbnail('small-thumb'); ?></a>
                <p><a class="link" href="<?php the_permalink() ?>"><?php the_title(); ?></a></p>
                <span><?php echo get_the_date();?></span>
            </div>
            <?php
            endwhile;
            endif;
            wp_reset_query();
            rewind_posts();
            echo '</div><div class="tab-content" id="tab2">';
            $recent_posts = new WP_Query(
                array(
                    'posts_per_page' => $count,
                    'post_status' => 'publish',
                    'nopaging' => 0,
                    'post__not_in' => get_option('sticky_posts')
                    )
                );
            $postnum = 0;
            if ($recent_posts->have_posts()) :while ($recent_posts->have_posts()) : $recent_posts->the_post();
            $postnum++;
            $class = ( $postnum % 2 ) ? ' even' : ' odd';
            ?>
            <div class="latest-post-blog">
                <a href="<?php the_permalink() ?>"> <?php the_post_thumbnail('small-thumb'); ?></a>
                <p><a class="link" href="<?php the_permalink() ?>"><?php the_title(); ?></a></p>
                <span><?php echo get_the_date();?></span>
            </div>
            <?php
            endwhile;
            endif;
            wp_reset_query();
            rewind_posts();
            echo '</div></div>';
            echo $after_widget;
        }

        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['title2'] = $new_instance['title2'];
            $instance['count'] = $new_instance['count'];

            return $instance;
        }

        function form($instance) {
            $instance = wp_parse_args((array) $instance, array('title' => ''));
            $title = strip_tags($instance['title']);
            $title2 = $instance['title2'];
            $count = $instance['count'];
            ?>


            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>">Recent posts title:
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('title2'); ?>">Popular posts title:
                    <input class="widefat" id="<?php echo $this->get_field_id('title2'); ?>" name="<?php echo $this->get_field_name('title2'); ?>" type="text" value="<?php echo esc_attr($title2); ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('count'); ?>">How many posts? (type only number):
                    <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo esc_attr($count); ?>" />
                </label>
            </p>

            <?php
        }

    }


    class incredible_latest extends WP_Widget {

        function incredible_latest() {
            $widget_ops = array('classname' => 'incredible-latest', 'description' => 'CentumWP recent posts');
            $control_ops = array('width' => 300);
            $this->WP_Widget('incredible_latest', 'CentumWP Recent Posts', $widget_ops, $control_ops);
        }

        function widget($args, $instance) {
            extract($args, EXTR_SKIP);
            echo $before_widget;
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
            $count = $instance['count'];

            echo $before_title . $title . $after_title;
            wp_reset_query();
            rewind_posts();

            $recent_posts = new WP_Query(
                array(
                    'posts_per_page' => $count,
                    'post_status' => 'publish',
                    'nopaging' => 0,
                    'post__not_in' => get_option('sticky_posts')
                    )
                );
            $postnum = 0;
            if ($recent_posts->have_posts()) :while ($recent_posts->have_posts()) : $recent_posts->the_post();
            $postnum++;
            $class = ( $postnum % 2 ) ? ' even' : ' odd';
            ?>

            <div class="latest-post-blog <?php if(!has_post_thumbnail()) echo "no-thumb" ?>">
                <a href="<?php the_permalink() ?>"> <?php the_post_thumbnail('small-thumb'); ?></a>
                <p><a class="link" href="<?php the_permalink() ?>"><?php the_title(); ?></a>
                    <span><?php echo get_the_date();?></span></p>
                </div>
                <?php
                endwhile;
                endif;
                wp_reset_query();
                rewind_posts();

                echo $after_widget;
            }

            function update($new_instance, $old_instance) {
                $instance = $old_instance;
                $instance['title'] = strip_tags($new_instance['title']);

                $instance['count'] = $new_instance['count'];

                return $instance;
            }

            function form($instance) {
                $instance = wp_parse_args((array) $instance, array('title' => ''));
                $title = strip_tags($instance['title']);

                $count = $instance['count'];
                ?>


                <p>
                    <label for="<?php echo $this->get_field_id('title'); ?>">Title:
                        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                    </label>
                </p>

                <p>
                    <label for="<?php echo $this->get_field_id('count'); ?>">How many posts? (type only number):
                        <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo esc_attr($count); ?>" />
                    </label>
                </p>

                <?php
            }

        }

        ?>