(function($) {


  //
  // Ajax search
  //
  function ajax_search_init() {

    var endpoint = window.location.protocol + "//" + window.location.host + '/searchapi';
    var searchform = $('form.searchform');

    searchform.after('<div class="search-results"><a href="#" class="close-results">Close</a><div class="loading-wrapper"></div><div class="results"></div></div>');

    $('.close-results').click(function(e){
      $(this).closest('.search-results').removeClass('active').removeClass('loading');
      $('.search-results .results').empty();
      e.preventDefault();
    });

    searchform.submit(function(e){
      $('.search-results').addClass('loading');
      var query = $(this).find('input[name="s"]').val();
      if(query.length > 0) {
        $('.search-results .results').load(endpoint + '/' + query, function(e){
          $('.search-results').removeClass('loading').addClass('active');
        });
      } else {
        $('.search-results').addClass('active').removeClass('loading')
          .find('.results').html('<p class="default-text">Please enter a word or phrase to search for.</p>');
      }

      e.preventDefault();
    });
  }


  //
  // Vroom vroom
  //
  jQuery(document).ready(function() {
    ajax_search_init();
  });


}(jQuery));