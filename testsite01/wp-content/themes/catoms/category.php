<?php 

acf_form_head(); 

?>

<style type="text/css">
   #statusterm{display:none;}
  </style>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
	$('ul.acf-checkbox-list > li').each(function(i){
		$(this).attr('id','number' + (i+1));
	});

	$("#number4 > label > input").attr('checked', true);
});
</script>


<?php get_header(); ?>

<div class="contents-wrap">
<div id="main-single">
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
 $cat_ID = wp_specialchars( $cat_info->cat_ID );  // カテゴリーID
 $cat_name = wp_specialchars( $cat_info->name );

//カテゴリーdiscriptionにてfield_group設定
$description = category_description($cat_ID);
// //スラッグ名表示
// $catslug = $category->slug;
// 


?>


<?php //申請ページフォーム数制御
	query_posts('posts_per_page=1'); ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<?php
		$post_id = wp_insert_post( array( 
		'post_title'=>'作業申請', 
		'post_category'=> array("$cat_ID"),
		'post_status'  => 'publish' ,
		) );
		?>

<h2>依頼：ID <?php echo $post_id; ?>_<?php echo  $cat_name;?>　新規</h2>

	<?php


  $cat = wp_get_object_terms($post->ID, 'status');
  $cat = $cat[0];
 $cat_termname = $cat->name;
  $cat_term = $cat->term_id;
  $termset = '78';//完了のIDをセット
echo   $cat_termname;
  if ($termset == $cat_term) {

	$my_post = array(
						'post_status'  => 'publish' ,
						'post_id' => $post_id,
						'post_author' => $user_ID,
						'post_category'=> array("$cat_ID"),

								'new_post' => array(
								'post_name' =>  array( $post_id),
								'post_title'	=> true,
								'validation'  => true,
								// 'post_parent' => $post_id,
								'field_groups' => array('group_556d83105301a ',"$description"),
								'html_before_fields' => '
								<input  type="hidden" id="acf-_post_title" class name="acf[_post_title]" value="作業申請" >',	
								),

						'submit_value'	=> '提出',
						'return' => '%post_url%?foo=bar',

);
   wp_update_post( $my_post);
acf_form($my_post);




}else{}
				?>




依頼IDを発行してから提出となります。
<?php endwhile; ?>

<!-- /pager	 -->
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