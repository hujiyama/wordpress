<?php
/*
Template Name: listpage
*/
?>

<?php get_header(); ?>

<div class="contents-wrap">
<div id="main-pages">
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<h2><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>
 <!-- ログイン判定 -->

<?php if ( is_user_logged_in() ) {
	echo 'ログイン中!!　　　';
  echo 'table使用するためページネーションはなし';
$user_id = get_current_user_id(); //ユーザーID
  $user_data = get_userdata( $user_id );
$user = wp_get_current_user();
echo '<hr>';
  echo 'ログインユーザー情報';
  echo '<br>';
echo 'ID番号：'; echo $user->get('ID'); // ID
echo '<br>';
echo 'メールアドレス：';echo $user->get('user_email'); // メールアドレス
echo '<br>';
echo 'ユーザー名：';echo $user->get('display_name'); // 表示名
echo '<hr>';
  $query = new WP_Query( 'author_name=' . $user_data->user_nicename );
  if( $query->have_posts( ) ) {
    while( $query->have_posts( ) ) {
      $query->the_post( );

      //ここからコンテンツ
// echo the_title(); post titleはhidden状態
      $cats = get_the_category(); 
      ?>
<h3>申請内容</h3>
申請カテゴリー：
<a href="<?php echo the_permalink();  ?>"><?php  echo $cats[0]->cat_name; ?></a>
      <?php
echo '<br>';
 echo '依頼ID：';
echo $post->ID; //依頼ID
echo '<br>';
      //記事IDとタクソノミーを指定してタームを取得 ""ステータス""
$product_terms = wp_get_object_terms($post->ID, 'status');
if(!empty($product_terms)){
  if(!is_wp_error( $product_terms )){
    foreach($product_terms as $term){
       echo 'ステータス：';
      echo '<a href="'.get_term_link($term->slug, 'status').'">'.$term->name.'</a>'; 
    }
  }
}
echo '<br>';
echo '提出者名：';echo the_author(); 
echo $user_info->display_name;
echo '<br>';
 echo '対応者：';
$modified = get_field('corresp'); //対応者名表示
if (isset($modified)){
echo $modified = get_field('corresp');
echo '<br>';
 echo '作業期日：';
echo the_field('due-date'); //作業期日
} else {
 echo '未対応'; //未対応
 echo '<br>';
}
?>
<span class="comment-num"><?php comments_popup_link('Comment : 0', 'Comment : 1', 'Comments : %'); ?></span>
<?php
echo '<hr>';
      // The Loop
    }
  }
} else {
echo 'ログインして下さい';
}
		?>




</div>
       <!--   //  ここから修正中ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー↑ -->

        
        <!-- /pager  -->
        
</div><!-- main-single -->
<div id="sidebar">
<?php get_sidebar(); ?>
</div>
</div><!-- contents-wrap -->
<?php get_footer(); ?>