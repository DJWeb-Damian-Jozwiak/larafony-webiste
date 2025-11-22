<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Queue & Jobs</h1>
    <p class="lead text-white-50">
        Enterprise-grade job scheduling and queue processing with ORM integration, Clock-based timestamps, UUID support, and comprehensive failed job handling
    </p>

    <div class="alert-docs alert-info">
        <i class="bi bi-lightning-charge-fill me-2"></i>
        <strong>Production Ready:</strong> Built with ORM persistence, UUID primary keys for distributed systems, Clock integration for testable time operations, and complete failed job recovery system.
    </div>

    <h2>Overview</h2>
    <p>
        Larafony's scheduler system combines two powerful capabilities:
    </p>
    <ul>
        <li><strong>Queue System</strong> - Asynchronous job processing with database and Redis backends</li>
        <li><strong>Task Scheduler</strong> - Cron-based recurring jobs with enum presets</li>
        <li><strong>ORM Integration</strong> - Jobs and failed jobs stored as entities with full ORM support</li>
        <li><strong>UUID Support</strong> - Distributed-system-ready with RFC 4122 v4 UUIDs</li>
        <li><strong>Clock Integration</strong> - Stores Clock objects for testable time operations</li>
        <li><strong>Attribute-Based</strong> - Explicit <code>#[Serialize]</code> attribute for job properties</li>
        <li><strong>Failed Job Handling</strong> - Complete retry, forget, flush, and prune commands</li>
    </ul>

    <h2>Quick Start</h2>

    <h3>1. Setup Database Tables</h3>

    <pre class="line-numbers"><code class="language-bash"># Generate migrations
php bin/larafony table:jobs
php bin/larafony table:failed-jobs

# Run migrations
php bin/larafony migrate</code></pre>

    <h3>2. Create a Job</h3>

    <pre class="line-numbers"><code class="language-php">namespace App\Jobs;

use Larafony\Framework\Scheduler\Attributes\Serialize;
use Larafony\Framework\Scheduler\Job;

class SendWelcomeEmailJob extends Job
{
    public function __construct(
        #[Serialize] private int $userId,
        #[Serialize] private string $email
    ) {}

    public function handle(): void
    {
        // Send welcome email
        $emailService = Application::instance()->get(EmailService::class);
        $emailService->send($this->email, 'Welcome!', 'Welcome to our platform');
    }

    public function handleException(\Throwable $e): void
    {
        // Log the failure
        error_log("Failed to send welcome email to {$this->email}: " . $e->getMessage());
    }
}</code></pre>

    <div class="alert-docs alert-success">
        <i class="bi bi-check-circle me-2"></i>
        <strong>Explicit Serialization:</strong> Only properties marked with <code>#[Serialize]</code> are serialized. This prevents accidental serialization of dependencies and makes the code more explicit and type-safe.
    </div>

    <h3>3. Dispatch the Job</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Scheduler\Dispatcher;

$dispatcher = $container->get(Dispatcher::class);

// Immediate dispatch
$jobId = $dispatcher->dispatch(new SendWelcomeEmailJob(123, 'user@example.com'));

// Delayed dispatch (after 5 minutes)
$jobId = $dispatcher->dispatchAfter(300, new SendWelcomeEmailJob(123, 'user@example.com'));

// Batch dispatch
$jobIds = $dispatcher->dispatchBatch(
    new SendWelcomeEmailJob(1, 'user1@example.com'),
    new SendWelcomeEmailJob(2, 'user2@example.com'),
    new SendWelcomeEmailJob(3, 'user3@example.com')
);</code></pre>

    <h3>4. Process Jobs</h3>

    <pre class="line-numbers"><code class="language-bash"># Run worker continuously
php bin/larafony queue:work

# Process one job and exit (testing)
php bin/larafony queue:work --once

# Process max 100 jobs then exit (worker rotation)
php bin/larafony queue:work --max-jobs=100</code></pre>

    <h2>ORM Integration</h2>

    <p>Unlike other frameworks that use raw SQL, Larafony's queue system is fully ORM-based:</p>

    <pre class="line-numbers"><code class="language-php">// DatabaseQueue uses Job entity
