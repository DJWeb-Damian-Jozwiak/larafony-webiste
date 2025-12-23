<x-bridges-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Carbon Clock Bridge</h1>
    <p class="lead text-white-50">
        PSR-20 Clock implementation using Carbon for powerful date/time handling.
    </p>

    <h2>Installation</h2>
    <pre class="line-numbers"><code class="language-bash">composer require larafony/clock-carbon</code></pre>

    <h2>Configuration</h2>
    <pre class="line-numbers"><code class="language-php">use Larafony\Clock\Carbon\ServiceProviders\CarbonServiceProvider;

$app->withServiceProviders([
    CarbonServiceProvider::class
]);</code></pre>

    <h2>Basic Usage</h2>
    <pre class="line-numbers"><code class="language-php">use Psr\Clock\ClockInterface;
use Larafony\Clock\Carbon\CarbonClock;

final class ReportController extends Controller
{
    #[Route('/reports/daily', methods: ['GET'])]
    public function daily(ClockInterface $clock): ResponseInterface
    {
        $now = $clock->now(); // Returns CarbonImmutable

        $report = Report::query()
            ->whereDate('created_at', $now->toDateString())
            ->get();

        return new ResponseFactory()->createJsonResponse($report);
    }
}</code></pre>

    <h2>Date Manipulation</h2>
    <pre class="line-numbers"><code class="language-php">use Larafony\Clock\Carbon\CarbonClock;

$clock = CarbonClock::fromTimezone('Europe/Warsaw');
$now = $clock->now();

// Add/subtract time
$tomorrow = $now->addDay();
$lastWeek = $now->subWeek();
$nextMonth = $now->addMonth();

// Human readable
$diff = $now->diffForHumans(); // "just now"

// Formatting
$formatted = $now->format('Y-m-d H:i:s');
$iso = $now->toIso8601String();</code></pre>

    <h2>Timezone Support</h2>
    <pre class="line-numbers"><code class="language-php">// Create clock for specific timezone
$warsawClock = CarbonClock::fromTimezone('Europe/Warsaw');
$nyClock = CarbonClock::fromTimezone('America/New_York');

// Compare times
$warsawNow = $warsawClock->now();
$nyNow = $nyClock->now();

echo $warsawNow->diffInHours($nyNow); // 6</code></pre>

    <h2>Features</h2>
    <ul>
        <li><strong>PSR-20 compatible</strong> - Implements <code>ClockInterface</code></li>
        <li><strong>Immutable by default</strong> - Uses <code>CarbonImmutable</code></li>
        <li><strong>Timezone aware</strong> - Full timezone support</li>
        <li><strong>Human readable</strong> - <code>diffForHumans()</code> and more</li>
        <li><strong>Rich API</strong> - All Carbon methods available</li>
    </ul>
</x-bridges-layout>
