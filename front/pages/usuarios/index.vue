<template>
  <v-card class="pa-8">
    <v-container fluid>
      <h2 class="mb-6 blue-grey--text">Usuários</h2>

      <v-data-iterator
        :items="users"
        hide-default-footer
        no-results-text="Nenhum usuário encontrado"
        no-data-text="Nenhum usuário encontrado"
      >
        <template #header>
          <v-toolbar 
            dark
            color="blue darken-3"
            class="mb-4 px-2"
          >
            <v-row class="d-flex justify-between">
              <v-text-field
                v-model="search"
                clearable
                flat
                solo-inverted
                hide-details
                prepend-inner-icon="mdi-magnify"
                label="Buscar"
                append-icon="mdi-plus-circle"
                @keypress.enter="callLoadUsers"
                @click:append="$router.push({ name: 'usuarios-novo' })"
              />
            </v-row>
          </v-toolbar>
        </template>

        <template #default>
          <v-row>
            <v-col
              v-for="user in users"
              :key="user.id"
              cols="12"
            >
              <v-card
                class="mx-auto"
                elevation="6"
                outlined
              >
                <v-list-item three-line>
                  <v-list-item-content>
                    <div class="text-overline mb-4 blue--text">
                      Usuário
                    </div>
                    <v-list-item-title class="text-h5 mb-1">
                      {{ user.name }}
                    </v-list-item-title>
                    <v-list-item-subtitle>{{ user.email }}</v-list-item-subtitle>
                  </v-list-item-content>

                  <v-avatar size="64">
                    <img
                      alt="user"
                      src="~/assets/imgs/user.png"
                    >
                  </v-avatar>
                </v-list-item>

                <v-card-actions class="d-flex justify-end flex-wrap mb-2">
                  <v-btn
                    class="ma-2"
                    :to="{ name: 'usuarios-user-editar', params: { user: user.id } }"
                  >
                    <v-icon size="20"> mdi-pencil </v-icon>
                    Editar
                  </v-btn>

                  <v-btn
                    class="ma-2 white--text"
                    color="red"
                    :disabled="deleting"
                    @click="destroy(user.id)"
                  >
                    <v-icon size="20"> mdi-trash-can </v-icon>
                    Remover
                    <v-progress-circular
                      v-if="deleting"
                      indeterminate
                      color="white"
                      size="24"
                      class="ml-2"
                    ></v-progress-circular>
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-col>
          </v-row>
        </template>

        <template #footer>
          <v-row
            class="mt-2"
            align="center"
            justify="center"
          >
            <span class="grey--text">Itens por página</span>
            <v-menu offset-y>
              <template #activator="{ on, attrs }">
                <v-btn
                  dark
                  text
                  color="primary"
                  class="ml-2"
                  v-bind="attrs"
                  v-on="on"
                >
                  {{ pagination.per_page }}
                  <v-icon>mdi-chevron-down</v-icon>
                </v-btn>
              </template>

              <v-list>
                <v-list-item
                  v-for="(number, index) in itemsPerPageArray"
                  :key="index"
                  @click="callUpdateItemsPerPage(number)"
                >
                  <v-list-item-title>{{ number }}</v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>

            <v-spacer></v-spacer>

            <span
              class="mr-4
              grey--text"
            >
              Página {{ pagination.current_page }} de {{ pagination.last_page }}
            </span>
            <v-btn
              fab
              dark
              color="blue darken-3"
              class="mr-1"
              @click="formerPage"
            >
              <v-icon>mdi-chevron-left</v-icon>
            </v-btn>
            <v-btn
              fab
              dark
              color="blue darken-3"
              class="ml-1"
              @click="nextPage"
            >
              <v-icon>mdi-chevron-right</v-icon>
            </v-btn>
          </v-row>
        </template>
      </v-data-iterator>
    </v-container>
  </v-card>
</template>

<script>
  import { mapActions, mapState } from 'vuex';

  export default {
    data () {
      return {
        itemsPerPageArray: [4, 8, 12],
        search: null,
        deleting: false
      }
    },

    head() {
      return {
        title: "Usuários"
      };
    },

    computed: {
      ...mapState('users', ['users']),
      ...mapState('pagination', ['pagination']),
      
      numberOfPages () {
        return Math.ceil(this.users.length / this.pagination.per_page)
      },
    },

    created() {
      this.setLoader(true)
      this.loadUsers()
          .finally(() => this.setLoader())
    },

    methods: {
      ...mapActions('users', ['loadUsers']),
      ...mapActions('loader', ['setLoader']),
      ...mapActions('pagination', ['setPage', 'updateItemsPerPage']),

      nextPage () {
        if (this.pagination.current_page < this.pagination.last_page) {
          this.setPage(this.pagination.current_page + 1)
          this.callLoadUsers();
        }
      },

      formerPage () {
        if (this.pagination.current_page > 1) {
          this.setPage(this.pagination.current_page - 1);
          this.callLoadUsers();
        }
      },

      callUpdateItemsPerPage (number) {
        this.updateItemsPerPage(number);
        this.setPage(1);
        this.callLoadUsers();
      },

      async callLoadUsers() {
        this.setLoader(true)
        await this.loadUsers({
          search: this.search,
          page: this.pagination.current_page,
          per_page: this.pagination.per_page
        })
        this.setLoader()
      },

      destroy(userId) {
        this.deleting = true;

        this.$axios.delete(`users/${userId}`)
          .then(async () => {
            this.$toast.success('usuário deletado com sucesso')
            await this.callLoadUsers()
          })
          .catch(() => this.$toast.error('ocorreu um erro ao deletar o usuário'))
          .finally(() => {
            this.deleting = false;
          })
      },
    },
  }
</script>