public function push(JobContract $job): string
{
    $jobEntity = new JobEntity();
    $jobEntity->payload = serialize($job);
    $jobEntity->queue = 'default';
    $jobEntity->attempts = 0;
    $jobEntity->reserved_at = null;
    $jobEntity->available_at = ClockFactory::instance(); // Clock object!
    $jobEntity->created_at = ClockFactory::instance();
    $jobEntity->save();

    return (string) $jobEntity->id; // Returns UUID
}</code></pre>

    <h3>Key Features</h3>

    <ul>
        <li><strong>UUID Primary Keys</strong> - Job entity has <code>use_uuid = true</code> for distributed systems</li>
        <li><strong>Clock Objects</strong> - <code>available_at</code> and <code>created_at</code> are Clock instances, not DateTimeImmutable</li>
        <li><strong>ORM Queries</strong> - Uses <code>JobEntity::query()</code> with proper type casting</li>
        <li><strong>OrderDirection Enum</strong> - Type-safe sorting with <code>OrderDirection::ASC</code></li>
    </ul>

    <div class="alert-docs alert-info">
        <i class="bi bi-clock-history me-2"></i>
        <strong>Clock vs DateTimeImmutable:</strong> Storing Clock objects enables seamless time mocking in tests with <code>ClockFactory::freeze()</code> and proper separation between system time and domain time.
    </div>

    <h2>Task Scheduling</h2>

    <p>Schedule recurring jobs with cron-like syntax using enum presets:</p>

    <h3>Configuration</h3>

    <pre class="line-numbers"><code class="language-php">// config/schedule.php
use Larafony\Framework\Scheduler\CronSchedule;

return [
    // Run every minute
    HealthCheckJob::class => CronSchedule::EVERY_MINUTE,

    // Run daily at 3:00 AM
    DatabaseBackupJob::class => CronSchedule::DAILY->at(3, 0),

    // Run every Monday at 9:00 AM
    SendWeeklyReportJob::class => CronSchedule::MONDAY->at(9, 0),

    // Run every 15 minutes
    CleanupTempFilesJob::class => CronSchedule::EVERY_FIFTEEN_MINUTES,

    // Run on weekdays at noon
    SendDailyReportJob::class => CronSchedule::WEEKDAYS->at(12, 0),

    // Custom cron expression
    GenerateSitemapJob::class => '30 * * * *', // Every hour at :30

    // Every N minutes
    CacheWarmupJob::class => CronSchedule::everyNMinutes(10),
];</code></pre>

    <h3>Cron Setup</h3>

    <pre class="line-numbers"><code class="language-bash"># Add this single cron entry (runs every minute)
* * * * * cd /var/www/project && php bin/larafony schedule:run >> /dev/null 2>&1</code></pre>

    <h3>Available Presets</h3>

    <ul>
        <li><code>EVERY_MINUTE</code> - Every minute</li>
        <li><code>EVERY_FIVE_MINUTES</code> - Every 5 minutes</li>
        <li><code>EVERY_FIFTEEN_MINUTES</code> - Every 15 minutes</li>
        <li><code>EVERY_THIRTY_MINUTES</code> - Every 30 minutes</li>
        <li><code>HOURLY</code> - Every hour at :00</li>
        <li><code>DAILY</code> - Every day at midnight</li>
        <li><code>WEEKLY</code> - Every Sunday at midnight</li>
        <li><code>MONTHLY</code> - First day of month at midnight</li>
        <li><code>MONDAY</code>, <code>TUESDAY</code>, ..., <code>SUNDAY</code> - Specific day at midnight</li>
        <li><code>WEEKDAYS</code> - Monday-Friday at midnight</li>
        <li><code>WEEKENDS</code> - Saturday-Sunday at midnight</li>
    </ul>

    <h2>Failed Job Handling</h2>

    <p>When a job throws an exception, it's automatically logged to the <code>failed_jobs</code> table with full stack trace:</p>

    <h3>List Failed Jobs</h3>

    <pre class="line-numbers"><code class="language-bash">php bin/larafony queue:failed

