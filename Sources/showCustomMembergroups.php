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

	if (!isset($txt['sCM_main']))
		loadLanguage('showCustomMembergroups');

	// ManageAttachments.php doesn't have a single hook on it so I can't add this section there... :(
	$areas['config']['areas']['modsettings']['subsections']['sCM'] = array($txt['sCM_setting_pageTitle']);

}