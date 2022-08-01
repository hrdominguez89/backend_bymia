<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactUsRepository")
 * @ORM\Table("mia_contact_us")
 */
class ContactUs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone_main", type="string", length=255, nullable=true)
     */
    private $phoneMain;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone_other", type="string", length=255, nullable=true)
     */
    private $phoneOther;

    /**
     * @var string|null
     *
     * @ORM\Column(name="url", type="string", nullable=true)
     */
    private $url;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): ContactUs
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     * @return $this
     */
    public function setAddress(?string $address): ContactUs
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return $this
     */
    public function setEmail(?string $email): ContactUs
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoneMain(): ?string
    {
        return $this->phoneMain;
    }

    /**
     * @param string|null $phoneMain
     * @return $this
     */
    public function setPhoneMain(?string $phoneMain): ContactUs
    {
        $this->phoneMain = $phoneMain;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoneOther(): ?string
    {
        return $this->phoneOther;
    }

    /**
     * @param string|null $phoneOther
     * @return $this
     */
    public function setPhoneOther(?string $phoneOther): ContactUs
    {
        $this->phoneOther = $phoneOther;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     * @return $this
     */
    public function setUrl(?string $url): ContactUs
    {
        $this->url = $url;

        return $this;
    }

}
