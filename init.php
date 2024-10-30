<?php

/*
  Plugin Name:Categorized Simple FAQ Plugin
  Description:In This You Can add FAQ Category wise.
  Version: 2.0
  Author: wamasoftware
  Author URI: http://wamasoftware.com
 */
if (!defined('ABSPATH'))
    exit;
define('SIMPLEFAQ_POST_TYPE', 'simfaq');

class Categorised_Simplefaqinit
{

    function Categorised_simplefaqmenu()
    {
        $settings = new Categorised_SimpleFaqsettings();
        add_submenu_page('edit.php?post_type=' . SIMPLEFAQ_POST_TYPE, __('Settings'), __('Settings'), 'manage_options', 'simfaq-designs', array($settings, 'Categorised_settings'));
    }

    function Categorised_getlistoffaq()
    {
        $getallfaq = new Categorised_Getallfaq();
        add_shortcode('simplefaq', array($getallfaq, 'Categorised_getallfaqshortcode'));
    }

    function Categorised_simplefaq_custom_post_type()
    {
        $labels = array(
            'name' => _x('Simple FAQ', 'Post Type General Name', 'simple-faq'),
            'singular_name' => _x('Simple FAQ', 'Post Type Singular Name', 'simple-faq'),
            'menu_name' => __('Simple FAQ', 'simple-faq'),
            'parent_item_colon' => __('Parent FAQ', 'simple-faq'),
            'all_items' => __('All FAQ', 'simple-faq'),
            'view_item' => __('View FAQ', 'simple-faq'),
            'add_new_item' => __('Add New FAQ', 'simple-faq'),
            'add_new' => __('Add New', 'simple-faq'),
            'edit_item' => __('Edit FAQ', 'simple-faq'),
            'update_item' => __('Update FAQ', 'simple-faq'),
            'search_items' => __('Search FAQ', 'simple-faq'),
            'not_found' => __('Not Found', 'simple-faq'),
            'not_found_in_trash' => __('Not found in Trash', 'simple-faq'), );
        $args = array(
            'label' => __('FAQ', 'simple-faq'),
            'description' => __('Frequently asked questions', 'simple-faq'),
            'labels' => $labels,
            'supports' => array('title', 'editor', 'thumbnail', 'revisions', ),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 25,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'menu_icon' => 'dashicons-warning',
            'capability_type' => 'page',
        );
        register_post_type('simfaq', $args);
    }

    function Categorised_simplefaq_post_taxomomy()
    {
        $labels = array(
            'name' => _x('Category', 'taxonomy general name'),
            'singular_name' => _x('Category', 'taxonomy singular name'),
            'search_items' => __('Search FAQ Category'),
            'all_items' => __('All FAQ Category'),
            'parent_item' => __('Parent FAQ Category'),
            'parent_item_colon' => __('Parent FAQ Category:'),
            'edit_item' => __('Edit FAQ Category'),
            'update_item' => __('Update FAQ Category'),
            'add_new_item' => __('Add New FAQ Category'),
            'new_item_name' => __('New FAQ Category'),
            'menu_name' => __('Category'),
        );
        $args = array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'simfaq'),
        );
        register_taxonomy('simfaq_texonamy', 'simfaq', $args);
    }

    function Categorised_simfaq_cat_manage_columns($theme_columns)
    {
        $new_columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => __('Name'),
            'simfaq_category_shortcode' => __('FAQ Category Shortcode', 'simfaq'),
            'slug' => __('Slug'),
            'posts' => __('Posts')
        );
        return $new_columns;
    }

    function Categorised_simfaq_cat_columns($output, $column_name, $term_id)
    {
        $theme = get_term($term_id, 'simfaq_texonamy');
        switch ($column_name) {
            case 'title':
                echo get_the_title();
                break;

            case 'simfaq_category_shortcode':
                echo '[simplefaq category="' . $term_id . '"]';
                break;
            default:
                break;
        }
        return $output;
    }

    function Categorised_simplefaq_add_js_head()
    {
        wp_enqueue_script("jquery");
        wp_enqueue_script('jqueryuijs', plugins_url("/js/jquery-ui-accordion.js", __FILE__));
        wp_enqueue_style('jqueryuicss', plugins_url("/css/jquery-ui-accordion.css", __FILE__));
    }

    function Categorised_simplefaq_add_js_adminhead()
    {
        wp_enqueue_script('jquerycustjs', plugins_url("/js/custom.js", __FILE__));
        wp_enqueue_style('admincss', plugins_url("/css/style.css", __FILE__));
    }

    // add link on plugin list page
    function plugin_add_category_link($links)
    {
        $gallery_link = "<a href='" . get_admin_url() . "/edit-tags.php?taxonomy=simfaq_texonamy&post_type=simfaq'>Add FAQ Category</a>";
        array_unshift($links, $gallery_link);
        return $links;
    }
    // add link on plugin list page end

    // Change title placeholder
    function my_title_place_holder($title, $post)
    {
        if ($post->post_type == 'simfaq') {
            $my_title = "Enter Question";
            return $my_title;
        }
        return $title;
    }
    // Change title placeholder End
}

$simplefaq = new Categorised_Simplefaqinit();
$plugin_name = plugin_basename(__FILE__);
add_action('init', array($simplefaq, 'Categorised_simplefaq_custom_post_type'), 0);
add_action('init', array($simplefaq, 'Categorised_simplefaq_post_taxomomy'), 0);
add_action('admin_menu', array($simplefaq, 'Categorised_simplefaqmenu'), 0);
add_filter('manage_simfaq_texonamy_custom_column', array($simplefaq, 'Categorised_simfaq_cat_columns'), 10, 3);
add_filter('manage_edit-simfaq_texonamy_columns', array($simplefaq, 'Categorised_simfaq_cat_manage_columns'));
add_action('init', array($simplefaq, 'Categorised_getlistoffaq'), 0);
add_action('wp_footer', array($simplefaq, 'Categorised_simplefaq_add_js_head'), 10);
add_action('admin_enqueue_scripts', array($simplefaq, 'Categorised_simplefaq_add_js_adminhead'));
add_filter('enter_title_here', array($simplefaq, 'my_title_place_holder'), 20, 2);
add_filter("plugin_action_links_" . $plugin_name, array($simplefaq, 'plugin_add_category_link'));
require_once(plugin_dir_path(__FILE__) . '/includes/getallfaq.php');
require_once(plugin_dir_path(__FILE__) . '/includes/settings.php');
include_once(plugin_dir_path(__FILE__) . '/includes/cat_faq.php');
