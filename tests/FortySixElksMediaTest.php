<?php

namespace NotificationChannels\FortySixElks\Test;

use NotificationChannels\FortySixElks\FortySixElksMMS;
use NotificationChannels\FortySixElks\FortySixElksSMS;

class FortySixElksMediaTest extends \PHPUnit\Framework\TestCase
{
    public function testSMSTest()
    {
        $this->assertNotNull(FortySixElksSMS::ENDPOINT);
        $class = new FortySixElksSMS();

        //test content
        $this->assertInstanceOf(FortySixElksSMS::class, $class->line('test line'));
        $this->assertStringContainsString('test', $class->getContent());
    }

    public function testMMSTest()
    {
        $this->assertNotNull(FortySixElksMMS::ENDPOINT);

        $class = new FortySixElksMMS();

        //test content
        $this->assertInstanceOf(FortySixElksMMS::class, $class->line('test line'));
        $this->assertStringContainsString('test', $class->getContent());
    }
}
