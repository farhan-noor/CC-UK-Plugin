(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
        
        $(document).ready(function(){
            $( "#ccuk_search_form" ).submit(function(){
            var datastring = new FormData(document.getElementById("ccuk_search_form"));
            $.ajax({
                    url: ccuk_public.ajaxurl,
                    type: 'POST',
                    dataType: 'json',
                    data: datastring,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function(){
                        $('#ccuk_form_status').removeClass();
                        $('#ccuk_output').hide();
                        $('#ccuk_form_status').html('Searching . . . . . ');
                        $('#ccuk_form_status').addClass('alert alert-warning');
                        $(".ccuk_search_button").prop('disabled', true);
                        $('#ccuk_output tbody').html('');
                    },
                    success:function(response){
                        $(document).trigger('afterAppSubmit', response); //Custom event  on ajax completiong
                        
                        if(response['success']==true){
                            $('#ccuk_form_status').removeClass();
                            $('#ccuk_output').show();
                            $('#ccuk_form_status').addClass('alert alert-success');
                            $('#ccuk_form_status').html(response['message']);
                            $(".ccuk_search_button").prop('disabled', false);
                            var data = response['data'].CharityList;
                            $.each( data, function(i, item){
                                $('#ccuk_output tbody').append('<tr><td>'+item.CharityName+'</td><td>'+item.RegisteredCharityNumber+'</td></td><td>'+item.RegistrationStatus+'</td></td><td><a href=# data-id="'+item.RegisteredCharityNumber+'">Details</a></td></tr>');
                            });
                        }
                        else if(response['success']==false){
                            $('#ccuk_form_status').removeClass();
                            $('#ccuk_form_status').addClass('alert alert-danger');
                            $('#ccuk_form_status').html(response['error']);
                            $(".ccuk_search_button").prop('disabled', false);
                        }
                        //If response is not jSon.
                        else{
                            $('#ccuk_form_status').addClass('alert alert-danger');
                            $('#ccuk_form_status').html('Something went wrong. Please try again. ');
                            $(".ccuk_search_button").prop('disabled', false);
                        }
                    },
                    error: function(xhr, type, error){
                        $('#ccuk_form_status').removeClass();
                        $('#ccuk_form_status').addClass('alert alert-danger');
                        $('#ccuk_form_status').html('An unexpected error occured with error code: <u>' + xhr.status + " " + xhr.statusText+'</u>. Please try again or contact us for more information.');
                        $(".ccuk_search_button").prop('disabled', false);
                    },
                    // Custom XMLHttpRequest
                    xhr: function () {
                      $('progress').attr({
                              value: 0,
                            });
                      var myXhr = $.ajaxSettings.xhr();
                      if (myXhr.upload) {
                        // For handling the progress of the upload
                        myXhr.upload.addEventListener('progress', function (e) {
                          if (e.lengthComputable) {
                            $('progress').attr({
                              value: e.loaded,
                              max: e.total,
                            });
                          }
                        }, false);
                      }
                      return myXhr;
                    },
            });
            return false;
          });
          
           //Show user detailed info in the popup when table element is clicked
           $('#ccuk_output').on( 'click', 'tr td a', function(event){
               event.preventDefault();
               this.blur(); // Manually remove focus from clicked link.
               
               $('#ccuk-modal').text('Loading . . . .').modal();
               
               var jqxhr = $.post(ccuk_public.ajaxurl,{action:"ccuk_charity", id:$(this).data('id'), wp_nonce:ccuk_public.ccuk_nonce}, function(data, status, xhr, ){
                   var output = '<table>';
                   $.each(data, function(i,item){
                       //if(isArray(item)) output = output + '<table>';
                       output = output + '<tr><th>'+i+'</th><td>'+item+'</td></tr>';
                   });
                   output = output + '</table><a href="#" rel="modal:close"><button class="btn-primary">Close</button></a>';
                   $('#ccuk-modal').html(output).modal();
                   //$(test).appendTo('body').modal();
              });
              jqxhr.fail(function(xhr){
                  $('#ccuk-modal').html(xhr.status+': '+xhr.statusText).modal();
              });
           });          
        });

})( jQuery );
