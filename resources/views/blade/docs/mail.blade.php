<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Sending Emails</h1>
    <p class="lead text-white-50">
        Native SMTP implementation built from scratch with Laravel-inspired Mailable classes
    </p>

    <div class="alert-docs alert-info">
        <i class="bi bi-envelope-fill me-2"></i>
        <strong>Zero Dependencies:</strong> Complete RFC 5321 compliant SMTP implementation without external libraries. Symfony Mailer support coming after PHP 8.5 GA.
    </div>

    <h2>Overview</h2>
    <p>
        Larafony's Mail component provides:
    </p>
    <ul>
        <li><strong>Native SMTP</strong> - RFC 5321 compliant implementation from scratch</li>
        <li><strong>Mailable Classes</strong> - Laravel-inspired email composition</li>
        <li><strong>Blade Templates</strong> - Use views for email content</li>
        <li><strong>Interface-Based</strong> - Easy to swap transports via TransportContract</li>
        <li><strong>Database Logging</strong> - Track sent emails with MailHistoryLogger</li>
        <li><strong>DSN Configuration</strong> - Connection strings with smart defaults</li>
        <li><strong>Framework Integration</strong> - Reuses UriManager, Stream, ViewManager</li>
    </ul>

    <h2>Quick Start</h2>

    <h3>1. Configuration</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Mail\MailerFactory;

// From DSN with smart defaults
$mailer = MailerFactory::fromDsn('smtp://user:pass@smtp.example.com:587');

// MailHog for local development
$mailer = MailerFactory::createMailHogMailer('localhost', 1025);</code></pre>

    <div class="alert-docs alert-success">
        <i class="bi bi-lightbulb-fill me-2"></i>
        <strong>Development Tip:</strong> Use MailHog to test emails locally without sending real messages. Install with Docker: <code>docker run -p 1025:1025 -p 8025:8025 mailhog/mailhog</code>
    </div>

    <h3>2. Create a Mailable Class</h3>

    <pre class="line-numbers"><code class="language-php">namespace App\Mail;

use Larafony\Framework\Mail\Mailable;
use Larafony\Framework\Mail\Envelope;
use Larafony\Framework\Mail\Content;
use Larafony\Framework\Mail\Address;

class WelcomeEmail extends Mailable
{
    public function __construct(
        private readonly string $userName,
        private readonly string $userEmail
    ) {}

    protected function envelope(): Envelope
    {
        return (new Envelope())
            ->from(new Address('noreply@example.com', 'Larafony'))
            ->to(new Address($this->userEmail))
            ->subject('Welcome to Larafony!');
    }

    protected function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
            data: ['userName' => $this->userName]
        );
    }
}</code></pre>

    <h3>3. Create Email View</h3>

    <p>Create <code>resources/views/emails/welcome.blade.php</code>:</p>

    <pre class="line-numbers"><code class="language-blade">@component('components.Layout', ['title' => 'Welcome'])

&lt;div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;"&gt;
    &lt;div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                padding: 40px; text-align: center;"&gt;
        &lt;h1 style="color: white; margin: 0;"&gt;Welcome to Larafony!&lt;/h1&gt;
    &lt;/div&gt;

    &lt;div style="padding: 40px; background: white;"&gt;
        &lt;p style="font-size: 18px;"&gt;
            Hello &lt;strong&gt;&lcub;&lcub; $userName &rcub;&rcub;&lt;/strong&gt;,
        &lt;/p&gt;

        &lt;p style="font-size: 16px; line-height: 1.6;"&gt;
            Thank you for joining Larafony! We're excited to have you on board.
        &lt;/p&gt;

        &lt;div style="text-align: center; margin: 30px 0;"&gt;
            &lt;a href="https://github.com/larafony/framework"
               style="background: #667eea; color: white; padding: 12px 30px;
                      text-decoration: none; border-radius: 6px;"&gt;
                Get Started
            &lt;/a&gt;
        &lt;/div&gt;
    &lt;/div&gt;
&lt;/div&gt;

@endcomponent</code></pre>

    <h3>4. Send the Email</h3>

    <pre class="line-numbers"><code class="language-php">$mailer->send(new WelcomeEmail('John Doe', 'john@example.com'));</code></pre>

    <h2>DSN Configuration</h2>

    <p>DSN format: <code>smtp://[username:password@]host[:port]</code></p>

    <pre class="line-numbers"><code class="language-php">// Basic SMTP (port 25)
$mailer = MailerFactory::fromDsn('smtp://localhost');

// With authentication (port 587 for TLS by default)
$mailer = MailerFactory::fromDsn('smtp://user:pass@smtp.gmail.com:587');

