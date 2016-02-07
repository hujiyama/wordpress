<div id="comment-area">
	<?php 
	if(have_comments()): // コメントがあったら
	?>

		<h3 id="comments">Comment</h3>
	
		<ol class="commets-list">
		<?php wp_list_comments('avatar_size=55'); //コメント一覧を表示 ?>
		</ol>
		
		<div class="comment-page-link">
				<?php paginate_comments_links(); //コメントが多い場合、ページャーを表示 ?>
		</div>
  
</p>

	<?php
	endif;
	
	// ここからコメントフォーム
	
 comment_form($args);
	?>
</div><!-- comment area -->
