<?php

/**
 *
 * @package showCustomMembergroups mod
 * @version 1.0
 * @author Jessica González <suki@missallsunday.com>
 * @copyright Copyright (c) 2013, Jessica González
 * @license http://www.mozilla.org/MPL/2.0/
 */

if (!defined('SMF'))
	die('No direct access...');

function sCM_modifications(&$sub_actions)
{
	global $context;

	$sub_actions['sCM'] = 'sCM_settings';
	$context[$context['admin_menu_name']]['tab_data']['tabs']['sCM'] = array();
}

function sCM_admin_areas(&$areas)
{
	global $txt;

	if (!isset($txt['sCM_title'] ))
		loadLanguage('showCustomMembergroups');

	// ManageAttachments.php doesn't have a single hook on it so I can't add this section there... :(
	$areas['config']['areas']['modsettings']['subsections']['sCM'] = array($txt['sCM_setting_pageTitle']);

}

function sCM_settings(&$return_config = false)
{
	global $context, $scripturl, $txt;

	$config_vars = array();

	if ($return_config)
		return $config_vars;

	$context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=sCM';
	$context['settings_title'] = $txt['sCM_title'] ;

	if (empty($config_vars))
	{
		$context['settings_save_dont_show'] = true;
		$context['settings_message'] = '<div align="center">' . $txt['modification_no_misc_settings'] . '</div>';

		return prepareDBSettingContext($config_vars);
	}

	if (isset($_GET['save']))
	{
		checkSession();
		$save_vars = $config_vars;
		saveDBSettings($save_vars);
		redirectexit('action=admin;area=modsettings;sa=sCM');
	}

	prepareDBSettingContext($config_vars);
}
