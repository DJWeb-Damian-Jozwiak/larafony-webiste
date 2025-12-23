<x-bridges-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Symfony Mailer Bridge</h1>
    <p class="lead text-white-50">
        Send emails with Symfony Mailer - SMTP, Amazon SES, Mailgun, SendGrid, and more.
    </p>

    <h2>Installation</h2>
    <pre class="line-numbers"><code class="language-bash">composer require larafony/mail-symfony

# Optional transports
composer require symfony/amazon-mailer   # Amazon SES
composer require symfony/mailgun-mailer  # Mailgun
composer require symfony/sendgrid-mailer # SendGrid
composer require symfony/postmark-mailer # Postmark</code></pre>

    <h2>Configuration</h2>
    <p>Register the service provider in your <code>bootstrap.php</code>:</p>
    <pre class="line-numbers"><code class="language-php">use Larafony\Mail\Symfony\ServiceProviders\SymfonyMailerServiceProvider;

$app->withServiceProviders([
    SymfonyMailerServiceProvider::class
]);</code></pre>

    <p>Create <code>config/mail.php</code>:</p>
    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Config\Environment\EnvReader;

return [
    'default' => EnvReader::read('MAIL_MAILER', 'smtp'),

    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => EnvReader::read('MAIL_HOST', 'localhost'),
            'port' => EnvReader::read('MAIL_PORT', 587),
            'encryption' => EnvReader::read('MAIL_ENCRYPTION', 'tls'),
            'username' => EnvReader::read('MAIL_USERNAME'),
            'password' => EnvReader::read('MAIL_PASSWORD'),
        ],

        'ses' => [
            'transport' => 'ses',
            'region' => EnvReader::read('AWS_DEFAULT_REGION'),
        ],

        'mailgun' => [
            'transport' => 'mailgun',
            'domain' => EnvReader::read('MAILGUN_DOMAIN'),
            'secret' => EnvReader::read('MAILGUN_SECRET'),
        ],
    ],

    'from' => [
        'address' => EnvReader::read('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => EnvReader::read('MAIL_FROM_NAME', 'Larafony'),
    ],
];</code></pre>

    <h2>Using Mailable Classes</h2>
    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Mail\Mailable;
use Larafony\Framework\Mail\Envelope;
use Larafony\Framework\Mail\Content;
use Larafony\Framework\Mail\Address;

final class WelcomeMail extends Mailable
{
    public function __construct(
        private readonly User $user,
        private readonly string $activationUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@example.com', 'Larafony App'),
            subject: 'Welcome to Larafony!',
        )->addTo(new Address($this->user->email, $this->user->name));
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
            data: [
                'user' => $this->user,
                'activationUrl' => $this->activationUrl,
            ],
        );
    }
}</code></pre>

    <h2>Sending Emails</h2>
    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Mail\Contracts\MailerContract;

final class AuthController extends Controller
{
    #[Route('/register', methods: ['POST'])]
    public function register(MailerContract $mailer, UserDto $dto): ResponseInterface
    {
        $user = User::create($dto->toArray());

        $mailer->send(new WelcomeMail($user, $activationUrl));

        return new ResponseFactory()->createJsonResponse(['registered' => true]);
    }
}</code></pre>

    <h2>With Attachments</h2>
    <pre class="line-numbers"><code class="language-php">use Larafony\Mail\Symfony\EmailConverter;
use Larafony\Mail\Symfony\Transport\SymfonyTransport;
use Symfony\Component\Mime\Email;

#[Route('/invoices/{id}/send', methods: ['POST'])]
public function sendInvoice(SymfonyTransport $transport, ViewManager $viewManager, int $id): ResponseInterface
{
    $invoice = Invoice::find($id);

    // Build email using Mailable (renders Blade view)
    $mailable = new InvoiceMail($invoice);
    $larafonyEmail = $mailable->withViewManager($viewManager)->build();

    // Convert to Symfony Email and add attachments
    $symfonyEmail = EmailConverter::toSymfony($larafonyEmail)
        ->attachFromPath(storage_path("invoices/{$invoice->number}.pdf"))
        ->priority(Email::PRIORITY_HIGH);

    $transport->sendSymfonyEmail($symfonyEmail);

    return new ResponseFactory()->createJsonResponse(['sent' => true]);
}</code></pre>

    <h2>Features</h2>
    <ul>
        <li><strong>Multiple transports</strong> - SMTP, Amazon SES, Mailgun, Postmark, SendGrid</li>
        <li><strong>Mailable classes</strong> - Declarative email building with Blade views</li>
        <li><strong>Attachments</strong> - Files, inline images, raw content</li>
        <li><strong>HTML & Text</strong> - Both formats in single email</li>
        <li><strong>EmailConverter</strong> - Convert Larafony Email to Symfony Email</li>
        <li><strong>DSN configuration</strong> - Simple transport configuration</li>
    </ul>
</x-bridges-layout>
