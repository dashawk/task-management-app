<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('User API', function () {
    test('authenticated user can get list of users', function () {
        $user = User::factory()->create();
        User::factory()->count(5)->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'email_verified_at',
                        'created_at',
                        'updated_at',
                    ]
                ],
                'links',
                'meta',
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Users retrieved successfully',
            ]);
    });

    test('unauthenticated user cannot get list of users', function () {
        User::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(401);
    });

    test('authenticated user can view a specific user', function () {
        $user = User::factory()->create();
        $targetUser = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/v1/users/{$targetUser->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'User retrieved successfully',
                'data' => [
                    'id' => $targetUser->id,
                    'name' => $targetUser->name,
                    'email' => $targetUser->email,
                ],
            ]);
    });

    test('authenticated user can create a new user', function () {
        $user = User::factory()->create();

        $userData = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password123',
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/users', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'User created successfully',
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ]);
    });

    test('user can update their own profile', function () {
        $user = User::factory()->create();

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/v1/users/{$user->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'User updated successfully',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    });

    test('user cannot update another users profile', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $updateData = [
            'name' => 'Hacker Name',
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/v1/users/{$otherUser->id}", $updateData);

        $response->assertStatus(403);
    });

    test('user can delete their own account', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/v1/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'User deleted successfully',
            ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    });

    test('user cannot delete another users account', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/v1/users/{$otherUser->id}");

        $response->assertStatus(403);
    });

    test('users can search for other users', function () {
        $user = User::factory()->create();
        $searchUser = User::factory()->create(['name' => 'John Search']);
        User::factory()->count(3)->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/users?search=John');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Users retrieved successfully',
            ]);

        $responseData = $response->json('data');
        expect($responseData)->toBeArray();
        expect($responseData)->toHaveCount(1);
        expect($responseData[0]['name'])->toBe('John Search');
    });
});
