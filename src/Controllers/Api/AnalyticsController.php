<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Models\UserActivity;
use App\Models\ExternalLinkClick;
use Larafony\Framework\Clock\ClockFactory;
use Larafony\Framework\Clock\Enums\TimeFormat;
use Larafony\Framework\Clock\Enums\Timezone;
use Larafony\Framework\Routing\Advanced\Attributes\Route;
use Larafony\Framework\Web\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AnalyticsController extends Controller
{
    #[Route('/api/track/activity', 'POST')]
    public function trackActivity(ServerRequestInterface $request): ResponseInterface
    {
        $data = json_decode($request->getBody()->getContents(), true);

        if (!$data || !isset($data['page_url'])) {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        try {
            // Generate backend fingerprint from IP + User Agent
            $ipAddress = $this->getClientIp($request);
            $userAgent = $request->getHeaderLine('User-Agent');
            $fingerprint = hash('sha256', $ipAddress . '|' . $userAgent . '|' . date('Y-m-d'));

            // Get or create session ID
            $sessionId = $data['session_id'] ?? $this->generateSessionId();

            // Create and save activity using Model
            $activity = new UserActivity();
            $activity->fill([
                'session_id' => $sessionId,
                'page_url' => $data['page_url'],
                'page_title' => $data['page_title'] ?? null,
                'referrer' => $data['referrer'] ?? null,
                'user_agent' => $userAgent,
                'ip_address' => $ipAddress,
                'backend_fingerprint' => $fingerprint,
                'screen_width' => $data['screen_width'] ?? null,
                'screen_height' => $data['screen_height'] ?? null,
            ]);
            $activity->save();

            return $this->json([
                'success' => true,
                'session_id' => $sessionId,
                'fingerprint' => $fingerprint
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Failed to track activity'], 500);
        }
    }

    #[Route('/api/track/link-click', 'POST')]
    public function trackLinkClick(ServerRequestInterface $request): ResponseInterface
    {
        $data = json_decode($request->getBody()->getContents(), true);

        if (!$data || !isset($data['link_type'], $data['link_url'])) {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        try {
            // Generate backend fingerprint
            $ipAddress = $this->getClientIp($request);
            $userAgent = $request->getHeaderLine('User-Agent');
            $fingerprint = hash('sha256', $ipAddress . '|' . $userAgent . '|' . date('Y-m-d'));

            // Get session ID
            $sessionId = $data['session_id'] ?? $this->generateSessionId();

            // Create and save link click using Model
            $linkClick = new ExternalLinkClick();
            $linkClick->fill([
                'session_id' => $sessionId,
                'link_type' => $data['link_type'],
                'link_url' => $data['link_url'],
                'source_page' => $data['source_page'] ?? 'unknown',
                'user_agent' => $userAgent,
                'ip_address' => $ipAddress,
                'backend_fingerprint' => $fingerprint,
                'clicked_at' => ClockFactory::timezone(Timezone::EUROPE_WARSAW)
                    ->now()->format(TimeFormat::DATETIME->value),
            ]);
            $linkClick->save();

            return $this->json([
                'success' => true,
                'session_id' => $sessionId
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Failed to track link click'], 500);
        }
    }

    private function getClientIp(ServerRequestInterface $request): string
    {
        $serverParams = $request->getServerParams();

        // Check for proxy headers
        if (!empty($serverParams['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $serverParams['HTTP_X_FORWARDED_FOR'])[0];
            return trim($ip);
        }

        if (!empty($serverParams['HTTP_X_REAL_IP'])) {
            return $serverParams['HTTP_X_REAL_IP'];
        }

        return $serverParams['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    private function generateSessionId(): string
    {
        return bin2hex(random_bytes(16));
    }
}
