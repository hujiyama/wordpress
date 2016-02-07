<?php
/*
Template Name: cat-source
*/
?>
<?php get_header(); ?>
<div class="contents-wrap">
<div id="main-single">
		<?php if ( is_user_logged_in() ) {
$user_id = get_current_user_id(); //ユーザーID
echo $user_id;
  $user_data = get_userdata( $user_id );
echo $user_data->user_nicename;

$user = wp_get_current_user();
?>

<div id="postlisttable">
      <table id="table_id2" class="mdl-data-table mdl-js-data-table mdl-data-table__cell--non-numeric">
  
    <thead>
        <tr><th>依頼ID</th><th>作業依頼</th><th>提出者</th><th>対応者</th><th>コメント</th><th>提出日</th><th>更新日</th><th>作業期日</th><th>ステータス</th></tr>
    </thead>
    <tbody>

<?php
$args = array(
   'author'=> $user_id,
    'posts_per_page'=> 5
  );
  $query = new WP_Query($args );//データを所持するユーザーを設定
  if( $query->have_posts( ) ) {
    while( $query->have_posts( ) ) {
      $query->the_post( );
      //ここからコンテンツ
// echo the_title(); post titleはhidden状態
      $cats = get_the_category(); 
      ?>
            <td>
            <?php echo $post->ID;?>
            </td>
            <td>
            <a href="<?php echo the_permalink();  ?>"><?php  echo $cats[0]->cat_name; ?></a>
            </td>
            <td>
            <?php echo the_author();  ?>
            </td>
            <td>
            <?php  $modified = get_field('corresp'); //対応者名表示
            if (isset($modified)){
            echo $modified = get_field('corresp');
            } else {
            echo '未対応'; //未対応
            }?>
            </td>
            <td>
            
            <span class=""><?php comments_popup_link(' 0', ' 1', ' %'); ?></span>
            </td>
            <td>
            <?php global $post;
            $pfx_date = get_the_date($post_id );
            echo $pfx_date; ?>
            </td>
                <td>
 <?php the_modified_date('Y/m/d g:i:s A') ; ?>

            </td>
            <td>
            <?php  global $post;
            echo the_field('due-date'); ?>
            </td>
            <td><?php  $product_terms = wp_get_object_terms($post->ID, 'status');
            if(!empty($product_terms)){
            if(!is_wp_error( $product_terms )){
            foreach($product_terms as $term){
            echo $term->name; 
            }
            }
            }
            ?>
            </td>
 
            </tr>

</tbody>
</table>
-------------------------------------------------<br>

<?php // The Loop
    }
  }

?>
-------------------------------------------------上のループ<br>









<?php
}  else {
echo 'ログインして下さい';
}
        ?>

</div>
</div><!-- contents-wrap -->
<?php get_footer(); ?>