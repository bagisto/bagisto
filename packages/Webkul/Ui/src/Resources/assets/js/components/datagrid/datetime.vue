<template>
    <Flatpickr class="datetime-field" :options="fpOptions" />
</template>
<script>
window.Vue = require("vue");
import VueFlatpickr from "vue-flatpickr";
import "vue-flatpickr/theme/airbnb.css";

Vue.use(VueFlatpickr);

export default {
  data() {
    const now = new Date();

    return {
      fpOptions: {
        utc: false,
        defaultDate: now,
        enableTime: true
      }
    };
  },

  mounted: function() {
    console.log("Date time compoenent mounted");
  }
};
</script>
<style>
.datetime-field {
  border: 2px solid #c7c7c7;
  border-radius: 3px;
  font-family: "Montserrat", sans-serif;
  height: 35px;
  padding-left: 5px;
  color: gray;
}
</style>
