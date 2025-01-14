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
        /*var awn_options = {
            labels: {
                tip          : cbxchangelog_listing.awn_options.tip,
                info         : cbxchangelog_listing.awn_options.info,
                success      : cbxchangelog_listing.awn_options.success,
                warning      : cbxchangelog_listing.awn_options.warning,
                alert        : cbxchangelog_listing.awn_options.alert,
                async        : cbxchangelog_listing.awn_options.async,
                confirm      : cbxchangelog_listing.awn_options.confirm,
                confirmOk    : cbxchangelog_listing.awn_options.confirmOk,
                confirmCancel: cbxchangelog_listing.awn_options.confirmCancel
            }
        };*/

        //click to copy shortcode
        $('.cbxballon_ctp').on('click', function (e) {
            e.preventDefault();

            var $this = $(this);
            cbxchangelog_copyStringToClipboard($this.prev('.cbxshortcode').text());

            $this.attr('aria-label', cbxchangelog_listing.copycmds.copied_tip);

            window.setTimeout(function () {
                $this.attr('aria-label', cbxchangelog_listing.copycmds.copy_tip);
            }, 1000);
        });

        $('.wrap').addClass('cbx-chota cbxchangelog-page-wrapper cbxchangelog-addedit-wrapper');
        $('#search-submit').addClass('button primary');
        $('#post-query-submit').addClass('button primary');
        //$('.button.action').addClass('button outline primary');
        $('.button.action').addClass('button primary');
        $('.page-title-action').addClass('button primary');
        $('#save-post').addClass('button primary');
        //$('#doaction').addClass('button primary');
        $('#publish').addClass('button primary');

        //$(cbxchangelog_admin_js_vars.global_setting_link_html).insertAfter('.page-title-action');
        $('#screen-meta').addClass('cbx-chota cbxchangelog-page-wrapper cbxchangelog-logs-wrapper');
        $('#screen-options-apply').addClass('primary');


        $('#post-search-input').attr('placeholder', cbxchangelog_listing.placeholder.search);

    });

})(jQuery);