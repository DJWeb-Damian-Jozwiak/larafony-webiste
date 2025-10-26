<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">DTO Validation</h1>
    <p class="lead text-white-50">
        Type-safe validation using PHP 8.5 attributes and property hooks
    </p>

    <h2>What are DTOs?</h2>
    <p>
        Data Transfer Objects (DTOs) in Larafony are classes that validate and transform incoming request data.
        They use PHP 8.5 features like attributes, property hooks, and asymmetric visibility for clean, type-safe validation.
    </p>

    <h2>Creating a DTO</h2>
    <p>
        Extend <code>FormRequest</code> and add properties with validation attributes:
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

declare(strict_types=1);

namespace App\DTOs;

use Larafony\Framework\Validation\FormRequest;
use Larafony\Framework\Validation\Attributes\IsValidated;
use Larafony\Framework\Validation\Attributes\MinLength;

class CreateNoteDto extends FormRequest
{
    #[IsValidated]
    #[MinLength(3)]
    public protected(set) string $title;

    #[IsValidated]
    #[MinLength(10)]
    public protected(set) string $content;
}</code></pre>

    <div class="alert-docs alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Asymmetric Visibility:</strong> <code>public protected(set)</code> means the property can be read publicly
        but only set within the class. This protects your data from external modification.
    </div>

    <h2>Using DTOs in Controllers</h2>
    <p>
        Type-hint the DTO in your controller method. Validation happens automatically:
    </p>

    <pre class="line-numbers"><code class="language-php">use App\DTOs\CreateNoteDto;

#[Route('/notes', 'POST')]
public function store(CreateNoteDto $dto): ResponseInterface
{
    // If we reach here, validation passed!

    $note = new Note()->fill([
        'title' => $dto->title,
        'content' => $dto->content,
    ]);
    $note->save();

    return $this->redirect('/notes');
}</code></pre>

    <p>
        If validation fails, an exception is thrown automatically. You can catch and handle it in your error handler.
    </p>

    <h2>Validation Attributes (13 Total)</h2>
    <p>
        Larafony provides 13 powerful validation attributes covering basic constraints, advanced conditional logic,
        and custom validation with PHP 8.5 closures.
    </p>

    <h3>Marker Attribute</h3>

    <h4>#[IsValidated]</h4>
    <p>Marks a property for auto-population from request data. Required on all validated properties:</p>
    <pre class="line-numbers"><code class="language-php">#[IsValidated]
public protected(set) string $title;</code></pre>

    <h3>Basic Constraints</h3>

    <h4>#[Required]</h4>
    <p>Field must not be null:</p>
    <pre class="line-numbers"><code class="language-php">#[IsValidated]
#[Required]
public protected(set) ?string $username;</code></pre>

    <h4>#[Email]</h4>
    <p>Validates email format using <code>filter_var</code>:</p>
    <pre class="line-numbers"><code class="language-php">#[IsValidated]
#[Required]
#[Email]
public protected(set) ?string $email;</code></pre>

    <h4>#[Min] / #[Max]</h4>
    <p>Numeric range validation:</p>
    <pre class="line-numbers"><code class="language-php">#[IsValidated]
#[Min(18)]
#[Max(120)]
public protected(set) ?int $age;</code></pre>

    <h4>#[MinLength] / #[MaxLength] / #[Length]</h4>
    <p>String length validation:</p>
    <pre class="line-numbers"><code class="language-php">#[IsValidated]
#[MinLength(3)]
public protected(set) string $username;

#[IsValidated]
#[MaxLength(255)]
public protected(set) string $bio;

#[IsValidated]
#[Length(min: 8, max: 32)]
public protected(set) string $password;</code></pre>

    <h4>#[StartsWith] / #[EndsWith]</h4>
    <p>String pattern matching:</p>
    <pre class="line-numbers"><code class="language-php">#[IsValidated]
#[StartsWith('https://')]
public protected(set) string $website;

#[IsValidated]
#[EndsWith('.com')]
public protected(set) string $domain;</code></pre>

    <h3>Advanced Conditional Validation (PHP 8.5 Closures)</h3>

    <h4>#[RequiredWhen(Closure)]</h4>
    <p>Field required when closure returns true. Uses PHP 8.5 closures in attributes:</p>
    <pre class="line-numbers"><code class="language-php">#[IsValidated]
#[RequiredWhen(fn(array $data) => $data['type'] === 'business')]
public protected(set) ?string $companyName;

