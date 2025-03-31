<template>
    <div class="create-outer">
        <div class="create-container">
            <h2>Create A new post</h2>
            <form @submit.prevent="submitForm(form)">
                <div class="create-loc">
                    <label for="loc">Location</label>
                    <input id="loc" autofocus type="text" v-model="form.location" >
                </div>
                <div class="create-title">
                    <label for="title">Post Caption</label>
                    <input id="title" required autofocus type="text" v-model="form.postTitle" >
                </div>
                <!-- <div class="create-body">
                    <label for="body">Body</label>
                    <input id="body" required type="text" v-model="form.postBody" >
                </div> -->
                <div>
                    <input type="file" ref="photo" @input="uploadImage($event);"  accept="image/*" >
                    <div v-if="imagePreview" class="image-preview-con">
                        <img :src="imagePreview" alt="Image Preview" class="preview-image" />
                    </div>
                </div>
                <button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Create Post
                </button>
            </form>
        </div>
    </div>

    <OptionsBar :page="4" :user="this.$page.props.auth.user" />
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import OptionsBar from '@/components/custom/OptionsBar.vue';

export default {
    setup(){
        const form = useForm({
            'postTitle' : '',
            'postBody' : '',
            'image' : null,
            'location' : null,
        });

        return { form, LoaderCircle } 
    },
    name: 'CreatePost',
    data() {
        return {
            imagePreview: null, 
        };
    },
    components: {
        LoaderCircle, 
        OptionsBar,
    },
    methods: {
        uploadImage(event) {
            this.form.image = event.target.files[0];
            this.updatePreview(this.form.image);
            
        },
        submitForm (form) {
            form.post('/create', {
                onSuccess: () => {
                    form.reset()
                },                
                // Optionally handle errors
                onError: (errors) => {
                    console.log('Form submission error:', errors);
                }
            });
        },

        updatePreview(imageFile) {
            const file = imageFile; 
            if (file) {
                const reader = new FileReader();
                reader.onloadend = () => {
                    this.imagePreview = reader.result;
                };
                reader.readAsDataURL(file);
            }
        },
    },
};
</script>

<style scoped>
.create-outer {
    position: fixed;
    top: 0px;
    left: 0px;
    height: 100%;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    .create-container {
        padding: 20px;
        background-color: #007258;
        input {
            color: #000000;
            border: 2px solid #000000;
        }
        .preview-image {
            aspect-ratio: 1/1;
            height: 300px;
            width: 300px;
            object-fit: cover;
            object-position: center;
        }
    }
   
}

</style>