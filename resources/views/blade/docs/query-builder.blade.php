<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Query Builder</h1>
    <p class="lead text-white-50">
        Build and execute SQL queries with Larafony's fluent, type-safe Query Builder.
    </p>

    <div class="alert-docs alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>SQL Injection Protection:</strong> All queries use prepared statements with automatic parameter binding.
    </div>

    <h2>Overview</h2>
    <p>
        The Query Builder provides an expressive, chainable API for constructing SQL queries programmatically.
        Key features include:
    </p>
    <ul>
        <li><strong>Fluent Interface</strong> - Chain methods to build complex queries</li>
        <li><strong>SQL Injection Protection</strong> - Automatic parameter binding</li>
        <li><strong>Type Safety</strong> - PHP 8.5 enums for query types and directions</li>
        <li><strong>Database Agnostic</strong> - Works across MySQL, PostgreSQL, SQLite</li>
    </ul>

    <h2>Basic SELECT Queries</h2>

    <h3>Simple SELECT</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Database\DatabaseManager;

$db = app(DatabaseManager::class);

// Select all columns
$users = $db->table('users')->get();

// Select specific columns
$users = $db->table('users')
    ->select(['id', 'name', 'email'])
    ->get();</code></pre>

    <h3>Get Single Record</h3>

    <pre class="line-numbers"><code class="language-php">// Get first matching record
$user = $db->table('users')
    ->where('email', '=', 'john@example.com')
    ->first();  // Returns array or null</code></pre>

    <h2>WHERE Clauses</h2>

    <h3>Basic WHERE</h3>

    <pre class="line-numbers"><code class="language-php">// Simple where conditions
$users = $db->table('users')
    ->where('status', '=', 'active')
    ->where('age', '>', 18)
    ->get();
// SQL: SELECT * FROM users WHERE status = ? AND age > ?</code></pre>

    <h3>OR WHERE</h3>

    <pre class="line-numbers"><code class="language-php">$users = $db->table('users')
    ->where('status', '=', 'active')
    ->orWhere('verified', '=', true)
    ->get();
// SQL: SELECT * FROM users WHERE status = ? OR verified = ?</code></pre>

    <h3>WHERE IN</h3>

    <pre class="line-numbers"><code class="language-php">$users = $db->table('users')
    ->whereIn('id', [1, 2, 3, 4, 5])
    ->get();
// SQL: SELECT * FROM users WHERE id IN (?, ?, ?, ?, ?)</code></pre>

    <h3>WHERE NOT IN</h3>

    <pre class="line-numbers"><code class="language-php">$users = $db->table('users')
    ->whereNotIn('status', ['banned', 'deleted'])
    ->get();</code></pre>

    <h3>WHERE NULL / NOT NULL</h3>

    <pre class="line-numbers"><code class="language-php">// Where column is NULL
$users = $db->table('users')
    ->whereNull('deleted_at')
    ->get();

// Where column is NOT NULL
$users = $db->table('users')
    ->whereNotNull('email_verified_at')
    ->get();</code></pre>

    <h3>WHERE BETWEEN</h3>

    <pre class="line-numbers"><code class="language-php">$users = $db->table('users')
    ->whereBetween('age', [18, 65])
    ->get();
// SQL: SELECT * FROM users WHERE age BETWEEN ? AND ?</code></pre>

    <h3>WHERE LIKE</h3>

    <pre class="line-numbers"><code class="language-php">$users = $db->table('users')
    ->whereLike('name', '%John%')
    ->get();
// SQL: SELECT * FROM users WHERE name LIKE ?</code></pre>

    <h3>Nested WHERE (Grouping)</h3>

    <pre class="line-numbers"><code class="language-php">$users = $db->table('users')
    ->where('status', '=', 'active')
    ->whereNested(function ($query) {
        $query->where('age', '>', 18)
              ->orWhere('verified', '=', true);
    }, 'and')
    ->get();
