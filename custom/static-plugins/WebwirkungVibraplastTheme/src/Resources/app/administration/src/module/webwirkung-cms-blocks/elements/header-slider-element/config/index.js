import template from './sw-cms-el-config-header-slider-element.html.twig';
import './sw-cms-el-config-header-slider-element.scss';

const { Component, Mixin, Context, Utils } = Shopware;

Component.register('sw-cms-el-config-header-slider-element', {
    template,

    mixins: [
        'cms-element'
    ],
    inject: ['repositoryFactory'],
    
    computed: {
        mediaRepository() {
            return this.repositoryFactory.create('media');
        },
    },
    
    data() {
        return {
            counter: 1,
            
            // IMAGE
            initialFolderId: null,
            
            //NEW
            slideBackgroundMediaPreviews: [],
            slideOverlayMediaPreviews: [],
            controlsCustomImagePreviousPreview: null,
            controlsCustomImageNextPreview: null,
    
        };
    },
    created() {
        this.createdComponent();
    },
    
    mounted() {
        this.getMediaEntityPreviews();
    },
    
    methods: {
        createdComponent() {
            this.initElementConfig('header-slider-element');
        },
    
        onClickAddSlide() {
            this.element.config.sliderItems.value.push({
                active: true,
                id: Utils.createId(),
                contentType: 'default',
            
                // Content
                label: 'Bestseller',
                price: null,
                strikePrice: null,
                pricePosition: 'top',
                text: '<h2>Melaminschaumstoffe aus Basotect® G+</h2><p>Zur Verwendung als Schalldämmung in universellen Anwendungen.</p><a target="_self" href="#" class="btn btn-primary btn-special">Jetzt einkaufen</a>',
                backgroundMedia: null,
                overlayMedia: null,
            });
        
            this.getMediaEntityPreviews();
        },
    
        onClickRemoveSlide(slide) {
            const slides = this.element.config.sliderItems.value;
            const index = slides.indexOf(slide);
        
            slides.splice(index, 1);
        
            if (slides.length === 1) {
                slides[0].active = true;
            }
        
            this.element.config.sliderItems.value = slides;
            this.getMediaEntityPreviews();
        },
    
    
        async asyncForEach(array, callback) {
            for (let i = 0; i < array.length; i++) {
                await callback(array[i], i, array);
            }
        },
        
        async getMediaEntityPreviews() {
            if (this.element.config.sliderItems.value) {
                this.slideBackgroundMediaPreviews = [];
            
                await this.asyncForEach(
                  this.element.config.sliderItems.value,
                  async (slide) => {
                      if (slide.backgroundMedia) {
                          const mediaEntity = await this.mediaRepository.get(
                            slide.backgroundMedia,
                            Context.api
                          );
                      
                          this.slideBackgroundMediaPreviews.push(mediaEntity);
                      } else {
                          this.slideBackgroundMediaPreviews.push(null);
                      }
                      if (slide.overlayMedia) {
                          const mediaEntity = await this.mediaRepository.get(
                            slide.overlayMedia,
                            Context.api
                          );
                      
                          this.slideOverlayMediaPreviews.push(mediaEntity);
                      } else {
                          this.slideOverlayMediaPreviews.push(null);
                      }
                  }
                );
            }
        },
    
        onRemoveSlideBackgroundMedia(slide) {
            const sliderItems = this.element.config.sliderItems.value;
            const index = sliderItems.indexOf(slide);
        
            this.$set(this.slideBackgroundMediaPreviews, index, null);
            slide.backgroundMedia = null;
        },
        
        onRemoveSlideOverlayMedia(slide) {
            const sliderItems = this.element.config.sliderItems.value;
            const index = sliderItems.indexOf(slide);
        
            this.$set(this.slideOverlayMediaPreviews, index, null);
            slide.overlayMedia = null;
        },
        
        onChangeSlideBackgroundMedia(mediaEntity, slide) {
            const sliderItems = this.element.config.sliderItems.value;
            const index = sliderItems.indexOf(slide);
        
            this.$set(this.slideBackgroundMediaPreviews, index, mediaEntity[0]);
            slide.backgroundMedia = mediaEntity[0].id;
        },
        onChangeSlideOverlayMedia(mediaEntity, slide) {
            const sliderItems = this.element.config.sliderItems.value;
            const index = sliderItems.indexOf(slide);
        
            this.$set(this.slideOverlayMediaPreviews, index, mediaEntity[0]);
            slide.overlayMedia = mediaEntity[0].id;
        },
        async onFinishUploadSlideBackgroundMedia(mediaItem, slide) {
            const mediaEntity = await this.mediaRepository.get(
              mediaItem.targetId,
              Context.api
            );
            const sliderItems = this.element.config.sliderItems.value;
            const index = sliderItems.indexOf(slide);
        
            this.$set(this.slideBackgroundMediaPreviews, index, mediaEntity);
            slide.backgroundMedia = mediaEntity.id;
            this.refreshMediaEntityPreviews(mediaEntity.id);
        },
        
        async onFinishUploadSlideOverlayMedia(mediaItem, slide) {
            const mediaEntity = await this.mediaRepository.get(
              mediaItem.targetId,
              Context.api
            );
            const sliderItems = this.element.config.sliderItems.value;
            const index = sliderItems.indexOf(slide);
        
            this.$set(this.slideOverlayMediaPreviews, index, mediaEntity);
            slide.overlayMedia = mediaEntity.id;
            this.refreshMediaEntityPreviews(mediaEntity.id);
        },
    
        async refreshMediaEntityPreviews(mediaEntityId) {
            const mediaEntity = await this.mediaRepository.get(
              mediaEntityId,
              Context.api
            );
            if (this.element.config.sliderItems.value) {
                this.element.config.sliderItems.value.forEach((slide, index) => {
                    if (slide.backgroundMedia === mediaEntityId) {
                        this.$set(
                          this.slideBackgroundMediaPreviews,
                          index,
                          mediaEntity
                        );
                    }
                    if (slide.overlayMedia === mediaEntityId) {
                        this.$set(
                          this.slideOverlayMediaPreviews,
                          index,
                          mediaEntity
                        );
                    }
                });
            }
        },
        
    }
});
