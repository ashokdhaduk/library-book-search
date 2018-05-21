(function( $ ) {
	'use strict';
	
	$( "#slider-range" ).slider({
      range: true,
      min: 0,
      max: 3000,
      values: [ 1, 3000 ],
      slide: function( event, ui ) {
        $( "#amount" ).text( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
        $("#price_range").val( ui.values[ 0 ] + "-" + ui.values[ 1 ] );
      },
    });

    $( "#amount" ).text( "$" + $( "#slider-range" ).slider( "values", 0 ) +
      " - $" + $( "#slider-range" ).slider( "values", 1 ) );

    //Pagination AJAX
    $(document).on("click", ".book_pagination a", function(e) {
    	e.preventDefault();
    	var paged = $(this).data('page_id');
    	search_library_books( paged );
    });

    $(document).on("click", "#search_book", function(e) {
    	e.preventDefault();
    	search_library_books();
    });

    function search_library_books( paged = 1 ) {
    	$("#loading").show();
    	var search_form_data = $("#book-search").serialize();
    	$.ajax({
    		url: book_search_params.ajaxurl,
    		type: "POST",
    		data: search_form_data + "&paged=" + paged + "&action=search_books",
    		dataType: "HTML",
    		success: function( response ) {
    			console.log(response);
    			$(".books_section").html(response);
    			$("#loading").hide();
    		},
    		error: function( jqXHR, textStatus, errorThrown ) {
    			console.log(jqXHR + " :: " + textStatus + " :: " + errorThrown);
    			$("#loading").hide();
    		}
    	});
    }
  	

})( jQuery );
