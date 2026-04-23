import GallerySliderPlugin from 'src/plugin/slider/gallery-slider.plugin';

export default class GridGallerySliderPlugin extends GallerySliderPlugin {
    static options = {
        ...GallerySliderPlugin.options,
        gridPageSize: 8,
    };

    _initSlider() {
        super._initSlider();
        this._initGridThumbnails();
    }

    _initGridThumbnails() {
        if (!this.el.classList.contains('is-grid-thumbnails')) {
            return;
        }

        const pageSize = this.options.gridPageSize;
        let currentPage = 0;

        const thumbnailItems = this.el.querySelectorAll('.gallery-slider-thumbnails-item');
        const totalItems = thumbnailItems.length;
        const totalPages = Math.ceil(totalItems / pageSize);

        const showPage = (page, direction = 'none') => {
            const newPage = Math.max(0, Math.min(page, totalPages - 1));
            if (newPage === currentPage && direction !== 'none') return;
            currentPage = newPage;
            const startVisible = currentPage * pageSize;
            const endVisible = startVisible + pageSize;

            if (direction === 'none') {
                thumbnailItems.forEach((item, index) => {
                    if (index >= startVisible && index < endVisible) {
                        item.style.display = '';
                        item.style.opacity = '1';
                        item.style.transform = 'translateX(0)';
                    } else {
                        item.style.display = 'none';
                    }
                });
                return;
            }

            const slideOut = direction === 'forward' ? '-20px' : '20px';
            const slideIn = direction === 'forward' ? '20px' : '-20px';

            thumbnailItems.forEach((item) => {
                if (item.style.display !== 'none') {
                    item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    item.style.opacity = '0';
                    item.style.transform = `translateX(${slideOut})`;
                }
            });

            setTimeout(() => {
                thumbnailItems.forEach((item, index) => {
                    if (index >= startVisible && index < endVisible) {
                        item.style.display = '';
                        item.style.transition = 'none';
                        item.style.opacity = '0';
                        item.style.transform = `translateX(${slideIn})`;

                        item.offsetHeight;
                        item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        item.style.opacity = '1';
                        item.style.transform = 'translateX(0)';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }, 300);
        };

        showPage(0);

        if (this._thumbnailSlider) {
            let prevIndex = 0;

            this._thumbnailSlider.events.on('indexChanged', () => {
                const info = this._thumbnailSlider.getInfo();
                const newIndex = info.index;

                if (newIndex > prevIndex) {
                    showPage(currentPage + 1, 'forward');
                } else if (newIndex < prevIndex) {
                    showPage(currentPage - 1, 'backward');
                }

                prevIndex = newIndex;
            });
        }

        if (this._slider) {
            this._slider.events.on('indexChanged', () => {
                const currentSlideIndex = this.getCurrentSliderIndex();
                const targetPage = Math.floor(currentSlideIndex / pageSize);

                if (targetPage !== currentPage) {
                    const dir = targetPage > currentPage ? 'forward' : 'backward';
                    showPage(targetPage, dir);

                    if (this._thumbnailSlider) {
                        this._thumbnailSlider.goTo(targetPage * pageSize);
                    }
                }
            });
        }
    }
}
