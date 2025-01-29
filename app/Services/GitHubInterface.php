<?php

namespace App\Services;

interface GitHubInterface
{
    public function getUserRepositories(string $username): ?array;
}
