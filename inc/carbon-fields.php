<?php

/**
 * Bootstrap Carbon Fields et chargement des définitions de champs.
 *
 * Chaque nouveau groupe de champs (post_meta, term_meta, theme_options...)
 * doit être ajouté comme un fichier séparé dans inc/carbon-fields/.
 *
 * @package Travel_Dams
 */

add_action('after_setup_theme', function () {
    require_once get_template_directory() . '/vendor/autoload.php';
    \Carbon_Fields\Carbon_Fields::boot();
});

add_action('carbon_fields_register_fields', function () {
    foreach (glob(get_template_directory() . '/inc/carbon-fields/*.php') as $file) {
        require $file;
    }
});
