<template>
    <v-overlay
        :opacity="1"
        :value="loading"
        color="#2196F388"
    >
        <v-progress-circular indeterminate size="64" />
    </v-overlay>
</template>

<script>
    import { mapState } from 'vuex';

  export default {
    computed: {
      ...mapState('loader', ['loading'])
    }, 
  }
</script>