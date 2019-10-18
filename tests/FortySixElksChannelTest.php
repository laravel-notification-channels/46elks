<?php

namespace NotificationChannels\FortySixElks\Test;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\FortySixElks\FortySixElksChannel;
use NotificationChannels\FortySixElks\FortySixElksSMS;

class FortySixElksChannelTest extends \PHPUnit_Framework_TestCase
{
    protected $dispatcher;

    protected $channel;

    protected $notifiable;

    protected $notification;

    protected $smsMessage;

    public function setUp()
    {
        parent::setUp();
        $this->dispatcher = new \Illuminate\Events\Dispatcher();

        $this->channel = new FortySixElksChannel($this->dispatcher);

        $this->smsMessage = Mockery::mock(FortySixElksSMS::class);

        $this->notifiable = new TestNotifiable();

        $this->notification = new TestNotification($this->smsMessage);
    }

    public function testItCanBeInstantiatedTest()
    {
        $this->assertInstanceOf(FortySixElksChannel::class, $this->channel);
    }

    public function testCanSendNotification()
    {
        $this->smsMessage->shouldReceive('send')
            ->once()
            ->andReturn($this->smsMessage);

        $this->notification->to46Elks($this->notifiable);
        $response = $this->channel->send($this->notifiable, $this->notification);

        $this->assertInstanceOf(FortySixElksSMS::class, $response);
    }
}

class TestNotifiable
{
    use Notifiable;
}

class TestNotification extends Notification
{
    public function __construct($message)
    {
        $this->message = $message;
    }

    public function to46Elks($notifiable)
    {
        return $this->message;
    }
}
