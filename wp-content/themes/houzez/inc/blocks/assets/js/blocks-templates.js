'use strict';

(($) => {
    if (
        typeof elementor === 'undefined' ||
        typeof elementorCommon === 'undefined'
    ) {
        return;
    }

    elementor.on('preview:loaded', () => {
        let dialog = null;
        let allTemplates = []; // Store all loaded templates for filtering

        // Houzez Template button
        let buttons = $('#tmpl-elementor-add-section');

        const text = buttons
            .text()
            .replace(
                '<div class="elementor-add-section-drag-title',
                '<div class="elementor-add-section-area-button houzez-library-modal-btn" title="Houzez Templates">Houzez Templates</div><div class="elementor-add-section-drag-title'
            );

        buttons.text(text);

        // Call modal
        $(elementor.$previewContents[0].body).on(
            'click',
            '.houzez-library-modal-btn',
            () => {
                if (dialog) {
                    dialog.show();
                    return;
                }

                var modalOptions = {
                    id: 'houzez-library-modal',
                    headerMessage: $(
                        '#tmpl-elementor-houzez-library-modal-header'
                    ).html(),
                    message: $('#tmpl-elementor-houzez-library-modal').html(),
                    className: 'elementor-templates-modal',
                    closeButton: true,
                    draggable: false,
                    hide: {
                        onOutsideClick: true,
                        onEscKeyPress: true,
                    },
                    position: {
                        my: 'center',
                        at: 'center',
                    },
                };
                dialog = elementorCommon.dialogsManager.createWidget(
                    'lightbox',
                    modalOptions
                );
                dialog.show();

                loadTemplates();
            }
        );

        // Load templates directly from JSON file
        function loadTemplates() {
            showLoader();

            const jsonUrl = 'https://studio.houzez.co/wp-content/uploads/houzez-studio-files/all-templates.json?v=' + Date.now();

            console.log('📚 Houzez Library: Loading templates from JSON file:', jsonUrl);

            $.ajax({
                url: jsonUrl,
                method: 'GET',
                dataType: 'json',
                cache: false,
                success: function (response) {
                    if (
                        response &&
                        response.elements &&
                        Array.isArray(response.elements)
                    ) {
                        console.log(`✅ Loaded ${response.elements.length} templates from JSON file`);

                        // Store templates for later use
                        allTemplates = response.elements;

                        // Display all templates
                        displayTemplates(response);
                        hideLoader();
                    } else {
                        console.error('Invalid template response structure', response);
                        showError('Invalid template data structure.');
                        hideLoader();
                    }
                },
                error: function (xhr, status, error) {
                    let errorMessage = '';

                    if (xhr.status === 0) {
                        console.error('❌ CORS or network error loading all-templates.json');
                        console.error('💡 Ensure CORS headers are properly configured on studio.houzez.co');
                        errorMessage = 'Network or CORS error. Check console for details.';
                    } else if (xhr.status === 404) {
                        console.error('❌ all-templates.json not found (404)');
                        console.error('💡 Run "Generate JSON Files" in the Favethemes API settings');
                        errorMessage = 'Template file not found. Generate JSON files first.';
                    } else if (xhr.status >= 500) {
                        console.error(`❌ Server error (${xhr.status})`);
                        errorMessage = `Server error (${xhr.status}). Try again later.`;
                    } else {
                        console.warn(`⚠️ HTTP error (${xhr.status})`);
                        errorMessage = `HTTP error ${xhr.status}`;
                    }

                    showError(`Failed to load templates: ${errorMessage}`);
                    hideLoader();
                },
            });
        }

        // Display templates in the modal
        function displayTemplates(response) {
            console.log('Displaying templates:', response.elements.length);

            // Validate response structure
            if (
                !response ||
                !response.elements ||
                !Array.isArray(response.elements)
            ) {
                console.error('Invalid template response structure', response);
                showError('Invalid template data structure.');
                hideLoader();
                return;
            }

            var itemTemplate = wp.template(
                'elementor-houzez-library-modal-item'
            );
            var itemOrderTemplate = wp.template(
                'elementor-houzez-library-modal-order'
            );

            $(
                '#houzez-library-modal #elementor-template-library-templates-container'
            ).empty();
            $(
                '#houzez-library-modal #elementor-template-library-filter-toolbar-remote'
            ).empty();

            $(itemTemplate(response)).appendTo(
                $(
                    '#houzez-library-modal #elementor-template-library-templates-container'
                )
            );
            $(itemOrderTemplate(response)).appendTo(
                $(
                    '#houzez-library-modal #elementor-template-library-filter-toolbar-remote'
                )
            );

            importTemplate();
        }

        // Helper function to find template slug by ID from loaded templates
        function getTemplateSlugById(templateId) {
            if (allTemplates && allTemplates.length > 0) {
                const template = allTemplates.find(t => t.id == templateId);
                if (template && template.slug) {
                    return template.slug;
                }
            }

            // Fallback: try to get slug from DOM element data attribute
            const $element = $(`[data-id="${templateId}"]`).closest('.elementor-template-library-template');
            if ($element.length > 0) {
                const slug = $element.data('slug');
                if (slug) {
                    return slug;
                }
            }

            return null;
        }

        // Import single template from JSON file
        function importSingleTemplate(templateId) {
            showLoader();

            const templateSlug = getTemplateSlugById(templateId);

            if (!templateSlug) {
                console.error(`Could not find slug for template ${templateId}`);
                showError('Template not found.');
                hideLoader();
                return;
            }

            const jsonUrl = `https://studio.houzez.co/wp-content/uploads/houzez-studio-files/${templateSlug}.json?v=${Date.now()}`;

            console.log(`📄 Loading template from JSON file: ${jsonUrl}`);

            $.ajax({
                url: jsonUrl,
                method: 'GET',
                dataType: 'json',
                cache: false,
                success: function(templateData) {
                    if (templateData && templateData.content && Array.isArray(templateData.content)) {
                        console.log(`✅ Template loaded from JSON file: ${templateSlug}`);

                        // Process the template content for Elementor
                        const processedContent = processTemplateContent(templateData.content);

                        // Add to Elementor
                        elementor.getPreviewView().addChildModel(processedContent);
                        dialog.hide();
                        setTimeout(function () {
                            hideLoader();
                        }, 2000);
                        activateUpdateButton();

                        // Show success notification
                        $(
                            '<div class="houzez-notice houzez-success">✅ Template loaded from JSON file</div>'
                        )
                            .prependTo(
                                $(
                                    '#houzez-library-modal #elementor-template-library-templates-container'
                                )
                            )
                            .delay(3000)
                            .fadeOut();
                    } else {
                        console.error('Invalid JSON template structure');
                        showError('Invalid template data.');
                        hideLoader();
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = 'Unknown error';
                    let errorType = 'generic';

                    if (xhr.status === 0) {
                        errorMessage = 'CORS error or network failure';
                        errorType = 'cors';
                        console.error(`❌ CORS or network error loading JSON: ${jsonUrl}`);
                    } else if (xhr.status === 404) {
                        errorMessage = 'JSON file not found (404)';
                        errorType = '404';
                        console.warn(`⚠️ JSON file not found (404): ${jsonUrl}`);
                    } else if (xhr.status >= 500) {
                        errorMessage = `Server error (${xhr.status})`;
                        errorType = 'server';
                        console.error(`❌ Server error loading JSON: ${jsonUrl}`);
                    } else {
                        errorMessage = `HTTP error ${xhr.status}`;
                        errorType = 'http';
                        console.warn(`⚠️ HTTP error loading JSON: ${jsonUrl}`);
                    }

                    showError(`Failed to load template: ${errorMessage}`);
                    hideLoader();
                }
            });
        }

        // Helper function to process template content (Elementor's processing)
        function processTemplateContent(content) {
            // This mimics what Elementor does on the backend
            // The content from JSON should already be in the correct format
            // But we need to ensure IDs are unique
            return replaceElementIds(content);
        }

        // Helper function to replace element IDs to make them unique
        function replaceElementIds(content) {
            // Deep clone the content
            const clonedContent = JSON.parse(JSON.stringify(content));

            // Recursively replace IDs
            function replaceIds(element) {
                if (element.id) {
                    // Generate a unique ID (similar to Elementor's method)
                    element.id = generateUniqueId();
                }

                if (element.elements && Array.isArray(element.elements)) {
                    element.elements.forEach(replaceIds);
                }
            }

            if (Array.isArray(clonedContent)) {
                clonedContent.forEach(replaceIds);
            }

            return clonedContent;
        }

        // Generate unique ID for Elementor elements
        function generateUniqueId() {
            return Math.random().toString(36).substr(2, 9);
        }

        // Show error message
        function showError(message) {
            $(
                '<div class="houzez-notice houzez-error">' + message + '</div>'
            ).appendTo(
                $(
                    '#houzez-library-modal #elementor-template-library-templates-container'
                )
            );
        }

        function showLoader() {
            $(
                '#houzez-library-modal #elementor-template-library-templates'
            ).hide();
            $('#houzez-library-modal .elementor-loader-wrapper').show();
        }

        function hideLoader() {
            $(
                '#houzez-library-modal #elementor-template-library-templates'
            ).show();
            $('#houzez-library-modal .elementor-loader-wrapper').hide();
        }

        function activateUpdateButton() {
            $('#elementor-panel-saver-button-publish').toggleClass(
                'elementor-disabled'
            );
            $('#elementor-panel-saver-button-save-options').toggleClass(
                'elementor-disabled'
            );
        }

        function importTemplate() {
            $(
                '#houzez-library-modal .elementor-template-library-template-insert'
            )
                .off('click')
                .on('click', function () {
                    const templateId = $(this).data('id');
                    importSingleTemplate(templateId);
                });

            $(
                '#houzez-library-modal .elementor-templates-modal__header__close'
            ).on('click', () => {
                dialog.hide();
                hideLoader();
            });

            $(
                '#houzez-library-modal #elementor-template-library-filter-text'
            ).on('keyup', function () {
                var searchValue = $(this).val();
                console.log(searchValue);

                var search = String($(this).val()).toLowerCase(); // Convert to string explicitly

                var activeTab = document
                    .querySelector(
                        '#elementor-houzez-library-header-menu .elementor-active'
                    )
                    .getAttribute('data-tab');

                $('#houzez-library-modal')
                    .find('.elementor-template-library-template')
                    .each(function () {
                        const $this = $(this);
                        const slug = $this.data('slug');
                        const type = $this.data('type');
                        const name = $this.data('name');

                        if (name.includes(search) && type.includes(activeTab)) {
                            $this.show();
                        } else {
                            $this.hide();
                        }
                    });
            });

            // Filter by tag
            $(
                '#houzez-library-modal #elementor-template-library-filter-subtype'
            ).on('change', function () {
                var val = $(this).val();

                $('#houzez-library-modal')
                    .find('.elementor-template-library-template-block')
                    .each(function () {
                        var $this = $(this);
                        var slug = String($this.data('slug')).toLowerCase();

                        if (slug.indexOf(val) > -1 || val == 'all') {
                            $this.show();
                        } else {
                            $this.hide();
                        }
                    });
            });

            function setActiveTab(tab) {
                $(
                    '#houzez-library-modal .elementor-template-library-menu-item'
                ).removeClass('elementor-active');
                const activeTab = $('#houzez-tab-' + tab);
                activeTab.addClass('elementor-active');

                document
                    .querySelectorAll(
                        '#houzez-library-modal .elementor-template-library-template'
                    )
                    .forEach((e) => {
                        const type = e.getAttribute('data-type');
                        e.style.display = type === tab ? 'block' : 'none';

                        if (tab === 'template') {
                            $('#elementor-template-library-filter').hide();
                        } else {
                            $('#elementor-template-library-filter').show();
                        }
                    });
            }

            setActiveTab('block');

            // Filter by type
            $('#houzez-library-modal .elementor-template-library-menu-item').on(
                'click',
                function () {
                    setActiveTab($(this).data('tab'));
                }
            );
        }
    });
})(jQuery);
