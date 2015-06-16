<?php

namespace Application\Models\Entities;

use Phalcon\Mvc\Model\Validator\Email as Email;

class Advertisement extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $source_name;

    /**
     *
     * @var integer
     */
    protected $source_id;

    /**
     *
     * @var string
     */
    protected $title;

    /**
     *
     * @var string
     */
    protected $district;

    /**
     *
     * @var string
     */
    protected $address;

    /**
     *
     * @var string
     */
    protected $phone;

    /**
     *
     * @var string
     */
    protected $email;

    /**
     *
     * @var string
     */
    protected $author;

    /**
     *
     * @var double
     */
    protected $area;

    /**
     *
     * @var double
     */
    protected $price_per_area;

    /**
     *
     * @var double
     */
    protected $price_per_meter;

    /**
     *
     * @var integer
     */
    protected $rooms;

    /**
     *
     * @var integer
     */
    protected $middleman;

    /**
     *
     * @var string
     */
    protected $added;

    /**
     *
     * @var string
     */
    protected $updated;

    /**
     *
     * @var string
     */
    protected $url;

	/**
     *
     * @var string
     */
    protected $show_after;

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
     * Method to set the value of field source_name
     *
     * @param string $source_name
     * @return $this
     */
    public function setSourceName($source_name)
    {
        $this->source_name = $source_name;

        return $this;
    }

    /**
     * Method to set the value of field source_id
     *
     * @param integer $source_id
     * @return $this
     */
    public function setSourceId($source_id)
    {
        $this->source_id = $source_id;

        return $this;
    }

    /**
     * Method to set the value of field title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Method to set the value of field district
     *
     * @param string $district
     * @return $this
     */
    public function setDistrict($district)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Method to set the value of field address
     *
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Method to set the value of field phone
     *
     * @param string $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Method to set the value of field email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Method to set the value of field author
     *
     * @param string $author
     * @return $this
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Method to set the value of field area
     *
     * @param double $area
     * @return $this
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Method to set the value of field price_per_area
     *
     * @param double $price_per_area
     * @return $this
     */
    public function setPricePerArea($price_per_area)
    {
        $this->price_per_area = $price_per_area;

        return $this;
    }

    /**
     * Method to set the value of field price_per_meter
     *
     * @param double $price_per_meter
     * @return $this
     */
    public function setPricePerMeter($price_per_meter)
    {
        $this->price_per_meter = $price_per_meter;

        return $this;
    }

    /**
     * Method to set the value of field rooms
     *
     * @param integer $rooms
     * @return $this
     */
    public function setRooms($rooms)
    {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * Method to set the value of field middleman
     *
     * @param integer $middleman
     * @return $this
     */
    public function setMiddleman($middleman)
    {
        $this->middleman = intval($middleman);

        return $this;
    }

    /**
     * Method to set the value of field added
     *
     * @param string $added
     * @return $this
     */
    public function setAdded($added)
    {
        $this->added = $added;

        return $this;
    }

    /**
     * Method to set the value of field updated
     *
     * @param string $updated
     * @return $this
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Method to set the value of field url
     *
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Method to set the value of field url
     *
     * @param string $url
     * @return $this
     */
    public function seShowAfter($show_after)
    {
        $this->show_after = $show_after;

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
     * Returns the value of field source_name
     *
     * @return string
     */
    public function getSourceName()
    {
        return $this->source_name;
    }

    /**
     * Returns the value of field source_id
     *
     * @return integer
     */
    public function getSourceId()
    {
        return $this->source_id;
    }

    /**
     * Returns the value of field title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the value of field district
     *
     * @return string
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Returns the value of field address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Returns the value of field phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Returns the value of field email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns the value of field author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Returns the value of field area
     *
     * @return double
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Returns the value of field price_per_area
     *
     * @return double
     */
    public function getPricePerArea()
    {
        return $this->price_per_area;
    }

    /**
     * Returns the value of field price_per_meter
     *
     * @return double
     */
    public function getPricePerMeter()
    {
        return $this->price_per_meter;
    }

    /**
     * Returns the value of field rooms
     *
     * @return integer
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * Returns the value of field middleman
     *
     * @return integer
     */
    public function getMiddleman()
    {
        return $this->middleman;
    }

    /**
     * Returns the value of field added
     *
     * @return string
     */
    public function getAdded()
    {
        return $this->added;
    }

    /**
     * Returns the value of field updated
     *
     * @return string
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Returns the value of field url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
	
    /**
     * Returns the value of field url
     *
     * @return string
     */
    public function getShowAfter()
    {
        return $this->show_after;
    }
	
	public function beforeValidation() {
		
		if(!$this->updated) {
			$this->updated = $this->added;
		}
		
	}
	
	public function initialize() {
		$this->skipAttributesOnCreate(['show_after']);
	}

    /**
     * Validations and business logic
     */
    public function validation()
    {
		if($this->email !== null) {
			$this->validate(
				new Email(
					array(
						'field'    => 'email',
						'required' => true,
					)
				)
			);
			if ($this->validationHasFailed() == true) {
				return false;
			}
		}
    }

    public function getSource()
    {
        return 'advertisement';
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
            'source_name' => 'source_name', 
            'source_id' => 'source_id', 
            'title' => 'title', 
            'district' => 'district', 
            'address' => 'address', 
            'phone' => 'phone', 
            'email' => 'email', 
            'author' => 'author', 
            'area' => 'area', 
            'price_per_area' => 'price_per_area', 
            'price_per_meter' => 'price_per_meter', 
            'rooms' => 'rooms', 
            'middleman' => 'middleman', 
            'added' => 'added', 
            'updated' => 'updated', 
            'url' => 'url',
            'show_after' => 'show_after'
        );
    }

}
