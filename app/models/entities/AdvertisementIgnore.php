<?php

namespace Application\Models\Entities;

class AdvertisementIgnore extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $advertisement_id;

    /**
     *
     * @var string
     */
    protected $timeout;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field advertisement_id
     *
     * @param integer $advertisement_id
     * @return $this
     */
    public function setAdvertisementId($advertisement_id)
    {
        $this->advertisement_id = $advertisement_id;

        return $this;
    }

    /**
     * Method to set the value of field timeout
     *
     * @param string $timeout
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field advertisement_id
     *
     * @return integer
     */
    public function getAdvertisementId()
    {
        return $this->advertisement_id;
    }

    /**
     * Returns the value of field timeout
     *
     * @return string
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    public function getSource()
    {
        return 'advertisement_ignore';
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'advertisement_id' => 'advertisement_id', 
            'timeout' => 'timeout'
        );
    }

}
