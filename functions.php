<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Add featured images to posts
add_theme_support('post-thumbnails');

// Registers a custom REST API endpoint to get Movies
add_action('rest_api_init', function () {
    register_rest_route('movies/v1', '/movies', array(
        'methods' => 'GET',
        'callback' => 'get_all_movies',
    ));
});

function get_all_movies() {
    $args = array(
        'post_type' => 'movie',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    );
    $posts = get_posts($args);
    $movies = array();
    foreach ($posts as $post) {
        $movie_data = array(
            'id' => $post->ID,
            'title' => $post->post_title,
            'slug' => get_post_field('post_name', $post->ID),
            'release' => get_field('release', $post->ID),
            'genres' => get_field('genres', $post->ID),
            'runtime' => get_field('runtime', $post->ID),
            'poster_url' => get_the_post_thumbnail_url($post->ID, 'full'),
            'overview' => get_field('overview', $post->ID),
            'excerpt' => get_the_excerpt($post->ID),
        );
        array_push($movies, $movie_data);
    }
    return $movies;
}