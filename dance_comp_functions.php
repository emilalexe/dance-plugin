<?php
/*
Plugin Name: Dance Competition
Plugin URI: https://www.devprolab.com/wordpress/plugin/dance-competition
Description: Wordpress Plugin for Dance Competition
Author: Emil ALEXE
Version: 1.0
Author URI: http://emil.alexe.xyz
Text Domain: dance_comp_domain
Domain Path: languages
*/

/**
 * MultiLang
 */
function dance_comp_load_textdomain() {
    load_plugin_textdomain( 'dance_comp_domain', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'dance_comp_load_textdomain');

/**
 * Global Debug ON
 */
global $dance_comp_debug;
// $dance_comp_debug = false; //off
$dance_comp_debug = true; //on

if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}
/**
 * Test Mode
 *
 * 1-ON 0-OFF
 */
$GLOBALS["test_mode"] = true; // ON


/**
 * Admin menu setup
 */
function dance_comp_setup_menu(){

    if (empty ($GLOBALS['admin_page_hooks']['dance_comp'])){
        add_menu_page('dance_comp', 'DanceComp', 'manage_options', 'dance_comp', 'dance_comp_init_setup','',58);
        add_submenu_page( 'dance_comp', __('Settings','dance_comp_domani'), __('Settings','dance_comp_domain'), 'manage_options', 'dance_comp', 'dance_comp_init_setup');
    }
    add_submenu_page( 'dance_comp', __('About','dance_comp_domain'), __('About','dance_comp_domain'), 'manage_options', 'dance_comp-add', 'dance_comp_init_about');

}
add_action('admin_menu', 'dance_comp_setup_menu');

/**
 * Admin menu initialization
 */
function dance_comp_init_setup(){
    include ( plugin_dir_path( __FILE__ ).'template/_setup.php');
}
function dance_comp_init_about(){
    include ( plugin_dir_path( __FILE__ ).'template/_about.php');
}
/**
 * Register hooks for install and unistall
 */
register_activation_hook( __FILE__ , 'dance_comp_db_install' );
register_uninstall_hook( __FILE__, 'dance_comp_db_unistall');

/**
 * Install db
 */
global $dance_comp_db_version;
$dance_comp_db_version = '1.0';

/**
 * @param $slug
 * @return bool
 */
function dance_comp_get_page_by_slug($slug) {
    if ($pages = get_pages())
        foreach ($pages as $page)
            if ($slug === $page->post_name) return $page;
    return false;
}

function dance_comp_db_install(){
    global $wpdb;
    global $dance_comp_db_version;

    $table_name = $wpdb->prefix . 'dance_comp';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
          id int(9) NOT NULL AUTO_INCREMENT,
          visible tinyint(1) NOT NULL,
          type varchar(100) NULL,
          min_data int(3) NULL,
          UNIQUE KEY id (id)
          ) $charset_collate;";

    require_once ( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    add_option( 'dance_comp_db_version', $dance_comp_db_version );

    /* add dance_comp page */
    if (! dance_comp_get_page_by_slug('dance-competition')) {
        $dance_comp_page = array(
            'post_type' => 'page',
            'post_name' => 'dance_comp',
            'post_title' => 'Dance Competition',
            'post_content' => '<a href="https://www.devprolab.com/wordpress/plugin/dance-comp/?site='.get_site_url().'" target="_blank"><h1>Dance Competition</h1></a>',
            'post_status' => 'publish',
            'ping_status' => 'open',
            'menu_order' => 999
        );
// TODO https://developer.wordpress.org/reference/functions/wp_insert_post/
        wp_insert_post($dance_comp_page);
    }
}

/**
 * Check for db and install if it is not or it has older version
 */
function dance_comp_update_db_check() {
    global $dance_comp_db_version;
    if ( get_site_option( 'dance_comp_db_version' ) != $dance_comp_db_version ) {
        dance_comp_db_install();
    }
}
add_action( 'plugins_loaded', 'dance_comp_update_db_check' );

