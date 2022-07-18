<?php
namespace Themeongon\Woosame;

class functions
{
    public $isOpen;

    public $times;

    public $result;

    public $timeZone;

    public function __construct()
    {

        /**
         * Opening Times
         */
        $mon = esc_html(get_option('opening_hours_mon'));
        $tue = esc_html(get_option('opening_hours_tue'));
        $wed = esc_html(get_option('opening_hours_wed'));
        $thu = esc_html(get_option('opening_hours_thu'));
        $fri = esc_html(get_option('opening_hours_fri'));
        $sat = esc_html(get_option('opening_hours_sat'));
        $sun = esc_html(get_option('opening_hours_sun'));

        $this->times = array(
            'opening_hours_mon' => $mon,
            'opening_hours_tue' => $tue,
            'opening_hours_wed' => $wed,
            'opening_hours_thu' => $thu,
            'opening_hours_fri' => $fri,
            'opening_hours_sat' => $sat,
            'opening_hours_sun' => $sun,
        );

        $this->isOpen = $this->isOpen($this->times) ? "open" : "close";
        $this->result = array(
            "today" => strtolower(wp_date('l')),
            "current_time" => wp_date('g:ia'),
            "open_close" => $this->isOpen,
        );

        if ($this->isOpen === 'close') {
            add_filter('woocommerce_package_rates', [$this, "hide_shipping"], 99, 2);
        }

    }

    /**
     * Is Open Time
     */

    public function isOpen($times, $timeToCheck = 'now')
    {

        $tmzs = get_option('timezone_string') ? get_option('timezone_string') : 'Asia/Dhaka';
        if (!empty($tmzs)) {
            date_default_timezone_set($tmzs);
        }

        $timeToCheckAsUnixTimestamp = strtotime($timeToCheck);

        $yesterdayTimes = $todayTimes = '';
        //  Find yesterday's times and today's times
        foreach ($times as $day => $timeRange) {
            $yesterdayTimes = (stripos($day, date("D", time() - 60 * 60 * 24)) !== false ? $timeRange : $yesterdayTimes);
            $todayTimes = (stripos($day, date("D")) !== false ? $timeRange : $todayTimes);
        }
        //  Handle closed
        if (strcasecmp($todayTimes, 'closed') == 0) {
            return false;
        }

        if (strcasecmp($yesterdayTimes, 'closed') == 0) {
            $yesterdayTimes = '12am - 12am';
        }

        //  Process and check with yesterday's timings
        foreach (explode(',', $yesterdayTimes) as $timeRanges) {
            list($from, $to) = explode('-', $timeRanges);
            list($fromAsUnixTimestamp, $toAsUnixTimestamp) = array(strtotime($from . ' previous day'), strtotime($to . ' previous day'));
            $toAsUnixTimestamp = ($toAsUnixTimestamp < $fromAsUnixTimestamp ? strtotime($to) : $toAsUnixTimestamp);
            if ($fromAsUnixTimestamp <= $timeToCheckAsUnixTimestamp and $timeToCheckAsUnixTimestamp <= $toAsUnixTimestamp) {
                return true;
            }

        }
        //  Process and check with today's timings
        foreach (explode(',', $todayTimes) as $timeRanges) {
            list($from, $to) = explode('-', $timeRanges);
            list($fromAsUnixTimestamp, $toAsUnixTimestamp) = array(strtotime($from), strtotime($to));
            $toAsUnixTimestamp = ($toAsUnixTimestamp < $fromAsUnixTimestamp ? strtotime($to . ' next day') : $toAsUnixTimestamp);
            if ($fromAsUnixTimestamp <= $timeToCheckAsUnixTimestamp and $timeToCheckAsUnixTimestamp <= $toAsUnixTimestamp) {
                return true;
            }

        }
        return false;
    }

    /**
     * Hide Shipping
     *
     * @param [type] $rates
     * @param [type] $package
     * @return void
     */
    public function hide_shipping($rates, $package)
    {

        $rates_r = array();

        $ship_hide_req = get_option('ship_hide_req') ? get_option('ship_hide_req') : array();

        foreach ($rates as $rate_id => $rate) {

            if (
                (isset($ship_hide_req[0]['instance_id']) && $ship_hide_req[0]['instance_id'] == $rate->instance_id)
                || (isset($ship_hide_req[1]['instance_id']) && $ship_hide_req[1]['instance_id'] == $rate->instance_id)
                || (isset($ship_hide_req[2]['instance_id']) && $ship_hide_req[2]['instance_id'] == $rate->instance_id)
                || (isset($ship_hide_req[3]['instance_id']) && $ship_hide_req[3]['instance_id'] == $rate->instance_id)
                || (isset($ship_hide_req[4]['instance_id']) && $ship_hide_req[4]['instance_id'] == $rate->instance_id)
                || (isset($ship_hide_req[5]['instance_id']) && $ship_hide_req[5]['instance_id'] == $rate->instance_id)
                || (isset($ship_hide_req[6]['instance_id']) && $ship_hide_req[6]['instance_id'] == $rate->instance_id)
            ) {
                continue;
            } else {
                $rates_r[$rate_id] = $rate;
            }

        }

        // return !empty($rates_r) ? $rates_r : $rates;
        return $rates_r;
    }

}
