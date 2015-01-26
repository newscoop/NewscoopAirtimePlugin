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
 * Airtime Service
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

    /** @var airtimeForwardDate */
    protected $airtimePlaybackPref;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->client = new \Buzz\Client\Curl();
        $this->browser = new \Buzz\Browser($this->client);
        $preferencesService = $this->container->get('system_preferences_service');
        $this->airtimePlaybackPref = $preferencesService->AirtimeTrackPlayback;
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
     *
     * @param string $instanceName
     * @return binary file
     */
    public function getFile($instanceName, $fileId = null) {
        if ($this->airtimePlaybackPref == "ON") {
            $instance = $this->getInstance($instanceName);
            $url = $instance->getUrl() . "/api/get-media";
            $apikey = $instance->getApikey();
            if ($fileId) {
                $url .= "/file/" . $fileId;
            }
            $url .= "/api_key/" . $apikey;

            try {
                $response =  $this->browser->post($url);
                $content = $response->getContent();
            } catch (\Exception $e) {
                error_log('ERROR: ' . $e->getMessage());
                $content = null;
            }
            return $content;
        } else {
            return false;
        }
    }

    /**
     * Returns track listing for a specific show instance
     *
     * @param string $instanceName
     * @return array json content
     */
    public function getShowTracks($instanceName, $showInstanceId = null) {
        $instance = $this->getInstance($instanceName);
        $url = $instance->getUrl() . "/api/show-tracks";
        if ($showInstanceId) {
            $url .= "/instance_id/" . $showInstanceId;
        }

        try {
            $response =  $this->browser->post($url);
            $tracks = json_decode($response->getContent(), true);
            if (isset($tracks['error'])) {
                $tracks = array();
            }
        } catch (\Exception $e) {
            error_log('ERROR: ' . $e->getMessage());
            $tracks = array();
        }
        return $tracks; 
    }

    /**
     * Returns scheduled instances for a specific show
     *
     * @param string $instanceName
     * @return array json content
     */
    public function getShowSchedule($instanceName, $showId = null, $start = null, $end = null) {
        $start = empty($start) ? $this->airtimeBackDate : $start;
        $end = empty($end) ? $this->airtimeForwardDate : $end;
        $instance = $this->getInstance($instanceName);
        $url = $instance->getUrl() . "/api/show-schedules/start/" . urlencode($start) . "/end/" . urlencode($end);
        if ($showId) {
            $url .= "/show_id/" . $showId;
        }

        try {
            $response =  $this->browser->get($url);
            $schedule = json_decode($response->getContent(), true);
            if (isset($schedule['error'])) {
                $schedule = array();
            }
        } catch (\Exception $e) {
            error_log('ERROR: ' . $e->getMessage());
            $schedule = array();
        }
        return $schedule; 
    }

    /**
     * Returns show meta data (no schedule, no track listings)
     *
     * @param string $instanceName
     * @return array json content
     */
    public function getShows($instanceName, $showId = null) {
        $instance = $this->getInstance($instanceName);
        $url = $instance->getUrl() . "/api/shows";
        if ($showId) {
            $url .= "/show_id/" . $showId;
        }
    
        try {
            $response =  $this->browser->get($url);
            $shows = json_decode($response->getContent(), true);
        } catch (\Exception $e) {
            error_log('ERROR: ' . $e->getMessage());
            $shows = array();
        }

        return $shows;
    }

    /**
     * Returns all track list history without show coorelation
     *
     * @param string $instanceName
     * @return array json content
     */
    public function getTrackHistory($instanceName, $showInstanceId = null, $start = null, $end = null) {
        $start = empty($start) ? $this->airtimeBackDate : $start;
        $end = empty($end) ? $this->airtimeForwardDate : $end;
        $instance = $this->getInstance($instanceName);
        $url = $instance->getUrl() . "/api/item-history-feed/start/" . urlencode($start) . "/end/" . urlencode($end);
        if ($showInstanceId) {
            $url .= "/instance_id/" . $showInstanceId;
        }
        
        try { 
            $response =  $this->browser->get($url);
            $history = json_decode($response->getContent(), true);
        } catch (\Exception $e) {
            error_log('ERROR: ' . $e->getMessage());
            $history = array();
        }
        
        return $history; 
    }

    /**
     * Returns scheduled instances without specific show correlation
     *
     * @param string $instanceName
     * @return array json content
     */
    public function getShowHistory($instanceName, $start = null, $end = null) {
        $start = empty($start) ? $this->airtimeBackDate : $start;
        $end = empty($end) ? $this->airtimeForwardDate : $end;
        $instance = $this->getInstance($instanceName);
        $url = $instance->getUrl() . "/api/show-history-feed/start/" . urlencode($start) . "/end/" . urlencode($end);
    
        try {
            $response =  $this->browser->get($url);
            $history = json_decode($response->getContent(), true);
        } catch (\Exception $e) {
            error_log('ERROR: ' . $e->getMessage());
            $history = array();
        }
        return $history; 

    }

    /**
     * Returns an airtime instances stream settings
     *
     * @param string $instanceName
     * @return array json content
     */
    public function getStreamParameters($instanceName) {
        $instance = $this->getInstance($instanceName);
        $apikey = $instance->getApikey();
        $url = $instance->getUrl() . "/api/get-stream-parameters/format/json/api_key/" . $apikey;

        try {
            $response =  $this->browser->get($url);
            $parameters = json_decode($response->getContent(), true);
        } catch (\Exception $e) {
            error_log('ERROR: ' . $e->getMessage());
            $parameters = array();
        }
        return $parameters;

    }

    /**
     * Returns schedule organized weekly for the current 2 week period
     *
     * @param string $instanceName
     * @return array json content
     */
    public function getWeekInfo($instanceName) {
        $instance = $this->getInstance($instanceName);
        $apikey = $instance->getApikey();
        $url = $instance->getUrl() . "/api/week-info/format/json/api_key/" . $apikey;

        try {
            $response =  $this->browser->get($url);
            // we need to remove the stupid airtime api version line
            $content = json_decode($response->getContent(), true);
            unset($content['AIRTIME_API_VERSION']);
        } catch (\Exception $e) {
            error_log('ERROR: ' . $e->getMessage());
            $content = array();
        }
        return $content;
    }

    /**
     * Returns live show info for a given instanceName
     *
     * @param string $instanceName
     * @return array json content
     */
    public function getLiveInfo($instanceName) {
        $instance = $this->getInstance($instanceName);
        $apikey = $instance->getApikey();
        $url = $instance->getUrl() . "/api/live-info/format/json/api_key/" . $apikey;

        try {
            $response =  $this->browser->get($url);
            $info = json_decode($response->getContent(), true);
        } catch (\Exception $e) {
            error_log('ERROR: ' . $e->getMessage());
            $info = array();
        }
        return $info; 

    }

    /**
     * Returns instance object for a given instanceName
     *
     * @param string $instanceName
     * @return  Newscoop\AirtimePluginBundle\Entity\AirtimeInstance
     */
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
     * @return Newscoop\AirtimePluginBundle\Repository
     */
    private function getRepository()
    {
        $em = $this->container->get('em');

        return $em->getRepository('Newscoop\AirtimePluginBundle\Entity\AirtimeInstance');

    }
}
