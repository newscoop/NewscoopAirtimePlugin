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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Newscoop\AirtimePluginBundle\Entity\AirtimeInstance;

class AdminController extends Controller
{
    /**
     * @Route("/admin/airtime")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->container->get('em');
        $preferencesService = $this->container->get('system_preferences_service');
        $airtimeService = $this->container->get('newscoop_airtime_plugin.airtime_service');
        $instances = $em->getRepository('Newscoop\AirtimePluginBundle\Entity\AirtimeInstance')->findAll();
        $defaultInstanceId = $preferencesService->DefaultAirtimeInstanceId;
        $syncShowsPref = $preferencesService->SyncAirtimeShows;
        $trackPlaybackPref = $preferencesService->AirtimeTrackPlayback;
        $airtimeBackDate = $preferencesService->AirtimeBackDate;
        $airtimeForwardDate = $preferencesService->AirtimeForwardDate;

        if ($request->isMethod('POST')) {
            // add a new instance
            try {
                $instance = new AirtimeInstance();
                $instance
                    ->setName($request->request->get('name'))
                    ->setUrl($request->request->get('url'))
                    ->setApikey($request->request->get('apikey'));

                $em->persist($instance);
                $em->flush();
                $status = true;
                $message = "Success";
            } catch (\Exception $e) {
                $status = false;
                $message = $e->getMessage();
                error_log($message);
            }
            return new JsonResponse(array('status' => $status, 'message' => $message, 'instanceId' => $instance->getId()));
        }

        // load all show
        $allShows = array();
        foreach ($instances as $instance) {
            $instanceName = $instance->getName();
            $shows = $airtimeService->getShows($instanceName);
            if ($shows) {
                foreach ($shows as $show) {
                    $show['airtime_instance'] = $instanceName;
                    $allShows[] = $show;
                }
            }
        }

        return array(
            'instances' => $instances,
            'default_instance_id' => $defaultInstanceId,
            'sync_shows_pref' => $syncShowsPref,
            'track_playback_pref' => $trackPlaybackPref,
            'airtime_back_date' => $airtimeBackDate,
            'airtime_forward_date' => $airtimeForwardDate,
            'shows' => $allShows
        );
    }

    /**
     * @Route("/admin/airtime/update-schedule-prefs")
     */
    public function updateSchedulePrefsAction(Request $request)
    {
        $backPref = $request->request->get('back_pref');
        $forwardPref = $request->request->get('forward_pref');
        $preferencesService = $this->container->get('system_preferences_service');
        $preferencesService->set('AirtimeBackDate', $backPref);
        $preferencesService->set('AirtimeForwardDate', $forwardPref);
        return new JsonResponse(array('status' => true, 'message' => "Updated Airtime Schedule Prefs"));
    }

    /**
     * @Route("/admin/airtime/update-sync-shows")
     */
    public function updateSyncShowsAction(Request $request)
    {
        $value = $request->request->get('value');
        $preferencesService = $this->container->get('system_preferences_service');
        $preferencesService->set('SyncAirtimeShows', $value);
        return new JsonResponse(array('status' => true, 'message' => "Updated Airtime Show Sync"));
    }

    /**
     * @Route("/admin/airtime/update-track-playback")
     */
    public function updateTrackPlaybackAction(Request $request)
    {
        $value = $request->request->get('value');
        $preferencesService = $this->container->get('system_preferences_service');
        $preferencesService->set('AirtimeTrackPlayback', $value);
        return new JsonResponse(array('status' => true, 'message' => "Updated Airtime Track Playback"));
    }

    /**
     * @Route("/admin/airtime/update-default-instance")
     */
    public function updateDefaultInstanceAction(Request $request)
    {
        $instanceId = $request->request->get('instance_id');
        $em = $this->container->get('em');
        $preferencesService = $this->container->get('system_preferences_service');
        $instance = $em->getRepository('Newscoop\AirtimePluginBundle\Entity\AirtimeInstance')
            ->findOneById($instanceId);
        if ($instance) {
            $preferencesService->set('DefaultAirtimeInstanceId', $instanceId);
            return new JsonResponse(array('status' => true, 'message' => "Updated default Airtime instance"));
        }

        return new JsonResponse(array('status' => false, 'message' => "Instance id " . $instanceId . " does not exist"));
    }

    /**
     * @Route("/admin/airtime/remove/{id}")
     */
    public function removeAction(Request $request, $id)
    {
        $em = $this->container->get('em');
        $instance = $em->getRepository('Newscoop\AirtimePluginBundle\Entity\AirtimeInstance')
            ->findOneById($id);

        try {
            if ($instance) {
                $em->remove($instance);
                $em->flush();
                $status = true;
                $message = "Airtime instance removed successfully";
            } 
        } catch (\Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }

        return new JsonResponse(array('status' => $status, 'message' => $message));
    }
}
