<template>
    <div>Please wait while we log you in!</div>
</template>

<script>
export default {
    mounted() {
        if (this.$route.query.token) {
            this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$route.query.token;
        }

        this.axios.get('/api/user').then((res) => {
            console.log(res.data);

            localStorage.token = this.$route.query.token;
            localStorage.is_logged = true;
            localStorage.user = JSON.stringify(res.data);

            window.location.href = "/";
        });

        console.log(this.$route.query);
    }
}
</script>