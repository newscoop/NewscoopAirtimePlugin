<?php

/**
 * @package Newscoop\AirtimePluginBundle
 * @author Mark Lewis <mark.lewis@sourcefabric.org>
 */

namespace Newscoop\AirtimePluginBundle\Services;

use Doctrine\ORM\EntityManager;
use Newscoop\AirtimePluginBundle\Entity\AirtimeInstance;
use Symfony\Component\DependencyInjection\Container;

/**
 * Instagram Service
 */
class AirtimeService
{
    /** @var Container */
    protected $container;

    /** Buzz\Client\Curl */
    protected $client;

    /** Buzz\Browser */
    protected $browser;

    /** @var airtimeBackDate */
    protected $airtimeBackDate;

    /** @var airtimeForwardDate */
    protected $airtimeForwardDate;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->client = new \Buzz\Client\Curl();
        $this->browser = new \Buzz\Browser($this->client);
        $preferencesService = $this->container->get('system_preferences_service');
        $now = new \DateTime();
        $backDate = clone $now;
        $backDate->sub(new \DateInterval('P'.$preferencesService->AirtimeBackDate));
        $this->airtimeBackDate = $backDate->format('Y-m-d H:i:s');

        $forwardDate = clone $now;
        $forwardDate->add(new \DateInterval('P'.$preferencesService->AirtimeForwardDate));
        $this->airtimeForwardDate = $forwardDate->format('Y-m-d H:i:s');
    }

    /**
     * Returns an airtime audio file for inline playback
     */
    public function getFile($instanceName, $fileId = null) {
        $instance = $this->getInstance($instanceName);
        $url = $instance->getUrl() . "/api/get-media";
        $apikey = $instance->getApikey();
        if ($fileId) {
            $url .= "/file/" . $fileId;
        }
        $url .= "/api_key/" . $apikey;

        $response =  $this->browser->post($url);
        return $response->getContent();
    }

    /**
     * Returns track listing for a specific show instance
     */
    public function getShowTracks($instanceName, $showInstanceId = null) {
        $instance = $this->getInstance($instanceName);
        $url = $instance->getUrl() . "/api/show-tracks";
        if ($showInstanceId) {
            $url .= "/instance_id/" . $showInstanceId;
        }

        $response =  $this->browser->post($url);
        return json_decode($response->getContent(), true);
    }

    /**
     * Returns scheduled instances for a specific show
     */
    public function getShowSchedule($instanceName, $showId = null, $start = null, $end = null) {
        $start = empty($start) ? $this->airtimeBackDate : $start;
        $end = empty($end) ? $this->airtimeForwardDate : $end;
        $instance = $this->getInstance($instanceName);
        $url = $instance->getUrl() . "/api/show-schedules/start/" . urlencode($start) . "/end/" . urlencode($end);
        if ($showId) {
            $url .= "/show_id/" . $showId;
        }
        $response =  $this->browser->get($url);
        return json_decode($response->getContent(), true);
    }

    /**
     * Returns show meta data (no schedule, no track listings)
     */
    public function getShows($instanceName, $showId = null) {
        $instance = $this->getInstance($instanceName);
        $url = $instance->getUrl() . "/api/shows";
        if ($showId) {
            $url .= "/show_id/" . $showId;
        }
        $response =  $this->browser->get($url);

        // airtime api returns objects for single results and arrays for multiple
        if ($showId) {
            $json = "[" . $response->getContent() . "]"; 
        } else {
            $json = $response->getContent();
        }
        return json_decode($json, true);
    }

    /**
     * Returns all track list history without show coorelation
     */
    public function getTrackHistory($instanceName, $showInstanceId = null, $start = null, $end = null) {
        $start = empty($start) ? $this->airtimeBackDate : $start;
        $end = empty($end) ? $this->airtimeForwardDate : $end;
        $instance = $this->getInstance($instanceName);
        $url = $instance->getUrl() . "/api/item-history-feed/start/" . urlencode($start) . "/end/" . urlencode($end);
        if ($showInstanceId) {
            $url .= "/instance_id/" . $showInstanceId;
        }
    
        error_log($url);
        $response =  $this->browser->get($url);
        return json_decode($response->getContent(), true);
    }

    /**
     * Returns scheduled instances without specific show correlation
     */
    public function getShowHistory($instanceName, $start = null, $end = null) {
        $start = empty($start) ? $this->airtimeBackDate : $start;
        $end = empty($end) ? $this->airtimeForwardDate : $end;
        $instance = $this->getInstance($instanceName);
        $url = $instance->getUrl() . "/api/show-history-feed/start/" . urlencode($start) . "/end/" . urlencode($end);

        $response =  $this->browser->get($url);
        return json_decode($response->getContent(), true);

    }

    /**
     * Returns an airtime instances stream settings
     */
    public function getStreamParameters($instanceName) {
        $instance = $this->getInstance($instanceName);
        $apikey = $instance->getApikey();
        $url = $instance->getUrl() . "/api/get-stream-parameters/format/json/api_key/" . $apikey;

        $response =  $this->browser->get($url);
        $content = $response->getContent();

        return json_decode($content, true);

    }

    /**
     * Returns schedule organized weekly for the current 2 week period
     */
    public function getWeekInfo($instanceName) {
        $instance = $this->getInstance($instanceName);
        $apikey = $instance->getApikey();
        $url = $instance->getUrl() . "/api/week-info/format/json/api_key/" . $apikey;

        $response =  $this->browser->get($url);
        // we need to remove the stupid airtime api version line
        $contentObj = json_decode($response->getContent(), true);
        unset($contentObj['AIRTIME_API_VERSION']);
        return $contentObj;
    }

    public function getLiveInfo($instanceName) {
        $instance = $this->getInstance($instanceName);
        $apikey = $instance->getApikey();
        $url = $instance->getUrl() . "/api/live-info/format/json/api_key/" . $apikey;

        $response =  $this->browser->get($url);
        return json_decode($response->getContent(), true);

    }

    public function getInstance($instanceName) {
        try {
            if (isset($instanceName)) {
                $instance = $this->getRepository()->findOneBy(array('name' => $instanceName));
            } else {
                $preferencesService = $this->container->get('system_preferences_service'); 
                $instanceId = $preferencesService->DefaultAirtimeInstanceId;
                $instance = $this->getRepository()->findOneBy(array('id' => $instanceId));
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }

        return $instance;
    }

    /**
     * Get repository for announcments entity
     *
     * @return Newscoop\InstagramPluginBundle\Repository
     */
    private function getRepository()
    {
        $em = $this->container->get('em');

        return $em->getRepository('Newscoop\AirtimePluginBundle\Entity\AirtimeInstance');

    }
}
