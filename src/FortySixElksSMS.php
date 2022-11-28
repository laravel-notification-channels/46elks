<?php

namespace NotificationChannels\FortySixElks;

use NotificationChannels\FortySixElks\Exceptions\CouldNotSendNotification;

class FortySixElksSMS extends FortySixElksMedia implements FortySixElksMediaInterface
{
    const ENDPOINT = 'https://api.46elks.com/a1/SMS';

    public $type = 'SMS';
    protected $flash = 'no';
    protected $dryrun = 'no';
    protected $whendelivered = null;
    protected $dontlog = null;

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
                    'from'          => $this->from,
                    'message'       => $this->getContent(),
                    'to'            => $this->phone_number,
                    'flashsms'      => $this->flash,
                    'dryrun'        => $this->dryrun,
                    'whendelivered' => $this->whendelivered,
                    'dontlog'       => $this->dontlog,
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
    public function dryrun()
    {
        $this->dryrun = $this->payload['dryrun'] ?? 'yes';

        return $this;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function whendelivered($url)
    {
        $this->whendelivered = $this->payload['whendelivered'] ?? $url;

        return $this;
    }

    /**
     * @return $this
     */
    public function dontlog()
    {
        $this->dontlog = $this->payload['dontlog'] ?? 'message';

        return $this;
    }
}
