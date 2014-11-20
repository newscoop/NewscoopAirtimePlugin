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
function smarty_block_list_airtime_track_history(array $params, $content, &$smarty, &$repeat)
{
    $instanceName = empty($params['instanceName']) ? null : $params['instanceName'];
    $showInstanceId = empty($params['showInstanceId']) ? null : $params['showInstanceId'];
    $start = empty($params['start']) ? null : $params['start'];
    $end = empty($params['end']) ? null : $params['end'];

    $container = \Zend_Registry::get('container');
    $cacheService = $container->get('newscoop.cache');
    $airtimeService = $container->get('newscoop_airtime_plugin.airtime_service');
    $cacheKey = $cacheService->getCacheKey("airtime_track_history_" . $instanceName . "_" . $start . $end . "_" . $showInstanceId);

    if (!isset($content)) {
        $results = $airtimeService->getTrackHistory($instanceName, $showInstanceId, $start, $end);
        $cacheService->save($cacheKey, json_encode($results));
    }

    $tracks = json_decode($cacheService->fetch($cacheKey), true);

    if (!empty($tracks)) {
        // load the current record
        $track = array_shift($tracks);
        if (is_array($track)) {
            $smarty->assign('track', $track);
        }
        $cacheService->save($cacheKey, json_encode($tracks));
        $repeat = true;
    } else {
        $repeat = false;
    } 

    return $content;
}

