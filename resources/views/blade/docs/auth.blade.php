<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Authorization - Roles & Permissions</h1>
    <p class="lead text-white-50">
        Built-in Role-Based Access Control (RBAC) system for fine-grained authorization
    </p>

    <div class="alert-docs alert-info">
        <i class="bi bi-shield-check-fill me-2"></i>
        <strong>Zero Dependencies:</strong> Complete RBAC implementation built into the framework core—no external packages required. Users have Roles, Roles have Permissions.
    </div>

    <h2>Overview</h2>
    <p>
        Larafony's authorization system provides:
    </p>
    <ul>
        <li><strong>Role-Based Access Control</strong> - Users → Roles → Permissions hierarchy</li>
        <li><strong>ORM Integration</strong> - BelongsToMany relationships with property hooks</li>
        <li><strong>Facade Pattern</strong> - Convenient <code>Auth</code> static class for global access</li>
        <li><strong>Flexible Checks</strong> - Single, any, or all role/permission verification</li>
        <li><strong>Database Driven</strong> - Roles and permissions stored in database tables</li>
        <li><strong>Type-Safe</strong> - Full PHP 8.5 type hints and property hooks</li>
    </ul>

    <h2>Database Structure</h2>

    <p>The authorization system uses four tables:</p>

    <pre class="line-numbers"><code class="language-sql">-- Roles table
