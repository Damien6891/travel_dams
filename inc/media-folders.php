<?php

/**
 * Dossiers virtuels pour la médiathèque (taxonomie "media_folder").
 *
 * Chargement depuis functions.php :
 *   require_once get_theme_file_path( 'inc/media-folders.php' );
 *
 * Le JavaScript associé se trouve dans assets/js/media-folders.js
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * 1. Taxonomie hiérarchique rattachée aux médias.
 * Menu : Médias > Dossiers.
 */
add_action('init', function () {
    register_taxonomy('media_folder', 'attachment', [
        'labels'                => [
            'name'              => 'Dossiers',
            'singular_name'     => 'Dossier',
            'menu_name'         => 'Dossiers',
            'all_items'         => 'Tous les dossiers',
            'edit_item'         => 'Modifier le dossier',
            'add_new_item'      => 'Ajouter un dossier',
            'new_item_name'     => 'Nom du nouveau dossier',
            'search_items'      => 'Rechercher un dossier',
            'parent_item'       => 'Dossier parent',
            'parent_item_colon' => 'Dossier parent :',
        ],
        'public'                => false,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'show_in_rest'          => true,
        'hierarchical'          => true,
        // WordPress transmet automatiquement ce paramètre aux requêtes AJAX
        // de la médiathèque : c'est ce qui fait fonctionner le filtre en grille.
        'query_var'             => true,
        'rewrite'               => false,
        // Sans ça le compteur reste à 0 (les médias ont le statut "inherit").
        'update_count_callback' => '_update_generic_term_count',
    ]);
});

/**
 * 2. Liste "aplatie" des dossiers (avec indentation des sous-dossiers),
 * utilisée par le filtre JavaScript.
 */
function media_folders_flat_list($parent = 0, $depth = 0)
{
    $terms = get_terms([
        'taxonomy'   => 'media_folder',
        'hide_empty' => false,
        'parent'     => $parent,
        'orderby'    => 'name',
    ]);

    if (is_wp_error($terms)) {
        return [];
    }

    $list = [];

    foreach ($terms as $term) {
        $list[] = [
            'slug'  => $term->slug,
            // Échappé ici car le JS l'insère avec .html().
            'label' => str_repeat('— ', $depth) . esc_html($term->name),
        ];
        $list = array_merge($list, media_folders_flat_list($term->term_id, $depth + 1));
    }

    return $list;
}

/**
 * 3. Chargement du JS du filtre (vue Grille + fenêtre "Ajouter un média").
 * Branché sur deux hooks : wp_enqueue_media (page Médias, éditeur classique)
 * et enqueue_block_editor_assets (éditeur de blocs). Ne s'exécute qu'une fois.
 */
function media_folders_enqueue_assets()
{
    static $done = false;

    if ($done || ! is_admin()) {
        return;
    }

    $file = 'src/js/admin/media-folders.js';
    $path = get_theme_file_path($file);

    if (! file_exists($path)) {
        // Visible dans wp-content/debug.log si WP_DEBUG_LOG est activé.
        error_log('[media-folders] Fichier JS introuvable : ' . $path);
        return;
    }

    $done = true;

    wp_enqueue_script(
        'media-folders',
        get_theme_file_uri($file),
        ['media-views'],
        filemtime($path),
        true
    );

    wp_localize_script('media-folders', 'MediaFoldersData', [
        'allLabel'    => 'Tous les dossiers',
        'filterLabel' => 'Filtrer par dossier',
        'folders'     => media_folders_flat_list(),
    ]);

    // WordPress 7.0+ : la barre de filtres est une grille fixe de 2 colonnes x 2 lignes.
    // Sans ce CSS, le filtre dossier tombe sur une 3e ligne, coupée par la barre d'outils.
    // :has() limite la règle aux fenêtres où le filtre dossier est présent.
    wp_add_inline_style(
        'media-views',
        '.media-frame .media-toolbar-secondary:has( #media-attachment-folder-filter ) { grid-template-columns: repeat( 3, minmax( 0, 1fr ) ) !important; }'
            . '.media-frame .media-toolbar-secondary > .media-folder-filter-label { grid-area: 1 / 3 / 2 / 4; }'
            . '.media-frame .media-toolbar-secondary > #media-attachment-folder-filter { grid-area: 2 / 3 / 3 / 4; }'
    );
}
add_action('wp_enqueue_media', 'media_folders_enqueue_assets');
add_action('enqueue_block_editor_assets', 'media_folders_enqueue_assets');

