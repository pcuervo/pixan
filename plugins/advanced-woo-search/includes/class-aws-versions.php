<?php
/**
 * Versions capability
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Versions' ) ) :

    /**
     * Class for plugin search
     */
    class AWS_Versions {

        /**
         * Return a singleton instance of the current class
         *
         * @return object
         */
        public static function factory() {
            static $instance = false;

            if ( ! $instance ) {
                $instance = new self();
                $instance->setup();
            }

            return $instance;
        }

        /**
         * Placeholder
         */
        public function __construct() {}

        /**
         * Setup actions and filters for all things settings
         */
        public function setup() {

            $current_version = get_option( 'aws_plugin_ver' );
            $reindex_version = get_option( 'aws_reindex_version' );

            if ( ! ( $reindex_version ) ) {
                add_action( 'admin_notices', array( $this, 'admin_notice_no_index' ) );
            }

            if ( $reindex_version && version_compare( $reindex_version, '1.16', '<' ) ) {
                add_action( 'admin_notices', array( $this, 'admin_notice_reindex' ) );
            }

            if ( $current_version ) {

                if ( version_compare( $current_version, '1.16', '<' ) ) {

                    $settings = get_option( 'aws_settings' );

                    if ( $settings ) {
                        if ( ! isset( $settings['outofstock'] ) ) {
                            $settings['outofstock'] = 'false';
                            update_option( 'aws_settings', $settings );
                        }
                    }

                }

                if ( version_compare( $current_version, '1.17', '<' ) ) {

                    $settings = get_option( 'aws_settings' );

                    if ( $settings ) {
                        if ( ! isset( $settings['use_analytics'] ) ) {
                            $settings['use_analytics'] = 'false';
                            update_option( 'aws_settings', $settings );
                        }
                    }

                }

            }

            update_option( 'aws_plugin_ver', AWS_VERSION );

        }

        /**
         * Admin notice for table first reindex
         */
        public function admin_notice_no_index() { ?>
            <div class="updated notice is-dismissible">
                <p><?php printf( esc_html__( 'Advanced Woo Search: Please go to plugin setting page and start the indexing of your products. %s', 'aws' ), '<a class="button button-secondary" href="'.esc_url( admin_url('admin.php?page=aws-options') ).'">'.esc_html__( 'Reindex Table', 'aws' ).'</a>'  ); ?></p>
            </div>
        <?php }

        /**
         * Admin notice for table reindex
         */
        public function admin_notice_reindex() { ?>
            <div class="updated notice is-dismissible">
                <p><?php printf( esc_html__( 'Advanced Woo Search: Please reindex table for proper work of new plugin features. %s', 'aws' ), '<a class="button button-secondary" href="'.esc_url( admin_url('admin.php?page=aws-options') ).'">'.esc_html__( 'Reindex Table', 'aws' ).'</a>'  ); ?></p>
            </div>
        <?php }

    }


endif;

add_action( 'admin_init', 'AWS_Versions::factory' );