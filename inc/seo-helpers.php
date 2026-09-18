<?php

/**
 * SEO — helpers partagés.
 *
 * WordPress peut générer des URLs "protocol-relative" (//host/chemin, sans
 * http:/https: devant) selon la config siteurl/home. Les navigateurs les
 * gèrent très bien, mais les validateurs JSON-LD (dont le Rich Results Test
 * de Google) les rejettent comme invalides — il faut un schéma explicite.
 *
 * À charger depuis functions.php, EN PREMIER parmi les fichiers inc/seo-*.php :
 *   require get_template_directory() . '/inc/seo-helpers.php';
 *
 * @package Travel_Dams
 */

function travel_dams_absolute_url($url)
{
    if (empty($url) || is_wp_error($url)) {
        return $url;
    }

    return set_url_scheme($url, is_ssl() ? 'https' : 'http');
}
