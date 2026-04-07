const { Component } = Shopware;

Component.extend('webwirkung-faq-create', 'webwirkung-faq-detail', {
    methods: {
        getFaq() {
            if (!Shopware.State.getters['context/isSystemDefaultLanguage']) {
              this.changedToDefaultLanguage = true
              Shopware.Context.api.languageId = Shopware.Context.api.systemLanguageId;
            } else {
              this.changedToDefaultLanguage = false
            }
            this.faq = this.repository.create(Shopware.Context.api);
        },

        onClickSave() {
            this.isLoading = true;
            if (!this.faq.question) {
              this.hasError = true;
            }
            this.repository
              .save(this.faq, Shopware.Context.api)
              .then(() => {
                  this.isLoading = false;
                  this.$router.push({ name: 'webwirkung.faq.detail', params: { id: this.faq.id } });
              }).catch((exception) => {
                this.isLoading = false;

                this.createNotificationError({
                    title: this.$t('faq.detail.errorTitle'),
                    message: exception
                });
            });
        }
    },
});
