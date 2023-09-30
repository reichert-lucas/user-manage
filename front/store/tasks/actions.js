export default {
    async loadTasks({ commit }, payload = {}) {
        return await this.$axios.get('/tasks', {
            params: payload
        })
        .then(res => {
            commit('LOAD_TASKS', res.data.data);

            const pagination = {
                "current_page": res.data.meta.current_page,
                "last_page": res.data.meta.last_page,
                "total": res.data.meta.total,
                "per_page": res.data.meta.per_page
            }

            window.$nuxt.$store.dispatch('pagination/setPagination', pagination)
        })
    },
}
