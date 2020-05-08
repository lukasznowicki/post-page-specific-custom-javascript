jQuery(function ($) {
    const CustomJSApp = {
        bodyColor: null,
        optionTabs: null,
        accountLinks: null,
        dependencyLinks: null,
        suppressLinks: null,
        flagShow: null,
        idToggleLinks: null,
        fillUrlLinks: null,
        urlToFill: null,
        setDefaultVariables: function () {
            this.bodyColor = window.getComputedStyle(document.body, null).getPropertyValue('background-color');
            this.optionTabs = $('#optionTabs');
            this.accountLinks = $('.theAccount a');
            this.flagShow = $('.theFlagShow');
            this.dependencyLinks = $('.jsiDeps');
            this.suppressLinks = $('.jsiSuppress');
            this.idToggleLinks = $('.cjsToggleById');
            this.fillUrlLinks = $('.cjsFillUrl');
            this.urlToFill = $('#cjs_libraries_url');
        },
        setTabbedOptions: function () {
            if (1 === this.optionTabs.length) {
                this.optionTabs.tabs({})
            }
        },
        setAccountLinks: function () {
            const that = this;
            this.accountLinks.on('click', function (event) {
                event.stopPropagation();
                event.preventDefault();
                that.flagShow.hide(0);
                that.accountLinks.css('opacity', '.4');
                $(this).css('opacity', '1');
                $($(this).data('flag_to_show')).show(150);
            });
        },
        setDependencyLinks: function () {
            this.dependencyLinks.on('click', function (event) {
                event.stopPropagation();
                event.preventDefault();
                $(this).next('.jsiDepsList').toggleClass('jsiVisible');
            });
            this.suppressLinks.on('click', function (event) {
                $(this).next('.jsiInfoBox').toggleClass('jsiVisible');
                event.stopPropagation();
                event.preventDefault();
            });
        },
        setIdToggleLinks: function () {
            this.idToggleLinks.on('change', function (event) {
                const toggleElement = $('#' + $(this).attr('id') + '_toggle');
                if ($(this).prop('checked')) {
                    toggleElement.show(100);
                } else {
                    toggleElement.hide(100);
                }
            });
        },
        setFillUrl: function () {
            const that = this;
            this.fillUrlLinks.on('click', function (event) {
                event.stopPropagation();
                event.preventDefault();
                that.urlToFill.val($(this).data('url'));
            });
        },
        Init: function () {
            this.setDefaultVariables();
            this.setTabbedOptions();
            this.setAccountLinks();
            this.setDependencyLinks();
            this.setIdToggleLinks();
            this.setFillUrl();
        }
    };
    CustomJSApp.Init();
});