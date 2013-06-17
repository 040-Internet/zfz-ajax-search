<?php
/*
 * Plugin Name: Zerofourzero Ajax Search
 * Version: 1.0
 * Plugin URI: http://040.se/
 * Description: Hijacks the ordinary search box to display results inline.
 * Author: Linus Bohman of 040
 */


/**
*
* Settings
*
**/

function zfz_ajax_search_settings() {
  $settings = array();
  $settings['post_type'] = array('post', 'page');
  $settings['no_results_text'] = __('We couldn\'t find anything that matched your search in this area.', 'zfz');
  return $settings;
}


/**
 *
 * Adding needed CSS and JS
 *
 **/
 
function zfz_add_css() {
  if(!is_admin()) {
    wp_enqueue_style('zfz-ajax-search', plugins_url('zfz-ajax-search.css', __FILE__ ), false, '1', 'screen');
  }
}

function zfz_add_js() {
  if(!is_admin()) {
    wp_enqueue_script('zfz-ajax-search', plugins_url('zfz-ajax-search.js', __FILE__ ), array('jquery'));
  }
}

add_action('wp_print_styles', 'zfz_add_css');
add_action('wp_enqueue_scripts', 'zfz_add_js');


/**
 *
 * Adding a super simple endpoint
 *
 **/

add_action('init', 'zfz_add_endpoint');

function zfz_add_endpoint() {

  $path_parts = explode('/', $_SERVER["REQUEST_URI"]);
  $path_parts = array_filter($path_parts);

  if(in_array('searchapi', $path_parts)) {
    if(isset($_GET['ctis'])) {
      zfz_ajax_search($_GET['ctis']);
    }
  }
}


/**
 *
 * Doing the actual search
 *
 **/

function zfz_ajax_search($search_query) {

  $settings = zfz_ajax_search_settings();

  // Use query, create loop
  $args = array(
    'post_type' => $settings['post_type'],
    'publish' => 'published',
    'posts_per_page' => -1,
    's' => $search_query
  );

  $search = new WP_Query($args);
  $results = array();

  // Save our posts into different arrays for later grouping
  if($search->have_posts()) :
    while ($search->have_posts()) : $search->the_post();
      $html = '';
      $html .= '<a class="item" href="'. get_permalink() .'">';
      $html .= get_the_title();
      $html .= '</a>';
      $type = get_post_type();
      $results[$type][] = $html;
    endwhile;
  endif;
  wp_reset_postdata();

  // Output ?>

  <p class="search-query"><?php _e('You searched for', 'zfz'); ?> <span class="searched"><?php echo $search_query; ?></span></div>

  <?php foreach($settings['post_type'] as $post_type) {
    if(!isset($results[$post_type])) {
      $markup = '<p class="no-results">' . $settings['no_results_text'] . '</p>';
    } else {
      $markup = implode('', $results[$post_type]);
    }
    $obj = get_post_type_object($post_type);
    $name = $obj->labels->singular_name; ?>

    <div class="result-group">
      <h2 class="title sub-title"><?php echo $name; ?></h2>
      <?php echo $markup; ?>
    </div>
  <?php }
  exit;
}
