<?php
/**
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * prints airtime stream url for given instance and type
 *
 * @param array $params
 * @param Smarty_Internal_Template $template
 * @return string
 */
function smarty_function_airtime_prev_track(array $params, Smarty_Internal_Template $template)
{
    $instanceName = empty($params['instanceName']) ? null : $params['instanceName'];
    $container = \Zend_Registry::get('container');
    $airtimeService = $container->get('newscoop_airtime_plugin.airtime_service');

    $liveInfo = $airtimeService->getLiveInfo($instanceName);
                                                                                                         
    // TODO: solve issue with defaulting to http protocol here                                           
    print $liveInfo['previous']['name']; 
}
