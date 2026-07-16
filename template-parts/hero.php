<?php

/**
 * Template part : Hero (image de couverture + titre)
 * Appelé depuis header.php, uniquement si travel_dams_has_hero() est vrai.
 */

$hero_image_id = travel_dams_get_hero_image_id();
$hero_title    = travel_dams_get_hero_title();
$hero_tag      = tag_escape(travel_dams_get_hero_title_tag());
?>

<section class="hero">

    <?php if ($hero_image_id) : ?>
        <?php
        echo wp_get_attachment_image(
            $hero_image_id,
            'hero',
            false,
            array(
                'class'         => 'hero__image',
                'alt'           => '', // décorative : le titre en texte porte déjà l'information
                'loading'       => 'eager',
                'fetchpriority' => 'high',
            )
        );
        ?>
    <?php else : ?>
        <img
            src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/hero-default.jpg'); ?>"
            alt=""
            class="hero__image"
            loading="eager"
            fetchpriority="high">
    <?php endif; ?>

    <div class="hero__overlay"></div>

    <div class="hero__inner">
        <?php if ($hero_title) : ?>
            <<?php echo $hero_tag; ?> class="hero__title"><?php echo esc_html($hero_title); ?></<?php echo $hero_tag; ?>>
        <?php endif; ?>
    </div>
</section>

<div id="hero-sentinel" aria-hidden="true"></div>