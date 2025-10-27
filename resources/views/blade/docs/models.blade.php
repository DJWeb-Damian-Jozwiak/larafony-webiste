<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Models & Relationships</h1>
    <p class="lead text-white-50">
        Create ORM models with attribute-based relationships using PHP 8.5
    </p>

    <h2>Creating a Model</h2>
    <p>
        Models in Larafony extend the <code>Model</code> base class and use PHP 8.5 property hooks for clean, type-safe data access.
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

declare(strict_types=1);

namespace App\Models;

use Larafony\Framework\Database\ORM\Model;

class User extends Model
{
    public string $table { get => 'users'; }

    public array $fillable = ['name', 'email'];

    public ?string $name {
        get => $this->name ?? null;
        set {
            $this->name = $value;
            $this->markPropertyAsChanged('name');
        }
    }

    public ?string $email {
        get => $this->email ?? null;
        set {
            $this->email = $value;
            $this->markPropertyAsChanged('email');
        }
    }
}</code></pre>

    <div class="alert-docs alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Property Hooks:</strong> PHP 8.5 property hooks allow you to define getter and setter logic directly on properties.
        Call <code>markPropertyAsChanged()</code> in setters to track changes for saving.
    </div>

    <h2>BelongsTo Relationship</h2>
    <p>
        A <code>BelongsTo</code> relationship defines that a model belongs to another model.
        For example, a <code>Note</code> belongs to a <code>User</code>.
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

namespace App\Models;

use Larafony\Framework\Database\ORM\Model;
use Larafony\Framework\Database\ORM\Attributes\BelongsTo;

class Note extends Model
{
    public string $table { get => 'notes'; }

    public array $fillable = ['title', 'content', 'user_id'];

    // BelongsTo: Note belongs to User
    #[BelongsTo(
        related: User::class,
        foreign_key: 'user_id',
        local_key: 'id'
    )]
    public ?User $user {
        get => $this->relations->getRelation('user');
    }
}</code></pre>

    <p>
        Access the relationship like a property:
    </p>

    <pre class="line-numbers"><code class="language-php">$note = Note::query()->find(1);
echo $note->user->name; // Lazy-loaded automatically</code></pre>

    <h2>HasMany Relationship</h2>
    <p>
        A <code>HasMany</code> relationship defines that a model has many related models.
        For example, a <code>User</code> has many <code>Note</code>s.
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

namespace App\Models;

use Larafony\Framework\Database\ORM\Model;
use Larafony\Framework\Database\ORM\Attributes\HasMany;

class User extends Model
{
    public string $table { get => 'users'; }

    // HasMany: User has many Notes
    #[HasMany(
        related: Note::class,
        foreign_key: 'user_id',
        local_key: 'id'
    )]
    public array $notes {
        get => $this->relations->getRelation('notes');
    }
}</code></pre>

    <p>
        Access the collection:
    </p>

    <pre class="line-numbers"><code class="language-php">$user = User::query()->find(1);

foreach ($user->notes as $note) {
    echo $note->title;
}</code></pre>

    <h2>BelongsToMany Relationship</h2>
    <p>
        A <code>BelongsToMany</code> relationship defines a many-to-many relationship through a pivot table.
        For example, a <code>Note</code> belongs to many <code>Tag</code>s, and a <code>Tag</code> belongs to many <code>Note</code>s.
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

namespace App\Models;

use Larafony\Framework\Database\ORM\Model;
use Larafony\Framework\Database\ORM\Attributes\BelongsToMany;

class Note extends Model
{
    public string $table { get => 'notes'; }

