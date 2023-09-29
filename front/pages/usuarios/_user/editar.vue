<template>
  <v-card class="pa-8">
    <v-container>
      <h2 class="mb-6 blue-grey--text">Editar usuário</h2>

      <div v-if="loading">Carregando...</div>
      <users-form-manager v-else-if="Object.keys(user).length" :user="user"/>
      <div v-else>Usuário não encontrado!</div>
    </v-container>
  </v-card>
</template>

<script>
import { mapActions, mapState } from 'vuex';

export default {
  name: 'EditUser',
  auth: true,

  data() {
    return {
      userId: null,
      user: {}
    }
  },

  head() {
    return {
      title: "Editar usuário"
    };
  },

  computed: {
    ...mapState('loader', ['loading'])
  },

  created() {
    this.setLoader(true)
    this.userId = this.$route.params.user

    this.$axios.get(`users/${this.userId}`)
        .then(res => {
          this.user = res.data
        })
        .finally(() => this.setLoader())
  },

  methods: {
    ...mapActions('loader', ['setLoader']),
  },
}
</script>
