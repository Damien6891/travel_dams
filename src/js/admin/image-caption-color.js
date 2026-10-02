(function (wp) {
    const { addFilter } = wp.hooks;
    const { createHigherOrderComponent } = wp.compose;
    const { InspectorControls, PanelColorSettings, useSettings } = wp.blockEditor;
    const { Fragment, createElement: el } = wp.element;
    const { __ } = wp.i18n;

    const BLOCK = 'core/image';
    const cssVar = (slug) => `var(--wp--preset--color--${slug})`;

    // 1. Déclare l'attribut
    addFilter('blocks.registerBlockType', 'travel-dams/caption-color/attr', (settings, name) => {
        if (name !== BLOCK) return settings;
        return {
            ...settings,
            attributes: { ...settings.attributes, tdCaptionColor: { type: 'string' } },
        };
    });

    // 2. Contrôle dans l'inspecteur
    function CaptionColorControl({ attributes, setAttributes }) {
        const [palette = []] = useSettings('color.palette');
        const current = palette.find((c) => c.slug === attributes.tdCaptionColor);

        return el(InspectorControls, null,
            el(PanelColorSettings, {
                title: __('Légende', 'travel_dams'),
                disableCustomColors: true,
                colorSettings: [{
                    label: __('Couleur du texte', 'travel_dams'),
                    value: current?.color,
                    onChange: (hex) => {
                        const match = palette.find((c) => c.color === hex);
                        setAttributes({ tdCaptionColor: match ? match.slug : undefined });
                    },
                }],
            })
        );
    }

    addFilter('editor.BlockEdit', 'travel-dams/caption-color/control',
        createHigherOrderComponent((BlockEdit) => (props) => {
            if (props.name !== BLOCK) return el(BlockEdit, props);
            return el(Fragment, null, el(BlockEdit, props), el(CaptionColorControl, props));
        }, 'withCaptionColorControl')
    );

    // 3. Aperçu dans l'éditeur : variable CSS sur le wrapper
    addFilter('editor.BlockListBlock', 'travel-dams/caption-color/preview',
        createHigherOrderComponent((BlockListBlock) => (props) => {
            const slug = props.attributes?.tdCaptionColor;
            if (props.name !== BLOCK || !slug) return el(BlockListBlock, props);
            const wrapperProps = {
                ...props.wrapperProps,
                style: { ...(props.wrapperProps?.style || {}), '--td-caption-color': cssVar(slug) },
            };
            return el(BlockListBlock, { ...props, wrapperProps });
        }, 'withCaptionColorPreview')
    );
})(window.wp);