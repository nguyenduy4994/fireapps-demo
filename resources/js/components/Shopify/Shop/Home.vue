<template>
  <div class="container">
    <div class="row">
      <div class="col">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Title</th>
              <th scope="col">Vendor</th>
              <th scope="col">Message</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="products.length==0">
              <td colspan="4">Không có sản phẩm</td>
            </tr>
            <tr v-for="product in products" :key="product.id">
              <td>{{ product.id }}</td>
              <td>{{ product.title }}</td>
              <td>{{ product.vendor }}</td>
              <td>{{ product.message }}</td>
              <td>
                <button
                  class="btn btn-sm btn-primary"
                  v-on:click="showModalMessage(product)"
                >Add message</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div
      class="modal fade"
      id="messageModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="messageModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="messageModalLabel">Add message</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group">
                <label for="txt-message">Message for product {{ product.title }}:</label>
                <input id="txt-message" type="text" v-model="message" class="form-control" />
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <img src="/loading.svg" height="36px" id="loading-icon">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" v-on:click="addMessage()">
              Save changes
              </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: function() {
    return {
      products: [],
      product: {},
      message: ''
    };
  },
  mounted() {
    if (
      localStorage.is_logged == false ||
      typeof localStorage.token == "undefined" ||
      typeof localStorage.user == "undefined"
    ) {
      this.$router.push({ name: "auth.form" });

      return;
    }

    console.log($("#loading-icon"));
    $("#loading-icon").hide();

    this.axios.defaults.headers.common["Authorization"] =
      "Bearer " + localStorage.token;

    this.axios.get("/api/user").then(res => {
      console.log(res.data);
    });

    this.axios.get("/api/shopify/products").then(res => {
      this.products = res.data;
      console.log("Data product");
      console.log(res.data);
    });
  },
  methods: {
    showModalMessage: function(product) {
      this.product = product;
      this.message = product.message;
      $("#messageModal").modal({
        backdrop: 'static'
      });
    },
    addMessage: function() {
      $("#loading-icon").show();
      this.axios.post("/api/shopify/products/" + this.product.id + "/message", {
        message: this.message
      }).then((res) => {
        this.product.message = this.message;
        $("#loading-icon").hide();
        $("#messageModal").modal('hide');
      });
    }
  }
};
</script>