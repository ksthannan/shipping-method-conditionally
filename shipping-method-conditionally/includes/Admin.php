<?php
namespace Themeongon\Woosame;

/**
 * The admin class
 */
class Admin
{

    public function __construct()
    {
        add_action('woocommerce_shipping_zone_after_methods_table', [$this, 'wooShipScripts']);
        add_action('admin_enqueue_scripts', [$this, 'adminCss']);
        add_action('admin_init', [$this, 'adminFunctions']);
    }

    public function adminFunctions()
    {

        if (isset($_GET['instance_id']) && isset($_GET['method_title']) && isset($_GET['zone'])) {

            // query from url params
            $zone = sanitize_text_field($_GET['zone']);
            $method_title = sanitize_text_field($_GET['method_title']);
            $instance_id = sanitize_text_field($_GET['instance_id']);

            $ship_data = get_option('ship_hide_req') ? get_option('ship_hide_req') : array();

            $ship_req = array(
                'instance_id' => $instance_id,
                'method_title' => $method_title,
                'zone' => $zone,
            );

            $key = array_search(strval($instance_id), array_column($ship_data, 'instance_id'), true);
            if ($ship_data[$key]['instance_id'] == $instance_id || $key !== false) {
                unset($ship_data[$key]);
                // update
                update_option('ship_hide_req', $ship_data);
            } else {
                array_push($ship_data, $ship_req);
                // update
                update_option('ship_hide_req', $ship_data);
            }

            wp_redirect($_SERVER['HTTP_REFERER'] . '/updated=1');
            die();

        }

        if (isset($_GET['reset_condition'])) {
            $reset_condition = sanitize_text_field($_GET['reset_condition']);
            update_option('ship_hide_req', array());
            update_option('ship_hide_method_id', '');
            update_option('ship_hide_method_title', '');
            wp_redirect($_SERVER['HTTP_REFERER']);
            die();
        }

    }

    public function adminCss()
    {
        wp_enqueue_style('woo-cond-ship-style', WOO_SDDTL_ASSETS . '/css/con-ship.css', array(), WOO_SDDTL_VERSION, 'all');
        wp_enqueue_script('woo-cond-ship-script', WOO_SDDTL_ASSETS . '/js/con-ship.js', array('jquery'), WOO_SDDTL_VERSION, true);

    }

    public function wooShipScripts()
    {
        $ship_items = '';
        $in = 0;
        $ship_hide_req = get_option('ship_hide_req') ? get_option('ship_hide_req') : array();
        foreach ($ship_hide_req as $ship_hide_req_item) {
            if ($in > 0) {
                $ship_items .= ',' . $ship_hide_req_item['instance_id'];
            } else {
                $ship_items .= $ship_hide_req_item['instance_id'];
            }

            $in++;
        }
        ?>
        <div class="wrap">
                        <table class="form-table" role="presentation"><tbody><tr>
                        <th scope="row"><label for="shippng_method"><?php esc_html_e('Shipping Method Showing Conditionally', 'woo-wsmc');?></label></th>
                        <td>
                            <b><?php esc_html_e('Click on the shipping item below to activate timing conditions.', 'woo-wsmc');?></b>

    <div class="all_shipping_methods">
        <ul id="activate_ship" data-ship="[<?php echo esc_html_e($ship_items); ?>]"></ul>
        <hr>
        <p><a href="admin.php?page=woo-shipping-show-hide"><?php esc_html_e('Go back to time settings', 'woo-wsmc');?></a></p><br>
        <p><a href=""><?php esc_html_e('Refresh', 'woo-wsmc');?></a></p>
    </div>
          </td>
            </tr></tbody></table>
       <?php
}

}