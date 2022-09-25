<?php

namespace NotificationChannels\FortySixElks;

use NotificationChannels\FortySixElks\Exceptions\CouldNotSendNotification;

class FortySixElksSMS extends FortySixElksMedia implements FortySixElksMediaInterface
{
    const ENDPOINT = 'https://api.46elks.com/a1/SMS';

    public $type = 'SMS';
    protected $flash = 'no';
    protected $dry = 'no';
    protected $delivered = null;
    protected $log = false;

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
                'form_params' => [
                    'from'     => $this->from,
                    'message'  => $this->getContent(),
                    'to'       => $this->phone_number,
                    'flashsms' => $this->flash,
                    'dryrun'   => $this->dry,
                    'whendelivered' => $this->delivered,
                    'dontlog' => $this->log,
                ],

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
     * @return $this
     */
    public function flash()
    {
        $this->flash = $this->payload['flash'] ?? 'yes';

        return $this;
    }

    /**
     * @return $this
     */
    public function dry()
    {
        $this->dry = $this->payload['dryrun'] ?? 'yes';

        return $this;
    }

    /**
     * @param  string $url
     * @return $this
     */
    public function delivered($url)
    {
        $this->delivered = $this->payload['delivered'] ?? $url;

        return $this;
    }

    /**
     * @return $this
     */
    public function dontLog()
    {
        $this->log = $this->payload['dontLog'] ?? "message";

        return $this;
    }
}
