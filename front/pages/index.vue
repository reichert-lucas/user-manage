<template>
  <div>
    <v-card>
      <v-img
        height="300"
        src="https://images.unsplash.com/photo-1541701494587-cb58502866ab?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2670&q=80"
      ></v-img>
  
      <v-card-title class="blue--text">Bem-vindo, {{ $auth.user.name }}</v-card-title>
  
      <v-card-text>
        <v-row
          align="center"
          class="mx-0 mb-4"
        >
          <div>Aqui você vai poder gerenciar seus <b>usuários</b> e <b>tarefas</b>.</div>
        </v-row>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
export default {
  name: 'IndexPage',
  
  head() {
    return {
      title: "Início"
    };
  },
}
</script>