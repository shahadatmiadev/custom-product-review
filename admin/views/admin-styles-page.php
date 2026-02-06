<?php
/**
 * Admin Styles Page View
 * admin/views/admin-styles-page.php
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

 $style_settings = new AMRREV_Style_Settings();
 $style_settings->render_style_settings_page();