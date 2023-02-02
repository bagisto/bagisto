{{-- addImageView: function($event) {

letimage=$event.target.files[0];
if (image) {
if (image.type.includes('image/')) {
this.readFile(image, this.imageCount++);

this.items.push({'id': 'image_'+this.imageCount});

this.imageData[this.imageData.length] ='';
} else {
imageInput.value="";

alert('Only images (.jpeg, .jpg, .png, ..) are allowed.');
}
}
},


<input
type="file"
:name="[variantInputName +'[images][files]['+ index +']']"
accept="image/*"
multiple="multiple"
v-validate="'mimes:image/*'"
@change="addImageView($event)"
/>
<label class="btn btn-lg btn-primary add-image" @click="createFileType">
{{ __('admin::app.catalog.products.add-image-btn-title') }}
</label>


                    {{-- <label class="add-variant-image" @click="createFileType($event, index)">
                    <span class="add-image-icon"></span>
                        {{ __('admin::app.catalog.products.add-image-btn-title') }}
                    </label> --}} --}}




                    // createFileType: function() {
                        //     let self = this;
        
                        //     this.imageCount++;
        
                        //     this.items.push({'id': 'image_' + this.imageCount
                        //     });
        
                        //     this.imageData[this.imageData.length] = '';
        
                            
                        // },