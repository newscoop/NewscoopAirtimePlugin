<?php
/**
 * @package Newscoop\AirtimePluginBundle
 * @author Mark Lewis <mark.lewis@sourcefabric.org>
 * @copyright 2014 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Newscoop\AirtimePluginBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Newscoop\EventDispatcher\Events\GenericEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Event lifecycle management
 */
class LifecycleSubscriber implements EventSubscriberInterface
{
    private $contrainer;

    private $em;
    
    protected $scheduler;

    protected $cronjobs;

    public function __construct(ContainerInterface $container)
    {
        $appDirectory = realpath(__DIR__.'/../../../../application/console');
        $this->container = $container;
        $this->em = $this->container->get('em');
        $this->scheduler = $this->container->get('newscoop.scheduler');
        $this->preferences = $this->container->get('system_preferences_service');
    }

    public function install(GenericEvent $event)
    {
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $tool->updateSchema($this->getClasses(), true);

        $this->preferences->set('AirtimeBackDate', '1D');
        $this->preferences->set('AirtimeForwardDate', '1D');
        $this->preferences->set('AirtimeTrackPlayback', 'ON');
        $this->preferences->set('SyncAirtimeShows', 'ON');

        // Generate proxies for entities
        $this->em->getProxyFactory()->generateProxyClasses($this->getClasses(), __DIR__ . '/../../../../library/Proxy');
        $this->addJobs();
    }

    public function update(GenericEvent $event)
    {
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $tool->updateSchema($this->getClasses(), true);

        // Generate proxies for entities
        $this->em->getProxyFactory()->generateProxyClasses($this->getClasses(), __DIR__ . '/../../../../library/Proxy');
    }

    public function remove(GenericEvent $event)
    {
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $tool->dropSchema($this->getClasses(), true);
    }

    public static function getSubscribedEvents()
    {
        return array(
            'plugin.install.newscoop_airtime_plugin_bundle' => array('install', 1),
            'plugin.update.newscoop_airtime_plugin_bundle' => array('update', 1),
            'plugin.remove.newscoop_airtime_plugin_bundle' => array('remove', 1),
        );
    }

    /**
     * Add plugin cron jobs
     */
    private function addJobs()
    {
    }

    /**
     * Remove plugin cron jobs
     */
    private function removeJobs()
    {
    }

    private function getClasses()
    {
        return array(
            $this->em->getClassMetadata('Newscoop\AirtimePluginBundle\Entity\AirtimeInstance'),
        );
    }
}
