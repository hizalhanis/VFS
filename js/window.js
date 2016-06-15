var contentHeight = 0;

$(document).ready(function(){
                  
                  
                  $(window).resize(function(){
                                   
                                   contentHeight = $(window).height() - ($('#header').outerHeight() + $('#nav').outerHeight() + $('#footer').outerHeight());
                                   $('#main').css('height', contentHeight + 'px');
                                   
                                   var th = $('div.toolbar').outerHeight();
                                   $('div.content-scroll').height(contentHeight - th);
                                   
                                   $('div.questions-container').height(contentHeight - th - 39);
                                   
                                   })
                  
                  setTimeout(function(){
                             $(window).resize();
                             }, 10);
                  
                  
                  $('button.submit-btn').click(function(){
                                               var rel = $(this).attr('rel');
                                               
                                               
                                               $('#'+rel).submit();
                                               })
                  
                  $('input.date').dateEntry({dateFormat: 'ymd/',spinnerImage: base_url+'images/spinnerDefault.png'});
                  
                  $('#dialog button').click(function(){
                                            $('#dialog-overlay').fadeOut(300);
                                            })
                  
                  $('form').submit(function(){
                                   var ok = true;
                                   $(this).find('input.mandatory').each(function(){
                                                                        var value = $(this).val();
                                                                        if (!value.trim()) ok = false;
                                                                        })
                                   
                                   if (!ok){
                                   showDialog('Incomplete form', 'Please fill in all mandatory fields.');
                                   return false;
                                   }
                                   })
                  
                  })



function showDialog(title, text){
    $('#dialog .title').html(title);
    $('#dialog .text').html(text);
    $('#dialog-overlay').fadeIn(300);
}