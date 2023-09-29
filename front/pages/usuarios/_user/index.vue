<template>
  <v-card
    class="mx-auto"
    max-width="100%"
    outlined
  >
    <v-list-item three-line>
      <v-list-item-content>
        <v-list-item-title class="text-h5 mb-1">
          {{ user.name }}
        </v-list-item-title>
        <v-list-item-subtitle>{{ user.email }}</v-list-item-subtitle>
      </v-list-item-content>

      <v-list-item-avatar
        tile
        size="80"
        color="grey"
      ></v-list-item-avatar>
    </v-list-item>

    <v-card-actions>
          <v-btn 
            outlined
            rounded
            text
            class="mr-2 white--text" 
            color="red" 
            :disabled="deleting"
            @click="destroy"
          >
            Deletar 
            <v-progress-circular
              v-if="deleting"
              indeterminate
              color="white"
              size="24"
              class="ml-2"
            ></v-progress-circular>
          </v-btn>

          <v-btn
            outlined
            rounded
            text
            class="mr-2" 
            :to="{ name: 'usuarios-user-editar', params: { user: user.id } }"
          >
            Editar
          </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  name: 'ShowUser',
  auth: true,

  data() {
    return {
      userId: null,
      user: {},
      deleting: false
    }
  },

  head() {
    return {
      title: "Editar usuário"
    };
  },

  created() {
    this.setLoader(true)
    this.userId = this.$route.params.user

    this.$axios.get(`users/${this.userId}`)
        .then(res => {
          this.user = res.data
        })
        .catch(error => {
          if (error.response.status === 404) {
            this.$toast.error('usuário não encontrado');
            this.$router.push({ name: 'usuarios' })

            return;
          }

          this.$toast.success('ocorreu um erro ao buscar o usuário');
          this.$router.push({ name: 'usuarios' })
        })
        .finally(() => this.setLoader())
  },

  methods: {
    ...mapActions('loader', ['setLoader']),

    destroy() {
      this.deleting = true;

      this.$axios.delete(`users/${this.userId}`)
        .then(() => {
          this.$toast.success('usuário deletado com sucesso')
          this.$router.push({ name: 'usuarios' })
        })
        .catch(() => this.$toast.error('ocorreu um erro ao deletar o usuário'))
        .finally(() => {
          this.deleting = false;
        })
    }
  },
}
</script>
