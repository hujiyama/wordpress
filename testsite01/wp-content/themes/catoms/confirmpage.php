<?php
/*
Template Name: confirmpage
*/
?>

<?php acf_form_head(); ?>
<?php get_header(); ?>

<div class="contents-wrap">
  <div id="main-pages">


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



  ?>
<?php
$author_id = $post->post_author; // 現在の投稿者ID を変数に代入
$query= 'author=' . $author_id. '&showposts=1'; // クエリを連結
query_posts($query); // クエリを実行
?>
      <?php /* The loop */ ?>
      <?php while ( have_posts() ) : the_post(); ?>

        
        <?php //ここから本文
echo '<h2>';
 echo '依頼ID：';
echo $post->ID; //依頼ID
    $post_cat=get_the_category(); 
   echo '_'.$post_cat[0]->name.'';

   $product_terms = wp_get_object_terms($post->ID, 'status');
if(!empty($product_terms)){
  if(!is_wp_error( $product_terms )){
    foreach($product_terms as $term){
      echo '&nbsp'.$term->name.''; 
    }
  }
}
echo '</h2>';


 ?>
        <div id="signletemplate">
内容を確認し下の提出ボタンをクリック

<?php acf_form( $options ); 

        $options = array(

  /* (string) Unique identifier for the form. Defaults to 'acf-form' */
  'id' => 'acf-form',
  
  /* (int|string) The post ID to load data from and save data to. Defaults to the current post ID. 
  Can also be set to 'new_post' to create a new post on submit */
  'post_id' => false,
  
  /* (array) An array of post data used to create a post. See wp_insert_post for available parameters.
  The above 'post_id' setting must contain a value of 'new_post' */
  'new_post' => false,
  
  /* (array) An array of field group IDs/keys to override the fields displayed in this form */
  'field_groups' => false,
  
  /* (array) An array of field IDs/keys to override the fields displayed in this form */
  'fields' => false,
  
  /* (boolean) Whether or not to show the post title text field. Defaults to false */
  'post_title' => false,
  
  /* (boolean) Whether or not to show the post content editor field. Defaults to false */
  'post_content' => false,
  
  /* (boolean) Whether or not to create a form element. Useful when a adding to an existing form. Defaults to true */
  'form' => true,
  
  /* (array) An array or HTML attributes for the form element */
  'form_attributes' => array(),
  
  /* (string) The URL to be redirected to after the form is submit. Defaults to the current URL with a GET parameter '?updated=true'.
  A special placeholder '%post_url%' will be converted to post's permalink (handy if creating a new post) */
  'return' => '',
  
  /* (string) Extra HTML to add before the fields */
  'html_before_fields' => '',
  
  /* (string) Extra HTML to add after the fields */
  'html_after_fields' => '',
  
  /* (string) The text displayed on the submit button */
  'submit_value' => __("Update", 'acf'),
  
  /* (string) A message displayed above the form after being redirected. Can also be set to false for no message */
  'updated_message' => __("Post updated", 'acf'),
  
  /* (string) Determines where field labels are places in relation to fields. Defaults to 'top'. 
  Choices of 'top' (Above fields) or 'left' (Beside fields) */
  'label_placement' => 'top',
  
  /* (string) Determines where field instructions are places in relation to fields. Defaults to 'label'. 
  Choices of 'label' (Below labels) or 'field' (Below fields) */
  'instruction_placement' => 'label',
  
  /* (string) Determines element used to wrap a field. Defaults to 'div' 
  Choices of 'div', 'tr', 'td', 'ul', 'ol', 'dl' */
  'field_el' => 'div',
  
  /* (string) Whether to use the WP uploader or a basic input for image and file fields. Defaults to 'wp' 
  Choices of 'wp' or 'basic'. Added in v5.2.4 */
  'uploader' => 'wp'
  
);

?>

        </div>



      <?php endwhile; ?>
<?php
} else {
    echo "<h3>ユーザー様の情報URLではないようです。リストからお選びください</h3>";
echo home_url();  
}
?>

<?php else : ?>
ログインして申請願います。
<?php endif; ?>


    </div><!-- #content -->
    <div id="sidebar">
<?php if ( is_active_sidebar( 'sidebar-1' ) ) : //ウィジット表示
        dynamic_sidebar( 'sidebar-1' );
      else: ?>
        
      <?php
      endif;
      ?>  
</div>
  </div><!-- #primary -->

<?php get_footer(); ?>