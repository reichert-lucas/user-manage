<template>
  <validation-observer v-slot="{ handleSubmit }" ref="form">
    <form @submit.prevent="handleSubmit(save)">
      <v-row>
        <v-col>
          <base-input
            v-model="form.name"
            type="text"
            label="Nome"
            rules="required"
            validation-name="nome"
            class="mb-4"
          />
        </v-col>
      </v-row>

      <v-row>
        <v-col>
          <base-input
            v-model="form.email"
            type="email"
            label="E-mail"
            rules="required|email"
            validation-name="email"
            class="mb-4"
            autocomplete="email"
          />
        </v-col>
      </v-row>

      <v-row>
        <v-col>
          <base-input
            v-model="form.password"
            type="password"
            label="Senha"
            rules="min:8"
            validation-name="senha"
            class="mb-4"
            autocomplete="current-password"
          />
        </v-col>
      </v-row>

      <div class="d-flex justify-end">
        <v-btn class="mr-2" :to="{ name: 'usuarios' }">Voltar</v-btn>

        <v-btn
          color="light-blue accent-4"
          prepend-icon="mdi-door"
          class="white--text"
          type="submit"
          :disabled="loading"
        >
          {{ loading ? 'Salvando...' : 'Salvar' }}
        </v-btn>
      </div>
    </form>
  </validation-observer>
</template>
<script>
export default {
  props: {
    user: {
      type: Object,
      required: false,
      default: () => ({})
    }
  },

  data() {
    return {
      form: {
        name: null,
        email: null,
        password: null,
      },
      loading: false,
    }
  },

  created() {
    if (Object.keys(this.user).length) {
      this.form = Object.assign({}, this.user);
    }
  },

  methods: {
    save() {
      if (Object.keys(this.user).length) {
        this.update();
        return;
      }

      this.store();
    },

    store() {
      this.loading = true

      this.$axios
        .post('/users', this.form)
        .then(() => {
          this.$toast.success('usuário criado com sucesso')
          this.resetForm()
        })
        .catch(error => this.$returnErrorMsg(error, 'ocorreu um erro ao criar o usuário'))
        .finally(() => {
          this.loading = false
        })
    },

    update() {
      this.loading = true

      this.$axios
        .put(`/users/${this.user.id}`, this.form)
        .then(() => {
          this.$toast.success('usuário atualizado com sucesso')
        })
        .catch(error => this.$returnErrorMsg(error, 'ocorreu um erro ao atualizar o usuário'))
        .finally(() => {
          this.loading = false
        })
    },

    resetForm() {
      this.form = {
        name: null,
        email: null,
        password: null,
      }

      this.$refs.form.reset()
    }
  },
}
</script>
