<?php
/**
 * @package Newscoop\AirtimePluginBundle
 * @author Mark Lewis <mark.lewis@sourcefabric.org>
 * @copyright 2014 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Newscoop\AirtimePluginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Route("/airtime")
 */
class AirtimeController extends Controller
{

    // TODO: allow theme overrides for all tpl files
    //       add id params to all endpoints

    /**
     * @Route("/airtime/show_tracks/{instanceId}", defaults={"instanceId" = 0}))
     */
    public function showTracksAction($instanceId, Request $request)
    {
        $airtimeService = $this->container->get('newscoop_airtime_plugin.airtime_service');
        $templatesService = $this->container->get('newscoop.templates.service');
        $templateFile = $this->getTemplateFile('airtime_show_tracks.tpl');
        $instanceName = $this->_getParam('instance_name', $request);

        if ($instanceId > 0) {
            $tracks = $airtimeService->getShowTracks($instanceName, $instanceId);
        } else {
            $tracks = array();
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'text/html');
        $response->setContent($templatesService->fetchTemplate(
            $templateFile, 
            array('tracks' => $tracks)
        ));
        return $response;

    }

    /**
     * @Route("/airtime/file/{fileId}")
     */
    public function fileAction($fileId, Request $request)
    {
        $airtimeService = $this->container->get('newscoop_airtime_plugin.airtime_service');
        $instanceName = $this->_getParam('instance_name', $request);
        $file = $airtimeService->getFile($instanceName, $fileId);
        $size = mb_strlen($file, '8bit');

        $response = new Response();
        $response->headers->set('Cache-Control', 'private');
        // TODO: get content type from api
        $response->headers->set('Content-Type', 'audio/mp3');
        $response->headers->set('Content-Disposition', 'inline; filename="audio.mp3"');
        $response->headers->set('Content-Length', $size);
        $response->sendHeaders();
        $response->setContent($file);

        return $response;
    }


    /**
     * @Route("/airtime/shows/{showId}", defaults={"showId" = 0})
     */
    public function showsAction($showId, Request $request)
    {
        $templatesService = $this->container->get('newscoop.templates.service');
        $preferencesService = $this->container->get('system_preferences_service');
        $airtimeService = $this->container->get('newscoop_airtime_plugin.airtime_service');
        $syncShowsPref = $preferencesService->SyncAirtimeShows;
        $response = new Response();
        $response->headers->set('Content-Type', 'text/html');

        $instanceName = $this->_getParam('instance_name', $request);
        $start = $this->_getParam('start', $request);
        $end = $this->_getParam('end', $request);
        $shows = array();


        if ($showId == 0) {
            if ($syncShowsPref == "ON") {
                $shows = $airtimeService->getShows($instanceName);
            }
            $templateFile = $this->getTemplateFile('airtime_shows.tpl');
            $response->setContent($templatesService->fetchTemplate(
                $templateFile,
                array('shows' => $shows)
            ));
        } else {
            $showInstances = array();
            $loadedInstances = array();
            $show = $airtimeService->getShows($instanceName, $showId);
            $showInstances = $airtimeService->getShowSchedule($instanceName, $showId, $start, $end);

            // loop thourgh show isntances and get tracks
            foreach ($showInstances as $showInstance) {
                $instanceId = $showInstance['instance_id'];
                $instanceTracks = $airtimeService->getShowTracks($instanceName, $instanceId);
                $showInstance['tracks'] = $instanceTracks; 
                $loadedInstances[] = $showInstance;
            }

            $templateFile = $this->getTemplateFile('airtime_show.tpl');
            $response->setContent($templatesService->fetchTemplate(
                $templateFile,
                array(
                    'show' => $show,
                    'showInstances' => $loadedInstances
                )
            ));
        }
        return $response;
    }

    /**
     * @Route("/airtime/schedule")
     */
    public function scheduleAction(Request $request)
    {
        $airtimeService = $this->container->get('newscoop_airtime_plugin.airtime_service');
        $templatesService = $this->container->get('newscoop.templates.service');
        $templateFile = $this->getTemplateFile('airtime_schedule.tpl');
        $instanceName = $this->_getParam('instance_name', $request); 

        $schedule = $airtimeService->getWeekInfo($instanceName);
        $response = new Response();
        $response->headers->set('Content-Type', 'text/html');
        $response->setContent($templatesService->fetchTemplate(
            $templateFile,
            array('schedule' => $schedule)
        ));
        return $response;

    }

    /**
     * @Route("/airtime/show_history")
     */
    public function showHistoryAction(Request $request)
    {
        $airtimeService = $this->container->get('newscoop_airtime_plugin.airtime_service');
        $templatesService = $this->container->get('newscoop.templates.service');
        $templateFile = $this->getTemplateFile('airtime_show_history.tpl');
        $instanceName = $this->_getParam('instance_name', $request); 
        $start = $this->_getParam('start', $request);
        $end = $this->_getParam('end', $request);

        $showHistory = $airtimeService->getShowHistory($instanceName, $start, $end);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/html');
        $response->setContent($templatesService->fetchTemplate(
            $templateFile,
            array('showHistory' => $showHistory)
        ));
        return $response;

    }

    /**
     * @Route("/airtime/track_history")
     */
    public function trackHistoryAction(Request $request)
    {
        $airtimeService = $this->container->get('newscoop_airtime_plugin.airtime_service');
        $templatesService = $this->container->get('newscoop.templates.service');
        $templateFile = $this->getTemplateFile('airtime_track_history.tpl');
        $instanceName = $this->_getParam('instance_name', $request); 
        $showInstanceId = $this->_getParam('show_instance_id', $request);
        $start = $this->_getParam('start', $request);
        $end = $this->_getParam('end', $request);

        $trackHistory = $airtimeService->getTrackHistory($instanceName, $showInstanceId, $start, $end);
        $response = new Response();
        $response->headers->set('Content-Type', 'text/html');
        $response->setContent($templatesService->fetchTemplate(
            $templateFile,
            array('trackHistory' => $trackHistory)
        ));
        return $response;

    }

    /**
     * @Route("/airtime/live")
     */
    public function liveAction(Request $request)
    {
        $airtimeService = $this->container->get('newscoop_airtime_plugin.airtime_service');
        $templatesService = $this->container->get('newscoop.templates.service');
        $templateFile = $this->getTemplateFile('airtime_live.tpl');

        $liveInfo = $airtimeService->getLiveInfo($instanceName);
        $response = new Response();
        $response->headers->set('Content-Type', 'text/html');
        $response->setContent($templatesService->fetchTemplate(
            $templateFile,
            array('liveInfo' => $liveInfo)
        ));
        return $response;

    }

    private function getTemplateFile($fileName) {
        $templatesService = $this->container->get('newscoop.templates.service');
        $smarty = $templatesService->getSmarty();
        $smarty = $templatesService->getSmarty();
        $templateDir = array_shift($smarty->getTemplateDir());
        $templateFile = "airtime/" . $fileName;
        if (!file_exists($templateFile)) {
            $templateFile = __DIR__ . "/../Resources/views/Airtime/" . $fileName;
        }

        return $templateFile;
    }

    public function _getParam($param, Request $request)
    {
        if ($request !== null) {
            if ($request->request->get($param)) {
                return $request->request->get($param);
            }
            if ($request->query->get($param)) {
                return $request->query->get($param);
            }
        }

        return null;
    }
}

