

// JavaScript   ..IE6 ERROR COMMENT..//
$(function () {
	if (typeof window.addEventListener == "undefined" && typeof document.documentElement.style.maxHeight == "undefined") {
		$('body').prepend('<div class="ie6_error">現在、旧式ブラウザをご利用中です。このウェブサイトは、現在ご利用中のブラウザには対応しておりません。バージョンを確認し、アップグレードを行ってください。</div>');
	}
});


// JavaScript   ..Scroll OPEN..//
$(function(){
		//st--スクロール
    $('a.scr[href^="#"]').click(function(event) {
        var id = $(this).attr("href");
        var offset = 60;
        var target = $(id).offset().top - offset;
        $('html, body').animate({scrollTop:target}, 500);
        event.preventDefault();
        return false;
    });
});

	$(function(){
		$("#acMenu dt").on("click", function() {
			$(this).next().slideToggle();
			$(this).toggleClass("active");//追加部分
		});
	});
	
	
