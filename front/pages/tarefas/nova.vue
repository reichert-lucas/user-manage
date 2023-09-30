<template>
  <v-card class="pa-8">
    <v-container>
      <h2 class="mb-6 blue-grey--text">Nova tarefa</h2>

      <validation-observer v-slot="{ handleSubmit }" ref="form">
        <form @submit.prevent="handleSubmit(store)">
          <v-row>
            <v-col>
              <base-input 
                v-model="form.name" 
                type="text" 
                label="Nome" 
                rules="required|min:3" 
                validation-name="nome"
                class="mb-4"
              />
            </v-col>
          </v-row>

          <div class="d-flex justify-end">
            <v-btn class="mr-2" :to="{ name: 'tarefas' }">
              Voltar
            </v-btn>

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
    </v-container>
  </v-card>
</template>

<script>
export default {
  name: 'NewTask',

  auth: true,
  
  data() {
    return {
      form: {
        name: null,
      },
      loading: false,
    }
  },

  head() {
    return {
      title: "Nova tarefa"
    };
  },

  methods: {
    store() {
      this.loading = true

      this.$axios
        .post('/tasks', this.form)
        .then(() => {
          this.$toast.success('tarefa criada com sucesso')
          this.resetForm()
        })
        .catch(error => this.$returnErrorMsg(error, 'ocorreu um erro ao criar a tarefa'))
        .finally(() => {
          this.loading = false
        })
    },

    resetForm() {
      this.form = {
        name: null,
      }

      this.$refs.form.reset()
    }
  }
}
</script>
