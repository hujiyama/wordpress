<?php

/*------------------------------------
 * 本番には外すこと！！！！！
 ------------------------------------ */

  
// function current_pagehook(){
// global $hook_suffix;
// if( !current_user_can( 'manage_options') ) return;
// echo '<div class="updated"><p>hook_suffix : '.$hook_suffix.'</p></div>';
// }
// add_action('admin_notices', 'current_pagehook');


// ファビコン検討中
// function admin_favicon() {
//   echo '<link rel="shortcut icon" type="image/x-icon" href="'.get_bloginfo('template_url').'/images/admin-favicon.icon" />';
// }
// add_action('admin_head', 'admin_favicon');
// 
// 
// 
// 
// 
//これで設定してある catoms
//https://gist.github.com/webaware/4688802

// if site is set to run on SSL, then force-enable SSL detection!
// 


//
//
//
//
//  //自動保存させない
// function disable_autosave() {
//  wp_deregister_script('autosave');
// }
// add_action( 'wp_print_scripts', 'disable_autosave' );

/* 以下、functions.php に追加 */
add_action( 'comment_post', 'my_comment_post', 15, 1 );
function my_comment_post( $comment_ID ) {
    $comment_data = get_comment( $comment_ID );

    $my_data = array();
    $my_data['ID'] = $comment_data->comment_post_ID;
    $my_data['post_modified'] = $comment_data->comment_date;
    $my_data['post_modified_gmt'] = $comment_data->comment_date_gmt;

    wp_update_post( $my_data );
}




 //casso用session
function register_session(){
    if( !session_id() )
        session_start();
}
add_action('init','register_session');

/*
  get_the_modified_time()の結果がget_the_time()より古い場合はget_the_time()を返す。
  同じ場合はnullをかえす。
  それ以外はget_the_modified_time()をかえす。
*/

function get_mtime($format) {
    $mtime = get_the_modified_time('Ymd');
    $ptime = get_the_time('Ymd');
    if ($ptime > $mtime) {
        return get_the_time($format);
    } elseif ($ptime === $mtime) {
        return null;
    } else {
        return get_the_modified_time($format);
    }
}


 // wp_update_post($postpost);

// Initialize the ajax
add_action('wp_ajax_sm_process_repeat_order', 'sm_process_repeat_order');

function sm_process_repeat_order() {
    
    // Verify a nonce has been set, else don't run the script
    if( !isset( $_POST['sm_nonce'] ) || !wp_verify_nonce($_POST['sm_nonce'], 'sm-nonce') )
        die('Permissions check failed');    

    $author = $_POST['author'];
    $pid = $_POST['pid'];

    $args = array(
        'form_attributes' => array(
            'id' => 'repeat-form'
        ),
        'post_id' => '',
        'field_groups' => array(1784),
        'html_before_fields' => '',
        'html_after_fields' => '
            <input type="hidden" class="text" id="set_repeat_uid" name="set_repeat_uid" value="'.$author.'">
            <input type="hidden" class="text" id="set_repeat_oid" name="set_repeat_oid" value="'.$pid.'">',
        'return' => add_query_arg( 'repeat', '1', get_permalink($pid) ),
        'submit_value' => 'Order'
    );

    $output = acf_form( $args );

    die ($output);

}

 require get_template_directory() . '/lib/postmail.php';
// ここまでーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーここまで

remove_filter('the_content', 'wptexturize');
// コメントテンプレート変更

// カテゴリーの説明文をurlとして使用、その際Pタグが邪魔なので特定削除
remove_filter('term_description','wpautop');

function wpd_comment_notification_text( $notify_message, $comment_id  ){
    // get the current comment and post data
    $comment = get_comment( $comment_id );
    $post = get_post( $comment->comment_post_ID );

// 申請内容カテゴリー名取得
  $post_category = get_the_category($comment->comment_post_ID);
$post_category = $post_category[0];

// コメント投稿ページurl_id取得(ampersand)をさけること
$comid = $comment_id;
$commentpostid = (int) get_comment($comid)->comment_post_ID;



    // don't modify trackbacks or pingbacks
    if( '' == $comment->comment_type ){
        // build the new message text
        $notify_message  = sprintf( __( 'New comment on your post "%s"' ), $post->post_title ) . "\r\n";
        $notify_message .= sprintf( __('提出者 : %1$s'), $comment->comment_author ) . "\r\n";
         $notify_message .= sprintf( __('E-mail : %s'), $comment->comment_author_email ) . "\r\n";
         $notify_message .= sprintf( __('申請内容 :'.$post->ID.' %1$s'), $post_category->cat_name) . "\r\n";
  
        // $notify_message .= sprintf( __('URL    : %s'), $comment->comment_author_url ) . "\r\n";
        $notify_message .= __('Comment: ') . "\r\n" . $comment->comment_content . "\r\n\r\n";
        $notify_message .= __('管理画面からご確認ください ') . "\r\n";
$notify_message .= sprintf( __('申請内容確認url : %s'), get_option('siteurl')."/wp-admin/post.php?post=$commentpostid&action=edit" ) . "\r\n";
        // $notify_message .= sprintf( __('Permalink: %s'), get_comment_link( $comment_id ) ) . "\r\n";

        if ( user_can( $post->post_author, 'edit_comment', $comment_id ) ) {
            if ( EMPTY_TRASH_DAYS )
                $notify_message .= sprintf( __(''), admin_url("comment.php?action=trash&c=$comment_id") ) . "\r\n";
               // $notify_message .= sprintf( __('Trash it: %s'), admin_url("comment.php?action=trash&c=$comment_id") ) . "\r\n";
            else
               $notify_message .= sprintf( __(''), admin_url("comment.php?action=delete&c=$comment_id") ) . "\r\n";
               // $notify_message .= sprintf( __('Delete it: %s'), admin_url("comment.php?action=delete&c=$comment_id") ) . "\r\n";
            // $notify_message .= sprintf( __('Spam it: %s'), admin_url("comment.php?action=spam&c=$comment_id") ) . "\r\n";
        }
    }
             
       
    // return the notification text
    return $notify_message;
}
add_filter( 'comment_notification_text', 'wpd_comment_notification_text', 20, 2 );



// jsのinclude
function my_enqueue() {
  wp_enqueue_script('my_admin_script', get_bloginfo('template_url') . '/js/admin-script.js', array('jquery'), false, true);
}


// カスタムタクソノミーを作成
// create custom taxonomy
function status_custom_taxonomies() {  
  register_taxonomy(
    'status',
    'post',
    array(
      'hierarchical' => true,
      'label' => 'STATUS',
      'singular_name' => 'STATUS',
      'query_var' => true,
      'rewrite' => true

    )
  );
}  
add_action('init', 'status_custom_taxonomies', 0);



