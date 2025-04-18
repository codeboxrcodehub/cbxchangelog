(function ($) {
    'use strict';

    function cbxchangelog_copyStringToClipboard(str) {
        // Create new element
        var el = document.createElement('textarea');
        // Set value (string to be copied)
        el.value = str;
        // Set non-editable to avoid focus and move outside of view
        el.setAttribute('readonly', '');
        el.style = {position: 'absolute', left: '-9999px'};
        document.body.appendChild(el);
        // Select text inside element
        el.select();
        // Copy text to clipboard
        document.execCommand('copy');
        // Remove temporary element
        document.body.removeChild(el);
    }

    $(document.body).ready(function ($) {
        var awn_options = {
            labels: {
                tip: cbxchangelog_edit.awn_options.tip,
                info: cbxchangelog_edit.awn_options.info,
                success: cbxchangelog_edit.awn_options.success,
                warning: cbxchangelog_edit.awn_options.warning,
                alert: cbxchangelog_edit.awn_options.alert,
                async: cbxchangelog_edit.awn_options.async,
                confirm: cbxchangelog_edit.awn_options.confirm,
                confirmOk: cbxchangelog_edit.awn_options.confirmOk,
                confirmCancel: cbxchangelog_edit.awn_options.confirmCancel
            }
        };

        //click to copy shortcode
        $('.cbxballon_ctp').on('click', function (e) {
            e.preventDefault();

            var $this = $(this);
            cbxchangelog_copyStringToClipboard($this.prev('.cbxshortcode').text());

            $this.attr('aria-label', cbxchangelog_edit.copycmds.copied_tip);

            window.setTimeout(function () {
                $this.attr('aria-label', cbxchangelog_edit.copycmds.copy_tip);
            }, 1000);
        });


        //attach datepicker
        $('#cbxchangelog_wrapper').on('click', '.cbxchangelog_datepicker', function (e) {
            //$(this).datetimepicker({
            $(this).datepicker({
                dateFormat: 'yy-mm-dd',
                //timeFormat:"HH:mm:ss",
                showOn: 'focus'
            }).focus();
        });


        $('#cbxchangelog_wrapper').on('click', '.toggle-release', function (e) {
            e.preventDefault();

            var $this = $(this);
            $this.toggleClass('cbx-icon-plus-white');
            $this.toggleClass('cbx-icon-minus-white');
            var $parent = $this.closest('.cbxchangelog_release');
            $parent.find('.release-content').toggle();
        });

        //sorting, add/remove etc


        var $release_template = $('#release_template').html();
        var $feature_template = $('#feature_template').html();
        Mustache.parse($release_template);   // optional, speeds up future uses
        Mustache.parse($feature_template);   // optional, speeds up future uses

        //sorting single feature
        $('#cbxchangelog_wrapper .release-feature-wrap').each(function (index, element) {
            var $element = $(element);

            //sort sponsor item
            $element.sortable({
                group: 'feature_wrap_' + index,
                nested: false,
                vertical: true,
                horizontal: false,
                pullPlaceholder: true,
                handle: '.move-feature',
                placeholder: 'feature_placeholder',
                itemSelector: 'p.feature',
                containerSelector: $element,
            });

        });

        var $changelog_wrapper = $('#cbxchangelog_wrapper');

        $('#cbxchangelog_metabox').on('click', 'a.cbxchangelog_add_release', function (e) {
            e.preventDefault();

            var $this = $(this);
            var $position = $this.data('position');

            //var $counter           = Number($this.attr('data-counter'));
            var $counter = Number($changelog_wrapper.data('counter'));

            //var rendered = Mustache.render($release_template, {increment: $counter, incrementplus: ($counter + 1)});
            var rendered = Mustache.render($release_template, {increment: ($counter - 1), incrementplus: ($counter)});

            if (typeof $position === 'undefined' || $position === 'bottom') {
                $changelog_wrapper.append(rendered);
            } else {
                $changelog_wrapper.prepend(rendered);
            }

            $counter++;
            $changelog_wrapper.data('counter', $counter);

            //sorting single feature
            $('#cbxchangelog_wrapper .release-feature-wrap').each(function (index, element) {
                var $element = $(element);

                //sort sponsor item
                $element.sortable({
                    group: 'feature_wrap_' + index,
                    nested: false,
                    vertical: true,
                    horizontal: false,
                    pullPlaceholder: true,
                    handle: '.move-feature',
                    placeholder: 'feature_placeholder',
                    itemSelector: 'p.feature',
                    containerSelector: $element,
                });
            });
        });

        //remove any release
        $changelog_wrapper.on('click', '.trash-release', function (e) {
            e.preventDefault();

            var $this = $(this);
            var $post_id = Number($this.data('post-id'));
            var $relesae_id = Number($this.data('id'));

            var notifier = new AWN(awn_options);

            var onCancel = () => {
            };

            var onOk = () => {

                if ($relesae_id == 0) {
                    $this.closest('.cbxchangelog_release').fadeOut('slow', function () {
                        $(this).remove();
                    });
                } else {
                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        url: cbxchangelog_edit.ajaxurl,
                        data: {
                            action: 'cbxchangelog_release_delete',
                            security: cbxchangelog_edit.nonce,
                            post_id: $post_id,
                            release_id: $relesae_id
                        },
                        success: function (data, textStatus, XMLHttpRequest) {

                            if (data.success) {
                                $this.closest('.cbxchangelog_release').fadeOut('slow', function () {
                                    $(this).remove();
                                });

                                new AWN(awn_options).success(data.message);
                            } else {
                                new AWN(awn_options).alert(data.message);
                            }
                        }//end of success
                    });//end of ajax
                }
            };

            notifier.confirm(
                cbxchangelog_edit.deleteconfirm_desc,
                onOk,
                onCancel,
                {
                    labels: {
                        confirm: cbxchangelog_edit.deleteconfirm
                    }
                }
            );

        });

        //add new feature
        $changelog_wrapper.on('click', '.add-feature', function (e) {
            e.preventDefault();

            var $this = $(this);
            var $parent = $this.parents('.release-feature-wrap');
            var $counter = $parent.data('boxincrement');

            var rendered = Mustache.render($feature_template, {increment: $counter});
            $parent.prepend(rendered);

        });

        //show/hide feature note
        $changelog_wrapper.on('click', '.add-feature-note', function (e) {
            e.preventDefault();

            var $this = $(this);
            var $parent = $this.parents('.feature');
            //$parent.find('.feature_note_textarea').toggle();
            $parent.find('.feature_note').toggle();

           /* var $counter = $parent.data('boxincrement');

            var rendered = Mustache.render($feature_template, {increment: $counter});
            $parent.prepend(rendered);*/

        });

        //remove any feature
        $changelog_wrapper.on('click', '.trash-feature', function (e) {
            e.preventDefault();

            var $this = $(this);

            var $parent_feature_wrap = $this.closest('.release-feature-wrap');

            if ($parent_feature_wrap.find('.feature').length > 1) {
                //delete if there are more than one features, otherwise edit or delete the total release.

                var notifier = new AWN(awn_options);

                var onCancel = () => {
                };

                var onOk = () => {

                    $this.closest('.feature').fadeOut('slow', function () {
                        $(this).remove();
                    });
                };

                notifier.confirm(
                    cbxchangelog_edit.deleteconfirm_desc,
                    onOk,
                    onCancel,
                    {
                        labels: {
                            confirm: cbxchangelog_edit.deleteconfirm
                        }
                    }
                );


            } else {

                new AWN(awn_options).alert(cbxchangelog_edit.deletelastitem);
            }


        });

        //resync release no/id with index (top to bottom or bottom to top)
        $('#cbxchangelog_toolbar_extras').on('click', 'a.cbxchangelog_resync_releases', function (e) {
            e.preventDefault();

            var $this = $(this);
            var $post_id = Number($this.data('post-id'));
            var $dir = Number($this.data('dir'));
            var $confirm_desc = ($dir) ? cbxchangelog_edit.resync.confirm_desc : cbxchangelog_edit.resync.confirm_desc_alt;

            var notifier = new AWN(awn_options);

            var onCancel = () => {
            };

            var onOk = () => {
                $this.prop('disabled', true);
                $this.addClass('running');

                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: cbxchangelog_edit.ajaxurl,
                    data: {
                        action: 'cbxchangelog_release_resync',
                        security: cbxchangelog_edit.nonce,
                        post_id: $post_id,
                        dir: $dir
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
                        if (data.success) {
                            new AWN(awn_options).success(data.message);

                            location.reload();
                        } else {
                            new AWN(awn_options).alert(data.message);
                        }
                    }//end of success
                });//end of ajax
            };

            notifier.confirm(
                $confirm_desc,
                onOk,
                onCancel,
                {
                    labels: {
                        confirm: cbxchangelog_edit.resync.confirm
                    }
                }
            );
        });

        //delete all releases
        $('#cbxchangelog_toolbar_extras').on('click', 'a.cbxchangelog_delete_releases', function (e) {
            e.preventDefault();

            var $this = $(this);
            var $post_id = Number($this.data('post-id'));

            var notifier = new AWN(awn_options);

            var onCancel = () => {
            };

            var onOk = () => {
                $this.prop('disabled', true);
                $this.addClass('running');

                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: cbxchangelog_edit.ajaxurl,
                    data: {
                        action: 'cbxchangelog_delete_releases',
                        security: cbxchangelog_edit.nonce,
                        post_id: $post_id
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
                        if (data.success) {
                            new AWN(awn_options).success(data.message);

                            location.reload();
                        } else {
                            new AWN(awn_options).alert(data.message);
                        }
                    }//end of success
                });//end of ajax
            };

            notifier.confirm(
                cbxchangelog_edit.deleteconfirm_all_releases,
                onOk,
                onCancel,
                {
                    labels: {
                        confirm: cbxchangelog_edit.deleteconfirm
                    }
                }
            );
        });

        //sorting releases
        $changelog_wrapper.sortable({
            group: 'cbxchangelog_releases',
            nested: false,
            vertical: true,
            horizontal: false,
            pullPlaceholder: true,
            handle: '.move-release',
            placeholder: 'cbxchangelog_release_placeholder',
            itemSelector: 'div.cbxchangelog_release',
            containerSelector: 'div#cbxchangelog_wrapper'
        });

        if(cbxchangelog_edit.current_post_type == 'cbxchangelog'){
            $('.wrap').addClass('cbx-chota cbxchangelog-page-wrapper cbxchangelog-addedit-wrapper');
            $('#search-submit').addClass('button primary');
            $('#post-query-submit').addClass('button primary');
            $('.button.action').addClass('button primary');
            $('.save-post-status').addClass('button primary mt-10');
            $('.save-post-visibility').addClass('button primary mt-10');
            $('.save-timestamp').addClass('button primary mt-10');
            $('.preview.button').addClass('button secondary');
            $('.cancel-post-status').addClass('button secondary mt-10');
            $('.cancel-post-visibility').addClass('button secondary mt-10');
            $('.cancel-timestamp').addClass('button secondary mt-10');
            $('.page-title-action').addClass('button primary');
            $('#save-post').addClass('button primary');
            $('#publish').addClass('button primary');
            $('#screen-meta').addClass('cbx-chota cbxchangelog-page-wrapper cbxchangelog-logs-wrapper');
            $('#screen-options-apply').addClass('primary');
        }

    });

})(jQuery);
