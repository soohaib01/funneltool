jQuery(document).ready(function ($) {

    var TemplateHandler = function() {
        var importVars = {
            funnelID : $('#funnelID').val(),
            funnelObj : {
                id: 0,
                title: '',
                link: '',
                slug: '',
                featured_media: 0,
                featured_image: '',
                steps_order: '',
                page_builder: '',
                categories: '',
                is_pro: false,
            },
            stepObj: {
                id: 0,
                title: '',
                link: '',
                slug: '',
                featured_media: 0,
                featured_image: '',
                steps_order: '',
                page_builder: '',
                categories: '',
                is_pro: false,
                type: '',
            }

        };
        this.initTemplateLibrary();
        $('#create-first-step, #add-new-step').on('click',{ stepTemplateHandler: this }, this.getStepTemplates);
    };

    TemplateHandler.prototype.getStepTemplates = function(e) {
        e.preventDefault();
        $(this).parents('.wpfnl').find('.wpfnl-modal').fadeIn(200);
        var payload = {'source' : 'remote'},
            $_this = this,
            handler = e.data.stepTemplateHandler;
        wpAjaxHelperRequest( "wpfunnel-get-step-templates-data", payload )
            .success( function( response ) {
                if( response.data ) {
                    $("#wpfnl-create-step__template-wrapper").children(":not('.create-step__from-scratch, .wpfnl-loader__wrapper')").remove();

                    handler.initStepsTemplateView(response.data);

                    var activeList = $('#wpfnl-create-step__filter-nav > li.active').attr('data-filter');
                    $('.wpfnl-create-step__template-wrapper .create-funnel__single-template.'+activeList+'').show();
                }
            })
            .error( function( response ) {
                console.log(response)
            });
    };

    /**
     * Init view for template library
     *
     * @param data
     * @since 1.0.0
     */
    TemplateHandler.prototype.initStepsTemplateView = function (data) {
        // init template library model

        var TemplatesLibrary = Backbone.Model.extend({
            defaults: {
                id: 0,
                title: '',
                link: '',
                slug: '',
                featured_media: 0,
                featured_image: '',
                steps_order: '',
                page_builder: '',
                categories: '',
                is_pro: false,
                type: '',
            }
        });

        // init template library collection
        var TemplatesCollection = Backbone.Collection.extend({
            model: TemplatesLibrary
        });
        var Templates = new TemplatesCollection;

        if( data.steps ){
            data.steps.map( m => {
                var template = new TemplatesLibrary(m);
                Templates.add(template)
            });
            $('#wpfnl-create-funnel__filter-nav').css('display', 'flex');
        }else {
            $('#wpfnl-create-funnel__filter-nav').hide();
        }

        var TemplatesView = Backbone.View.extend({
            el: '#wpfnl-create-step__template-wrapper',
            initialize: function() {
                this.render();
            },
            render: function() {
                Templates.each(function(model) {
                    var template = new TemplateView({
                        model: model
                    });
                    this.$el.prepend(template.render().el);
                }.bind(this));
                this.$el.find('.wpfnl-loader__wrapper').fadeOut();
                return this;
            }
        });
        var TemplateView = Backbone.View.extend({
            tagName: 'div',
            attributes : function () {
                var classes = this.model.get( 'type' );
                return {
                    class : 'create-funnel__single-template ' + classes,
                };
            },
            template: _.template($('#tmpl-wpfnl-step-templates-view').html()),
            render: function() {
                this.$el.html(this.template(this.model.attributes));
                return this;
            },
            events: {
                'click .wpfnl-import-step': 'importStep'
            },
            importStep: function (e) {
                e.preventDefault();
                var that = this,
                    loader = $(this)[0].$el.find('.importar-loader'),
                    funnel_id = $(this)[0].$el.find('.wpfnl-import-step').attr('data-funnel-id');
                loader.fadeIn();
                that.createStep( {'id' : this.model.get('id'), 'type' : this.model.get('type'), 'name' : this.model.get('type')}, funnel_id, loader);
            },
            createStep: function (step, funnelID, loader) {
                var that = this,
                    payload = {
                        'step'      : step,
                        'funnelID'  : funnelID,
                        'source'    : 'remote'
                    };
                wpAjaxHelperRequest( "wpfunnel-import-step", payload )
                    .success( function( response ) {
                        loader.find('.title').text('Importing Step...');
                        that.afterStepCreationRedirect(response.stepID, funnelID);
                        console.log(response)
                    })
                    .error( function( response ) {
                        console.log( "Uh, oh!" );
                        console.log( response.statusText );
                    });
            },
            afterStepCreationRedirect: function (stepID, funnelID) {
                var payload = {
                    'stepID'    : stepID,
                    'funnelID'  : funnelID,
                    'source'    : 'remote',
                };
                wpAjaxHelperRequest( "wpfunnel-after-step-creation", payload )
                    .success( function( response ) {
                        window.location = response.redirectLink
                    })
                    .error( function( response ) {
                        console.log(response)
                    });
            }
        });
        new TemplatesView;
    };


    /**
     * init template library for funnel showcase
     */
    TemplateHandler.prototype.initTemplateLibrary = function () {
        var payload = {'source' : 'remote'},
            $_this = this;
        wpAjaxHelperRequest( "wpfunnel-get-templates-data", payload )
            .success( function( response ) {
                if( response.data ) {
                    $_this.initTemplateView(response.data);
                    $_this.initTemplateCategoryView(response.data);
                }
            })
            .error( function( response ) {
                console.log(response)
            });
    };

    /**
     * Init view for template library
     *
     * @param data
     * @since 1.0.0
     */
    TemplateHandler.prototype.initTemplateView = function (data) {
        // init template library model
        var TemplatesLibrary = Backbone.Model.extend({
            defaults: {
                id: 0,
                title: '',
                link: '',
                slug: '',
                featured_media: 0,
                featured_image: '',
                steps_order: '',
                page_builder: '',
                categories: '',
                is_pro: false,
            }
        });

        // init template library collection
        var TemplatesCollection = Backbone.Collection.extend({
            model: TemplatesLibrary
        });
        var Templates = new TemplatesCollection;

        if( data.templates ){
            data.templates.map( m => {
                var template = new TemplatesLibrary(m);
                Templates.add(template)
            });

            $('#wpfnl-create-funnel__filter-nav').css('display', 'flex');
        }else {
            $('#wpfnl-create-funnel__filter-nav').hide();
        }

        var TemplatesView = Backbone.View.extend({
            el: '#wpfnl-create-funnel__template-wrapper',
            initialize: function() {
                this.render();
            },
            render: function() {
                Templates.each(function(model) {
                    var template = new TemplateView({
                        model: model
                    });
                    this.$el.prepend(template.render().el);
                }.bind(this));
                this.$el.find('.wpfnl-loader__wrapper').fadeOut();
                return this;
            }
        });
        var TemplateView = Backbone.View.extend({
            tagName: 'div',
            // className : 'create-funnel__single-template',
            attributes : function () {
                var categories = this.model.get( 'categories' ),
                    classes = [];
                if($.isArray(categories)) {
                    classes = categories.map((cat) => {
                        return cat.slug;
                    });
                }
                return {
                    class : 'create-funnel__single-template ' + classes.join(" "),
                };
            },
            template: _.template($('#tmpl-wpfnl-templates-view').html()),
            render: function() {
                this.$el.html(this.template(this.model.attributes));
                return this;
            },
            events: {
                'click .wpfnl-import-funnel': 'importFunnel'
            },
            importFunnel: function (e) {
                e.preventDefault();
                var that = this,
                    loader = $(this)[0].$el.find('.importar-loader');
                var steps = this.model.get('steps_order'),
                    payload = {
                        'steps'     : steps,
                        'source'    : 'remote',
                    };
                loader.fadeIn();
                wpAjaxHelperRequest( "wpfunnel-import-funnel", payload )
                    .success( function( response ) {
                        var looper = $.Deferred().resolve(),
                            first_step_id = 0;
                        $.when.apply($, $.map(steps, function(step, index) {
                            looper = looper.then(function() {
                                // trigger ajax call with item data
                                return that.createStep(step, response.funnelID, loader, index);
                            });
                            return looper;
                        })).then(function() {
                            // run this after all ajax calls have completed
                            console.log('Done!');
                            that.afterFunnelCreationRedirect(response.funnelID);
                        });
                    })
                    .error( function( response ) {
                        console.log( "Uh, oh!" );
                        console.log( response.statusText );
                    });
            },
            createStep: function (step, funnelID, loader, index) {
                var deferred = $.Deferred(),
                    payload = {
                        'step'      : step,
                        'funnelID'  : funnelID,
                        'source'    : 'remote'
                    };
                wpAjaxHelperRequest( "wpfunnel-import-step", payload )
                    .success( function( response ) {
                        loader.find('.title').text('Importing Step '+ (parseInt(index)+1) );
                        console.log(response)
                        deferred.resolve(response);
                    })
                    .error( function( response ) {
                        console.log( "Uh, oh!" );
                        console.log( response.statusText );
                        deferred.reject(response);
                    });
                return deferred.promise();
            },
            afterFunnelCreationRedirect: function (funnelId) {
                var payload = {
                    'funnelID'  : funnelId,
                    'source'    : 'remote'
                };
                wpAjaxHelperRequest( "wpfunnel-after-funnel-creation", payload )
                    .success( function( response ) {
                        window.location = response.redirectLink
                    })
                    .error( function( response ) {
                        console.log(response)
                    });
            }
        });
        new TemplatesView;
    };

    /**
     * Init view for template library
     * category filter
     *
     * @param data
     * @since 1.0.0
     */
    TemplateHandler.prototype.initTemplateCategoryView = function (data) {
        var CategoriesLibrary = Backbone.Model.extend();
        var CategoriesCollection = Backbone.Collection.extend({
            model: CategoriesLibrary
        });
        var Categories = new CategoriesCollection;

        if( data.categories ){
            data.categories.map( m => {
                var category = new CategoriesLibrary(m);
                Categories.add(category)
            });
        }
        var CategoriesView = Backbone.View.extend({
            el: '#wpfnl-create-funnel__filter-nav',

            initialize: function() {
                this.render();
            },
            render: function() {
                Categories.each(function(model, i) {
                    var template = new CategoryView({
                        model: model
                    });

                    this.$el.append(template.render().el);
                    if ( i === 0) {
                        this.$el.find('li').eq(0).addClass('active');
                    }
                }.bind(this));

                $('.wpfnl-create-funnel__filter-nav > li').on('click', function(){
                    $(this).addClass('active').siblings().removeClass('active');
                    const filterVal = $(this).attr('data-filter');
                    if( filterVal == 'all' ){
                        $('.create-funnel__single-template').show();
                    }else{
                        $('.create-funnel__single-template').not('.'+filterVal).not('.create-funnel__from-scratch').hide();
                        $('.create-funnel__single-template').filter('.'+filterVal).show();
                    }
                });
                return this;
            }
        });
        var CategoryView = Backbone.View.extend({
            tagName: 'li',
            template: _.template($('#tmpl-wpfnl-templates-category-filter').html()),
            render: function() {
                this.$el.html(this.template(this.model.attributes));
                $(this.el).attr('data-filter',this.model.get('slug')).addClass(this.class);
                return this;
            },
            events: {
                'click .click': 'getModel'
            },
            getModel: function () {
                return this.model;
            },
        });
        new CategoriesView;
    };

    new TemplateHandler();
});

