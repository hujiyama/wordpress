

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

