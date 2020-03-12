<template>
  <form class="mx-auto mt-5 p-2 text-center rounded border" id="authenticate-box">
    <h2>Welcome</h2>
    <p>Please enter your Shopify URL</p>
    <div class="input-group mb-2">
      <input type="text" class="form-control" placeholder="Store name" v-model="shop_name" />
      <div class="input-group-append">
        <span class="input-group-text" id="basic-addon2">.myshopify.com</span>
      </div>
    </div>
    <button type="button" class="btn btn-primary btn-block" v-on:click="login()">Login</button>
  </form>
</template>

<script>
export default {
  data: function() {
      return {
          shop_name: 'duy-store-from-fireapps'
      }
  },
  methods: {
    login: function() {
      axios.get("/api/shopify/authenticate", {
        params: {
          shop_url: this.shop_name
        }
      }).then(function(res) {
          if (res.status == 200) {
            window.location.href = res.data.redirect_url;
          } else {
            alert("Error! Please try again");
          }
      });
    }
  }
};
</script>