# Output:
# UUID: 550e8400-e29b-41d4-a716-446655440000
# Queue: default
# Failed: 2024-01-15 14:30:22
# Exception: RuntimeException: Connection timeout...</code></pre>

    <h3>Retry Failed Jobs</h3>

    <pre class="line-numbers"><code class="language-bash"># Retry specific job by UUID
php bin/larafony queue:retry 550e8400-e29b-41d4-a716-446655440000

# Retry all failed jobs
php bin/larafony queue:retry all</code></pre>

    <h3>Manage Failed Jobs</h3>

    <pre class="line-numbers"><code class="language-bash"># Delete specific failed job
php bin/larafony queue:forget 550e8400-e29b-41d4-a716-446655440000

# Clear all failed jobs
php bin/larafony queue:flush

# Remove failed jobs older than 7 days
php bin/larafony queue:prune --hours=168</code></pre>

    <h3>Programmatic Access</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Scheduler\FailedJobRepository;

$failedJobRepo = $container->get(FailedJobRepository::class);

// Get all failed jobs
$failedJobs = $failedJobRepo->all();

// Retry and re-queue
$job = $failedJobRepo->retry('some-uuid-here');
if ($job) {
    $dispatcher->dispatch($job);
}

// Prune old failures (older than 48 hours)
$count = $failedJobRepo->prune(48);

// Flush all
$failedJobRepo->flush();</code></pre>

    <h2>Testing with ClockFactory</h2>

    <p>Larafony's Clock system makes testing time-dependent queue behavior straightforward:</p>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Clock\ClockFactory;
use PHPUnit\Framework\TestCase;

class QueueTest extends TestCase
{
    protected function setUp(): void
    {
        // Freeze time at a specific moment
        ClockFactory::freeze(new \DateTimeImmutable('2024-01-01 12:00:00'));
    }

    protected function tearDown(): void
    {
        // Reset to real system time
        ClockFactory::reset();
    }

    public function testDelayedJobIsNotAvailableImmediately(): void
    {
        $queue = new DatabaseQueue();

        // Queue a job for 2 hours from now
        $delay = new \DateTime('2024-01-01 14:00:00');
        $jobId = $queue->later($delay, new SendEmailJob('test@example.com'));

        // Job should not be available yet (current time is 12:00)
        $this->assertNull($queue->pop());

        // Advance time to 14:01
        ClockFactory::freeze(new \DateTimeImmutable('2024-01-01 14:01:00'));

        // Now job should be available
        $job = $queue->pop();
        $this->assertInstanceOf(SendEmailJob::class, $job);
    }
}</code></pre>

    <div class="alert-docs alert-success">
        <i class="bi bi-check-circle me-2"></i>
        <strong>Testing Benefits:</strong> Frozen time ensures deterministic tests, no need to wait for delays, easy edge case testing, and complete test isolation.
    </div>

    <h2>Console Commands</h2>

    <h3>Queue Worker</h3>

    <pre class="line-numbers"><code class="language-bash"># Basic usage (runs indefinitely)
php bin/larafony queue:work

# Process one job and exit
php bin/larafony queue:work --once

# Process specific queue
php bin/larafony queue:work --queue=emails

# Process max 100 jobs then exit
php bin/larafony queue:work --max-jobs=100

# Stop when queue is empty
php bin/larafony queue:work --stop-when-empty</code></pre>

    <h3>Schedule Runner</h3>

    <pre class="line-numbers"><code class="language-bash"># Run scheduled tasks (call every minute via cron)
php bin/larafony schedule:run</code></pre>

    <h3>Migration Generators</h3>

    <pre class="line-numbers"><code class="language-bash"># Generate jobs table migration
php bin/larafony table:jobs

# Generate failed_jobs table migration
php bin/larafony table:failed-jobs</code></pre>

    <h2>Production Setup</h2>

    <h3>Supervisor Configuration</h3>

    <pre class="line-numbers"><code class="language-ini">[program:larafony-worker]
command=php /var/www/app/bin/larafony queue:work --stop-when-empty
autostart=true
autorestart=true
numprocs=3
user=www-data
redirect_stderr=true
stdout_logfile=/var/www/app/storage/logs/worker.log</code></pre>

    <h3>Cron Configuration</h3>

    <pre class="line-numbers"><code class="language-bash"># Schedule runner (every minute)
