(function ($) {
    'use strict';

    function cbxchangelog_copyStringToClipboard(str) {
        // Create new element
        var el   = document.createElement('textarea');
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
                tip          : cbxchangelog_edit.awn_options.tip,
                info         : cbxchangelog_edit.awn_options.info,
                success      : cbxchangelog_edit.awn_options.success,
                warning      : cbxchangelog_edit.awn_options.warning,
                alert        : cbxchangelog_edit.awn_options.alert,
                async        : cbxchangelog_edit.awn_options.async,
                confirm      : cbxchangelog_edit.awn_options.confirm,
                confirmOk    : cbxchangelog_edit.awn_options.confirmOk,
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
                group            : 'feature_wrap_' + index,
                nested           : false,
                vertical         : true,
                horizontal       : false,
                pullPlaceholder  : true,
                handle           : '.move-feature',
                placeholder      : 'feature_placeholder',
                itemSelector     : 'p.feature',
                containerSelector: $element,
            });

        });

        $('#cbxchangelog_metabox').on('click', 'a.cbxchangelog_add_release', function (e) {
            e.preventDefault();

            var $this    = $(this);
            var $counter = Number($this.attr('data-counter'));

            //var rendered = Mustache.render($release_template, {increment: $counter, incrementplus: ($counter + 1)});
            var rendered = Mustache.render($release_template, {increment: ($counter - 1), incrementplus: ($counter)});
            $('#cbxchangelog_wrapper').append(rendered);

            $counter++;
            $this.attr('data-counter', $counter);

            //sorting single feature
            $('#cbxchangelog_wrapper .release-feature-wrap').each(function (index, element) {
                var $element = $(element);

                //sort sponsor item
                $element.sortable({
                    group            : 'feature_wrap_' + index,
                    nested           : false,
                    vertical         : true,
                    horizontal       : false,
                    pullPlaceholder  : true,
                    handle           : '.move-feature',
                    placeholder      : 'feature_placeholder',
                    itemSelector     : 'p.feature',
                    containerSelector: $element,
                });
            });
        });

        //remove any release
        $('#cbxchangelog_wrapper').on('click', '.trash-release', function (e) {
            e.preventDefault();

            var $this       = $(this);
            var $post_id    = Number($this.data('post-id'));
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
                        type    : 'post',
                        dataType: 'json',
                        url     : cbxchangelog_edit.ajaxurl,
                        data    : {
                            action    : 'cbxchangelog_release_delete',
                            security  : cbxchangelog_edit.nonce,
                            post_id   : $post_id,
                            release_id: $relesae_id
                        },
                        success : function (data, textStatus, XMLHttpRequest) {

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
        $('#cbxchangelog_wrapper').on('click', '.add-feature', function (e) {
            e.preventDefault();

            var $this    = $(this);
            var $parent  = $this.parents('.release-feature-wrap');
            var $counter = $parent.data('boxincrement');

            var rendered = Mustache.render($feature_template, {increment: $counter});
            $parent.prepend(rendered);

        });

        //remove any feature
        $('#cbxchangelog_wrapper').on('click', '.trash-feature', function (e) {
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

        //sorting releases
        $('#cbxchangelog_wrapper').sortable({
            group            : 'cbxchangelog_releases',
            nested           : false,
            vertical         : true,
            horizontal       : false,
            pullPlaceholder  : true,
            handle           : '.move-release',
            placeholder      : 'cbxchangelog_release_placeholder',
            itemSelector     : 'div.cbxchangelog_release',
            containerSelector: 'div#cbxchangelog_wrapper'
        });


        $('.wrap').addClass('cbx-chota cbxchangelog-page-wrapper cbxchangelog-addedit-wrapper');
        $('#search-submit').addClass('button primary');
        $('#post-query-submit').addClass('button primary');
        $('.button.action').addClass('button primary');
        $('.page-title-action').addClass('button primary');
        $('#save-post').addClass('button primary');
        $('#publish').addClass('button primary');
        $('#screen-meta').addClass('cbx-chota cbxchangelog-page-wrapper cbxchangelog-logs-wrapper');
        $('#screen-options-apply').addClass('primary');
    });

})(jQuery);
