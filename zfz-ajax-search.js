(function($) {

  //
  // Ajax search
  //
  function ajax_search_init() {

    var doc = $(document);
    var endpoint = window.location.origin + window.location.pathname + '/searchapi';
    var searchform = $('form.searchform');

    searchform.after('<div class="search-results"><a href="#" class="close-results">Close</a><div class="loading-wrapper"></div><div class="results"></div></div>');

    doc.on('click', '.close-results', function(e){
      $(this).closest('.search-results').removeClass('active loading');
      $('.search-results .results').empty();
      e.preventDefault();
    });

    doc.on('submit', searchform, function(e){
      $('.search-results').addClass('loading');
      var query = encodeURIComponent($(this).find('input[name="s"]').val());
      
      if(query.length > 0) {
        $('.search-results .results').load(endpoint + '/?zfzs=' + query, function(e){
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
  $(function() {
    ajax_search_init();
  });


}(jQuery));