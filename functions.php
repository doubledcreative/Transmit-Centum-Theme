<?php
/**
 * Functions
 *
 * @package WordPress
 * @subpackage CookingPress
 * @since CookingPress 1.0
 */
//error_reporting(E_ALL);
//ini_set('display_errors', '1');


$themename = "Incredible";
$shortname = "icrd";
define('PPTNAME', $shortname);

/**
 * Optional: set 'ot_show_pages' filter to false.
 * This will hide the settings & documentation pages.
 */
add_filter( 'ot_show_pages', '__return_false' );

/**
 * Required: set 'ot_theme_mode' filter to true.
 */
add_filter( 'ot_theme_mode', '__return_true' );

/**
 * Required: include OptionTree.
 */
include_once( 'option-tree/ot-loader.php' );

/**
 * Theme Options
 */
include_once( 'theme-options.php' );
include_once( 'meta-boxes.php' );


require_once( 'backend/widgets.php' );
require_once( 'backend/helpers.php' );
require_once( 'backend/tinymce.php' );
require_once( 'backend/shortcodes.php' );
require_once( 'backend/cssjs.php' );
require_once ('backend/sidebars.php'); // Unlimited sidebars generatorfunctions.php

require_once("backend/class-pixelentity-theme-update.php");

$apikey = ot_get_option('incr_api_key');
if($apikey) {
    $username = ot_get_option('incr_username');
    PixelentityThemeUpdate::init($username,$apikey,'purethemes');
}




add_editor_style( 'editor-style.css' );
add_action('after_setup_theme', 'purepress_setup');
if (!function_exists('purepress_setup')):

    function purepress_setup() {

        // This theme styles the visual editor with editor-style.css to match the theme style.
        add_editor_style();

        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');

        // Make theme available for translation
        // Translations can be filed in the /languages/ directory
        load_theme_textdomain('purepress', get_template_directory() . '/languages');

        $locale = get_locale();
        $locale_file = get_template_directory() . "/languages/$locale.php";
        if (is_readable($locale_file))
            require_once( $locale_file );

        // This theme uses wp_nav_menu() in one location.
        add_theme_support('menus');
        register_nav_menus(array(
            'mainmenu' => 'Menu'
            )
        );

        // This theme allows users to set a custom background
        if ( ! isset( $content_width ) ) $content_width = 960;

        add_theme_support( 'post-formats', array( 'gallery','video' ) );
        add_post_type_support( 'post', 'post-formats' );
    }

endif; // function_exists

$args = array(
    'default-color' => 'ffffff',
    'default-image' => get_template_directory_uri() . '/images//bg/noise.png',
);
add_theme_support( 'custom-background', $args );


// Register and enquee scripts

    if (!function_exists('pp_scripts')) {
        function pp_scripts() {

            wp_register_script('flexslider', get_template_directory_uri() . '/js/flexslider.js');
            wp_register_script('twitter', get_template_directory_uri() . '/js/twitter.js');
            wp_register_script('tooltip', get_template_directory_uri() . '/js/tooltip.js');
            wp_register_script('effects', get_template_directory_uri() . '/js/effects.js');
            wp_register_script('fancybox', get_template_directory_uri() . '/js/fancybox.js');
            wp_register_script('carousel', get_template_directory_uri() . '/js/carousel.js');
            wp_register_script('isotope', get_template_directory_uri() . '/js/jquery.isotope.min.js');
            wp_register_script('custom', get_template_directory_uri() . '/js/custom.js');

            wp_enqueue_script('flexslider');
            wp_enqueue_script('twitter');
            wp_enqueue_script('tooltip');
            wp_enqueue_script('effects');
            wp_enqueue_script('fancybox');
            wp_enqueue_script('carousel');
            wp_enqueue_script('isotope');
            wp_enqueue_script('custom');

        }
        add_action('wp_enqueue_scripts', 'pp_scripts');
    }



    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(640, 300, true); //size of thumbs
    add_image_size('small-thumb', 49, 49, true);
    add_image_size('slider', 372, 255, true);

    //set to 472
    add_image_size('portfolio-main', 940, 0, true);
    add_image_size('portfolio-medium', 460, 290, true);
    add_image_size('portfolio-thumb', 300, 200, true);