function add_default_term_setting_item() {
    $post_types = get_post_types( array( 'public' => true, 'show_ui' => true ), false );
    if ( $post_types ) {
        foreach ( $post_types as $post_type_slug => $post_type ) {
            $post_type_taxonomies = get_object_taxonomies( $post_type_slug, false );
            if ( $post_type_taxonomies ) {
                foreach ( $post_type_taxonomies as $tax_slug => $taxonomy ) {
                    if ( ! ( $post_type_slug == 'post' && $tax_slug == 'category' ) && $taxonomy->show_ui ) {
                        add_settings_field( $post_type_slug . '_default_' . $tax_slug, $post_type->label . '用' . $taxonomy->label . 'の初期設定' , 'default_term_setting_field', 'writing', 'default', array( 'post_type' => $post_type_slug, 'taxonomy' => $taxonomy ) );
                    }
                }
            }
        }
    }
}
add_action( 'load-options-writing.php', 'add_default_term_setting_item' );
 
 
function default_term_setting_field( $args ) {
    $option_name = $args['post_type'] . '_default_' . $args['taxonomy']->name;
    $default_term = get_option( $option_name );
    $terms = get_terms( $args['taxonomy']->name, 'hide_empty=0' );
    if ( $terms ) : 
?>
    <select name="<?php echo $option_name; ?>">
        <option value="0">設定しない</option>
<?php foreach ( $terms as $term ) : ?>
        <option value="<?php echo esc_attr( $term->term_id ); ?>"<?php echo $term->term_id == $default_term ? ' selected="selected"' : ''; ?>><?php echo esc_html( $term->name ); ?></option>
<?php endforeach; ?>
    </select>
<?php
    else:
?>
    <p><?php echo esc_html( $args['taxonomy']->label ); ?>が登録されていません。</p>
<?php
    endif;
}
 
 
function allow_default_term_setting( $whitelist_options ) {
    $post_types = get_post_types( array( 'public' => true, 'show_ui' => true ), false );
    if ( $post_types ) {
        foreach ( $post_types as $post_type_slug => $post_type ) {
            $post_type_taxonomies = get_object_taxonomies( $post_type_slug, false );
            if ( $post_type_taxonomies ) {
                foreach ( $post_type_taxonomies as $tax_slug => $taxonomy ) {
                    if ( ! ( $post_type_slug == 'post' && $tax_slug == 'category' ) && $taxonomy->show_ui ) {
                        $whitelist_options['writing'][] = $post_type_slug . '_default_' . $tax_slug;
                    }
                }
            }
        }
    }
    return $whitelist_options;
}
add_filter( 'whitelist_options', 'allow_default_term_setting' );
 
 
function add_post_type_default_term( $post_id, $post ) {
    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || $post->post_status == 'auto-draft' ) { return; }
    $taxonomies = get_object_taxonomies( $post, false );
    if ( $taxonomies ) {
        foreach ( $taxonomies as $tax_slug => $taxonomy ) {
            $default = get_option( $post->post_type . '_default_' . $tax_slug );
            if ( ! ( $post->post_type == 'post' && $tax_slug == 'category' ) && $taxonomy->show_ui && $default && ! ( $terms = get_the_terms( $post_id, $tax_slug ) ) ) {
                if ( $taxonomy->hierarchical ) {
                    $term = get_term( $default, $tax_slug );
                    if ( $term ) {
                        wp_set_post_terms( $post_id, array_filter( array( $default ) ), $tax_slug );
                    }
                } else {
                    $term = get_term( $default, $tax_slug );
                    if ( $term ) {
                        wp_set_post_terms( $post_id, $term->name, $tax_slug );
                    }
                }
            }
        }
    }
}
add_action( 'wp_insert_post', 'add_post_type_default_term', 10, 2 );

/*------------------------------------
 * 申請後コメントでユーザー同士がメール通知を受ける
 ------------------------------------*/

 // add_action( 'comment_post', 'my_notify_commenters', 10, 2 );
 // function my_notify_commenters( $comment_id, $comment_approved ) {
 //   $comment = get_comment( $comment_id );
 //   $post = get_post( $comment->comment_post_ID );

 //   global $wpdb;
 //   $email_bcc = $wpdb->get_col( $wpdb->prepare(
 //     "SELECT DISTINCT comment_author_email FROM $wpdb->comments WHERE comment_post_ID = %d AND comment_approved = '1' ORDER BY comment_date",
 //     $post->ID
 //   ));

 //   if ( 1 < count( $email_bcc ) ) {
 //     $email_bcc = array_diff( $email_bcc, array( $comment->comment_author_email ) );

 //     $author = $comment->comment_author;
 //     $blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
 //     $subject = sprintf( '【%1$s】%2$sが「%3$s」にコメント', $blogname, $author, $post->post_title );

 //     $message  = sprintf( '%1$s が「%2$s」にコメントしました。', $author, $post->post_title ) . "\r\n\r\n";
 //     $message .= get_comment_link( $comment_id ) . "\r\n\r\n";
 //     $message .= 'コメント: ' . "\r\n" . strip_tags( $comment->comment_content ) . "\r\n--\r\n";

 //  $headers[] = sprintf( 'To: admin_email', $blogname, get_site_option( '"%1$s" <%2$s>' ) );
 //      $headers[] = sprintf( 'From: "%1$s" <%2$s>', $blogname, get_site_option( 'admin_email' ) );
 //     // $headers[] = sprintf( 'Bcc: %1$s', implode( ',', $email_bcc ) );

 //     @wp_mail( '', $subject, $message, implode( "\n", $headers ) );
 //   }
 // }


// add_action('updated_post_meta', 'updated_send_email');





// // アップデートしてから送信するファンクション
// function updated_send_email( $post_id ) {
//   // If this is just a revision, don't send the email.
//    if ( wp_is_post_revision( $post_id ))
//     return;

//    // if ( wp_is_edit_post( $post_id ));

//    // if ( wp_set_post_terms( $post_id ));
//    else if ( wp_publish_post( $post_id ));
 

//   // ここからget field

// $postid = get_the_ID();
//  $adminurl = get_edit_post_link( $id, $context );

// // $smessage = get_post_meta($post->ID , 'smessage' ,true);
// // $smessageafter = get_post_meta($post->ID , 'smessageafter' ,true);
// // $defaultmess = get_post_meta($post->ID , 'defaultmess' ,true);

// // デフォルトメッセージフォーム
//    global $post;
// // $defaultmess = get_post_meta($post->ID,'defaultmess',true); //using 'true' here is vital
// $defaultmess = get_post_meta($post->ID,'defaultmess',true); //using 'true' here is vital

// // これで最終の値を取得
// // $value = get_post_meta($post->ID, 'defaultmess',false);
// //  $defaultmess = end($value);




// // イメージ
// $post_image = get_field('image');

// // お問い合わせ
// $maintextarea = get_field('textarea');

// // 期日
// $date = DateTime::createFromFormat('Y/m/d', get_field('due-date'));
// // 連絡先（電話）
// $tel = get_field('tel');

// $date = get_field('due-date');
// // extract Y,M,D
// $y = substr($date, 0, 4);
// $m = substr($date, 4, 2);
// $d = substr($date, 6, 2);


// $post_ipv4 = get_field('ipv4');
// $post_ipv6 = get_field('ipv6');

// // スタッフナンバー
// $post_stuff = get_field('staffnumber');

// // カテゴリー名取得
// $cat = get_the_category(); $cat = $cat[0];
// $category =$cat->cat_name;

//  // $categories = get_terms( 'status');
//  //  $categorystatus =$categories->cat_name;
 
//   $term = array_pop(get_the_terms($post->ID, 'status'));
//  $term_p = $term->parent;
//  if ( ! $term_p == 0 ){
//      $term = array_shift(get_the_terms($post->ID, 'status'));
//  }
//  $categorystatus = esc_html($term->name);
//  // ステータス（カテゴリ）からの説明文（メッセージ）
//  $statusdiscription = $term->description;






//  $posttags = get_the_tags();
// if ($posttags) {
//   foreach($posttags as $tag) {
//    $option_tag_tag = $tag->name . ' '; 
//   }
// }
// $subject = "依頼ID：{$postid}"."_{$category}"."/{$categorystatus}";

// $post = get_post($post_id);
// if ($post){
//   $author = get_userdata($post->post_author);
//   $user_email = $author->user_email;
// }

// // CloverAccount
// $cloveraccount = get_field('cloveraccount');

// // Src to Dest
// $field = get_field_object('src_to_dest');
// $value = get_field('src_to_dest');
// $src_to_dest = $field['choices'][ $value ];