// SQL: SELECT * FROM users
//      WHERE status = ? AND (age > ? OR verified = ?)</code></pre>

    <h2>JOINs</h2>

    <h3>LEFT JOIN</h3>

    <pre class="line-numbers"><code class="language-php">$results = $db->table('users')
    ->select(['users.*', 'profiles.bio', 'profiles.avatar'])
    ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
    ->where('users.status', '=', 'active')
    ->get();</code></pre>

    <h3>INNER JOIN</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Database\Base\Query\Enums\JoinType;

$results = $db->table('orders')
    ->select(['orders.*', 'users.name'])
    ->join('users', 'orders.user_id', '=', 'users.id', JoinType::INNER)
    ->get();</code></pre>

    <h3>Multiple JOINs</h3>

    <pre class="line-numbers"><code class="language-php">$results = $db->table('orders')
    ->select(['orders.*', 'users.name', 'products.title'])
    ->leftJoin('users', 'orders.user_id', '=', 'users.id')
    ->leftJoin('products', 'orders.product_id', '=', 'products.id')
    ->get();</code></pre>

    <h2>Ordering and Limiting</h2>

    <h3>ORDER BY</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Database\Base\Query\Enums\OrderDirection;

// Order by single column
$users = $db->table('users')
    ->orderBy('name', OrderDirection::ASC)
    ->get();

// Multiple order clauses
$users = $db->table('users')
    ->orderBy('created_at', OrderDirection::DESC)
    ->orderBy('name', OrderDirection::ASC)
    ->get();</code></pre>

    <h3>Convenience Methods</h3>

    <pre class="line-numbers"><code class="language-php">// Order by created_at DESC
$users = $db->table('users')->latest()->get();

// Order by created_at ASC
$users = $db->table('users')->oldest()->get();

// Custom column
$users = $db->table('users')->latest('updated_at')->get();</code></pre>

    <h3>LIMIT and OFFSET</h3>

    <pre class="line-numbers"><code class="language-php">// Pagination - page 2, 20 per page
$users = $db->table('users')
    ->limit(20)
    ->offset(20)
    ->get();

// First 10 records
$users = $db->table('users')
    ->limit(10)
    ->get();</code></pre>

    <h2>Aggregates</h2>

    <h3>COUNT</h3>

    <pre class="line-numbers"><code class="language-php">// Count all records
$total = $db->table('users')->count();

// Count with conditions
$activeUsers = $db->table('users')
    ->where('status', '=', 'active')
    ->count();</code></pre>

    <h2>INSERT Operations</h2>

    <h3>Insert Single Record</h3>

    <pre class="line-numbers"><code class="language-php">$success = $db->table('users')->insert([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'status' => 'active',
    'created_at' => date('Y-m-d H:i:s')
]);
// Returns: true on success</code></pre>

    <h3>Insert and Get ID</h3>

    <pre class="line-numbers"><code class="language-php">$userId = $db->table('users')->insertGetId([
    'name' => 'Jane Smith',
    'email' => 'jane@example.com'
]);
// Returns: last insert ID as string</code></pre>

    <h2>UPDATE Operations</h2>

    <pre class="line-numbers"><code class="language-php">// Update records matching conditions
$affectedRows = $db->table('users')
    ->where('id', '=', 1)
    ->update([
        'status' => 'inactive',
        'updated_at' => date('Y-m-d H:i:s')
    ]);
// Returns: number of affected rows

// Update multiple records
$affectedRows = $db->table('users')
    ->where('status', '=', 'pending')
    ->whereNull('email_verified_at')
    ->update(['status' => 'inactive']);</code></pre>

    <h2>DELETE Operations</h2>

    <pre class="line-numbers"><code class="language-php">// Delete specific record
$deletedRows = $db->table('users')
    ->where('id', '=', 1)
    ->delete();

// Delete with multiple conditions
$deletedRows = $db->table('users')
    ->where('status', '=', 'inactive')
    ->whereNull('last_login_at')
    ->delete();
