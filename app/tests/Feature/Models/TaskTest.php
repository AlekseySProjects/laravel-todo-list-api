<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

/**
 * @internal
 */
#[CoversNothing]
final class TaskTest extends TestCase
{
    use RefreshDatabase;

    private static ?array $task;

    public static function setUpBeforeClass(): void
    {
        self::$task = [
            'title'       => 'Встреча с командой',
            'description' => 'Уточнить коды http ответов',
            'status'      => false,
        ];
    }

    public static function tearDownAfterClass(): void
    {
        self::$task = null;
    }

    public function testCreateTask(): void
    {
        $response = $this->postJson('/api/tasks', self::$task);
        $response
            ->assertStatus(201)
            ->assertJson(['data' => self::$task])
        ;
    }

    public function testCreateTaskValidationErrors(): void
    {
        $dataWithoutTitle = [
            'title'  => '',
            'status' => true,
        ];

        $response = $this->postJson('/api/tasks', $dataWithoutTitle);
        $response->assertStatus(422)
            ->assertJson(['message' => 'The title field is required.'])
        ;
        $tooLongTitle = [
            'title'  => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Fuga, maxime aliquid! Ullam temporibus, similique reiciendis labore deserunt corporis illo molestiae maxime tempore voluptates sunt aliquid, soluta optio repudiandae cumque iusto facere dolor et possimus.',
            'status' => true,
        ];
        $response = $this->postJson('/api/tasks', $tooLongTitle);
        $response->assertStatus(422)
            ->assertJson(['message' => 'The title field must not be greater than 255 characters.'])
        ;

        $dataStatusNotBoolean = [
            'title'  => 'Good title',
            'status' => 'Bad status',
        ];

        $response = $this->postJson('/api/tasks', $dataStatusNotBoolean);
        $response->assertStatus(422)
            ->assertJson(['message' => 'The status field must be true or false.'])
        ;
    }

    #[Depends('testCreateTask')]
    public function testShowTask(): void
    {
        $this->postJson('/api/tasks', self::$task);

        $response = $this->get('/api/tasks/1');
        $response
            ->assertStatus(200)
            ->assertJson(['data' => self::$task])
        ;

        $response = $this->get('/api/tasks/12');
        $response->assertStatus(404)
            ->assertJson(['message' => 'Not found.'])
        ;
    }

    #[Depends('testShowTask')]
    public function testUpdateTask(): void
    {
        $this->postJson('/api/tasks', self::$task);

        $newData = array_map(null, self::$task);

        $newData['status'] = true;

        $response = $this->putJson('/api/tasks/1', $newData);
        $response->assertNoContent();
    }

    #[Depends('testCreateTask')]
    public function testDestroyTask(): void
    {
        $response  = $this->postJson('/api/tasks', self::$task);
        $taskArray = $response['data'];

        $response = $this->deleteJson('/api/tasks/'.$taskArray['id']);
        $response->assertNoContent();
    }

    #[Depends('testCreateTask')]
    public function testIndexTasks(): void
    {
        $this->postJson('/api/tasks', self::$task);
        $this->postJson('/api/tasks', self::$task);
        $this->postJson('/api/tasks', self::$task);

        $response = $this->getJson('/api/tasks');
        self::assertCount(3, $response->original['data']);
    }
}
