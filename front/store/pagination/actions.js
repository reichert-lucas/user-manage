export default {
    setPagination ({ commit }, pagination) {
        commit('SET_PAGINATION', pagination)
    },

    async setPage({ commit, state }, page = 1) {
        const pagination = {
            "current_page": page,
            "last_page": state.pagination.last_page,
            "total": state.pagination.total,
            "per_page": state.pagination.per_page
        }
    
        await commit('SET_PAGINATION', pagination)
    },

    async updateItemsPerPage({ commit, state }, number = 8) {
        const pagination = {
            "current_page": state.pagination.current_page,
            "last_page": state.pagination.last_page,
            "total": state.pagination.total,
            "per_page": number
        }
    
        await commit('SET_PAGINATION', pagination)
    },
}