/*
 * Footer
*/
if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'id' => 'sidebar',
        'name' => 'Sidebar Area',
        'before_widget' => '<div id="%1$s" class="widget  %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<div class="headline no-margin"><h4>',
        'after_title' => '</h4></div>',
        ));

    register_sidebar(array(
        'id' => 'footer1st',
        'name' => 'Footer 1st Column',
        'description' => '1st column for widgets in Footer.',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<div class="footer-headline"><h4>',
        'after_title' => '</h4></div>',
        ));
     register_sidebar(array(
        'id' => 'footer2nd',
        'name' => 'Footer 2nd Column',
        'description' => '2nd column for widgets in Footer.',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<div class="footer-headline"><h4>',
        'after_title' => '</h4></div>',
        ));
     register_sidebar(array(
        'id' => 'footer3rd',
        'name' => 'Footer 3rd Column',
        'description' => '3rd column for widgets in Footer.',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
         'before_title' => '<div class="footer-headline"><h4>',
        'after_title' => '</h4></div>',
        ));
     register_sidebar(array(
        'id' => 'footer4th',
        'name' => 'Footer 4th Column',
        'description' => '4th column for widgets in Footer.',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<div class="footer-headline"><h4>',
        'after_title' => '</h4></div>',
        ));



}
if (ot_get_option('incr_sidebars')):
    $pp_sidebars = ot_get_option('incr_sidebars');
    foreach ($pp_sidebars as $pp_sidebar) {

    register_sidebar(array(
        'name' => $pp_sidebar["title"],
        'id' => $pp_sidebar["id"],

        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h5>',
        'after_title' => '</h5>',
        ));
}

endif;


/**
 *
 * @global <type> $GLOBALS['allowedposttags']
 * @name $allowedposttags
 */
$allowedposttags["li"] = array(
        "data-feature" => array(),

);


/**
 * Add to extended_valid_elements for TinyMCE
 *
 * @param $init assoc. array of TinyMCE options
 * @return $init the changed assoc. array
 */
function change_mce_options( $init ) {
    //code that adds additional attributes to the pre tag
    $ext = 'li[data-feature]';

    //if extended_valid_elements alreay exists, add to it
    //otherwise, set the extended_valid_elements to $ext
    if ( isset( $init['extended_valid_elements'] ) ) {
        $init['extended_valid_elements'] .= ',' . $ext;
    } else {
        $init['extended_valid_elements'] = $ext;
    }

    //important: return $init!
    return $init;
}
add_filter('tiny_mce_before_init', 'change_mce_options');

add_filter('comment_form_defaults', 'my_comment_defaults');
function my_comment_defaults($defaults) {
    $req = get_option('require_name_email');
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $user = wp_get_current_user();
    $user_identity = $user->display_name;
    $defaults = array(
        'fields' => array(
            'author' => '<div><label for="author">' . __('Name','purepress') . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' . '<input id="author" name="author"  type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' /></div>',
            'url' => '<div><label for="url">' . __('Email','purepress') . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' . '<input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' /></div>',
            'email' => '<div><label for="email">' . __('Url','purepress') . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' . '<input id="url" name="url" type="text"   value="' . esc_attr($commenter['comment_author_url']) . '" size="30"' . $aria_req . ' /></div>'
            ),
        'comment_field' => '<div><label for="comment">' . __('Comment', 'purepress') . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></div>',
        'must_log_in' => '<p class="must-log-in">' . sprintf(__('You must be <a href="%s">logged in</a> to post a comment.'), wp_login_url(apply_filters('the_permalink', get_permalink($post_id)))) . '</p>',
            'logged_in_as' => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>',
        'comment_notes_before' => '<fieldset>',
        'comment_notes_after' => '</fieldset>',
        'id_form' => 'commentform',
        'id_submit' => 'submit',
        'title_reply' => __('Leave a Comment','purepress'),
        'title_reply_to' => __('Leave a Reply %s','purepress'),
        'cancel_reply_link' => __('Cancel reply','purepress'),
        'label_submit' => __('Comment','purepress'),
        );

return $defaults;
}

/*
 * Custom comments
*/

