<?php

namespace App\Entity;

use App\Entity\Model\PaymentMethod;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PayPalRepository")
 * @ORM\Table("mia_paypal")
 */
class PayPal extends PaymentMethod
{
    const NAME = 'PAYPAL';
    const APPROVED = 'APPROVED';
    const SAVED = 'SAVED';
    const CREATED = 'CREATED';
    const VOIDED = 'VOIDED';
    const COMPLETED = 'COMPLETED';

    /**
     * @var string|null
     *
     * @ORM\Column(name="client_id", type="string", length=255, nullable=true)
     */
    private $clientId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="client_secret", type="string", length=255, nullable=true)
     */
    private $clientSecret;

    /**
     * @var string|null
     *
     * @ORM\Column(name="client_id_sand_box", type="string", length=255, nullable=true)
     */
    private $clientIdSandBox;

    /**
     * @var string|null
     *
     * @ORM\Column(name="client_secret_sand_box", type="string", length=255, nullable=true)
     */
    private $clientSecretSandBox;

    public function __construct()
    {
        parent::__construct();

        $this->setName(self::NAME);
    }


    /**
     * @return string|null
     */
    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    /**
     * @param string|null $clientId
     * @return $this
     */
    public function setClientId(?string $clientId): PayPal
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getClientSecret(): ?string
    {
        return $this->clientSecret;
    }

    /**
     * @param string|null $clientSecret
     * @return $this
     */
    public function setClientSecret(?string $clientSecret): PayPal
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getClientIdSandBox(): ?string
    {
        return $this->clientIdSandBox;
    }

    /**
     * @param string|null $clientIdSandBox
     * @return $this
     */
    public function setClientIdSandBox(?string $clientIdSandBox): PayPal
    {
        $this->clientIdSandBox = $clientIdSandBox;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getClientSecretSandBox(): ?string
    {
        return $this->clientSecretSandBox;
    }

    /**
     * @param string|null $clientSecretSandBox
     * @return $this
     */
    public function setClientSecretSandBox(?string $clientSecretSandBox): PayPal
    {
        $this->clientSecretSandBox = $clientSecretSandBox;

        return $this;
    }
}