// // radio
// $field = get_field_object('radiocheck');
// $value = get_field('radiocheck');
// $label = $field['choices'][ $value ];

// // select
// $field = get_field_object('selectcolor');
// $value = get_field('selectcolor');
// $labelselect = $field['choices'][ $value ];

// // chekbox
//  // $labelcheck = implode(',', get_field('checkbbox'));

//  // $queried_object = get_queried_object();
//  //  $skills = get_field('checkbbox', $queried_object['label']);
//  //    $labelcheck =  implode("_", $skills);

// // mailaddress
// $ccaddress = post_custom('e_mail_cc');
// $mail_address = array('rizm@heteml.jp', $ccaddress, $user_email);



// $message =<<<EOS
// CATOMSからのお知らせ

// {$statusdiscription}

// {$defaultmess}
// ---------------------------------
// 社員番号：{$post_stuff}
// ラヂオボタン：{$label}
// チェックボックス：{$labelcheck}
// セレクト：{$labelselect}
// image：{$post_image}
// 期日：{$date}
// 連絡先（電話）{$tel}
// CloverAccount：{$cloveraccount}
// Src to Dest：{$src_to_dest}
// ipv4：{$post_ipv4}
// ipv6：{$post_ipv6}
// Cc：{$ccaddress}
// 本文：{$maintextarea}
// タグ：{$option_tag_tag}

// 作業依頼内容URL
// 編集画面：{$adminurl}
// IDとPASSが必要です。
// EOS;

// // Send email to admin.
// wp_mail( $mail_address, $subject, $message);
// }
// // 通常はsave_postだが２重送信エラー回避の為、acf/save_post使用
// // 
// // 
// add_filter( 'acf/save_post', 'updated_send_email', 10, 2 );

// // メッセージフォームに文字がある場合も保存前に差し込む
// // add_filter( 'acf/wp_insert_post', 'updated_send_email', 10, 2 );
// add_action('acf/publish_post','updated_send_email', 150);


// 一括処理



// カテゴリーの並びと動き

function lig_wp_category_terms_checklist_no_top( $args, $post_id = null ) {
    $args['checked_ontop'] = false;
    $args['walker'] = new Danda_Category_Checklist();
    return $args;
}
add_action( 'wp_terms_checklist_args', 'lig_wp_category_terms_checklist_no_top' );

/**
 * 選択されたカテゴリーが先頭表示され、カテゴリー階層を無視する現象を調整し、
 * 選択フォームをラジオボタンに変更します
 */
// function filter_wp_terms_checklist_args( $args, $post_id ){
//     $args['checked_ontop'] = false;
//     if($args['taxonomy'] == 'category'){
//         $args['walker'] = new Walker_Category_Radiolist;
//     }
//     return $args;
// }


// 追加テンプレート　上書きカスタム
require_once(ABSPATH . '/wp-admin/includes/template.php');
class Danda_Category_Checklist extends Walker_Category_Checklist{


    var $tree_type = 'category';
    var $db_fields = array ('parent' => 'parent', 'id' => 'term_id'); //TODO: decouple this
 
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent<ul class='children'>\n";
    }
 
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
 
    function start_el( &$output, $category, $depth, $args, $id = 0 ) {

        extract($args);
        if ( empty($taxonomy) )
            $taxonomy = 'category';
 
        if ( $taxonomy == 'category' )
            $name = 'post_category';
        else
            $name = 'tax_input['.$taxonomy.']';

        $class = in_array( $category->term_id, $popular_cats ) ? ' class="popular-category"' : '';

        $output .= "\n<li id='{$taxonomy}-{$category->term_id}'$class>" . '<div class="messagepopup"><label class="selectit " ><input  value="' . $category->term_id . '" type="checkbox" name="'.$name.'[]" id="in-'.$taxonomy.'-' . $category->term_id . '"' . checked( in_array( $category->term_id, $selected_cats ), true, false ) . disabled( empty( $args['disabled'] ), false, false ) . ' />
        ' . esc_html( apply_filters('the_category', $category->name )) . '</label><span class="discription_to">'.term_description( $category->term_id,'status', $category->name ).'</span></div>';
    }
 
    function end_el( &$output, $category, $depth = 0, $args = array() ) {
        $output .= "</li>\n";
    }
}

  

 


// /**
//  * vpm_default_hidden_meta_boxes---------------------------------------------------------------作業中
//  */
// function vpm_default_hidden_meta_boxes( $hidden, $screen ) {
//   // Grab the current post type
//   $post_type = $screen->post_type;

//   // If we're on a 'post'...
//   if ( $post_type == 'post' ) {
//     // Define which meta boxes we wish to hide
//     $hidden = array(
//       '',
//       '',
//     );

//     // Pass our new defaults onto WordPress
//     return $hidden;
//   }

//   // If we are not on a 'post', pass the
//   // original defaults, as defined by WordPress
//   return $hidden;
// }

// add_action( 'default_hidden_meta_boxes', 'vpm_default_hidden_meta_boxes', 10, 2 );



/*------------------------------------
 * 作成者　表示のカスタマイズ
 ------------------------------------*/
// カスタム投稿タイプの記事編集画面にメタボックス（作成者変更）を表示する

/* admin_menu アクションフックでカスタムボックスを定義 */
add_action('admin_menu', 'myplugin_add_custom_box');

/* 投稿ページの "advanced" 画面にカスタムセクションを追加 */
function myplugin_add_custom_box() {
  if( function_exists( 'add_meta_box' )) {
    add_meta_box( 'myplugin_sectionid', __( '作成者', 'myplugin_textdomain' ), 'post_author_meta_box', 'books', 'advanced' );
   }
}


// 提出者
add_action( 'admin_init', 'my_admin_init' );
function my_admin_init() {
  add_meta_box( 'my_meta_box_post', '提出者', 'my_meta_box', 'post' );
}
function my_meta_box( $param ) {
  $post = get_post($post_id);
if ($post){
  $author = get_userdata($post->post_author);
  $display_name = $author->display_name;
}
  // $paramは投稿情報
  echo  $display_name;

}






/*------------------------------------
* 公開ボックスにコメントを入れる
 ------------------------------------ */

// 投稿一覧　一括編集のカテゴリーのみ　checkbox 機能 択一選択　テスト------------------------------------------------------------　作業中
// 
if(strstr($_SERVER['REQUEST_URI'], 'wp-admin/edit.php')) {
    ob_start('one_category_only');
}
function one_category_only($content) {
    $content = str_replace('type="checkbox" name="post_category', 'type="radio" name="post_category', $content);
    return $content;
}

// 下記のフォーム切り替え用js
// 

function my_radio_admin_script(){
    wp_enqueue_script( 'my_radio_admin_script', get_template_directory_uri().'/js/custom.js', array('jquery'));
}
add_action( 'admin_enqueue_scripts', 'my_radio_admin_script' );

/*------------------------------------
* 公開ボックスにコメントを入れる
 ------------------------------------ */
// 対応者からのメッセージ

// add_action('save_post', 'save_smessage');
// function save_smessage($post_id){
//   $my_nonce = isset($_POST['my_nonce']) ? $_POST['my_nonce'] : null;
//   if(!wp_verify_nonce($my_nonce, wp_create_nonce(__FILE__))) {
//     return $post_id;
//   }
//   if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return $post_id; }
//   if(!current_user_can('edit_post', $post_id)) { return $post_id; }
 
//   $data = $_POST['smessage'];
 
