<template>
  <v-card class="pa-8">
    <v-container>
      <h2 class="mb-6 blue-grey--text">Editar perfil</h2>

      <v-row class="d-flex justify-center mb-6">
        <photo-input />
      </v-row>

      <validation-observer v-slot="{ handleSubmit }" ref="formValidator">
        <form @submit.prevent="handleSubmit(sendLogin)">
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
            <v-btn
              class="mr-2"
              :to="{ name: 'index' }" 
            >
              Cancelar
            </v-btn>

            <v-btn
              color="light-blue accent-4"
              prepend-icon="mdi-door"
              class="white--text"
              type="submit"
              :disablad="loading"
            >
              {{ loading ? 'Salvando...' : 'Salvar' }}
            </v-btn>
          </div>
        </form>
      </validation-observer>
    </v-container>
  </v-card>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  name: 'ProfileIndex',
  auth: true,

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

  head() {
    return {
      title: "Perfil"
    };
  },

  created() {
    this.form = Object.assign({}, this.$auth.user);
    this.form.password = null;
  },

  methods: {
    ...mapActions('profile', ['updateProfile']),

    sendLogin() {
      this.loading = true

      this.updateProfile(this.form)
        .then(() => this.$toast.success('perfil atualizado com sucesso'))
        .finally(() => {
          this.loading = false
        })
    },
  },
}
</script>
