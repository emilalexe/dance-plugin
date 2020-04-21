<?php
/**
 * Created by PhpStorm.
 * User: Emil
 * Date: 21.04.2020
 * Time: 09:58
 */
?>
<h1 class="dance_comp-bold-h1 dance_comp-title dance_comp-clear-div33">
    <i class="dance_comp-dance_comp dance_comp"></i> <?php
    if ( ! function_exists( 'get_plugins' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $all_plugins = get_plugins( '/dance-comp/');
    $plugin_version = $all_plugins['functions.php']['Version'];
    echo __("Dance Competition").'<sub class="dance_comp-bold-h1-sub"> v '.$plugin_version.'</sub>';

    echo " - ".__('Settings','dance_comp_domain');
    ?>
</h1>