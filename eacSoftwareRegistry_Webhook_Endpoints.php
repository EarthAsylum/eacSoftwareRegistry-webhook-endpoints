<?php
/**
 * EarthAsylum Consulting {eac} Software Registration Server - WooCommerce Webhook Endpoints
 *
 * @category	WordPress Plugin
 * @package		{eac}SoftwareRegistry WooCommerce Webhook Endpoints
 * @author		Kevin Burkholder <KBurkholder@EarthAsylum.com>
 * @copyright	Copyright (c) 2026 EarthAsylum Consulting <www.earthasylum.com>
 * @link		https://swregistry.earthasylum.com/
 * @link 		https://swregistry.earthasylum.com/webhooks-for-woocommerce/
 * @link 		https://wordpress.org/plugins/eacsoftwareregistry-webhook-endpoints
 * @link 		https://github.com/EarthAsylum/eacSoftwaReregistry-webhook-endpoints
 *
 * @wordpress-plugin
 * Plugin Name:			{eac}SoftwareRegistry Webhook Endpoints
 * Description:			Software Registration Server WooCommerce Webhook Endpoints - enables the use of WooCommerce Webhooks to create or update a software registration.
 * Version:				1.1.7
 * Requires at least:	5.8
 * Tested up to:		7.1
 * Requires PHP:		8.1
 * Plugin URI:          https://swregistry.earthasylum.com/webhooks-for-woocommerce/
 * Author:				EarthAsylum Consulting
 * Author URI:			http://www.earthasylum.com
 * License: 			GPLv3 or later
 * License URI: 		https://www.gnu.org/licenses/gpl.html
 * Text Domain:			eacSoftwareRegistry
 * Domain Path:			/languages
 */

/*
 * This simple plugin file responds to the 'eacSoftwareRegistry_load_extensions' filter to load additional extensions.
 * Using this method prevents overwriting extensions when the plugin is updated or reinstalled.
 */

namespace EarthAsylumConsulting;

if (!class_exists('\\EarthAsylumConsulting\\eacSoftwareRegistry',false))
{
	\add_action( 'admin_notices', function()
		{
			echo '<div class="notice notice-error is-dismissible">'.
				 '<em>{eac}SoftwareRegistry Webhook Endpoints</em> '.
				 'requires installation & activation of '.
				 '<a href="https://swregistry.earthasylum.com/software-registration-server/" target="_blank">'.
				 '{eac}SoftwareRegistry</a>.</div>';
		}
	);
	return;
}

class eacSoftwareRegistry_Webhook_Endpoints
{
	/**
	 * constructor method
	 *
	 * @return	void
	 */
	public function __construct()
	{
		/**
		 * eacSoftwareRegistry_load_extensions - get the extensions directory to load
		 *
		 * @param 	array	$extensionDirectories - array of [plugin_slug => plugin_directory]
		 * @return	array	updated $extensionDirectories
		 */
		add_filter( 'eacSoftwareRegistry_load_extensions',	function($extensionDirectories)
			{
				if (is_admin())
				{
					/*
					 * Enable update notice (self hosted or wp hosted)
					 */
					eacSoftwareRegistry::loadPluginUpdater(__FILE__,'wp');

					/*
					 * Add links on plugins page
					 */
					add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ),function($pluginLinks, $pluginFile, $pluginData)
						{
							return array_merge(
								[
									'settings'		=> eacSoftwareRegistry::getSettingsLink($pluginData,'WooCommerce'),
									'documentation'	=> eacSoftwareRegistry::getDocumentationLink($pluginData),
									'support'		=> eacSoftwareRegistry::getSupportLink($pluginData),
								],
								$pluginLinks
							);
						},20,3
					);
				}

				/*
    			 * Add our extension to load
    			 */
				$extensionDirectories[ plugin_basename( __FILE__ ) ] = [plugin_dir_path( __FILE__ )];
				return $extensionDirectories;
			}
		);
	}
}
new \EarthAsylumConsulting\eacSoftwareRegistry_Webhook_Endpoints();
?>
