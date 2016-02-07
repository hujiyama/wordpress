
<style type="text/css">



</style>

<?php


  $cat = wp_get_object_terms($post->ID, 'status');
  $cat = $cat[0];
  $cat_term = $cat->term_id;
  $termset = '53';//完了のIDをセット
if ($termset == $cat_term) {


  $my_post1 = array(
         'post_status'   => 'publish',
                        'post_type'   => 'post',
                        'post_id' => $post_id,
                        'html_after_fields' => '',
                        'post_author'  => $user_ID,
                        'submit_value' => '更新新規に変更',
                     'html_before_fields' => '<div id="switch-form">',
                     'html_after_fields' => '</div>',
                 'form' => false,

        'tax_input'  => array( 
      'status' => array( 51)
                ));
   wp_update_post( $my_post1 );
       acf_form($my_post1);


}else{
  $my_post2 = array(
         'post_status'   => 'publish',
                        'post_type'   => 'post',
                        'post_id' => $post_id,
                        'html_after_fields' => '',
                        'post_author'  => $user_ID,
                        'submit_value' => '更新完了に変更します',
                           'html_before_fields' => '<div id="switch-form">',
                     'html_after_fields' => '</div>',
                      'form' => false,
    
        'tax_input'  => array( 
      'status' => array( 53)
                ));
   wp_update_post( $my_post2 );
       acf_form($my_post2);

}
?>
