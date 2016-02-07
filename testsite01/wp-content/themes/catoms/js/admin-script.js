jQuery(document).ready(function($){
  $('.cat-checklist>li:first-child')
    .prepend(
      '<li><label class="selectit"><input value="1" type="radio" name="remove_categories" id="remove_categories"/> 選択した項目を削除</label></li>'
    );
});
