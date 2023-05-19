<?php

namespace Skeleton\Blocks\Integrations\Elementor;

use Skeleton\Blocks\Contracts\IntegrationContract;
use function Skeleton\Support\path;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Elementor implements IntegrationContract
{
    /**
     * Addon Version
     *
     * @since 1.0.0
     * @var string The addon version.
     */
    public const VERSION = '1.0.0';

    /**
     * Minimum Elementor Version
     *
     * @since 1.0.0
     * @var string Minimum Elementor version required to run the addon.
     */
    public const MINIMUM_ELEMENTOR_VERSION = '3.7.0';

    /**
     * Minimum PHP Version
     *
     * @since 1.0.0
     * @var string Minimum PHP version required to run the addon.
     */
    public const MINIMUM_PHP_VERSION = '7.4';

    public function __construct()
    {
        if ($this->isCompatible()) {
            add_action('elementor/init', function () {
                require_once path('elementor/Factory.php');
                new \Skeleton\Elementor\Factory();
            });
        }
    }

    public function shouldRun($args): bool
    {
        return did_action('elementor/loaded') > 0;
    }

    public function isCompatible()
    {
        // Check if Elementor installed and activated
        if (! did_action('elementor/loaded')) {
            add_action('admin_notices', function () {
                $this->adminNoticeMissingMainPlugin();
            });
            return false;
        }

        // Check for required Elementor version
        if (! version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', function () {
                $this->adminNoticeMinimumElementorVersion();
            });
            return false;
        }

        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', function () {
                $this->adminNoticeMinimumPhpVersion();
            });
            return false;
        }

        return true;
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have Elementor installed or activated.
     *
     * @since 1.0.0
     * @access public
     */
    public function adminNoticeMissingMainPlugin()
    {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            // translators: 1: Plugin name 2: Elementor
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'elementor-test-addon'),
            '<strong>' . esc_html__('Elementor Test Addon', 'elementor-test-addon') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-test-addon') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required Elementor version.
     *
     * @since 1.0.0
     * @access public
     */
    public function adminNoticeMinimumElementorVersion()
    {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            // translators: 1: Plugin name 2: Elementor 3: Required Elementor version
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-test-addon'),
            '<strong>' . esc_html__('Elementor Test Addon', 'elementor-test-addon') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-test-addon') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since 1.0.0
     * @access public
     */
    public function adminNoticeMinimumPhpVersion()
    {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            // translators: 1: Plugin name 2: PHP 3: Required PHP version
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-test-addon'),
            '<strong>' . esc_html__('Elementor Test Addon', 'elementor-test-addon') . '</strong>',
            '<strong>' . esc_html__('PHP', 'elementor-test-addon') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function prepareData($args)
    {
    }
}
