let currentCategoryId = null;
let currentPage = 1;
let lastPage = 1;
let isLoading = false;
let hasMoreData = true;

function getSearchQuery() {
    return new URLSearchParams(window.location.search).get('search_query') || '';
}

function buildVideosUrl(basePath, page) {
    const search = getSearchQuery();
    let url = `${basePath}?page=${page}`;
    if (search) {
        url += `&search_query=${encodeURIComponent(search)}`;
    }
    return url;
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function getCategoryNameById(categoryId) {
    const categoryCard = document.querySelector(`.category-card[data-id="${categoryId}"]`);
    if (categoryCard) {
        return categoryCard.querySelector('.category-name').textContent;
    }
    return 'دسته بندی نشده';
}

function displayVideos(videos, isNewCategory = true) {
    const videosGrid = document.getElementById('videosGrid');
    if (!videosGrid) return;

    if (isNewCategory) {
        videosGrid.innerHTML = '';
    }

    if (!videos || videos.length === 0) {
        if (isNewCategory) {
            const emptyMessage = getSearchQuery()
                ? 'نتیجه‌ای برای جستجو یافت نشد'
                : 'هیچ ویدئویی در این دسته بندی یافت نشد';
            videosGrid.innerHTML = `
                <div class="empty-videos">
                    <i class="fa fa-video-slash"></i>
                    <p>${emptyMessage}</p>
                </div>
            `;
        }
        return;
    }

    let html = '';
    videos.forEach(video => {
        const videoPath = video.file_path && video.file_path.startsWith('storage/')
            ? '/' + video.file_path
            : '/storage/' + video.file_path;

        let categoryName = 'دسته بندی نشده';
        if (video.category && video.category.name) {
            categoryName = video.category.name;
        } else if (video.category_name) {
            categoryName = video.category_name;
        } else if (video.category_id) {
            categoryName = getCategoryNameById(video.category_id);
        }

        html += `
            <div class="video-card" data-id="${video.id}">
                <div class="video-player">
                    <video controls preload="metadata" loading="lazy">
                        <source src="${videoPath}" type="video/mp4">
                        مرورگر شما از ویدئو پشتیبانی نمی‌کند
                    </video>
                </div>
                <div class="video-info">
                    <h5 class="video-title">${escapeHtml(video.title)}</h5>
                    <p class="video-description">${escapeHtml(video.description) || ''}</p>
                    <span class="video-category">${escapeHtml(categoryName)}</span>
                </div>
            </div>
        `;
    });

    if (isNewCategory) {
        videosGrid.innerHTML = html;
    } else {
        videosGrid.insertAdjacentHTML('beforeend', html);
    }
}

function appendVideos(videos) {
    displayVideos(videos, false);
}

function showLoading(isNewCategory = true) {
    const videosGrid = document.getElementById('videosGrid');
    if (!videosGrid) return;

    if (isNewCategory) {
        videosGrid.innerHTML = `
            <div class="loading-spinner">
                <i class="fa fa-spinner fa-spin"></i>
                <p>در حال بارگذاری ویدئوها...</p>
            </div>
        `;
    } else {
        let loader = document.getElementById('loadMoreLoader');
        if (!loader) {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = `
                <div id="loadMoreLoader" class="load-more-loader">
                    <i class="fa fa-spinner fa-spin"></i>
                    <p>در حال بارگذاری بیشتر...</p>
                </div>
            `;
            videosGrid.parentElement.appendChild(tempDiv.firstElementChild);
        } else {
            loader.style.display = 'block';
        }
    }
}

function hideLoadMoreLoader() {
    const loader = document.getElementById('loadMoreLoader');
    if (loader) {
        loader.style.display = 'none';
    }
}

function resetPagination() {
    currentPage = 1;
    lastPage = 1;
    hasMoreData = true;
    isLoading = false;
}

function loadVideos(url, isNewCategory = true) {
    return fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const videosData = data.videos;
                const videos = videosData.data || [];
                const paginationCurrentPage = videosData.current_page;
                const paginationLastPage = videosData.last_page;

                if (isNewCategory) {
                    displayVideos(videos, true);
                } else {
                    appendVideos(videos);
                }

                currentPage = paginationCurrentPage;
                lastPage = paginationLastPage;
                hasMoreData = currentPage < lastPage;

                return { success: true, hasMore: hasMoreData };
            } else {
                throw new Error(data.error || 'خطا در دریافت ویدئوها');
            }
        });
}

