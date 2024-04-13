<?php

namespace Zevitagem\LaravelToolkit\Models\Config;

use Zevitagem\LaravelToolkit\Models\AbstractModel;

class DataAddressConfig extends AbstractModel
{
    const PRIMARY_KEY = 'id';

    protected $table = 'slug_address';
    protected $fillable = [
        'slug_config_id',
        'latitude',
        'longitude',
        'phone',
        'cellphone',
        'country',
        'state',
        'city',
        'street',
        'number',
        'zipcode',
        'open_at',
        'close_at',
        'email',
        'observation',
    ];

    public function getSlug()
    {
        return $this->slug_config_id;
    }
    
    public function getStreet()
    {
        return $this->street;
    }
    
    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getCellphone()
    {
        return $this->cellphone;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getState()
    {
        return $this->state;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getZipcode()
    {
        return $this->zipcode;
    }

    public function getOpenAt()
    {
        return $this->open_at;
    }

    public function getCloseAt()
    {
        return $this->close_at;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getObservation()
    {
        return $this->observation;
    }
}
