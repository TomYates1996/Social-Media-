<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import CreatePost from '../components/custom/CreatePost.vue';
import axios from 'axios';

</script>

<template>
    <div class="page-wrap">
        <Head title="Dashboard" />
        <Link 
            v-if="$page.props.auth.user"
            href="/logout"
            method="post"
            class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
        >
            Logout
        </Link>
        <Link 
            v-if="$page.props.auth.user"
            :href="'/profile/' + $page.props.auth.user.username"
            method="get"
            class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
        >
            My Profile
        </Link>
        <Link 
            v-if="$page.props.auth.user"
            href="/explore"
            method="get"
            class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
        >
            Explore
        </Link>
        <h1>Welcome to TOMSTAGRAM</h1>
        <button class="create-post" v-on:click="showCreatePost()">Create Post</button>
        <CreatePost @showCreate="showCreate = false" v-if="showCreate"/>
        <div>
            <h1>Posts</h1>
            <ul class="posts-container" v-if="posts.length > 0">
                <PostItem v-for="post in posts" :key="post.id" :post="post" @viewPost="viewPost"/>
            </ul>
            <div v-if="posts.length === 0">
                <p>No posts available.</p>
            </div>
    </div>
    </div>
    <FullScreenPost v-if="clickedPost !== null" :post="clickedPost" @closePost="closePost"/>
    
</template>

<script>
import PostItem from '@/components/custom/PostItem.vue';
import FullScreenPost from '@/components/custom/FullScreenPost.vue';

export default {
    components: {
        PostItem,
        FullScreenPost,
    },
    props: {
        posts: Array, 
    },
    data () {
        return {
            showCreate : false,
            localPosts: [],
            clickedPost: null,
        }
    },
    mounted() {
        console.log(this.clickedPost)
    },
    methods : {
        showCreatePost(){
            this.showCreate = !this.showCreate;
        },
        viewPost(emittedPost) {
            this.clickedPost = emittedPost;
        },
        closePost() {
            console.log('hello');
            this.clickedPost = null;
        },
    }
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
    }
}

    
</style>