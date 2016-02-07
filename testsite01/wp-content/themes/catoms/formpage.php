<?php
/*
Template Name: frontpage
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


<h2>Category：<?php the_category( ', ' ); ?></h2>

		<?php
$cat_info = get_category( $cat );
?>
<?php $catslug = wp_specialchars( $cat_info->slug ); ?>


<?php 
			
			while ( have_posts() ) : the_post(); ?>

				<?php acf_form(array(
						'post_id' => 'new_post',
					'id' => 'acf-form',
					'post_title' => false,
			
					'field_groups' => array('group_556d83105301a',"$catslug"),
				  'post_author'    => $user->ID,
				  'html_before_fields' => '
				  <input type="hidden" name="staffnumber" size="30" id="title" placeholder="Enter title here" >
		          <input type="text" name="post_title" size="30" id="title" placeholder="Enter title here" >',
					  'html_after_fields' => '',
		
		
					'new_post' => array(
						'post_type'		=> 'post',
						'post_status'		=> 'publish',
						'post_author'           => $user_ID,
						'tags_input'     =>  '新規'
					),
					'return'		=> home_url('contact-form-thank-you'),
		'submit_value' => __("提出", 'acf'),
		'uploader' => 'wp'

				)); 
 $post_id = wp_insert_post($my_post);
				?>

<?php endwhile; ?>


<!-- /pager	 -->



</div><!-- main-single -->
<div id="sidebar">

<!-- ここから調整中ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー -->


<?php

// カスタム分類名 全体の情報を渡す
$taxonomy = 'category';

$args = array(
  'orderby' => 'name',
  'parent' => 0,
  'hide_empty' => false,
  'exclude' => '48,68,67'
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
                                    echo '<a href="">'. $term_child->name .'</a>' ;
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
<a href="<?php echo get_category_link( 48 );?>">
<?php echo $cat_name = get_the_category_by_ID( 48 ); ?></a>



<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	
	<select name="cat">
		<option value="">カテゴリーで絞り込み</option>
		<?php
	
		if ( $categories ) {
			foreach ( $categories as $category ) {
				echo '<option value="'.$category->name.'">'.$category->name.'</option>';
				
			}
		}
		?>
	</select>
	<input type="submit" name="submit" value="確定" />
</form>




<!-- ここから調整中ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー -->


</div>
</div><!-- contents-wrap -->
<?php get_footer(); ?>
