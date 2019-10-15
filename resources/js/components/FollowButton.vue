<template>
  <div>
    <!-- v-text = buttonText is computed, based on status from follows props -->
    <button class="btn btn-primary ml-4" v-on:click="followUser" v-text="buttonText"></button>
  </div>
</template>

<script>
export default {
  props: ["userId", "follows"],
  mounted() {
    console.log("Component mounted.");
  },
  data: function() {
    return { status: this.follows };
  },
  methods: {
    followUser() {
      axios
        .post("/follow/" + this.userId)
        .then(result => {
          // when we receive the response (button clicked) we must update status to be !previousStatus
          this.status = !this.status;
          console.log(result.data);
        })
        .catch(err => {
          if (err.response.status == 401) {
            window.location = "/login";
          }
        });
    }
  },
  computed: {
    buttonText() {
      // if this.status = true (profile is followed, text = unfollow...)
      //this wont re-render UI
      return this.status ? "Unfollow" : "Follow";
    }
  }
};
</script>
