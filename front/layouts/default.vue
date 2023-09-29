<template>
  <v-app>
    <global-loader />
    <system-header />

    <v-main>
      <v-container class="pl-16 pt-14">
        <nuxt/>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {
  name: 'DefaultLayout'
}
</script>