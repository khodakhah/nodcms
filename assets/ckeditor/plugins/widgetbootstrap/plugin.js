// Init default alert classes

CKEDITOR.config.widgetbootstrapAlert_alertTypes = {
    'alert': 'Alert',
    'info': 'Info',
    'warning': 'Warning',
    'success': 'Success'
};


CKEDITOR.plugins.add( 'widgetbootstrap', {
    requires: 'widget',

    icons: 'widgetbootstrapLeftCol,widgetbootstrapRightCol,widgetbootstrapTwoCol,widgetbootstrapThreeCol,widgetbootstrapAlert',

    /*defaults : {
        name: 'accordion',
        count: 3,
        activePanel: 1,
        multiExpand: false
    },*/

    init: function( editor ) {
        
        // Configurable settings
        //var allowedWidget = editor.config.widgetbootstrap_allowedWidget != undefined ? editor.config.widgetbootstrap_allowedFull :
        //    'p h2 h3 h4 h5 h6 span br ul ol li strong em img[!src,alt,width,height]';
        var allowedFull = editor.config.widgetbootstrap_allowedFull != undefined ? editor.config.widgetbootstrap_allowedFull :
            'p a div span h2 h3 h4 h5 h6 section article iframe object embed strong b i em cite pre blockquote small sub sup code ul ol li dl dt dd table thead tbody th tr td img caption mediawrapper br[href,src,target,width,height,colspan,span,alt,name,title,class,id,data-options]{text-align,float,margin}(*);'
        //var allowedText = editor.config.widgetbootstrap_allowedText != undefined ? editor.config.widgetbootstrap_allowedFull :
        //    'p span br ul ol li strong em';


        allowedWidget = allowedFull;
        //allowedText = allowedWidget;

        var showButtons = editor.config.widgetbootstrapShowButtons != undefined ? editor.config.widgetbootstrapShowButtons : true;

        // Define the widgets
        editor.widgets.add( 'widgetbootstrapLeftCol', {

            button: showButtons ? 'Add left column box' : undefined,

            template:
                '<div class="row two-col-left">' +
                    '<div class="col-md-3 col-sidebar"><p><img src="http://placehold.it/300x250&text=Image" /></p></div>' +
                    '<div class="col-md-9 col-main"><p>Content</p></div>' +
                '</div>',

            editables: {
                col1: {
                    selector: '.col-sidebar',
                    allowedContent: allowedWidget
                },
                col2: {
                    selector: '.col-main',
                    allowedContent: allowedWidget
                }
            },

            allowedContent: allowedFull,

            upcast: function( element ) {
                return element.name == 'div' && element.hasClass( 'two-col-right-left' );
            }
            
        } );

        editor.widgets.add( 'widgetbootstrapRightCol', {

            button: showButtons ? 'Add right column box' : undefined,

            template:
                '<div class="row two-col-right">' +
                    '<div class="col-md-9 col-main"><p>Content</p></div>' +
                    '<div class="col-md-3 col-sidebar"><p><img src="http://placehold.it/300x250&text=Image" /></p></div>' +
                '</div>',

            editables: {
                col1: {
                    selector: '.col-sidebar',
                    allowedContent: allowedWidget
                },
                col2: {
                    selector: '.col-main',
                    allowedContent: allowedWidget
                }
            },

            allowedContent: allowedFull,

            upcast: function( element ) {
                return element.name == 'div' && element.hasClass( 'two-col-right' );
            }

        } );

        editor.widgets.add( 'widgetbootstrapTwoCol', {

            button: showButtons ? 'Add two column box' : undefined,

            template:
                '<div class="row two-col">' +
                    '<div class="col-md-6 col-1"><p><img src="http://placehold.it/500x280&text=Image" /></p><p>Content</p></div>' +
                    '<div class="col-md-6 col-2"><p><img src="http://placehold.it/500x280&text=Image" /></p><p>Content</p></div>' +
                '</div>',

            editables: {
                col1: {
                    selector: '.col-1',
                    allowedContent: allowedWidget
                },
                col2: {
                    selector: '.col-2',
                    allowedContent: allowedWidget
                }
            },

            allowedContent: allowedFull,

            upcast: function( element ) {
                return element.name == 'div' && element.hasClass( 'two-col' );
            }

        } );

        editor.widgets.add( 'widgetbootstrapThreeCol', {

            button: showButtons ? 'Add three column box' : undefined,

            template:
                '<div class="row three-col">' +
                    '<div class="col-md-4 col-1"><p><img src="http://placehold.it/400x225&text=Image" /></p><p>Text below</p></div>' +
                    '<div class="col-md-4 col-2"><p><img src="http://placehold.it/400x225&text=Image" /></p><p>Text below</p></div>' +
                    '<div class="col-md-4 col-3"><p><img src="http://placehold.it/400x225&text=Image" /></p><p>Text below</p></div>' +
                '</div>',

            editables: {
                col1: {
                    selector: '.col-1',
                    allowedContent: allowedWidget
                },
                col2: {
                    selector: '.col-2',
                    allowedContent: allowedWidget
                },
                col3: {
                    selector: '.col-3',
                    allowedContent: allowedWidget
                }
            },

            allowedContent: allowedFull,

            upcast: function( element ) {
                return element.name == 'div' && element.hasClass( 'three-col' );
            }

        } );

        editor.addCommand( 'openwidgetbootstrapAlert', new CKEDITOR.dialogCommand( 'widgetbootstrapAlert' ) );
        
        // Add foundation alert button
        // Textare decodes html entities
        //var textarea = new CKEDITOR.dom.element( 'textarea' );

        editor.widgets.add( 'widgetbootstrapAlert', {

            button: showButtons ? 'Add alert box' : undefined,
            dialog: 'widgetbootstrapAlert',

            template: '<div class="alert-box"><div class="alert-text">Some Text</span></div>',

            editables: {
                alertBox: {
                    selector: '.alert-text',
                    allowedContent: allowedWidget
                },
            },

            allowedContent: allowedFull,

            data: function() {
                var newData = this.data,
                    oldData = this.oldData;

                /*if( newData.alertText ) {
                    this.element.getChild( 0 ).setHtml( CKEDITOR.tools.htmlEncode( newData.alertText ) );
                }*/
                
                if ( oldData && newData.type != oldData.type )
                    this.element.removeClass(oldData.type);

                if ( newData.type )
                    this.element.addClass(newData.type);

                // Save oldData.
                this.oldData = CKEDITOR.tools.copy( newData );
            },

            upcast: function( el, data ) {
                if (el.name != 'div' || !el.hasClass( 'alert-box' ))
                    return;

                var childrenArray = el.children,
                    alertText;

                if ( childrenArray.length !== 1 || !( alertText = childrenArray[ 0 ] ).hasClass('alert-text'))
                    return;

                // Acceptable alert types
                var alertTypes = CKEDITOR.config.widgetbootstrapAlert_alertTypes;
                // Check alert types
                for(var i = 0; i < el.classes.length; i++) {
                    if(el.classes[i] != 'alert-box') {
                        for ( alertName in alertTypes ) {
                            if(el.classes[i] == alertName) {
                                data.type = alertName;
                            }
                        }
                    }
                }

                // Use textarea to decode HTML entities (#11926).
                //textarea.setHtml( alertText.getHtml() );
                //data.alertText = textarea.getValue();

                return el;
            },

            downcast: function( el ) {
                return el;
            }

        } );
        // Alert dialog
        CKEDITOR.dialog.add( 'widgetbootstrapAlert', this.path + 'dialogs/widgetbootstrapAlert.js' );

        /*CKEDITOR.dialog.add( 'widgetbootstrapAccordion', this.path + 'dialogs/widgetbootstrapAccordion.js' );
        editor.widgets.add( 'widgetbootstrapAccordion', {

            button: showButtons ? 'Add accordion box' : undefined,

            template:
                '<dl class="accordion" data-accordion><div class="col-1"></div></dl>',
     

            allowedContent: allowedFull,

            dialog: 'widgetbootstrapAccordion',

            upcast: function( element ) {
                return element.name == 'div' && element.hasClass( 'accordion' );
            },

            /*init: function() {
                var width = this.element.getStyle( 'width' );
                if ( width )
                    this.setData( 'width', width );
                if ( this.element.hasClass( 'align-left' ) )
                    this.setData( 'align', 'left' );
                if ( this.element.hasClass( 'align-right' ) )
                    this.setData( 'align', 'right' );
                if ( this.element.hasClass( 'align-center' ) )
                    this.setData( 'align', 'center' );
            },

            data: function() {

 
                var name = this.data.name != undefined ? this.data.name : 'accordion';
                var count = this.data.count != undefined ? this.data.count : 0;
                //@todo: var prevCount = this.data.prevCount != undefined ? this.data.prevCount : 

                // Add rows
                if (this.data.prevCount == undefined || this.data.prevCount < count) {
                    for (var i=this.data.prevCount != undefined ? this.data.prevCount : 1; i<=count; i++) {
                        var active = this.data.activePanel == i ? ' active' : '';
                        var template = 
                            '<dd class="accordion-navigation">' +
                                '<a href="#'+ name+i +'"><div class="accordion-header-'+i+'">Heading '+i+'</div></a>' +
                                '<div id="panel'+ name+i +'" class="content content-'+i+active+'">' +
                                  '' +
                                '</div>'
                            '</dd>'
                        var newPanel = CKEDITOR.dom.element.createFromHtml( template );
                        this.element.append(newPanel);
                    }

                    // For some reason, the initEditable call needs to come in a separate for loop
                    // the html code added wasn't in the DOM yet
                    for (var i=this.data.prevCount != undefined ? this.data.prevCount : 1; i<=count; i++) {
                        this.initEditable( 'heading'+i, {
                            selector: '.accordion-header-'+i
                        } );
                        this.initEditable( 'content'+i, {
                            selector: '.content-'+i
                        } ); 
                    }
                }

                // Remove rows
                if (this.data.prevCount != undefined && this.data.prevCount > count) {
                    // @todo
                }
                

                this.data.prevCount = i;
            }
        } );*/

        // Append the widget's styles when in the CKEditor edit page,
        // added for better user experience.
        // Assign or append the widget's styles depending on the existing setup.
        if (typeof editor.config.contentsCss == 'object') {
            editor.config.contentsCss.push(CKEDITOR.getUrl(this.path + 'contents.css'));
        }

        else {
            editor.config.contentsCss = [editor.config.contentsCss, CKEDITOR.getUrl(this.path + 'contents.css')];
        }

    }


} );