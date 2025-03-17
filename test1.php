<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    let url = 'https://staging.eko.in/ekoapi/v1/pan/verify'
    axios.post(url, {
        name: 'Kamal Kr'
    }).then(result => {
        let resp = result.data;
        console.log(resp)
    }).catch(er => console.error(er))
</script>