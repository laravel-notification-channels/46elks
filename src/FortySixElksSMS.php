<?php

namespace NotificationChannels\FortySixElks;

use NotificationChannels\FortySixElks\Exceptions\CouldNotSendNotification;

class FortySixElksSMS extends FortySixElksMedia implements FortySixElksMediaInterface
{
    const ENDPOINT = 'https://api.46elks.com/a1/SMS';
    public $type = 'SMS';

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
                    'flashsms' => isset($this->payload['flash']) ? $this->payload['flash']: 'no',
                    'dryrun' => isset($this->payload['dryrun']) ? $this->payload['dryrun']: 'no',
                    'whendelivered' => isset($this->payload['flash']) ? $this->payload['flash']: null,
                    'dontlog' => isset($this->payload['dontlog']) ? $this->payload['dontlog']: null,
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

    public function flash()
    {
        $this->payload['flash'] = "yes";

        return $this;
    }

    public function dryRun(){
        $this->payload['dryrun'] = "yes";
        return $this;
    }

    public function whendelivered($url){
        $this->payload['whendelivered'] = $url;
        return $this;
    }

    public function dontLog(){
        $this->payload['dontlog'] = "yes";
        return $this;
    }
}
