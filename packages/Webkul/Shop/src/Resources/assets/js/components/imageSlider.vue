<template>
    <div class="slider-content">
        <ul class="slider-images">
            <li>
              <img class="slider-item" :src="images[currentIndex]" />
              <div class="show-content"></div>
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

  props:{
    slides: {
      type: Array,
      required: true,
      default: () => [],
    }
  },

  data: function() {

    return {
      images: [
        // "vendor/webkul/shop/assets/images/banner.png"
      ],
      currentIndex: 0,
      content: [],

    };
  },

  mounted(){
    this.getProps();
  },

  methods: {

    getProps() {
      this.setProps();
    },

    setProps() {
      for(var i=0;i<this.slides.length;i++) {
        this.images.push(this.slides[i].path);
        this.content.push(this.slides[i].content);
      }
      if($('.show-content').html(this.content[0]))
        console.log('content pushed');
      else
        console.log('cannot push');
    },

    changeIndexLeft: function() {
      if (this.currentIndex > 0) {
        this.currentIndex--;
        if($('.show-content').html(this.content[this.currentIndex]))
          console.log('content pushed');
        else
          console.log('cannot push');
      }

      else if(this.currentIndex == 0) {
        this.currentIndex = this.images.length-1;
        if($('.show-content').html(this.content[this.currentIndex]))
          console.log('content pushed');
        else
          console.log('cannot push');
      }

    },
    changeIndexRight: function() {
      if(this.currentIndex < this.images.length-1) {
        this.currentIndex++;
        if($('.show-content').html(this.content[this.currentIndex]))
          console.log('content pushed');
        else
          console.log('cannot push');
      }

      else if(this.currentIndex == this.images.length-1) {
        this.currentIndex = 0;
        if($('.show-content').html(this.content[this.currentIndex]))
          console.log('content pushed');
        else
          console.log('cannot push');
      }

    }
  }
};
</script>