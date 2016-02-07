(function($) {
    var $wp_inline_edit = inlineEditPost.edit;
 
    inlineEditPost.edit = function( id ) {
        $wp_inline_edit.apply( this, arguments );
 
        var $post_id = 0;
        if ( typeof( id ) == 'object' )
            $post_id = parseInt( this.getId( id ) );
 
        if ( $post_id > 0 ) {
            var $edit_row = $( '#edit-' + $post_id );
            var $post_row = $( '#post-' + $post_id );
 
            //今日の天気
            var $memo = $( '.column-memo', $post_row ).html();
            $( ':input[name="memo"]', $edit_row ).val( $memo );
 
            //天気情報を表示（チェックボックス）
            var $display = !! $('.column-display>*', $post_row).attr('checked');
            $( ':input[name="display"]', $edit_row ).attr('checked', $display );
        }
    };
 
})(jQuery);




jQuery(document).ready(function($){
// $(document).ready(function(){
//  $('.field_type-text').addClass('mdl-textfield mdl-js-textfield');

//  $('input').addClass('mdl-textfield__input');

//  $('label').addClass('mdl-textfield__label');

//  $('#table_id_length > label').removeClass('mdl-textfield__label'); //特定の追加だけ削除

//   $('.acf-field').addClass('mdl-grid ');
// $( "div.acf-label" ).before( '<div class="mdl-cell mdl-cell--1-col stretch-spacer"></div>' );

//   $('div.acf-label').addClass('mdl-cell mdl-cell--4-col');
//     $('div.acf-input ').addClass('mdl-cell mdl-cell--6-col');

//     $( "div.acf-input" ).after( '<div class="mdl-cell mdl-cell--1-col stretch-spacer"></div>' );



$("#major-publishing-actions ").wrapAll( "");
// $( ".comment-message " ).before( '<div class="mdl-cell mdl-cell--1-col stretch-spacer "></div>' );
// $( ".comment-message " ).after( '<div class="mdl-cell mdl-cell--1-col stretch-spacer "></div>' );
};



