<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class Oauth2ServerTest extends WebTestCase
{
    public function testOauth2Server(): void
    {
        $client = static::createClient();

        $clientId = '42217fa12c05db45bee0b870b27ffe56';
        $clientSecret = 'b727fb0b872b532ea5f6061a8194f7a55c4e0db7ecf5bcf4e5487bfdd3aff9f33e05b4e275d013d55f6a96ca14ecc69dd83c5961aa02d3c5df59c1f433bc0998';
        $csrfToken = 'a0d9f59b4d80e287cbe1ba36771ac2a011a173f2';
        $scope = 'default';

        $client->request('GET', '/authorize', [
            'response_type' => 'code',
            'client_id' => $clientId,
            'scope' => $scope,
            'state' => $csrfToken,
        ]);
        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();
        $this->assertResponseIsSuccessful();

        $client->submitForm('Connexion', [
            'email' => 'test.user@example.com',
            'password' => 'password',
        ]);
        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();
        $this->assertResponseStatusCodeSame(302);

        $redirectUri = $client->getResponse()->headers->get('location', '');
        list($uri, $queryString) = explode('?', $redirectUri);
        parse_str($queryString, $parameters);
        $this->assertArrayHasKey('code', $parameters);
        $this->assertArrayHasKey('state', $parameters);
        $this->assertEquals($csrfToken, $parameters['state']);

        $client->request('POST', '/token', [
            'grant_type' => 'authorization_code',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'code' => $parameters['code'],
        ]);

        $this->assertResponseIsSuccessful();
        if (false === ($responseContent = $client->getResponse()->getContent())) {
            $this->fail('Invalid token request response.');
        }

        $data = json_decode($responseContent, true);
        $this->assertArrayHasKey('token_type', $data);
        $this->assertEquals('Bearer', $data['token_type']);
        $this->assertArrayHasKey('expires_in', $data);
        $this->assertArrayHasKey('access_token', $data);
        $this->assertArrayHasKey('refresh_token', $data);

        $client->request('POST', '/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $data['refresh_token'],
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'scope' => $scope,
        ]);

        $this->assertResponseIsSuccessful();
        if (false === ($responseContent = $client->getResponse()->getContent())) {
            $this->fail('Invalid refresh token request response.');
        }

        $data = json_decode($responseContent, true);
        $this->assertArrayHasKey('token_type', $data);
        $this->assertEquals('Bearer', $data['token_type']);
        $this->assertArrayHasKey('expires_in', $data);
        $this->assertArrayHasKey('access_token', $data);
        $this->assertArrayHasKey('refresh_token', $data);
    }
}