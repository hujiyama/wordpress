<?php
/*
Template Name: listpage
*/
?>

<?php get_header(); ?>

<div class="contents-wrap">
<div id="main-pages">
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<h2><?php echo get_the_title(); ?></h2>
 <!-- ログイン判定 -->

<?php if ( is_user_logged_in() ) {
$user_id = get_current_user_id(); //ユーザーID
  $user_data = get_userdata( $user_id );
$user = wp_get_current_user();
?>

<div id="postlisttable">
      <table id="table_id">
    <thead>
        <tr><th>依頼ID</th><th>作業依頼</th><th>提出者</th><th>対応者</th><th>コメント</th><th>提出日</th><th>更新日</th><th>作業期日</th><th>ステータス</th><th><button onclick="myFunction()">更新</button>

<script>
function myFunction() {
    location.reload();
}
</script></th></tr>
    </thead>
    <tbody>

<?php
  $query = new WP_Query( 'author_name=' . $user_data->user_nicename );//データを所持するユーザーを設定
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
            更新日：予定

 <?php the_modified_date('Y/m/d g:i:s A') ?>

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
            </td><td>

<script type="text/javascript">
  <!--
    function check(){
      if (window.confirm('依頼番号："<?php  echo $post->ID; ?>"本当に削除してもよろしいでしょうか？')) {
    alert('削除されました。更新ボタンをクリックしてください');
 
      } else {
        return false;
      }

    }
  // -->

  </script>
  <?php
    if(isset($_POST["$post->ID"])) {
      wp_delete_post( $post->ID, $force_delete = false);
    }
    else {
    }  

?>

<form action="" method="post"　name="frm">
    <input type="submit" name="<?php echo $post->ID;?>" value="削除"   onclick="return check();"/>
</form>



<a href="<?php echo wp_logout_url(); ?>&amp;redirect_to=<?php echo esc_attr($_SERVER['REQUEST_URI']) ?>"></a>

</td></tr>

<?php // The Loop
    }
  }
} else {
echo 'ログインして下さい';
}
        ?>
</tbody>
</table>
</div><!-- postlisttable -->


</div>
    
 <!-- /pager  -->
        
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