<?php
/**
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * List Airtime History block
 *
 * @param array $params
 * @param string $content
 * @param Smarty_Internal_Template $template
 * @param bool $repeat
 * @return string
 */
function smarty_block_list_airtime_show_history(array $params, $content, &$smarty, &$repeat)
{
    $instanceName = empty($params['instanceName']) ? null : $params['instanceName'];
    $start = empty($params['start']) ? null : $params['start'];
    $end = empty($params['end']) ? null : $params['end'];

    $container = \Zend_Registry::get('container');
    $cacheService = $container->get('newscoop.cache');
    $airtimeService = $container->get('newscoop_airtime_plugin.airtime_service');
    $cacheKey = $cacheService->getCacheKey("airtime_show_history_" . $instanceName . "_" . $start . $end);

    if (!isset($content)) {

        $results = $airtimeService->getShowHistory($instanceName, $start, $end);
        $cacheService->save($cacheKey, json_encode($results));
    }

    $shows = json_decode($cacheService->fetch($cacheKey), true);

    if (!empty($shows)) {
        // load the current record
        $show = array_shift($shows);
        if (is_array($show)) {
            $smarty->assign('show', $show);
        }
        $cacheService->save($cacheKey, json_encode($shows));
        $repeat = true;
    } else {
        $repeat = false;
    } 

    return $content;
}

