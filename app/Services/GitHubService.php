<?php

namespace App\Services;

use App\Exceptions\GitHubException;
use App\Services\Interfaces\GitHubInterface;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GitHubService implements GitHubInterface
{
    private string $apiUrl;
    private string $apiToken;

    public function __construct(string $apiUrl, string $apiToken)
    {
        $this->apiUrl = $apiUrl;
        $this->apiToken = $apiToken;
    }

    public function getUserRepositories(string $username): ?array
    {
        $cacheKey = "github_repositories_{$username}";

        return Cache::remember($cacheKey, 3600, function () use ($username) {
            try {
                $response = Http::withToken($this->apiToken)
                    ->get("{$this->apiUrl}/users/{$username}/repos");

                if ($response->failed()) {
                    $statusCode = $response->status();
                    $errorMessage = $response->json('message', 'Неизвестная ошибка');
                    throw GitHubException::requestError($statusCode, $errorMessage);
                }
                $data = $response->json();

                if (!is_array($data)) {
                    throw GitHubException::responseError();
                }

                return $data;
            } catch (RequestException $e) {
                throw new GitHubException('Ошибка при запросе данных с GitHub: ' . $e->getMessage(), 0, $e);
            } catch (Exception $e) {
                throw new GitHubException('Ошибка при получении списка репозиториев с GitHub: ' . $e->getMessage(), 0, $e);
            }
        });
    }
}