// Multiple conditions
#[IsValidated]
#[RequiredWhen(fn(array $data) =>
    $data['country'] === 'US' && $data['state'] !== null
)]
public protected(set) ?string $zipCode;</code></pre>

    <h4>#[RequiredUnless(Closure)]</h4>
    <p>Field required unless closure returns true (inverse of RequiredWhen):</p>
    <pre class="line-numbers"><code class="language-php">#[IsValidated]
#[RequiredUnless(fn(array $data) => !empty($data['phone']))]
public protected(set) ?string $alternativeContact;

// Email required unless social login is used
#[IsValidated]
#[RequiredUnless(fn(array $data) => !empty($data['social_provider']))]
public protected(set) ?string $email;</code></pre>

    <h4>#[ValidWhen(Closure, message)]</h4>
    <p>Custom validation logic with closures. The closure receives the value and all data:</p>
    <pre class="line-numbers"><code class="language-php">#[IsValidated]
#[ValidWhen(
    fn(mixed $value, array $data) => $value === $data['password'],
    message: 'Passwords must match'
)]
public protected(set) string $password_confirmation;

// Age validation based on account type
#[IsValidated]
#[ValidWhen(
    fn(mixed $value, array $data) =>
        $data['type'] !== 'business' || ($value !== null && $value >= 18),
    message: 'Must be 18+ for business accounts'
)]
public protected(set) ?int $age;</code></pre>

    <h4>#[Confirmed]</h4>
    <p>Field confirmation matching. Looks for <code>{field}_confirmation</code>:</p>
    <pre class="line-numbers"><code class="language-php">#[IsValidated]
#[Required]
#[MinLength(8)]
#[Confirmed]
public protected(set) string $password;

#[IsValidated]
public protected(set) string $password_confirmation;</code></pre>

    <h3>PHP 8.5 First-Class Callables</h3>
    <p>
        Use first-class callable syntax (<code>self::method(...)</code>) for cleaner validation:
    </p>

    <pre class="line-numbers"><code class="language-php">class InvoiceRequest extends FormRequest
{
    #[IsValidated]
    #[Required]
    public protected(set) string $invoiceType; // 'standard' or 'proforma'

    // Using first-class callable syntax (PHP 8.5)
    #[IsValidated]
    #[RequiredWhen(self::isStandardInvoice(...))]
    public protected(set) ?string $paymentMethod;

    #[IsValidated]
    #[ValidWhen(self::validInvoiceNumber(...), 'Invalid invoice format')]
    public protected(set) ?string $invoiceNumber;

    private static function isStandardInvoice(array $data): bool
    {
        return $data['invoiceType'] === 'standard';
    }

    private static function validInvoiceNumber(mixed $value, array $data): bool
    {
        if ($data['invoiceType'] === 'standard') {
            return preg_match('/^INV-\d{4}-\d{4}$/', $value) === 1;
        }

        return preg_match('/^PRO-\d{4}-\d{4}$/', $value) === 1;
    }
}</code></pre>

    <div class="alert-docs alert-success">
        <i class="bi bi-lightbulb-fill me-2"></i>
        <strong>PHP 8.5 Magic:</strong> First-class callables (<code>self::method(...)</code>) provide clean,
        refactorable method references. The <code>(...)</code> syntax creates a closure from the method
        without verbose anonymous functions!
    </div>

    <h2>Property Hooks for Transformation</h2>
    <p>
        Use property hooks to transform data automatically:
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

namespace App\DTOs;

use Larafony\Framework\Validation\FormRequest;
use Larafony\Framework\Validation\Attributes\IsValidated;

class CreateNoteDto extends FormRequest
{
    #[IsValidated]
    public protected(set) string $title;

    #[IsValidated]
    public protected(set) string $content;

    // Transform comma-separated string to array
    #[IsValidated]
    public protected(set) string|array|null $tags {
        get {
            if (!isset($this->tags)) {
                return null;
            }
            if (is_array($this->tags)) {
                return $this->tags;
            }

            // Transform "php, framework, laravel" to ["php", "framework", "laravel"]
            return array_map('trim', explode(',', $this->tags));
        }
        set => $this->tags = $value;
    }
}</code></pre>

    <p>
        Now when you access <code>$dto->tags</code>, you always get an array, even if the input was a string!
    </p>

    <h2>Optional Properties</h2>
    <p>
        Make properties optional by using nullable types:
    </p>

    <pre class="line-numbers"><code class="language-php">#[IsValidated]
public protected(set) ?string $description;

