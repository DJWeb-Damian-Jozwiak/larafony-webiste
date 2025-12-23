<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Larafony\Framework\Console\Attributes\AsCommand;
use Larafony\Framework\Console\Command;

#[AsCommand(name: 'docs:index')]
class DocsIndexCommand extends Command
{
    protected string $description = 'Generate search index for documentation';

    private const DOCS_PATH = __DIR__ . '/../../../resources/views/blade/docs';
    private const BRIDGES_PATH = __DIR__ . '/../../../resources/views/blade/bridges';
    private const OUTPUT_PATH = __DIR__ . '/../../../public/search-index.json';

    public function run(): int
    {
        $this->output->writeln('');
        $this->output->info('Generating documentation search index...');
        $this->output->writeln('');

        $documents = $this->scanDocuments();

        if ($documents === []) {
            $this->output->warning('No documents found.');
            return 1;
        }

        $this->output->writeln(sprintf('Found %d documents', count($documents)));

        $index = [];
        foreach ($documents as $document) {
            $doc = $this->parseDocument($document['path'], $document['type']);
            if ($doc !== null) {
                $index[] = $doc;
                $this->output->writeln(sprintf('  ✓ %s', $doc['title']));
            }
        }

        $json = json_encode($index, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents(self::OUTPUT_PATH, $json);

        $this->output->writeln('');
        $this->output->success(sprintf(
            'Index generated: %d pages, %s',
            count($index),
            $this->formatBytes(strlen($json))
        ));
        $this->output->writeln('Output: public/search-index.json');
        $this->output->writeln('');

        return 0;
    }

    /**
     * @return array<int, array{path: string, type: string}>
     */
    private function scanDocuments(): array
    {
        $documents = [];

        $docsFiles = glob(self::DOCS_PATH . '/*.blade.php') ?: [];
        foreach ($docsFiles as $file) {
            $documents[] = ['path' => $file, 'type' => 'docs'];
        }

        $bridgesFiles = glob(self::BRIDGES_PATH . '/*.blade.php') ?: [];
        foreach ($bridgesFiles as $file) {
            $documents[] = ['path' => $file, 'type' => 'bridges'];
        }

        return $documents;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function parseDocument(string $file, string $type): ?array
    {
        $content = file_get_contents($file);
        if ($content === false) {
            return null;
        }

        $filename = basename($file, '.blade.php');

        // Generate URL based on type
        if ($type === 'bridges') {
            $url = $filename === 'index' ? '/bridges' : '/bridges/' . $filename;
        } else {
            $url = '/docs/' . $filename;

            // Handle special cases for docs
            if ($filename === 'index') {
                $url = '/docs';
            } elseif ($filename === 'console-debugging') {
                $url = '/docs/error-handling/console';
            } elseif ($filename === 'error-handling-web') {
                $url = '/docs/error-handling/web';
            }
        }

        // Extract title from <h1> tag
        $title = $this->extractTitle($content) ?? $this->slugToTitle($filename);

        // Extract headings for section search
        $headings = $this->extractHeadings($content);

        // Extract text content (remove HTML, PHP, code blocks)
        $text = $this->extractText($content);

        // Extract code keywords
        $codeKeywords = $this->extractCodeKeywords($content);

        return [
            'id' => $type . '-' . $filename,
            'url' => $url,
            'title' => $title,
            'headings' => $headings,
            'text' => $this->truncate($text, 500),
            'keywords' => $codeKeywords,
            'category' => $type === 'bridges' ? 'Bridges' : 'Documentation',
        ];
    }

    private function extractTitle(string $content): ?string
    {
        // Match <h1 class="gradient-text">Title</h1>
        if (preg_match('/<h1[^>]*>(.+?)<\/h1>/s', $content, $matches)) {
            return strip_tags($matches[1]);
        }
        return null;
    }

    /**
     * @return array<int, string>
     */
    private function extractHeadings(string $content): array
    {
        $headings = [];

        // Match h2 and h3 headings
        if (preg_match_all('/<h[23][^>]*>(.+?)<\/h[23]>/s', $content, $matches)) {
            foreach ($matches[1] as $heading) {
                $clean = trim(strip_tags($heading));
                if ($clean !== '' && strlen($clean) < 100) {
                    $headings[] = $clean;
                }
            }
        }

        return array_unique($headings);
    }

    private function extractText(string $content): string
    {
        // Remove code blocks
        $text = preg_replace('/<pre[^>]*>.*?<\/pre>/s', ' ', $content) ?? $content;
        $text = preg_replace('/<code[^>]*>.*?<\/code>/s', ' ', $text) ?? $text;

        // Remove PHP blocks
        $text = preg_replace('/<\?php.*?\?>/s', ' ', $text) ?? $text;

        // Remove Blade directives and expressions
        $text = preg_replace('/\{\{.*?\}\}/s', ' ', $text) ?? $text;
        $text = preg_replace('/\{!!.*?!!\}/s', ' ', $text) ?? $text;
        $text = preg_replace('/@\w+(\([^)]*\))?/', ' ', $text) ?? $text;

        // Remove HTML tags
        $text = strip_tags($text);

        // Clean up whitespace
        $text = preg_replace('/\s+/', ' ', $text) ?? $text;

        return trim($text);
    }

    /**
     * @return array<int, string>
     */
    private function extractCodeKeywords(string $content): array
    {
        $keywords = [];

        // Extract class names from code
        if (preg_match_all('/class\s+(\w+)/', $content, $matches)) {
            $keywords = array_merge($keywords, $matches[1]);
        }

        // Extract method names
        if (preg_match_all('/function\s+(\w+)/', $content, $matches)) {
            $keywords = array_merge($keywords, $matches[1]);
        }

        // Extract use statements
        if (preg_match_all('/use\s+[\w\\\\]+\\\\(\w+)/', $content, $matches)) {
            $keywords = array_merge($keywords, $matches[1]);
        }

        // Extract #[Attribute] names
        if (preg_match_all('/#\[(\w+)/', $content, $matches)) {
            $keywords = array_merge($keywords, $matches[1]);
        }

        // Extract common framework terms
        $frameworkTerms = [
            'Route', 'Controller', 'Middleware', 'Model', 'Migration',
            'Blade', 'Component', 'Container', 'PSR', 'Request', 'Response',
            'Query', 'Schema', 'ORM', 'Validation', 'DTO', 'Event',
            'Cache', 'Session', 'Cookie', 'Mail', 'Queue', 'Job',
            'WebSocket', 'Inertia', 'Vue', 'DebugBar',
        ];

        foreach ($frameworkTerms as $term) {
            if (stripos($content, $term) !== false) {
                $keywords[] = $term;
            }
        }

        return array_values(array_unique(array_filter($keywords)));
    }

    private function slugToTitle(string $slug): string
    {
        $title = str_replace('-', ' ', $slug);
        return ucwords($title);
    }

    private function truncate(string $text, int $length): string
    {
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length) . '...';
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }
        return round($bytes / 1024, 1) . ' KB';
    }
}
