window.axios = axios.create({
    headers: {
        'X-Requested-With': 'XMLHttpRequest'
    }
});