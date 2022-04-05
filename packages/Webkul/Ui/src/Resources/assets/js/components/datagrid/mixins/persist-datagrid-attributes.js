export default {
    methods: {
        getDatagridsInfoStorageKey() {
            return 'datagridsInfo';
        },

        analyzeDatagridsInfo() {
            if (!this.isDataLoaded && this.url === `${this.src}?v=1`) {
                let datagridInfo = this.getCurrentDatagridInfo();

                if (datagridInfo) {
                    /**
                     * Will check this later. Don't remove it.
                     */
                    // this.filterCurrentDatagridFromDatagridsInfo();

                    this.url = datagridInfo.previousUrl;
                    this.filters = datagridInfo.previousFilters;
                }
            } else {
                let datagridsInfo = this.getDatagridsInfo();

                if (datagridsInfo && datagridsInfo.length > 0) {
                    if (this.isCurrentDatagridInfoExists()) {
                        datagridsInfo = datagridsInfo.map(datagrid => {
                            if (datagrid.id === this.id) {
                                return this.getDatagridsInfoDefaults();
                            }

                            return datagrid;
                        });
                    } else {
                        datagridsInfo.push(this.getDatagridsInfoDefaults());
                    }
                } else {
                    datagridsInfo = [this.getDatagridsInfoDefaults()];
                }

                this.updateDatagridsInfo(datagridsInfo);
            }
        },

        isCurrentDatagridInfoExists() {
            let datagridsInfo = this.getDatagridsInfo();

            return !!datagridsInfo.find(({ id }) => id === this.id);
        },

        getCurrentDatagridInfo() {
            let datagridsInfo = this.getDatagridsInfo();

            return this.isCurrentDatagridInfoExists()
                ? datagridsInfo.find(({ id }) => id === this.id)
                : null;
        },

        getDatagridsInfoDefaults() {
            return {
                id: this.id,
                previousFilters: this.filters,
                previousUrl: this.url
            };
        },

        getDatagridsInfo() {
            let storageInfo = localStorage.getItem(
                this.getDatagridsInfoStorageKey()
            );

            return !this.isValidJsonString(storageInfo)
                ? []
                : JSON.parse(storageInfo) ?? [];
        },

        updateDatagridsInfo(info) {
            localStorage.setItem(
                this.getDatagridsInfoStorageKey(),
                JSON.stringify(info)
            );
        },

        filterCurrentDatagridFromDatagridsInfo() {
            let datagridsInfo = this.getDatagridsInfo();

            datagridsInfo = datagridsInfo.filter(({ id }) => id !== this.id);

            this.updateDatagridsInfo(datagridsInfo);
        },

        isValidJsonString(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        }
    }
};
