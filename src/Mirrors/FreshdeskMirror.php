<?php

namespace Mirror\Mirrors;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Mirror\Reflections\FreshdeskReflection;

class FreshdeskMirror extends AbstractMirror
{
    /**
     * Sync the reflectable.
     *
     * @param $reflectable
     * @return mixed|null
     */
    public function reflect($reflectable)
    {
        $reflection = $reflectable->reflectTo('freshdesk');

        if (! $reflectable->reflectTo('freshdesk') ||
            ! $reflection instanceof FreshdeskReflection) {
            return null;
        }

        if ($user = $this->fetchUser($reflectable)) {
            $this->client()->put("/api/v2/search/contacts/{$user['id']}", $reflection);
        } else {
            $user = $this->client()->post('/api/v2/search/contacts', $reflection);
        }

        return $user['id'];
    }

    protected function fetchUser(FreshdeskReflection $reflection): array
    {
        $results = $this->client()->get('/api/v2/search/contacts', [
            'query' => "email:{$reflection->getEmail()}",
        ])->json();

        return head($results);
    }

    protected function client(): PendingRequest
    {
        return Http::asJson()
            ->withToken($this->config['key'])
            ->baseUrl($this->config['domain']);
    }
}
