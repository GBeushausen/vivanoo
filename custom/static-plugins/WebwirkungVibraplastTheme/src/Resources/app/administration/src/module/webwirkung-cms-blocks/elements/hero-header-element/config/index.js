import template from './sw-cms-el-config-hero-header-element.html.twig';
import './sw-cms-el-config-hero-header-element.scss';

const { Component, Mixin, Context, Utils } = Shopware;

Component.register('sw-cms-el-config-hero-header-element', {
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
            heroImageModalIsOpen: false,
            heroBackgroundModalIsOpen: false,
        };
    },
    
    computed: {
        mediaRepository() {
            return this.repositoryFactory.create('media');
        },
        defaultFolderName() {
            return this.cmsPageState.pageEntityName;
        },
        uploadHeroImageTag() {
            return `cms-element-hero-image-config-${this.element.id}`;
        },
        uploadHeroBackgroundTag() {
            return `cms-element-hero-background-config-${this.element.id}`;
        },
        previewHeroImageSource() {
            if (this.element?.data?.heroImage?.id) {
                return this.element.data.heroImage;
            }
            
            return this.element.config.heroImage.value;
        },
        
        previewHeroBackgroundSource() {
            if (this.element?.data?.heroBackground?.id) {
                return this.element.data.heroBackground;
            }
            
            return this.element.config.heroBackground.value;
        },
        
        heroTitle: {
            get() {
                return this.element.config.heroTitle.value;
            },
            
            set(value) {
                this.element.config.heroTitle.value = value;
            }
        },
        heroText: {
            get() {
                return this.element.config.heroText.value;
            },
            
            set(value) {
                this.element.config.heroText.value = value;
            }
        },
        heroImage: {
            get() {
                return this.element.config.heroImage.value;
            },
            
            set(value) {
                this.element.config.heroImage.value = value;
            }
        },
        heroBackground: {
            get() {
                return this.element.config.heroBackground.value;
            },
            
            set(value) {
                this.element.config.heroBackground.value = value;
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
            this.initElementConfig('hero-header-element');
        },
        
        updateElementData(media = null) {
            const mediaId = media === null ? null : media.id;
            if (!this.element.data) {
                this.$set(this.element, 'data', { mediaId, media });
            } else {
                this.$set(this.element.data, 'heroImageId', mediaId);
                this.$set(this.element.data, 'heroImage', media);
            }
        },
        
        // Hero Image functions
        async onHeroImageUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);
            
            this.element.config.heroImage.value = mediaEntity.id;
            this.element.config.heroImage.source = 'static';
            
            this.updateElementData(mediaEntity);
            
            this.$emit('element-update', this.element);
        },
        onHeroImageRemove() {
            this.element.config.heroImage.value = null;
            
            this.updateElementData();
            
            this.$emit('element-update', this.element);
        },
        onOpenHeroImageModal() {
            this.heroImageModalIsOpen = true;
        },
        onCloseHeroImageModal() {
            this.heroImageModalIsOpen = false;
        },
        onHeroImageSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.heroImage.value = media.id;
            this.element.config.heroImage.source = 'static';
            
            this.updateElementData(media);
            
            this.$emit('element-update', this.element);
        },
        
        // Hero Background functions
        async onHeroBackgroundUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);
            
            this.element.config.heroBackground.value = mediaEntity.id;
            this.element.config.heroBackground.source = 'static';
            
            this.updateElementData(mediaEntity);
            
            this.$emit('element-update', this.element);
        },
        onHeroBackgroundRemove() {
            this.element.config.heroBackground.value = null;
            
            this.updateElementData();
            
            this.$emit('element-update', this.element);
        },
        onOpenHeroBackgroundModal() {
            this.heroBackgroundModalIsOpen = true;
        },
        onCloseHeroBackgroundModal() {
            this.heroBackgroundModalIsOpen = false;
        },
        onHeroBackgroundSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.heroBackground.value = media.id;
            this.element.config.heroBackground.source = 'static';
            
            this.updateElementData(media);
            
            this.$emit('element-update', this.element);
        },
    }
});
