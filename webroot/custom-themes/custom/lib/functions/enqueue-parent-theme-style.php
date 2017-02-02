<?php
/**
 * Load parent theme
 *
 * @link http://codex.wordpress.org/Child_Themes
 *
 * @package WordPress
 * @subpackage Custom
 */

add_action('wp_enqueue_scripts', 'enqueue_parent_theme_style');

/**
 * Enqueue the CSS of the parent theme
 *
 * @return void
 */
function enqueue_parent_theme_style()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}
