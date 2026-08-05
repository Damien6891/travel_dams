<?php

/**
 * Walker menu : inject mega menu Destinations after item with CSS class "has-megamenu"
 */

class Travel_Dams_Nav_Walker extends Walker_Nav_Menu
{

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        // if (in_array('has-megamenu', $item->classes, true)) {
        //     $classes   = array_merge((array) $item->classes, array('menu-item-' . $item->ID));
        //     $class_str = implode(' ', array_filter($classes));
        //     $panel_id = 'mega-menu-panel-' . $item->ID;

        //     $output .= '<li id="menu-item-' . $item->ID . '" class="' . esc_attr($class_str) . '">';
        //     $output .= '<button type="button" class="mega-menu-trigger" aria-haspopup="true" aria-expanded="false" aria-controls="' . esc_attr($panel_id) . '">';
        //     $output .= esc_html($item->title);
        //     $output .= '</button>';
        //     return;
        // }
        // if (in_array('has-megamenu', $item->classes, true)) {
        //     $classes   = array_merge((array) $item->classes, array('menu-item-' . $item->ID));
        //     $class_str = implode(' ', array_filter($classes));
        //     $panel_id  = 'mega-menu-panel-' . $item->ID;

        //     $output .= '<li id="menu-item-' . $item->ID . '" class="' . esc_attr($class_str) . '">';
        //     $output .= '<div class="mega-menu-header">';

        //     // Vrai lien, navigue vers la page Destinations
        //     $output .= '<a href="' . esc_url($item->url) . '" class="mega-menu-link">';
        //     $output .= esc_html($item->title);
        //     $output .= '</a>';

        //     // Bouton séparé, uniquement pour ouvrir/fermer le panneau
        //     $output .= '<button type="button" class="mega-menu-trigger" aria-haspopup="true" aria-expanded="false" aria-controls="' . esc_attr($panel_id) . '">';
        //     $output .= '<span class="screen-reader-text">' . esc_html__('Afficher les destinations', 'travel-dams') . '</span>';
        //     $output .= '<span class="mega-menu-chevron" aria-hidden="true"></span>';
        //     $output .= '<span class="dashicons dashicons-arrow-down-alt2"></span>';
        //     $output .= '</button>';

        //     $output .= '</div>';
        //     return;
        // }

        if (in_array('has-megamenu', $item->classes, true)) {
            $classes   = array_merge((array) $item->classes, array('menu-item-' . $item->ID));
            $class_str = implode(' ', array_filter($classes));
            $panel_id  = 'mega-menu-panel-' . $item->ID;

            $output .= '<li id="menu-item-' . $item->ID . '" class="' . esc_attr($class_str) . '">';
            $output .= '<div class="mega-menu-header">';

            // Lien réel, navigue vers la page Destinations
            $output .= '<a href="' . esc_url($item->url) . '" class="mega-menu-link">';
            $output .= esc_html($item->title);
            $output .= '</a>';

            // Bouton séparé, uniquement pour ouvrir/fermer le panneau
            $output .= '<button type="button" class="mega-menu-trigger" aria-haspopup="true" aria-expanded="false" aria-controls="' . esc_attr($panel_id) . '">';
            $output .= '<span class="screen-reader-text">' . esc_html__('Afficher les destinations', 'travel-dams') . '</span>';
            // $output .= '<span class="mega-menu-chevron" aria-hidden="true"></span>';
            $output .= '<span class="mega-menu-chevron dashicons dashicons-arrow-down-alt2" aria-hidden="true"></span>';
            $output .= '</button>';

            $output .= '</div>';
            return;
        }

        parent::start_el($output, $item, $depth, $args, $id);
    }

    public function end_el(&$output, $item, $depth = 0, $args = null)
    {
        if (in_array('has-megamenu', $item->classes, true)) {
            $output .= travel_dams_get_destinations_megamenu();
            $output .= '</li>';
            return;
        }

        parent::end_el($output, $item, $depth, $args);
    }
}
