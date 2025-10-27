/**
 * Larafony Analytics Tracker
 * Uses Beacon API for reliable tracking even when page is closing
 */

class LarafonyAnalytics {
    constructor() {
        this.sessionId = this.getOrCreateSessionId();
        this.apiBase = '/api/track';
    }

    /**
     * Get or create session ID stored in sessionStorage
     */
    getOrCreateSessionId() {
        let sessionId = sessionStorage.getItem('larafony_session_id');
        if (!sessionId) {
            sessionId = this.generateUUID();
            sessionStorage.setItem('larafony_session_id', sessionId);
        }
        return sessionId;
    }

    /**
     * Generate UUID v4
     */
    generateUUID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            const r = Math.random() * 16 | 0;
            const v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    /**
     * Send data using Beacon API (works even after page unload)
     */
    sendBeacon(endpoint, data) {
        const payload = JSON.stringify({
            ...data,
            session_id: this.sessionId
        });

        // Use Beacon API if available (works even when page is closing)
        if (navigator.sendBeacon) {
            const blob = new Blob([payload], { type: 'application/json' });
            return navigator.sendBeacon(this.apiBase + endpoint, blob);
        }

        // Fallback to fetch with keepalive
        fetch(this.apiBase + endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: payload,
            keepalive: true // Important: keeps request alive even if page closes
        }).catch(() => {
            // Silent fail - analytics shouldn't break the app
        });
    }

    /**
     * Track page activity
     */
    trackActivity() {
        const data = {
            page_url: window.location.href,
            page_title: document.title,
            referrer: document.referrer || null,
            screen_width: window.screen.width,
            screen_height: window.screen.height
        };

        this.sendBeacon('/activity', data);
    }

    /**
     * Track external link click
     */
    trackLinkClick(linkType, linkUrl) {
        const data = {
            link_type: linkType,
            link_url: linkUrl,
            source_page: window.location.href
        };

        this.sendBeacon('/link-click', data);
    }

    /**
     * Initialize tracking for external links
     */
    initLinkTracking() {
        // Track GitHub framework link
        document.querySelectorAll('a[href*="github.com/DJWeb-Damian-Jozwiak/larafony"]').forEach(link => {
            link.addEventListener('click', (e) => {
                this.trackLinkClick('github_framework', link.href);
            });
        });

        // Track GitHub demo app link
        document.querySelectorAll('a[href*="github.com/DJWeb-Damian-Jozwiak/larafony-demo-app"]').forEach(link => {
            link.addEventListener('click', (e) => {
                this.trackLinkClick('github_demo', link.href);
            });
        });

        // Track MasterPHP link
        document.querySelectorAll('a[href*="masterphp.eu"]').forEach(link => {
            link.addEventListener('click', (e) => {
                this.trackLinkClick('masterphp', link.href);
            });
        });
    }

    /**
     * Initialize all tracking
     */
    init() {
        // Track page view on load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                this.trackActivity();
                this.initLinkTracking();
            });
        } else {
            this.trackActivity();
            this.initLinkTracking();
        }

        // Track page visibility changes (when user returns to tab)
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.trackActivity();
            }
        });
    }
}

// Auto-initialize
const analytics = new LarafonyAnalytics();
analytics.init();

// Expose globally if needed
window.LarafonyAnalytics = analytics;
