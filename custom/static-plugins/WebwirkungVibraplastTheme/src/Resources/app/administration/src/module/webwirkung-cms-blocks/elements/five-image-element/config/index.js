import template from './sw-cms-el-config-five-image-element.html.twig';
import './sw-cms-el-config-five-image-element.scss';

const { Component, Mixin, Context, Utils } = Shopware;

Component.register('sw-cms-el-config-five-image-element', {
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
            fiveImageOneModalIsOpen: false,
            fiveImageTwoModalIsOpen: false,
            fiveImageThreeModalIsOpen: false,
            fiveImageFourModalIsOpen: false,
            fiveImageFiveModalIsOpen: false,
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
        uploadFiveImageOneTag() {
            return `cms-element-five-image-one-config-${this.element.id}`;
        },
        uploadFiveImageTwoTag() {
            return `cms-element-five-image-two-config-${this.element.id}`;
        },
        uploadFiveImageThreeTag() {
            return `cms-element-five-image-three-config-${this.element.id}`;
        },
        uploadFiveImageFourTag() {
            return `cms-element-five-image-four-config-${this.element.id}`;
        },
        uploadFiveImageFiveTag() {
            return `cms-element-five-image-five-config-${this.element.id}`;
        },
        
        uploadBackgroundImageTag() {
            return `cms-element-hero-background-config-${this.element.id}`;
        },
        previewFiveImageOneSource() {
            if (this.element?.data?.fiveImageOne?.id) {
                return this.element.data.fiveImageOne;
            }
            
            return this.element.config.fiveImageOne.value;
        },
        
        previewFiveImageTwoSource() {
            if (this.element?.data?.fiveImageTwo?.id) {
                return this.element.data.fiveImageTwo;
            }
            
            return this.element.config.fiveImageTwo.value;
        },
        previewFiveImageThreeSource() {
            if (this.element?.data?.fiveImageThree?.id) {
                return this.element.data.fiveImageThree;
            }
            
            return this.element.config.fiveImageThree.value;
        },
        previewFiveImageFourSource() {
            if (this.element?.data?.fiveImageFour?.id) {
                return this.element.data.fiveImageFour;
            }
            
            return this.element.config.fiveImageFour.value;
        },
        previewFiveImageFiveSource() {
            if (this.element?.data?.fiveImageFive?.id) {
                return this.element.data.fiveImageFive;
            }
            
            return this.element.config.fiveImageFive.value;
        },
        
        previewBackgroundImageSource() {
            if (this.element?.data?.backgroundImage?.id) {
                return this.element.data.backgroundImage;
            }
            
            return this.element.config.backgroundImage.value;
        },
        fiveImageOne: {
            get() {
                return this.element.config.fiveImageOne.value;
            },
            
            set(value) {
                this.element.config.fiveImageOne.value = value;
            }
        },
        fiveImageTwo: {
            get() {
                return this.element.config.fiveImageTwo.value;
            },
            
            set(value) {
                this.element.config.fiveImageTwo.value = value;
            }
        },
        fiveImageThree: {
            get() {
                return this.element.config.fiveImageThree.value;
            },
            
            set(value) {
                this.element.config.fiveImageThree.value = value;
            }
        },
        fiveImageFour: {
            get() {
                return this.element.config.fiveImageFour.value;
            },
            
            set(value) {
                this.element.config.fiveImageFour.value = value;
            }
        },
        fiveImageFive: {
            get() {
                return this.element.config.fiveImageFive.value;
            },
            
            set(value) {
                this.element.config.fiveImageFive.value = value;
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
            this.initElementConfig('five-image-element');
        },
        
        updateElementData(media = null) {
            const mediaId = media === null ? null : media.id;
            if (!this.element.data) {
                this.$set(this.element, 'data', { mediaId, media });
            } else {
                this.$set(this.element.data, 'fiveImageOneId', mediaId);
                this.$set(this.element.data, 'fiveImageOne', media);
            }
        },
        
        // First Image
        async onFiveImageOneUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);
            
            this.element.config.fiveImageOne.value = mediaEntity.id;
            this.element.config.fiveImageOne.source = 'static';
            
            this.updateElementData(mediaEntity);
            
            this.$emit('element-update', this.element);
        },
        onFiveImageOneRemove() {
            this.element.config.fiveImageOne.value = null;
            
            this.updateElementData();
            
            this.$emit('element-update', this.element);
        },
        onOpenFiveImageOneModal() {
            this.fiveImageOneModalIsOpen = true;
        },
        onCloseFiveImageOneModal() {
            this.fiveImageOneModalIsOpen = false;
        },
        onFiveImageOneSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.fiveImageOne.value = media.id;
            this.element.config.fiveImageOne.source = 'static';
            
            this.updateElementData(media);
            
            this.$emit('element-update', this.element);
        },
        
        // Second Image
        async onFiveImageTwoUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);
            
            this.element.config.fiveImageTwo.value = mediaEntity.id;
            this.element.config.fiveImageTwo.source = 'static';
            
            this.updateElementData(mediaEntity);
            
            this.$emit('element-update', this.element);
        },
        onFiveImageTwoRemove() {
            this.element.config.fiveImageTwo.value = null;
            
            this.updateElementData();
            
            this.$emit('element-update', this.element);
        },
        onOpenFiveImageTwoModal() {
            this.fiveImageTwoModalIsOpen = true;
        },
        
        onCloseFiveImageTwoModal() {
            this.fiveImageTwoModalIsOpen = false;
        },
        onFiveImageTwoSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.fiveImageTwo.value = media.id;
            this.element.config.fiveImageTwo.source = 'static';
            
            this.updateElementData(media);
            
            this.$emit('element-update', this.element);
        },
        
        // Third Image
        async onFiveImageThreeUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);
            
            this.element.config.fiveImageThree.value = mediaEntity.id;
            this.element.config.fiveImageThree.source = 'static';
            
            this.updateElementData(mediaEntity);
            
            this.$emit('element-update', this.element);
        },
        onFiveImageThreeRemove() {
            this.element.config.fiveImageThree.value = null;
            
            this.updateElementData();
            
            this.$emit('element-update', this.element);
        },
        onOpenFiveImageThreeModal() {
            this.fiveImageThreeModalIsOpen = true;
        },
        
        onCloseFiveImageThreeModal() {
            this.fiveImageThreeModalIsOpen = false;
        },
        onFiveImageThreeSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.fiveImageThree.value = media.id;
            this.element.config.fiveImageThree.source = 'static';
            
            this.updateElementData(media);
            
            this.$emit('element-update', this.element);
        },
        
        // Fourth Image
        async onFiveImageFourUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);
            
            this.element.config.fiveImageFour.value = mediaEntity.id;
            this.element.config.fiveImageFour.source = 'static';
            
            this.updateElementData(mediaEntity);
            
            this.$emit('element-update', this.element);
        },
        onFiveImageFourRemove() {
            this.element.config.fiveImageFour.value = null;
            
            this.updateElementData();
            
            this.$emit('element-update', this.element);
        },
        onOpenFiveImageFourModal() {
            this.fiveImageFourModalIsOpen = true;
        },
        
        onCloseFiveImageFourModal() {
            this.fiveImageFourModalIsOpen = false;
        },
        onFiveImageFourSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.fiveImageFour.value = media.id;
            this.element.config.fiveImageFour.source = 'static';
            
            this.updateElementData(media);
            
            this.$emit('element-update', this.element);
        },
        
        // Fifth Image
        async onFiveImageFiveUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);
            
            this.element.config.fiveImageFive.value = mediaEntity.id;
            this.element.config.fiveImageFive.source = 'static';
            
            this.updateElementData(mediaEntity);
            
            this.$emit('element-update', this.element);
        },
        onFiveImageFiveRemove() {
            this.element.config.fiveImageFive.value = null;
            
            this.updateElementData();
            
            this.$emit('element-update', this.element);
        },
        onOpenFiveImageFiveModal() {
            this.fiveImageFiveModalIsOpen = true;
        },
        
        onCloseFiveImageFiveModal() {
            this.fiveImageFiveModalIsOpen = false;
        },
        onFiveImageFiveSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.fiveImageFive.value = media.id;
            this.element.config.fiveImageFive.source = 'static';
            
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
