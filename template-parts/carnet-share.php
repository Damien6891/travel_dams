<?php

/**
 * Bloc "Partager cette aventure" — liens de partage social statiques,
 * affiché en pied d'un carnet de voyage (single.php).
 */

$permalink = get_permalink();
$title     = get_the_title();
?>

<div class="carnet-share">
    <span class="carnet-share__label eyebrow"><?php esc_html_e('Partager cette aventure', 'travel-dams'); ?></span>
    <div class="carnet-share__links">
        <a class="carnet-share__link" target="_blank" rel="noopener noreferrer" href="<?php echo esc_url('https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode($permalink)); ?>" aria-label="<?php esc_attr_e('Partager sur Facebook', 'travel-dams'); ?>">f</a>
        <a class="carnet-share__link" target="_blank" rel="noopener noreferrer" href="<?php echo esc_url('https://twitter.com/intent/tweet?url=' . rawurlencode($permalink) . '&text=' . rawurlencode($title)); ?>" aria-label="<?php esc_attr_e('Partager sur X', 'travel-dams'); ?>">𝕏</a>
        <button type="button" class="carnet-share__link js-copy-permalink" data-permalink="<?php echo esc_url($permalink); ?>" aria-label="<?php esc_attr_e('Copier le lien', 'travel-dams'); ?>">🔗</button>
    </div>
</div>
<script>
    document.querySelectorAll('.js-copy-permalink').forEach(function(button) {
        button.addEventListener('click', function() {
            navigator.clipboard && navigator.clipboard.writeText(button.dataset.permalink);
        });
    });
</script>