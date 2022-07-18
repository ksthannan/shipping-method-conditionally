<?php
namespace Themeongon\Woosame\Admin;

class Settings
{

    public $shippingData = [];

    public function __construct()
    {
        add_filter('woocommerce_package_rates', [$this, "hide_shipping_data"], 10, 2);

        add_action('admin_init', [$this, 'section_setings']);

    }
    public function ship_section_callback_function()
    {

        $ship_data = get_option('ship_hide_req');

        echo '<div class="entry_info"><table class="form-table" role="presentation"><tbody><tr><th><h3>' . esc_html("Current Settings", "woo-wsmc") . '</h3></th><td>';

        if (count($ship_data) > 0) {
            foreach ($ship_data as $ship_data_item) {
                ?>
            <p class="active_shipping"><b><?php echo esc_html($ship_data_item['method_title']) . '</b> - <i>' . esc_html($ship_data_item['zone']) . '</i>'; ?> </p>
        <?php
}
        } else {
            echo '<p>' . esc_html('No Active Shipping Method Conditionally', 'woo-wsmc') . '</p>';
        }

        ?>
        <br>
        <br>
        <a class="shipping_settings_zone" href="admin.php?page=wc-settings&tab=shipping&section="><?php esc_html_e('Shipping Method Settings into Zone', 'woo-wsmc');?></a>
    <?php
echo '</td></tr></tbody></table></div>';

    }

    public function ship_setting_markup()
    {
        ob_start();
        ?>

            <table>
<tr>
    <th><hr></th>
    <td><hr></td>
</tr>
            <tr>
                            <th><h3><?php _e('Custom Time Range Settings', 'woo-wsmc');?></h3></th>
                            <td class="info_frame_box">
                                <?php
$tmzs = get_option('timezone_string') ? get_option('timezone_string') : '';
        if (empty($tmzs)) {
            echo '<p style="color:#D63638;">' . esc_html('Please change timezone by zone name instead of UTC/Manual-Offsets to get more accurate results.', 'woo-wsmc') . '</p>';
        }
        $time_zone = wp_timezone_string();
        $today = wp_date('l');
        $tm = wp_date('g:ia');
        echo '<p><a href="options-general.php">' . __('Timezone settings', 'woo-wsmc') . '</a></p><p>Selected time zone : <b>' . esc_html($time_zone) . '</b><br> Today is <b>' . esc_html($today) . '</b><br> Time now <b>' . esc_html($tm) . '</b></p>';
        ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                               <label for="monday"> Monday</label>
                            </th>
                            <td>
                                <input type="text" id="monday" name="opening_hours_mon" class="regular-text" placeholder="09:00am - 06:30pm" value="<?php echo esc_html(get_option('opening_hours_mon')); ?>">
                                <p>Example: 09:00am - 06:30pm</p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="tuesday">Tuesday</label>
                            </th>
                            <td>
                                <input type="text" id="tuesday" name="opening_hours_tue" class="regular-text" placeholder="09:00am - 06:30pm" value="<?php echo esc_html(get_option('opening_hours_tue')); ?>">
                                <p>Example: 09:00am - 06:30pm</p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                            <label for="wednesday">Wednesday</label>
                            </th>
                            <td>
                                <input type="text" id="wednesday" name="opening_hours_wed" class="regular-text" placeholder="09:00am - 06:30pm" value="<?php echo esc_html(get_option('opening_hours_wed')); ?>">
                                <p>Example: 09:00am - 06:30pm</p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                            <label for="thursday">Thursday</label>
                            </th>
                            <td>
                                <input type="text" id="thursday" name="opening_hours_thu" class="regular-text" placeholder="09:00am - 06:30pm" value="<?php echo esc_html(get_option('opening_hours_thu')); ?>">
                                <p>Example: 09:00am - 06:30pm</p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                            <label for="friday">Friday</label>
                            </th>
                            <td>
                                <input type="text" id="friday" name="opening_hours_fri" class="regular-text" placeholder="09:00am - 06:30pm" value="<?php echo (get_option('opening_hours_fri')); ?>">
                                <p>Example: 09:00am - 06:30pm</p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                            <label for="saturday">Saturday</label>
                            </th>
                            <td>
                                <input type="text" id="saturday" name="opening_hours_sat" class="regular-text" placeholder="09:00am - 06:30pm" value="<?php echo esc_html(get_option('opening_hours_sat')); ?>">
                                <p>Example: 09:00am - 06:30pm</p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                            <label for="sunday">Sunday</label>
                            </th>
                            <td>
                                <input type="text" id="sunday" name="opening_hours_sun" class="regular-text"  placeholder="closed" value="<?php echo esc_html(get_option('opening_hours_sun')); ?>">
                                <p>Example: closed</p>
                            </td>
                        </tr>
            </table>
            <?php
return ob_get_contents();
    }

    public function section_setings()
    {
        add_settings_section(
            'ship_setting_section',
            __('Manage shipping method conditionally based on time tange', 'woo-wsmc'),
            [$this, 'ship_section_callback_function'],
            'ship_limit_opt'
        );

        add_settings_field(
            'ship_time_zone',
            __('', 'woo-wsmc'),
            [$this, 'ship_setting_markup'],
            'ship_limit_opt',
            'ship_setting_section'
        );

        register_setting('ship_limit_opt', 'select_time_zone');
        register_setting('ship_limit_opt', 'opening_hours_mon');
        register_setting('ship_limit_opt', 'opening_hours_tue');
        register_setting('ship_limit_opt', 'opening_hours_wed');
        register_setting('ship_limit_opt', 'opening_hours_thu');
        register_setting('ship_limit_opt', 'opening_hours_fri');
        register_setting('ship_limit_opt', 'opening_hours_sat');
        register_setting('ship_limit_opt', 'opening_hours_sun');
    }

}