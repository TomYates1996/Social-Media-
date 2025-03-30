<script>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import Button from '@/components/ui/button/Button.vue';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import { LoaderCircle } from 'lucide-vue-next';


export default {
    setup(){
        const form = useForm({
            'name' : '',
            'email' : '',
            'username' : '',
            'profile_picture' : '',
        });

        return { form, LoaderCircle } 
    },
    components: {
        Button,
        SettingsLayout,
        AppLayout,
        Label,
        Input,
        DeleteUser,
        HeadingSmall,
        InputError,
        Head,
        Link,
        usePage,
        LoaderCircle,
    },
    props: {
        mustVerifyEmail: Boolean,
        status: Boolean,
    },
    data() {
        return {
            imagePreview: null,
            defaultProfileImage: '/images/default-profile.jpg',
            defaultUsed: false,
        }
    },
    created() {
        this.form.name = this.$page.props.auth.user.name;
        this.form.email = this.$page.props.auth.user.email;
        this.form.username = this.$page.props.auth.user.username;
        this.form.profile_picture = this.$page.props.auth.user.profile_picture;
        this.initProfilePicture(this.$page.props.auth.user.profile_picture);
    },
    methods: {
        submitForm(form) {
            
            form.post(route('profile.update'), {
                preserveScroll: true,
                onSuccess: () => {
                    console.log('success');
                },                
                onError: (errors) => {
                    console.log('Form submission error:', errors);
                    console.log(this.form.email);
                }
            });
        },
        clickPhoto() {
            this.$refs.photo.click();
        },
        uploadImage(event) {
            this.form.profile_picture = event.target.files[0];
            this.updatePreview(this.form.profile_picture);
        },
        initProfilePicture(filePath) {
            
            if (filePath !== null) {
                this.imagePreview = filePath;
                this.defaultUsed = false;
            } else {
                this.defaultUsed = true;
                this.imagePreview = this.defaultProfileImage;
            }

        },
        removePhoto() {
            this.imagePreview = null;
            this.form.profile_picture = null;
            this.defaultUsed = true;
        },
        updatePreview(imageFile) {
            const file = imageFile; 
            if (file) {
                const reader = new FileReader();
                reader.onloadend = () => {
                    this.imagePreview = reader.result;
                    this.defaultUsed = false;
                };
                reader.readAsDataURL(file);
            } else {
                this.imagePreview = this.defaultProfileImage;
                this.defaultUsed = true;
            }
        },
    },
}
</script>

<template>
    <AppLayout>
        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Profile information" description="Update your name and email address" />

                <form @submit.prevent="submitForm(form)" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input id="name" class="mt-1 block w-full" v-model="form.name" required autocomplete="name" placeholder="Full name" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="username">Username</Label>
                        <Input id="username" class="mt-1 block w-full" v-model="form.username" required autocomplete="username" placeholder="Username" />
                        <InputError class="mt-2" :message="form.errors.username" />
                    </div>
                    <input type="file" class="hidden" ref="photo" @input="uploadImage($event);" accept="image/*" >
                    <div v-if="imagePreview" class="image-preview-con" @click="clickPhoto()">
                        <img :src="imagePreview" alt="Image Preview" class="preview-image" />
                    </div>
                    <button @click="removePhoto()" v-if="!defaultUsed">Remove photo</button>

                    <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autocomplete="email"
                            placeholder="Email address"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="-mt-4 text-sm text-muted-foreground">
                            Your email address is unverified.
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:!decoration-current dark:decoration-neutral-500"
                            >
                                Click here to resend the verification email.
                            </Link>
                        </p>

                        <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                            A new verification link has been sent to your email address.
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">Save</Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
                        </Transition>
                    </div>
                </form>
            </div>

            <DeleteUser />
        </SettingsLayout>
    </AppLayout>
</template>

<style scoped>
    .hidden {
        display: none;
    }
    .preview-image {
        border-radius: 100%;
        height: 300px;
        width: 300px;
    }
</style>