function purepress_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    switch ($comment->comment_type) :
    case '' :
    ?>
    <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
        <div class="comments">
            <div class="avatar"><?php  echo get_avatar($comment, 50); ?></div>
            <div class="comment-des">
                <div class="comment-by">
                    <strong><?php printf(__('%s ', 'boilerplate'), sprintf('<cite class="fn">%s</cite>', get_comment_author_link())); ?></strong>
                    <span class="reply"><span style="color:#aaa">/ </span><?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?></span>
                    <span class="date"> <?php
                    /* translators: 1: date, 2: time */
                    printf(__('%1$s at %2$s', 'boilerplate'), get_comment_date(), get_comment_time());
                    ?> </span></div>

                    <?php if ($comment->comment_approved == '0') : ?>
                    <em><?php _e('Your comment is awaiting moderation.', 'purepress'); ?></em>
                <?php endif; ?>
                <?php comment_text(); ?>
            </div>
        </article><!-- #comment-##  -->
        <?php
        break;
        case 'pingback' :
        case 'trackback' :
        ?>
        <li class="post pingback">
            <p><?php _e('Pingback:', 'boilerplate'); ?> <?php comment_author_link(); ?><?php edit_comment_link(__('(Edit)', 'boilerplate'), ' '); ?></p>
            <?php
            break;
            endswitch;
        }

    /**
     * Collects our theme options
     *
     * @return array
     */
    function purepress_get_global_options() {

        $purepress_option = array();

        $purepress_option = get_option('purepress_options');

        return $purepress_option;
    }

    /**
     * Call the function and collect in variable
     *
     * Should be used in template files like this:
     * <?php echo $purepress_option['purepress_txt_input']; ?>
     *
     * Note: Should you notice that the variable ($purepress_option) is empty when used in certain templates such as header.php, sidebar.php and footer.php
     * you will need to call the function (copy the line below and paste it) at the top of those documents (within php tags)!
     */
    $purepress_option = purepress_get_global_options();




/* ----------------------------------------------------- */
/* Work Custom Post Type */
/* ----------------------------------------------------- */


add_action( 'init', 'register_cpt_portfolio' );

function register_cpt_portfolio() {

    $labels = array(
        'name' => __( 'Portfolio','purepress'),
        'singular_name' => __( 'Portfolio','purepress'),
        'add_new' => __( 'Add New','purepress' ),
        'add_new_item' => __( 'Add New Work','purepress' ),
        'edit_item' => __( 'Edit Work','purepress'),
        'new_item' => __( 'New Work','purepress'),
        'view_item' => __( 'View Work','purepress'),
        'search_items' => __( 'Search Portfolio','purepress'),
        'not_found' => __( 'No portfolio found','purepress'),
        'not_found_in_trash' => __( 'No works found in Trash','purepress'),
        'parent_item_colon' => __( 'Parent work:','purepress'),
        'menu_name' => __( 'Portfolio','purepress'),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'description' => 'Display your works by filters',
        'supports' => array( 'title', 'editor', 'excerpt', 'revisions', 'thumbnail' ),

        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,

        //'menu_icon' => TEMPLATE_URL . 'work.png',
        'show_in_nav_menus' => false,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'portfolio', $args );
}

/* ----------------------------------------------------- */
/* Filter Taxonomy */
/* ----------------------------------------------------- */

add_action( 'init', 'register_taxonomy_filters' );

function register_taxonomy_filters() {

    $labels = array(
        'name' => __( 'Filters', 'purepress' ),
        'singular_name' => __( 'Filter', 'purepress' ),
        'search_items' => __( 'Search Filters', 'purepress' ),
        'popular_items' => __( 'Popular Filters', 'purepress' ),
        'all_items' => __( 'All Filters', 'purepress' ),
        'parent_item' => __( 'Parent Filter', 'purepress' ),
        'parent_item_colon' => __( 'Parent Filter:', 'purepress' ),
        'edit_item' => __( 'Edit Filter', 'purepress' ),
        'update_item' => __( 'Update Filter', 'purepress' ),
        'add_new_item' => __( 'Add New Filter', 'purepress' ),
        'new_item_name' => __( 'New Filter', 'purepress' ),
        'separate_items_with_commas' => __( 'Separate Filters with commas', 'purepress' ),
        'add_or_remove_items' => __( 'Add or remove Filters', 'purepress' ),
        'choose_from_most_used' => __( 'Choose from the most used Filters', 'purepress' ),
        'menu_name' => __( 'Filters', 'purepress' ),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => false,
        'show_ui' => true,
        'show_tagcloud' => false,
        'hierarchical' => true,

        'rewrite' => true,
        'query_var' => true
    );

    register_taxonomy( 'filters', array('portfolio'), $args );
}


