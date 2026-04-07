const { Component } = Shopware;

Component.extend('webwirkung-glossary-create', 'webwirkung-glossary-detail', {
    methods: {
        getGlossary() {
            if (!Shopware.State.getters['context/isSystemDefaultLanguage']) {
              this.changedToDefaultLanguage = true
              Shopware.Context.api.languageId = Shopware.Context.api.systemLanguageId;
            } else {
              this.changedToDefaultLanguage = false
            }
            this.glossary = this.repository.create(Shopware.Context.api);
        },

        onClickSave() {
            this.isLoading = true;
            if (!this.glossary.name) {
              this.hasError = true;
            }
            this.repository
              .save(this.glossary, Shopware.Context.api)
              .then(() => {
                  this.isLoading = false;
                  this.$router.push({ name: 'webwirkung.glossary.detail', params: { id: this.glossary.id } });
              }).catch((exception) => {
                this.isLoading = false;

                this.createNotificationError({
                    title: this.$t('webwirkung.glossary.detail.errorTitle'),
                    message: exception
                });
            });
        }
    },
});
