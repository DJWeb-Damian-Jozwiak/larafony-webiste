<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Schema Builder & Migrations</h1>
    <p class="lead text-white-50">
        Build and modify database schemas with Larafony's fluent Schema Builder and migration system.
    </p>

    <div class="alert-docs alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>SQL Transparency:</strong> Schema Builder returns SQL strings for inspection before execution - you're always in control.
    </div>

    <h2>Overview</h2>
    <p>
        The Schema Builder provides an expressive, database-agnostic API for creating and modifying database schemas.
        Key features include:
    </p>
    <ul>
        <li><strong>Fluent API</strong> - Chain methods to define tables and columns</li>
        <li><strong>Type-safe</strong> - PHP 8.5 enums and property hooks ensure correctness</li>
        <li><strong>SQL Inspection</strong> - See generated SQL before execution</li>
        <li><strong>Migration System</strong> - Version control your database schema</li>
        <li><strong>Pipe Operator</strong> - Modern PHP 8.5 syntax for cleaner code</li>
    </ul>

    <h2>Basic Table Creation</h2>

    <h3>Simple Table</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Database\Schema;

// Create a users table
$sql = Schema::create('users', function ($table) {
    $table->id();                    // Auto-increment primary key
    $table->string('name');          // VARCHAR(255)
    $table->string('email');
    $table->timestamps();            // created_at, updated_at
});

// Inspect the SQL
echo $sql;

// Execute when ready
Schema::execute($sql);</code></pre>

    <h3>Column Types</h3>

    <pre class="line-numbers"><code class="language-php">$sql = Schema::create('products', function ($table) {
    // Primary key
    $table->id();

    // String types
    $table->string('name', 200);         // VARCHAR(200)
    $table->text('description');         // TEXT
    $table->char('code', 10);           // CHAR(10)

    // Numeric types
    $table->integer('stock');           // INT(11)
    $table->bigInteger('views');        // BIGINT
    $table->decimal('price', 8, 2);     // DECIMAL(8,2)
    $table->float('rating');            // FLOAT

    // Date and time
    $table->date('released_at');        // DATE
    $table->datetime('expires_at');     // DATETIME
    $table->timestamp('verified_at');   // TIMESTAMP
    $table->timestamps();               // created_at, updated_at

    // Boolean
    $table->boolean('is_active');       // TINYINT(1)

    // Enum
    $table->enum('status', ['draft', 'published', 'archived']);

    // JSON
    $table->json('metadata');           // JSON
});</code></pre>

    <h3>Column Modifiers</h3>

    <pre class="line-numbers"><code class="language-php">$sql = Schema::create('orders', function ($table) {
    $table->id();

    // Nullable columns
    $table->string('notes')->nullable(true);

    // Non-nullable (default)
    $table->string('customer_name')->nullable(false);

    // Default values
    $table->integer('status')->default(1);
    $table->boolean('is_paid')->default(false);
    $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');

    // Unique constraint
    $table->string('order_number')->nullable(false)->unique();

    // Chaining modifiers
    $table->string('email')
        ->nullable(false)
        ->unique();
});</code></pre>

    <h2>Indexes</h2>

    <h3>Adding Indexes</h3>

    <pre class="line-numbers"><code class="language-php">$sql = Schema::create('posts', function ($table) {
    $table->id();
    $table->string('title');
    $table->string('slug');
    $table->integer('user_id');
    $table->integer('category_id');
    $table->timestamp('published_at')->nullable(true);

    // Primary key (automatic with id())
    // $table->id() adds PRIMARY KEY automatically

    // Unique index
    $table->unique('slug');

    // Regular index (single column)
    $table->index('user_id');

    // Composite index (multiple columns)
    $table->index(['published_at', 'category_id']);

    // Named index
    $table->index('user_id', 'idx_posts_user');
});</code></pre>

    <h2>Table Modifications</h2>

    <h3>Adding Columns</h3>

    <pre class="line-numbers"><code class="language-php">// Add new columns to existing table
$sql = Schema::table('users', function ($table) {
    $table->string('phone', 20)->nullable(true);
    $table->date('birth_date')->nullable(true);
    $table->integer('status')->default(1);
});

