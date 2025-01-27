<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GitHubService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.github.com',
            'headers' => [
                'Authorization' => 'token' . env('GITHUB_PERSONAL_ACCESS_TOKEN'),
                'Accept' => 'application/vnd.github.v3+json',
            ],
        ]);
    }

    /**
     * Получение списка репозиториев пользователя.
     *
     * @param string $username
     * @return array|null
     */
    public function getUserRepositories(string $username): ?array
    {
        try {
            $response = $this->client->get('users/{$username}/repos');
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Ошибка получения репозиториев: ' . $e->getMessage());
            return null;
        }
    }
}
