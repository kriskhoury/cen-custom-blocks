jQuery(document).ready(function($){
  $(".state.active").each(function(){
    $(this).hover(function(e){
      var stateAbbr = $(this).attr("id").toLowerCase();
      var popover = $('.state-'+stateAbbr);
      var offset = $(this).offset();
      var x = e.pageX - offset.left;
      var y = e.pageY - offset.top;
      popover.addClass('open');
      $('.popover-shell').addClass('open');

    },function(){
      var stateAbbr = $(this).attr("id").toLowerCase();
      var popover = $('.state-'+stateAbbr);
      popover.removeClass('open');
      $('.popover-shell').removeClass('open');
    })
  });
})

function gotoLink(url){
  window.open(url, '_blank').focus();
  // document.location.href = url;
}