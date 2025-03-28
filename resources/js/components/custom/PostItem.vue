<template>
    <li class="post-item" v-on:click="viewPost()">
        <img :src="post.image" alt="">
        <h3>{{ post.title }}</h3>
        <p>{{ post.body }}</p>
        <Link 
            v-if="post.username !== $page.props.auth.user.username"
            :href="'/profile/' + post.username"
            method="get"
            class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
        >{{ post.username }}</Link>
    </li>
</template>

<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        Link,
    },
    props: {
        post: Object,
    },
    methods: {
        viewPost() {
            this.$emit('viewPost', this.post);
        },
    },
}
</script>

<style>
    .post-item {
        img {
            aspect-ratio: 1/1;
            width: 400px;
            height: 400px;
            object-fit: cover;
        }
    }
</style>