Schema::execute($sql);</code></pre>

    <h3>Modifying Columns</h3>

    <pre class="line-numbers"><code class="language-php">// Change column properties
$sql = Schema::table('users', function ($table) {
    // Make column non-nullable
    $table->change('email')->nullable(false);

    // Change default value
    $table->change('status')->default(2);
});

Schema::execute($sql);</code></pre>

    <h3>Dropping Columns</h3>

    <pre class="line-numbers"><code class="language-php">// Remove columns from table
$sql = Schema::table('users', function ($table) {
    $table->drop('phone');
    $table->drop('status');
});

Schema::execute($sql);</code></pre>

    <h2>Migrations with Pipe Operator</h2>

    <h3>Migration Structure</h3>

    <p>
        Migrations are stored in <code>database/migrations/</code> with numeric prefixes for ordering:
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php
// database/migrations/001_create_users_table.php

declare(strict_types=1);

use Larafony\Framework\Database\Base\Migrations\Migration;
use Larafony\Framework\Database\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $sql = Schema::create('users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->unique('email');
            $table->timestamps();
        });

        Schema::execute($sql);
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};</code></pre>

    <h3>Using Pipe Operator in Migrations</h3>

    <p>
        PHP 8.5's pipe operator (<code>|&gt;</code>) allows elegant functional composition in migrations:
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php
// database/migrations/002_create_posts_table.php

use Larafony\Framework\Database\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Traditional approach
        $sql = Schema::create('posts', function ($table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->integer('user_id');
            $table->timestamps();
        });
        Schema::execute($sql);

        // With pipe operator - cleaner composition
        Schema::create('posts', function ($table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->integer('user_id');
            $table->timestamps();
        }) |> Schema::execute(...);
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};</code></pre>

    <h3>Advanced Pipe Operator Usage</h3>

    <pre class="line-numbers"><code class="language-php">// Chain multiple operations with pipe operator
Schema::create('comments', fn($t) =>
    $t->id(),
    $t->integer('post_id'),
    $t->text('content'),
    $t->timestamps()
)
|> Schema::execute(...)
|> fn() => Schema::create('comment_votes', fn($t) =>
    $t->id(),
    $t->integer('comment_id'),
    $t->integer('user_id'),
    $t->enum('type', ['up', 'down'])
)
|> Schema::execute(...);</code></pre>

    <div class="alert-docs alert-success">
        <i class="bi bi-lightbulb-fill me-2"></i>
        <strong>Tip:</strong> The pipe operator (<code>|&gt;</code>) passes the result of the left expression as the first argument to the right expression, enabling functional-style programming in PHP 8.5.
    </div>

    <h2>Running Migrations</h2>

    <h3>Console Command</h3>

    <pre class="line-numbers"><code class="language-bash"># Run all pending migrations
php bin/larafony migrate

# Rollback last migration batch
php bin/larafony migrate:rollback

# Reset database (rollback all)
php bin/larafony migrate:reset

# Refresh database (reset + migrate)
php bin/larafony migrate:refresh</code></pre>

    <h2>Practical Examples</h2>

    <h3>Example 1: Blog Schema</h3>

    <pre class="line-numbers"><code class="language-php">// Create posts table
Schema::create('posts', function ($table) {
    $table->id();
    $table->integer('user_id')->nullable(false);
    $table->integer('category_id')->nullable(true);
    $table->string('title', 200)->nullable(false);
    $table->string('slug')->nullable(false);
    $table->text('content');
    $table->text('excerpt')->nullable(true);
    $table->string('featured_image')->nullable(true);
    $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
    $table->timestamp('published_at')->nullable(true);
    $table->integer('views')->default(0);
    $table->timestamps();
    $table->softDeletes(); // deleted_at timestamp

    // Indexes
    $table->unique('slug');
    $table->index('user_id');
    $table->index('category_id');
    $table->index(['status', 'published_at']);
}) |> Schema::execute(...);</code></pre>

    <h3>Example 2: E-commerce Schema</h3>

    <pre class="line-numbers"><code class="language-php">// Products table
