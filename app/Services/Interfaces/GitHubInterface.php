<?php

namespace App\Services\Interfaces;

interface GitHubInterface
{
    public function getUserRepositories(string $username): ?array;
}
