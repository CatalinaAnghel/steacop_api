<?php

declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\OpenApi\OpenApi;
use ApiPlatform\OpenApi\Model;
use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;

final class JwtDecorator implements OpenApiFactoryInterface
{
    public const JSON_MIME_TYPE = 'application/json';
    public const TOKEN_REFRESH_MESSAGE = 'Refresh JWT token';

    public function __construct(
        private readonly OpenApiFactoryInterface $decorated
    ) {}

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $schemas = $this->buildSchema($openApi);

        $pathItem = new Model\PathItem(
            'JWT Token',
            null,
            null,
            null,
            null,
            new Model\Operation(
                'postCredentialsItem',
                ['Access Token'],
                [
                    '200' => [
                        'description' => 'Get JWT token',
                        'content' => [
                            self::JSON_MIME_TYPE => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token'
                                ],
                            ],
                        ],
                    ],
                ],
                'Get JWT token to login.',
                '',
                null,
                [],
                new Model\RequestBody(
                    'Generate new JWT Token',
                    new \ArrayObject([
                        self::JSON_MIME_TYPE => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials',
                            ],
                        ],
                    ])
                )
            )
        );
        $openApi->getPaths()->addPath('/api/authentication_token', $pathItem);
        $pathItem = new Model\PathItem(
            'JWT Refresh Token',
            null,
            null,
            null,
            null,
            new Model\Operation(
                'postCredentialsItem',
                ['Refresh Token'],
                [
                    '200' => [
                        'description' => self::TOKEN_REFRESH_MESSAGE,
                        'content' => [
                            self::JSON_MIME_TYPE => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token',
                                ],
                            ],
                        ],
                    ],
                ],
                self::TOKEN_REFRESH_MESSAGE,
                '',
                null,
                [],
                new Model\RequestBody(
                    self::TOKEN_REFRESH_MESSAGE,
                    new \ArrayObject([
                        self::JSON_MIME_TYPE => [
                            'schema' => [
                                '$ref' => '#/components/schemas/RefreshToken',
                            ],
                        ],
                    ])
                )
            )
        );
        $openApi->getPaths()->addPath('/api/token/refresh', $pathItem);

        return $openApi;
    }

    /**
     * Build the schemas
     * @param $openApi
     * @return \ArrayObject
     */
    private function buildSchema($openApi): \ArrayObject
    {
        $schemas = $openApi->getComponents()->getSchemas();

        $schemas['Token'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'refreshToken' => [
                    'type' => 'string',
                    'readOnly' => true
                ],
                'refreshTokenExpiration' => [
                    'type' => 'integer'
                ]
            ],
        ]);
        $schemas['RefreshToken'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'refreshToken' => [
                    'type' => 'string'
                ]
            ],
        ]);
        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'email' => [
                    'type' => 'string',
                    'example' => 'johndoe@example.com',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => 'apassword',
                ],
            ],
        ]);

        return $schemas;
    }
}
