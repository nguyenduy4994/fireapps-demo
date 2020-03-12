<template>
    
</template>

<script>
export default {
    mounted() {
        localStorage.removeItem("is_logged");
        localStorage.removeItem("token");
        localStorage.removeItem("user");

        window.location.href="/";
    }
}
</script>