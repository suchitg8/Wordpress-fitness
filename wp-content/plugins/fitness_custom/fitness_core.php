<?php

//Registers custom post type : Question
// TODO: CLOUD 9 HOSTING

add_action('init', 'fitness_init');

function fitness_init() {
    session_start();
	register_post_type(
		'Question', 
		array(
			'labels' => array(
				'name' => 'Questions',
				'singular_name' => 'Question'
				), 
			'public' => true, 
			'show_ui' => true, 
            'supports' => array(
                'title',
                'editor',
                'author',
                'excerpts',
                'custom-fields',
                'revisions',
                'thumbnail', 
                'post-tags'
            )
		)
	); 

}

function fitness_rewrite_flush() {
	fitness_init();
	flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'fitness_rewrite_flush' );