/**
 * public/js/blog.js
 * Xử lý AJAX load bài viết, lọc thể loại, phân trang, smooth scroll.
 */
document.addEventListener('DOMContentLoaded', () => {

    // ── State ─────────────────────────────────────────────────────────────────
    const state = {
        my:    { page: 1, category: 'all', sort: 'newest', totalPages: 1 },
        other: { page: 1, category: 'all', sort: 'newest', totalPages: 1 },
    };
    // Ensure menu click handlers are initialized only once to avoid duplicate bindings when content reloads
    let postMenuInit = false;

    // ── Load bài viết qua AJAX ────────────────────────────────────────────────
    function loadPosts(type) {
        const s = state[type];
        const container = document.getElementById(`${type}-posts-container`);
        if (!container) return;

        container.innerHTML = '<div class="loading-spinner">Đang tải...</div>';

        const params = new URLSearchParams({
            type:     type,
            category: s.category,
            sort:     s.sort,
            page:     s.page,
        });

        fetch(`api/get_posts.php?${params}`)
            .then(r => r.json())
            .then(data => {
                container.innerHTML = data.html || '<div class="no-posts">Chưa có bài viết nào.</div>';
                s.totalPages = data.totalPages || 1;

                if (type === 'other') {
                    renderPagination(data.page, data.totalPages);
                }

                // Read more button cho My Posts
                if (type === 'my') {
                    const readMoreBtn = document.getElementById('my-read-more');
                    if (readMoreBtn) {
                        readMoreBtn.style.display = (data.totalPages > 1) ? 'inline-block' : 'none';
                        readMoreBtn.dataset.page = 1;
                    }
                }

                // Gắn event cho nút menu 3 chấm
                bindPostMenus();
            })
            .catch(() => {
                container.innerHTML = '<div class="no-posts">Lỗi tải bài viết. Vui lòng thử lại.</div>';
            });
    }

    // ── Phân trang (Posts from the other) ────────────────────────────────────
    function renderPagination(currentPage, totalPages) {
        const wrap = document.getElementById('other-pagination');
        if (!wrap) return;
        if (totalPages <= 1) { wrap.innerHTML = ''; return; }

        let html = '';

        // << (First)
        html += `<button class="page-btn" ${currentPage === 1 ? 'disabled' : ''} data-page="1">&lt;&lt;</button>`;
        // < (Prev)
        html += `<button class="page-btn" ${currentPage === 1 ? 'disabled' : ''} data-page="${currentPage - 1}">&lt;</button>`;

        // Các số trang
        const range = getPageRange(currentPage, totalPages);
        range.forEach(p => {
            if (p === '...') {
                html += `<span class="page-btn" style="border:none;cursor:default">...</span>`;
            } else {
                html += `<button class="page-btn ${p === currentPage ? 'active' : ''}" data-page="${p}">${p}</button>`;
            }
        });

        // > (Next)
        html += `<button class="page-btn" ${currentPage === totalPages ? 'disabled' : ''} data-page="${currentPage + 1}">&gt;</button>`;
        // >> (Last)
        html += `<button class="page-btn" ${currentPage === totalPages ? 'disabled' : ''} data-page="${totalPages}">&gt;&gt;</button>`;

        wrap.innerHTML = html;

        // Bind click
        wrap.querySelectorAll('.page-btn[data-page]').forEach(btn => {
            btn.addEventListener('click', () => {
                const p = parseInt(btn.dataset.page);
                if (!isNaN(p)) {
                    state.other.page = p;
                    loadPosts('other');
                    document.getElementById('other-posts-section')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    }

    function getPageRange(current, total) {
        if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);
        const pages = [];
        if (current <= 4) {
            for (let i = 1; i <= 5; i++) pages.push(i);
            pages.push('...', total);
        } else if (current >= total - 3) {
            pages.push(1, '...');
            for (let i = total - 4; i <= total; i++) pages.push(i);
        } else {
            pages.push(1, '...', current - 1, current, current + 1, '...', total);
        }
        return pages;
    }

    // ── Read more (My Posts) ──────────────────────────────────────────────────
    const myReadMore = document.getElementById('my-read-more');
    if (myReadMore) {
        myReadMore.addEventListener('click', () => {
            state.my.page += 1;
            const container = document.getElementById('my-posts-container');

            fetch(`api/get_posts.php?type=my&category=${state.my.category}&sort=${state.my.sort}&page=${state.my.page}`)
                .then(r => r.json())
                .then(data => {
                    // Append HTML
                    const temp = document.createElement('div');
                    temp.innerHTML = data.html;
                    // Lấy các posts-col và gộp vào container hiện có
                    const existingGrid = container.querySelector('.posts-grid');
                    const newGrid = temp.querySelector('.posts-grid');
                    if (existingGrid && newGrid) {
                        const cols = existingGrid.querySelectorAll('.posts-col');
                        const newCols = newGrid.querySelectorAll('.posts-col');
                        cols.forEach((col, i) => {
                            if (newCols[i]) col.append(...newCols[i].children);
                        });
                    }
                    if (state.my.page >= data.totalPages) {
                        myReadMore.style.display = 'none';
                    }
                    bindPostMenus();
                });
        });
    }

    // ── Category Tabs ─────────────────────────────────────────────────────────
    document.querySelectorAll('.category-tabs').forEach(tabGroup => {
        const type = tabGroup.dataset.target;
        tabGroup.querySelectorAll('.cat-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                tabGroup.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                state[type].category = btn.dataset.cat;
                state[type].page = 1;
                loadPosts(type);
            });
        });
    });

    // ── Filter Dropdowns ──────────────────────────────────────────────────────
    document.querySelectorAll('.filter-select').forEach(sel => {
        const type = sel.dataset.target;
        sel.addEventListener('change', () => {
            state[type].sort = sel.value;
            state[type].page = 1;
            loadPosts(type);
        });
    });

    // ── Post menu 3 chấm (click-to-toggle, robust against hover gaps) ─────────────────
    function bindPostMenus() {
        // Attach delete handlers to delete buttons (idempotent: mark buttons once bound)
        document.querySelectorAll('.delete-post-btn').forEach(btn => {
            if (btn.dataset.bound === '1') return;
            btn.dataset.bound = '1';
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                if (confirm('Bạn có chắc muốn xoá bài viết này không?')) {
                    const id = btn.dataset.id;
                    fetch(`api/delete_post.php?id=${id}`, { method: 'POST' })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                // Remove card từ DOM nếu có
                                const card = btn.closest('.post-card');
                                if (card) {
                                    card.remove();
                                } else {
                                    // Nếu đang ở trang đọc bài viết, chuyển hướng về trang blog/profile sau khi xoá
                                    if (document.querySelector('.read-blog-container')) {
                                        window.location.href = '?page=blog';
                                        return;
                                    }
                                }

                                // Nếu đang hiển thị trang profile, giảm số lượng bài viết trên UI
                                try {
                                    document.querySelectorAll('.profile-stats-row .stat-badge').forEach(b => {
                                        const txt = b.querySelector('.stat-txt')?.textContent?.trim();
                                        if (txt === 'Bài viết') {
                                            const numEl = b.querySelector('.stat-num');
                                            if (numEl) {
                                                let n = parseInt(numEl.textContent) || 0;
                                                if (n > 0) numEl.textContent = n - 1;
                                            }
                                        }
                                    });
                                } catch (err) {
                                    // ignore if profile elements not present
                                }
                            } else {
                                alert('Lỗi xoá bài viết.');
                            }
                        });
                }
            });
        });

        // Initialize menu toggle behavior once (delegated)
        if (!postMenuInit) {
            postMenuInit = true;

            // Toggle menu when clicking the ⋮ button
            document.addEventListener('click', (ev) => {
                const btn = ev.target.closest('.post-menu-btn');
                if (btn) {
                    ev.stopPropagation();
                    // Toggle open on the parent .post-menu
                    const menu = btn.closest('.post-menu');
                    if (menu) {
                        // Close any other open menus first
                        document.querySelectorAll('.post-menu.open').forEach(m => {
                            if (m !== menu) m.classList.remove('open');
                        });
                        menu.classList.toggle('open');
                    }
                    return;
                }

                // Clicking outside closes any open menus
                if (!ev.target.closest('.post-menu')) {
                    document.querySelectorAll('.post-menu.open').forEach(m => m.classList.remove('open'));
                }
            });

            // Also close menus on Escape key
            document.addEventListener('keydown', (ev) => {
                if (ev.key === 'Escape') {
                    document.querySelectorAll('.post-menu.open').forEach(m => m.classList.remove('open'));
                }
            });
        }
    }

    // ── Smooth Scroll: "Read the Blog" button ────────────────────────────────
    const readBlogBtn = document.getElementById('read');
    if (readBlogBtn) {
        readBlogBtn.addEventListener('click', () => {
            const blogSection = document.getElementById('blog-anchor');
            if (blogSection) {
                blogSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    }

    // ── Subscribe button (Hero Home) ──────────────────────────────────────────
    const subBtn = document.getElementById('sub');
    if (subBtn) {
        subBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const subscribeModal = document.getElementById('subscribe-modal');
            if (subscribeModal) {
                subscribeModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        });
    }

    // ── Load lần đầu ─────────────────────────────────────────────────────────
    loadPosts('my');
    loadPosts('other');

    // Ensure menu handlers are available on pages that don't call loadPosts (e.g., read_blog)
    bindPostMenus();
});
