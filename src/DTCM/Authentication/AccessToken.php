<?php
namespace DTCM\Authentication;

/**
 * Class AccessToken
 *
 * @package DTCM
 */
class AccessToken
{
    /**
     * The access token value.
     *
     * @var string
     */
    protected $value = '';

    /**
     * The access token type.
     *
     * @var string
     */
    protected $type = '';

    /**
     * The access token scope.
     *
     * @var string
     */
    protected $scope = '';

    /**
     * Date when token expires.
     *
     * @var \DateTime|null
     */
    protected $expiresAt;

    /**
     * Create a new access token entity.
     *
     * @param string $accessToken
     * @param int    $expiresAt
     */
    public function __construct($accessToken, $expiresAt = 0, $scope, $type)
    {
        $this->value = $accessToken;
        if ($expiresAt) {
            $this->setExpiresAtFromTimeStamp($expiresAt);
        }
        $this->scope = $scope;
        $this->type = $type;
    }

    /**
     * Getter for expiresAt.
     *
     * @return \DateTime|null
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Determines whether or not this is an app access token.
     *
     * @return bool
     */
    public function isAppAccessToken()
    {
        return strpos($this->value, '|') !== false;
    }

    /**
     * Checks the expiration of the access token.
     *
     * @return boolean|null
     */
    public function isExpired()
    {
        if ($this->getExpiresAt() instanceof \DateTime) {
            return $this->getExpiresAt()->getTimestamp() < time();
        }

        if ($this->isAppAccessToken()) {
            return false;
        }

        return null;
    }

    /**
     * Returns the access token as a string.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }


    /**
     * Returns the token scope as a string.
     *
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Returns the token scope as a string.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the access token as a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }

    /**
     * Setter for expires_at.
     *
     * @param int $timeStamp
     */
    protected function setExpiresAtFromTimeStamp($timeStamp)
    {
        $dt = new \DateTime();
        $dt->setTimestamp($timeStamp);
        $this->expiresAt = $dt;
    }
}
