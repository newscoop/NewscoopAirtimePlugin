<?php
/**
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * List Airtime Schedule By Week block
 *
 * @param array $params
 * @param string $content
 * @param Smarty_Internal_Template $template
 * @param bool $repeat
 * @return string
 */
function smarty_block_list_airtime_week_info(array $params, $content, &$smarty, &$repeat)
{
    $instanceName = empty($params['instanceName']) ? null : $params['instanceName'];
    $container = \Zend_Registry::get('container');
    $cacheService = $container->get('newscoop.cache');
    $airtimeService = $container->get('newscoop_airtime_plugin.airtime_service');

    $cacheKey = $cacheService->getCacheKey("airtime_weekly_schedule_" . $instanceName);

    if (!isset($content)) {

        $results = $airtimeService->getWeekInfo($instanceName);
        $cacheService->save($cacheKey, json_encode($results));
    }

    $days = json_decode($cacheService->fetch($cacheKey), true);

    if (!empty($days)) {
        // load the current record
        $dow = current(array_keys($days));
        $day = array_shift($days);
        if (is_array($day)) { 
            $smarty->assign('dow', $dow);
            $smarty->assign('day', $day);
        }
        $cacheService->save($cacheKey, json_encode($days));
        $repeat = true;
    } else {
        $repeat = false;
    } 

    return $content;
}

