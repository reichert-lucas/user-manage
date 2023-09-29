<template>
  <v-navigation-drawer
    permanent
    expand-on-hover
    style="position: fixed; left: 0; bottom: 0; z-index: 1000"
  >
    <v-list-item class="px-2">
      <v-list-item-avatar>
        <img src="~/assets/imgs/user.png" />
      </v-list-item-avatar>

      <v-list-item-title>{{ $auth.user.name }}</v-list-item-title>
    </v-list-item>

    <v-divider></v-divider>

    <v-list dense>
      <v-list-item
        v-for="route in routes"
        :key="route.path"
        :to="route.path"
        link
      >
        <v-list-item-icon>
          <v-icon>{{ route.icon }}</v-icon>
        </v-list-item-icon>

        <v-list-item-content>
          <v-list-item-title>{{ route.title }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list>

    <template #append>
      <v-list dense>
        <v-list-item @click="logout()">
          <v-list-item-icon>
            <v-icon>mdi-logout</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>Sair</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </template>
  </v-navigation-drawer>
</template>

<script>
import { mapActions } from 'vuex'
export default {
  data: () => ({
    routes: [
      { title: 'Início', icon: 'mdi-chart-arc', path: '/' },
      { title: 'Perfil', icon: 'mdi-account', path: '/perfil' },
      {
        title: 'Usuários',
        icon: 'mdi-account-group',
        path: '/usuarios',
      },
    ],
  }),

  methods: {
    ...mapActions('loader', ['setLoader']),

    logout() {
      this.setLoader(true)

      this.$auth.logout().finally(() => {
        this.$toast.success('logout efetuado com sucesso')
        this.$router.push({ name: 'login' })
        this.setLoader()
      })
    },
  },
}
</script>
