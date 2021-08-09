jQuery(document).ready(function($){
  var currentX = 0;
  var padding = 24;
  var alwaysShow = 3;
  var totalColumns = $('.slider-wrapper .columns .column').length;
  var columnWidth = Math.round($('.slider-wrapper .columns .column:first-child').width() + padding);
  var totalWidth = Math.round((totalColumns-alwaysShow)*columnWidth);
  $('.slider-button-previous').on('click', function(e){
    e.preventDefault();
    if(currentX < 0){
      currentX = Math.round(currentX + columnWidth);
      $('.slider-wrapper .columns').css({'left':currentX+'px'});
    }
  });
  $('.slider-button-next').on('click', function(e){
    e.preventDefault();
    if(currentX > (totalWidth*-1)){
      currentX = Math.round(currentX - columnWidth);
      $('.slider-wrapper .columns').css({'left':currentX+'px'});
    }
  });
})