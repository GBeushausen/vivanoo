import template from './sw-cms-el-config-image-caption-element.html.twig';

const { Component, Mixin, Context, Utils } = Shopware;

Component.register('sw-cms-el-config-image-caption-element', {
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
            imageCaptionImageModalIsOpen: false,
        };
    },
    
    computed: {
        uploadimageCaptionImageTag() {
            return `cms-element-icon-teaser-image-config-${this.element.id}`;
        },
        previewimageCaptionImageSource() {
            if (this.element?.data?.imageCaptionImage?.id) {
                return this.element.data.imageCaptionImage;
            }
            
            return this.element.config.imageCaptionImage.value;
        },
        imageCaptionImage: {
            get() {
                return this.element.config.imageCaptionImage.value;
            },
            
            set(value) {
                this.element.config.imageCaptionImage.value = value;
            }
        },
        imageCaption: {
            get() {
                return this.element.config.imageCaption.value;
            },
            
            set(value) {
                this.element.config.imageCaption.value = value;
            }
        },
    },
    created() {
        this.createdComponent();
    },
    
    methods: {
        createdComponent() {
            this.initElementConfig('image-caption-element');
        },
        updateElementData(media = null) {
            const mediaId = media === null ? null : media.id;
            if (!this.element.data) {
                this.$set(this.element, 'data', { mediaId, media });
            } else {
                this.$set(this.element.data, 'imageCaptionImageId', mediaId);
                this.$set(this.element.data, 'imageCaptionImage', media);
            }
        },
        onElementUpdateContent(value) {
            // this.element.config.headerItem.value.content = value;
            this.$emit('element-update', this.element);
        },
        onElementUpdateActive(value) {
            // this.element.config.headerItem.value.active = value;
            this.$emit('element-update', this.element);
        },
        
        // Image
        async onimageCaptionImageUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);
            
            this.element.config.imageCaptionImage.value = mediaEntity.id;
            this.element.config.imageCaptionImage.source = 'static';
            
            this.updateElementData(mediaEntity);
            
            this.$emit('element-update', this.element);
        },
        onimageCaptionImageRemove() {
            this.element.config.imageCaptionImage.value = null;
            
            this.updateElementData();
            
            this.$emit('element-update', this.element);
        },
        onOpenimageCaptionImageModal() {
            this.imageCaptionImageModalIsOpen = true;
        },
        onCloseimageCaptionImageModal() {
            this.imageCaptionImageModalIsOpen = false;
        },
        onimageCaptionImageSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.imageCaptionImage.value = media.id;
            this.element.config.imageCaptionImage.source = 'static';
            
            this.updateElementData(media);
            
            this.$emit('element-update', this.element);
        },
        
    }
});