/*
 * Adds terms from a custom taxonomy to post_class
 */
add_filter( 'post_class', 'theme_t_wp_taxonomy_post_class', 10, 3 );

function theme_t_wp_taxonomy_post_class( $classes, $class, $ID ) {
    $taxonomy = 'filters';
    $terms = get_the_terms( (int) $ID, $taxonomy );
    if( !empty( $terms ) ) {
        foreach( (array) $terms as $order => $term ) {
            if( !in_array( $term->slug, $classes ) ) {
                $classes[] = $term->slug;
            }
        }
    }
    return $classes;
}

/* ----------------------------------------------------- */
/* EOF */




/*
Plugin Name: OptionTree Attachments Checkbox
 ----------------------------------------------------- */



function ot_type_attachments_checkbox( $args = array() ) {
    /* turns arguments array into variables */
    extract( $args );
    global $post;

    $current_post_id = $post->ID;

    /* verify a description */
    $has_desc = $field_desc ? true : false;

    /* format setting outer wrapper */
    echo '<div class="format-setting type-post_attachments_checkbox type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

    /* description */
    echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

    /* format setting inner wrapper */
    echo '<div class="format-setting-inner">';

    /* setup the post types */
    $post_type = isset( $field_post_type ) ? explode( ',', $field_post_type ) : array( 'post' );
    global $pagenow;
    if($pagenow == 'themes.php' ) {
        $args = array(
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'post_mime_type' => 'image',
            'posts_per_page' => '-1',
            'order' => 'ASC',
            'orderby' => 'menu_order'
            );
    } else {
        $args = array(
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'post_parent' => $current_post_id,
            'post_mime_type' => 'image',
            'posts_per_page' => '-1',
            'order' => 'ASC',
            'orderby' => 'menu_order'
            );
    }

    /* query posts array */
    $query = new WP_Query( $args  );

    /* has posts */
    if ( $query->have_posts() ) {
        $count = 0;
        echo '<input id="this_field_id" type="hidden" value="'. esc_attr( $field_id ).'" />' ;
        echo '<input id="this_field_name" type="hidden" value="'. esc_attr( $field_name ).'" />' ;
        echo '<ul id="option-tree-attachments-list">';
        while ( $query->have_posts() ) {
            $query->the_post();
            echo '<li>';
            $thumbnail = wp_get_attachment_image_src( $query->post->ID, 'thumbnail');
            echo '<img  src="' . $thumbnail[0] . '" alt="' . apply_filters('the_title', $image->post_title). '"/>';
            echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $count ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $count ) . '" value="' . esc_attr( get_the_ID() ) . '" ' . ( isset( $field_value[$count] ) ? checked( $field_value[$count], get_the_ID(), false ) : '' ) . ' class="option-tree-ui-checkbox ' . esc_attr( $field_class ) . '" />';
            echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $count ) . '">' . get_the_title() . '</label>';
            echo '</li>';
            $count++;
        }
        echo "</ul>";
    } else {
        echo '<p>' . __( 'No Posts Found', 'option-tree' ) . '</p>';
    }
    echo '<a title="Refresh images" class="option-tree-attachments-update option-tree-ui-button blue right hug-right" href="#">Update</a>';
    echo '</div>';

    echo '</div>';
}

