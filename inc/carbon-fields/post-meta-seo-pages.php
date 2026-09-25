<?php

/**
 * Champs Carbon Fields — titre SEO et meta description, uniquement sur les
 * pages ayant une vraie valeur de recherche : la page d'accueil et le hub
 * destinations (page-destination.php). Les pages utilitaires (à propos,
 * mentions légales…) n'en ont pas besoin — le fallback auto (titre + nom
 * du site) leur suffit ; à ajouter au cas par cas si l'une devient
 * stratégique un jour, pas préventivement pour toutes les pages.
 *
 * @package Travel_Dams
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

$strategic_page_ids = array();

$front_page_id = (int) get_option('page_on_front');

if ($front_page_id) {
    $strategic_page_ids[] = $front_page_id;
}

$hub_pages = get_posts(array(
    'post_type'      => 'page',
    'post_status'    => 'publish',
    'meta_key'       => '_wp_page_template',
    'meta_value'     => 'page-destinations.php',
    'posts_per_page' => -1,
    'lang'           => '', // bypass le filtre de langue automatique de Polylang
    'fields'         => 'ids',
));

$strategic_page_ids = array_unique(array_filter(array_merge($strategic_page_ids, $hub_pages)));

if (! empty($strategic_page_ids)) {
    $container = Container::make('post_meta', __('SEO', 'travel-dams'))
        ->where('post_id', '=', $strategic_page_ids[0]);

    foreach (array_slice($strategic_page_ids, 1) as $page_id) {
        $container->or_where('post_id', '=', $page_id);
    }

    /** @disregard P1013 */
    $container->add_fields(array(
        Field::make('text', 'seo_title', __('Titre SEO', 'travel-dams'))
            ->set_help_text(__('~60 caractères. Laisser vide pour utiliser le titre de la page + nom du site.', 'travel-dams'))
            ->set_attribute('maxLength', 70),

        Field::make('textarea', 'seo_description', __('Meta description', 'travel-dams'))
            ->set_help_text(__('~155 caractères. Laisser vide pour générer automatiquement à partir de l\'extrait.', 'travel-dams'))
            ->set_attribute('maxLength', 170)
            ->set_rows(3),
    ));
}
