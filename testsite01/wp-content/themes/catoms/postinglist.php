<?php acf_form_head(); ?>
<?php
/*
Template Name:  postinglist
 */

get_header('single'); ?>
<div class="contents-wrap">
<div id="main-single">

<h2><?php echo get_the_title(); ?></h2>



<?php if( is_user_logged_in() ) : ?>
<?php global $current_user;
      get_currentuserinfo();
      echo '<h2>ユーザー名:'. $current_user->display_name .'</h2>';
?>
<b style="font-size:30px;"><a href="http://rizm.heteml.jp/testcatoms/category/contactbox">テストページ</a></b>

<h2>申請リスト</h2>
<?php

// カスタム分類名 全体の情報を渡す
$taxonomy = 'category';
$args = array(
  // 'orderby' => 'name',
  'parent' => 0,
  'hide_empty' => false,
  'exclude' => '72' //本番環境は_48
  );

$categories = get_categories( $args );
foreach ( $categories as $category ) {
    // echo $category->term_id; 
//トップカテゴリー ID

        // $cat_id = $cat_id->cat_ID;  ID番号 トップカテゴリー名
        echo '<div class="first-title">';
   echo '<span class="title-description">';
        echo $category->name ;
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
                    // 'order_by' => 'name', // 表示基準選択id,name,slug.....?
                    // 'order' => ASC,// ソート
                    
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
                        echo '<h4>' . $term->name . '</h4>';

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
                                    echo '<label for="name"><a href="'. $term_child_link .'">'. $term_child->name .'</a></label>' ;
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
<a href="<?php echo get_category_link( 72 );　//本番では48------------------- ?>">
<?php echo $cat_name = get_the_category_by_ID( 72 ); 　//本番では48------------------- ?></a>

<?php else : ?>
ログインしてください。
<?php endif; ?>
			
			</div>
			<!-- /sidebar -->

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