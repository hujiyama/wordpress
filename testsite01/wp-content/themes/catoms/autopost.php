<?php
/*
Template Name: autopost
*/
?>
<?php get_header(); ?>

<div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
     <?php  get_sidebar(); ?>
</div>

 <main class="mdl-layout__content mdl-color--grey-100">
  <div class="mdl-grid content">
   <div class=" mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid">
            
 

<h1>........</h1>


<?php 

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}



session_start();

// function session_initctm() {
if (isset($_GET['noheader'])) {
        require_once ABSPATH . 'wp-admin/admin-header.php';
    }
    if (isset($_REQUEST['code'])) {
$code = $_GET['code'];
}
$url_redirect = get_site_url();

        $opt_name_urlafter = 'http://rizm.heteml.jp/testcatoms';  //ここは下記にuser登録時の必要情報 9_27 https://catoms.cvb.io/sso/callback
             $opt_val_urlafter = get_option($opt_name_urlafter); //ここは下記にuser登録時の必要情報 9_27
 $redirectadm = get_site_url() . '/wp-admin';


if (empty($opt_val_urlafter)) {
                $redirectadm = get_site_url() . '/wp-admin';
            } else {
                $redirectadm = $opt_val_urlafter;
            }

            // $json = json_decode($response['body']);
            // $user_email = $json->email;  //ユーザーEmail
            // $user_id = $json->sub;   //ユーザー名
                
                
            $json = json_decode($response['body']);
            $user_email = $json->email;  //ユーザーEmail
            $user_id = $json->sub;   //ユーザー名

$user_email ='info@s-fujiyama.com';
$user_id ='testuser';



//emailがすでに使用されているか？確認
            if (email_exists($user_email)) {
                $user_id = email_exists($user_email);
                wp_set_auth_cookie($user_id);
                update_user_meta($user_id, "catoms_access_token", $access_token);
                wp_redirect($redirectadm);
                exit();
            } else {
              if (!$opt_val_register) {
                    wp_die(_e('Your Linkedin account doesn\'t match any user on this page'), 'Error', array('back_link' => true));
                    exit;
                } else {
          
                    //使用されていないことを確認後ユーザー作成
                    $create = wp_create_user($user_id, generateRandomString(), $user_email);
            if (is_wp_error($create)) {
                        wp_die($create);
                    }
                    //流れてきた情報をアップデート
                    $user_id = email_exists($user_email);
                    wp_set_auth_cookie($user_id);
                    update_user_meta($user_id, "catoms_access_token", $access_token);
                wp_redirect(get_site_url() . '/wp-admin');
                    exit();

                update_user_meta(get_current_user_id(), "reddit_access_token", $access_token);
               
                }


           
    //     if (!is_user_logged_in()) {
    //     //determine WordPress user account to impersonate
    //     $user_login = $user_id; 

    //     //get users password
    //     $user = new WP_User(0, $user_login);
    //     $user_pass = md5($user->user_pass); 
     

    //     //login, set cookies, and set current user
    //     wp_login($user_login, $user_pass, true);
    //     wp_setcookie($user_login, $user_pass, true);
    //     wp_set_current_user($user->ID, $user_login);
    // }
// }

 ?>




</div><!-- main-single -->
</div><!-- contents-wrap -->
<?php get_footer(); ?>