Schema::create('products', function ($table) {
    $table->id();
    $table->string('sku', 50)->nullable(false);
    $table->string('name');
    $table->text('description')->nullable(true);
    $table->decimal('price', 10, 2)->nullable(false);
    $table->decimal('compare_price', 10, 2)->nullable(true);
    $table->integer('stock')->default(0);
    $table->boolean('is_active')->default(true);
    $table->json('attributes')->nullable(true); // Color, size, etc.
    $table->timestamps();

    $table->unique('sku');
    $table->index('is_active');
}) |> Schema::execute(...);

// Orders table
Schema::create('orders', function ($table) {
    $table->id();
    $table->string('order_number', 20)->nullable(false);
    $table->integer('user_id')->nullable(false);
    $table->decimal('total', 10, 2)->nullable(false);
    $table->enum('status', ['pending', 'paid', 'shipped', 'delivered', 'cancelled'])
        ->default('pending');
    $table->timestamp('paid_at')->nullable(true);
    $table->timestamp('shipped_at')->nullable(true);
    $table->timestamp('delivered_at')->nullable(true);
    $table->timestamps();

    $table->unique('order_number');
    $table->index('user_id');
    $table->index(['status', 'created_at']);
}) |> Schema::execute(...);</code></pre>

    <h3>Example 3: Pivot Table (Many-to-Many)</h3>

    <pre class="line-numbers"><code class="language-php">// Note-Tag pivot table
Schema::create('note_tag', function ($table) {
    $table->id();
    $table->integer('note_id')->nullable(false);
    $table->integer('tag_id')->nullable(false);
    $table->timestamps();

    // Composite index for relationship queries
    $table->index(['note_id', 'tag_id']);

    // Prevent duplicate relationships
    $table->unique(['note_id', 'tag_id']);
}) |> Schema::execute(...);</code></pre>

    <h2>Best Practices</h2>

    <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem; margin: 1.5rem 0;">
        <h4><i class="bi bi-check-circle text-success me-2"></i>Do</h4>
        <ul class="mb-0">
            <li>Always inspect generated SQL before executing in production</li>
            <li>Use the pipe operator for cleaner migration code</li>
            <li>Add indexes on foreign key columns</li>
            <li>Use meaningful migration file names with numeric prefixes</li>
            <li>Implement both up() and down() methods</li>
            <li>Test migrations on a copy of production data</li>
        </ul>
    </div>

    <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 0.75rem; padding: 1.5rem; margin: 1.5rem 0;">
        <h4><i class="bi bi-x-circle text-danger me-2"></i>Don't</h4>
        <ul class="mb-0">
            <li>Don't modify published migrations - create new ones instead</li>
            <li>Don't forget to add indexes on frequently queried columns</li>
            <li>Don't use nullable() on primary or foreign keys</li>
            <li>Don't execute migrations directly in production without testing</li>
        </ul>
    </div>

    <h2>API Reference</h2>

    <h3>Schema Facade Methods</h3>

    <table class="table table-dark table-bordered">
        <thead>
            <tr>
                <th>Method</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>Schema::create(string $table, Closure $callback)</code></td>
                <td>Create a new table</td>
            </tr>
            <tr>
                <td><code>Schema::table(string $table, Closure $callback)</code></td>
                <td>Modify existing table</td>
            </tr>
            <tr>
                <td><code>Schema::drop(string $table)</code></td>
                <td>Drop a table</td>
            </tr>
            <tr>
                <td><code>Schema::dropIfExists(string $table)</code></td>
                <td>Drop table if it exists</td>
            </tr>
            <tr>
                <td><code>Schema::execute(string $sql)</code></td>
                <td>Execute SQL statement</td>
            </tr>
            <tr>
                <td><code>Schema::hasTable(string $table)</code></td>
                <td>Check if table exists</td>
            </tr>
            <tr>
                <td><code>Schema::getColumnListing(string $table)</code></td>
                <td>Get list of columns</td>
            </tr>
        </tbody>
    </table>

    <h2>Next Steps</h2>
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-search me-2 text-primary"></i>Query Builder</h4>
                <p class="text-white-50 mb-3">Learn how to query your database with the fluent Query Builder.</p>
                <a href="/docs/query-builder" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-database me-2 text-primary"></i>ORM Models</h4>
                <p class="text-white-50 mb-3">Explore the Active Record ORM with attribute-based relationships.</p>
                <a href="/docs/models" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</x-docs-layout>
