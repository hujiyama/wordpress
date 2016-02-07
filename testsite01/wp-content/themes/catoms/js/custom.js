jQuery(document).ready(function($){
$(document).ready(function(){
	  $('#table_id').dataTable( {
	    "bPaginate": true,
	    "bLengthChange": true,
	    "bFilter": false,
	    "bSort": true,
	    "bInfo": true,
	    "bAutoWidth": true
	  });

$('.field_type-text').addClass('mdl-textfield mdl-js-textfield');

$('input').addClass('mdl-textfield__input');

$('label').addClass('mdl-textfield__label');

//tableのドロップダウンのクラスだけ削除
 $('#table_id_length > label').removeClass('mdl-textfield__label'); //特定の追加だけ削除



 // $('#nav_menu-2 > div > ul > li > a').addClass('mdl-navigation__link mdl-textfield__label');


//  $('#nav_menu-2 > div > ul > li > a').text('open');
// var text = $('#nav_menu-2 > div > ul > li > a').text(); // => 'Hello, world'

// // $('#nav_menu-2 > div > ul > li > a > i').insertAfter('open')
//  $('#nav_menu-2 > div > ul > li > a').html('<i　class="mdl-color-text--blue-grey-400 material-icons" role="presentation"></i>');
// var html = $('#nav_menu-2 > div > ul > li > a').html(); // => 'Hello, world'
// //  $('#nav_menu-2 > div > ul > li > a').text('<i　class="mdl-color-text--blue-grey-400 material-icons" role="presentation">home</i>');
// // var html = $('#nav_menu-2 > div > ul > li > a').text(); // => 'Hello, world'

//  $('#nav_menu-2 > div > ul > li > a > i').addClass("mdl-color-text--blue-grey-400 material-icons");


// $("ul li:nth-child(2)").css("background-color", "yellow");
// $('textarea').addClass('mdl-textfield__input');



// //$('.acf-form-submit > input').wrap($('<button>'));//ラップする
// $('.acf-form-submit > input').addClass('mdl-button mdl-js-button mdl-button--raised mdl-button--colored');



// $('select').wrap($('<div>'));//ラップする


