<?php
/*
Template Name: newpost
*/
?>
<?php acf_form_head(); ?>
<?php
/**
 * The template for displaying Archive pages.
 */

get_header('single'); ?>
<div class="contents-wrap">
<div id="main-single">



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
$email = $user->get('user_email');
echo '<br>';
echo 'ユーザー名：';echo $user->get('display_name'); // 表示名
echo '<hr>';

 echo '依頼ID：';
echo $post->ID; 
echo $user_data->user_nicename;



?>


<?php
$categoryselect = $_POST["categoryselect"];
$cat_id = $categoryselect;
echo $cat_id;

?>
<?php

//カテゴリIDからカテゴリ情報取得  ID post
$category = get_category($cat_id); //ここにpost
//カテゴリ名表示
//
echo '<h2>';
echo $category->cat_name;
echo '</h2>';
//カテゴリーID取得
$cat_ID = $category->cat_ID;
$description = category_description($cat_ID); 
//スラッグ名表示
$catslug = $category->slug;
?>



<?php
/* 現在のカテゴリ－の取得 */
$cat_now = get_the_category();
$cat_now = $cat_now[0];
/*親カテゴリーのID取得*/
$parent_id = $cat_now->category_parent;
/*現在のカテゴリーID/カテゴリー名取得*/
$now_id = $cat_now->cat_ID; /* カテゴリID */
$now_name = $cat_now->cat_name; /* カテゴリ名 */
?>


<h1>フォームデータの送信</h1>


<?php query_posts('posts_per_page=1'); ?><!-- 申請ページフォーム１ページ -->
<?php
      while ( have_posts() ) : the_post(); ?>

        <?php acf_form(array(
            'post_id' => 'new_post',
          'id' => 'acf-form',
          'post_title'  => false,
          'field_groups' => array('group_556d83105301a',"$description"),//ここテスト中
          'post_author'    => $user->ID,
          'html_before_fields' => '
        <input  type="hidden" id="acf-_post_title" class name="acf[_post_title]" value="作業申請" >
              <input type="hidden" name="post_title" size="30" id="title" placeholder="Enter title here" >',
            'html_after_fields' => '',
    
    
          'new_post' => array(
            'post_type'   => 'post',
            'post_status'   => 'publish',
            'post_author'           => $user_ID,
            'post_term' => array('raw'),//ここテスト中
      
          'post_category'=> array("$cat_ID"),

            // 'post_email'     =>  $email 

          ),
          'return'    => home_url('contact-form-thank-you'),
    'submit_value' => __("提出", 'acf'),
    'uploader' => 'wp'

        )); 
 $post_id = wp_insert_post($post);
        ?>

<?php endwhile; ?>


<!-- /pager  -->
<?php
}

else {
echo 'ログインして下さい';
}
    ?>


</div><!-- main-single -->
<div id="sidebar">
<h2>申請リスト</h2>






<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" >        
<select name="categoryselect"　 size="27"><!-- selectlist -->
<option value="">選択してください</option>

<?php

// カスタム分類名 全体の情報を渡す
$taxonomy = 'category';

$args = array(
  'orderby' => 'name',
  'parent' => 0,
  'hide_empty' => false,
  'exclude' => '72,75,68,67'
  );

$categories = get_categories( $args );
foreach ( $categories as $category ) {
    // echo $category->term_id; 
//トップカテゴリー ID

        // $cat_id = $cat_id->cat_ID;  ID番号 トップカテゴリー名
        echo '<div class="first-title">';
   echo '<span class="title-description">';
    echo'<option type=text disabled="disabled">'.$category->name.'</option>';
        echo '</span>';
          echo '</div>';
                //
                //SET_LOOP _START
                //
                // TOP-A-group_親のID番号設定
                // パラメータ
                $args = array(
                    'parent' => $category->term_id,   // 親タームのみ取得------------------------
                    'pad_counts' => true,// 子タームの投稿数を親タームに含める
                    'hide_empty' => false,// 投稿記事がないタームも取得
                    'order_by' => 'name', // 表示基準選択id,name,slug.....?
                    'order' => ASC,// ソート
                    
                );
                 $term_children = get_term_children( $term->term_id, $taxonomy );

                // カスタム分類のタームのリストを取得
                $terms = get_terms( $taxonomy , $args );

                if ( count( $terms ) != 0 ) {
                    

                    // 親タームのリスト $terms を $term に格納してループ
                    foreach ( $terms as $term ) {

                        // 親タームのURLを取得
                        $term = sanitize_term( $term, $taxonomy );
                        $term_link = get_term_link( $term, $taxonomy );
                        if ( is_wp_error( $term_link ) ) {
                            continue;
                        }

                        // 親タームのURLと名称とカウントを出力
                          echo '<div class="second-group">';
                        echo '<h4>';
                         echo'<option type=text disabled="disabled">'.$term->name.'</option>';
                         echo '</h4>';

                        // 子タームのIDのリストを取得
                        $term_children = get_term_children( $term->term_id, $taxonomy );

                        if( count( $term_children ) != 0 ) {
                            echo '<ul>';
                            // 子タームのIDのリスト $term_children を $term_idに格納してループ
                            foreach ( $term_children as $term_id ) {

                                // 子タームのIDを元に子タームの情報を取得
                                $term_child = get_term_by( 'id', $term_id, $taxonomy );

                                // 子タームのURLを取得
                                $term_child = sanitize_term( $term_child, $taxonomy );
                                $term_child_link = get_term_link( $term_child, $taxonomy );

                                $postid = url_to_postid( $url );
                                if ( is_wp_error( $term_child_link ) ) {
                                    continue;
                                }

                                    // 子タームのURLと名称とカウントを出力
                                    echo '<div id="start_radio" >';
                                    // echo '<a href="'. $term_child_link .'">'. $term_child->name .','. $term_id .'</a>' ;
                                     echo'<option <?php if (isset($categoryselect) && $categoryselect=="'. $term_id .'") echo "selected";?>'. $term_child->name .','. $term_id .'</option>';
                                    echo "</div>\n";
                                    }
                                    echo '</ul>';
                                       echo '</div>';
                        }
                    }
            }// 最終ラップ

}

?>
<!-- 各種お問い合わせ -->
<a href="<?php echo get_category_link( 72 );?>">
<?php echo $cat_name = get_the_category_by_ID( 72 ); ?>,72</a>

    <option <?php if (isset($categoryselect) && $categoryselect=="c") echo "selected";?>>c</option>
</select>
<input type="submit" name="確定" value="確定" />
</form>



      <?php if ( is_active_sidebar( 'sidebar-1' ) ) : 
        dynamic_sidebar( 'sidebar-1' );
      else: ?>

      <?php
      endif;
      ?>  
</div>
</div><!-- contents-wrap -->
<?php get_footer(); ?>