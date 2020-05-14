<?php

namespace Smile\Bundle\ProductReviewBundle\Manager;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Exception;
use Oro\Bundle\ConfigBundle\Config\ConfigManager;
use Psr\Http\Message\MessageInterface;
use Smile\Bundle\ProductReviewBundle\DependencyInjection\Configuration;

/**
 * Class GoogleRecaptchaManager
 */
class GoogleRecaptchaManager
{
    protected const
        BASE_GOOGLE_RECAPTCHA_VERIFY_URL =  'https://www.google.com/recaptcha/api/siteverify',
        DELIMITER_URL_QUERY = '?',
        SUCCESS_KEY = 'success'
    ;

    /**
     * @var HttpMethodsClient
     */
    protected $httpClient;

    /**
     * @var ConfigManager
     */
    protected $configManager;

    /**
     * GoogleRecaptchaManager constructor.
     *
     * @param HttpMethodsClient $httpClient
     * @param ConfigManager $configManager
     */
    public function __construct(
        HttpMethodsClient $httpClient,
        ConfigManager $configManager
    ) {
        $this->httpClient = $httpClient;
        $this->configManager = $configManager;
    }

    /**
     * @return bool
     */
    public function isGoogleRecaptchaEnabled(): bool
    {
        return $this->getConfigKeyByName(Configuration::PARAM_NAME_ENABLED_GOOGLE_RECAPTCHA) ?: false;
    }

    /**
     * @return string|null
     */
    public function getGoogleRecaptchaSiteKey(): ?string
    {
        return $this->getConfigKeyByName(Configuration::PARAM_NAME_GOOGLE_RECAPTCHA_SITE_KEY) ?: null;
    }

    /**
     * @return string|null
     */
    public function getGoogleRecaptchaSecretKey(): ?string
    {
        return $this->getConfigKeyByName(Configuration::PARAM_NAME_GOOGLE_RECAPTCHA_SECRET_KEY) ?: null;
    }

    /**
     * @return bool
     */
    public function isGoogleRecaptchaSettingsValid(): bool
    {
        return $this->isGoogleRecaptchaEnabled()
            && $this->getGoogleRecaptchaSiteKey()
            && $this->getGoogleRecaptchaSecretKey()
        ;
    }

    /**
     * @param string $recaptchaResponse
     *
     * @return bool
     */
    public function isValidGoogleRecaptcha(string $recaptchaResponse): bool
    {
        $response = $this->getGoogleRecaptchaResponse($recaptchaResponse);

        return $this->isSuccessGoogleRecaptchaResponse($response);
    }

    /**
     * @param string $paramName
     *
     * @return string|null
     */
    protected function getConfigKeyByName(string $paramName): ?string
    {
        return $this->configManager
            ->get(Configuration::getConfigKeyByName($paramName));
    }

    /**
     * @param string $recaptchaResponse
     *
     * @return array
     */
    protected function getGoogleRecaptchaResponse(string $recaptchaResponse): array
    {
        try {
            $response = $this->httpClient->get($this->createGoogleRecaptchaVerifyUrl($recaptchaResponse));
        } catch (Exception $exception) {
            return [];
        }

        return $this->getResponseContent($response);
    }

    /**
     * @param string $recaptchaResponse
     *
     * @return string
     */
    protected function createGoogleRecaptchaVerifyUrl(string $recaptchaResponse): string
    {
        $data = $this->createGoogleRecaptchaData($recaptchaResponse);

        return self::BASE_GOOGLE_RECAPTCHA_VERIFY_URL . self::DELIMITER_URL_QUERY . http_build_query($data);
    }

    /**
     * @param string $recaptchaResponse
     *
     * @return array
     */
    protected function createGoogleRecaptchaData(string $recaptchaResponse): array
    {
        return [
            'secret' => $this->getGoogleRecaptchaSecretKey(),
            'response' => $recaptchaResponse,
        ];
    }

    /**
     * @param MessageInterface $rawResponse
     *
     * @return array
     */
    protected function getResponseContent(MessageInterface $rawResponse): array
    {
        $content = $rawResponse->getBody();

        if (!$content) {
            return [];
        }

        return json_decode($content, true);
    }

    /**
     * @param array $response
     *
     * @return bool
     */
    protected function isSuccessGoogleRecaptchaResponse(array $response): bool
    {
        if (!array_key_exists(self::SUCCESS_KEY, $response)) {
            return false;
        }

        return $response[self::SUCCESS_KEY];
    }
}
