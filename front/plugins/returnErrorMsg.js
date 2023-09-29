export default (context, inject) => {
    const returnErrorMsg = (error, defaultMessage = '') => {
        
        if(error.response){
            let errorMessage = defaultMessage

            if(error.response.status === 401) {
                window.localStorage.removeItem('user-token')
                window.localStorage.removeItem('user')
                window.$nuxt._router.push({path: '/'})
            }

            if(error.response.data.message){    
                errorMessage = error.response.data.message
            }
            
            if(error.response.data.errors){
                const errors = error.response.data.errors
                const firstErroIndex = Object.keys(errors)[0]
                const errorMsg = errors[firstErroIndex][0]
    
                errorMessage = errorMsg
            }

            window.$nuxt.$toast.error(errorMessage)

            return  Promise.reject(new Error(errorMessage))
        }
    }

    inject('returnErrorMsg', returnErrorMsg)
}