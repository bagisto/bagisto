<template>
    <div class="slider-content">
        <ul class="slider-images">
            <li>
                <img class="slider-item" :src="images[currentIndex]" />
            </li>
            <div class="slider-control">
                <span class="icon dark-left-icon slider-left" @click="changeIndexLeft"></span>
                <span class="icon light-right-icon slider-right" @click="changeIndexRight"></span>
            </div>
        </ul>
    </div>
</template>
<script>
export default {
  data: function() {
    return {
      images: [
        "vendor/webkul/shop/assets/images/banner.png",
        "vendor/webkul/shop/assets/images/1.png",
        "vendor/webkul/shop/assets/images/2.png",
        "vendor/webkul/shop/assets/images/3.png"
      ],
      currentIndex: 0
    };
  },
  mounted: function() {},
  methods: {
    changeIndexLeft: function() {
      if (this.currentIndex > 0) {
        this.currentIndex--;
      }
    },
    changeIndexRight: function() {
      if (this.currentIndex < 3) {
        this.currentIndex++;
        console.log(this.currentIndex);
      } else if (this.currentIndex == 3) {
        this.currentIndex = 0;
        console.log(this.currentIndex);
      }
    }
  }
};
</script>
