<?php

namespace NotificationChannels\FortySixElks;

use NotificationChannels\FortySixElks\Exceptions\CouldNotSendNotification;

class FortySixElksSMS extends FortySixElksMedia implements FortySixElksMediaInterface
{
    const ENDPOINT = 'https://api.46elks.com/a1/SMS';

    protected array $form_params;

    /**
     * FortySixElksSMS constructor.
     */
    public function __construct()
    {
        return parent::__construct();
    }

    /**
     * @return $this
     */
    public function send()
    {
        try {
            $response = $this->client->request('POST', self::ENDPOINT, [
                'form_params' => array_merge(
                    $this->form_params,
                    [
                        'from'          => $this->from,
                        'to'            => $this->phone_number,
                        'message'       => $this->getContent(),
                    ]
                )

            ]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $response = $e->getResponse();

            throw CouldNotSendNotification::serviceRespondedWithAnError(
                $response->getBody()->getContents(),
                $response->getStatusCode()
            );
        }

        return $this;
    }

    /**
     * @return array
     */
    public function prepareParams(string $key, mixed $value): FortySixElksSMS
    {
        $this->form_params[$key] = $value;

        return $this;
    }

    /**
     * @return void
     */
    public function flash(string $value = 'yes'): FortySixElksSMS
    {
        self::prepareParams(key: 'flash', value: $value);

        return $this;
    }

    /**
     * @param string $value
     *
     * @return FortySixElksSMS
     */
    public function dry(string $value = 'yes'): FortySixElksSMS
    {
        self::prepareParams(key: 'dryrun', value: $value);

        return $this;
    }

    /**
     * @param string $url
     *
     * @return FortySixElksSMS
     */
    public function whenDelivered(string $url): FortySixElksSMS
    {
        self::prepareParams(key: 'whendelivered', value: $url);

        return $this;
    }

    /**
     * @return FortySixElksSMS
     */
    public function dontLog(): FortySixElksSMS
    {
        self::prepareParams(key: 'dontlog', value: 'message');

        return $this;
    }
}
