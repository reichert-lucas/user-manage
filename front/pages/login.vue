<template>
    <div class="d-flex justify-center">
        <v-card class="pa-4" elevation="24" width="600px">
            <h2 class="pa-2 mb-2 blue-grey--text">Login</h2>

            <v-row class="d-flex justify-center mb-6">
                <v-img src="/logo.png" max-width="56px"/>
            </v-row>
    
            <validation-observer v-slot="{handleSubmit}" ref="formValidator">
                <form @submit.prevent="handleSubmit(sendLogin)">
                    <base-input 
                        v-model="loginForm.email" 
                        type="email" 
                        label="E-mail"
                        rules="required|email" 
                        validation-name="email" 
                        class="mb-4"
                        autocomplete="email"
                    />
    
                    <base-input 
                        v-model="loginForm.password" 
                        type="password" 
                        label="Senha" 
                        rules="required|min:8" 
                        validation-name="senha" 
                        class="mb-4"
                        autocomplete="current-password"    
                    />
    
                    <div class="d-flex justify-end">
                        <v-btn 
                            color="light-blue accent-4"
                            prepend-icon="mdi-door"
                            class="white--text"
                            x-large
                            type="submit"
                            :disablad="loading"
                        >
                            {{ loading ? 'Entrando...' : 'Entrar' }}
                        </v-btn>
                    </div>    
                </form>
            </validation-observer>
        </v-card>
    </div>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {
    layout: 'auth',
    auth: false,

    data() {
        return {
            loginForm: {
                email: null,
                password: null
            }
        }
    },

    head() {
      return {
        title: "Login"
      };
    },

    computed: {
        ...mapState('loader', ['loading'])
    },

    created() {
        if (this.$route.query.token) {
            this.$auth.strategy.token.set(this.$route.query.token)

            this.$auth.fetchUser().then(() => {
                this.$router.push({ name: 'index' })
                this.$toast.success('login efetuado com sucesso')
            })
            .catch(() => this.$toast.error('ocorreu um erro ao efetuar o login'))
        }

    },

    methods: {
        ...mapActions('profile', ['storeUser']),
        ...mapActions('loader', ['setLoader']),

        sendLogin(){
            this.setLoader(true)

            this.$auth
                .loginWith("local", {
                    data: this.loginForm
                })
                .then(() => {
                    this.$toast.success('login efetuado com sucesso')
                    this.$router.push({ name: 'index' })
                })
                .catch((e) => this.$toast.error(`ocorreu um erro ao efetuar o login (${e.response.status})`))
                .finally(() => {
                    this.setLoader()
                })
        },
    },
}
</script>