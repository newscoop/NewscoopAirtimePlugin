<?php
/**
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * List Airtime Live Schedule Info  block
 *
 * @param array $params
 * @param string $content
 * @param Smarty_Internal_Template $template
 * @param bool $repeat
 * @return string
 */
function smarty_block_list_airtime_live_info(array $params, $content, &$smarty, &$repeat)
{

    $instanceName = empty($params['instanceName']) ? null : $params['instanceName'];

    $container = \Zend_Registry::get('container');
    $cacheService = $container->get('newscoop.cache');
    $airtimeService = $container->get('newscoop_airtime_plugin.airtime_service');
    $cacheKey = $cacheService->getCacheKey("airtime_live_schedule_" . $instanceName);

    if (!isset($content)) {

        $results = $airtimeService->getLiveInfo($instanceName);
        $cacheService->save($cacheKey, json_encode($results));
    }

    $schedule = json_decode($cacheService->fetch($cacheKey), true);

    if (!empty($schedule)) {
        // load the current record
        $fields = array_keys($results);
        foreach ($fields as $field) {
            if (($field == "currentShow") || ($field == 'nextShow')) {
                // these fields are arrays for some reason?
                $smarty->assign($field, $results[$field][0]);
            } else {
                $smarty->assign($field, $results[$field]);
            }
        }
        $cacheService->save($cacheKey, '{}');
        $repeat = true;
    } else {
        $repeat = false;
    }

    return $content;
}

