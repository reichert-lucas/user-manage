import path from 'path'
import fs from 'fs'
require('dotenv').config({ path: `.env.${process.env.ENV_FILE}` })

export default {
  ssr: false,

  target: 'static',

  head: {
    titleTemplate: '%s - UserManager',
    title: 'UserManager',
    htmlAttrs: {
      lang: 'pt-BR',
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' },
    ],
    link: [{ rel: 'icon', href: '/logo.png' }],
  },

  css: [],

  plugins: [
    '@/plugins/mask.js',
    '@/plugins/toast-notificatios.js',
    {src: '@/plugins/vee-validate.js', mode: 'client'},
    {src: '@/plugins/returnErrorMsg.js', mode: 'client'},
    '~/plugins/vuetify.js',
  ],

  components: true,

  buildModules: [
    '@nuxtjs/eslint-module',
    '@nuxtjs/fontawesome',
    ['@nuxtjs/dotenv', { filename: `.env.${process.env.ENV_FILE}`, systemvars: true }],
    '@nuxtjs/vuetify'
  ],

  fontawesome: {
    icons:{
     solid:true,
     regular:true,
     brands:true
    }
  },

  modules: [
    '@nuxtjs/axios',
    '@nuxtjs/auth-next',
  ],

  axios: {
    baseURL: process.env.API_URL,
    https: process.env.ENV_FILE === 'production',
    headers: {
      common: {
        'Accept': 'application/json, multipart/form-data'
      },
    },
    credentials: true
  },

  auth: {
    strategies: {
      local: {
        user: { propertyName: false, autoFetch: true },
        token: {
          maxAge: 604800,
          global: true,
        },
        endpoints: {
          login: { url: '/auth/login', method: 'post', propertyName: 'token' },
          user: { url: '/auth/me', method: 'get', propertyName: 'user' },
          logout: { url: '/auth/logout', method: 'post', propertyName: 'message' }
        }
      },
    },
  },

  router: {
    middleware: ['auth']
  },

  build: {},

  server: process.env.ENV_FILE !== 'production' ? 
  {
    host: '0.0.0.0',
    https: {
      key: fs.readFileSync(path.resolve(__dirname, 'certs/server.key')),
      cert: fs.readFileSync(path.resolve(__dirname, 'certs/server.crt'))
    }
  }:
  {
    host: '0.0.0.0',
  }
}
