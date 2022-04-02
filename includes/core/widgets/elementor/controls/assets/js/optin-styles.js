jQuery( window ).on( 'elementor:init', () => {

	var optinFormStylesItemView = elementor.modules.controls.BaseData.extend({
		onReady() {
			console.log(this.$el)
		},
		saveValue() {
			console.log(this.$el)
		},
		onBeforeDestroy() {
			this.$el.remove();
		}
	});

	elementor.addControlView( 'optin_styles', optinFormStylesItemView );

} );
