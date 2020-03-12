<template>
    <div class="container">
    </div>
</template>

<script>
export default {
    mounted() {
    if (
      localStorage.is_logged == false ||
      typeof localStorage.token == "undefined" || 
      typeof localStorage.user == "undefined"
    ) {
      this.$router.push({ name: "auth.form" });

      return;
    }

    this.axios.defaults.headers.common["Authorization"] =
      "Bearer " + localStorage.token;

    this.axios.get("/api/user").then(res => {
      console.log(res.data)
    });

    this.axios.get("/api/shopify/products").then(res => {
      console.log("Data product");
      console.log(res.data);
    })

  }
}
</script>