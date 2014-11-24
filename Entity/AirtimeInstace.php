<?php
/**
 * @package Newscoop\AirtimePluginBundle
 * @author Mark Lewis <mark.lewis@sourcefabric.org>
 * @copyright 2014 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Newscoop\AirtimePluginBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * AirtimeInstance entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="plugin_airtime_instance")
 */
class AirtimeInstance
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="id")
     * @var string
     */
    protected $id;

    /**
     * @ORM\Column(type="text",  name="name")
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255, name="url")
     * @var string
     */
    protected $url;

    /**
     * @ORM\Column(type="text",  name="apikey")
     * @var string
     */
    protected $apikey;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     * @var datetime
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="boolean", name="is_active")
     * @var boolean
     */
    protected $isActive;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setIsActive(true);
    }

    /**
     * Gets the value of id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the value of id.
     *
     * @param int $id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the value of name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the value of name.
     *
     * @param string $name the name 
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the value of url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the value of url.
     *
     * @param int $url the url 
     *
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Gets the value of apikey.
     *
     * @return string 
     */
    public function getApikey()
    {
        return $this->apikey;
    }

    /**
     * Sets the value of apikey.
     *
     * @param string $apikey the apikey 
     *
     * @return self
     */
    public function setApikey($apikey)
    {
        $this->apikey = $apikey;

        return $this;
    }

    /**
     * Gets the value of created_at.
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Sets the value of created_at.
     *
     * @param datetime $created_at the created  at
     *
     * @return self
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Gets the value of isActive.
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Sets the value of isActive.
     *
     * @param boolean $isActive the is  active
     *
     * @return self
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

}