function ot_type_attachments_ajax_update() {
    if ( !empty( $_POST['post_id'] ) )  {
            $args = array(
                    'post_type' => 'attachment',
                    'post_status' => 'inherit',
                    'post_parent' => $_POST['post_id'],
                    'post_mime_type' => 'image',
                    'posts_per_page' => '-1',
                    'order' => 'ASC',
                    'orderby' => 'menu_order',
                    'exclude'     => get_post_thumbnail_id($_POST['post_id'])
                );


            $return = '';
                /* query posts array */
    $query = new WP_Query( $args  );
    $post_type = isset( $field_post_type ) ? explode( ',', $field_post_type ) : array( 'post' );
    /* has posts */
    if ( $query->have_posts() ) {
        $count = 0;
        $field_id = $_POST['field_id'];
        $field_name = $_POST['field_name'];
        while ( $query->have_posts() ) {
            $query->the_post();
            $return .= '<li>';
            $thumbnail = wp_get_attachment_image_src( $query->post->ID, 'thumbnail');
            $return .=  '<img  src="' . $thumbnail[0] . '" alt="' . apply_filters('the_title', $image->post_title). '"/>';
            $return .=  '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $count ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $count ) . '" value="' . esc_attr( get_the_ID() ) . '" ' . ( isset( $field_value[$count] ) ? checked( $field_value[$count], get_the_ID(), false ) : '' ) . ' class="option-tree-ui-checkbox ' . esc_attr( $field_class ) . '" />';
            $return .=  '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $count ) . '">' . get_the_title() . '</label>';
            $return .=  '</li>';
            $count++;
        }

    } else {
        $return .=  '<p>' . __( 'No Posts Found', 'option-tree' ) . '</p>';
    }
            echo $return;
            exit();
    }
}

add_action( 'wp_ajax_attachments_update', 'ot_type_attachments_ajax_update' );

add_filter( 'gform_form_tag', 'gform_form_tag_autocomplete', 11, 2 );
function gform_form_tag_autocomplete( $form_tag, $form ) {
    if ( is_admin() ) return $form_tag;
    if ( GFFormsModel::is_html5_enabled() ) {
        $form_tag = str_replace( '>', ' autocomplete="off">', $form_tag );
    }
    return $form_tag;
}
add_filter( 'gform_field_content', 'gform_form_input_autocomplete', 11, 5 ); 
function gform_form_input_autocomplete( $input, $field, $value, $lead_id, $form_id ) {
    if ( is_admin() ) return $input;
    if ( GFFormsModel::is_html5_enabled() ) {
        $input = preg_replace( '/<(input|textarea)/', '<${1} autocomplete="off" ', $input ); 
    }
    return $input;
}

////////////////////////////////////////////////////////////////////////////////////////////////////


/* Load LESS */

function childtheme_scripts() {

wp_enqueue_style('less', get_stylesheet_directory_uri() .'/css/style.less');
add_filter('style_loader_tag', 'my_style_loader_tag_function');

wp_enqueue_script('less', get_stylesheet_directory_uri() .'/js/less.min.js', array('jquery'),'2.5.0');

}
add_action('wp_enqueue_scripts','childtheme_scripts', 150);

function my_style_loader_tag_function($tag){   
  return preg_replace("/='stylesheet' id='less-css'/", "='stylesheet/less' id='less-css'", $tag);
}

//////////////////////////////////////////////////////


/* Divider Shortcode */

function DividerShortcode() {
	return '<div class="divider"></div>';
}
add_shortcode('divider', 'DividerShortcode');

function DividerShortcode2() {
	return '<div class="divider-2"></div>';
}
add_shortcode('divider-2', 'DividerShortcode2');

//////////////////////////////////////////////////////


/* Defer Parsing of Java 

if (!(is_admin() )) {
  function defer_parsing_of_js ( $url ) {
    if ( FALSE === strpos( $url, '.js' ) ) return $url;
    if ( strpos( $url, 'jquery.js' ) ) return $url;
    return "$url' defer ";
  }
  add_filter( 'clean_url', 'defer_parsing_of_js', 11, 1 );
}*/

////////////////////////////////////////////////////////////////////////////////////////////////////


/* Remove Query String  */


