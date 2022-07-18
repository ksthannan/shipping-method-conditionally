<?php
namespace Themeongon\Woosame\Admin;

class Menu
{
    public $menu;

    public function __construct()
    {
        add_action('admin_menu', [$this, 'admin_menu']);
        new Settings();
    }

    public function admin_menu()
    {

        $parent_slug = 'woocommerce';
        $capability = 'manage_options';
        add_submenu_page($parent_slug, __('Shipping Method Show/Hide Conditionally', 'woo-wsmc'), esc_html('Shipping Method Manage Conditionally', 'woo-wsmc'), $capability, 'woo-shipping-show-hide', [$this, 'plugin_seetings']);

    }

    public function plugin_seetings()
    {

        ?>
    <form method="POST" action="options.php">
    <?php
settings_fields('ship_limit_opt');
        do_settings_sections('ship_limit_opt');
        submit_button();
        ?>
    </form>
    <?php
}
}
