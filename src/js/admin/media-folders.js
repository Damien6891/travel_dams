/**
 * Ajoute un menu "Tous les dossiers" dans la médiathèque WordPress
 * (page Médias en vue Grille + fenêtre d'insertion d'image).
 *
 * Données fournies par PHP : window.MediaFoldersData
 *   { allLabel, filterLabel, folders: [ { slug, label } ] }
 */
(function () {
    'use strict';

    var data = window.MediaFoldersData;

    if (!window.wp || !wp.media || !wp.media.view || !data) {
        return;
    }

    var Browser = wp.media.view.AttachmentsBrowser;

    /**
     * Menu déroulant : chaque option modifie la propriété "media_folder"
     * de la requête de la médiathèque. WordPress la transmet au serveur,
     * qui filtre sur la taxonomie du même nom.
     */
    var FolderFilter = wp.media.view.AttachmentFilters.extend({
        id: 'media-attachment-folder-filter',

        initialize: function () {
            wp.media.view.AttachmentFilters.prototype.initialize.apply(this, arguments);

            // Les fichiers téléversés pendant qu'un dossier est sélectionné
            // sont classés dans ce dossier (traité côté PHP, hook add_attachment).
            this.listenTo(this.model, 'change:media_folder', this.syncUploader);
            this.syncUploader();
        },

        syncUploader: function () {
            var frame = this.controller;
            var uploader = frame && frame.uploader && frame.uploader.uploader;

            if (uploader && uploader.param) {
                uploader.param('media_folder', this.model.get('media_folder') || '');
            }
        },

        createFilters: function () {
            var filters = {
                all: {
                    text: data.allLabel,
                    props: { media_folder: null },
                    priority: 10
                }
            };

            _.each(data.folders, function (folder, index) {
                filters['folder-' + folder.slug] = {
                    text: folder.label,
                    props: { media_folder: folder.slug },
                    priority: 20 + index
                };
            });

            this.filters = filters;
        }
    });

    /**
     * On étend la vue qui affiche la grille d'images pour y ajouter le menu
     * dans la barre d'outils, à côté des filtres "Type" et "Date".
     */
    wp.media.view.AttachmentsBrowser = Browser.extend({
        createToolbar: function () {
            Browser.prototype.createToolbar.apply(this, arguments);

            // Rien à afficher tant qu'aucun dossier n'a été créé.
            // (Ne pas conditionner à this.options.filters : la fenêtre de l'éditeur
            // de blocs n'active pas les filtres natifs de WordPress.)
            if (!data.folders.length) {
                return;
            }

            // Libellé au-dessus du menu, comme "Filtrer par type" / "Filtrer par date".
            this.toolbar.set('mediaFolderFilterLabel', new wp.media.view.Label({
                value: data.filterLabel,
                className: 'media-folder-filter-label',
                attributes: { 'for': 'media-attachment-folder-filter' },
                priority: -71
            }).render());

            this.toolbar.set('mediaFolderFilter', new FolderFilter({
                controller: this.controller,
                model: this.collection.props,
                priority: -70
            }).render());
        }
    });
}());