import template from './sw-cms-el-config-banner-element.html.twig';
import './sw-cms-el-config-banner-element.scss';

const { Component, Mixin, Context, Utils } = Shopware;

Component.register('sw-cms-el-config-banner-element', {
    template,

    mixins: [
        'cms-element'
    ],
    inject: ['repositoryFactory'],
    
    data() {
        return {
            // Icon
            initialFolderId: null,
            entity: this.element,
            bannerImageModalIsOpen: false,
            bannerBackgroundModalIsOpen: false,
        };
    },
    
    computed: {
        mediaRepository() {
            return this.repositoryFactory.create('media');
        },
        defaultFolderName() {
            return this.cmsPageState.pageEntityName;
        },
        uploadBannerImageTag() {
            return `cms-element-banner-image-config-${this.element.id}`;
        },
        uploadBannerBackgroundTag() {
            return `cms-element-banner-background-config-${this.element.id}`;
        },
        previewBannerImageSource() {
            if (this.element?.data?.bannerImage?.id) {
                return this.element.data.bannerImage;
            }
            
            return this.element.config.bannerImage.value;
        },
        
        previewBannerBackgroundSource() {
            if (this.element?.data?.bannerBackground?.id) {
                return this.element.data.bannerBackground;
            }
            
            return this.element.config.bannerBackground.value;
        },
        
        bannerText: {
            get() {
                return this.element.config.bannerText.value;
            },
            
            set(value) {
                this.element.config.bannerText.value = value;
            }
        },
        bannerImage: {
            get() {
                return this.element.config.bannerImage.value;
            },
            
            set(value) {
                this.element.config.bannerImage.value = value;
            }
        },
        bannerBackground: {
            get() {
                return this.element.config.bannerBackground.value;
            },
            
            set(value) {
                this.element.config.bannerBackground.value = value;
            }
        },
        bannerSize: {
            get() {
                return this.element.config.bannerSize.value;
            },
            
            set(value) {
                this.element.config.bannerSize.value = value;
            }
        },
        bannerArrangement: {
            get() {
                return this.element.config.bannerArrangement.value;
            },
            
            set(value) {
                this.element.config.bannerArrangement.value = value;
            }
        }
    },
    created() {
        this.createdComponent();
    },
    
    mounted() {
        // this.getMediaEntityPreviews();
    },
    
    methods: {
        createdComponent() {
            this.initElementConfig('banner-element');
        },
        
        updateElementData(media = null) {
            const mediaId = media === null ? null : media.id;
            if (!this.element.data) {
                this.$set(this.element, 'data', { mediaId, media });
            } else {
                this.$set(this.element.data, 'bannerImageId', mediaId);
                this.$set(this.element.data, 'bannerImage', media);
            }
        },
        
        // Banner Image functions
        async onBannerImageUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);
            
            this.element.config.bannerImage.value = mediaEntity.id;
            this.element.config.bannerImage.source = 'static';
            
            this.updateElementData(mediaEntity);
            
            this.$emit('element-update', this.element);
        },
        onBannerImageRemove() {
            this.element.config.bannerImage.value = null;
            
            this.updateElementData();
            
            this.$emit('element-update', this.element);
        },
        onOpenBannerImageModal() {
            this.bannerImageModalIsOpen = true;
        },
        onCloseBannerImageModal() {
            this.bannerImageModalIsOpen = false;
        },
        onBannerImageSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.bannerImage.value = media.id;
            this.element.config.bannerImage.source = 'static';
            
            this.updateElementData(media);
            
            this.$emit('element-update', this.element);
        },
        
        // Banner Background functions
        async onBannerBackgroundUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);
            
            this.element.config.bannerBackground.value = mediaEntity.id;
            this.element.config.bannerBackground.source = 'static';
            
            this.updateElementData(mediaEntity);
            
            this.$emit('element-update', this.element);
        },
        onBannerBackgroundRemove() {
            this.element.config.bannerBackground.value = null;
            
            this.updateElementData();
            
            this.$emit('element-update', this.element);
        },
        onOpenBannerBackgroundModal() {
            this.bannerBackgroundModalIsOpen = true;
        },
        onCloseBannerBackgroundModal() {
            this.bannerBackgroundModalIsOpen = false;
        },
        onBannerBackgroundSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.bannerBackground.value = media.id;
            this.element.config.bannerBackground.source = 'static';
            
            this.updateElementData(media);
            
            this.$emit('element-update', this.element);
        },
    }
});
