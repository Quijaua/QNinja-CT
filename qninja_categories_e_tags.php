<?php
/**
 * Plugin Name: QNinja Categories e Tags
 * Plugin URI:  http://wordpress.org/plugins
 * Description: Adiciona tags e categorias a submissões do Ninja Forms
 * Version:     0.1.0
 * Author:      Eduardo Alencar
 * Author URI:  
 * License:     GPLv2+
 * Text Domain: nfr
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2015 Eduardo Alencar (email : ealencar10@gmail.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Built using grunt-wp-plugin
 * Copyright (c) 2013 10up, LLC
 * https://github.com/10up/grunt-wp-plugin
 */

// Useful global constants
define( 'NFRTT_VERSION', '0.1.0' );
define( 'NFRTT_URL',     plugin_dir_url( __FILE__ ) );
define( 'NFRTT_PATH',    dirname( __FILE__ ) . '/' );

/**
 * Default initialization for the plugin:
 * - Registers the default textdomain.
 */
function nfrtt_init() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'nfr' );
	load_textdomain( 'nfr', WP_LANG_DIR . '/nfr/nfr-' . $locale . '.mo' );
	load_plugin_textdomain( 'nfr', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	nfrtt_create_nfsub_taxonomies();
}

/**
 * Activate the plugin
 */
function nfrtt_activate() {
	// First load the init scripts in case any rewrite functionality is being loaded
	nfrtt_init();

	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'nfrtt_activate' );

/**
 * Deactivate the plugin
 * Uninstall routines should be in uninstall.php
 */
function nfrtt_deactivate() {

}
register_deactivation_hook( __FILE__, 'nfrtt_deactivate' );

// Wireup actions
add_action( 'init', 'nfrtt_init' );

// create two taxonomies, genres and writers for the post type "book"
function nfrtt_create_nfsub_taxonomies() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Categoria da submissão', 'taxonomy general name' ),
		'singular_name'     => _x( 'Categoria da submissão', 'taxonomy singular name' ),
		'search_items'      => __( 'Buscar Categoria da submissão' ),
		'all_items'         => __( 'Todas Categorias' ),
		'parent_item'       => __( 'Parent Categoria' ),
		'parent_item_colon' => __( 'Parent Categoria:' ),
		'edit_item'         => __( 'Editar Categoria' ),
		'update_item'       => __( 'Atualizar Categoria' ),
		'add_new_item'      => __( 'Adicionar nova categoria' ),
		'new_item_name'     => __( 'Nova categoria nome' ),
		'menu_name'         => __( 'Ninja Forms Categoria' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'ninja-forms-category' ),
	);

	register_taxonomy( 'ninja-forms-category', array( 'nf_sub' ), $args );

	// Add new taxonomy, NOT hierarchical (like tags)
	$labels = array(
		'name'                       => _x( 'Submissão Tags', 'taxonomy general name' ),
		'singular_name'              => _x( 'Submissão Tags', 'taxonomy singular name' ),
		'search_items'               => __( 'Buscar tags' ),
		'popular_items'              => __( 'Tags populares' ),
		'all_items'                  => __( 'Todas as tags' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Editar tag' ),
		'update_item'                => __( 'Salvar tag' ),
		'add_new_item'               => __( 'Adicionar tag' ),
		'new_item_name'              => __( 'Nova tag nome' ),
		'separate_items_with_commas' => __( 'Separar tags por virgula' ),
		'add_or_remove_items'        => __( 'Adicionar ou remover tags' ),
		'choose_from_most_used'      => __( 'Escolher tags mais usadas' ),
		'not_found'                  => __( 'Nenhuma tag encontrada' ),
		'menu_name'                  => __( 'Ninja Forms Tags' ),
	);

	$args = array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'ninja-forms-tags' ),
	);

	register_taxonomy( 'ninja-forms-tags', 'nf_sub', $args );
}