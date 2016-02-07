<?php 
add_filter('acf/update_value', 'wp_kses_post', 10, 1);
acf_form_head(); ?>

<?php
/*
Template Name:  testpost
 */


get_header('single'); ?>
<div class="contents-wrap">
<div id="main-single">

<h2><?php $catpost = the_category( ', ' ); 
echo $catpost;
?></h2>

    <?php if ( is_user_logged_in() ) {
    $user_id = get_current_user_id(); //ユーザーID
    $user_data = get_userdata( $user_id );
    $user = wp_get_current_user();
    global $current_user;
    get_currentuserinfo();
    $stuffid =$current_user->display_name ;
    ?>

<script src="<?php echo ltrim(get_stylesheet_directory_uri(), 'htps:'); ?>/js/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
var str = "<?php  echo $stuffid; ?>";
// field_556d83227fdfc 移設用  field_55dc3737f0ba5  テスト
$("#acf-field_55dc3737f0ba5").val(str);
});
</script>

<?php //form　情報
$cat_info = get_category( $cat ); //フォーム内容情報カテゴリー
 $catslug = wp_specialchars( $cat_info->slug ); //カテゴリースラッグ
 $cat_ID = 72;  // カテゴリーID


//カテゴリーdiscriptionにてfield_group設定
$description = category_description($cat_ID);
// //スラッグ名表示
// $catslug = $category->slug;
?>

                <?php acf_form(array(
                    'post_id' => 'new_post',
                    
                    'post_title'    => false,
                    'field_groups' => array('group_556d83105301a ','group_55678698cd12a'),//group_55dc372f0729c テスト用  //group_556d83105301a　　本番用
                   
                    'html_before_fields' => '
                    <input  type="hidden" id="acf-_post_title" class name="acf[_post_title]" value="作業申請" >
                ',
                    'html_after_fields' => '',

                    'new_post' => array(
                    'post_type'     => 'post',
                    'post_status'       => 'publish',
                    // 'post_author'           => $user_ID,
                     'post_author'    => $user->ID,
                    'post_openid'     =>  '新規',
                    'post_category'=> array("$cat_ID"),
                    ),
                     'return' => add_query_arg( 'updated', 'true', get_permalink() ), // return url
                     //'return'     => home_url('contact-form-thank-you'),
                    'submit_value' => __("提出", 'acf'),
                      // 'uploader' => 'basic'

                )); 
                    
                ?>


        

<!-- /pager  -->
<?php
}

else {
echo 'ログインして申請を行って下さい';
}
    ?>


</div><!-- main-single -->
<div id="sidebar">
<?php if ( is_active_sidebar( 'sidebar-1' ) ) : //ウィジット表示
        dynamic_sidebar( 'sidebar-1' );
        else: ?>

        <?php
        endif;
        ?>  
</div>
</div><!-- contents-wrap -->
<?php get_footer(); ?>