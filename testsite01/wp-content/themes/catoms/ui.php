<?php

/* jQuery の読み込み */
function add_my_scripts() {
    if(is_admin()) return; //管理画面ではスクリプトは読み込まない
    wp_deregister_script( 'jquery'); //デフォルトの jQuery は読み込まない
    //CDN から読み込む
    wp_enqueue_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.4/jquery.min.js', array(), '1.11.4', false);
    wp_enqueue_script( 'jquery-mig', '//cdnjs.cloudflare.com/ajax/libs/jquery-migrate/1.2.1/jquery-migrate.min.js', array(), '1.2.1', false);
    }
    add_action('wp_print_scripts', 'add_my_scripts');


