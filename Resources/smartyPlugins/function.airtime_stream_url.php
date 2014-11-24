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
function smarty_function_airtime_stream_url(array $params, Smarty_Internal_Template $template)
{
    $instanceName = empty($params['instanceName']) ? null : $params['instanceName'];
    $type = empty($params['type']) ? 's1' : $params['type'];

    $container = \Zend_Registry::get('container');
    $airtimeService = $container->get('newscoop_airtime_plugin.airtime_service');

    $streamParameters = $airtimeService->getStreamParameters($instanceName);

    $host = $streamParameters['stream_params'][$type]['host'];
    $port = $streamParameters['stream_params'][$type]['port'];
    $mount = $streamParameters['stream_params'][$type]['mount'];

    // TODO: solve issue with defaulting to http protocol here
    print 'http://' . $host . ':' . $port . '/' . $mount;

}