/**
 * 4. Champ "Dossier" dans le panneau de détails d'une image
 * (vue Grille et fenêtre d'insertion d'image).
 *
 * Le champ porte volontairement le même nom que la taxonomie ("media_folder") :
 * WordPress enregistre alors lui-même les termes à partir de ce champ, en le
 * lisant comme une liste de NOMS ou SLUGS séparés par des virgules. On envoie
 * donc le SLUG du dossier (et non son ID, sinon WordPress crée un nouveau
 * dossier nommé "72"). Une valeur vide retire l'image de son dossier.
 * Aucun filtre attachment_fields_to_save n'est nécessaire.
 */
add_filter('attachment_fields_to_edit', function ($fields, $post) {
    $current = wp_get_object_terms($post->ID, 'media_folder', ['fields' => 'slugs']);
    $current = (! is_wp_error($current) && ! empty($current)) ? $current[0] : '';

    $select = wp_dropdown_categories([
        'taxonomy'          => 'media_folder',
        'name'              => 'attachments[' . $post->ID . '][media_folder]',
        'id'                => 'media-folder-' . $post->ID,
        'hierarchical'      => true,
        'hide_empty'        => false,
        'show_option_none'  => 'Aucun dossier',
        'option_none_value' => '',
        'value_field'       => 'slug',
        'selected'          => $current,
        'echo'              => false,
    ]);

    $fields['media_folder'] = [
        'label' => 'Dossier',
        'input' => 'html',
        'html'  => $select,
    ];

    return $fields;
}, 10, 2);

/**
 * 5. Vue Liste : menu déroulant de filtre au-dessus du tableau.
 */
add_action('restrict_manage_posts', function ($post_type) {
    if ('attachment' !== $post_type) {
        return;
    }

    $selected = isset($_GET['media_folder'])
        ? sanitize_text_field(wp_unslash($_GET['media_folder']))
        : '';

    wp_dropdown_categories([
        'taxonomy'        => 'media_folder',
        'name'            => 'media_folder',
        'show_option_all' => 'Tous les dossiers',
        'hierarchical'    => true,
        'hide_empty'      => false,
        'value_field'     => 'slug',
        'selected'        => $selected,
        'orderby'         => 'name',
    ]);
});

/**
 * 6. Vue Liste : action groupée "Déplacer vers : <dossier>".
 */
add_filter('bulk_actions-upload', function ($actions) {
    $terms = get_terms([
        'taxonomy'   => 'media_folder',
        'hide_empty' => false,
    ]);

    if (is_wp_error($terms)) {
        return $actions;
    }

    foreach ($terms as $term) {
        $actions['move_to_folder_' . $term->term_id] = 'Déplacer vers : ' . $term->name;
    }

    return $actions;
});

add_filter('handle_bulk_actions-upload', function ($redirect, $action, $ids) {
    if (0 !== strpos($action, 'move_to_folder_')) {
        return $redirect;
    }

    $term_id = (int) substr($action, strlen('move_to_folder_'));

    foreach ($ids as $id) {
        if (current_user_can('edit_post', (int) $id)) {
            wp_set_object_terms((int) $id, [$term_id], 'media_folder', false);
        }
    }

    return $redirect;
}, 10, 3);

/**
 * 7. Téléversement depuis la fenêtre de médias : l'image est classée dans le
 * dossier actuellement sélectionné dans le filtre. Le JS envoie le paramètre
 * "media_folder" (slug du dossier) avec chaque fichier.
 * Les envois qui ne passent pas par la fenêtre (glisser-déposer direct dans un
 * bloc, bouton "Téléverser" du bloc Image) n'envoient pas ce paramètre.
 */
add_action('add_attachment', function ($attachment_id) {
    if (empty($_REQUEST['media_folder']) || ! is_string($_REQUEST['media_folder'])) {
        return;
    }

    $slug = sanitize_text_field(wp_unslash($_REQUEST['media_folder']));
    $term = get_term_by('slug', $slug, 'media_folder');

    if ($term && ! is_wp_error($term)) {
        wp_set_object_terms($attachment_id, [(int) $term->term_id], 'media_folder', false);
    }
});
