/**
 * Larafony Documentation Search
 * Uses MiniSearch for fast, typo-tolerant search
 */
(function() {
    'use strict';

    let searchIndex = null;
    let miniSearch = null;
    let isLoaded = false;

    // Create search modal HTML
    function createSearchModal() {
        const modal = document.createElement('div');
        modal.id = 'search-modal';
        modal.innerHTML = `
            <div class="search-backdrop"></div>
            <div class="search-container">
                <div class="search-header">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="search-input" placeholder="Search documentation..." autocomplete="off" />
                    <kbd class="search-shortcut">Ctrl+F</kbd>
                </div>
                <div class="search-results" id="search-results">
                    <div class="search-empty">
                        <i class="bi bi-lightbulb"></i>
                        <p>Start typing to search...</p>
                        <p class="search-hints">Try: "routing", "middleware", "model", "validation"</p>
                    </div>
                </div>
                <div class="search-footer">
                    <span><kbd>↑</kbd> <kbd>↓</kbd> Navigate</span>
                    <span><kbd>Enter</kbd> Open</span>
                    <span><kbd>ESC</kbd> Close</span>
                </div>
            </div>
        `;
        document.body.appendChild(modal);

        // Add styles
        const style = document.createElement('style');
        style.textContent = `
            #search-modal {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 9999;
            }

            #search-modal.active {
                display: block;
            }

            .search-backdrop {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.7);
                backdrop-filter: blur(4px);
            }

            .search-container {
                position: absolute;
                top: 10%;
                left: 50%;
                transform: translateX(-50%);
                width: 90%;
                max-width: 640px;
                background: #1e293b;
                border-radius: 12px;
                border: 1px solid #334155;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
                overflow: hidden;
            }

            .search-header {
                display: flex;
                align-items: center;
                padding: 16px;
                border-bottom: 1px solid #334155;
                background: #0f172a;
            }

            .search-icon {
                color: #64748b;
                font-size: 1.25rem;
                margin-right: 12px;
            }

            #search-input {
                flex: 1;
                background: transparent;
                border: none;
                outline: none;
                color: #f1f5f9;
                font-size: 1.1rem;
                font-family: inherit;
            }

            #search-input::placeholder {
                color: #64748b;
            }

            .search-shortcut {
                background: #334155;
                color: #94a3b8;
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 0.75rem;
                font-family: inherit;
                border: 1px solid #475569;
            }

            .search-results {
                max-height: 400px;
                overflow-y: auto;
                padding: 8px;
            }

            .search-empty {
                text-align: center;
                padding: 40px 20px;
                color: #64748b;
            }

            .search-empty i {
                font-size: 2rem;
                margin-bottom: 12px;
                display: block;
            }

            .search-hints {
                font-size: 0.85rem;
                margin-top: 8px;
                color: #475569;
            }

            .search-result {
                display: block;
                padding: 12px 16px;
                border-radius: 8px;
                text-decoration: none;
                color: #e2e8f0;
                transition: background 0.15s;
            }

            .search-result:hover,
            .search-result.active {
                background: rgba(99, 102, 241, 0.2);
            }

            .search-result-title {
                font-weight: 600;
                margin-bottom: 4px;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .search-result-title i {
                color: #6366f1;
            }

            .search-result-match {
                font-size: 0.85rem;
                color: #94a3b8;
            }

            .search-result-match mark {
                background: rgba(251, 191, 36, 0.3);
                color: #fbbf24;
                padding: 0 2px;
                border-radius: 2px;
            }

            .search-badge {
                background: rgba(236, 72, 153, 0.2);
                color: #ec4899;
                padding: 2px 6px;
                border-radius: 4px;
                font-size: 0.7rem;
                font-weight: 600;
                text-transform: uppercase;
                margin-left: auto;
            }

            .search-no-results {
                text-align: center;
                padding: 40px 20px;
                color: #64748b;
            }

            .search-no-results i {
                font-size: 2rem;
                margin-bottom: 12px;
                display: block;
            }

            .search-footer {
                display: flex;
                justify-content: center;
                gap: 24px;
                padding: 12px;
                border-top: 1px solid #334155;
                background: #0f172a;
                font-size: 0.8rem;
                color: #64748b;
            }

            .search-footer kbd {
                background: #334155;
                padding: 2px 6px;
                border-radius: 4px;
                font-size: 0.75rem;
                border: 1px solid #475569;
            }

            .search-trigger {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 8px 12px;
                background: rgba(99, 102, 241, 0.1);
                border: 1px solid #334155;
                border-radius: 8px;
                color: #94a3b8;
                cursor: pointer;
                transition: all 0.2s;
                font-size: 0.9rem;
            }

            .search-trigger:hover {
                background: rgba(99, 102, 241, 0.2);
                border-color: #6366f1;
                color: #e2e8f0;
            }

            .search-trigger kbd {
                background: #334155;
                padding: 2px 6px;
                border-radius: 4px;
                font-size: 0.7rem;
                border: 1px solid #475569;
                margin-left: 8px;
            }

            @media (max-width: 640px) {
                .search-container {
                    top: 5%;
                    width: 95%;
                }

                .search-footer {
                    display: none;
                }

                .search-trigger kbd {
                    display: none;
                }
            }
        `;
        document.head.appendChild(style);

        return modal;
    }

    // Load search index
    async function loadSearchIndex() {
        if (isLoaded) return;

        try {
            const response = await fetch('/search-index.json');
            searchIndex = await response.json();

            // Initialize MiniSearch
            miniSearch = new MiniSearch({
                fields: ['title', 'headings', 'text', 'keywords'],
                storeFields: ['title', 'url', 'headings', 'text', 'category'],
                searchOptions: {
                    boost: { title: 3, headings: 2, keywords: 1.5 },
                    fuzzy: 0.2,
                    prefix: true,
                },
            });

            // Index documents
            miniSearch.addAll(searchIndex);
            isLoaded = true;
        } catch (error) {
            console.error('Failed to load search index:', error);
        }
    }

    // Perform search
    function performSearch(query) {
        const resultsContainer = document.getElementById('search-results');

        if (!query.trim()) {
            resultsContainer.innerHTML = `
                <div class="search-empty">
                    <i class="bi bi-lightbulb"></i>
                    <p>Start typing to search...</p>
                    <p class="search-hints">Try: "routing", "middleware", "model", "validation"</p>
                </div>
            `;
            return;
        }

        if (!isLoaded) {
            resultsContainer.innerHTML = `
                <div class="search-empty">
                    <i class="bi bi-hourglass-split"></i>
                    <p>Loading search index...</p>
                </div>
            `;
            return;
        }

        const results = miniSearch.search(query, { limit: 10 });

        if (results.length === 0) {
            resultsContainer.innerHTML = `
                <div class="search-no-results">
                    <i class="bi bi-search"></i>
                    <p>No results found for "<strong>${escapeHtml(query)}</strong>"</p>
                    <p class="search-hints">Try different keywords or check spelling</p>
                </div>
            `;
            return;
        }

        resultsContainer.innerHTML = results.map((result, index) => {
            const matchText = getMatchContext(result, query);
            const isBridge = result.category === 'Bridges';
            const icon = isBridge ? 'bi-plug' : 'bi-file-text';
            const badge = isBridge ? '<span class="search-badge">Bridge</span>' : '';
            return `
                <a href="${result.url}" class="search-result ${index === 0 ? 'active' : ''}" data-index="${index}">
                    <div class="search-result-title">
                        <i class="bi ${icon}"></i>
                        ${highlightMatch(result.title, query)}
                        ${badge}
                    </div>
                    ${matchText ? `<div class="search-result-match">${matchText}</div>` : ''}
                </a>
            `;
        }).join('');
    }

    // Get context around match
    function getMatchContext(result, query) {
        const terms = query.toLowerCase().split(/\s+/).filter(t => t.length > 0);
        const text = result.text || '';

        // Handle headings - could be array, string, or undefined
        let headings = [];
        if (Array.isArray(result.headings)) {
            headings = result.headings;
        } else if (typeof result.headings === 'string' && result.headings) {
            headings = [result.headings];
        }

        // Check headings first
        for (const heading of headings) {
            if (heading && typeof heading === 'string') {
                if (terms.some(term => heading.toLowerCase().includes(term))) {
                    return highlightMatch(heading, query);
                }
            }
        }

        // Find match in text
        for (const term of terms) {
            const index = text.toLowerCase().indexOf(term);
            if (index !== -1) {
                const start = Math.max(0, index - 40);
                const end = Math.min(text.length, index + term.length + 60);
                let context = text.substring(start, end);
                if (start > 0) context = '...' + context;
                if (end < text.length) context += '...';
                return highlightMatch(context, query);
            }
        }

        return '';
    }

    // Highlight matching text
    function highlightMatch(text, query) {
        const terms = query.toLowerCase().split(/\s+/).filter(t => t.length > 1);
        let result = escapeHtml(text);

        for (const term of terms) {
            const regex = new RegExp(`(${escapeRegex(term)})`, 'gi');
            result = result.replace(regex, '<mark>$1</mark>');
        }

        return result;
    }

    // Escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Escape regex special characters
    function escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    // Keyboard navigation
    function navigateResults(direction) {
        const results = document.querySelectorAll('.search-result');
        if (results.length === 0) return;

        const current = document.querySelector('.search-result.active');
        let nextIndex = 0;

        if (current) {
            const currentIndex = parseInt(current.dataset.index, 10);
            current.classList.remove('active');
            nextIndex = direction === 'down'
                ? Math.min(currentIndex + 1, results.length - 1)
                : Math.max(currentIndex - 1, 0);
        }

        results[nextIndex].classList.add('active');
        results[nextIndex].scrollIntoView({ block: 'nearest' });
    }

    // Open selected result
    function openSelectedResult() {
        const active = document.querySelector('.search-result.active');
        if (active) {
            window.location.href = active.href;
        }
    }

    // Initialize
    function init() {
        const modal = createSearchModal();
        const input = document.getElementById('search-input');
        const backdrop = modal.querySelector('.search-backdrop');

        // Open modal
        function openSearch() {
            modal.classList.add('active');
            input.focus();
            loadSearchIndex();
        }

        // Close modal
        function closeSearch() {
            modal.classList.remove('active');
            input.value = '';
            performSearch('');
        }

        // Keyboard shortcut (Ctrl+F or Cmd+F)
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                openSearch();
            }

            if (modal.classList.contains('active')) {
                if (e.key === 'Escape') {
                    closeSearch();
                } else if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    navigateResults('down');
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    navigateResults('up');
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    openSelectedResult();
                }
            }
        });

        // Close on backdrop click
        backdrop.addEventListener('click', closeSearch);

        // Search input
        let debounceTimer;
        input.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                performSearch(input.value);
            }, 150);
        });

        // Expose openSearch for trigger button
        window.openDocsSearch = openSearch;
    }

    // Wait for DOM and MiniSearch
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
