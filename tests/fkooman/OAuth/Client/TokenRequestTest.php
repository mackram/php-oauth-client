<?php

/**
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Lesser General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Lesser General Public License for more details.
 *
 *  You should have received a copy of the GNU Lesser General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace fkooman\OAuth\Client;

use Guzzle\Http\Client;
use Guzzle\Plugin\History\HistoryPlugin;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Message\Response;

class TokenRequestTest extends \PHPUnit_Framework_TestCase
{

    public function testSimpleClient()
    {
        $client = new Client();
        $mock = new MockPlugin();
        $mock->addResponse(
            new Response(
                200,
                null,
                json_encode(
                    array(
                        "access_token" => "foo",
                        "token_type" => "Bearer",
                        "expires_in" => 5,
                        "scope" => "foo",
                        "refresh_token" => "bar",
                        "unsupported_key" => "foo",
                    )
                )
            )
        );
        $client->addSubscriber($mock);
        $history = new HistoryPlugin();
        $history->setLimit(5);
        $client->addSubscriber($history);

        $tokenRequest = new TokenRequest(
            $client,
            new ClientConfig(
                array(
                    "client_id" => "foo",
                    "client_secret" => "bar",
                    "authorize_endpoint" => "http://www.example.org/authorize",
                    "token_endpoint" => "http://www.example.org/token"
                )
            )
        );
        $tokenRequest->withAuthorizationCode("12345");
        $lastRequest = $history->getLastRequest();
        $this->assertEquals("POST", $lastRequest->getMethod());
        $this->assertEquals("code=12345&grant_type=authorization_code", $lastRequest->getPostFields()->__toString());
        $this->assertEquals("Basic Zm9vOmJhcg==", $lastRequest->getHeader("Authorization"));
        $this->assertEquals(
            "application/x-www-form-urlencoded; charset=utf-8",
            $lastRequest->getHeader("Content-Type")
        );
    }
}
