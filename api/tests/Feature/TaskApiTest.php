<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('reorders tasks and returns order field', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    // Create tasks
    $t1 = Task::create([
        'user_id' => $user->id,
        'title' => 'Task 1',
        'order' => 3,
    ]);
    $t2 = Task::create([
        'user_id' => $user->id,
        'title' => 'Task 2',
        'order' => 1,
    ]);
    $t3 = Task::create([
        'user_id' => $user->id,
        'title' => 'Task 3',
        'order' => 2,
    ]);

    $payload = [
        'tasks' => [
            ['id' => $t1->id, 'order' => 1],
            ['id' => $t2->id, 'order' => 2],
            ['id' => $t3->id, 'order' => 3],
        ],
    ];

    $response = $this->postJson('/api/v1/tasks/reorder', $payload);

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success', 'message', 'data' => [
                ['id', 'title', 'description', 'completed', 'due_date', 'created_at', 'updated_at', 'user_id', 'order']
            ]
        ]);

    // Refresh models
    $t1->refresh();
    $t2->refresh();
    $t3->refresh();

    expect($t1->order)->toBe(1);
    expect($t2->order)->toBe(2);
    expect($t3->order)->toBe(3);

    // Ensure response contains the updated order values
    $returned = collect($response->json('data'))
        ->mapWithKeys(fn($t) => [$t['id'] => $t['order']]);

    expect($returned[$t1->id])->toBe(1);
    expect($returned[$t2->id])->toBe(2);
    expect($returned[$t3->id])->toBe(3);
});

