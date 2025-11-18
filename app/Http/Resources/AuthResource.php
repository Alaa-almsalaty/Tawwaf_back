<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class AuthResource extends JsonResource
{
    protected $accessToken;
    protected $refreshToken;
    protected $tenantDomain;

    public function __construct($resource, ?string $accessToken = null, ?string $refreshToken = null, ?string $tenantDomain = null)
    {
        parent::__construct($resource);
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->tenantDomain = $tenantDomain;
    }

    public static $wrap = null;

    public function toArray($request)
    {
        return [
            'user' => UserResource::make($this->resource),
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
            'token_type' => 'Bearer',
            'expires_in' => config('sanctum.expiration') ? config('sanctum.expiration') * 60 : null,
            'tenant' => [
                'domain' => $this->tenantDomain ?? parse_url(config('app.url'), PHP_URL_HOST),
            ],
        ];
    }
}
