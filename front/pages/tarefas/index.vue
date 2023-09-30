<template>
  <v-card class="pa-8">
    <v-container fluid>
      <h2 class="mb-6 blue-grey--text">Tarefas</h2>

      <v-data-iterator
        :items="tasks"
        hide-default-footer
        no-results-text="Nenhuma tarefa encontrada"
        no-data-text="Nenhuma tarefa encontrada"
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
                @keypress.enter="callLoadTasks"
                @click:append="$router.push({ name: 'tarefas-nova' })"
              />
            </v-row>
          </v-toolbar>
        </template>

        <template #default>
          <v-row>
            <v-col
              v-for="task in tasks"
              :key="task.id"
              cols="12"
            >
              <v-card
                class="mx-auto"
                :elevation="task.status.id === 3 ? 0 : 6"
                :color="task.status.id === 3 ? 'rgba(174, 174, 174, 0.15)' : null"
                outlined
              >
                <v-list-item three-line>
                  <v-list-item-content>
                    <div class="text-overline mb-4 blue--text">
                      Tarefa
                    </div>
                    <v-list-item-title class="text-h5 mb-1">
                      {{ task.name }}
                    </v-list-item-title>
                    <v-list-item-subtitle>{{ task.status.name }}</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>

                <v-card-actions class="d-flex justify-end flex-wrap mb-2">
                  <v-btn
                    v-if="task.status.id !== 3"
                    class="ma-2 white--text"
                    :color="task.status.id === 1 ? 'green' : 'blue'"
                    :disabled="updating"
                    @click="updateTask(task)"
                  >
                    <v-icon size="20">mdi-check-circle</v-icon> 
                    {{ task.status.id === 1 ? 'Iniciar tarefa' : 'Concluir tarefa'}} 
                  </v-btn>

                  <v-btn
                    class="ma-2 white--text"
                    color="red"
                    :disabled="deleting"
                    @click="destroy(task.id)"
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
        deleting: false,
        updating: false,
      }
    },

    head() {
      return {
        title: "Tarefas"
      };
    },

    computed: {
      ...mapState('tasks', ['tasks']),
      ...mapState('pagination', ['pagination']),
      
      numberOfPages () {
        return Math.ceil(this.tasks.length / this.pagination.per_page)
      },
    },

    created() {
      this.setLoader(true)
      this.loadTasks()
          .finally(() => this.setLoader())
    },

    methods: {
      ...mapActions('tasks', ['loadTasks']),
      ...mapActions('loader', ['setLoader']),
      ...mapActions('pagination', ['setPage', 'updateItemsPerPage']),

      nextPage () {
        if (this.pagination.current_page < this.pagination.last_page) {
          this.setPage(this.pagination.current_page + 1)
          this.callLoadTasks();
        }
      },

      formerPage () {
        if (this.pagination.current_page > 1) {
          this.setPage(this.pagination.current_page - 1);
          this.callLoadTasks();
        }
      },

      callUpdateItemsPerPage (number) {
        this.updateItemsPerPage(number);
        this.setPage(1);
        this.callLoadTasks();
      },

      async callLoadTasks() {
        this.setLoader(true)
        await this.loadTasks({
          search: this.search,
          page: this.pagination.current_page,
          per_page: this.pagination.per_page
        })
        this.setLoader()
      },

      destroy(taskId) {
        this.deleting = true;

        this.$axios.delete(`tasks/${taskId}`)
          .then(async () => {
            this.$toast.success('tarefa deletada com sucesso')
            await this.callLoadTasks()
          })
          .catch(() => this.$toast.error('ocorreu um erro ao deletar a tarefa'))
          .finally(() => {
            this.deleting = false;
          })
      },

      updateTask(task) {
        if (task.status.id === 3) {
          return
        }

        this.updating = true;
        const newStatus = task.status.id + 1

        this.$axios.put(`tasks/${task.id}`, {
          status_id: newStatus
        })
        .then(async () => {
          this.$toast.success('tarefa atualizada com sucesso')
          await this.callLoadTasks()
        })
        .catch(() => this.$toast.error('ocorreu um erro ao atualizar a tarefa'))
        .finally(() => {
          this.updating = false;
        })
      },
    },
  }
</script>