//   if(get_post_meta($post_id, 'smessage') == ""){
//     add_post_meta($post_id, 'smessage', $data, true);
//   }elseif($data != get_post_meta($post_id, 'smessage', true)){
//     update_post_meta($post_id, 'smessage', $data);
//   }elseif($data == ""){
//     delete_post_meta($post_id, 'smessage', get_post_meta($post_id, 'smessage', true));
//   }
// }
// add_action('save_post', 'save_smessageafter');
// function save_smessageafter($post_id){
//   $my_nonce = isset($_POST['my_nonce']) ? $_POST['my_nonce'] : null;
//   if(!wp_verify_nonce($my_nonce, wp_create_nonce(__FILE__))) {
//     return $post_id;
//   }
//   if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return $post_id; }
//   if(!current_user_can('edit_post', $post_id)) { return $post_id; }
 
//   $data = $_POST['smessageafter'];
 
//   if(get_post_meta($post_id, 'smessageafter') == ""){
//     add_post_meta($post_id, 'smessageafter', $data, true);
//   }elseif($data != get_post_meta($post_id, 'smessageafter', true)){
//     update_post_meta($post_id, 'smessageafter', $data);
//   }elseif($data == ""){
//     delete_post_meta($post_id, 'smessageafter', get_post_meta($post_id, 'smessageafter', true));
//   }
// }

// add_action('save_post', 'save_defaultmess');
// function save_defaultmess($post_id){
//   $my_nonce = isset($_POST['my_nonce']) ? $_POST['my_nonce'] : null;
//   if(!wp_verify_nonce($my_nonce, wp_create_nonce(__FILE__))) {
//     return $post_id;
//   }
//   if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return $post_id; }
//   if(!current_user_can('edit_post', $post_id)) { return $post_id; }
 
//   $data = $_POST['defaultmess'];
 
//   if(get_post_meta($post_id, 'defaultmess') == ""){
//     add_post_meta($post_id, 'defaultmess', $data, true);
//   }elseif($data != get_post_meta($post_id, 'defaultmess', true)){
//     update_post_meta($post_id, 'defaultmess', $data);
//   }elseif($data == ""){
//     delete_post_meta($post_id, 'defaultmess', get_post_meta($post_id, 'defaultmess', true));
//   }
// }


// メッセージボックス

   // function submitbox_callback() {
   //    global $post;
   //     if ($post->post_type == 'post') { 

       //if you only want to display this on posts     ------------------------------------------------------------　作業中
//          echo '<div class="hndle ui-sortable-handle custom_title">';
//          echo '<input type="hidden" name="defaultmess" value="0" />';

// // メッセージフォーム


//   echo '<div id="defaultmess">
//        <label class="defaultmess" for="defaultmess">対応メッセージ</label><input  type="text" name="defaultmess" size="30" value="" placeholder=" デフォルト"/></div>';

// // ラヂオボタン　切り替え
// // // パラメータ 
// $args = array(
//     // 子タームの投稿数を親タームに含める
//     'pad_counts' => true,
  
//     // 投稿記事がないタームも取得
//     'hide_empty' => false
// );

//           $terms = get_terms( 'status' , $args);
//  if ( ! empty( $terms ) && !is_wp_error( $terms ) ){
//   echo '<div id="statusdiv" class="postbox " >';
//   echo '<input type="hidden" name="tax_input[status][]" value="0" />  ';
//      echo '<ul id="statuschecklist" data-wp-lists="list:status" class="categorychecklist form-no-clear">';
//      foreach ( $terms as $term ) {
//       $checked = (has_term($term->slug, 'status', $post->ID)) ? 'checked="checked"' : '';
//          echo '
//          <li id="status-'. $term->term_id .'" class="popular-category">
// <label class="selectit">
//     <input id="in-status-'. $term->term_id .'" type="radio" onclick="entryChange1();" name="tax_input[status]" value="'. $term->term_id .'" '.$checked.'>'. $term->name . '</label>
//          </li>';
//      }
//      echo '</ul></div>

//      </div> ';
//  }

 //       }
 //   }
 // add_action( 'post_submitbox_misc_actions', 'submitbox_callback' );






/*------------------------------------
 * 編集リストの上にボタン設置
 ------------------------------------*/

 function my_custom_comment_field(){
      add_meta_box( 'trash_box','破棄情報を表示' , 'field_status_trash', 'post',  'main', 'high' );
  }
  add_action( 'wp-admin-edit.php', 'my_custom_comment_field' );
   function field_status_trash(){
echo 'ここにボタン表示';
        }

// /*------------------------------------
//  * カスタムタグ表示area box 宣言 提出情報をget または表示　非表示
//  ------------------------------------*/
   function custom_field(){
       add_meta_box( 'progression_status','作業依頼情報' , 'field_status', 'post',  'side', 'high' );
   }
   add_action( 'add_meta_boxes', 'custom_field' );
  
  
/*---カスタムタグ表示area content  ID------*/
  function field_status(){
    echo '<div id="custom_status_disp">';
      /*--- CATEOGRY------*/  
$cat = get_the_category(); $cat = $cat[0];
$category =$cat->cat_name;
 echo '<div class="title">カテゴリー</div><span class="status-category">'.$category.'</span>';
 
  /*--- CATEOGRY-STATUS------*/ 
$term = array_pop(get_the_terms($post->ID, 'status'));
$term_p = $term->parent;
if ( ! $term_p == 0 ){
    $term = array_shift(get_the_terms($post->ID, 'status'));
} 
 echo '<div class="title">ステータス</div><span class="status-category">'.esc_html($term->name).'</span>';
 

/*--- ID------*/
echo '<div class="title">依頼ID</div><span class="status-id">'.get_the_ID().'</span>';

/*--- POST AUTHOR------*/
 global $post;
  $author = get_userdata($post->post_author);
$post_author_name = $author->display_name;
echo '<div class="title">提出者</div><span class="status-author">'.$post_author_name.'</span>';

/*---POST STATUS------*/
if(mb_strlen($post->post_content, 'utf-8') < 10){



/*--- POST UPDATE------*/
global $post;
$pfx_date = get_the_date($post_id );
 echo '<div class="title">提出日</div><span class="status-update">'.$pfx_date.'</span>';



/*--- POST MAX DATE byACF------*/
 echo '<div class="title">作業期日</div>';
 global $post;
 echo  '<span class="status-dudate">';
echo the_field('due-date');
echo '</span>';

     }else{
          // ステータスが発行されていない場合
          echo '情報を表示できませんでした。更新ボタンを押してください。';
     }
      echo '</div>';// △　custom_status_disp △　
  }

// /*------------------------------------
//  * 既存のタグ(meta_box)を止めて新たに変わるボタン形式を代えてステータスmetabox追加
//  * Add new taxonomy meta box
//  ------------------------------------*/
//  add_action( 'add_meta_boxes', 'myprefix_add_meta_box');
//  function myprefix_add_meta_box() {
//      add_meta_box( 'mytaxonomy_id', 'ステータス','myprefix_mytaxonomy_metabox','post' ,'side','core');
//  }
 
//   function myprefix_mytaxonomy_metabox( $post ) {
//    $posttags = get_the_tags();
// if ($posttags) {
//   foreach($posttags as $tag) {
//    echo '<span class="statusbox_put "> '; 
//     echo $tag->name . ' '; 
//     echo '</sapan> '; 
//   }
// }
//     echo '
// <div id="quick_edit_custom_fujiyama" >
// <input  class="new"　type="radio" name="tax_input[post_tag]"  value="新規" checked="checked" />
// <label><input class="in-correspondence" type="radio" name="tax_input[post_tag]"value="対応中">対応中</label>
// <label><input class="completion"  type="radio" name="tax_input[post_tag]" value="完了">完了</label>
// <label><input class="rejection"  type="radio" name="tax_input[post_tag]" value="棄却">棄却</label>
// </div>';
//   }

/*------------------------------------
 * 管理ツールバー非表示
 ------------------------------------*/
