<?php get_header(); ?>

<div class="contents-wrap">
<div id="main-pages">
<?php 
if (have_posts()) : // WordPress ループ
while (have_posts()) : the_post(); // 繰り返し処理開始 ?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<h2><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>

<?php the_content(); ?>

<?php 
$args = array(
'before' => '<div class="page-link">',
'after' => '</div>',
'link_before' => '<span>',
'link_after' => '</span>',
);
wp_link_pages($args); ?>

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