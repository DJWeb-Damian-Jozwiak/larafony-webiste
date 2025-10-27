<?php

declare(strict_types=1);

namespace App\Models;

use Larafony\Framework\Database\ORM\Model;

class UserActivity extends Model
{
    public string $table { get => 'user_activity'; }

    public array $fillable = [
        'session_id',
        'page_url',
        'page_title',
        'referrer',
        'user_agent',
        'ip_address',
        'backend_fingerprint',
        'country_code',
        'screen_width',
        'screen_height',
    ];

    public ?string $session_id {
        get => $this->session_id ?? null;
        set {
            $this->session_id = $value;
            $this->markPropertyAsChanged('session_id');
        }
    }

    public ?string $page_url {
        get => $this->page_url ?? null;
        set {
            $this->page_url = $value;
            $this->markPropertyAsChanged('page_url');
        }
    }

    public ?string $page_title {
        get => $this->page_title ?? null;
        set {
            $this->page_title = $value;
            $this->markPropertyAsChanged('page_title');
        }
    }

    public ?string $referrer {
        get => $this->referrer ?? null;
        set {
            $this->referrer = $value;
            $this->markPropertyAsChanged('referrer');
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

    public ?string $country_code {
        get => $this->country_code ?? null;
        set {
            $this->country_code = $value;
            $this->markPropertyAsChanged('country_code');
        }
    }

    public ?int $screen_width {
        get => $this->screen_width ?? null;
        set {
            $this->screen_width = $value;
            $this->markPropertyAsChanged('screen_width');
        }
    }

    public ?int $screen_height {
        get => $this->screen_height ?? null;
        set {
            $this->screen_height = $value;
            $this->markPropertyAsChanged('screen_height');
        }
    }
}
