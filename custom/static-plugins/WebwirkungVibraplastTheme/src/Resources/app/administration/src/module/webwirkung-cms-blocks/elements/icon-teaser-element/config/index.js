import template from './sw-cms-el-config-icon-teaser-element.html.twig';
import './sw-cms-el-config-icon-teaser-element.scss';

const { Component, Mixin, Context, Utils } = Shopware;

Component.register('sw-cms-el-config-icon-teaser-element', {
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
            iconTeaserImageModalIsOpen: false,
        };
    },
    
    computed: {
        uploadIconTeaserImageTag() {
            return `cms-element-icon-teaser-image-config-${this.element.id}`;
        },
        previewIconTeaserImageSource() {
            if (this.element?.data?.iconTeaserImage?.id) {
                return this.element.data.iconTeaserImage;
            }
            
            return this.element.config.iconTeaserImage.value;
        },
        iconTeaserImage: {
            get() {
                return this.element.config.iconTeaserImage.value;
            },
            
            set(value) {
                this.element.config.iconTeaserImage.value = value;
            }
        },
        iconTeaserBackground: {
            get() {
                return this.element.config.iconTeaserBackground.value;
            },
            
            set(value) {
                this.element.config.iconTeaserBackground.value = value;
            }
        },
        iconBackground: {
            get() {
                return this.element.config.iconBackground.value;
            },
            
            set(value) {
                this.element.config.iconBackground.value = value;
            }
        },
        iconPosition: {
            get() {
                return this.element.config.iconPosition.value;
            },
            
            set(value) {
                this.element.config.iconPosition.value = value;
            }
        },
        iconTeaserText: {
            get() {
                return this.element.config.iconTeaserText.value;
            },
            
            set(value) {
                this.element.config.iconTeaserText.value = value;
            }
        },
        iconTeaserSmallSpace: {
            get() {
                return this.element.config.iconTeaserSmallSpace.value;
            },
            
            set(value) {
                this.element.config.iconTeaserSmallSpace.value = value;
            }
        },
        iconTeaserTitleBefore: {
            get() {
                return this.element.config.iconTeaserTitleBefore.value;
            },
            
            set(value) {
                this.element.config.iconTeaserTitleBefore.value = value;
            }
        },
    },
    created() {
        this.createdComponent();
    },
    
    methods: {
        createdComponent() {
            this.initElementConfig('icon-teaser-element');
        },
        updateElementData(media = null) {
            const mediaId = media === null ? null : media.id;
            if (!this.element.data) {
                this.$set(this.element, 'data', { mediaId, media });
            } else {
                this.$set(this.element.data, 'iconTeaserImageId', mediaId);
                this.$set(this.element.data, 'iconTeaserImage', media);
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
        async onIconTeaserImageUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);
            
            this.element.config.iconTeaserImage.value = mediaEntity.id;
            this.element.config.iconTeaserImage.source = 'static';
            
            this.updateElementData(mediaEntity);
            
            this.$emit('element-update', this.element);
        },
        onIconTeaserImageRemove() {
            this.element.config.iconTeaserImage.value = null;
            
            this.updateElementData();
            
            this.$emit('element-update', this.element);
        },
        onOpenIconTeaserImageModal() {
            this.iconTeaserImageModalIsOpen = true;
        },
        onCloseIconTeaserImageModal() {
            this.iconTeaserImageModalIsOpen = false;
        },
        onIconTeaserImageSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.iconTeaserImage.value = media.id;
            this.element.config.iconTeaserImage.source = 'static';
            
            this.updateElementData(media);
            
            this.$emit('element-update', this.element);
        },
        
    }
});