add_filter( 'show_admin_bar', '__return_false' );
function my_cloud($echo = false) {
    if (function_exists('wp_tag_cloud'))
        return wp_tag_cloud();
}

/*------------------------------------
 * 公開ボタンを変更　設置　別に同じ動きをさせる
 ------------------------------------*/

function rename_metaboxes(){
    global $wp_meta_boxes;

    /* 編集　投稿　公開　全てのアクションに反映させるボタン. */
    if(array_key_exists($custom_post_type, $wp_meta_boxes)){
        /* Make a backup copy of the original Meta Boxes. */
        $meta_box['publish'] = $wp_meta_boxes[$custom_post_type]['side']['core']['submitdiv'];
        $meta_box['featured_image'] = $wp_meta_boxes[$custom_post_type]['side']['low']['postimagediv'];

        /* Remove the original Meta Boxes from the Custom Post Type. */
        unset($wp_meta_boxes[$custom_post_type]['side']['core']['submitdiv']);
        unset($wp_meta_boxes[$custom_post_type]['side']['low']['postimagediv']);

        /* Re-label the "Publish" Meta Box. */
        $meta_box['publish']['title'] = 'Group Actions';

        /* Re-label the "Featured Image" Meta Box. */
        $meta_box['featured_image']['title'] = 'Group Image';

        /* Re-add our Meta Boxes to the Custom Post Type. */
        $wp_meta_boxes[$custom_post_type]['side']['high']['submitdiv'] = $meta_box['publish'];
        $wp_meta_boxes[$custom_post_type]['side']['high']['postimagediv'] = $meta_box['featured_image'];
    }

}
add_action('in_admin_header',  'rename_metaboxes');


/*------------------------------------
 * タイトルフォームにプレスホルダー　ユーザーには非表示状態->css
 ------------------------------------ */
function post_title_placeholder_text( $title ){
return $title = '項目を埋めてください。';
}
add_filter( 'enter_title_here', 'post_title_placeholder_text');

/*------------------------------------
 * 追加文章　２　　エディタの下
 ------------------------------------ */

 add_action( 'edit_form_after_title', 'after_title' );
 function after_editor() {//エディタ項目下部
    echo '<p></p>';     
}
 add_action( 'edit_form_after_editor-post-new.php', 'after_editor' );






/*------------------------------------
 * 管理者以外の投稿者'administrator','editor'以外に（Author）申請のみ表示ー設置箇所投稿一覧 投稿者の情報だけ表示
 ------------------------------------ */

// Show only posts and media related to logged in author

 if(is_admin() && !current_user_can('edit_others_posts'))   { 

function exclude_other_posts( $wp_query ) {
    if ( isset( $_REQUEST['post_type'] ) && post_type_exists( $_REQUEST['post_type'] ) ) {
        $post_type = get_post_type_object( $_REQUEST['post_type'] );
        $cap_type = $post_type->cap->edit_other_posts;
    } else {
        $cap_type = 'edit_others_posts';
    }
    if ( is_admin() && $wp_query->is_main_query() && ! $wp_query->get( 'author' ) && ! current_user_can( $cap_type ) ) {
        $user = wp_get_current_user();
        $wp_query->set( 'author', $user->ID );
    }
}
add_action( 'pre_get_posts', 'exclude_other_posts' );
}

/*------------------------------------
 * 管理画面独自のcssを読み込む
 ------------------------------------ */

 if(is_admin('edit_others_posts'))   { 
function wp_custom_admin_css() {
  echo "\n" . '<link rel="stylesheet" type="text/css" href="' .get_bloginfo('template_directory'). '/style/custom-admin-css.css' . '" />' . "\n";
}
add_action('admin_head', 'wp_custom_admin_css', 100);
}


/*------------------------------------
 * admin-edit.phpのページだけstyleを反映させる
 ------------------------------------ */
function my_admin_style() {
    if(get_post_type() === 'post'){
        echo '<style>
         .row-title{display:none!important;}
        </style>'.PHP_EOL;
    }
}
add_action("admin_head-edit.php", "my_admin_style");

/*------------------------------------
 * タイトルの下に依頼番号とカテゴリー
 ------------------------------------ */
 function after_title() {//タイトル項目下部
}

// タイトル下にタイトル２を追加
add_action( 'edit_form_after_title', 'postMetaAfterTitle' );
function postMetaAfterTitle() {
  global $post;
  $metaKey = 'title2';
  if ( empty ( $post ) || 'post' !== get_post_type( $GLOBALS['post'] ) ) {
    return;
  }
  if ( ! $content = get_post_meta( $post->ID, $metaKey, TRUE ) ) {
    $content = '';
  }
    global $postid;
    $postid = get_the_ID();
 $cat = get_the_category(); $cat = $cat[0];
 $category =$cat->cat_name;
 $term = array_pop(get_the_terms($post->ID, 'status'));
$term_p = $term->parent;
if ( ! $term_p == 0 ){
    $term = array_shift(get_the_terms($post->ID, 'status'));
}
 
  echo '<h2 id="message_area">';
  echo '依頼ID：',"".$postid;
  echo '_',"".$category;
  echo ' ',"&nbsp;".esc_html($term->name);
    echo '</h2>';
  printf(
'',
    $metaKey,
    esc_attr( $content )
  );
}
/*------------------------------------
 * メインコンテンツの幅を指定
 ------------------------------------ */

if ( ! isset( $content_width ) ) $content_width = 980;


/*------------------------------------
 * カスタムメニューを有効化
 ------------------------------------ */

add_theme_support( 'menus' );


/*------------------------------------
 * カスタムメニューの「場所」を設定
 ------------------------------------ */

register_nav_menu( 'header-navi', 'ヘッダーのナビゲーション' );


/*------------------------------------
 * サイドバーウィジットを有効化
 ------------------------------------ */

register_sidebar( array(
  'name' => 'サイドバーウィジット-1',
  'id' => 'sidebar-1',
  'description' => 'サイドバーのウィジットエリアです。',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
) );


register_sidebar( array( 'name' => 'サービス仕様') );
register_sidebar( array( 'name' => 'その他のページ用') );
/*------------------------------------
 * ヘッダの余計なタグを無効化
 ------------------------------------ */

remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'wp_generator' ); 


/*------------------------------------
 * ユーザープロフィールの項目の追加
 ------------------------------------ */
function my_user_meta($x){

$x['section'] = '所属部署';
$x['skypeaccount'] = 'skype Account';
$x['gmail'] = 'gmail';

return $x;
}
add_filter('user_contactmethods', 'my_user_meta', 10, 1);


// =================================
// ユーザープロフィールの不要な項目を削除
// =================================
function profile_delete() { ?>
<script type="text/javascript">
  tftn = "table.form-table:nth-of-type";
  jQuery(document).ready(function() {
    jQuery("div#profile-page h3").css("display", "none");//『個人設定』『名前』『連絡先情報』の大タイトルを非表示に
    jQuery(tftn + "(1) tr:nth-child(1)").css("display", "none");//『ビジュアルエディター』を非表示に
    jQuery(tftn + "(1) tr:nth-child(2)").css("display", "none");//『管理画面の配色』を非表示に
    jQuery(tftn + "(1) tr:nth-child(3)").css("display", "none");//『キーボードショートカット』を非表示に
    jQuery(tftn + "(1) tr:nth-child(4)").css("display", "none");//『ツールバー』を非表示に
  // jQuery(tftn + "(3) tr:nth-child(1)").css("display", "none");//『メールアドレス』を非表示に（※この項目は必須です）
    jQuery(tftn + "(3) tr:nth-child(2)").css("display", "none");//『ウェブサイト』を非表示に
    jQuery(tftn + "(4) tr:nth-child(1)").css("display", "none");//『プロフィール情報』を非表示に
   });
</script>
<?php }
add_action( 'show_user_profile', 'profile_delete' );


