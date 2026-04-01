
namespace Tests7\Feature;

use Final7\App\Enums\Priority;
use Final7\App\Enums\TaskStatus;
use Final7\App\Models\Project;
use Final7\App\Models\Task;
use Final7\App\Models\User;
use Final7\App\Models\Webhook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Tests7\TestCase;


class WebhookCorrectSignatureTest extends TestCase
{
    use RefreshDatabase;
    protected string $webhookUrl = "http://webhook-receiver:81";


    public function testMain()
    {


        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $project = Project::factory()->create([
            'owner_id' => $user->id,
            'name' => 'test',
        ]);
        $webhook = Webhook::create([
            'project_id' => $project->id,
            'url' => $this->webhookUrl,
            'secret' => 'secret-test',
            'enable' => true,
        ]);

        $task = Task::factory()->create([
            'project_id' => $project->id,
            'status' => TaskStatus::NEW->value,
            'priority' => Priority::NORMAL->value,
            'title' => 'test'
        ]);

        $payload = json_encode([
            'status' => TaskStatus::DONE->value,
            'title' => $task->title,
            'timestamp' => new \DateTimeImmutable()->format('c')
        ]);
        $signature = hash_hmac('sha256', $payload, $webhook->secret);

        $response = $this->withHeaders([
            'X-Webhook-Signature' => $signature,
        ])
            ->patchJson('/api/tasks/' . $task->id, [
                'status' => TaskStatus::DONE->value,
                'title' => $task->title,
            ]);

        $response->assertStatus(200);
    }
} 