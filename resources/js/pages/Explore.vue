<template>
    <div class="page-wrap">
        <Link 
            href="/dashboard"
            method="get"
            class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
        >My Feed</Link>
        <h1>See the most recent posts</h1>
        <ul class="posts-container" v-if="posts.length > 0">
            <PostItem v-for="post in posts" :key="post.id" :post="post" @viewPost="viewPost"/>
        </ul>
        <div v-if="posts.length === 0">
            <p>No posts available.</p>
        </div>
    </div>
    <FullScreenPost v-if="clickedPost !== null" :post="clickedPost" @closePost="closePost"/>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import PostItem from '@/components/custom/PostItem.vue';
import FullScreenPost from '@/components/custom/FullScreenPost.vue';

export default {
    components: {
        Link,
        PostItem,
        FullScreenPost,
    },
    props: {
        posts: Array,
    },
    data () {
        return {
            clickedPost: null,
        }
    },
    methods: {
        viewPost(emittedPost) {
            this.clickedPost = emittedPost;
        },
        closePost() {
            console.log('hello');
            this.clickedPost = null;
        },
    },
}
</script>

<style scoped>
.page-wrap {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    .posts-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        max-width: 1200px;
        gap: 20px;
        .post-item {
            img {
                aspect-ratio: 1/1;
                width: 400px;
                height: 400px;
                object-fit: cover;
            }
        }
    }
}
</style>