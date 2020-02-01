/*! wbcom-elementor-addons - v1.9.3 - 03-10-2017 */
(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
var EditorModule = function() {
	var self = this;

	this.init = function() {
		Backbone.$( window ).on( 'elementor:init', _.bind( this.onElementorReady, this ) );
	};

	this.onElementorReady = function() {
		self.onElementorInit();

		elementor.on( 'frontend:init', function() {
			self.onElementorFrontendInit();
		} );

		elementor.on( 'preview:loaded', function() {
			self.onElementorPreviewLoaded();
		} );
	};

	this.init();
};

EditorModule.prototype.onElementorInit = function() {};

EditorModule.prototype.onElementorPreviewLoaded = function() {};

EditorModule.prototype.onElementorFrontendInit = function() {};

EditorModule.extend = Backbone.View.extend;

module.exports = EditorModule;

},{}],2:[function(require,module,exports){
var WbcomElementorAddons = Marionette.Application.extend( {
	config: {},

	modules: {},

	initModules: function() {
		var QueryControl = require( 'modules/query-control/assets/js/editor' ),
			Forms = require( 'modules/forms/assets/js/editor' ),
			Library = require( 'modules/library/assets/js/editor' ),
			CustomCSS = require( 'modules/custom-css/assets/js/editor' ),
			Slides = require( 'modules/slides/assets/js/editor' ),
			GlobalWidget = require( 'modules/global-widget/assets/js/editor/editor' ),
			FlipBox = require( 'modules/flip-box/assets/js/editor/editor' ),
			ShareButtons = require( 'modules/share-buttons/assets/js/editor/editor' );

		this.modules = {
			queryControl: new QueryControl(),
			forms: new Forms(),
			library: new Library(),
			customCSS: new CustomCSS(),
			slides: new Slides(),
			globalWidget: new GlobalWidget(),
			flipBox: new FlipBox(),
			shareButtons: new ShareButtons()
		};
	},

	ajax: {
		send: function() {
			var args = arguments;

			args[0] = 'pro_' + args[0];

			return elementor.ajax.send.apply( elementor.ajax, args );
		}
	},

	translate: function( stringKey, templateArgs ) {
		return elementor.translate( stringKey, templateArgs, this.config.i18n );
	},

	onStart: function() {
		this.config = WbcomElementorAddonsConfig;

		this.initModules();

		Backbone.$( window ).on( 'elementor:init', this.onElementorInit );
	},

	onElementorInit: function() {
		elementorPro.libraryRemoveGetProButtons();

		elementor.debug.addURLToWatch( 'wbcom-elementor-addons/assets' );
	},

	libraryRemoveGetProButtons: function() {
		elementor.hooks.addFilter( 'elementor/editor/template-library/template/action-button', function( viewID, templateData ) {
			return templateData.isPro && ! elementorPro.config.isActive ? '#tmpl-wbcom-elementor-addons-template-library-activate-license-button' : '#tmpl-elementor-template-library-insert-button';
		} );
	}
} );

window.elementorPro = new WbcomElementorAddons();

elementorPro.start();

},{"modules/custom-css/assets/js/editor":4,"modules/flip-box/assets/js/editor/editor":6,"modules/forms/assets/js/editor":7,"modules/global-widget/assets/js/editor/editor":13,"modules/library/assets/js/editor":19,"modules/query-control/assets/js/editor":21,"modules/share-buttons/assets/js/editor/editor":23,"modules/slides/assets/js/editor":24}],3:[function(require,module,exports){
var Module = require( 'elementor-utils/module' );

module.exports = Module.extend( {
	elementType: null,

	__construct: function( elementType ) {
		this.elementType = elementType;

		this.addEditorListener();
	},

	addEditorListener: function() {
		var self = this;

		if ( self.onElementChange ) {
			var eventName = 'change';

			if ( 'global' !== self.elementType ) {
				eventName += ':' + self.elementType;
			}

			elementor.channels.editor.on( eventName, function( controlView, elementView ) {
				self.onElementChange( controlView.model.get( 'name' ),  controlView, elementView );
			} );
		}
	},

	getView: function( name ) {
		var editor = elementor.getPanelView().getCurrentPageView();
		return editor.children.findByModelCid( this.getControl( name ).cid );
	},

	getControl: function( name ) {
		var editor = elementor.getPanelView().getCurrentPageView();
		return editor.collection.findWhere( { name: name } );
	},

	addControlSpinner: function( name ) {
		this.getView( name ).$el.find( ':input' ).attr( 'disabled', true );
		this.getView( name ).$el.find( '.elementor-control-title' ).after( '<span class="elementor-control-spinner"><i class="fa fa-spinner fa-spin"></i>&nbsp;</span>' );
	},

	removeControlSpinner: function( name ) {
		this.getView( name ).$el.find( ':input' ).attr( 'disabled', false );
		this.getView( name ).$el.find( 'elementor-control-spinner' ).remove();
	},

	addSectionListener: function( section, callback ) {
		var self = this;

		elementor.channels.editor.on( 'section:activated', function( sectionName, editor ) {
			var model = editor.getOption( 'editedElementView' ).getEditModel(),
				currentElementType = model.get( 'elType' ),
				_arguments = arguments;

			if ( 'widget' === currentElementType ) {
				currentElementType = model.get( 'widgetType' );
			}

			if ( self.elementType === currentElementType && section === sectionName ) {
				setTimeout( function() {
					callback.apply( self, _arguments );
				}, 10 );
			}
		} );
	}
} );

},{"elementor-utils/module":26}],4:[function(require,module,exports){
var EditorModule = require( 'wbcom-elementor-addons/editor/editor-module' );

module.exports = EditorModule.extend( {
	onElementorInit: function() {
		var CustomCss = require( './editor/custom-css' );
		this.customCss = new CustomCss();
	}
} );

},{"./editor/custom-css":5,"wbcom-elementor-addons/editor/editor-module":1}],5:[function(require,module,exports){
module.exports = function() {
	var self = this;

	self.init = function() {
		elementor.hooks.addFilter( 'editor/style/styleText', self.addCustomCss );

		elementor.settings.page.addChangeCallback( 'custom_css', self.addPageCustomCss );

		self.addPageCustomCss.call(  elementor.settings.page,  elementor.settings.page.model.get( 'custom_css' ) );
	};

	self.addPageCustomCss = function( newValue ) {
		newValue = newValue.replace( /selector/g, '.elementor-page-' + elementor.config.post_id );

		this.controlsCSS.stylesheet.addRawCSS( 'page-settings-custom-css', newValue );
	};

	self.addCustomCss = function( css, view ) {
		var model = view.getEditModel(),
			customCSS = model.get( 'settings' ).get( 'custom_css' );

		if ( customCSS ) {
			css += customCSS.replace( /selector/g, '.elementor-element.elementor-element-' + view.model.id );
		}

		return css;
	};

	self.init();
};

},{}],6:[function(require,module,exports){
var EditorModule = require( 'wbcom-elementor-addons/editor/editor-module' );

module.exports = EditorModule.extend( {
	onElementorInit: function() {
		elementor.channels.editor.on( 'section:activated', this.onSectionActivated );
	},

	onSectionActivated: function( sectionName, editor ) {
		var editedElement = editor.getOption( 'editedElementView' );

		if ( 'flip-box' !== editedElement.model.get( 'widgetType' ) ) {
			return;
		}

		var isSideBSection = -1 !== [ 'section_side_b_content', 'section_style_b' ].indexOf( sectionName );

		editedElement.$el.toggleClass( 'elementor-flip-box--flipped', isSideBSection );

		var $backLayer = editedElement.$el.find( '.elementor-flip-box__back' );

		if ( isSideBSection ) {
            $backLayer.css( 'transition', 'none' );
		}

		if ( ! isSideBSection ) {
			setTimeout( function() {
				$backLayer.css( 'transition', '' );
			}, 10 );
		}
	}
} );

},{"wbcom-elementor-addons/editor/editor-module":1}],7:[function(require,module,exports){
var EditorModule = require( 'wbcom-elementor-addons/editor/editor-module' );

module.exports = EditorModule.extend( {
	onElementorInit: function() {
		var ReplyToField = require( './editor/reply-to-field' ),
			Mailchimp = require( './editor/mailchimp' ),
			Recaptcha = require( './editor/recaptcha' ),
			Shortcode = require( './editor/shortcode' );

		this.replyToField = new ReplyToField();
		this.mailchimp = new Mailchimp( 'form' );
		this.shortcode = new Shortcode( 'form' );
		this.recaptcha = new Recaptcha( 'form' );

		elementor.addControlView( 'Fields_map', require( './editor/fields-map-control' ) );
	}
} );

},{"./editor/fields-map-control":8,"./editor/mailchimp":9,"./editor/recaptcha":10,"./editor/reply-to-field":11,"./editor/shortcode":12,"wbcom-elementor-addons/editor/editor-module":1}],8:[function(require,module,exports){
module.exports = elementor.modules.controls.Repeater.extend( {
	onBeforeRender: function() {
		 this.$el.hide();
	},

	updateMap: function( fields ) {
		var self = this,
			savedMapObject = {};

		self.collection.each( function( model ) {
			savedMapObject[ model.get( 'remote_id' ) ] = model.get( 'local_id' );
		} );

		self.collection.reset();

		_.each( fields, function( field ) {
			var model = {
				remote_id: field.remote_id,
				remote_label: field.remote_label,
				remote_type: field.remote_type ? field.remote_type : '',
				remote_required: field.remote_required ? field.remote_required : false,
				local_id: savedMapObject[field.remote_id] ? savedMapObject[field.remote_id] : ''
			};

			self.collection.add( model );
		} );

		self.render();
	},

	onRender: function() {
		elementor.modules.controls.Base.prototype.onRender.apply( this, arguments );

		var self = this;

		self.children.each( function( view ) {
			var localFieldsControl = view.children.last(),
				options = {
					'': '- ' + elementor.translate( 'None' ) + ' -'
				},
				label = view.model.get( 'remote_label' );

			if ( view.model.get( 'remote_required' ) ) {
				label += '<span class="elementor-required">*</span>';
			}

			_.each( self.elementSettingsModel.get( 'form_fields' ).models, function( model, index ) {

				// If it's an email field, add only email fields from thr form
				var remoteType = view.model.get( 'remote_type' );

				if ( 'text' !== remoteType && remoteType !== model.get( 'field_type' ) ) {
					return;
				}

				options[ model.get( '_id' ) ] = model.get( 'field_label' ) || 'Field #' + ( index + 1 );
			} );

			localFieldsControl.model.set( 'label',  label );
			localFieldsControl.model.set( 'options', options );
			localFieldsControl.render();

			view.$el.find( '.elementor-repeater-row-tools' ).hide();
			view.$el.find( '.elementor-repeater-row-controls' )
				.removeClass( 'elementor-repeater-row-controls' )
				.find( '.elementor-control' )
				.css( {
					paddingBottom: 0
				} );
		} );

		self.$el.find( '.elementor-button-wrapper' ).remove();

		if ( self.children.length ) {
			self.$el.show();
		}
	}
} );

},{}],9:[function(require,module,exports){
var ElementEditorModule = require( 'wbcom-elementor-addons/editor/element-editor-module' );

module.exports = ElementEditorModule.extend( {
	cache: {},

	onElementChange: function( setting ) {
		switch ( setting ) {
			case 'mailchimp_api_key':
				this.onMailchimpApiKeyUpdate();
				break;
			case 'mailchimp_list':
				this.onMailchimpListUpdate();
				break;
		}
	},

	onMailchimpApiKeyUpdate: function() {
		var self = this,
			controlView = self.getView( 'mailchimp_api_key' );

		if ( ! controlView.getControlValue().match( /[0-9a-z-]{36,37}/ ) ) {
			self.updateOptions( 'mailchimp_list', [] );
			self.getView( 'mailchimp_list' ).setValue( '' );
			return;
		}

		self.addControlSpinner( 'mailchimp_list' );

		self.getMailchimpCache( 'lists', controlView.getControlValue() ).done( function( json ) {
			self.updateOptions( 'mailchimp_list', json.data.lists );
		} );
	},

	onMailchimpListUpdate: function() {
		var self = this;

		self.updateOptions( 'mailchimp_groups', [] );
		self.getView( 'mailchimp_groups' ).setValue( '' );
		self.updatMailchimpList();
	},

	updatMailchimpList: function() {
		var self = this,
		controlView = self.getView( 'mailchimp_list' );

		if ( ! controlView.getControlValue() ) {
			return;
		}

		self.addControlSpinner( 'mailchimp_groups' );

		self.getMailchimpCache( 'list_details', controlView.getControlValue(), {
			mailchimp_list: controlView.getControlValue()
		} ).done( function( json ) {
			self.updateOptions( 'mailchimp_groups', json.data.list_details.groups );
			self.getView( 'mailchimp_fields_map' ).updateMap( json.data.list_details.fields );
		} );
	},

	getMailchimpCache: function( type, cacheKey, requestArgs ) {
		if ( _.has( this.cache[ type ], cacheKey ) ) {
			var data = {};
			data[ type ] = this.cache[ type][ cacheKey ];
			return jQuery.Deferred().resolve( {
				data: data
			} );
		}

		requestArgs = _.extend( {}, requestArgs, {
			service: 'mailchimp',
			mailchimp_action: type,
			api_key: this.getView( 'mailchimp_api_key' ).getControlValue()
		} );

		var self = this;

		return elementorPro.ajax.send( 'forms_panel_action_data', {
			data: requestArgs,
			success: function( data ) {
				self.cache[ type ] = _.extend( {}, self.cache[ type ] );
				self.cache[ type ][ cacheKey ] = data[ type ];
			}
		} );
	},

	updateOptions: function( name, options ) {
		if ( this.getView( name ) ) {
			this.getControl( name ).set( 'options', options );
			this.getView( name ).render();
		}
	},

	onSectionActive: function() {
		this.onMailchimpApiKeyUpdate();
		this.updatMailchimpList();
	},

	onInit: function() {
		this.addSectionListener( 'section_mailchimp', this.onSectionActive );
	}
} );

},{"wbcom-elementor-addons/editor/element-editor-module":3}],10:[function(require,module,exports){
var ElementEditorModule = require( 'wbcom-elementor-addons/editor/element-editor-module' );

module.exports = ElementEditorModule.extend( {

	renderField: function( inputField, item ) {
		var config = elementorPro.config.forms.recaptcha;
		inputField += '<div class="elementor-field">';

		if ( config.enabled ) {
			inputField += '<div class="elementor-g-recaptcha' + _.escape( item.css_classes ) + '" data-sitekey="' + config.site_key + '" data-theme="' + item.recaptcha_style + '" data-size="' + item.recaptcha_size + '"></div>';
		} else {
			inputField += '<div class="elementor-alert">' + config.setup_message + '</div>';
		}

		inputField += '</div>';

		return inputField;

	},

	filterItem: function( item ) {
		if ( 'recaptcha' === item.field_type ) {
			item.field_label = false;
		}

		return item;
	},

	onInit: function() {
		elementor.hooks.addFilter( 'wbcom_elementor_addons/forms/content_template/item', this.filterItem );
		elementor.hooks.addFilter( 'wbcom_elementor_addons/forms/content_template/field/recaptcha', this.renderField, 10, 2 );
	}
} );

},{"wbcom-elementor-addons/editor/element-editor-module":3}],11:[function(require,module,exports){
module.exports = function() {
	var editor,
		editedModel,
		replyToControl;

	var setReplyToControl = function() {
		replyToControl = editor.collection.findWhere( { name: 'email_reply_to' } );
	};

	var getReplyToView = function() {
		return editor.children.findByModelCid( replyToControl.cid );
	};

	var refreshReplyToElement = function() {
		var replyToView = getReplyToView();

		if ( replyToView ) {
			replyToView.render();
		}
	};

	var updateReplyToOptions = function() {
		var settingsModel = editedModel.get( 'settings' ),
			emailModels = settingsModel.get( 'form_fields' ).where( { field_type: 'email' } ),
			emailFields;

		emailModels = _.reject( emailModels, { field_label: '' } );

		emailFields = _.map( emailModels, function( model ) {
			return {
				id: model.get( '_id' ),
				label: elementorPro.translate( 'x_field', [ model.get( 'field_label' ) ] )
			};
		} );

		replyToControl.set( 'options', { '': replyToControl.get( 'options' )[''] } );

		_.each( emailFields, function( emailField ) {
			replyToControl.get( 'options' )[ emailField.id ] = emailField.label;
		} );

		refreshReplyToElement();
	};

	var updateDefaultReplyTo = function( settingsModel ) {
		replyToControl.get( 'options' )[ '' ] = settingsModel.get( 'email_from' );

		refreshReplyToElement();
	};

	var onFormFieldsChange = function( changedModel ) {
		// If it's repeater field
		if ( changedModel.get( '_id' ) ) {
			if ( 'email' === changedModel.get( 'field_type' ) ) {
				updateReplyToOptions();
			}
		}

		if ( changedModel.changed.email_from ) {
			updateDefaultReplyTo( changedModel );
		}
	};

	var onPanelShow = function( panel, model ) {
		editor = panel.getCurrentPageView();

		editedModel = model;

		setReplyToControl();

		var settingsModel = editedModel.get( 'settings' );

		settingsModel.on( 'change', onFormFieldsChange );

		updateDefaultReplyTo( settingsModel );

		updateReplyToOptions();
	};

	var init = function() {
		elementor.hooks.addAction( 'panel/open_editor/widget/form', onPanelShow );
	};

	init();
};

},{}],12:[function(require,module,exports){
var ElementEditorModule = require( 'wbcom-elementor-addons/editor/element-editor-module' );

module.exports = ElementEditorModule.extend( {
	getExistId: function( id ) {
		var exist = this.getView( 'form_fields' ).collection.filter( function( model ) {
			return id === model.get( '_id' );
		} );

		return exist.length > 1;
	},

	onFieldChanged: function( model, collection, eventArgs ) {
		var self = this;

		_.defer( function() {
			var view = self.getView( 'form_fields' ).children.findByModel( model );
			self.updateId( view, eventArgs && eventArgs.add );
			self.updateShortcode( view );
		} );
	},

	updateId: function( view, isNewItem ) {
		var id = view.model.get( '_id' ),
			sanitizedId = id.replace( /[^\w]/, '_' ),
			fieldIndex = 1,
			IdView = view.children.filter( function( view ) {
				return '_id' === view.model.get( 'name' );
			});

		while ( sanitizedId !== id || isNewItem || ! id || this.getExistId( id ) ) {
			if ( sanitizedId !== id ) {
				id = sanitizedId;
			} else {
				id = 'field_' + fieldIndex;
				sanitizedId = id;
			}

			view.model.attributes._id = id;
			IdView[0].render();
			IdView[0].$el.find( 'input' ).focus();
			fieldIndex++;
			isNewItem = false;
		}
	},

	updateShortcode: function( view ) {
		var template = _.template( '[field id="<%= id %>"]' )( {
			title: view.model.get( 'field_label' ),
			id: view.model.get( '_id' )
		} );

		view.$el.find( '.elementor-form-field-shortcode' ).focus( function() {
			this.select();
		} ).val( template );
	},

	onSectionActive: function() {
		var controlView = this.getView( 'form_fields' );

		controlView.children.each( this.updateShortcode  );

		if ( ! this.collectionEventsAttached ) {
			controlView.collection.on( 'add change', this.onFieldChanged );
			this.collectionEventsAttached = true;
		}
	},

	onInit: function() {
		this.addSectionListener( 'section_form_fields', this.onSectionActive );
	}
} );

},{"wbcom-elementor-addons/editor/element-editor-module":3}],13:[function(require,module,exports){
var EditorModule = require( 'wbcom-elementor-addons/editor/editor-module' );

module.exports =  EditorModule.extend( {
	globalModels: {},

	panelWidgets: null,

	addGlobalWidget: function( id, args ) {
		args = _.extend( {}, args, {
			categories: [],
			icon: elementor.config.widgets[ args.widgetType ].icon,
			widgetType: args.widgetType,
			custom: {
				templateID: id
			}
		} );

		var globalModel = this.createGlobalModel( id, args );

		return this.panelWidgets.add( globalModel );
	},

	createGlobalModel: function( id, modelArgs ) {
		var globalModel = new elementor.modules.element.Model( modelArgs );

		globalModel.set( 'id', id );

		return this.globalModels[ id ] = globalModel;
	},

	setWidgetType: function() {
		elementor.hooks.addFilter( 'element/view', function( DefaultView, model ) {
			if ( model.get( 'templateID' ) ) {
				return require( './widget-view' );
			}

			return DefaultView;
		} );

		elementor.hooks.addFilter( 'element/model', function( DefaultModel, attrs ) {
			if ( attrs.templateID ) {
				return require( './widget-model' );
			}

			return DefaultModel;
		} );
	},

	registerTemplateType: function() {
		elementor.config.widgets.global = {
			title: elementor.translate( 'global' )
		};

		elementor.templates.registerTemplateType( 'widget', {
			showInLibrary: false,
			saveDialog: {
				title: elementorPro.translate( 'global_widget_save_title' ),
				description: elementorPro.translate( 'global_widget_save_description' )
			},
			prepareSavedData: function( data ) {
				data.widgetType = data.content[0].widgetType;

				return data;
			},
			ajaxParams: {
				success: _.bind( this.onWidgetTemplateSaved, this )
			}
		} );
	},

	addSavedWidgetsToPanel: function() {
		var self = this;

		self.panelWidgets = new Backbone.Collection();

		_.each( elementorPro.config.widget_templates, function( templateArgs, id ) {
			self.addGlobalWidget( id, templateArgs );
		} );

		elementor.hooks.addFilter( 'panel/elements/regionViews', function( regionViews ) {
			_.extend( regionViews.global, {
				view: require( './global-templates-view' ),
				options: {
					collection: self.panelWidgets
				}
			} );

			return regionViews;
		} );
	},

	addPanelPage: function() {
		elementor.getPanelView().addPage( 'globalWidget', {
			view: require( './panel-page' )
		} );
	},

	getGlobalModels: function( id ) {
		if ( ! id ) {
			return this.globalModels;
		}

		return this.globalModels[ id ];
	},

	saveTemplates: function() {
		if ( ! Object.keys( this.globalModels ).length ) {
			return;
		}

		var templatesData = [];

		_.each( this.globalModels, function( templateModel, id ) {
			if ( 'loaded' !== templateModel.get( 'settingsLoadedStatus' ) ) {
				return;
			}

			var data = {
				data: JSON.stringify( [ templateModel ] ),
				source: 'local',
				type: 'widget',
				id: id
			};

			templatesData.push( data );
		} );

		if ( ! templatesData.length ) {
			return;
		}

		elementor.ajax.send( 'update_templates', {
			data: {
				templates: templatesData
			}
		} );
	},

	setSaveButton: function() {
		elementor.getPanelView().footer.currentView.ui.buttonSave.on( 'click', _.bind( this.saveTemplates, this ) );
	},

	requestGlobalModelSettings: function( globalModel, callback ) {
		elementor.templates.requestTemplateContent( 'local', globalModel.get( 'id' ), {
			success: function( data ) {
				globalModel.set( 'settingsLoadedStatus', 'loaded' ).trigger( 'settings:loaded' );

				var settings = data.content[0].settings,
					settingsModel = globalModel.get( 'settings' );

				// Don't track it in History
				if ( elementor.history ) {
					elementor.history.history.setActive( false );
				}

				settingsModel.handleRepeaterData( settings );

				settingsModel.set( settings );

				if ( callback ) {
					callback( globalModel );
				}

				if ( elementor.history ) {
					elementor.history.history.setActive( true );
				}
			}
		} );
	},

	onElementorInit: function() {
		this.setWidgetType();
		this.registerTemplateType();

		// If it's before moving handlers scripts to frontend in edit mode
		if ( ! elementor.compareVersions ) {
			this.addSavedWidgetsToPanel();
		}
	},

	onElementorFrontendInit: function() {
		this.addSavedWidgetsToPanel();
	},

	onElementorPreviewLoaded: function() {
		this.addPanelPage();
		this.setSaveButton();
	},

	onWidgetTemplateSaved: function( data ) {
		if ( elementor.history ) {
			elementor.history.history.startItem( {
				title: elementor.config.widgets[ data.widgetType ].title,
				type: elementorPro.translate( 'linked_to_global' )
			} );
		}

		var widgetModel = elementor.templates.getLayout().modalContent.currentView.model,
			widgetModelIndex = widgetModel.collection.indexOf( widgetModel );

		elementor.templates.closeModal();

		data.elType = data.type;
		data.settings = widgetModel.get( 'settings' ).attributes;

		var globalModel = this.addGlobalWidget( data.template_id, data ),
			globalModelAttributes = globalModel.attributes;

		widgetModel.collection.add( {
			id: elementor.helpers.getUniqueID(),
			elType: globalModelAttributes.type,
			templateID: globalModelAttributes.template_id,
			widgetType: 'global'
		}, { at: widgetModelIndex }, true );

		widgetModel.destroy();

		var panel = elementor.getPanelView();

		panel.setPage( 'elements' );

		panel.getCurrentPageView().activateTab( 'global' );

		if ( elementor.history ) {
			elementor.history.history.endItem();
		}
	}
} );

},{"./global-templates-view":14,"./panel-page":16,"./widget-model":17,"./widget-view":18,"wbcom-elementor-addons/editor/editor-module":1}],14:[function(require,module,exports){
module.exports = elementor.modules.templateLibrary.ElementsCollectionView.extend( {
	id: 'elementor-global-templates',

	getEmptyView: function() {
		if ( this.collection.length ) {
			return null;
		}

		return require( './no-templates' );
	},

	onFilterEmpty: function() {}
} );

},{"./no-templates":15}],15:[function(require,module,exports){
module.exports = Marionette.ItemView.extend( {
	template: '#tmpl-elementor-panel-global-widget-no-templates',

	id: 'elementor-panel-global-widget-no-templates',

	className: 'elementor-panel-nerd-box',

	initialize: function() {
		elementor.getPanelView().getCurrentPageView().search.reset();
	},

	onDestroy: function() {
		elementor.getPanelView().getCurrentPageView().showView( 'search' );
	}
} );

},{}],16:[function(require,module,exports){

module.exports = Marionette.ItemView.extend( {
	id: 'elementor-panel-global-widget',

	template: '#tmpl-elementor-panel-global-widget',

	ui: {
		editButton: '#elementor-global-widget-locked-edit .elementor-button',
		unlinkButton: '#elementor-global-widget-locked-unlink .elementor-button',
		loading: '#elementor-global-widget-loading'
	},

	events: {
		'click @ui.editButton': 'onEditButtonClick',
		'click @ui.unlinkButton': 'onUnlinkButtonClick'
	},

	initialize: function() {
		this.initUnlinkDialog();
	},

	buildUnlinkDialog: function() {
		var self = this;

		return elementor.dialogsManager.createWidget( 'confirm', {
			id: 'elementor-global-widget-unlink-dialog',
			headerMessage: elementorPro.translate( 'unlink_widget' ),
			message: elementorPro.translate( 'dialog_confirm_unlink' ),
			position: {
				my: 'center center',
				at: 'center center'
			},
			strings: {
				confirm: elementorPro.translate( 'unlink' ),
				cancel: elementorPro.translate( 'cancel' )
			},
			onConfirm: function() {
				self.getOption( 'editedView' ).unlink();
			}
		} );
	},

	initUnlinkDialog: function() {
		var dialog;

		this.getUnlinkDialog = function() {
			if ( ! dialog ) {
				dialog = this.buildUnlinkDialog();
			}

			return dialog;
		};
	},

	editGlobalModel: function() {
		var editedView = this.getOption( 'editedView' );

		elementor.getPanelView().openEditor( editedView.getEditModel(), editedView );
	},

	onEditButtonClick: function() {
		var self = this,
			editedView = self.getOption( 'editedView' ),
			editedModel = editedView.getEditModel();

		if ( 'loaded' === editedModel.get( 'settingsLoadedStatus' ) ) {
			self.editGlobalModel();

			return;
		}

		self.ui.loading.removeClass( 'elementor-hidden' );

		elementorPro.modules.globalWidget.requestGlobalModelSettings( editedModel, function() {
			self.ui.loading.addClass( 'elementor-hidden' );

			self.editGlobalModel();
		} );
	},

	onUnlinkButtonClick: function() {
		this.getUnlinkDialog().show();
	}
} );

},{}],17:[function(require,module,exports){
module.exports = elementor.modules.element.Model.extend( {
	initialize: function() {
		this.set( { widgetType: 'global' }, { silent: true } );

		elementor.modules.element.Model.prototype.initialize.apply( this, arguments );
	},

	initSettings: function() {
		var templateID = this.get( 'templateID' ),
			globalModel = elementorPro.modules.globalWidget.getGlobalModels( templateID );

		elementorFrontend.config.elements.data[ this.cid ] = globalModel.get( 'settings' );

		elementorFrontend.config.elements.editSettings[ this.cid ] = globalModel.get( 'editSettings' );
	},

	initEditSettings: function() {},

	onDestroy: function() {
		var panel = elementor.getPanelView(),
			currentPageName = panel.getCurrentPageName();

		if ( -1 !== [ 'editor', 'globalWidget' ].indexOf( currentPageName ) ) {
			panel.setPage( 'elements' );
		}
	}
} );

},{}],18:[function(require,module,exports){
var WidgetView = elementor.modules.WidgetView,
	GlobalWidgetView;

GlobalWidgetView = WidgetView.extend( {

	globalModel: null,

	className: function() {
		return WidgetView.prototype.className.apply( this, arguments ) + ' elementor-global-widget elementor-global-' + this.model.get( 'templateID' );
	},

	initialize: function() {
		var self = this,
			templateID = self.model.get( 'templateID' );

		self.globalModel = elementorPro.modules.globalWidget.getGlobalModels( templateID );

		var globalSettingsLoadedStatus = self.globalModel.get( 'settingsLoadedStatus' );

		if ( ! globalSettingsLoadedStatus ) {
			self.globalModel.set( 'settingsLoadedStatus', 'pending' );

			elementorPro.modules.globalWidget.requestGlobalModelSettings( self.globalModel );
		}

		if ( 'loaded' !== globalSettingsLoadedStatus ) {
			self.$el.addClass( 'elementor-loading' );
		}

		self.globalModel.on( 'settings:loaded', function() {
			self.$el.removeClass( 'elementor-loading' );

			self.render();
		} );

		WidgetView.prototype.initialize.apply( self, arguments );
	},

	getEditModel: function() {
		return this.globalModel;
	},

	getHTMLContent: function( html ) {
		if ( 'loaded' === this.globalModel.get( 'settingsLoadedStatus' ) ) {
			return WidgetView.prototype.getHTMLContent.call( this, html );
		}

		return '';
	},

	serializeModel: function() {
		return this.globalModel.toJSON.apply( this.globalModel, _.rest( arguments ) );
	},

	edit: function() {
		elementor.getPanelView().setPage( 'globalWidget', 'Global Editing', { editedView: this } );
	},

	unlink: function() {
		var newModel = new elementor.modules.element.Model( {
			elType: 'widget',
			widgetType: this.globalModel.get( 'widgetType' ),
			id: elementor.helpers.getUniqueID(),
			settings: elementor.helpers.cloneObject( this.globalModel.get( 'settings' ).attributes ),
			defaultEditSettings: elementor.helpers.cloneObject( this.globalModel.get( 'editSettings' ).attributes )
		} );

		this._parent.addChildModel( newModel, { at: this.model.collection.indexOf( this.model ) } );

		var newWidget = this._parent.children.findByModelCid( newModel.cid );

		this.model.destroy();

		newWidget.edit();
	}
} );

module.exports = GlobalWidgetView;

},{}],19:[function(require,module,exports){
var EditorModule = require( 'wbcom-elementor-addons/editor/editor-module' );

module.exports = EditorModule.extend( {
	onElementorPreviewLoaded: function() {
		var EditButton = require( './editor/edit-button' );
		this.editButton = new EditButton();
	}
} );

},{"./editor/edit-button":20,"wbcom-elementor-addons/editor/editor-module":1}],20:[function(require,module,exports){
module.exports = function() {
	var self = this;

	self.onPanelShow = function(  panel ) {
		var templateIdControl = panel.content.currentView.collection.findWhere( { name: 'template_id' } );

		if ( ! templateIdControl ) {
			return; // No templates
		}
		var templateIdInput = panel.content.currentView.children.findByModelCid( templateIdControl.cid );

		templateIdInput.on( 'input:change', self.onTemplateIdChange ).trigger( 'input:change' );
	};

	self.onTemplateIdChange = function() {
		var templateID = this.options.elementSettingsModel.attributes.template_id,
			type = this.options.model.attributes.types[ templateID ],
			$editButton = this.$el.find( '.elementor-edit-template' );

		if ( '0' === templateID || ! templateID || 'widget' === type ) { // '0' = first option, 'widget' is editable only from Elementor page
			if ( $editButton.length ) {
				$editButton.remove();
			}

			return;
		}

		var editUrl = ElementorConfig.home_url + '?p=' + templateID + '&elementor';

		if ( $editButton.length ) {
			$editButton.prop( 'href', editUrl );
		} else {
			$editButton = jQuery( '<a />', {
				target: '_blank',
				class: 'elementor-button elementor-button-default elementor-edit-template',
				href: editUrl,
				html: '<i class="fa fa-pencil" /> ' + WbcomElementorAddonsConfig.i18n.edit_template
		} );

			this.$el.find( '.elementor-control-input-wrapper' ).after( $editButton );
		}
	};

	self.init = function() {
		elementor.hooks.addAction( 'panel/open_editor/widget/template', self.onPanelShow );
	};

	self.init();
};

},{}],21:[function(require,module,exports){
var EditorModule = require( 'wbcom-elementor-addons/editor/editor-module' );

module.exports = EditorModule.extend( {
	onElementorPreviewLoaded: function() {
		elementor.addControlView( 'Query', require( './editor/query-control' ) );
	}
} );

},{"./editor/query-control":22,"wbcom-elementor-addons/editor/editor-module":1}],22:[function(require,module,exports){
module.exports = elementor.modules.controls.Select2.extend( {
	isTitlesReceived: false,

	getSelect2Options: function() {
		var self = this;

		return {
			ajax: {
				transport: function( params, success, failure ) {

					var data = {
						q: params.data.q,
						filter_type: self.model.get( 'filter_type' ),
						object_type: self.model.get( 'object_type' )
					};

					return elementorPro.ajax.send( 'panel_posts_control_filter_autocomplete', {
						data: data,
						success: success,
						error: failure
					} );
				},
				data: function( params ) {
					return {
						q: params.term,
						page: params.page
					};
				},
				cache: true
			},
			escapeMarkup: function( markup ) {
				return markup;
			},
			minimumInputLength: 2
		};
	},

	getValueTitles: function() {
		var self = this,
			value = self.getControlValue(),
			filterType = self.model.get( 'filter_type' );

		if ( ! value || ! filterType ) {
			return;
		}

		var data = {
			filter_type: filterType,
			object_type: self.model.get( 'object_type' ),
			value: value
		};

		this.addControlSpinner();

		var request = elementorPro.ajax.send( 'panel_posts_control_value_titles', { data: data });

		request.then( function( response ) {
			self.isTitlesReceived = true;

			self.model.set( 'options', response.data );

			self.render();

			self.ui.select.select2( self.getSelect2Options() );
		});
	},

	addControlSpinner: function( name ) {
		this.ui.select.prop( 'disabled', true );
		this.$el.find( '.elementor-control-title' ).after( '<span class="elementor-control-spinner">&nbsp;<i class="fa fa-spinner fa-spin"></i>&nbsp;</span>' );
	},

	onReady: function() {
		elementor.modules.controls.Select2.prototype.onReady.apply( this, arguments );

		if ( ! this.isTitlesReceived ) {
			this.getValueTitles();
		}
	}
} );

},{}],23:[function(require,module,exports){
var EditorModule = require( 'wbcom-elementor-addons/editor/editor-module' );

module.exports = EditorModule.extend( {
	config: elementorPro.config.shareButtonsNetworks,

	networksClassDictionary: {
		google: 'fa fa-google-plus',
		pocket: 'fa fa-get-pocket',
		email: 'fa fa-envelope'
	},

	getNetworkClass: function( networkName ) {
		return this.networksClassDictionary[ networkName ] || 'fa fa-' + networkName;
	},

	getNetworkTitle: function( buttonSettings ) {
		return buttonSettings.text || this.config[ buttonSettings.button ].title;
	},

	hasCounter: function( networkName, settings ) {
		return 'icon' !== settings.view && 'yes' === settings.show_counter && this.config[ networkName ].has_counter;
	}
} );

},{"wbcom-elementor-addons/editor/editor-module":1}],24:[function(require,module,exports){
var EditorModule = require( 'wbcom-elementor-addons/editor/editor-module' );

module.exports = EditorModule.extend( {
	onElementorPreviewLoaded: function() {
		var StopSlider = require( './editor/stop-slider' );
		this.stopSlider = new StopSlider();
	}
} );

},{"./editor/stop-slider":25,"wbcom-elementor-addons/editor/editor-module":1}],25:[function(require,module,exports){
module.exports = function() {
	var self = this;

	self.onPanelShow = function( panel, model, view ) {
		var $slider = view.$el.find( '.elementor-slides' );

		if ( $slider.length ) {
			$slider.slick( 'slickPause' );

			$slider.on( 'afterChange', function() {
				$slider.slick( 'slickPause' );
			} );
		}
	};

	self.init = function() {
		elementor.hooks.addAction( 'panel/open_editor/widget/slides', self.onPanelShow );
	};

	self.init();
};

},{}],26:[function(require,module,exports){
var Module = function() {
	var $ = jQuery,
		instanceParams = arguments,
		self = this,
		settings,
		events = {};

	var ensureClosureMethods = function() {
		$.each( self, function( methodName ) {
			var oldMethod = self[ methodName ];

			if ( 'function' !== typeof oldMethod ) {
				return;
			}

			self[ methodName ] = function() {
				return oldMethod.apply( self, arguments );
			};
		});
	};

	var initSettings = function() {
		settings = self.getDefaultSettings();

		var instanceSettings = instanceParams[0];

		if ( instanceSettings ) {
			$.extend( settings, instanceSettings );
		}
	};

	var init = function() {
		self.__construct.apply( self, instanceParams );

		ensureClosureMethods();

		initSettings();

		self.trigger( 'init' );
	};

	this.getItems = function( items, itemKey ) {
		if ( itemKey ) {
			var keyStack = itemKey.split( '.' ),
				currentKey = keyStack.splice( 0, 1 );

			if ( ! keyStack.length ) {
				return items[ currentKey ];
			}

			if ( ! items[ currentKey ] ) {
				return;
			}

			return this.getItems(  items[ currentKey ], keyStack.join( '.' ) );
		}

		return items;
	};

	this.getSettings = function( setting ) {
		return this.getItems( settings, setting );
	};

	this.setSettings = function( settingKey, value, settingsContainer ) {
		if ( ! settingsContainer ) {
			settingsContainer = settings;
		}

		if ( 'object' === typeof settingKey ) {
			$.extend( settingsContainer, settingKey );

			return self;
		}

		var keyStack = settingKey.split( '.' ),
			currentKey = keyStack.splice( 0, 1 );

		if ( ! keyStack.length ) {
			settingsContainer[ currentKey ] = value;

			return self;
		}

		if ( ! settingsContainer[ currentKey ] ) {
			settingsContainer[ currentKey ] = {};
		}

		return self.setSettings( keyStack.join( '.' ), value, settingsContainer[ currentKey ] );
	};

	this.forceMethodImplementation = function( methodArguments ) {
		var functionName = methodArguments.callee.name;

		throw new ReferenceError( 'The method ' + functionName + ' must to be implemented in the inheritor child.' );
	};

	this.on = function( eventName, callback ) {
		if ( ! events[ eventName ] ) {
			events[ eventName ] = [];
		}

		events[ eventName ].push( callback );

		return self;
	};

	this.off = function( eventName, callback ) {
		if ( ! events[ eventName ] ) {
			return self;
		}

		if ( ! callback ) {
			delete events[ eventName ];

			return self;
		}

		var callbackIndex = events[ eventName ].indexOf( callback );

		if ( -1 !== callbackIndex ) {
			delete events[ eventName ][ callbackIndex ];
		}

		return self;
	};

	this.trigger = function( eventName ) {
		var methodName = 'on' + eventName[ 0 ].toUpperCase() + eventName.slice( 1 ),
			params = Array.prototype.slice.call( arguments, 1 );

		if ( self[ methodName ] ) {
			self[ methodName ].apply( self, params );
		}

		var callbacks = events[ eventName ];

		if ( ! callbacks ) {
			return;
		}

		$.each( callbacks, function( index, callback ) {
			callback.apply( self, params );
		} );
	};

	init();
};

Module.prototype.__construct = function() {};

Module.prototype.getDefaultSettings = function() {
	return {};
};

Module.extendsCount = 0;

Module.extend = function( properties ) {
	var $ = jQuery,
		parent = this;

	var child = function() {
		return parent.apply( this, arguments );
	};

	$.extend( child, parent );

	child.prototype = Object.create( $.extend( {}, parent.prototype, properties ) );

	child.prototype.constructor = child;

	/*
	 * Constructor ID is used to set an unique ID
     * to every extend of the Module.
     *
	 * It's useful in some cases such as unique
	 * listener for frontend handlers.
	 */
	var constructorID = ++Module.extendsCount;

	child.prototype.getConstructorID = function() {
		return constructorID;
	};

	child.__super__ = parent.prototype;

	return child;
};

module.exports = Module;

},{}]},{},[2])
//# sourceMappingURL=editor.js.map
