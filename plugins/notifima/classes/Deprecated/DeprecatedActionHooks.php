<?php
/**
 * DeprecatedActionHooks class file.
 *
 * @package Notifima
 */

namespace Notifima\Deprecated;

/**
 * Deprecated action hooks
 *
 * @package Notifima
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles deprecation notices and triggering of legacy action hooks.
 */
class DeprecatedActionHooks extends \WC_Deprecated_Hooks {

    /**
     * Array of deprecated hooks we need to handle. Format of 'new' => 'old'.
     *
     * @var array
     */
    protected $deprecated_hooks = array(
        'wc_product_stock_alert_new_subscriber_added' => 'dc_wc_product_stock_alert_new_subscriber_added',
        'woo_stock_manager_settings_after_save'       => 'woo_stock_alert_settings_after_save',
        'notifima_before_subscribe_product'           => 'stock_manager_before_subscribe',
        'notifima_loaded'                             => 'stock_manager_loaded',
        'notifima_after_save_settings'                => 'stock_manager_settings_after_save',
        'notifima_before_subscription_form'           => 'woocommerce_product_stock_alert_form_before',
        'notifima_after_subscription_form'            => 'woocommerce_product_stock_alert_form_after',
    );

    /**
     * Array of versions on each hook has been deprecated.
     *
     * @var array
     */
    protected $deprecated_version = array(
        'dc_wc_product_stock_alert_new_subscriber_added' => '2.0.0',
        'woocommerce_product_stock_alert_form_before'    => '2.4.0',
        'woocommerce_product_stock_alert_form_after'     => '2.4.0',
        'woo_stock_alert_settings_after_save'            => '2.4.0',
        'stock_manager_before_subscribe'                 => '2.5.17',
        'stock_manager_loaded'                           => '2.5.17',
        'stock_manager_settings_after_save'              => '2.5.17',
        'woocommerce_stock_manager_form_before'          => '2.5.17',
        'woocommerce_stock_manager_form_after'           => '2.5.17',
    );

    /**
     * Hook into the new hook so we can handle deprecated hooks once fired.
     *
     * @param string $hook_name Hook name.
     */
    public function hook_in( $hook_name ) {
        add_action( $hook_name, array( $this, 'maybe_handle_deprecated_hook' ), -1000, 8 );
    }

    /**
     * If the old hook is in-use, trigger it.
     *
     * @param  string $new_hook          New hook name.
     * @param  string $old_hook          Old hook name.
     * @param  array  $new_callback_args New callback args.
     * @param  mixed  $return_value      Returned value.
     * @return mixed
     */
    public function handle_deprecated_hook( $new_hook, $old_hook, $new_callback_args, $return_value ) {
        if ( has_action( $old_hook ) ) {
            $this->display_notice( $old_hook, $new_hook );
            $return_value = $this->trigger_hook( $old_hook, $new_callback_args );
        }

        return $return_value;
    }

    /**
     * Fire off a legacy hook with it's args.
     *
     * @param  string $old_hook          Old hook name.
     * @param  array  $new_callback_args New callback args.
     * @return mixed
     */
    protected function trigger_hook( $old_hook, $new_callback_args ) {
        do_action_ref_array( $old_hook, $new_callback_args );
    }
}
