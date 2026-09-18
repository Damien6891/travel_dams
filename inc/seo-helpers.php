<?php

/**
 * SEO — helpers partagés.
 *
 * WordPress peut générer des URLs "protocol-relative" (//host/chemin, sans
 * http:/https: devant) selon la config siteurl/home. Les navigateurs les
 * gèrent très bien, mais les validateurs JSON-LD (dont le Rich Results Test
 * de Google) les rejettent comme invalides — il faut un schéma explicite.
 *
 * set_url_scheme() de WordPress core ne suffit PAS ici : elle ne fait que
 * remplacer un schéma déjà présent (http:// → https://), sa regex interne
 * (^\w+://) ne matche pas une URL qui commence directement par "//" — donc
 * elle la laisse inchangée. Il faut détecter ce cas et préfixer à la main.
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

    $scheme = is_ssl() ? 'https' : 'http';

    if (str_starts_with($url, '//')) {
        return $scheme . ':' . $url;
    }

    return set_url_scheme($url, $scheme);
}