/* --------------------------------------------
 * ページの編集一覧表示を制御
 * -------------------------------------------- */

function redirect_dashiboard() {
  global $current_user;
  get_currentuserinfo();
  if ( !current_user_can('manage_options') ) {
    switch( true ) {
      case preg_match('/^\/wordpress(\/wp-admin\/edit-pages\.php)(\?[^author][^=]+=[^&]+)?$/', $_SERVER['REQUEST_URI'], $author_url):
      case preg_match('/^\/wordpress(\/wp-admin\/edit-pages\.php)(\?trashed=[\d]&ids=[\d][^&]+)?$/', $_SERVER['REQUEST_URI'], $author_url):
        $redirect_url = get_option('siteurl') . $author_url[1];
        if( $author_url[2] ) {
          $redirect_url .= $author_url[2] . "&author=" . $current_user->ID;
        } else {
          $redirect_url .= "?author=" . $current_user->ID;
        }
        wp_redirect( $redirect_url );
        exit;
        break;
    }
  }
}
add_action( 'init', 'redirect_dashiboard' );

/* --------------------------------------------
 * 破棄したデータをユーザーに見せない管理者は全て見えるリダイレクト
 * -------------------------------------------- */
function fb_redirect_2() {
  
  if ( preg_match('#wp-admin/?(edit.php\?post_status=trash&post_type=post)?$#', $_SERVER['REQUEST_URI']) ) { 
    if ( function_exists('admin_url') ) {
      wp_redirect( admin_url('edit.php?post_status=trash') );
    } else {
      wp_redirect( get_option('siteurl') . '/wp-admin/' . 'edit.php?post_status=trash' );
    }
  }
}
if ( is_admin() )
  add_action( 'admin_menu', 'fb_redirect_2' );



/*------------------------------------
 * ログイン後、編集画面に移動 権限　に限らずedit.php移動
 ------------------------------------*/
// ユーザーのredirect

// 管理者のredirect
function my_login_redirect( $redirect_to, $request, $user ) {
  //is there a user to check?
  global $user;
  if ( isset( $user->roles ) && is_array( $user->roles ) ) {
    //check for admins
   if(is_admin() && !current_user_can('edit_others_posts'))   { 
      // redirect them to the default place 
      return home_url('wp-admin/iedit.php?post_type=post');
    } else {
      return home_url('wp-admin/edit.php?post_type=post');
    }
  } 
}

add_filter( 'login_redirect', 'my_login_redirect', 10, 3 );




// サーチフォームはcssでnoneに設定してあるが機能だけは追加しておく
 function post_id_search_where( $where, $obj )
{
  global $wpdb;
  if( $obj->is_search ) {
    $where = preg_replace(
      "/\(\s*$wpdb->posts\.post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
      "($wpdb->posts.post_title LIKE $1) OR ($wpdb->posts.ID LIKE $1)", $where );
  }
  return $where;
}
add_filter('posts_where', 'post_id_search_where', 10, 2 );



// ログイン状態を保存するにチェック

function login_rememberme_check() { ?>
 <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
 <script>
 $(document).ready(function(){
 $('#rememberme').prop('checked', true);
 });
 </script>
<?php }
add_action( 'login_enqueue_scripts', 'login_rememberme_check' );

/*------------------------------------
//セレクトボタンを入れかえにする　一つだけ押せる　
//設置箇所ー投稿画面のカテゴリのチェックボックス
 ------------------------------------ */

add_filter( 'admin_print_footer_scripts', 'limit_category_select' );
function limit_category_select() {
  ?>
  <script type="text/javascript">
    jQuery(function($) {
      // 投稿画面のカテゴリー選択を制限
      var cat_checklist = $('.categorychecklist input[type=checkbox]');
      cat_checklist.click( function() {
        $(this).parents('.categorychecklist').find('input[type=checkbox]').attr('checked', false);
        $(this).attr('checked', true);
      });
      
      // クイック編集のカテゴリー選択を制限
      var quickedit_cat_checklist = $('.cat-checklist input[type=checkbox]');
      quickedit_cat_checklist.click( function() {
        $(this).parents('.cat-checklist').find('input[type=checkbox]').attr('checked', false);
        $(this).attr('checked', true);
      });
 // // クイック編集のカテゴリー選択を制限
       var bulk_edit_posts_cat_checklist = $('#bulk-edit>.colspanchange>.inline-edit-col-center>.inline-edit-col>.cat-checklist>li:first-child input[type=checkbox]');
     bulk_edit_posts_cat_checklist.click( function() {
         $(this).parents('#bulk-edit>.colspanchange>.inline-edit-col-center>.inline-edit-col>.cat-checklist>li:first-child').find('input[type=checkbox]').attr('checked', false);
     $(this).attr('checked', true);

     });



//      $("#bulk-edit>.colspanchange>.inline-edit-col-center>.inline-edit-col>.cat-checklist").click( function() {
//     var radioVal = $("input[name='tax_input[status][]']:checked").val();
//     alert(radioVal);
// });


// // カテゴリー削除ボタンーステータス
//       $('#bulk-edit>.colspanchange>.inline-edit-col-center>.inline-edit-col>.status-checklist>li:first-child')
//    .before("<b>削除ボタン設置</b>");

   // カテゴリーメッセージ〜アラート 
      $('#bulk-edit>.colspanchange>.inline-edit-col-center>.inline-edit-col>.cat-checklist>li:first-child').before('<p style="padding-top:5px;">カテゴリーは移動できません</p>');
    });

  </script>
 

  <?php
}



/*------------------------------------
//セレクトボタンを入れかえにする　一つだけ押せる　
//設置箇所ー投稿画面のカテゴリのチェックボックス
 ------------------------------------ */

function my_print_footer_scripts() {
echo '<script type="text/javascript">
  //<![CDATA[
  jQuery(document).ready(function($){

    $(".categorychecklist input[type=checkbox]").each(function(){
      $check = $(this);
      var checked = $check.attr("checked") ? \' checked="checked"\' : \'\';
      $(\'<input type="radio" id="\' + $check.attr("id")
        + \'" name="\' + $check.attr("name") + \'"\'
    + checked
  + \' value="\' + $check.val()
  + \'"/>\'
      ).insertBefore($check);
      $check.remove();
    });
  });
  //]]>
  </script>';
}
add_action('admin_print_footer_scripts', 'my_print_footer_scripts', 21);





/*------------------------------------
* パーマリンクを非表示
 ------------------------------------ */

add_filter( 'get_sample_permalink_html', '__return_false' );

/*------------------------------------
* 投稿一覧にID追加
 ------------------------------------ */

function add_posts_columns_postid($columns) {
  $columns['postid'] = 'ID';
  return $columns;
}
function add_posts_columns_postid_row($column_name, $post_id) {
  if( 'postid' == $column_name ) {
    echo $post_id;
  }
}
add_filter( 'manage_posts_columns', 'add_posts_columns_postid' );
add_action( 'manage_posts_custom_column', 'add_posts_columns_postid_row', 10, 2 );


/*------------------------------------
* 投稿関係の表示名変更　サイドバー
 ------------------------------------ */

