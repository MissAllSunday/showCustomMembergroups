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

	$config_vars = array(
		array('text', 'sCM_groups_ids', 'size' => 22, 'subtext' => $txt['sCM_groups_ids_sub']),
	);

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

function getMemberGroups($allGroups, $id_member)
{
	global $smcFunc, $modSettings, $settings;

	if (empty($allGroups))
		return false;

	$groups = array_intersect(!empty($modSettings['sCM_groups_ids']) ? $modSettings['sCM_groups_ids'] : array(), $allGroups);

	if (($returnedGroups = cache_get_data('sCM_groups-'. $id_member, 120)) == null)
	{
		$request = $smcFunc['db_query']('', '
			SELECT id_group, group_name, online_color, stars
			FROM {db_prefix}membergroups
			WHERE id_group IN ({array_int:groups})
			ORDER BY id_group',
			array(
				'groups' => explode(',', $groups),
			)
		);

		$returnedGroups = array();

		while ($row = $smcFunc['db_fetch_assoc']($request))
		{
			$stars = empty($row['stars']) ? array('', '') : explode('#', $row['stars']);

			$returnedGroups[$row['id_group']] = array(
				'name' => $row['group_name'],
				'color' => $row['online_color'],
				'star' => $stars[1],
				'star_number' => $stars[0],
				'image' => '<img src="'. $settings['images_url'] .'/'. $stars[1] .'" />',
			);
		}

		$smcFunc['db_free_result']($request);

		// Cache this beauty
		cache_put_data('sCM_groups-'. $id_member, $returnedGroups, 120);
	}

	return $returnedGroups;
}
