<?php

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('UserRepository', function () {
    beforeEach(function () {
        $this->repository = new UserRepository(new User());
    });

    test('can create a user', function () {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ];

        $user = $this->repository->create($userData);

        expect($user)->toBeInstanceOf(User::class);
        expect($user->name)->toBe('John Doe');
        expect($user->email)->toBe('john@example.com');
        expect($user->password)->not->toBe('password123'); // Should be hashed
        
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    });

    test('can find user by id', function () {
        $user = User::factory()->create();

        $foundUser = $this->repository->find($user->id);

        expect($foundUser)->toBeInstanceOf(User::class);
        expect($foundUser->id)->toBe($user->id);
        expect($foundUser->email)->toBe($user->email);
    });

    test('returns null when user not found by id', function () {
        $foundUser = $this->repository->find(999);

        expect($foundUser)->toBeNull();
    });

    test('can find user by email', function () {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $foundUser = $this->repository->findByEmail('test@example.com');

        expect($foundUser)->toBeInstanceOf(User::class);
        expect($foundUser->id)->toBe($user->id);
        expect($foundUser->email)->toBe('test@example.com');
    });

    test('returns null when user not found by email', function () {
        $foundUser = $this->repository->findByEmail('nonexistent@example.com');

        expect($foundUser)->toBeNull();
    });

    test('can update a user', function () {
        $user = User::factory()->create(['name' => 'Original Name']);

        $updateData = ['name' => 'Updated Name'];
        $updatedUser = $this->repository->update($user, $updateData);

        expect($updatedUser->name)->toBe('Updated Name');
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
        ]);
    });

    test('can delete a user', function () {
        $user = User::factory()->create();

        $result = $this->repository->delete($user);

        expect($result)->toBeTrue();
        
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    });

    test('can get all users', function () {
        User::factory()->count(5)->create();

        $users = $this->repository->all();

        expect($users)->toHaveCount(5);
        expect($users->first())->toBeInstanceOf(User::class);
    });

    test('can get paginated users', function () {
        User::factory()->count(10)->create();

        $users = $this->repository->all(5);

        expect($users->total())->toBe(10);
        expect($users->perPage())->toBe(5);
        expect($users->items())->toHaveCount(5);
    });

    test('can search users by name', function () {
        User::factory()->create(['name' => 'John Doe']);
        User::factory()->create(['name' => 'Jane Smith']);
        User::factory()->create(['name' => 'Bob Johnson']);

        $results = $this->repository->search('John');

        expect($results)->toHaveCount(2); // John Doe and Bob Johnson
    });

    test('can search users by email', function () {
        User::factory()->create(['email' => 'john@example.com']);
        User::factory()->create(['email' => 'jane@test.com']);
        User::factory()->create(['email' => 'bob@example.com']);

        $results = $this->repository->search('example');

        expect($results)->toHaveCount(2); // john@example.com and bob@example.com
    });

    test('can search users with pagination', function () {
        User::factory()->count(10)->create(['name' => 'John Test']);

        $results = $this->repository->search('John', 5);

        expect($results->total())->toBe(10);
        expect($results->perPage())->toBe(5);
        expect($results->items())->toHaveCount(5);
    });
});
