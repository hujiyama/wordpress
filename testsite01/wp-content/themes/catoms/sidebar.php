<div id="sidebar">

<?php if( is_user_logged_in() ) : ?>
<?php global $current_user;
      get_currentuserinfo();
      echo '<h2>ユーザー名:'. $current_user->display_name .'</h2>';
?>
<a href="<?php echo wp_logout_url(); ?>">ログアウト</a>
<?php else : ?>
<a href="<?php echo wp_login_url(); ?>">ログイン</a>
ログイン後にリダイレクト<br>
認証後のリダイレクトとは異なる？？
<?php endif; ?>






			
			</div>
			<!-- /sidebar -->