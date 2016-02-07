<!DOCTYPE html>
<html lang="ja"><head>
<!--[if IE]>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<![endif]-->

<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<title><?php wp_title('|', true, 'right'); bloginfo('name');?></title> 

<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_uri(); ?>" /> 

<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico">

<link rel='stylesheet' id='wp-admin-remote-css' href="<?php echo get_template_directory_uri(); ?>/code/style-code.css" type='text/css' media='all'/>

  <link rel="stylesheet" type="text/css" href="<?php echo ltrim(get_stylesheet_directory_uri(), 'htps:'); ?>/datatable/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="<?php echo ltrim(get_stylesheet_directory_uri(), 'htps:'); ?>/datatable/js/jquery-1.8.2.min.js"></script>
  <script type="text/javascript" charset="utf8" src="<?php echo ltrim(get_stylesheet_directory_uri(), 'htps:'); ?>/datatable/js/jquery.dataTables.min.js"></script>
   <script type="text/javascript" charset="utf8" src="<?php echo ltrim(get_stylesheet_directory_uri(), 'htps:'); ?>/js/common.js"></script>

<?php if ( is_singular() ) wp_enqueue_script( "comment-reply" );
wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div id="header" class="clearfix">
<!-- Navigation -->

<div id="toggle"><a href="#"></a></div><!--マルチでバイス用に設定-->
<div class="header-wrap">
<div id="toggle"><a href="#"></a></div><!--マルチでバイス用に設定-->
<div class="header-wrap">
<?php wp_nav_menu( array ( 'theme_location' => 'header-navi' ) ); ?>
<div id="nav-loginbar">
<?php if( is_user_logged_in() ) : ?>
<?php global $current_user;
      get_currentuserinfo();
      echo  $current_user->display_name;
?>
<a href="<?php echo wp_logout_url(); ?>">ログアウト</a>
<?php else : ?>
<a href="<?php echo wp_login_url(); ?>">ログイン</a>
<?php endif; ?>
</div>

</div>
</div><!-- header -->





	<div id="container">		