#[IsValidated]
public protected(set) string|null $notes;</code></pre>

    <h2>Complete Real-World Example</h2>
    <p>
        Here's a complete business registration DTO showcasing all validation features:
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

declare(strict_types=1);

namespace App\DTOs;

use Larafony\Framework\Validation\FormRequest;
use Larafony\Framework\Validation\Attributes\{
    IsValidated,
    Required,
    Email,
    MinLength,
    MaxLength,
    Length,
    Min,
    Max,
    StartsWith,
    RequiredWhen,
    RequiredUnless,
    ValidWhen,
    Confirmed
};

class BusinessRegistrationDto extends FormRequest
{
    // Basic validation
    #[IsValidated]
    #[Required]
    public protected(set) string $type; // 'personal' or 'business'

    // Email validation
    #[IsValidated]
    #[Required]
    #[Email]
    public protected(set) string $email;

    // String length validation
    #[IsValidated]
    #[Required]
    #[MinLength(3)]
    #[MaxLength(50)]
    public protected(set) string $name;

    // Numeric range validation
    #[IsValidated]
    #[Required]
    #[Min(18)]
    #[Max(120)]
    public protected(set) int $age;

    // Conditional validation - business fields
    #[IsValidated]
    #[RequiredWhen(fn(array $data) => $data['type'] === 'business')]
    #[MinLength(2)]
    public protected(set) ?string $companyName;

    #[IsValidated]
    #[RequiredWhen(fn(array $data) => $data['type'] === 'business')]
    #[Length(min: 9, max: 11)]
    public protected(set) ?string $taxId;

    // RequiredUnless - need email OR phone
    #[IsValidated]
    #[RequiredUnless(fn(array $data) => !empty($data['phone']))]
    public protected(set) ?string $alternativeEmail;

    // String pattern validation
    #[IsValidated]
    #[StartsWith('https://')]
    public protected(set) ?string $website;

    // Password with confirmation
    #[IsValidated]
    #[Required]
    #[MinLength(8)]
    #[Confirmed]
    public protected(set) string $password;

    #[IsValidated]
    public protected(set) string $password_confirmation;

    // Custom validation with closure
    #[IsValidated]
    #[ValidWhen(
        fn(mixed $value, array $data) =>
            $data['type'] !== 'business' || ($value !== null && $value >= 18),
        message: 'Business accounts require age 18+'
    )]
    public protected(set) ?int $businessAge;

    // Property hook for transformation
    #[IsValidated]
    public protected(set) string|array|null $interests {
        get {
            if (!isset($this->interests)) {
                return null;
            }
            if (is_array($this->interests)) {
                return $this->interests;
            }
            return array_map('trim', explode(',', $this->interests));
        }
        set => $this->interests = $value;
    }
}</code></pre>

    <div class="alert-docs alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>All 13 Attributes in Action:</strong> This example demonstrates:
        <ul class="mt-2 mb-0">
            <li><code>#[IsValidated]</code> - Marker for all properties</li>
            <li><code>#[Required]</code>, <code>#[Email]</code> - Basic constraints</li>
            <li><code>#[MinLength]</code>, <code>#[MaxLength]</code>, <code>#[Length]</code> - String length</li>
            <li><code>#[Min]</code>, <code>#[Max]</code> - Numeric ranges</li>
            <li><code>#[StartsWith]</code> - Pattern matching</li>
            <li><code>#[RequiredWhen]</code>, <code>#[RequiredUnless]</code> - Conditional requirements</li>
            <li><code>#[ValidWhen]</code> - Custom validation logic</li>
            <li><code>#[Confirmed]</code> - Field confirmation</li>
            <li>Property hooks - Data transformation</li>
        </ul>
    </div>

    <h2>Using the DTO</h2>
    <pre class="line-numbers"><code class="language-php">#[Route('/users', 'POST')]
