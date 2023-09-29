<template>
  <v-card class="pa-8">
    <v-container>
      <h2 class="mb-6 blue-grey--text">Novo usuário</h2>

      <users-form-manager />
    </v-container>
  </v-card>
</template>

<script>
export default {
  name: 'NewUser',
  auth: true,
  head() {
    return {
      title: "Novo usuário"
    };
  },
}
</script>