CREATE TABLE roles (
    id BIGINT UNSIGNED PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    description VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Permissions table
CREATE TABLE permissions (
    id BIGINT UNSIGNED PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    description VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Role-Permission pivot
CREATE TABLE role_permissions (
    id BIGINT UNSIGNED PRIMARY KEY,
    role_id BIGINT UNSIGNED NOT NULL,
    permission_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(role_id, permission_id),
    INDEX(role_id),
    INDEX(permission_id)
);

-- User-Role pivot
CREATE TABLE user_roles (
    id BIGINT UNSIGNED PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    role_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, role_id),
    INDEX(user_id),
    INDEX(role_id)
);</code></pre>

    <div class="alert-docs alert-success">
        <i class="bi bi-terminal me-2"></i>
        <strong>Setup Commands:</strong> Run <code>php bin/console database:init</code> to automatically create all auth tables.
    </div>

    <h2>Creating Roles & Permissions</h2>

    <h3>Define Permissions</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Database\ORM\Entities\Permission;

// Create granular permissions
$createNotes = new Permission();
$createNotes->name = 'notes.create';
$createNotes->description = 'Can create notes';
$createNotes->save();

$editNotes = new Permission();
$editNotes->name = 'notes.edit';
$editNotes->description = 'Can edit notes';
$editNotes->save();

$deleteNotes = new Permission();
$deleteNotes->name = 'notes.delete';
$deleteNotes->description = 'Can delete notes';
$deleteNotes->save();</code></pre>

    <h3>Create Roles with Permissions</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Database\ORM\Entities\Role;

// Create admin role
$adminRole = new Role();
$adminRole->name = 'admin';
$adminRole->description = 'Administrator with full access';
$adminRole->save();

// Attach all permissions to admin role
$adminRole->relations->getRelationInstance('permissions')
    ->attach([
        $createNotes->id,
        $editNotes->id,
        $deleteNotes->id
    ]);

// Create editor role with limited permissions
$editorRole = new Role();
$editorRole->name = 'editor';
$editorRole->description = 'Can create and edit content';
$editorRole->save();

$editorRole->relations->getRelationInstance('permissions')
    ->attach([
        $createNotes->id,
        $editNotes->id
        // No delete permission
    ]);</code></pre>

    <h3>Assign Roles to Users</h3>

    <pre class="line-numbers"><code class="language-php">use App\Models\User;

// Fetch user
$user = User::query()->where('email', '=', 'john@example.com')->first();

// Add role (prevents duplicates automatically)
$user->addRole($adminRole);

// User now has all permissions from admin role</code></pre>

    <h2>Checking Permissions</h2>

    <h3>In Controllers</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Auth\Auth;
use Larafony\Framework\Web\Controller;
use Psr\Http\Message\ResponseInterface;

class NoteController extends Controller
{
    public function create(): ResponseInterface
    {
        // Check if user has specific permission
        if (!Auth::hasPermission('notes.create')) {
            return $this->json([
                'message' => 'Forbidden',
                'errors' => ['permission' => [
                    'You do not have permission to create notes.'
                ]]
            ], 403);
        }

        // User has permission, proceed
        return $this->render('notes.create');
    }

    public function delete(int $id): ResponseInterface
    {
        // Check if user has ANY of these permissions
        if (!Auth::hasAnyPermission(['notes.delete', 'admin.all'])) {
            return $this->json(['message' => 'Forbidden'], 403);
        }

        // Delete the note
        Note::query()->where('id', '=', $id)->delete();

        return $this->json(['message' => 'Note deleted']);
    }
}</code></pre>

    <h3>Checking Roles</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Auth\Auth;

// Check single role
if (Auth::hasRole('admin')) {
    // User is an admin
}

// Check if user has ANY of these roles
if (Auth::hasAnyRole(['admin', 'moderator'])) {
    // User is either admin OR moderator
}

// Check if user has ALL these roles
if (Auth::hasAllRoles(['admin', 'super-user'])) {
    // User has BOTH admin AND super-user roles
}</code></pre>

    <h3>Multiple Permission Checks</h3>

    <pre class="line-numbers"><code class="language-php">// Check if user has ALL specified permissions
if (Auth::hasAllPermissions(['notes.create', 'notes.edit', 'notes.delete'])) {
    // User has full CRUD permissions
}

// Check if user has ANY specified permission
if (Auth::hasAnyPermission(['notes.edit', 'notes.delete'])) {
    // User can modify or delete notes
}</code></pre>

    <h2>Direct Model Usage</h2>

    <h3>User Model</h3>

    <pre class="line-numbers"><code class="language-php">$user = Auth::user();

// Check roles directly on user
if ($user->hasRole('admin')) {
    echo "User is an admin";
}

// Check permissions (checks through all user's roles)
if ($user->hasPermission('notes.delete')) {
    echo "User can delete notes";
}

// Access all user's roles (PHP 8.5 property hooks)
foreach ($user->roles as $role) {
    echo $role->name . "\n";

    // Check if role has specific permission
    if ($role->hasPermission('notes.create')) {
        echo "Role can create notes\n";
    }
}</code></pre>

    <h3>Role Model</h3>

    <pre class="line-numbers"><code class="language-php">$role = Role::query()->where('name', '=', 'editor')->first();

// Check if role has permission
if ($role->hasPermission('posts.create')) {
    echo "Editors can create posts";
}

// Access all permissions for this role
foreach ($role->permissions as $permission) {
    echo $permission->name . "\n";
}

// Access all users with this role
foreach ($role->users as $user) {
    echo $user->email . " is an editor\n";
}</code></pre>

    <h2>Architecture Components</h2>

    <h3>Auth Facade</h3>

    <p>The <code>Auth</code> class provides unified access to authentication and authorization:</p>

    <pre class="line-numbers"><code class="language-php">namespace Larafony\Framework\Auth;

final class Auth
{
    // Authentication methods
    public static function attempt(User $user, string $password, bool $remember = false): bool
    public static function login(User $user, bool $remember = false): void
    public static function logout(): void
    public static function user(): ?User
    public static function check(): bool
    public static function guest(): bool
    public static function id(): int|string|null

    // Role authorization methods
    public static function hasRole(string $role): bool
    public static function hasAnyRole(array $roles): bool
    public static function hasAllRoles(array $roles): bool

    // Permission authorization methods
    public static function hasPermission(string $permission): bool
    public static function hasAnyPermission(array $permissions): bool
    public static function hasAllPermissions(array $permissions): bool
}</code></pre>

    <h3>RoleManager</h3>

    <p>Handles role-based authorization checks:</p>

    <pre class="line-numbers"><code class="language-php">namespace Larafony\Framework\Auth;

final readonly class RoleManager
{
    public function __construct(private UserManager $userManager) {}

    public function hasRole(string $role): bool
    {
        return $this->userManager->check()
            && $this->userManager->user()?->hasRole($role);
    }

    public function hasAnyRole(array $roles): bool
    public function hasAllRoles(array $roles): bool
}</code></pre>

    <h3>PermissionManager</h3>

    <p>Handles permission-based authorization checks:</p>

    <pre class="line-numbers"><code class="language-php">namespace Larafony\Framework\Auth;

final readonly class PermissionManager
{
    public function __construct(private UserManager $userManager) {}

    public function hasPermission(string $permission): bool
    {
        return $this->userManager->check()
            && $this->userManager->user()?->hasPermission($permission);
    }

    public function hasAnyPermission(array $permissions): bool
    public function hasAllPermissions(array $permissions): bool
}</code></pre>

    <h2>ORM Entities</h2>

    <h3>User Entity</h3>

    <pre class="line-numbers"><code class="language-php">namespace Larafony\Framework\Database\ORM\Entities;

use Larafony\Framework\Database\ORM\Attributes\BelongsToMany;
use Larafony\Framework\Database\ORM\Model;

class User extends Model
{
    // Many-to-many relationship to roles
    #[BelongsToMany(Role::class, 'user_roles', 'user_id', 'role_id')]
    public array $roles {
        get => $this->relations->getRelation('roles');
    }

    // Add role to user (prevents duplicates)
    public function addRole(Role $role): void
    {
        if ($this->hasRole($role->name)) {
            return;
        }
        $this->relations->getRelationInstance('roles')
            ->attach([$role->id]);
    }

    // Check if user has role by name
    public function hasRole(string $roleName): bool
    {
        return array_any(
            $this->roles,
            static fn(Role $role) => $role->name === $roleName
        );
    }

    // Check if user has permission through roles
    public function hasPermission(string $permissionName): bool
    {
        return array_any(
            $this->roles,
            static fn(Role $role) => $role->hasPermission($permissionName)
        );
    }
}</code></pre>

    <h3>Role Entity</h3>

    <pre class="line-numbers"><code class="language-php">namespace Larafony\Framework\Database\ORM\Entities;

use Larafony\Framework\Database\ORM\Attributes\BelongsToMany;
use Larafony\Framework\Database\ORM\Model;

class Role extends Model
{
    public string $name { get => $this->name; set { /* ... */ } }
    public ?string $description { get => $this->description; set { /* ... */ } }

    // Many-to-many to permissions
    #[BelongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id')]
    public array $permissions {
        get => $this->relations->getRelation('permissions');
    }

    // Many-to-many to users
    #[BelongsToMany(User::class, 'user_roles', 'role_id', 'user_id')]
    public array $users {
        get => $this->relations->getRelation('users');
    }

    // Check if role has specific permission
    public function hasPermission(string $permissionName): bool
    {
        return in_array(
            $permissionName,
            array_column($this->permissions, 'name')
        );
    }
}</code></pre>

    <h3>Permission Entity</h3>

    <pre class="line-numbers"><code class="language-php">namespace Larafony\Framework\Database\ORM\Entities;

use Larafony\Framework\Database\ORM\Attributes\BelongsToMany;
use Larafony\Framework\Database\ORM\Model;

class Permission extends Model
{
    public string $name { get => $this->name; set { /* ... */ } }
    public ?string $description { get => $this->description; set { /* ... */ } }

    // Inverse many-to-many to roles
    #[BelongsToMany(Role::class, 'role_permissions', 'permission_id', 'role_id')]
    public array $roles {
        get => $this->relations->getRelation('roles');
    }
}</code></pre>

    <h2>Permission Naming Convention</h2>

    <p>Use dot notation for hierarchical permission structure:</p>

    <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem; margin: 1.5rem 0;">
        <p><strong>Format:</strong> <code>resource.action</code></p>
        <ul class="mb-0">
            <li><code>notes.create</code> - Can create notes</li>
            <li><code>notes.edit</code> - Can edit notes</li>
            <li><code>notes.delete</code> - Can delete notes</li>
            <li><code>users.manage</code> - Can manage users</li>
            <li><code>admin.all</code> - Full admin access</li>
        </ul>
    </div>

    <h2>Security Best Practices</h2>

    <div class="alert-docs alert-warning">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Always Check Authentication First:</strong> Permission checks assume user is authenticated. Always verify with <code>Auth::check()</code> before checking permissions.
    </div>

    <pre class="line-numbers"><code class="language-php">// ✅ CORRECT - Check authentication first
if (!Auth::check()) {
    return $this->json(['message' => 'Unauthorized'], 401);
}

if (!Auth::hasPermission('notes.create')) {
    return $this->json(['message' => 'Forbidden'], 403);
}

// ❌ INCORRECT - Permission check on unauthenticated user
if (!Auth::hasPermission('notes.create')) {
    // This will return false for guests, but it's unclear why
}</code></pre>

    <h3>HTTP Status Codes</h3>

    <ul>
        <li><strong>401 Unauthorized</strong> - User is not authenticated (not logged in)</li>
        <li><strong>403 Forbidden</strong> - User is authenticated but lacks permission</li>
    </ul>

    <h2>Comparison with Other Frameworks</h2>

    <div style="overflow-x: auto;">
        <table class="table table-dark table-bordered" style="margin: 2rem 0;">
            <thead>
                <tr>
                    <th>Feature</th>
                    <th>Larafony</th>
                    <th>Laravel + Spatie</th>
                    <th>Symfony</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Integration</strong></td>
                    <td>Built into core</td>
                    <td>External package required</td>
                    <td>Built-in voters</td>
                </tr>
                <tr>
                    <td><strong>User-Permission</strong></td>
                    <td>Only through roles (pure RBAC)</td>
                    <td>Direct + through roles</td>
                    <td>Through voters</td>
                </tr>
                <tr>
                    <td><strong>API Style</strong></td>
                    <td><code>Auth::hasPermission('x')</code></td>
                    <td><code>$user->can('x')</code></td>
                    <td><code>isGranted('x')</code></td>
                </tr>
                <tr>
                    <td><strong>Tables</strong></td>
                    <td>4 tables</td>
                    <td>5 tables</td>
                    <td>Configurable</td>
                </tr>
                <tr>
                    <td><strong>Wildcards</strong></td>
                    <td>Not supported</td>
                    <td>Supported (<code>posts.*</code>)</td>
                    <td>Custom voters</td>
                </tr>
                <tr>
                    <td><strong>Caching</strong></td>
                    <td>Manual</td>
                    <td>Built-in</td>
                    <td>Built-in</td>
                </tr>
            </tbody>
        </table>
    </div>

    <h2>Real World Example</h2>

    <p>Complete authorization flow from the demo application:</p>

    <pre class="line-numbers"><code class="language-php">// 1. Create permissions in seeder
$createNotePermission = new Permission();
$createNotePermission->name = 'notes.create';
$createNotePermission->description = 'Can create notes';
$createNotePermission->save();

// 2. Create role
$adminRole = new Role();
$adminRole->name = 'admin';
$adminRole->description = 'Administrator role';
$adminRole->save();

// 3. Attach permission to role (not in demo, but should be)
$adminRole->relations->getRelationInstance('permissions')
    ->attach([$createNotePermission->id]);

// 4. Assign role to user
$user = new User();
$user->email = 'admin@example.com';
$user->password = 'password'; // Auto-hashed with Argon2id
$user->save();
$user->addRole($adminRole);

// 5. Check permission in controller
class NoteController extends Controller
{
    public function store(CreateNoteDto $dto): ResponseInterface
    {
        if (!Auth::check()) {
            return $this->json(['message' => 'Unauthorized'], 401);
        }

        if (!Auth::hasPermission('notes.create')) {
            return $this->json(['message' => 'Forbidden'], 403);
        }

        // Create note...
        $note = new Note()->fill([
            'title' => $dto->title,
            'content' => $dto->content,
            'user_id' => Auth::user()->id,
        ]);
        $note->save();

        return $this->redirect('/notes');
    }
}</code></pre>

    <div class="alert-docs alert-success">
        <i class="bi bi-lightbulb-fill me-2"></i>
        <strong>Pro Tip:</strong> Create seeders for roles and permissions in your application to ensure consistent authorization setup across environments.
    </div>

</x-docs-layout>
