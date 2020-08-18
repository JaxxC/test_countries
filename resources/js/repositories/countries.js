export default {
    upload() {
        return axios({
            url: `/api/file/upload`,
            method: 'GET'
        })
    },
    download(payload) {
        return axios({
            url: '/api/file/download',
            data: payload,
            method: 'POST'
        })
    }
}
