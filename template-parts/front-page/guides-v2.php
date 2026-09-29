<?php

/**
 * Section "Guides" — grille 
 *
 * @package Travel_Dams
 */
$guides = travel_dams_get_homepage_guides();

?>


<section class="guides-section">

    <div class="container container--wide">

        <div class="guides-section__panel">

            <div class="section-header">
                <h2 class="section-title"><?php esc_html_e('Guides pour préparer son voyage', 'travel-dams'); ?></h2>
                <p class="section-header__lead">
                    <?php esc_html_e("Itinéraires, budget, hébergement : ce que j'aurais aimé savoir avant de partir, tiré de mes propres voyages.", 'travel-dams'); ?>
                </p>
            </div>

            <div class=" guides-section__grid">
                <?php foreach ($guides as $index => $guide) : ?>

                    <?php
                    $is_main = $index === 0 ? true : false;
                    $category = get_the_category($guide->ID)[0];
                    $class = '';
                    if (td_in_category(TD_SLUG_DESTINATIONS_GUIDES, $guide->ID)) $class = 'badge--secondary';
                    if (td_in_category(TD_SLUG_GUIDES, $guide->ID)) $class = 'badge--accent';
                    ?>

                    <a href="<?= get_permalink($guide->ID) ?>" class="guides-section__item <?= $is_main ? 'guides-section__item--main' : '' ?>">
                        <div class="guides-section__item-thumbnail">
                            <?php echo get_the_post_thumbnail($guide->ID, 'large',  array('class' => 'guides-section__image')); ?>
                        </div>
                        <div class="guides-section__item-body">
                            <div class="guides-section__item-eyebrow">

                                <span class="badge <?= $class ?>">
                                    <?= $category->name ?>
                                </span>
                                <?php if (td_get_country($guide->ID)) : ?>
                                    <span class="guides-section__item-country">
                                        <?= td_get_country($guide->ID)->name ?>
                                    </span>
                                <?php endif ?>
                            </div>
                            <h3 class="guides-section__item-title">
                                <?= $guide->post_title ?>
                            </h3>
                            <p class="guides-section__item-desc">
                                <?= $index === 0 ? $guide->post_excerpt : wp_trim_words($guide->post_excerpt, 10, '...') ?>
                            </p>
                        </div>

                    </a>

                <?php endforeach ?>
            </div>


        </div>
    </div>
</section>