function _remove_script_version( $src ){
  $parsed = parse_url($src);

  if (isset($parsed['query'])) {
    parse_str($parsed['query'], $qrystr);
    if (isset($qrystr['ver'])) {
      unset($qrystr['ver']); 
    }
    $parsed['query'] = http_build_query($qrystr);
  }
  // return http_build_url($parsed); // elegant but not always available

  $src = '';
  $src .= (!empty($parsed['scheme'])) ? $parsed['scheme'].'://' : '';
  $src .= (!empty($parsed['host'])) ? $parsed['host'] : '';
  $src .= (!empty($parsed['path'])) ? $parsed['path'] : '';
  $src .= (!empty($parsed['query'])) ? '?'.$parsed['query'] : '';

  return $src;
}
add_filter( 'script_loader_src', '_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', '_remove_script_version', 15, 1 );

//////////////////////////////////////////////////////


/* Remove Date from Yoast SEO */

add_filter( 'wpseo_show_date_in_snippet_preview', false); //Returning false on this will prevent the date from showing up in the snippet preview.

//////////////////////////////////////////////////////


/* Remove Dates from SEO on Pages */

function wpd_remove_modified_date(){
    if( is_page() ){
        add_filter( 'the_time', '__return_false' );
        add_filter( 'the_modified_time', '__return_false' );
        add_filter( 'get_the_modified_time', '__return_false' );
        add_filter( 'the_date', '__return_false' );
        add_filter( 'the_modified_date', '__return_false' );
        add_filter( 'get_the_modified_date', '__return_false' );
    }
}
add_action( 'template_redirect', 'wpd_remove_modified_date' );


////////////////////////////////////////////////////////////////////////////////////////////////////


/* Add Field Visibility Section to Gravity Forms */		
	
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );

add_filter("gform_init_scripts_footer", "init_scripts");
function init_scripts() {
return true;
}

/* Increase Date Drop Down Year */

add_filter( 'gform_date_max_year', 'set_max_year' );
function set_max_year( $max_year ) {
    return 2030;
}


////////////////////////////////////////////////////////////////////////////////////////////////////


/* Add Tags Shortcode */	

function shortcode_empty_paragraph_fix( $content ) {

    // define your shortcodes to filter, '' filters all shortcodes
    $shortcodes = array( 'tsu-grey-box', 'tsu-4-col', 'tsu-2-col' );
    
    foreach ( $shortcodes as $shortcode ) {
        
        $array = array (
            '<p>[' . $shortcode => '[' .$shortcode,
            '<p>[/' . $shortcode => '[/' .$shortcode,
            $shortcode . ']</p>' => $shortcode . ']',
            $shortcode . ']<br>' => $shortcode . ']',
            $shortcode . ']<br />' => $shortcode . ']'
        );

        $content = strtr( $content, $array );
    }

    return $content;
}

add_filter( 'the_content', 'shortcode_empty_paragraph_fix' );


////////////////////////////////////////////////////////////////////////////////////////////////////


/* SVG Support */	


add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

  global $wp_version;
  if ( $wp_version !== '4.7.1' ) {
     return $data;
  }

  $filetype = wp_check_filetype( $filename, $mimes );

  return [
      'ext'             => $filetype['ext'],
      'type'            => $filetype['type'],
      'proper_filename' => $data['proper_filename']
  ];

}, 10, 4 );

function cc_mime_types( $mimes ){
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

function fix_svg() {
  echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
add_action( 'admin_head', 'fix_svg' );

////////////////////////////////////////////////////////////////////////////////////////////////////


/* If Modified Since */

add_action('template_redirect', 'last_mod_header');

function last_mod_header($headers) {
     if( is_singular() ) {
            $post_id = get_queried_object_id();
            $LastModified = gmdate("D, d M Y H:i:s \G\M\T", $post_id);
            $LastModified_unix = gmdate("D, d M Y H:i:s \G\M\T", $post_id);
            $IfModifiedSince = false;
            if( $post_id ) {
                if (isset($_ENV['HTTP_IF_MODIFIED_SINCE']))
                    $IfModifiedSince = strtotime(substr($_ENV['HTTP_IF_MODIFIED_SINCE'], 5));  
                if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
                    $IfModifiedSince = strtotime(substr($_SERVER['HTTP_IF_MODIFIED_SINCE'], 5));
                if ($IfModifiedSince && $IfModifiedSince >= $LastModified_unix) {
                    header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
                    exit;
                } 
     header("Last-Modified: " . get_the_modified_time("D, d M Y H:i:s", $post_id) );
                }
        }
}

?>