function loadAllVideos() {
    resetPagination();
    currentCategoryId = null;
    showLoading(true);

    loadVideos(buildVideosUrl('/get-all-videos', 1), true)
        .catch(error => {
            const videosGrid = document.getElementById('videosGrid');
            if (videosGrid) {
                videosGrid.innerHTML = `
                    <div class="empty-videos">
                        <i class="fa fa-exclamation-triangle"></i>
                        <p>خطا در بارگذاری ویدئوها: ${error.message}</p>
                        <button onclick="location.reload()" class="btn btn-primary mt-3">تلاش مجدد</button>
                    </div>
                `;
            }
        });
}

function loadVideosByCategory(categoryId) {
    resetPagination();
    currentCategoryId = categoryId;
    showLoading(true);

    loadVideos(buildVideosUrl(`/get-videos/${categoryId}`, 1), true)
        .catch(error => {
            const videosGrid = document.getElementById('videosGrid');
            if (videosGrid) {
                videosGrid.innerHTML = `
                    <div class="empty-videos">
                        <i class="fa fa-exclamation-triangle"></i>
                        <p>خطا در بارگذاری ویدئوها</p>
                    </div>
                `;
            }
        });
}

function loadMoreVideos() {
    isLoading = true;
    const nextPage = currentPage + 1;

    const url = currentCategoryId
        ? buildVideosUrl(`/get-videos/${currentCategoryId}`, nextPage)
        : buildVideosUrl('/get-all-videos', nextPage);

    showLoading(false);

    loadVideos(url, false)
        .then(result => {
            hideLoadMoreLoader();
            isLoading = false;
        })
        .catch(error => {
            hideLoadMoreLoader();
            isLoading = false;
        });
}

function setupInfinityScroll() {
    const sentinel = document.createElement('div');
    sentinel.id = 'scroll-sentinel';
    sentinel.style.height = '10px';
    sentinel.style.width = '100%';
    sentinel.style.background = 'transparent';

    const videosGrid = document.getElementById('videosGrid');
    if (videosGrid && videosGrid.parentElement) {
        videosGrid.parentElement.appendChild(sentinel);
    } else {
        document.body.appendChild(sentinel);
    }

    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
            if (!isLoading && hasMoreData) {
                loadMoreVideos();
            }
        }
    }, { threshold: 0.1 });

    observer.observe(sentinel);
}

document.addEventListener('DOMContentLoaded', function() {
    const slider = document.getElementById('categorySlider');
    const prevBtn = document.getElementById('prevCategory');
    const nextBtn = document.getElementById('nextCategory');

    if (slider && prevBtn && nextBtn) {
        function scrollToRight() {
            slider.scrollBy({ left: 250, behavior: 'smooth' });
        }

        function scrollToLeft() {
            slider.scrollBy({ left: -250, behavior: 'smooth' });
        }

        function checkButtons() {
        }

        prevBtn.addEventListener('click', scrollToRight);
        nextBtn.addEventListener('click', scrollToLeft);
        slider.addEventListener('scroll', checkButtons);
        setTimeout(checkButtons, 100);
        window.addEventListener('resize', () => setTimeout(checkButtons, 100));

        let isDown = false;
        let startX;
        let scrollLeftPos;
        let dragOccurred = false;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.style.cursor = 'grabbing';
            startX = e.pageX - slider.offsetLeft;
            scrollLeftPos = slider.scrollLeft;
            e.preventDefault();
        });

        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.style.cursor = 'grab';
        });

        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.style.cursor = 'grab';
            setTimeout(() => { dragOccurred = false; }, 10);
        });

        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            dragOccurred = true;
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 1.5;
            slider.scrollLeft = scrollLeftPos - walk;
        });

        slider.addEventListener('dragstart', (e) => {
            e.preventDefault();
            return false;
        });

        slider.style.cursor = 'grab';

        document.querySelectorAll('.category-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (dragOccurred) {
                    e.stopPropagation();
                    return;
                }

                const categoryId = this.dataset.id;

                document.querySelectorAll('.category-card').forEach(c => {
                    c.classList.remove('active');
                });
                this.classList.add('active');

                loadVideosByCategory(categoryId);
            });
        });
    }

    setupInfinityScroll();

    loadAllVideos();
});
