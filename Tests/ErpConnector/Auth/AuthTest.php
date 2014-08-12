<?php

class AuthTest extends ErpConnectTest
{
    public function testLogin()
    {
        $this->assertEquals(1, $this->Auth->login());
    }
} 