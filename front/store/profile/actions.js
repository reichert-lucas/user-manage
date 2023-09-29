export default {
    async storeUser({ commit }, payload) {
        return await this.$axios.post('/auth/register', payload)
            .catch(error => this.$returnErrorMsg(error, 'ocorreu um erro ao criar o usuário'))
    },

    async updateProfile({ commit }, payload) {
        return await this.$axios.put('/auth/me', payload)
            .catch(error => this.$returnErrorMsg(error, 'ocorreu um erro ao atualizar seu perfil'))
    }
}