// SSL (port 465)
$mailer = MailerFactory::fromDsn('smtps://user:pass@smtp.gmail.com:465');

// Explicit TLS
$mailer = MailerFactory::fromDsn('smtp+tls://user:pass@smtp.example.com:587');</code></pre>

    <h3>Smart Port Defaults</h3>
    <ul>
        <li><strong>Port 25</strong> - Plain SMTP (no encryption)</li>
        <li><strong>Port 587</strong> - SMTP with TLS/STARTTLS</li>
        <li><strong>Port 465</strong> - SMTP with SSL (smtps://)</li>
    </ul>

    <h2>Advanced Usage</h2>

    <h3>Multiple Recipients</h3>

    <pre class="line-numbers"><code class="language-php">protected function envelope(): Envelope
{
    return (new Envelope())
        ->from(new Address('noreply@example.com', 'Larafony'))
        ->to(new Address('user1@example.com'))
        ->to(new Address('user2@example.com'))
        ->cc(new Address('manager@example.com'))
        ->bcc(new Address('admin@example.com'))
        ->replyTo(new Address('support@example.com'))
        ->subject('Team Update');
}</code></pre>

    <h3>Email Logging</h3>

    <p>Track sent emails in the database:</p>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Mail\MailHistoryLogger;
use Larafony\Framework\Mail\Mailer;

$logger = new MailHistoryLogger();
$mailer = new Mailer($transport, $logger);

// Emails are automatically logged when sent
$mailer->send($mailable);</code></pre>

    <p>Create the mail_log table:</p>

    <pre class="line-numbers"><code class="language-bash">php bin/larafony table:mail-log
php bin/larafony migrate</code></pre>

    <h2>SMTP Protocol Details</h2>

    <h3>Implemented Commands</h3>
    <ul>
        <li><strong>EHLO</strong> - Extended hello, establishes connection</li>
        <li><strong>AUTH LOGIN</strong> - Base64-encoded authentication</li>
        <li><strong>MAIL FROM</strong> - Specifies sender address</li>
        <li><strong>RCPT TO</strong> - Specifies recipient addresses (to, cc, bcc)</li>
        <li><strong>DATA</strong> - Begins message content</li>
        <li><strong>QUIT</strong> - Closes connection</li>
    </ul>

    <h3>Response Codes</h3>
    <ul>
        <li><strong>2xx</strong> - Success (250 OK, 220 Ready)</li>
        <li><strong>3xx</strong> - Intermediate (354 Start mail input)</li>
        <li><strong>4xx</strong> - Transient failure (421 Service not available)</li>
        <li><strong>5xx</strong> - Permanent failure (550 Mailbox unavailable)</li>
    </ul>

    <h3>Multi-line Response Handling</h3>

    <p>SMTP responses can span multiple lines. The implementation detects the last line by checking character at index 3:</p>

    <pre class="line-numbers"><code class="language-text">250-mail.example.com
250-SIZE 52428800
250-8BITMIME
250 HELP
    ^ Space indicates this is the final line</code></pre>

    <div class="alert-docs alert-info">
        <i class="bi bi-file-text me-2"></i>
        <strong>RFC Compliance:</strong> Our implementation follows RFC 5321 specification for SMTP protocol.
    </div>

    <h2>PHP 8.5 Features</h2>

    <h3>Asymmetric Visibility</h3>

    <p>Email and Envelope use <code>private(set)</code> for immutability:</p>

    <pre class="line-numbers"><code class="language-php">final class Email
{
    public function __construct(
        public private(set) ?Address $from = null,
        public private(set) array $to = [],
        public private(set) ?string $subject = null,
    ) {}

    // Immutable API using clone()
    public function from(Address $address): self
    {
        return clone($this, ['from' => $address]);
    }

    public function to(Address $address): self
    {
        return clone($this, ['to' => [...$this->to, $address]]);
    }
}</code></pre>

    <h3>Property Hooks</h3>

    <p>Smart defaults with property hooks:</p>

    <pre class="line-numbers"><code class="language-php">final class MailEncryption
{
    public bool $isSsl {
        get => $this->value === 'ssl';
    }

    public bool $isTls {
        get => $this->value === 'tls';
    }

    private function __construct(
        public private(set) string $value
    ) {}
}</code></pre>

    <div class="alert-docs alert-warning">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Important Caveat:</strong> Properties with <code>private(set)</code> cannot use reference-based operations like <code>array_walk()</code>. According to RFC Asymmetric Visibility v2, obtaining a reference follows <code>set</code> visibility, not <code>get</code> visibility. Use <code>foreach</code> for read-only iteration.
    </div>

    <h2>Architecture</h2>

    <h3>Value Objects</h3>
    <ul>
        <li><strong>Address</strong> - Email address with optional name</li>
        <li><strong>MailPort</strong> - Smart port selection based on encryption</li>
        <li><strong>MailEncryption</strong> - SSL, TLS, or none</li>
        <li><strong>MailUserInfo</strong> - Parses username:password from DSN</li>
        <li><strong>SmtpCommand</strong> - SMTP commands with validation</li>
        <li><strong>SmtpResponse</strong> - SMTP response parsing and validation</li>
    </ul>

    <h3>Contracts (Interfaces)</h3>
    <pre class="line-numbers"><code class="language-php">interface TransportContract
{
    public function send(Email $message): void;
}

interface MailerContract
{
    public function send(Mailable $mailable): void;
}

interface MailHistoryLoggerContract
{
    public function log(Email $message): void;
}</code></pre>

    <h3>Framework Integration</h3>
    <ul>
        <li><strong>UriManager</strong> - DSN parsing with scheme detection</li>
        <li><strong>Stream</strong> - Socket I/O from HTTP module</li>
        <li><strong>ViewManager</strong> - Email template rendering</li>
        <li><strong>Application Container</strong> - Dependency injection</li>
        <li><strong>DBAL Models</strong> - Email logging with ORM</li>
    </ul>

    <h2>Testing</h2>

    <h3>Using MailHog</h3>

    <pre class="line-numbers"><code class="language-bash"># Start MailHog with Docker
docker run -d -p 1025:1025 -p 8025:8025 mailhog/mailhog

# View sent emails at http://localhost:8025</code></pre>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Mail\MailerFactory;

$mailer = MailerFactory::createMailHogMailer();
$mailer->send(new WelcomeEmail('Test User', 'test@example.com'));

// Check http://localhost:8025 to see the email</code></pre>

    <h2>Future Enhancements</h2>

    <p>After PHP 8.5 GA release and base implementation completion:</p>
    <ul>
        <li><strong>Symfony Mailer Integration</strong> - Add <code>symfony/mailer</code> as optional transport</li>
        <li><strong>Additional Transports</strong> - AWS SES, SendGrid, Mailgun drivers</li>
        <li><strong>Attachments</strong> - File attachment support</li>
        <li><strong>Multipart</strong> - HTML/text multipart messages</li>
        <li><strong>Inline Images</strong> - Embedded image support</li>
        <li><strong>Queue Integration</strong> - Async email sending</li>
        <li><strong>Testing Utilities</strong> - Fake mailer for unit tests</li>
    </ul>

    <h2>Resources</h2>
    <ul>
        <li><a href="https://datatracker.ietf.org/doc/html/rfc5321" target="_blank">RFC 5321 - Simple Mail Transfer Protocol</a></li>
        <li><a href="https://datatracker.ietf.org/doc/html/rfc2045" target="_blank">RFC 2045 - MIME Format</a></li>
        <li><a href="https://wiki.php.net/rfc/property-hooks" target="_blank">PHP RFC: Property Hooks</a></li>
        <li><a href="https://wiki.php.net/rfc/asymmetric-visibility-v2" target="_blank">PHP RFC: Asymmetric Visibility v2</a></li>
        <li><a href="https://github.com/mailhog/MailHog" target="_blank">MailHog - Email testing tool</a></li>
    </ul>

    <h2>Learn More</h2>

    <p>
        This native SMTP implementation is explained in detail with RFC compliance, PHP 8.5 features, and production-ready patterns at
        <a href="https://masterphp.eu" target="_blank" class="text-decoration-none" style="color: #ec4899;">
            <strong>masterphp.eu</strong> <i class="bi bi-arrow-up-right-square"></i>
        </a>
    </p>

    <div class="alert-docs alert-success mt-4">
        <i class="bi bi-rocket-takeoff-fill me-2"></i>
        <strong>Zero Dependencies:</strong> Unlike other frameworks that rely on Symfony Mailer or SwiftMailer, Larafony implements SMTP from scratch using only PSR standards and PHP 8.5. This demonstrates complete framework transparency - you can read and understand every line of the mail system without diving into external libraries.
        <br><br>
        <strong>Coming Soon:</strong> Symfony Mailer integration will be added as an optional transport after PHP 8.5 GA, giving you the choice between native implementation and battle-tested external solutions.
        <br><br>
        <a href="https://packagist.org/packages/larafony/core" target="_blank" class="btn btn-sm btn-success me-2">
            <i class="bi bi-box-seam me-1"></i> View on Packagist
        </a>
        <a href="https://github.com/larafony/framework" target="_blank" class="btn btn-sm btn-outline-light">
            <i class="bi bi-github me-1"></i> View on GitHub
        </a>
    </div>
</x-docs-layout>
