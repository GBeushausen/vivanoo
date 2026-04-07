import template from './sw-cms-el-config-history-element.html.twig';
import './sw-cms-el-config-history-element.scss';

const { Component, Mixin, Context, Utils } = Shopware;

Component.register('sw-cms-el-config-history-element', {
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
            initialFolderId: null,
            historyImagePreviews: [],
            counter: 1,
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
            this.initElementConfig('history-element');
        },
    
        onClickAddItem() {
            this.element.config.historyItems.value.push({
                active: true,
                id: Utils.createId(),
                contentType: 'default',
            
                // Content
                year: '2024',
                text: '<ul><li>Lorem ipsum dolor sit amet</li></ul>',
                historyImage: null,
            });
            this.getMediaEntityPreviews();
        },
    
        onClickRemoveItem(item) {
            const items = this.element.config.historyItems.value;
            const index = items.indexOf(item);
        
            items.splice(index, 1);
        
            if (items.length === 1) {
                items[0].active = true;
            }
        
            this.element.config.historyItems.value = items;
            this.getMediaEntityPreviews();
        },
        
        async asyncForEach(array, callback) {
            for (let i = 0; i < array.length; i++) {
                await callback(array[i], i, array);
            }
        },
        
        async getMediaEntityPreviews() {
            if (this.element.config.historyItems.value) {
                this.historyImagePreviews = [];
                
                await this.asyncForEach(
                  this.element.config.historyItems.value,
                  async (historyItem) => {
                      if (historyItem.historyImage) {
                          const mediaEntity = await this.mediaRepository.get(
                            historyItem.historyImage,
                            Context.api
                          );
                          
                          this.historyImagePreviews.push(mediaEntity);
                      } else {
                          this.historyImagePreviews.push(null);
                      }
                  }
                );
            }
        },
        onRemoveHistoryImage(historyItem) {
            const historyItems = this.element.config.historyItems.value;
            const index = historyItems.indexOf(historyItem);
            
            this.$set(this.historyImagePreviews, index, null);
            historyItem.historyImage = null;
        },
        onChangeHistoryImage(mediaEntity, historyItem) {
            const historyItems = this.element.config.historyItems.value;
            const index = historyItems.indexOf(historyItem);
            
            this.$set(this.historyImagePreviews, index, mediaEntity[0]);
            historyItem.historyImage = mediaEntity[0].id;
        },
        async onFinishUploadHistoryImage(mediaItem, historyItem) {
            const mediaEntity = await this.mediaRepository.get(
              mediaItem.targetId,
              Context.api
            );
            const historyItems = this.element.config.historyItems.value;
            const index = historyItems.indexOf(historyItem);
            
            this.$set(this.historyImagePreviews, index, mediaEntity);
            historyItem.historyImage = mediaEntity.id;
            this.refreshMediaEntityPreviews(mediaEntity.id);
        },
        async refreshMediaEntityPreviews(mediaEntityId) {
            const mediaEntity = await this.mediaRepository.get(
              mediaEntityId,
              Context.api
            );
            if (this.element.config.historyItems.value) {
                this.element.config.historyItems.value.forEach((historyItem, index) => {
                    if (historyItem.historyImage === mediaEntityId) {
                        this.$set(
                          this.historyImagePreviews,
                          index,
                          mediaEntity
                        );
                    }
                    if (historyItem.overlayMedia === mediaEntityId) {
                        this.$set(
                          this.historyItemOverlayMediaPreviews,
                          index,
                          mediaEntity
                        );
                    }
                });
            }
        },
    }
});
