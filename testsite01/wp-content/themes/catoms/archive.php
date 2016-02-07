<?php
/**
 * The template for displaying Archive pages.
 */

get_header('single'); ?>
<div class="contents-wrap">
<div id="main-single">



<h2>Archive for <?php the_time('F Y'); ?></h2>


<?php 
				if (have_posts()) : // WordPress ループ
					while (have_posts()) : the_post(); // 繰り返し処理開始 ?>
						<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							
							<h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s'), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
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
<?php get_sidebar(); ?>
</div>
</div><!-- contents-wrap -->
<?php get_footer(); ?>