<?php

declare(strict_types=1);

namespace App\Models;

use Larafony\Framework\Database\ORM\Model;

class ExternalLinkClick extends Model
{
    public string $table { get => 'external_link_clicks'; }

    public array $fillable = [
        'session_id',
        'link_type',
        'link_url',
        'source_page',
        'user_agent',
        'ip_address',
        'backend_fingerprint',
        'clicked_at',
    ];

    public ?string $session_id {
        get => $this->session_id ?? null;
        set {
            $this->session_id = $value;
            $this->markPropertyAsChanged('session_id');
        }
    }

    public ?string $link_type {
        get => $this->link_type ?? null;
        set {
            $this->link_type = $value;
            $this->markPropertyAsChanged('link_type');
        }
    }

    public ?string $link_url {
        get => $this->link_url ?? null;
        set {
            $this->link_url = $value;
            $this->markPropertyAsChanged('link_url');
        }
    }

    public ?string $source_page {
        get => $this->source_page ?? null;
        set {
            $this->source_page = $value;
            $this->markPropertyAsChanged('source_page');
        }
    }

    public ?string $user_agent {
        get => $this->user_agent ?? null;
        set {
            $this->user_agent = $value;
            $this->markPropertyAsChanged('user_agent');
        }
    }

    public ?string $ip_address {
        get => $this->ip_address ?? null;
        set {
            $this->ip_address = $value;
            $this->markPropertyAsChanged('ip_address');
        }
    }

    public ?string $backend_fingerprint {
        get => $this->backend_fingerprint ?? null;
        set {
            $this->backend_fingerprint = $value;
            $this->markPropertyAsChanged('backend_fingerprint');
        }
    }

    public ?string $clicked_at {
        get => $this->clicked_at ?? null;
        set {
            $this->clicked_at = $value;
            $this->markPropertyAsChanged('clicked_at');
        }
    }
}