/**
 * Unistall db
 */
function dance_comp_db_unistall()
{
    $option_name = 'dance_comp_db_version';

    delete_option($option_name);

    // For site options in multisite
    delete_site_option($option_name);

    //drop a custom db table
    global $wpdb;
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}dance_comp");

}

/**
 *  Add settings link on plugin page
 */
function dance_comp_settings_link($links) {
    $settings_link = '<a href="admin.php?page=dance_comp">'.__('Settings','dance_comp_domain').'</a>';
    array_unshift($links, $settings_link);
    return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'dance_comp_settings_link' );

/**
 * Add dance_comp to post button [deprecated]
 */
add_action('media_buttons', 'dance_comp_top_form_edit',15);
function dance_comp_top_form_edit(  ) {
    echo "<a href='#' id='dance_comp-add-card' class='button'><span class='dance_comp-add-card-button-icon'></span>".__('Add Dance Competition','dance_comp_domain')."</a>";
}

/**
 * Add admin style and script
 */
function load_dance_comp_admin_style_and_script($wp_editor) {
    wp_register_style( 'dance_comp_style',  plugin_dir_url( __FILE__ ) . 'assets/css/style.css', false, '1.0.0' );
    wp_enqueue_style( 'dance_comp_style' );

    wp_register_style( 'dance_comp_admin_css',  plugin_dir_url( __FILE__ ) . 'assets/css/dance_comp_admin_style.css', false, '1.0.0' );
    wp_enqueue_style( 'dance_comp_admin_css' );

    wp_enqueue_script( 'dance_comp_admin_script', plugin_dir_url( __FILE__ ) . 'assets/js/dance_comp_admin_script.js', array('jquery'), false, true);
    wp_localize_script('dance_comp_admin_script', 'plugin_url', array('plugin_url' => plugin_dir_url( __FILE__ )));

}
add_action( 'admin_enqueue_scripts', 'load_dance_comp_admin_style_and_script' );

/**
 * Add scripts and style for frontend
 */
function load_dance_comp_style_and_script() {
    // TODO: add script here
}
add_action( 'wp_enqueue_scripts', 'load_dance_comp_style_and_script' );


/**
 * Add a widget to the dashboard.
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function dance_comp_add_dashboard_widgets() {

    wp_add_dashboard_widget(
        'dance_comp',         // Widget slug.
        'Dance Competition - '.ucfirst(__('mini dashboard','dance_comp_domain')),         // Title.
        'dance_comp_dashboard_widget_function' // Display function.
    );
    // Globalize the metaboxes array, this holds all the widgets for wp-admin

    global $wp_meta_boxes;

    // Get the regular dashboard widgets array
    // (which has our new widget already but at the end)

    $dance_comp_normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

    // Backup and delete our new dashboard widget from the end of the array

    $dance_comp_widget_backup = array( 'dance_comp' => $dance_comp_normal_dashboard['dance_comp'] );
    unset( $dance_comp_normal_dashboard['dance_comp'] );

    // Merge the two arrays together so our widget is at the beginning

    $dance_comp_sorted_dashboard = array_merge( $dance_comp_widget_backup, $dance_comp_normal_dashboard );

    // Save the sorted array back into the original metaboxes

    $wp_meta_boxes['dashboard']['normal']['core'] = $dance_comp_sorted_dashboard;

}
add_action( 'wp_dashboard_setup', 'dance_comp_add_dashboard_widgets' );


function dance_comp_shortcode($atts){
    $a = shortcode_atts(array( 'id' => '' ), $atts);
    $output='<span>'.__('Dance Competition','dance_comp_domain').': 5</span>';
    return $output;
}
add_shortcode( 'dance_comp', 'dance_comp_shortcode' );

/**
 * Functions to register client-side assets (scripts and stylesheets) for the
 * Gutenberg block.
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * @see https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type/#enqueuing-block-scripts
 */