function change_post_menu_label() {
  global $menu;
  global $submenu;
  $menu[5][0] = '作業';
  $submenu['edit.php'][5][0] = '作業申請一覧';
  $submenu['edit.php'][10][0] = '作業申請2';
  $submenu['edit.php'][16][0] = 'タグ';
  //echo ”;
}
function change_post_object_label() {
  global $wp_post_types;
  $labels = &$wp_post_types['post']->labels;
  $labels->name = '作業申請一覧';
  $labels->singular_name = '作業';
  $labels->add_new = _x('作業申請', '作業申請一覧');
  $labels->add_new_item = '作業申請';
  $labels->edit_item = '作業申請の提出';
  $labels->new_item = '作業申請';
  $labels->view_item = '作業申請を表示';
  $labels->search_items = '検索';
  $labels->not_found = '見つかりませんでした';
  $labels->not_found_in_trash = '見つかりませんでした';
}
add_action( 'init', 'change_post_object_label' );
add_action( 'admin_menu', 'change_post_menu_label' );


// サイドバーメニュー非表示
function remove_admin_menus() {
        global $menu;
        // unsetで非表示にするメニューを指定
       unset($menu[2]);        // ダッシュボード
       // unset($menu[5]);        // 投稿
         remove_submenu_page('edit.php', 'post-new.php');
      //  unset($menu[10]);       // メディア
      //  unset($menu[20]);       // 固定ページ
   unset($menu[25]);       // コメント
      //  unset($menu[60]);       // 外観
      //  unset($menu[65]);       // プラグイン
      //  unset($menu[70]);       // ユーザー
      //  unset($menu[75]);       // ツール
      //  unset($menu[80]);       // 設定
    }
add_action('admin_menu', 'remove_admin_menus');



/*------------------------------------
* wordpress@heteml.jp　　これなに？！ドメインとメールアドがあってないと表示されるときがある
 ------------------------------------ */
add_filter( 'wp_mail_from', 'change_mail_from' );
add_filter( 'wp_mail_from_name', 'change_mail_from_name' );

 function change_mail_from($from_mail) {
   return 'rizm@heteml.jp';
 }
 function change_mail_from_name($from_name) {
  return 'CATOMS';
 }

/*------------------------------------
* WordPress本体のバージョンアップ通知　更新　バージョンアップ表示をOFF
 ------------------------------------ */
add_filter('pre_site_transient_update_core', '__return_zero');
remove_action('wp_version_check', 'wp_version_check');
remove_action('admin_init', '_maybe_update_core');
// プラグイン通知
add_filter('site_option__site_transient_update_plugins', '__return_zero');



/*------------------------------------
* 一覧ページのサーチ機能にstatus（タグ）を追加
 ------------------------------------ */
function add_post_tag_restrict_filter() {

   global $post_type;
    if ( 'post' == $post_type ) {
        ?>
        <select name="status">
            <option value="">ステータス</option>
            <?php
            $terms = get_terms('status');
            foreach ($terms as $term) { ?>
                <option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?>(<?php echo $term->count; ?>)</option>
            <?php } ?>
        </select>

        <?php
    }
}
add_action( 'restrict_manage_posts', 'add_post_tag_restrict_filter' );

 
/*------------------------------------
* 一覧ページでタグを検索機能に乗せる（ステータス）
 ------------------------------------ */
function convert_tag_name2tag_slug() {
    if ( ! isset( $_GET['post_type'] ) ) {
        $post_type = 'post';
    } elseif ( in_array( $_GET['post_type'], get_post_types( array( 'show_ui' => true ) ) ) ) {
        $post_type = $_GET['post_type'];
    } else {
        wp_die( __('Invalid post type') );
    }
     
    if ( ! is_object_in_taxonomy( $post_type, 'post_tag' ) || ! isset( $_GET['tag_name'] ) ) {
        return;
    }
    if ( is_array( $_GET['tag_name'] ) ) {
        $_GET['tag_name'] = implode( ',', $_GET['tag_name'] );
    }
    $tag_name = explode( ',', $_GET['tag_name'] );
    $tag_name = array_map( 'trim', $tag_name );
    if ( $tag_name ) {
        $tags = get_tags( 'hide_empty=0&orderby=slug' );
        $tags_arr = array();
        if ( $tags ) {
            foreach ( $tags as $tag ) {
                $tags_arr[$tag->name] = $tag->slug;
            }
        } else {
            unset( $_GET['tag_name'] );
            return;
        }
        $searh_tags = array();
        $matched_tags = array();
        foreach ( $tag_name as $t_name ) {
            if ( isset( $tags_arr[$t_name] ) ) {
                $searh_tags[] = $tags_arr[$t_name];
                $matched_tags[] = $t_name;
            }
        }
        $searh_tags = implode( ' ', $searh_tags );
// OR 検索にしたい場合は、カンマ繋ぎにする
//      $searh_tags = implode( ',', $searh_tags );
        if ( $searh_tags ) {
            $_GET['tag'] = $searh_tags;
            $_GET['tag_name'] = implode( ',', $matched_tags );
        } else {
            unset( $_GET['tag_name'] );
        }
    }
}
add_action( 'load-edit.php', 'convert_tag_name2tag_slug' );

/*------------------------------------
* admin_menu にドキュメントを追加
 ------------------------------------ */


//メニューに追加する
add_action ( 'admin_menu', 'artist_add_pages' );
function artist_add_pages () {
    add_menu_page('document', 'document', 'manage_options', 'manual', 'manual');
}

/**
 * メニューのリンクurlを書き換える
 *
 */
function add_side_menu_manual() {
    //pdfのurlを設定
    $pdf_url = 'http://rizm.heteml.jp/green';
    ?>
    <script type="text/javascript">
        jQuery( function( $ ) {
            $ ("#toplevel_page_manual a").attr("href","<?php echo $pdf_url; ?>"); //hrefを書き換える
            $ ("#toplevel_page_manual a").attr("target","_blank"); //target blankを追加する
        } );
    </script>
<?php
}
add_action('admin_footer', 'add_side_menu_manual');


/*------------------------------------
* 管理バーのヘルプメニューを非表示にする
 ------------------------------------ */
function my_admin_head(){
 echo '<style type="text/css">#contextual-help-link-wrap{display:none;}</style>';
 }
add_action('admin_head', 'my_admin_head');

/*------------------------------------
* フッターWordPressリンクを非表示に
 ------------------------------------ */
function custom_admin_footer() {
 echo '';
 }
add_filter('admin_footer_text', 'custom_admin_footer');

/*------------------------------------
* iframeのフィルター登録
 ------------------------------------ */
add_filter('content_save_pre','test_save_pre');
function test_save_pre($content){
    global $allowedposttags;
    // iframeとiframeで使える属性を指定する
    $allowedposttags['iframe'] = array('class' => array () , 'src'=>array() , 'width'=>array(),
    'height'=>array() , 'frameborder' => array() , 'scrolling'=>array(),'marginheight'=>array(),
    'marginwidth'=>array());
    return $content;
}



/*------------------------------------
 * ログイン画面　編集
 ------------------------------------*/
function custom_login_logo() {
 echo '<style type="text/css">h1 a { background: url('.get_bloginfo('template_directory').'/images/logo.png) 50% 50% no-repeat !important; }</style>';
 }
add_action('login_head', 'custom_login_logo');
// ログインstyle
function my_custom_login() {
  $files = '<link rel="stylesheet" href="'.get_bloginfo('template_directory').'/style/wp-admin-login.css" />';

  echo $files;
}
add_action( 'login_enqueue_scripts', 'my_custom_login' );
// ログイン画像
function custom_login() { ?>
  <style>
    .login {
      background: url(<?php echo get_stylesheet_directory_uri(); ?>/style/bg.jpg) no-repeat center center;
      background-size: cover;
    }
  </style>
<?php }
add_action( 'login_enqueue_scripts', 'custom_login' );



/*------------------------------------
 * ACF form IP validation
 ------------------------------------*/
//  function my_admin_title_script() {
//    wp_enqueue_script( 'my_admin_title_script', get_template_directory_uri().'/js/zscript.js');
// }
// add_action('admin_head', 'my_admin_title_script');


