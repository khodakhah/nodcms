CKEDITOR.dialog.add( 'widgetfoundationAlert', function( editor ) {
    var clientHeight = document.documentElement.clientHeight,
        alertTypes = CKEDITOR.config.widgetfoundationAlert_alertTypes,
        alertTypesSelect = [],
        alertName;

    for ( alertName in alertTypes ) {
        alertTypesSelect.push( [ alertTypes[ alertName ], alertName ] );
    }


    // Size adjustments.
    /*var size = CKEDITOR.document.getWindow().getViewPaneSize(),
        // Make it maximum 800px wide, but still fully visible in the viewport.
        width = Math.min( size.width - 70, 800 ),
        // Make it use 2/3 of the viewport height.
        height = size.height / 1.5;
        // Low resolution settings.
        if ( clientHeight < 650 )
            height = clientHeight - 220;*/

    return {
        title: 'Edit Alert Type',
        minWidth: 200,
        minHeight: 100,
        contents: [
            {
                id: 'info',
                elements: [
                    {
                        id: 'type',
                        type: 'select',
                        label: 'Alert Type',
                        items: alertTypesSelect,
                        required: true,
                        validate: CKEDITOR.dialog.validate.notEmpty('Alert type required'),
                        setup: function( widget ) {
                            this.setValue( widget.data.type != undefined ? widget.data.type : 'alert');
                        },
                        commit: function( widget ) {
                            widget.setData( 'type', this.getValue() );
                        }
                    }/*,
                    {
                        id: 'alertText',
                        type: 'textarea',
                        label: 'Alert Content',
                        setup: function( widget ) {
                            this.setValue( widget.data.alertText );
                        },
                        commit: function( widget ) {
                            widget.setData( 'alertText', this.getValue() );
                        },
                        required: true,
                        validate: CKEDITOR.dialog.validate.notEmpty('Content required'),
                        inputStyle: 'cursor:auto;' +
                            'width:' + width + 'px;' +
                            'height:' + height + 'px;' +
                            'tab-size:4;' +
                            'text-align:left;',
                            'class': 'cke_source'
                    }*/
                ]
            }
        ]
    };
} );