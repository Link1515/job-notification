<?php

namespace Link1515\JobNotification\entities;

use Link1515\JobNotification\Traits\GetterSetterTrait;

/**
 * setter
 * @method self setId(string $id)
 * @method self setName(string $name)
 * @method self setIndustry(string $industry)
 * @method self setCompany(string $company)
 * @method self setCompanyLink(string $companyLink)
 * @method self setAddress(string $address)
 * @method self setLandmark(string $landmark)
 * @method self setLatitude(float $latitude)
 * @method self setLongitude(float $longitude)
 * @method self setLink(string $link)
 * @method self setPostDate(\DateTime $postDate)
 * @method self setDescription(string $description)
 * @method self setSalary(string $salary)
 */
class Job
{
    use GetterSetterTrait;
    public string $id;
    public string $name;
    public string $industry;
    public string $company;
    public string $companyLink;
    public string $address;
    public string $landmark = '';
    public float $latitude;
    public float $longitude;
    public string $link;
    public \DateTime $postDate;
    public string $description;
    public string $salary;

    public function __toString()
    {
        return <<<STRING
        id: {$this->id}
        name: {$this->name}
        industry: {$this->industry}
        company: {$this->company}
        companyLink: {$this->companyLink}
        address: {$this->address}
        landmark: {$this->landmark}
        latitude: {$this->latitude}
        longitude: {$this->longitude}
        link: {$this->link}
        postDate: {$this->postDate->format('Y-m-d')}
        description: {$this->description}
        salary: {$this->salary}\n
        STRING;
    }
}
