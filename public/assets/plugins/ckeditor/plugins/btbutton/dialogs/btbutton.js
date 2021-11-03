CKEDITOR.dialog.add('btbutton', function (editor) {
    var lang = editor.lang.btbutton;

    return {
        title: 'Button Link',
        minWidth: 500,
        minHeight: 150,
        resizable: false,
        contents: [
            {
                id: 'info',
                label: lang.tabInfo,
                accessKey: 'I',
                elements: [
                    {
                        type: "hbox",
                        widths: ["50%", "50%"],
                        children: [
                            {
                                id: 'btntype',
                                type: 'select',
                                label: lang.buttonStyleLabel,
                                items: [
                                    [lang.buttonLink, 'btn-link'],
                                    [lang.buttonDefault, 'btn-default'],
                                    [lang.buttonPrimary, 'btn-primary'],
                                    [lang.buttonSuccess, 'btn-success'],
                                    [lang.buttonInfo, 'btn-info'],
                                    [lang.buttonWarning, 'btn-warning'],
                                    [lang.buttonDanger, 'btn-danger']
                                ],
                                setup: function (widget) {
                                    this.setValue(widget.data.btntype || 'btn-default');
                                },
                                commit: function (widget) {
                                    widget.setData('btntype', this.getValue());
                                }
                            },
                            {
                                id: 'btnsize',
                                type: 'select',
                                label: lang.buttonSizeLabel,
                                items: [
                                    [lang.buttonSizeExSmall, 'btn-xs'],
                                    [lang.buttonSizeSmall, 'btn-sm'],
                                    [lang.buttonSizeNormal, ''],
                                    [lang.buttonSizeLarge, 'btn-lg']
                                ],
                                setup: function (widget) {
                                    this.setValue(widget.data.btnsize || '');
                                },
                                commit: function (widget) {
                                    widget.setData('btnsize', this.getValue());
                                }
                            }
                        ]
                    },
                    {
                        type: "hbox",
                        widths: ["50%", "50%"],
                        children: [
                            {
                                id: 'text',
                                type: 'text',
                                width: '200px',
                                required: true,
                                label: lang.buttonTextLabel,
                                setup: function (widget) {
                                    this.setValue(widget.data.text || 'A Button');
                                },
                                commit: function (widget) {
                                    widget.setData('text', this.getValue());
                                }
                            },
                            {
                                id: 'href',
                                type: 'text',
                                width: '200px',
                                required: true,
                                label: lang.buttonUrlLabel,
                                setup: function (widget) {
                                    this.setValue(widget.data.href || '#');
                                },
                                commit: function (widget) {
                                    widget.setData('href', this.getValue());
                                }
                            }
                        ]
                    }
                ]
            },
            {
                id: 'target',
                label: lang.tabTarget,
                elements: [
                    {
                        id: "target",
                        type: "select",
                        label: lang.buttonTargetLabel,
                        items: [
                            ['Not Set', ''],
                            ['Frame', "frame"],
                            ['Popup', "popup"],
                            ['New Window (_blank)', "_blank"],
                            ['Topmost Window (_top)', "_top"],
                            ['Same Window (_self)', "_self"],
                            ['Parent Window (_parent)', "_parent"]
                        ],
                        setup: function (widget) {
                            this.setValue(widget.data.target || '');
                        },
                        commit: function (widget) {
                            widget.setData('target', this.getValue());
                        }
                    }
                ]
            },
            {
                id: 'icons',
                label: lang.tabIcons,
                elements: [
                    {
                        type: "hbox",
                        widths: ["50%", "50%"],
                        children: [
                            {
                                type: 'vbox',
                                children: [
                                    {
                                        type: 'html',
                                        html: '<strong>Bootstrap Glyphicon</strong>' +
                                        '<p><a href="http://getbootstrap.com/components/#glyphicons" target="_blank" style="padding: 0px; vertical-align: top;">List of Icons</a></p><br/>' +
                                        '<p>e.g. <em>glyphicon-pencil</em></p>'
                                    },
                                    {
                                        id: 'bsiconleft',
                                        type: 'text',
                                        width: '150px',
                                        label: 'Left Icon',
                                        setup: function (widget) {
                                            this.setValue(widget.data.bsiconleft || '');
                                        },
                                        commit: function (widget) {
                                            widget.setData('bsiconleft', this.getValue());
                                        }
                                    },
                                    {
                                        id: 'bsiconright',
                                        type: 'text',
                                        width: '150px',
                                        label: 'Right Icon',
                                        setup: function (widget) {
                                            this.setValue(widget.data.bsiconright || '');
                                        },
                                        commit: function (widget) {
                                            widget.setData('bsiconright', this.getValue());
                                        }
                                    }
                                ]
                            },
                            {
                                type: 'vbox',
                                children: [
                                    {
                                        type: 'html',
                                        html: '<strong>Font Awesome</strong>' +
                                        '<p><a href="http://fortawesome.github.io/Font-Awesome/cheatsheet/" target="_blank" style="padding: 0px; vertical-align: top;">List of Icons</a></p><br/>' +
                                        '<p>e.g. <em>fa-arrow-right</em></p>'
                                    },
                                    {
                                        id: 'faiconleft',
                                        type: 'text',
                                        width: '150px',
                                        label: 'Left Icon',
                                        setup: function (widget) {
                                            this.setValue(widget.data.faiconleft || '');
                                        },
                                        commit: function (widget) {
                                            widget.setData('faiconleft', this.getValue());
                                        }
                                    },
                                    {
                                        id: 'faiconright',
                                        type: 'text',
                                        width: '150px',
                                        label: 'Right Icon',
                                        setup: function (widget) {
                                            this.setValue(widget.data.faiconright || '');
                                        },
                                        commit: function (widget) {
                                            widget.setData('faiconright', this.getValue());
                                        }
                                    }
                                ]
                            }
                        ]
                    }
                ]
            }
        ]
    };
});