    // BelongsToMany: Note belongs to many Tags
    #[BelongsToMany(
        related: Tag::class,
        pivot_table: 'note_tag',
        foreign_pivot_key: 'note_id',
        related_pivot_key: 'tag_id'
    )]
    public array $tags {
        get => $this->relations->getRelation('tags');
    }

    /**
     * Attach tags to this note
     */
    public function attachTags(array $tagIds): void
    {
        $relation = $this->relations->getRelationInstance('tags');
        $relation->attach($tagIds);
    }

    /**
     * Detach tags from this note
     */
    public function detachTags(array $tagIds): void
    {
        $relation = $this->relations->getRelationInstance('tags');
        $relation->detach($tagIds);
    }
}</code></pre>

    <p>
        Working with many-to-many relationships:
    </p>

    <pre class="line-numbers"><code class="language-php">$note = Note::query()->find(1);

// Access tags
foreach ($note->tags as $tag) {
    echo $tag->name;
}

// Attach new tags
$note->attachTags([1, 2, 3]);

// Detach tags
$note->detachTags([2]);</code></pre>

    <h2>Complete Example</h2>
    <p>
        Here's a complete model with all three relationship types:
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

declare(strict_types=1);

namespace App\Models;

use Larafony\Framework\Database\ORM\Model;
use Larafony\Framework\Database\ORM\Attributes\{
    BelongsTo,
    HasMany,
    BelongsToMany
};

class Note extends Model
{
    public string $table { get => 'notes'; }

    public array $fillable = ['title', 'content', 'user_id'];

    public ?string $title {
        get => $this->title ?? null;
        set {
            $this->title = $value;
            $this->markPropertyAsChanged('title');
        }
    }

    public ?string $content {
        get => $this->content ?? null;
        set {
            $this->content = $value;
            $this->markPropertyAsChanged('content');
        }
    }

    public ?int $user_id {
        get => $this->user_id ?? null;
        set {
            $this->user_id = $value;
            $this->markPropertyAsChanged('user_id');
        }
    }

    // BelongsTo: Note belongs to User
    #[BelongsTo(
        related: User::class,
        foreign_key: 'user_id',
        local_key: 'id'
    )]
    public ?User $user {
        get => $this->relations->getRelation('user');
    }

    // HasMany: Note has many Comments
    #[HasMany(
        related: Comment::class,
        foreign_key: 'note_id',
        local_key: 'id'
    )]
    public array $comments {
        get => $this->relations->getRelation('comments');
    }

    // BelongsToMany: Note belongs to many Tags
    #[BelongsToMany(
        related: Tag::class,
        pivot_table: 'note_tag',
        foreign_pivot_key: 'note_id',
        related_pivot_key: 'tag_id'
    )]
    public array $tags {
        get => $this->relations->getRelation('tags');
    }

    public function attachTags(array $tagIds): void
    {
        $relation = $this->relations->getRelationInstance('tags');
        $relation->attach($tagIds);
    }
}</code></pre>

    <h2>Query Builder</h2>
    <p>
        Access the query builder through the static <code>query()</code> method:
    </p>

    <pre class="line-numbers"><code class="language-php">// Find by ID
$note = Note::query()->find(1);

// Get all records
$notes = Note::query()->get();

// Where clause
$notes = Note::query()
    ->where('user_id', '=', 1)
    ->get();

// Order and limit
$notes = Note::query()
    ->where('published', '=', true)
    ->orderBy('created_at', 'DESC')
    ->limit(10)
    ->get();

// First record
$note = Note::query()
    ->where('slug', '=', 'hello-world')
    ->first();</code></pre>

    <h2>Creating and Updating</h2>
    <p>
        Use the <code>fill()</code> method for mass assignment:
    </p>

    <pre class="line-numbers"><code class="language-php">// Create new record
$note = new Note()->fill([
    'title' => 'My Note',
    'content' => 'Note content here',
    'user_id' => 1,
]);
$note->save();

// Update existing record
$note = Note::query()->find(1);
$note->title = 'Updated Title';
$note->save();</code></pre>


    <h2>Next Steps</h2>
    <ul>
        <li><a href="/docs/controllers">Learn how to use models in controllers →</a></li>
        <li><a href="/docs/validation">Learn about DTO validation →</a></li>
    </ul>
</x-docs-layout>
