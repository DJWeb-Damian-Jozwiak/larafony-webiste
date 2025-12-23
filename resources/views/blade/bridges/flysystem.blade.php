<x-bridges-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Flysystem Storage Bridge</h1>
    <p class="lead text-white-50">
        Unified filesystem abstraction with Flysystem - local, S3, FTP, SFTP, and more.
    </p>

    <h2>Installation</h2>
    <pre class="line-numbers"><code class="language-bash">composer require larafony/storage-flysystem

# Optional adapters
composer require league/flysystem-aws-s3-v3  # Amazon S3
composer require league/flysystem-ftp        # FTP
composer require league/flysystem-sftp-v3    # SFTP</code></pre>

    <h2>Configuration</h2>
    <pre class="line-numbers"><code class="language-php">use Larafony\Storage\Flysystem\ServiceProviders\FlysystemServiceProvider;

$app->withServiceProviders([
    FlysystemServiceProvider::class
]);</code></pre>

    <h2>Basic Usage</h2>
    <pre class="line-numbers"><code class="language-php">use Larafony\Storage\Flysystem\FlysystemStorage;

// Local storage
$storage = FlysystemStorage::local('/var/www/storage');

// Write file
$storage->put('uploads/photo.jpg', $content);

// Read file
$content = $storage->get('uploads/photo.jpg');

// Check existence
if ($storage->exists('uploads/photo.jpg')) {
    // File exists
}

// Delete file
$storage->delete('uploads/photo.jpg');</code></pre>

    <h2>Multi-Disk Configuration</h2>
    <pre class="line-numbers"><code class="language-php">use Larafony\Storage\Flysystem\FlysystemFactory;

final class UploadController extends Controller
{
    #[Route('/upload', methods: ['POST'])]
    public function upload(FlysystemFactory $factory): ResponseInterface
    {
        // Get specific disk
        $local = $factory->disk('local');
        $s3 = $factory->disk('s3');

        // Upload to local first
        $local->put('temp/upload.jpg', $content);

        // Then sync to S3
        $s3->put('backups/upload.jpg', $local->get('temp/upload.jpg'));

        return new ResponseFactory()->createJsonResponse(['uploaded' => true]);
    }
}</code></pre>

    <h2>S3 Configuration</h2>
    <pre class="line-numbers"><code class="language-php">// config/filesystems.php
return [
    'default' => 'local',

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
        ],
    ],
];</code></pre>

    <h2>Features</h2>
    <ul>
        <li><strong>Unified API</strong> - Same methods for all storage backends</li>
        <li><strong>Multiple adapters</strong> - Local, S3, FTP, SFTP, and more</li>
        <li><strong>Disk switching</strong> - Easy multi-storage management</li>
        <li><strong>Stream support</strong> - Handle large files efficiently</li>
        <li><strong>Visibility control</strong> - Public/private file permissions</li>
    </ul>
</x-bridges-layout>
