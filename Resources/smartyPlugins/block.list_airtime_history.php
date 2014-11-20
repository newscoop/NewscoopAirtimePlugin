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
function smarty_block_list_airtime_history(array $params, $content, &$smarty, &$repeat)
{
    if (empty($params['instanceName'])) {
        return;
    }

    if (empty($params['maxResults'])) {
        $maxResults = "";
    } else {
        $maxResults = "&maxResults=" . $params['maxResults'];
    }

    $container = \Zend_Registry::get('container');
    $cacheService = $container->get('newscoop.cache');
    $airtimeService = $container->get('newscoop_airtime_plugin.airtime_service');

    $cacheKey = $cacheService->getCacheKey("airtime_history_" . $params['instanceName'] . "_" . $maxResults);

    if (!isset($content)) {

        $results = $airtimeService->getPlayoutHistoryByName($params['instanceName']);
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