function dance_comp_block_init() {
    /*if ( ! function_exists( 'register_block_type' ) ) {
        // Gutenberg is not active.
        return;
    }*/

    $dir = dirname( __FILE__ );

    $block_js = 'assets\js\dance_comp_admin_block_script.js';
    wp_register_script(
        'dance_comp-block-editor-script',
        plugins_url( $block_js, __FILE__ ),
        array(
            'wp-blocks',
            'wp-i18n',
            'wp-element',
        ),
        filemtime( "$dir/$block_js" )
    );

    $editor_css = 'assets\css\dance_comp_admin_block_style.css';
    wp_register_style(
        'dance_comp-block-editor-style',
        plugins_url( $editor_css, __FILE__ ),
        array(),
        filemtime( "$dir/$editor_css" )
    );

    $style_css = 'assets\css\dance_comp_wp_style.css';
    wp_register_style(
        'dance_comp-block',
        plugins_url( $style_css, __FILE__ ),
        array(),
        filemtime( "$dir/$style_css" )
    );

    register_block_type( 'widgets/dance-comp', array(
        'editor_script' => 'dance_comp-block-editor-script',
        'editor_style'  => 'dance_comp-block-editor-style',
        'style'         => 'dance_comp-block',
    ) );
}
add_action( 'init', 'dance_comp_block_init' );


/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function dance_comp_dashboard_widget_function() {
    echo '<div id="dance_comp-dashboard">';
    echo '<p style="text-align: right;"><a title="'.__('About','dance_comp_domain').'" style="color: #82878c;font-size: 0.8em;" href="' . get_plugin_data( __FILE__ )['PluginURI'] . '">' . ucfirst(get_plugin_data( __FILE__ )['Name']) . " v" . get_plugin_data( __FILE__ )['Version'].'</a></p>';
    echo '</div>';
}

/**
 * SETTINGS actions
 */
function dance_comp_modify_settings(){
    global $dance_comp_debug;
    if( $dance_comp_debug) {
         echo "DEBUG: ";
         print_r($_POST);
    }
    global $wpdb;

    if(isset($_POST["dance_comp"]) && $_POST["dance_comp"] != ""){
        $data = $wpdb->get_row("SELECT * FROM  ".$wpdb->prefix."dance_comp WHERE id = '1';");
        if($data->id == 1){
            if(!isset($_POST['visible'])){
                $_POST['visible'] = 0;
            }
            $wpdb->update(
                $wpdb->prefix."dance_comp",
                array(
                    'visible'         => $_POST['visible'],
                    'type'            => $_POST['type'],
                    'min_data'        => $_POST['min-data']
                ),
                array(   "id" => 1)
            );
        }
        else {
            $wpdb->insert(
                $wpdb->prefix . "dance_comp",
                array(
                    'id' => 1,
                    'visible' => $_POST['visible'],
                    'type' => $_POST['type'],
                    'min_data' => $_POST['min-data']
                )
            );

        }
    }
    else{
        $wpdb->get_row("SELECT * FROM  ".$wpdb->prefix."dance_comp WHERE id = '1';");
    }
    //echo "<meta http-equiv='refresh' content='0'>";
}

/** UPDATE */
add_action('init', 'dance_comp_activate_au');
function dance_comp_activate_au()
{


    if( !class_exists('wp_auto_update') ) {
        require_once('php/wp_auto_update.php');      // File which contains the Class below
        echo '<!-- <p>Class not exist in dance_comp</p> -->';
    }else{
        echo '<!-- <p>Class exist in dance_comp</p> -->';
    }
    $dance_comp_plugin_current_version = '1.0';
    $dance_comp_plugin_remote_path     = 'https://www.devprolab.com/wordpress/plugin/dance-comp/update.php';
    $dance_comp_plugin_slug            = plugin_basename(__FILE__);

    $dance_comp_plugin = new wp_auto_update( $dance_comp_plugin_current_version, $dance_comp_plugin_remote_path, $dance_comp_plugin_slug );

}