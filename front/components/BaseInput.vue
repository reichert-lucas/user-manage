<template>
    <div>
        <validation-provider v-slot="{ errors }" :name="validationName" :rules="rules">
            <div class="ma-0">
                <v-text-field
                    v-if="mask.length"
                    v-model="model"
                    v-mask="mask" 
                    :label="label"
                    filled
                    clearable
                    hide-details
                    v-bind="$attrs" 
                    :disabled="disabled"
                    @keyup="$emit('keyup')"
                />
    
                <v-text-field
                    v-else
                    v-model="model"
                    :label="label"
                    filled
                    clearable
                    hide-details
                    v-bind="$attrs" 
                    :disabled="disabled"
                    @keyup="$emit('keyup')"
                />
            </div>

            <span class="ma-0 red--text">{{ errors[0] }}</span>
        </validation-provider>
    </div>
</template>

<script>
export default {
    props: {
        label: {
            required: true,
            type: String
        },
        validationName: {
            required: false,
            type: String,
            default: null,
        },
        rules: {
            required: false,
            type: [String, Object],
            default: null,
        },
        mask: {
            required: false,
            type: [String, Array],
            default: () => []
        },
        value: {
            type: [String, Number],
            default: ''
        },
        disabled: {
            type: Boolean
        }
    },

    data() {
      return {
        model: this.value
      }
    },

    watch: {
      model(currentValue) {
        this.$emit('input', currentValue)
      },
      value(currentValue) {
        this.model = currentValue
      }
    },

    methods: {
        changePasswordVisibility() {
            if (this.$refs.passwordInput.type === 'password') {
                this.$refs.passwordInput.type = "text"
                return
            }

            this.$refs.passwordInput.type = "password"
        }
    },
}
</script>