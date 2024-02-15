<?php

if (!defined("ABSPATH")) {
    exit();
}

function wpdil_admin_scripts($hook_suffix) {

        wp_enqueue_script('datatables', plugins_url('../libraries/datatable/js/jquery.dataTables.min.js', __FILE__), array('jquery'), null, true);
        wp_enqueue_script('datatables_buttons', plugins_url('../libraries/datatable/js/dataTables.buttons.min.js', __FILE__), array('jquery'), null, true);
        wp_enqueue_script('jszip', plugins_url('../libraries/datatable/js/jszip.min.js', __FILE__), array('jquery'), null, true);
        wp_enqueue_script('datatables-buttons-html5', plugins_url('../libraries/datatable/js/buttons.html5.min.js', __FILE__), array('jquery'), null, true);
        wp_enqueue_script('swal-js', plugins_url('../libraries/swal2/sweetalert2.all.min.js', __FILE__), array('jquery'), null, true);
        wp_enqueue_script('image-list', plugins_url('../admin/js/app.js', __FILE__), array('jquery', 'datatables'), null, true);

        wp_enqueue_style('datatables-style', plugins_url('../libraries/datatable/css/dataTables.jqueryui.css', __FILE__));
        wp_enqueue_style('swal-style', plugins_url('../libraries/swal2/sweetalert2.min.css', __FILE__));
        wp_enqueue_style('image-list-style', plugins_url('../admin/css/app.css', __FILE__));


        wp_localize_script('image-list', 'il_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('il_nonce')
        ));



}

add_action('admin_enqueue_scripts', 'wpdil_admin_scripts');