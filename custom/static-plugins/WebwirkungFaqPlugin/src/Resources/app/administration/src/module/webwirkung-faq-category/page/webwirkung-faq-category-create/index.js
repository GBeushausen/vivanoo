const { Component } = Shopware;

Component.extend('webwirkung-faq-category-create', 'webwirkung-faq-category-detail', {
    methods: {
        getFaqCategory() {
            if (!Shopware.State.getters['context/isSystemDefaultLanguage']) {
              this.changedToDefaultLanguage = true
              Shopware.Context.api.languageId = Shopware.Context.api.systemLanguageId;
            } else {
              this.changedToDefaultLanguage = false
            }
            this.faqCategory = this.repositoryCategory.create(Shopware.Context.api);
        },

        onClickSave() {
            this.isLoading = true;

            this.repositoryCategory
              .save(this.faqCategory, Shopware.Context.api)
              .then(() => {
                  this.isLoading = false;
                  this.$router.push({ name: 'webwirkung.faq.category.detail', params: { id: this.faqCategory.id } });
              }).catch((exception) => {
                this.isLoading = false;

                this.createNotificationError({
                    title: this.$t('faq-category.detail.errorTitle'),
                    message: exception
                });
            });
        }
    },
});
