import template from './sw-cms-el-config-double-image-element.html.twig';
import './sw-cms-el-config-double-image-element.scss';

const { Component, Mixin, Context, Utils } = Shopware;

Component.register('sw-cms-el-config-double-image-element', {
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
            overlayImageOneModalIsOpen: false,
            overlayImageTwoModalIsOpen: false,
            backgroundImageModalIsOpen: false,
        };
    },
    
    computed: {
        mediaRepository() {
            return this.repositoryFactory.create('media');
        },
        defaultFolderName() {
            return this.cmsPageState.pageEntityName;
        },
        uploadOverlayImageOneTag() {
            return `cms-element-double-image-one-config-${this.element.id}`;
        },
        uploadOverlayImageTwoTag() {
            return `cms-element-double-image-two-config-${this.element.id}`;
        },
        uploadBackgroundImageTag() {
            return `cms-element-hero-background-config-${this.element.id}`;
        },
        previewOverlayImageOneSource() {
            if (this.element?.data?.overlayImageOne?.id) {
                return this.element.data.overlayImageOne;
            }
            
            return this.element.config.overlayImageOne.value;
        },
        
        previewOverlayImageTwoSource() {
            if (this.element?.data?.overlayImageTwo?.id) {
                return this.element.data.overlayImageTwo;
            }
            
            return this.element.config.overlayImageTwo.value;
        },
        
        previewBackgroundImageSource() {
            if (this.element?.data?.backgroundImage?.id) {
                return this.element.data.backgroundImage;
            }
            
            return this.element.config.backgroundImage.value;
        },
        overlayImageOne: {
            get() {
                return this.element.config.overlayImageOne.value;
            },
            
            set(value) {
                this.element.config.overlayImageOne.value = value;
            }
        },
        overlayImageTwo: {
            get() {
                return this.element.config.overlayImageTwo.value;
            },
            
            set(value) {
                this.element.config.overlayImageTwo.value = value;
            }
        },
        backgroundImage: {
            get() {
                return this.element.config.backgroundImage.value;
            },
            
            set(value) {
                this.element.config.backgroundImage.value = value;
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
            this.initElementConfig('double-image-element');
        },
        
        updateElementData(media = null) {
            const mediaId = media === null ? null : media.id;
            if (!this.element.data) {
                this.$set(this.element, 'data', { mediaId, media });
            } else {
                this.$set(this.element.data, 'overlayImageOneId', mediaId);
                this.$set(this.element.data, 'overlayImageOne', media);
            }
        },
        
        // Hero Image functions
        async onOverlayImageOneUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);
            
            this.element.config.overlayImageOne.value = mediaEntity.id;
            this.element.config.overlayImageOne.source = 'static';
            
            this.updateElementData(mediaEntity);
            
            this.$emit('element-update', this.element);
        },
        async onOverlayImageTwoUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);
            
            this.element.config.overlayImageTwo.value = mediaEntity.id;
            this.element.config.overlayImageTwo.source = 'static';
            
            this.updateElementData(mediaEntity);
            
            this.$emit('element-update', this.element);
        },
        onOverlayImageOneRemove() {
            this.element.config.overlayImageOne.value = null;
            
            this.updateElementData();
            
            this.$emit('element-update', this.element);
        },
        onOverlayImageTwoRemove() {
            this.element.config.overlayImageTwo.value = null;
            
            this.updateElementData();
            
            this.$emit('element-update', this.element);
        },
        onOpenOverlayImageOneModal() {
            this.overlayImageOneModalIsOpen = true;
        },
        onOpenOverlayImageTwoModal() {
            this.overlayImageTwoModalIsOpen = true;
        },
        onCloseOverlayImageOneModal() {
            this.overlayImageOneModalIsOpen = false;
        },
        
        onCloseOverlayImageTwoModal() {
            this.overlayImageTwoModalIsOpen = false;
        },
        onOverlayImageOneSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.overlayImageOne.value = media.id;
            this.element.config.overlayImageOne.source = 'static';
            
            this.updateElementData(media);
            
            this.$emit('element-update', this.element);
        },
        onOverlayImageTwoSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.overlayImageTwo.value = media.id;
            this.element.config.overlayImageTwo.source = 'static';
            
            this.updateElementData(media);
            
            this.$emit('element-update', this.element);
        },
        
        // Hero Background functions
        async onBackgroundImageUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);
            
            this.element.config.backgroundImage.value = mediaEntity.id;
            this.element.config.backgroundImage.source = 'static';
            
            this.updateElementData(mediaEntity);
            
            this.$emit('element-update', this.element);
        },
        onBackgroundImageRemove() {
            this.element.config.backgroundImage.value = null;
            
            this.updateElementData();
            
            this.$emit('element-update', this.element);
        },
        onOpenBackgroundImageModal() {
            this.backgroundImageModalIsOpen = true;
        },
        onCloseBackgroundImageModal() {
            this.backgroundImageModalIsOpen = false;
        },
        onBackgroundImageSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.backgroundImage.value = media.id;
            this.element.config.backgroundImage.source = 'static';
            
            this.updateElementData(media);
            
            this.$emit('element-update', this.element);
        },
    }
});
