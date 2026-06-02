<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SupabaseService
{
    protected Client $http;
    protected string $url;
    protected string $key;
    protected string $serviceKey;

    public function __construct()
    {
        $this->url        = rtrim(config('supabase.url'), '/');
        $this->key        = config('supabase.anon_key');
        $this->serviceKey = config('supabase.service_key');
        $this->http       = new Client(['base_uri' => $this->url]);
    }

    // ── Auth ──────────────────────────────────────────────

    public function signUp(string $email, string $password): array
    {
        return $this->post('/auth/v1/signup', [
            'email'    => $email,
            'password' => $password,
        ]);
    }

    public function signIn(string $email, string $password): array
    {
        return $this->post('/auth/v1/token?grant_type=password', [
            'email'    => $email,
            'password' => $password,
        ]);
    }

    public function signOut(string $accessToken): array
    {
        return $this->post('/auth/v1/logout', [], $accessToken);
    }

    public function getUser(string $accessToken): array
    {
        return $this->get('/auth/v1/user', $accessToken);
    }

    // ── Database ──────────────────────────────────────────

    public function select(string $table, array $params = [], ?string $token = null): array
    {
        $query = http_build_query($params);
        return $this->get("/rest/v1/{$table}?{$query}", $token);
    }

    public function insert(string $table, array $data, ?string $token = null): array
    {
        return $this->post("/rest/v1/{$table}", $data, $token, [
            'Prefer' => 'return=representation',
        ]);
    }

    public function update(string $table, string $id, array $data, ?string $token = null): array
    {
        return $this->patch("/rest/v1/{$table}?id=eq.{$id}", $data, $token);
    }

    public function delete(string $table, string $id, ?string $token = null): array
    {
        return $this->request('DELETE', "/rest/v1/{$table}?id=eq.{$id}", [], $token);
    }

    public function search(string $query, string $userId, ?string $token = null): array
    {
        return $this->post('/rest/v1/rpc/search_vault', [
            'query' => $query,
            'uid'   => $userId,
        ], $token);
    }

    // ── Storage ───────────────────────────────────────────

    public function uploadFile(string $userId, string $fileName, string $fileContent, string $mimeType, string $token): array
    {
        $path = "{$userId}/{$fileName}";
        try {
            $response = $this->http->request('POST', "/storage/v1/object/keeption-files/{$path}", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Content-Type'  => $mimeType,
                    'apikey'        => $this->key,
                ],
                'body' => $fileContent,
            ]);
            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (GuzzleException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getFileUrl(string $userId, string $fileName): string
    {
        return "{$this->url}/storage/v1/object/public/keeption-files/{$userId}/{$fileName}";
    }

    // ── HTTP Helpers ──────────────────────────────────────

    protected function get(string $path, ?string $token = null): array
    {
        return $this->request('GET', $path, [], $token);
    }

    protected function post(string $path, array $body, ?string $token = null, array $extra = []): array
    {
        return $this->request('POST', $path, $body, $token, $extra);
    }

    protected function patch(string $path, array $body, ?string $token = null): array
    {
        return $this->request('PATCH', $path, $body, $token);
    }

    protected function request(string $method, string $path, array $body = [], ?string $token = null, array $extraHeaders = []): array
    {
        try {
            $headers = array_merge([
                'apikey'       => $this->key,
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ], $extraHeaders);

            if ($token) {
                $headers['Authorization'] = "Bearer {$token}";
            }

            $options = ['headers' => $headers];
            if (!empty($body)) {
                $options['json'] = $body;
            }

            $response = $this->http->request($method, $path, $options);
            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (GuzzleException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
