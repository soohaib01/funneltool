<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit277cca59858b7802893578b1a0feb959
{
    public static $files = array (
        'db1766888a4f96ab813d6f6a38125eb9' => __DIR__ . '/..' . '/philipnewcomer/wp-ajax-helper/src/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PhilipNewcomer\\WP_Ajax_Helper\\' => 30,
        ),
        'A' => 
        array (
            'Appsero\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PhilipNewcomer\\WP_Ajax_Helper\\' => 
        array (
            0 => __DIR__ . '/..' . '/philipnewcomer/wp-ajax-helper/src/components',
        ),
        'Appsero\\' => 
        array (
            0 => __DIR__ . '/..' . '/appsero/client/src',
        ),
    );

    public static $classMap = array (
        'Appsero\\Client' => __DIR__ . '/..' . '/appsero/client/src/Client.php',
        'Appsero\\Insights' => __DIR__ . '/..' . '/appsero/client/src/Insights.php',
        'Appsero\\License' => __DIR__ . '/..' . '/appsero/client/src/License.php',
        'Appsero\\Updater' => __DIR__ . '/..' . '/appsero/client/src/Updater.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'PhilipNewcomer\\WP_Ajax_Helper\\Frontend' => __DIR__ . '/..' . '/philipnewcomer/wp-ajax-helper/src/components/Frontend.php',
        'PhilipNewcomer\\WP_Ajax_Helper\\Handler' => __DIR__ . '/..' . '/philipnewcomer/wp-ajax-helper/src/components/Handler.php',
        'PhilipNewcomer\\WP_Ajax_Helper\\Responder' => __DIR__ . '/..' . '/philipnewcomer/wp-ajax-helper/src/components/Responder.php',
        'PhilipNewcomer\\WP_Ajax_Helper\\Utility' => __DIR__ . '/..' . '/philipnewcomer/wp-ajax-helper/src/components/Utility.php',
        'PhilipNewcomer\\WP_Ajax_Helper\\Validations' => __DIR__ . '/..' . '/philipnewcomer/wp-ajax-helper/src/components/Validations.php',
        'PhilipNewcomer\\WP_Ajax_Helper\\Validator' => __DIR__ . '/..' . '/philipnewcomer/wp-ajax-helper/src/components/Validator.php',
        'WPFunnels\\Admin\\Module\\Steps\\Wpfnl_Steps_Factory' => __DIR__ . '/../..' . '/admin/modules/steps/class-wpfnl-steps-factory.php',
        'WPFunnels\\Admin\\Module\\Wpfnl_Admin_Module' => __DIR__ . '/../..' . '/admin/modules/abstract-wpfnl-admin-modules.php',
        'WPFunnels\\Admin\\Modules\\Steps\\Checkout\\Module' => __DIR__ . '/../..' . '/admin/modules/steps/checkout/class-wpfnl-checkout.php',
        'WPFunnels\\Admin\\Modules\\Steps\\Landing\\Module' => __DIR__ . '/../..' . '/admin/modules/steps/landing/class-wpfnl-landing.php',
        'WPFunnels\\Admin\\Modules\\Steps\\Module' => __DIR__ . '/../..' . '/admin/modules/steps/class-wpfnl-steps.php',
        'WPFunnels\\Admin\\Modules\\Steps\\Thankyou\\Module' => __DIR__ . '/../..' . '/admin/modules/steps/thankyou/class-wpfnl-thank-you.php',
        'WPFunnels\\Admin\\Notices\\Notice' => __DIR__ . '/../..' . '/admin/class-wpfnl-notices.php',
        'WPFunnels\\Admin\\SetupWizard' => __DIR__ . '/../..' . '/admin/modules/setup-wizard/setup-wizard.php',
        'WPFunnels\\Admin\\Wpfnl_Admin' => __DIR__ . '/../..' . '/admin/class-wpfnl-admin.php',
        'WPFunnels\\Ajax_Handler\\Ajax_Handler' => __DIR__ . '/../..' . '/includes/core/classes/class-wpfnl-ajax-handler.php',
        'WPFunnels\\Base_Manager' => __DIR__ . '/../..' . '/includes/core/classes/abstact-wpfnl-manager.php',
        'WPFunnels\\Batch\\Divi\\Wpfnl_Divi_Source' => __DIR__ . '/../..' . '/admin/import-export/batch-processing/divi/class-wpfnl-divi-source.php',
        'WPFunnels\\Batch\\Elementor\\Wpfnl_Batch' => __DIR__ . '/../..' . '/admin/import-export/batch-processing/class-wpfnl-batch.php',
        'WPFunnels\\Batch\\Elementor\\Wpfnl_Elementor_Source' => __DIR__ . '/../..' . '/admin/import-export/batch-processing/elementor/class-wpfnl-elementor-source.php',
        'WPFunnels\\Batch\\Gutenberg\\Wpfnl_Gutenberg_Batch' => __DIR__ . '/../..' . '/admin/import-export/batch-processing/gutenberg/class-wpfnl-gutenberg-batch.php',
        'WPFunnels\\Batch\\Gutenberg\\Wpfnl_Gutenberg_Source' => __DIR__ . '/../..' . '/admin/import-export/batch-processing/gutenberg/class-wpfnl-gutenberg-source.php',
        'WPFunnels\\Batch\\Oxygen\\Wpfnl_Oxygen_Batch' => __DIR__ . '/../..' . '/admin/import-export/batch-processing/oxygen/class-wpfnl-oxygen-batch.php',
        'WPFunnels\\Batch\\Oxygen\\Wpfnl_Oxygen_Source' => __DIR__ . '/../..' . '/admin/import-export/batch-processing/oxygen/class-wpfnl-oxygen-source.php',
        'WPFunnels\\Batch\\Wpfnl_Background_Task' => __DIR__ . '/../..' . '/admin/import-export/batch-processing/class-wpfnl-background-task.php',
        'WPFunnels\\Batch\\Wpfnl_Divi_Batch' => __DIR__ . '/../..' . '/admin/import-export/batch-processing/divi/class-wpfnl-divi-batch.php',
        'WPFunnels\\Batch\\Wpfnl_Elementor_Batch' => __DIR__ . '/../..' . '/admin/import-export/batch-processing/elementor/class-wpfnl-elementor-batch.php',
        'WPFunnels\\CPT\\Wpfnl_CPT' => __DIR__ . '/../..' . '/includes/core/classes/class-wpfnl-register-funnel.php',
        'WPFunnels\\Classes\\OrderBumpActions\\Wpfnl_Order_Bump_Action' => __DIR__ . '/../..' . '/classes/class-wpfnl-order-bump-actions.php',
        'WPFunnels\\Compatibility\\Wpfnl_Theme_Compatibility' => __DIR__ . '/../..' . '/includes/core/classes/class-wpfnl-compatibility.php',
        'WPFunnels\\Conditions\\Wpfnl_Condition_Checker' => __DIR__ . '/../..' . '/includes/core/classes/class-wpfnl-condition-checker.php',
        'WPFunnels\\Constants\\Wpfnl_Constants' => __DIR__ . '/../..' . '/includes/utils/class-wpfnl-constants.php',
        'WPFunnels\\Data_Store\\Wpfnl_Abstract_Store_data' => __DIR__ . '/../..' . '/includes/core/data-store/abstact-wpfnl-store-cpt.php',
        'WPFunnels\\Data_Store\\Wpfnl_Data_Store' => __DIR__ . '/../..' . '/includes/core/data-store/interface-wpfnl-data-store.php',
        'WPFunnels\\Data_Store\\Wpfnl_Funnel_Store_Data' => __DIR__ . '/../..' . '/includes/core/data-store/class-wpfnl-store-funnel-data.php',
        'WPFunnels\\Data_Store\\Wpfnl_Steps_Store_Data' => __DIR__ . '/../..' . '/includes/core/data-store/class-wpfnl-store-steps-data.php',
        'WPFunnels\\Exception\\Wpfnl_Api_Exception' => __DIR__ . '/../..' . '/includes/core/exception/class-wpfnl-api-exception.php',
        'WPFunnels\\Exception\\Wpfnl_Data_Exception' => __DIR__ . '/../..' . '/includes/core/exception/class-wpfnl-data-exception.php',
        'WPFunnels\\Frontend\\Module\\Wpfnl_Frontend_Module' => __DIR__ . '/../..' . '/public/modules/abstract-wpfnl-public-modules.php',
        'WPFunnels\\Frontend\\Wpfnl_Frontend' => __DIR__ . '/../..' . '/public/classes/class-wpfnl-frontend.php',
        'WPFunnels\\Frontend\\Wpfnl_Public' => __DIR__ . '/../..' . '/public/class-wpfnl-public.php',
        'WPFunnels\\Importer\\Image\\Wpfnl_Image_Importer' => __DIR__ . '/../..' . '/admin/import-export/class-wpfnl-image-import.php',
        'WPFunnels\\Importer\\Wpfnl_Importer_Helper' => __DIR__ . '/../..' . '/admin/import-export/class-wpfnl-importer-helper.php',
        'WPFunnels\\Menu\\Wpfnl_Menus' => __DIR__ . '/../..' . '/includes/core/classes/class-wpfnl-admin-menus.php',
        'WPFunnels\\Meta\\Wpfnl_Default_Meta' => __DIR__ . '/../..' . '/classes/class-wpfnl-meta.php',
        'WPFunnels\\Metas\\Wpfnl_Step_Meta_keys' => __DIR__ . '/../..' . '/includes/core/data-store/class-wpfnl-step-metas.php',
        'WPFunnels\\Modules\\Admin\\Category\\Module' => __DIR__ . '/../..' . '/admin/modules/category/class-wpfnl-category.php',
        'WPFunnels\\Modules\\Admin\\CreateFunnel\\Module' => __DIR__ . '/../..' . '/admin/modules/createFunnel/class-wpfnl-create-funnel.php',
        'WPFunnels\\Modules\\Admin\\Discount\\Module' => __DIR__ . '/../..' . '/admin/modules/discount/class-wpfnl-discount.php',
        'WPFunnels\\Modules\\Admin\\Funnel\\Module' => __DIR__ . '/../..' . '/admin/modules/funnel/class-wpfnl-funnel.php',
        'WPFunnels\\Modules\\Admin\\Funnels\\Module' => __DIR__ . '/../..' . '/admin/modules/funnels/class-wpfnl-funnels.php',
        'WPFunnels\\Modules\\Admin\\Product\\Module' => __DIR__ . '/../..' . '/admin/modules/product/class-wpfnl-product.php',
        'WPFunnels\\Modules\\Admin\\Settings\\Module' => __DIR__ . '/../..' . '/admin/modules/settings/class-wpfnl-settings.php',
        'WPFunnels\\Modules\\Frontend\\Checkout\\Module' => __DIR__ . '/../..' . '/public/modules/checkout/class-wpfnl-checkout.php',
        'WPFunnels\\Modules\\Frontend\\Thankyou\\Module' => __DIR__ . '/../..' . '/public/modules/thankyou/class-wpfnl-thankyou.php',
        'WPFunnels\\Modules\\Wpfnl_Modules_Manager' => __DIR__ . '/../..' . '/includes/core/classes/class-wpfnl-module-manager.php',
        'WPFunnels\\Optin\\Optin_Record' => __DIR__ . '/../..' . '/includes/core/classes/class-wpfnl-optin-record.php',
        'WPFunnels\\PageTemplates\\Manager' => __DIR__ . '/../..' . '/includes/core/page-templates/Manager.php',
        'WPFunnels\\Rest\\Controllers\\FunnelController' => __DIR__ . '/../..' . '/includes/core/rest-api/Controllers/FunnelController.php',
        'WPFunnels\\Rest\\Controllers\\GutenbergCSSController' => __DIR__ . '/../..' . '/includes/core/rest-api/Controllers/GutenbergCSSController.php',
        'WPFunnels\\Rest\\Controllers\\OrderBumpController' => __DIR__ . '/../..' . '/includes/core/rest-api/Controllers/OrderBumpController.php',
        'WPFunnels\\Rest\\Controllers\\ProductsController' => __DIR__ . '/../..' . '/includes/core/rest-api/Controllers/ProductsController.php',
        'WPFunnels\\Rest\\Controllers\\RemoteFunnelsController' => __DIR__ . '/../..' . '/includes/core/rest-api/Controllers/RemoteFunnelsController.php',
        'WPFunnels\\Rest\\Controllers\\SettingsController' => __DIR__ . '/../..' . '/includes/core/rest-api/Controllers/SettingsController.php',
        'WPFunnels\\Rest\\Controllers\\StepController' => __DIR__ . '/../..' . '/includes/core/rest-api/Controllers/StepController.php',
        'WPFunnels\\Rest\\Controllers\\TemplateLibraryController' => __DIR__ . '/../..' . '/includes/core/rest-api/Controllers/TemplateLibraryController.php',
        'WPFunnels\\Rest\\Controllers\\Wpfnl_REST_Controller' => __DIR__ . '/../..' . '/includes/core/rest-api/Controllers/class-wpfnl-rest-controller.php',
        'WPFunnels\\Rest\\Server' => __DIR__ . '/../..' . '/includes/core/rest-api/Server.php',
        'WPFunnels\\Rollback' => __DIR__ . '/../..' . '/includes/utils/class-wpfnl-rollback.php',
        'WPFunnels\\Shortcodes\\WC_Shortcode_Optin' => __DIR__ . '/../..' . '/includes/core/shortcodes/class-wpfnl-shortcode-optin.php',
        'WPFunnels\\Shortcodes\\Wpfnl_Shortcode_Checkout' => __DIR__ . '/../..' . '/includes/core/shortcodes/class-wpfnl-shortcode-checkout.php',
        'WPFunnels\\Shortcodes\\Wpfnl_Shortcode_NextStepButton' => __DIR__ . '/../..' . '/includes/core/shortcodes/class-wpfnl-shortcode-next-step-button.php',
        'WPFunnels\\Shortcodes\\Wpfnl_Shortcode_Order_details' => __DIR__ . '/../..' . '/includes/core/shortcodes/class-wpfnl-shortcode-order-details.php',
        'WPFunnels\\Shortcodes\\Wpfnl_Shortcodes' => __DIR__ . '/../..' . '/includes/core/shortcodes/class-wpfnl-shortcodes.php',
        'WPFunnels\\TemplateLibrary\\Manager' => __DIR__ . '/../..' . '/admin/modules/template-library/Manager.php',
        'WPFunnels\\TemplateLibrary\\Wpfnl_Source_Base' => __DIR__ . '/../..' . '/admin/modules/template-library/sources/base.php',
        'WPFunnels\\TemplateLibrary\\Wpfnl_Source_Remote' => __DIR__ . '/../..' . '/admin/modules/template-library/sources/remote.php',
        'WPFunnels\\Traits\\SingletonTrait' => __DIR__ . '/../..' . '/includes/core/traits/SingletonTrait.php',
        'WPFunnels\\Widgets\\DiviModules\\Manager' => __DIR__ . '/../..' . '/includes/core/widgets/divi-modules/Manager.php',
        'WPFunnels\\Widgets\\DiviModules\\Modules\\WPFNL_Checkout' => __DIR__ . '/../..' . '/includes/core/widgets/divi-modules/includes/modules/Checkout/Checkout.php',
        'WPFunnels\\Widgets\\DiviModules\\Modules\\WPFNL_Next_Step_Button' => __DIR__ . '/../..' . '/includes/core/widgets/divi-modules/includes/modules/NextStepButton/NextStepButton.php',
        'WPFunnels\\Widgets\\DiviModules\\Modules\\WPFNL_OptIN' => __DIR__ . '/../..' . '/includes/core/widgets/divi-modules/includes/modules/OptIn/OptIn.php',
        'WPFunnels\\Widgets\\DiviModules\\Modules\\WPFNL_Order_details' => __DIR__ . '/../..' . '/includes/core/widgets/divi-modules/includes/modules/OrderDetails/OrderDetails.php',
        'WPFunnels\\Widgets\\DiviModules\\WPFNL_DiviModules' => __DIR__ . '/../..' . '/includes/core/widgets/divi-modules/includes/DiviModules.php',
        'WPFunnels\\Widgets\\DiviModules\\Wpfnl_Divi_Editor' => __DIR__ . '/../..' . '/includes/core/widgets/divi-modules/classes/class-wpfnl-divi-builder.php',
        'WPFunnels\\Widgets\\Elementor\\Checkout_Form' => __DIR__ . '/../..' . '/includes/core/widgets/elementor/elements/checkout-widget.php',
        'WPFunnels\\Widgets\\Elementor\\Controls\\Optin_Styles' => __DIR__ . '/../..' . '/includes/core/widgets/elementor/controls/optin-styles.php',
        'WPFunnels\\Widgets\\Elementor\\Controls\\Product_Control' => __DIR__ . '/../..' . '/includes/core/widgets/elementor/controls/products.php',
        'WPFunnels\\Widgets\\Elementor\\Elemenetor_Editror_Compatibility' => __DIR__ . '/../..' . '/includes/core/widgets/elementor/classes/class-wpfnl-elementor-editor.php',
        'WPFunnels\\Widgets\\Elementor\\Manager' => __DIR__ . '/../..' . '/includes/core/widgets/elementor/Manager.php',
        'WPFunnels\\Widgets\\Elementor\\OptinForm' => __DIR__ . '/../..' . '/includes/core/widgets/elementor/elements/optin.php',
        'WPFunnels\\Widgets\\Elementor\\Order_Bump' => __DIR__ . '/../..' . '/includes/core/widgets/elementor/elements/order-bump-widget.php',
        'WPFunnels\\Widgets\\Elementor\\Order_Details' => __DIR__ . '/../..' . '/includes/core/widgets/elementor/elements/order-detail-widget.php',
        'WPFunnels\\Widgets\\Elementor\\Step_Pointer' => __DIR__ . '/../..' . '/includes/core/widgets/elementor/elements/next-step-widget.php',
        'WPFunnels\\Widgets\\Gutenberg\\BlockTypes\\AbstractBlock' => __DIR__ . '/../..' . '/includes/core/widgets/block/block-types/AbstractBlock.php',
        'WPFunnels\\Widgets\\Gutenberg\\BlockTypes\\AbstractDynamicBlock' => __DIR__ . '/../..' . '/includes/core/widgets/block/block-types/AbstractDynamicBlock.php',
        'WPFunnels\\Widgets\\Gutenberg\\BlockTypes\\CheckoutForm' => __DIR__ . '/../..' . '/includes/core/widgets/block/block-types/CheckoutForm.php',
        'WPFunnels\\Widgets\\Gutenberg\\BlockTypes\\NextStepButton' => __DIR__ . '/../..' . '/includes/core/widgets/block/block-types/NextStepButton.php',
        'WPFunnels\\Widgets\\Gutenberg\\BlockTypes\\OptinForm' => __DIR__ . '/../..' . '/includes/core/widgets/block/block-types/OptinForm.php',
        'WPFunnels\\Widgets\\Gutenberg\\BlockTypes\\OrderDetails' => __DIR__ . '/../..' . '/includes/core/widgets/block/block-types/OrderDetails.php',
        'WPFunnels\\Widgets\\Gutenberg\\Checkout_Block' => __DIR__ . '/../..' . '/includes/core/widgets/block/assets/js/blocks/checkout-form/index.php',
        'WPFunnels\\Widgets\\Gutenberg\\Manager' => __DIR__ . '/../..' . '/includes/core/widgets/block/Manager.php',
        'WPFunnels\\Widgets\\Gutenberg\\Order_Detail' => __DIR__ . '/../..' . '/includes/core/widgets/block/assets/js/blocks/order-details/index.php',
        'WPFunnels\\Widgets\\Gutenberg\\Wpfnl_Gutenberg_Editor' => __DIR__ . '/../..' . '/includes/core/widgets/block/classes/class-wpfnl-gutenberg-editor.php',
        'WPFunnels\\Widgets\\Oxygen\\Checkout' => __DIR__ . '/../..' . '/includes/core/widgets/oxygen/elements/checkout/Checkout.php',
        'WPFunnels\\Widgets\\Oxygen\\Elements' => __DIR__ . '/../..' . '/includes/core/widgets/oxygen/elements/abstract-class-wpfnl-oxygen-elements.php',
        'WPFunnels\\Widgets\\Oxygen\\Manager' => __DIR__ . '/../..' . '/includes/core/widgets/oxygen/Manager.php',
        'WPFunnels\\Widgets\\Oxygen\\NextStepButton' => __DIR__ . '/../..' . '/includes/core/widgets/oxygen/elements/next-step-button/NextStepButton.php',
        'WPFunnels\\Widgets\\Oxygen\\Optin' => __DIR__ . '/../..' . '/includes/core/widgets/oxygen/elements/optin/Optin.php',
        'WPFunnels\\Widgets\\Oxygen\\OrderDetails' => __DIR__ . '/../..' . '/includes/core/widgets/oxygen/elements/order-details/OrderDetails.php',
        'WPFunnels\\Widgets\\Wpfnl_Widgets_Manager' => __DIR__ . '/../..' . '/includes/core/widgets/Manager.php',
        'WPFunnels\\Wpfnl_functions' => __DIR__ . '/../..' . '/includes/utils/class-wpfnl-functions.php',
        'WP_Async_Request' => __DIR__ . '/..' . '/deliciousbrains/wp-background-processing/classes/wp-async-request.php',
        'WP_Background_Process' => __DIR__ . '/..' . '/deliciousbrains/wp-background-processing/classes/wp-background-process.php',
        'Wpfnl_Activator' => __DIR__ . '/../..' . '/includes/utils/class-wpfnl-activator.php',
        'Wpfnl_Deactivator' => __DIR__ . '/../..' . '/includes/utils/class-wpfnl-deactivator.php',
        'Wpfnl_Divi_Theme_Compatibility' => __DIR__ . '/../..' . '/includes/core/widgets/divi-modules/classes/class-wpfnl-divi-theme-compatibility.php',
        'Wpfnl_Loader' => __DIR__ . '/../..' . '/includes/utils/class-wpfnl-loader.php',
        'Wpfnl_Step_Template' => __DIR__ . '/../..' . '/public/classes/class-wpfnl-step-template.php',
        'Wpfnl_i18n' => __DIR__ . '/../..' . '/includes/utils/class-wpfnl-i18n.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit277cca59858b7802893578b1a0feb959::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit277cca59858b7802893578b1a0feb959::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit277cca59858b7802893578b1a0feb959::$classMap;

        }, null, ClassLoader::class);
    }
}