public function store(CreateUserDto $dto): ResponseInterface
{
    $user = new User()->fill([
        'name' => $dto->name,
        'email' => $dto->email,
        'password' => password_hash($dto->password, PASSWORD_DEFAULT),
        'bio' => $dto->bio,
    ]);
    $user->save();

    // Handle interests (array of strings)
    if ($dto->interests) {
        foreach ($dto->interests as $interest) {
            // Process each interest
        }
    }

    return $this->redirect('/users');
}</code></pre>

    <h2>Form Example</h2>
    <p>
        HTML form that works with the DTO:
    </p>

    <pre class="line-numbers"><code class="language-html">&lt;form method="POST" action="/notes"&gt;
    &lt;div&gt;
        &lt;label&gt;Title&lt;/label&gt;
        &lt;input type="text" name="title" required&gt;
    &lt;/div&gt;

    &lt;div&gt;
        &lt;label&gt;Content&lt;/label&gt;
        &lt;textarea name="content" required&gt;&lt;/textarea&gt;
    &lt;/div&gt;

    &lt;div&gt;
        &lt;label&gt;Tags (comma-separated)&lt;/label&gt;
        &lt;input type="text" name="tags" placeholder="php, framework, tutorial"&gt;
    &lt;/div&gt;

    &lt;button type="submit"&gt;Create Note&lt;/button&gt;
&lt;/form&gt;</code></pre>

    <div class="alert-docs alert-success">
        <i class="bi bi-lightbulb-fill me-2"></i>
        <strong>Tip:</strong> DTO property names must match form field names exactly.
        Use <code>name="title"</code> for a DTO property called <code>$title</code>.
    </div>

    <h2>Quick Reference: All 13 Validation Attributes</h2>
    <div class="table-responsive">
        <table class="table table-dark table-bordered">
            <thead>
                <tr>
                    <th>Attribute</th>
                    <th>Description</th>
                    <th>Example</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>#[IsValidated]</code></td>
                    <td>Marker for auto-population (required)</td>
                    <td><code>#[IsValidated]</code></td>
                </tr>
                <tr>
                    <td><code>#[Required]</code></td>
                    <td>Field must not be null</td>
                    <td><code>#[Required]</code></td>
                </tr>
                <tr>
                    <td><code>#[Email]</code></td>
                    <td>Validates email format</td>
                    <td><code>#[Email]</code></td>
                </tr>
                <tr>
                    <td><code>#[Min]</code></td>
                    <td>Minimum numeric value</td>
                    <td><code>#[Min(18)]</code></td>
                </tr>
                <tr>
                    <td><code>#[Max]</code></td>
                    <td>Maximum numeric value</td>
                    <td><code>#[Max(120)]</code></td>
                </tr>
                <tr>
                    <td><code>#[MinLength]</code></td>
                    <td>Minimum string length</td>
                    <td><code>#[MinLength(3)]</code></td>
                </tr>
                <tr>
                    <td><code>#[MaxLength]</code></td>
                    <td>Maximum string length</td>
                    <td><code>#[MaxLength(255)]</code></td>
                </tr>
                <tr>
                    <td><code>#[Length]</code></td>
                    <td>String length range</td>
                    <td><code>#[Length(8, 32)]</code></td>
                </tr>
                <tr>
                    <td><code>#[StartsWith]</code></td>
                    <td>String must start with prefix</td>
                    <td><code>#[StartsWith('https://')]</code></td>
                </tr>
                <tr>
                    <td><code>#[EndsWith]</code></td>
                    <td>String must end with suffix</td>
                    <td><code>#[EndsWith('.com')]</code></td>
                </tr>
                <tr>
                    <td><code>#[RequiredWhen]</code></td>
                    <td>Required when closure returns true</td>
                    <td><code>#[RequiredWhen(fn($d) => $d['type'] === 'business')]</code></td>
                </tr>
                <tr>
                    <td><code>#[RequiredUnless]</code></td>
                    <td>Required unless closure returns true</td>
                    <td><code>#[RequiredUnless(fn($d) => !empty($d['phone']))]</code></td>
                </tr>
                <tr>
                    <td><code>#[ValidWhen]</code></td>
                    <td>Custom validation with closure</td>
                    <td><code>#[ValidWhen(fn($v, $d) => $v === $d['password'], 'Must match')]</code></td>
                </tr>
                <tr>
                    <td><code>#[Confirmed]</code></td>
                    <td>Field must match {field}_confirmation</td>
                    <td><code>#[Confirmed]</code></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="alert-docs alert-warning mt-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>PHP 8.5 Required:</strong> Features like closures in attributes (<code>#[RequiredWhen(fn...)]</code>)
        and first-class callables (<code>self::method(...)</code>) require PHP 8.5+. These cutting-edge features
        make Larafony's validation system more powerful than production frameworks limited to PHP 8.1.
    </div>

    <h2>Next Steps</h2>
    <ul>
        <li><a href="/docs/controllers">Learn about using DTOs in controllers →</a></li>
        <li><a href="/docs/models">Learn about saving validated data to models →</a></li>
    </ul>
</x-docs-layout>