// Returns: number of deleted rows</code></pre>

    <h2>Query Debugging</h2>

    <h3>Inspect SQL</h3>

    <pre class="line-numbers"><code class="language-php">// Get SQL with placeholders
$sql = $db->table('users')
    ->where('status', '=', 'active')
    ->toSql();
// Returns: "SELECT * FROM users WHERE status = ?"

// Get SQL with actual values (safe for debugging!)
$rawSql = $db->table('users')
    ->where('status', '=', 'active')
    ->toRawSql();
// Returns: "SELECT * FROM users WHERE status = 'active'"</code></pre>

    <div class="alert-docs alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Safe Debugging:</strong> The <code>toRawSql()</code> method is inspired by <a href="https://github.com/laravel/framework/pull/47507" target="_blank" class="text-white">Laravel PR #47507</a> and uses proper SQL escaping to safely display queries with their actual values for debugging purposes.
    </div>

    <h3>How toRawSql() Works</h3>

    <p>
        Unlike simple string concatenation, <code>toRawSql()</code> properly escapes values to prevent SQL injection
        when displaying queries for debugging:
    </p>

    <pre class="line-numbers"><code class="language-php">// Complex query with multiple types
$rawSql = $db->table('users')
    ->where('name', '=', "O'Brien")  // String with quote
    ->where('age', '>', 18)           // Integer
    ->whereNull('deleted_at')         // NULL
    ->whereBetween('score', [10, 100])
    ->toRawSql();

// Safely escaped output:
// SELECT * FROM users
// WHERE name = 'O\'Brien'
// AND age > 18
// AND deleted_at IS NULL
// AND score BETWEEN 10 AND 100</code></pre>

    <h3>Best Use Cases for toRawSql()</h3>

    <ul>
        <li><strong>Debugging slow queries</strong> - Copy the output to MySQL Workbench or phpMyAdmin</li>
        <li><strong>Logging queries</strong> - Log the complete SQL for troubleshooting</li>
        <li><strong>Development</strong> - Understand what SQL is being generated</li>
        <li><strong>Testing</strong> - Verify query structure in unit tests</li>
    </ul>

    <pre class="line-numbers"><code class="language-php">// Example: Log slow queries
$startTime = microtime(true);
$results = $query->get();
$duration = microtime(true) - $startTime;