* * * * * cd /var/www/app && php bin/larafony schedule:run >> /dev/null 2>&1

# Clean up old failed jobs (daily at 3 AM)
0 3 * * * cd /var/www/app && php bin/larafony queue:prune --hours=168</code></pre>

    <h2>Database Schema</h2>

    <h3>Jobs Table</h3>

    <pre class="line-numbers"><code class="language-sql">CREATE TABLE jobs (
    id CHAR(36) PRIMARY KEY,              -- UUID
    payload TEXT NOT NULL,                -- Serialized job
    queue VARCHAR(255),                   -- Queue name
    attempts INT DEFAULT 0,               -- Retry counter
    reserved_at DATETIME,                 -- Job lock
    available_at DATETIME NOT NULL,       -- When available
    created_at DATETIME NOT NULL,         -- Creation time
    INDEX idx_queue_available (queue, available_at)
);</code></pre>

    <h3>Failed Jobs Table</h3>

    <pre class="line-numbers"><code class="language-sql">CREATE TABLE failed_jobs (
    id CHAR(36) PRIMARY KEY,              -- UUID
    uuid CHAR(36) UNIQUE NOT NULL,        -- Unique identifier
    connection VARCHAR(255) NOT NULL,     -- Queue connection
    queue VARCHAR(255) NOT NULL,          -- Queue name
    payload LONGTEXT NOT NULL,            -- Serialized job
    exception LONGTEXT NOT NULL,          -- Stack trace
    failed_at DATETIME NOT NULL           -- Failure timestamp
);</code></pre>

    <h2>Comparison with Laravel</h2>

    <table class="comparison-table table table-bordered table-striped">
        <thead>
            <tr>
                <th>Feature</th>
                <th>Larafony</th>
                <th>Laravel</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Job Serialization</td>
                <td>Explicit <code>#[Serialize]</code> attribute</td>
                <td>Implicit (all constructor params)</td>
            </tr>
            <tr>
                <td>Persistence</td>
                <td>ORM-based with entities</td>
                <td>Raw SQL queries</td>
            </tr>
            <tr>
                <td>Primary Keys</td>
                <td>UUID (distributed-ready)</td>
                <td>Auto-increment integer</td>
            </tr>
            <tr>
                <td>Time Handling</td>
                <td>Clock objects (testable)</td>
                <td>Carbon library</td>
            </tr>
            <tr>
                <td>Cron Scheduling</td>
                <td>Enum presets in config</td>
                <td>Fluent API in code</td>
            </tr>
            <tr>
                <td>Failed Jobs</td>
                <td>UUID-based with ORM</td>
                <td>ID-based with Horizon UI</td>
            </tr>
        </tbody>
    </table>

    <div class="alert-docs alert-info">
        <i class="bi bi-star me-2"></i>
        <strong>Larafony Advantage:</strong> Explicit serialization prevents bugs, ORM provides type safety, UUIDs enable distributed systems, and Clock integration makes testing trivial.
    </div>

    <h2>Best Practices</h2>

    <ul>
        <li><strong>Keep Jobs Small</strong> - One job should do one thing</li>
        <li><strong>Make Jobs Idempotent</strong> - Jobs should be safe to run multiple times</li>
        <li><strong>Handle Failures Gracefully</strong> - Implement <code>handleException()</code> method</li>
        <li><strong>Use Serializable Properties</strong> - Mark constructor parameters with <code>#[Serialize]</code></li>
        <li><strong>Monitor Failed Jobs</strong> - Regularly check <code>queue:failed</code> output</li>
        <li><strong>Prune Old Failures</strong> - Run <code>queue:prune</code> periodically</li>
        <li><strong>Use Appropriate Drivers</strong> - Database for simplicity, Redis for performance</li>
    </ul>

    <h2>Learn More</h2>

    <p>
        For detailed implementation examples, advanced patterns, and testing strategies, check out the complete tutorial at <a href="https://masterphp.eu" target="_blank">masterphp.eu</a>.
    </p>

</x-docs-layout>
