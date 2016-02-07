<?php acf_form_head(); ?>
<?php get_header(); ?>

<div class="drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
    <?php  get_sidebar(); ?> 
</div>
 <main class="mdl-layout__content mdl-color--grey-100">
  <div class="mdl-grid content">
   <div class="main-spacer mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid">



rwwerwer

<?php
global $comment;
// $quickpostcom = get_comment_text($post_id); 
$quickpostcom = get_comments(array('status' => 'approve' ,'number' => 1 , 'post_id' => $post_id));

$current_comment = get_comment_text();

echo $current_comment
?>






  <?php if( is_user_logged_in() ) : 
$user_id = get_current_user_id(); //ユーザーID
  $user_data = get_userdata( $user_id );
$user = wp_get_current_user();
//比較のため情報取得
$user->get('ID'); // ログインのid
$pid = $user->get('ID');
    $post = get_post($post_id);
        if ($post){
                $author = get_userdata($post->post_author); //投稿者のid
                $posauthorid = $author->ID;
        }
//上記値が同じであることを確認、投稿記事の本人以外urlから入るとメッセージ表示
$a = $pid;
$b = $posauthorid;
if ($a == $b) {
// ログインユーザーと投稿者が同じであり表示可能

//ここから本文
echo '<h2>';
 echo '依頼ID：';
echo $post->ID; //依頼ID
    $post_cat=get_the_category(); 
   echo '_'.$post_cat[0]->name.'';

   $product_terms = wp_get_object_terms($post->ID, 'status');
if(!empty($product_terms)){
  if(!is_wp_error( $product_terms )){
    foreach($product_terms as $term){
      
    }
  }
}
echo '</h2>';
  ?>

       <div id="signletemplateafter">
       <div id="postsetafter">

      <?php /* The loop */ ?>
      <?php while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>

     <?php 
        $my_post = array(
                        'post_status'   => 'publish',
                        'post_type'   => 'post',
                        'post_id' => $post_id,
                        'html_after_fields' => '',
                        'post_author'  => $user_ID,
                        'submit_value' => '更新',
                        'form' => true,
                          'validation'  => true,
                        'return' => get_permalink(), // return url
                        'updated_message' => __("受付いたしました", 'acf'),
                         ); 
        acf_form($my_post);
            wp_update_post($my_post);
?>
</div>



<div class="mdl-grid">
  <div class="mdl-cell mdl-cell--1-col stretch-spacer"></div>
    <div class="mdl-cell mdl-cell--10-col comment-message">
  <div class="postaftermess">申請後はコメント欄から行ってください。</div> 
<div id="comment_form">
      <?php
        if( is_singular('post') ) {
        comments_template();
        }
      ?>
</div>
</div>
    <div class="mdl-cell mdl-cell--1-col"></div>
    </div>
      <?php endwhile; ?>
<?php
} else {
    echo "<h3>正しいURLではないようです。リストからお選びください</h3>";
}
?>


ステータス：<br>
<?php
//記事IDとタクソノミーを指定してタームを取得
$product_terms = wp_get_object_terms($post->ID, 'status');
//タームとURLを出力
if(!empty($product_terms)){
  if(!is_wp_error( $product_terms )){
    foreach($product_terms as $term){
      echo $term->term_id; 
    }
  }
}

?>

コメント表示今のnew wp_comment_query：<br>
<!-- コメント表示 -->
<?php
// The Query
$comments_query_args = array( 'post_type'=>'post','number' => 1,'status' => 'approve', 'post_id' => $post_id);
$comments_query = new WP_Comment_Query();     
$recent_comments = $comments_query->query($comments_query_args);

// Comment Loop
if ($recent_comments ) {
  foreach ( $recent_comments as $recent_comment ) {
    echo $recent_comment->comment_content;
  }
} else {
  echo 'No comments found.';
       }
?>



<!-- 新規以外で機能させる -->

<!-- inputへname追加　識別のため -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
jQuery(document).ready(function($){
  $("#commentform > .form-submit > input:nth-child(1)").attr("name","post2");
 });
</script>




<!-- 更新ボタンでコメントするをさせる -->
<script>
jQuery(document).ready(function($){
$(document).ready(function(){
    $(".acf-form-submit >input").click(function(){
  $('input[name="post2"]').parents('#commentform').submit();
  // $('#post').submit();
          $('.acf-form-submit >input').attr('disabled', true);
        $('.acf-form-submit >input').closest('#post').submit();

        // $('#post >input').attr('disabled', true);
        // $('#post >input').closest('form').submit();

         });
   });
});
</script>







<?php else : ?>
ログインして申請願います。
<?php endif; ?>

   </div>
    </div><!-- #content -->
  </div><!-- #primary -->

<?php get_footer(); ?>