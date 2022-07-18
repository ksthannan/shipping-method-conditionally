<?php
namespace Themeongon\Woosame;

class Installer
{
    public function run()
    {
        $this->add_version();
    }

    public function add_version()
    {
        $installed = get_option('woo_same_installed');

        if (!$installed) {
            update_option('woo_same_installed', time());
        }

        update_option('woo_same_version', WOO_SDDTL_VERSION);

    }
}
