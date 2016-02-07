<?php
/*
Template Name: custom-index
*/
?>
<?php get_header(); ?>
<div class="contents-wrap">
<div id="main-single">
				<?php 
				if (have_posts()) : // WordPress ループ
					 $loop = new WP_Query( array( 'post_type' => 'gallery', 'posts_per_page' => 0 ) );
                              while ( $loop->have_posts() ) : 
							  $loop->the_post();// 繰り返し処理開始 ?>
						<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							
							<h2><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>
							<p class="post-meta">
								<span class="post-date"><?php echo get_the_date(); ?></span>
								<span class="category">Category - <?php the_category(', ') ?></span>
								<span class="comment-num"><?php comments_popup_link('Comment : 0', 'Comment : 1', 'Comments : %'); ?></span>
							</p>
                            
						<?php global $more;
                              $more = 0;
                              the_content('......&rarr;');
                         ?>
						</div>
                        
                        <br />
					<?php 
					endwhile; // 繰り返し処理終了		
				else : // ここから記事が見つからなかった場合の処理 ?>
						<div class="post">
							<h2>記事はありません</h2>
							<p>お探しの記事は見つかりませんでした。</p>
						</div>
				<?php
				endif;
				?>
				
				<!-- pager -->
				<?php
				if ( $wp_query -> max_num_pages > 1 ) : ?>
					<div class="navigation">
						<div class="alignleft"><?php next_posts_link('&laquo; PREV'); ?></div>
						<div class="alignright"><?php previous_posts_link('NEXT &raquo;'); ?></div>
					</div>
				<?php 
				endif;
				?>
				<!-- /pager	 -->
				
</div><!-- main-single -->
<div id="sidebar">
<h2>最近の投稿/gallery</h2>
<ul>
<?php
query_posts('showposts=10&post_type=gallery');
if (have_posts()) : while (have_posts()) : the_post();
?>
<li><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
<?php the_title(); ?></a></li>
<?php endwhile; endif; ?>
</ul>


<?php the_category( $separator, $parents, $post_id ); ?> 

<?php dynamic_sidebar('sidebar 1'); ?>
</div>
</div><!-- contents-wrap -->
<?php get_footer(); ?>