if ($duration > 1.0) {
    Log::warning('Slow query detected', [
        'sql' => $query->toRawSql(),
        'duration' => $duration
    ]);
}</code></pre>

    <div class="alert-docs alert-warning">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Important:</strong> While <code>toRawSql()</code> safely escapes values for display, never use it to execute queries. Always use the query builder's <code>get()</code>, <code>insert()</code>, <code>update()</code>, or <code>delete()</code> methods which use prepared statements for true SQL injection protection.
    </div>

    <h2>Complex Query Examples</h2>

    <h3>Example 1: Active Users with Orders</h3>

    <pre class="line-numbers"><code class="language-php">$results = $db->table('users')
    ->select(['users.id', 'users.name', 'COUNT(orders.id) as order_count'])
    ->leftJoin('orders', 'users.id', '=', 'orders.user_id')
    ->where('users.status', '=', 'active')
    ->whereNotNull('users.email_verified_at')
    ->orderBy('order_count', OrderDirection::DESC)
    ->limit(10)
    ->get();</code></pre>

    <h3>Example 2: Product Search</h3>

    <pre class="line-numbers"><code class="language-php">$products = $db->table('products')
    ->select(['products.*', 'categories.name as category_name'])
    ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
    ->where('products.is_active', '=', true)
    ->whereNested(function ($query) use ($searchTerm) {
        $query->whereLike('products.name', "%{$searchTerm}%")
              ->orWhereLike('products.description', "%{$searchTerm}%");
    }, 'and')
    ->whereBetween('products.price', [10, 100])
    ->orderBy('products.created_at', OrderDirection::DESC)
    ->limit(20)
    ->get();</code></pre>

    <h3>Example 3: User Activity Report</h3>

    <pre class="line-numbers"><code class="language-php">$report = $db->table('users')
    ->select([
        'users.id',
        'users.name',
        'users.email',
        'COUNT(DISTINCT orders.id) as total_orders',
        'SUM(orders.total) as total_spent'
    ])
    ->leftJoin('orders', 'users.id', '=', 'orders.user_id')
    ->where('users.created_at', '>=', '2024-01-01')
    ->whereNotNull('users.email_verified_at')
    ->orderBy('total_spent', OrderDirection::DESC)
    ->limit(50)
    ->get();</code></pre>

    <h2>Best Practices</h2>

    <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem; margin: 1.5rem 0;">
        <h4><i class="bi bi-check-circle text-success me-2"></i>Do</h4>
        <ul class="mb-0">
            <li>Always use query builder methods instead of raw SQL</li>
            <li>Add appropriate indexes for columns used in WHERE and JOIN clauses</li>
            <li>Use <code>first()</code> when you only need one record</li>
            <li>Use <code>select()</code> to limit columns and reduce data transfer</li>
            <li>Use <code>count()</code> instead of <code>get()</code> when you only need the count</li>
        </ul>
    </div>

    <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 0.75rem; padding: 1.5rem; margin: 1.5rem 0;">
        <h4><i class="bi bi-x-circle text-danger me-2"></i>Don't</h4>
        <ul class="mb-0">
            <li>Don't use <code>toRawSql()</code> for production queries</li>
            <li>Don't build WHERE clauses with string concatenation</li>
            <li>Don't forget to add WHERE clauses to UPDATE and DELETE operations</li>
            <li>Don't use SELECT * in production - specify columns</li>
        </ul>
    </div>

    <h2>API Reference</h2>

    <table class="table table-dark table-bordered">
        <thead>
            <tr>
                <th>Method</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>table(string $table)</code></td>
                <td>Set the table for query</td>
            </tr>
            <tr>
                <td><code>select(array $columns)</code></td>
                <td>Specify columns to select</td>
            </tr>
            <tr>
                <td><code>where($column, $operator, $value)</code></td>
                <td>Add WHERE clause</td>
            </tr>
            <tr>
                <td><code>orWhere($column, $operator, $value)</code></td>
                <td>Add OR WHERE clause</td>
            </tr>
            <tr>
                <td><code>whereIn($column, array $values)</code></td>
                <td>WHERE IN clause</td>
            </tr>
            <tr>
                <td><code>whereNull(string $column)</code></td>
                <td>WHERE column IS NULL</td>
            </tr>
            <tr>
                <td><code>join($table, $first, $op, $second)</code></td>
                <td>Add JOIN clause</td>
            </tr>
            <tr>
                <td><code>orderBy($column, $direction)</code></td>
                <td>Add ORDER BY clause</td>
            </tr>
            <tr>
                <td><code>limit(int $value)</code></td>
                <td>Add LIMIT clause</td>
            </tr>
            <tr>
                <td><code>get()</code></td>
                <td>Execute and get results</td>
            </tr>
            <tr>
                <td><code>first()</code></td>
                <td>Get first result or null</td>
            </tr>
            <tr>
                <td><code>count()</code></td>
                <td>Count matching records</td>
            </tr>
            <tr>
                <td><code>insert(array $values)</code></td>
                <td>Insert record</td>
            </tr>
            <tr>
                <td><code>update(array $values)</code></td>
                <td>Update records</td>
            </tr>
            <tr>
                <td><code>delete()</code></td>
                <td>Delete records</td>
            </tr>
        </tbody>
    </table>

    <h2>Next Steps</h2>
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-database me-2 text-primary"></i>ORM Models</h4>
                <p class="text-white-50 mb-3">Learn about Active Record models with attribute-based relationships.</p>
                <a href="/docs/models" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-table me-2 text-primary"></i>Schema Builder</h4>
                <p class="text-white-50 mb-3">Create and modify database schemas with migrations.</p>
                <a href="/docs/schema-builder" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</x-docs-layout>
