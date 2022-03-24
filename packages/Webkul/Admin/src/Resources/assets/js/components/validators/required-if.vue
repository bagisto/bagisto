<template>
    <div class="control-group"  :class="[errors.has(name) ? 'has-error' : '']">
        <label :for="name" :class="checkValidations">
            {{ label }}
            <span class="locale"> {{ channel_locale }} </span>
        </label>

        <select v-if="this.options.length" v-validate="checkValidations" class="control" :id = "name" :name = "name" v-model="savedValue"
        :data-vv-as="label">
            <option v-for='(option, index) in this.options' :value="option.value"> {{ option.title }} </option>
        </select>

        <input v-else
            type="text"
            class="control"
            :id="name"
            :name="name"
            :placeholder="info"
            v-validate="checkValidations"
            v-model="savedValue"
            :data-vv-as="label">

        <span class="control-error" v-if="errors.has(name)">
            {{ errors.first(name) }}
        </span>
    </div>
</template>

<script>
export default {
    props: [
        'name',
        'label',
        'info',
        'options',
        'result',
        'validations',

        'depend',
        'dependResult',

        'channel_locale',
    ],

    inject: ['$validator'],

    data: function() {
        return {
            isRequire: false,
            checkValidations: [],

            savedValue: this.result,
            dependSavedValue: parseInt(this.dependResult)
        }
    },

    mounted: function () {
        this.isRequire = this.dependSavedValue ? true : false;
        this.updateValidations();

        $(document.getElementById(this.depend)).on('change', (e) => {
            this.dependSavedValue = !this.dependSavedValue;
            this.dependSavedValue = this.dependSavedValue ? 1 : 0;
            this.isRequire = this.dependSavedValue ? true : false;

            this.updateValidations();
        });
    },

    methods: {
        updateValidations: function () {
            this.checkValidations = this.validations.split('|').filter(validation => !this.validations.includes('required_if'));

            if (this.isRequire) {
                this.checkValidations.push('required');
            } else {
                this.checkValidations = this.checkValidations.filter((value) => {
                    return value !== 'required';
                });
            }

            this.checkValidations = this.checkValidations.join('|');
        }
    }
}
</script>