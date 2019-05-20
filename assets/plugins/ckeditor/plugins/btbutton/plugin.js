(function () {

    CKEDITOR.plugins.add('btbutton', {
            lang: 'en',
            requires: 'widget,dialog',
            icons: 'btbutton',
            init: function (editor) {
                var lang = editor.lang.btbutton;

                CKEDITOR.dialog.add('btbutton', this.path + 'dialogs/btbutton.js');

                // Add widget
                editor.ui.addButton('btbutton', {
                    label: lang.buttonTitle,
                    command: 'btbutton',
                    icon: this.path + 'icons/btbutton.png'
                });

                editor.widgets.add('btbutton', {
                    dialog: 'btbutton',

                    init: function () {

                    },

                    template: '<a class="btn">' + '<span class="text"></span>' + '</a>',

                    data: function () {
                        var $el = jQuery(this.element.$);

                        if (this.data.btntype) {
                            $el.removeClass('btn-link btn-default btn-primary btn-info btn-success btn-warning btn-danger').addClass(this.data.btntype);
                        }

                        if (this.data.btnsize) {
                            $el.removeClass('btn-xs btn-sm btn-lg').addClass(this.data.btnsize);
                        }

                        if (this.data.href) {
                            $el.attr('href', this.data.href);
                        }

                        if (this.data.target && this.data.target != '') {
                            $el.attr('target', this.data.target);
                        }

                        if (this.data.text) {
                            jQuery('.text', $el).text(this.data.text);
                        }

                        if (this.data.hasOwnProperty('bsiconleft')) {
                            jQuery('.bs-icon-left', $el).remove();
                            if (this.data.bsiconleft) {
                                $el.prepend('<span class="bs-icon-left glyphicon ' + this.data.bsiconleft + '"></span>');
                            }
                        }

                        if (this.data.hasOwnProperty('bsiconright')) {
                            jQuery('.bs-icon-right', $el).remove();
                            if (this.data.bsiconright) {
                                $el.append('<span class="bs-icon-right glyphicon ' + this.data.bsiconright + '"></span>');
                            }
                        }

                        if (this.data.hasOwnProperty('faiconleft')) {
                            jQuery('.fa-icon-left', $el).remove();
                            if (this.data.faiconleft) {
                                $el.prepend('<i class="fa fa-icon-left ' + this.data.faiconleft + '"></i>');
                            }
                        }

                        if (this.data.hasOwnProperty('faiconright')) {
                            jQuery('.fa-icon-right', $el).remove();
                            if (this.data.faiconright) {
                                $el.append('<i class="fa fa-icon-right ' + this.data.faiconright + '"></i>');
                            }
                        }
                    },

                    requiredContent: 'a(btn)',

                    upcast: function (element) {
                        return element.name == 'a' && element.hasClass('btn');
                    }
                });
            }
        }
    );

})();