function my_admin_post_script() {
   wp_enqueue_script( 'my_admin_post_script', get_template_directory_uri().'/js/jquery.input-ip-address-control-1.0.min.js');
}
add_action('admin_head', 'my_admin_post_script');

function my_admin_header_script() {
  echo "
<script>
 jQuery(function($) {
    $(function(){
        $('#ipv4').ipAddress();
        $('#ipv6').ipAddress({v:6});
    });
    });

  </script>
  ".PHP_EOL;
}
add_action('admin_head', 'my_admin_header_script');



// メタボックス　最終対応者
add_action('admin_menu', 'add_corresp');
add_action('save_post', 'save_corresp');
 
function add_corresp(){
     if(function_exists('add_corresp')){
          add_meta_box('corresp1', '対応者', 'insert_corresp', 'post', 'normal', 'high');
     }
}

function insert_corresp(){
     global $post;
     wp_nonce_field(wp_create_nonce(__FILE__), 'my_nonce');
      if(is_admin() && !current_user_can('edit_others_posts'))   {
     echo '<label class="hidden" for="corresp">対応者</label><input class="hidden" type="text" name="corresp" size="30" value="';
  echo $modified = get_field('corresp');
     echo'" />';
 }
 else{

  echo '<label class="hidden" for="corresp">対応者</label><input class="hidden" type="text" name="corresp" size="30" value="';
  $user = wp_get_current_user();
echo $user->get('display_name');
     echo'" />';
 }
     echo '<p>対応者の名前</p>';
       echo '<div class="test">';
      echo $modified = get_field('corresp');
        echo '</div >';
}
function save_corresp($post_id){
  $my_nonce = isset($_POST['my_nonce']) ? $_POST['my_nonce'] : null;
  if(!wp_verify_nonce($my_nonce, wp_create_nonce(__FILE__))) {
    return $post_id;
  }
  if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return $post_id; }
  if(!current_user_can('edit_post', $post_id)) { return $post_id; }
 
  $data = $_POST['corresp'];
 
  if(get_post_meta($post_id, 'corresp') == ""){
    add_post_meta($post_id, 'corresp', $data, true);
  }elseif($data != get_post_meta($post_id, 'corresp', true)){
    update_post_meta($post_id, 'corresp', $data);
  }elseif($data == ""){
    delete_post_meta($post_id, 'corresp', get_post_meta($post_id, 'corresp', true));
  }
}



// // UI専用 php
// require get_template_directory() . '/ui.php';

function save_post_lock( $post_id )
{
    $GLOBALS['acf_save_lock'] = $post_id;
    return $post_id;
}

// 他人のライブラリを非表示

/*
 * メディアの抽出条件にログインユーザーの絞り込み条件を追加する
 */
function display_only_self_uploaded_medias( $wp_query ) {
    global $userdata;
    if ( is_admin() && $wp_query->is_main_query() && $wp_query->get( 'post_type' ) == 'attachment' ) {
        $wp_query->set( 'author', $userdata->ID );
    }
}
add_action( 'pre_get_posts', 'display_only_self_uploaded_medias' );




//カテゴリーに項目追加

add_action ( 'edit_category_form_fields', 'extra_category_fields');
function extra_category_fields( $tag ) {
    $t_id = $tag->term_id;
    $cat_meta = get_option( "cat_$t_id");
?>
<tr class="form-field">
    <th><label for="extra_text">その他テキスト</label></th>
    <td><input type="text" name="Cat_meta[extra_text]" id="extra_text" size="25" value="<?php if(isset ( $cat_meta['extra_text'])) echo esc_html($cat_meta['extra_text']) ?>" /></td>
</tr>
<tr class="form-field">
    <th><label for="upload_image">画像URL</label></th>
    <td>
        <input id="upload_image" type="text" size="36" name="Cat_meta[img]" value="<?php if(isset ( $cat_meta['img'])) echo esc_html($cat_meta['img']) ?>" /><br />
        画像を追加: <img src="images/media-button-other.gif" alt="画像を追加"  id="upload_image_button" value="Upload Image" style="cursor:pointer;" />
    </td>
</tr>
<?php
}

//追加した項目を保存
add_action ( 'edited_term', 'save_extra_category_fileds');
function save_extra_category_fileds( $term_id ) {
    if ( isset( $_POST['Cat_meta'] ) ) {
       $t_id = $term_id;
       $cat_meta = get_option( "cat_$t_id");
       $cat_keys = array_keys($_POST['Cat_meta']);
          foreach ($cat_keys as $key){
          if (isset($_POST['Cat_meta'][$key])){
             $cat_meta[$key] = $_POST['Cat_meta'][$key];
          }
       }
       update_option( "cat_$t_id", $cat_meta );
    }
}





 // ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー




/*------------------------------------
* 
* quick編集   メモ機能
* 
* 
 ------------------------------------ */

function my_posts_columns( $defaults ) {
    $defaults['memo'] = 'メモ';

 
    return $defaults;
}
add_filter( 'manage_posts_columns', 'my_posts_columns' );



function my_posts_custom_column( $column, $post_id ) {
    switch ( $column ) {
        case 'memo':
            $post_meta = get_post_meta( $post_id, 'memo', true );
            if ( $post_meta ) {
                echo $post_meta;
                echo '';
            } else {
                echo ''; //値が無い場合の表示
            }
            break;
       
    }
}
add_action( 'manage_posts_custom_column' , 'my_posts_custom_column', 10, 2 );

function display_my_custom_quickedit( $column_name, $post_type ) {
    static $print_nonce = TRUE;
    if ( $print_nonce ) {
        $print_nonce = FALSE;
        wp_nonce_field( 'quick_edit_action', $post_type . '_edit_nonce' ); //CSRF対策
    }
 
    ?>
    <fieldset class="inline-edit-col-right inline-custom-meta">
        <div class="inline-edit-col column-<?php echo $column_name ?>">
            <label class="inline-edit-group">
                <?php
                switch ( $column_name ) {
                    case 'memo':
                        ?><span class="title">メモ帳</span><textarea name="memo" rows="4" cols="40"></textarea><?php
                        break;
                   
                }
                ?>
            </label>
        </div>
    </fieldset>
<?php
}
add_action( 'quick_edit_custom_box', 'display_my_custom_quickedit', 10, 2 );


function my_admin_edit_foot() {
    global $post_type;
    $slug = 'post'; //他の一覧ページで動作しないように投稿タイプの指定をする
 
    if ( $post_type == $slug ) {
        echo '<script type="text/javascript" src="', get_stylesheet_directory_uri() .'/js/admin_edit.js', '"></script>';
    }
}
add_action('admin_footer-edit.php', 'my_admin_edit_foot');



function save_custom_meta( $post_id ) {
    $slug = 'post'; //カスタムフィールドの保存処理をしたい投稿タイプを指定
 
    if ( $slug !== get_post_type( $post_id ) ) {
        return;
    }
    if ( !current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
 
    $_POST += array("{$slug}_edit_nonce" => '');
    if ( !wp_verify_nonce( $_POST["{$slug}_edit_nonce"], 'quick_edit_action' ) ) {
        return;
    }
 
    if ( isset( $_REQUEST['memo'] ) ) {
        update_post_meta( $post_id, 'memo', $_REQUEST['memo'] );
         wp_update_post($post_id);
    }
 
    //チェックボックスの場合
    if ( isset( $_REQUEST['display'] ) ) {
        update_post_meta($post_id, 'display', TRUE);
        wp_update_post($post_id);
    } else {
        update_post_meta($post_id, 'display', FALSE);
         wp_update_post($post_id);
    }
}
add_action( 'save_post', 'save_custom_meta' );



//メタボックスを開く




